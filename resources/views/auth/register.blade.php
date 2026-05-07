@extends('layouts.app', ['title' => 'Register'])

@section('content')
<div class="auth-shell">
    <div class="card auth-panel">
        <div class="eyebrow">New learner setup</div>
        <h1>Create your student account.</h1>
        <p class="hero-copy" style="margin-bottom: 1.5rem;">Join the platform to run guided practice sessions and build a trackable revision history.</p>

        <form method="POST" action="{{ route('register.store') }}">
            @csrf
            <label>Name</label>
            <input type="text" name="name" value="{{ old('name') }}" required>
            @error('name')<div class="error">{{ $message }}</div>@enderror

            <label>Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required>
            @error('email')<div class="error">{{ $message }}</div>@enderror

            <label>Password</label>
            <input type="password" name="password" required>
            @error('password')<div class="error">{{ $message }}</div>@enderror

            <label>Confirm password</label>
            <input type="password" name="password_confirmation" required>

            <button class="btn" type="submit">Create Account</button>
        </form>
    </div>

    <div class="card hero-mini auth-panel">
        <div class="kicker" style="color: rgba(255,255,255,0.7);">What you get</div>
        <h3 style="font-size: 2rem; margin-top: 0.45rem;">A clean practice workflow from first session to exam day.</h3>
        <div class="feature-list" style="margin-top: 1.25rem;">
            <div class="feature-item" style="background: rgba(255,255,255,0.1); border-color: rgba(255,255,255,0.14);">
                <strong>Progress dashboard</strong>
                <p class="muted">Track recent attempts, averages, and best score.</p>
            </div>
            <div class="feature-item" style="background: rgba(255,255,255,0.1); border-color: rgba(255,255,255,0.14);">
                <strong>Category focus</strong>
                <p class="muted">Drill into AWS, Azure, GCP, networking, or any custom domain.</p>
            </div>
        </div>
    </div>
</div>
@endsection
