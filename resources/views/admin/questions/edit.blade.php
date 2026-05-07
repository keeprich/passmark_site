@extends('layouts.app', ['title' => 'Edit Question'])

@section('content')
<div class="card">
    <div class="section-title">
        <div>
            <div class="eyebrow">Question maintenance</div>
            <h1>Edit question</h1>
            <p class="muted">Update wording, explanation, category, or activation state for this record.</p>
        </div>
    </div>
    <form method="POST" action="{{ route('admin.questions.update', $question) }}">
        @method('PUT')
        @include('admin.questions._form')
    </form>
</div>
@endsection
