<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\QuizAttempt;
use App\Models\QuizAttemptAnswer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class PracticeController extends Controller
{
    private const TRIAL_ATTEMPT_LIMIT = 2;
    private const PREMIUM_PRICE_LABEL = '£5/month';

    public function index(Request $request): View
    {
        $user = $request->user();
        $selectedCategory = $request->string('category')->toString();
        $timeLimit = (int) $request->integer('time_limit', 15);
        $questionCount = (int) $request->integer('question_count', 20);
        $lessonLoaded = $request->boolean('lesson_loaded');
        $attemptCount = $user->quizAttempts()->count();
        $hasPremiumAccess = $user->hasPremiumAccess();
        $trialAttemptsRemaining = max(self::TRIAL_ATTEMPT_LIMIT - $attemptCount, 0);
        $upgradeRequired = ! $hasPremiumAccess && $trialAttemptsRemaining === 0;

        if (! in_array($questionCount, [10, 20], true)) {
            $questionCount = 20;
        }

        $questions = collect();

        if ($lessonLoaded && ! $upgradeRequired) {
            $questionQuery = Question::query()
                ->where('is_active', true)
                ->when($selectedCategory !== '', function ($query) use ($selectedCategory): void {
                    $query->where('category', $selectedCategory);
                });

            $questions = $questionQuery
                ->inRandomOrder()
                ->limit($questionCount)
                ->get();
        }

        $categories = Question::query()
            ->where('is_active', true)
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->orderBy('category')
            ->pluck('category')
            ->unique()
            ->values();

        return view('practice.index', compact(
            'questions',
            'categories',
            'selectedCategory',
            'timeLimit',
            'questionCount',
            'lessonLoaded',
            'attemptCount',
            'trialAttemptsRemaining',
            'hasPremiumAccess',
            'upgradeRequired'
        ));
    }

    public function submit(Request $request): View|RedirectResponse
    {
        $user = $request->user();
        if (! $user->hasPremiumAccess() && $user->quizAttempts()->count() >= self::TRIAL_ATTEMPT_LIMIT) {
            return redirect()
                ->route('practice.index')
                ->with('status', 'Your free 2-attempt trial has ended. Upgrade to Premium (' . self::PREMIUM_PRICE_LABEL . ') to continue.');
        }

        $validated = $request->validate([
            'question_ids' => ['required', 'array', 'min:1'],
            'question_ids.*' => ['integer', 'exists:questions,id'],
            'answers' => ['nullable', 'array'],
            'answers.*' => ['nullable', 'in:a,b,c,d'],
            'elapsed_seconds' => ['nullable', 'integer', 'min:0'],
            'time_limit_minutes' => ['nullable', 'integer', 'min:1', 'max:180'],
        ]);

        $questions = Question::query()
            ->whereIn('id', $validated['question_ids'])
            ->get()
            ->keyBy('id');

        if ($questions->isEmpty()) {
            return redirect()->route('practice.index')->with('status', 'No valid questions were submitted.');
        }

        $answers = $validated['answers'] ?? [];
        $elapsedSeconds = (int) ($validated['elapsed_seconds'] ?? 0);
        $timeLimitMinutes = (int) ($validated['time_limit_minutes'] ?? 15);
        $score = 0;
        $resultRows = [];
        $missedCategories = [];

        DB::transaction(function () use ($request, $questions, $answers, $elapsedSeconds, &$score, &$resultRows, &$missedCategories): void {
            $attempt = QuizAttempt::create([
                'user_id' => $request->user()->id,
                'score' => 0,
                'total_questions' => $questions->count(),
                'percentage' => 0,
                'duration_seconds' => $elapsedSeconds,
                'started_at' => Carbon::now()->subSeconds($elapsedSeconds),
                'completed_at' => Carbon::now(),
            ]);

            foreach ($questions as $question) {
                $selected = $answers[$question->id] ?? null;
                $isCorrect = $selected !== null && $selected === $question->correct_option;

                if ($isCorrect) {
                    $score++;
                } else {
                    $category = $question->category ?: 'Uncategorized';
                    $missedCategories[$category] = ($missedCategories[$category] ?? 0) + 1;
                }

                QuizAttemptAnswer::create([
                    'attempt_id' => $attempt->id,
                    'question_id' => $question->id,
                    'selected_option' => $selected,
                    'is_correct' => $isCorrect,
                ]);

                $resultRows[] = [
                    'question' => $question,
                    'selected' => $selected,
                    'is_correct' => $isCorrect,
                ];
            }

            $percentage = round(($score / max($questions->count(), 1)) * 100, 1);
            $attempt->update([
                'score' => $score,
                'percentage' => $percentage,
            ]);
        });

        $totalQuestions = $questions->count();
        $percentage = round(($score / max($totalQuestions, 1)) * 100, 1);
        arsort($missedCategories);
        $canUseAiAssistant = $user->hasPremiumAccess();
        $premiumPriceLabel = self::PREMIUM_PRICE_LABEL;

        return view('practice.results', compact(
            'resultRows',
            'score',
            'totalQuestions',
            'percentage',
            'elapsedSeconds',
            'timeLimitMinutes',
            'missedCategories',
            'canUseAiAssistant',
            'premiumPriceLabel'
        ));
    }

    public function activatePremium(Request $request): RedirectResponse
    {
        $request->user()->update([
            'is_premium' => true,
            'premium_ends_at' => Carbon::now()->addMonth(),
        ]);

        return redirect()
            ->route('practice.index')
            ->with('status', 'Premium activated. You now have unlimited attempts and AI assistance.');
    }

    public function aiExplain(Request $request): JsonResponse
    {
        if (! $request->user()->hasPremiumAccess()) {
            return response()->json([
                'message' => 'AI assistance is available for Premium members only.',
            ], 403);
        }

        $validated = $request->validate([
            'question' => ['required', 'string', 'max:1200'],
            'selected' => ['nullable', 'string', 'max:20'],
            'correct' => ['required', 'string', 'max:20'],
            'explanation' => ['nullable', 'string', 'max:2000'],
        ]);

        $apiKey = (string) config('services.huggingface.api_key', '');
        $model = (string) config('services.huggingface.model', 'Qwen/Qwen2.5-7B-Instruct');
        $configuredEndpoint = (string) config('services.huggingface.endpoint', '');
        $apiUrl = $configuredEndpoint !== ''
            ? $configuredEndpoint
            : 'https://router.huggingface.co/v1/chat/completions';

        if ($apiKey === '') {
            return response()->json([
                'message' => 'AI assistant is not configured yet. Add HUGGINGFACE_API_KEY to your environment.',
            ], 503);
        }

        $prompt = "You are a cloud certification tutor. Explain this question simply in 4-6 short bullet points.\n"
            . "Question: {$validated['question']}\n"
            . "Student answer: " . ($validated['selected'] ?? 'No answer') . "\n"
            . "Correct answer: {$validated['correct']}\n"
            . "Known explanation: " . ($validated['explanation'] ?? 'None provided') . "\n"
            . "Focus on why the correct answer is right and how to avoid this mistake next time.";

        try {
            $response = Http::withToken($apiKey)
                ->timeout(45)
                ->post($apiUrl, [
                    'model' => $model,
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => 'You are a concise cloud certification tutor.',
                        ],
                        [
                            'role' => 'user',
                            'content' => $prompt,
                        ],
                    ],
                    'max_tokens' => 240,
                    'temperature' => 0.3,
                ]);

            if (! $response->successful()) {
                return response()->json([
                    'message' => 'AI assistant is temporarily unavailable. Please try again.',
                ], 502);
            }

            $payload = $response->json();
            $text = '';

            if (is_array($payload)
                && isset($payload['choices'][0]['message']['content'])
                && is_string($payload['choices'][0]['message']['content'])) {
                $text = trim($payload['choices'][0]['message']['content']);
            }

            if ($text === '') {
                $text = 'I could not generate an explanation for this question yet. Please try again.';
            }

            return response()->json(['answer' => $text]);
        } catch (\Throwable $exception) {
            return response()->json([
                'message' => 'AI assistant request failed. Please retry in a moment.',
            ], 500);
        }
    }
}
