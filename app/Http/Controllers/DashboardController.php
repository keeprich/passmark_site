<?php

namespace App\Http\Controllers;

use App\Models\QuizAttemptAnswer;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $user = $request->user();
        $attempts = $user->quizAttempts()->latest()->limit(10)->get();

        $attemptCount = $user->quizAttempts()->count();
        $averageScore = round((float) ($user->quizAttempts()->avg('percentage') ?? 0), 1);
        $bestScore = round((float) ($user->quizAttempts()->max('percentage') ?? 0), 1);
        $averageDuration = (int) round((float) ($user->quizAttempts()->avg('duration_seconds') ?? 0));

        $weakTopics = QuizAttemptAnswer::query()
            ->join('quiz_attempts', 'quiz_attempts.id', '=', 'quiz_attempt_answers.attempt_id')
            ->join('questions', 'questions.id', '=', 'quiz_attempt_answers.question_id')
            ->where('quiz_attempts.user_id', $user->id)
            ->selectRaw("COALESCE(NULLIF(questions.category, ''), 'Uncategorized') as category")
            ->selectRaw('COUNT(*) as total_answers')
            ->selectRaw('SUM(CASE WHEN quiz_attempt_answers.is_correct = 0 THEN 1 ELSE 0 END) as incorrect_answers')
            ->selectRaw('ROUND((SUM(CASE WHEN quiz_attempt_answers.is_correct = 0 THEN 1 ELSE 0 END) / COUNT(*)) * 100, 1) as miss_rate')
            ->groupBy(DB::raw("COALESCE(NULLIF(questions.category, ''), 'Uncategorized')"))
            ->orderByDesc('miss_rate')
            ->orderByDesc('incorrect_answers')
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'attempts',
            'attemptCount',
            'averageScore',
            'bestScore',
            'averageDuration',
            'weakTopics'
        ));
    }
}
