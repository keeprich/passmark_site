@extends('layouts.app', ['title' => 'Contact'])

@section('content')
<div class="hero">
    <div class="hero-body">
        <div class="eyebrow">Contact Passmark</div>
        <h1>Reach out for onboarding, content imports, or support.</h1>
        <p class="hero-copy">Use the details below or send a message through the form. This is a sample contact page ready for your production inbox integration.</p>
    </div>
</div>

<div class="grid cols-2" style="margin-top: 1rem;">
    <div class="card">
        <div class="kicker">Contact details</div>
        <div class="feature-list" style="margin-top: 0.7rem;">
            <div class="feature-item"><strong>Email</strong><p class="muted">support@passmark.app</p></div>
            <div class="feature-item"><strong>Sales</strong><p class="muted">sales@passmark.app</p></div>
            <div class="feature-item"><strong>Hours</strong><p class="muted">Mon-Fri, 09:00-18:00 UTC</p></div>
        </div>
    </div>

    <div class="card">
        <div class="kicker">Message form</div>
        <form>
            <label>Name</label>
            <input type="text" placeholder="Your full name">

            <label>Email</label>
            <input type="email" placeholder="you@example.com">

            <label>Message</label>
            <textarea placeholder="Tell us what you need help with"></textarea>

            <button class="btn warning" type="button">Send Message</button>
        </form>
    </div>
</div>
@endsection
