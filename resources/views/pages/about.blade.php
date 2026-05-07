@extends('layouts.app', ['title' => 'About Us'])

@section('content')
<div class="hero">
    <div class="hero-body grid cols-2" style="align-items: center;">
        <div>
            <div class="eyebrow">About Passmark</div>
            <h1 class="hero-title">Built to make cloud certification prep measurable and motivating.</h1>
            <p class="hero-copy">Passmark was designed for teams and independent learners who want more than static question dumps. We combine structured question banks, timed simulations, and simple analytics so every study session has direction.</p>
        </div>
        <div>
            <img class="media-photo" src="https://picsum.photos/seed/passmark-about/1200/700" alt="Cloud training team collaboration">
        </div>
    </div>
</div>

<div class="grid cols-3" style="margin-top: 1rem;">
    <div class="card">
        <div class="kicker">Mission</div>
        <h3>Confidence before exam day</h3>
        <p class="muted">We help learners focus on weak areas, practice under pressure, and improve with clear feedback.</p>
    </div>
    <div class="card">
        <div class="kicker">Approach</div>
        <h3>Simple workflows</h3>
        <p class="muted">Admins manage questions easily. Students get polished sessions with immediate insight.</p>
    </div>
    <div class="card">
        <div class="kicker">Stack</div>
        <h3>Laravel + MySQL</h3>
        <p class="muted">A practical architecture that can run smoothly on common hosting platforms.</p>
    </div>
</div>
@endsection
