@extends('layouts.app', ['title' => 'Dashboard'])

@section('content')
<div class="hero">
    <div class="hero-body">
        <div class="eyebrow">Student performance workspace</div>
        <div class="section-title">
            <div>
                <h1>Measure progress and target weak domains.</h1>
                <p class="hero-copy" style="margin-top: 0.5rem;">Your dashboard combines score trends, recent attempts, timing, and weak-topic signals so each next session is more deliberate.</p>
            </div>
            <a class="btn" href="{{ route('practice.index') }}">Start Practice Session</a>
        </div>
    </div>
</div>

<div class="stats-strip" style="margin-top: 1rem; margin-bottom: 1rem;">
    <div class="card">
        <div class="kicker">Attempts</div>
        <div class="metric">{{ $attemptCount }}</div>
        <p class="muted">Completed practice sessions</p>
    </div>
    <div class="card">
        <div class="kicker">Average score</div>
        <div class="metric">{{ $averageScore }}%</div>
        <p class="muted">Across all attempts</p>
    </div>
    <div class="card">
        <div class="kicker">Best score</div>
        <div class="metric">{{ $bestScore }}%</div>
        <p class="muted">Your peak performance</p>
    </div>
    <div class="card">
        <div class="kicker">Average duration</div>
        <div class="metric">{{ gmdate('i:s', $averageDuration) }}</div>
        <p class="muted">Average time spent per session</p>
    </div>
</div>

<div class="grid cols-2">
    <div class="card">
        <div class="section-title">
            <div>
                <div class="kicker">Weak-topic analysis</div>
                <h3>Topics to revisit</h3>
            </div>
        </div>
        <div class="topic-list">
            @forelse($weakTopics as $topic)
                <div class="topic-item">
                    <div class="inline-stack" style="justify-content: space-between; align-items: center;">
                        <strong>{{ $topic->category }}</strong>
                        <span class="pill danger">{{ $topic->miss_rate }}% miss rate</span>
                    </div>
                    <p class="muted" style="margin-top: 0.35rem;">{{ $topic->incorrect_answers }} incorrect out of {{ $topic->total_answers }} answers.</p>
                </div>
            @empty
                <div class="empty-state">
                    <p class="muted">No weak-topic data yet. Complete a few sessions to reveal patterns.</p>
                </div>
            @endforelse
        </div>
    </div>

    <div class="card">
        <div class="section-title">
            <div>
                <div class="kicker">Next action</div>
                <h3>Practice recommendations</h3>
            </div>
        </div>
        <div class="feature-list">
            <div class="feature-item">
                <strong>Run a timed session</strong>
                <p class="muted">Simulate exam pressure and compare your timing against the dashboard average.</p>
            </div>
            <div class="feature-item">
                <strong>Filter by category</strong>
                <p class="muted">Concentrate on the domains you miss most often before broad mixed practice.</p>
            </div>
            <div class="feature-item">
                <strong>Review explanations</strong>
                <p class="muted">Use the result breakdown after each session to close knowledge gaps immediately.</p>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="section-title">
        <div>
            <div class="kicker">Recent history</div>
            <h3>Latest attempts</h3>
        </div>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Score</th>
                    <th>Total Questions</th>
                    <th>Percent</th>
                    <th>Duration</th>
                </tr>
            </thead>
            <tbody>
                @forelse($attempts as $attempt)
                    <tr>
                        <td>{{ optional($attempt->created_at)->format('Y-m-d H:i') }}</td>
                        <td>{{ $attempt->score }}</td>
                        <td>{{ $attempt->total_questions }}</td>
                        <td>{{ round($attempt->percentage, 1) }}%</td>
                        <td>{{ $attempt->duration_seconds ? gmdate('i:s', (int) $attempt->duration_seconds) : '-' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="muted">No attempts yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
