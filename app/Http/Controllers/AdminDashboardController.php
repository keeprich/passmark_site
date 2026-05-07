<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\QuizAttempt;
use App\Models\User;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function __invoke(): View
    {
        $metrics = [
            'users' => User::count(),
            'questions' => Question::count(),
            'attempts' => QuizAttempt::count(),
            'avg_score' => round((float) (QuizAttempt::avg('percentage') ?? 0), 1),
        ];

        $recentAttempts = QuizAttempt::query()
            ->with('user')
            ->latest()
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact('metrics', 'recentAttempts'));
    }
}
