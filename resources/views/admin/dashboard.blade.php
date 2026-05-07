@extends('layouts.app', ['title' => 'Admin Dashboard'])

@section('content')
<div class="hero">
    <div class="hero-body">
        <div class="eyebrow">Admin workspace</div>
        <div class="section-title">
            <div>
                <h1>Operate the question bank and monitor learner activity.</h1>
                <p class="hero-copy" style="margin-top: 0.5rem;">Use the admin area to manage question supply, bulk upload CSV content, and keep an eye on attempt volume and scoring trends.</p>
            </div>
            <a class="btn" href="{{ route('admin.questions.index') }}">Manage Questions</a>
        </div>
    </div>
</div>

<div class="stats-strip" style="margin-top: 1rem; margin-bottom: 1rem;">
    <div class="card"><div class="kicker">Users</div><p class="metric">{{ $metrics['users'] }}</p><p class="muted">Registered accounts</p></div>
    <div class="card"><div class="kicker">Questions</div><p class="metric">{{ $metrics['questions'] }}</p><p class="muted">Total question bank size</p></div>
    <div class="card"><div class="kicker">Attempts</div><p class="metric">{{ $metrics['attempts'] }}</p><p class="muted">Practice sessions completed</p></div>
    <div class="card"><div class="kicker">Average Score</div><p class="metric">{{ $metrics['avg_score'] }}%</p><p class="muted">Across all student attempts</p></div>
</div>

<div class="grid cols-2">
    <div class="card">
        <div class="kicker">Operations</div>
        <div class="feature-list" style="margin-top: 0.75rem;">
            <div class="feature-item">
                <strong>Question lifecycle</strong>
                <p class="muted">Create, edit, deactivate, and bulk import questions from CSV.</p>
            </div>
            <div class="feature-item">
                <strong>Catalog quality</strong>
                <p class="muted">Use categories to keep content segmented by provider, service, or certification domain.</p>
            </div>
            <div class="feature-item">
                <strong>Learner oversight</strong>
                <p class="muted">Review recent attempt activity and verify that students are using the platform.</p>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="kicker">Recommended next step</div>
        <h3 style="margin-top: 0.35rem;">Import your production question bank</h3>
        <p class="muted" style="margin-top: 0.6rem;">Head into the question manager and upload a CSV with question text, four options, the correct answer, and optional explanation and category fields.</p>
    </div>
</div>

<div class="card">
    <div class="section-title">
        <div>
            <div class="kicker">Recent attempts</div>
            <h3>Latest student activity</h3>
        </div>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>User</th><th>Date</th><th>Score</th><th>Percent</th></tr>
            </thead>
            <tbody>
                @forelse($recentAttempts as $attempt)
                    <tr>
                        <td>{{ $attempt->user->name ?? 'N/A' }}</td>
                        <td>{{ optional($attempt->created_at)->format('Y-m-d H:i') }}</td>
                        <td>{{ $attempt->score }} / {{ $attempt->total_questions }}</td>
                        <td>{{ round($attempt->percentage, 1) }}%</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="muted">No attempts yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
