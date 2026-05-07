@extends('layouts.app', ['title' => 'Home'])

@section('content')
<div class="hero">
    <div class="hero-body grid cols-2" style="align-items: center;">
        <div>
            <div class="eyebrow">Passmark cloud exam platform</div>
            <h1 class="hero-title">Professional cloud certification practice, built for momentum.</h1>
            <p class="hero-copy">Passmark helps learners train with timed sessions, structured category drills, and score analytics while admins manage a production-ready Q&A bank.</p>
            <div class="inline-stack" style="margin-top: 1.2rem;">
                @auth
                    <a class="btn" href="{{ route('practice.index') }}">Start Practice</a>
                    <a class="btn secondary" href="{{ route('dashboard') }}">Open Dashboard</a>
                @else
                    <a class="btn" href="{{ route('register') }}">Create Account</a>
                    <a class="btn secondary" href="{{ route('login') }}">Sign In</a>
                @endauth
            </div>
            <div class="inline-stack" style="margin-top: 0.9rem;">
                <span class="pill">Timed sessions</span>
                <span class="pill warning">10/20 question chunks</span>
                <span class="pill">Weak-topic insight</span>
            </div>
        </div>
        <div>
            <img class="media-photo" src="https://picsum.photos/seed/passmark-hero-1/1200/700" alt="Developer workstation with cloud architecture visuals">
            <img class="media-photo" src="https://picsum.photos/seed/passmark-hero-2/1200/700" alt="Team planning certification learning roadmap">
        </div>
    </div>
</div>

<div class="grid cols-3" style="margin-top: 1rem;">
    <div class="card">
        <img class="media-photo" src="https://picsum.photos/seed/passmark-feature-1/1200/700" alt="Circuit board and cloud infrastructure concept">
        <div class="kicker">Practice Experience</div>
        <h3>Per-question confidence flow</h3>
        <p class="muted">Learners immediately see correct or incorrect feedback before advancing to the next question.</p>
    </div>
    <div class="card">
        <img class="media-photo" src="https://picsum.photos/seed/passmark-feature-2/1200/700" alt="Performance analytics dashboard visual">
        <div class="kicker">Learner Analytics</div>
        <h3>Track score and pacing</h3>
        <p class="muted">Monitor average score, best score, recent attempts, and timing to refine exam readiness.</p>
    </div>
    <div class="card">
        <img class="media-photo" src="https://picsum.photos/seed/passmark-feature-3/1200/700" alt="Security and cloud data stream illustration">
        <div class="kicker">Admin Operations</div>
        <h3>Bulk upload + control</h3>
        <p class="muted">Import CSV question banks, segment by category, and keep content active or inactive by exam cycle.</p>
    </div>
</div>

<div class="grid cols-2" style="margin-top: 1rem;">
    <div class="card">
        <div class="kicker">What is included</div>
        <div class="feature-list" style="margin-top: 0.65rem;">
            <div class="feature-item"><strong>Student portal</strong><p class="muted">Dashboard, history, timed practice, and answer review.</p></div>
            <div class="feature-item"><strong>Admin portal</strong><p class="muted">Question creation, edit/delete, CSV import, and usage monitoring.</p></div>
            <div class="feature-item"><strong>Hostinger-friendly stack</strong><p class="muted">Laravel + MySQL with straightforward deployment flow.</p></div>
        </div>
    </div>
    <div class="card">
        <div class="kicker">Demo login credentials</div>
        <div class="feature-list" style="margin-top: 0.65rem;">
            <div class="feature-item"><strong>Admin</strong><p class="muted">admin@example.com / password123</p></div>
            <div class="feature-item"><strong>Student</strong><p class="muted">student@example.com / password123</p></div>
            <div class="feature-item"><strong>Need details?</strong><p class="muted">See About Us, Contact, and Blog pages from the top navigation.</p></div>
        </div>
    </div>
</div>
@endsection
