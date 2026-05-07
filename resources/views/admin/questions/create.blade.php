@extends('layouts.app', ['title' => 'Create Question'])

@section('content')
<div class="card">
    <div class="section-title">
        <div>
            <div class="eyebrow">Question authoring</div>
            <h1>Add a new question</h1>
            <p class="muted">Create a single question record with four options, the correct answer, and an optional explanation.</p>
        </div>
    </div>
    <form method="POST" action="{{ route('admin.questions.store') }}">
        @include('admin.questions._form')
    </form>
</div>
@endsection
