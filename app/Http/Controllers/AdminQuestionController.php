<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminQuestionController extends Controller
{
    public function index(): View
    {
        $questions = Question::query()->latest()->paginate(20);
        $questionCount = Question::count();
        $activeCount = Question::where('is_active', true)->count();
        $categoryCount = Question::query()
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct('category')
            ->count('category');

        return view('admin.questions.index', compact('questions', 'questionCount', 'activeCount', 'categoryCount'));
    }

    public function create(): View
    {
        return view('admin.questions.create');
    }

    public function store(Request $request): RedirectResponse
    {
        Question::create($this->validatedData($request));

        return redirect()->route('admin.questions.index')->with('status', 'Question created.');
    }

    public function edit(Question $question): View
    {
        return view('admin.questions.edit', compact('question'));
    }

    public function update(Request $request, Question $question): RedirectResponse
    {
        $question->update($this->validatedData($request));

        return redirect()->route('admin.questions.index')->with('status', 'Question updated.');
    }

    public function destroy(Question $question): RedirectResponse
    {
        $question->delete();

        return redirect()->route('admin.questions.index')->with('status', 'Question deleted.');
    }

    public function import(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'csv_file' => ['required', 'file', 'mimes:csv,txt'],
        ]);

        $handle = fopen($validated['csv_file']->getRealPath(), 'r');

        if (! $handle) {
            return back()->with('status', 'Unable to read the uploaded CSV file.');
        }

        $header = fgetcsv($handle);

        if (! is_array($header)) {
            fclose($handle);

            return back()->with('status', 'The CSV file is empty.');
        }

        $normalizedHeader = array_map(fn ($value) => Str::of((string) $value)->trim()->lower()->toString(), $header);
        $requiredColumns = ['question_text', 'option_a', 'option_b', 'option_c', 'option_d', 'correct_option'];

        foreach ($requiredColumns as $requiredColumn) {
            if (! in_array($requiredColumn, $normalizedHeader, true)) {
                fclose($handle);

                return back()->with('status', 'CSV import failed. Missing required column: '.$requiredColumn);
            }
        }

        $createdCount = 0;

        while (($row = fgetcsv($handle)) !== false) {
            if (count(array_filter($row, fn ($value) => trim((string) $value) !== '')) === 0) {
                continue;
            }

            $record = [];

            foreach ($normalizedHeader as $index => $column) {
                $record[$column] = trim((string) ($row[$index] ?? ''));
            }

            if (($record['question_text'] ?? '') === '') {
                continue;
            }

            Question::create([
                'question_text' => $record['question_text'],
                'option_a' => $record['option_a'] ?? '',
                'option_b' => $record['option_b'] ?? '',
                'option_c' => $record['option_c'] ?? '',
                'option_d' => $record['option_d'] ?? '',
                'correct_option' => Str::lower($record['correct_option'] ?? 'a'),
                'explanation' => $record['explanation'] ?? null,
                'category' => $record['category'] ?? null,
                'is_active' => ! isset($record['is_active']) || in_array(Str::lower($record['is_active']), ['1', 'true', 'yes', 'active'], true),
            ]);

            $createdCount++;
        }

        fclose($handle);

        return redirect()->route('admin.questions.index')->with('status', "Imported {$createdCount} question(s) from CSV.");
    }

    private function validatedData(Request $request): array
    {
        $validated = $request->validate([
            'question_text' => ['required', 'string'],
            'option_a' => ['required', 'string'],
            'option_b' => ['required', 'string'],
            'option_c' => ['required', 'string'],
            'option_d' => ['required', 'string'],
            'correct_option' => ['required', 'in:a,b,c,d'],
            'explanation' => ['nullable', 'string'],
            'category' => ['nullable', 'string', 'max:120'],
            'is_active' => ['nullable', 'in:1'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        return $validated;
    }
}
