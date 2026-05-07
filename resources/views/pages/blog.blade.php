@extends('layouts.app', ['title' => 'Blog'])

@section('content')
<div class="hero">
    <div class="hero-body grid cols-2" style="align-items: center;">
        <div>
            <div class="eyebrow">Passmark Blog</div>
            <h1>Insights for certification teams and individual learners.</h1>
            <p class="hero-copy">A sample editorial page you can expand with real posts, categories, and an admin publishing workflow.</p>
        </div>
        <div>
            <img class="media-photo" src="https://picsum.photos/seed/passmark-blog/1200/700" alt="Student studying cloud certification notes">
        </div>
    </div>
</div>

<div class="grid cols-3" style="margin-top: 1rem;">
    <article class="card">
        <div class="kicker">Study strategy</div>
        <h3>How to use 10-question sprint sessions effectively</h3>
        <p class="muted">Short sessions are ideal for daily retention checks and faster topic reinforcement.</p>
    </article>
    <article class="card">
        <div class="kicker">Exam readiness</div>
        <h3>Timing benchmarks for cloud certification practice</h3>
        <p class="muted">Use average duration analytics to compare pace over time and identify pressure points.</p>
    </article>
    <article class="card">
        <div class="kicker">Admin workflow</div>
        <h3>Building a reliable question import pipeline</h3>
        <p class="muted">Standardize CSV templates with categories and explanations for consistent quality.</p>
    </article>
</div>
@endsection
