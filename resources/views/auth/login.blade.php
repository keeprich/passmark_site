@extends('layouts.app', ['title' => 'Login'])

@section('content')
<div class="auth-shell">
    <div class="card auth-panel">
        <div class="eyebrow">Welcome back</div>
        <h1>Sign in to continue your exam prep.</h1>
        <p class="hero-copy" style="margin-bottom: 1.5rem;">Access your dashboard, review weak topics, and continue timed practice sessions.</p>

        <form method="POST" action="{{ route('login.attempt') }}">
            @csrf
            <label>Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required>
            @error('email')<div class="error">{{ $message }}</div>@enderror

            <label>Password</label>
            <input type="password" name="password" required>
            @error('password')<div class="error">{{ $message }}</div>@enderror

            <label style="display:flex;align-items:center;gap:0.5rem;font-weight:400;">
                <input type="checkbox" name="remember" style="width:auto;margin:0;"> Remember me
            </label>

            <button class="btn" type="submit">Login</button>
        </form>
    </div>

    <div class="card hero-mini auth-panel">
        <div class="kicker" style="color: rgba(255,255,255,0.7);">Platform benefits</div>
        <h3 style="font-size: 2rem; margin-top: 0.45rem;">Track score, pace, and missed domains in one place.</h3>
        <div class="feature-list" style="margin-top: 1.25rem;">
            <div class="feature-item" style="background: rgba(255,255,255,0.1); border-color: rgba(255,255,255,0.14);">
                <strong>Timed practice</strong>
                <p class="muted">Simulate exam pressure with controlled session lengths.</p>
            </div>
            <div class="feature-item" style="background: rgba(255,255,255,0.1); border-color: rgba(255,255,255,0.14);">
                <strong>Weak-topic insights</strong>
                <p class="muted">See which categories need a deeper review.</p>
            </div>
        </div>
    </div>
</div>
@endsection
