@extends('layouts.app', ['title' => 'Question Bank'])

@section('content')
<div class="hero">
    <div class="hero-body">
        <div class="section-title">
            <div>
                <div class="eyebrow">Question operations</div>
                <h1>Manage the full question catalog.</h1>
                <p class="hero-copy" style="margin-top: 0.5rem;">Create and edit questions individually, or import a CSV when you want to load a production-size bank quickly.</p>
            </div>
            <a class="btn" href="{{ route('admin.questions.create') }}">Add Question</a>
        </div>
    </div>
</div>

<div class="stats-strip" style="margin-top: 1rem; margin-bottom: 1rem;">
    <div class="card"><div class="kicker">Total questions</div><p class="metric">{{ $questionCount }}</p><p class="muted">All stored records</p></div>
    <div class="card"><div class="kicker">Active</div><p class="metric">{{ $activeCount }}</p><p class="muted">Currently available to learners</p></div>
    <div class="card"><div class="kicker">Categories</div><p class="metric">{{ $categoryCount }}</p><p class="muted">Distinct active content domains</p></div>
    <div class="card"><div class="kicker">Bulk import</div><p class="metric">CSV</p><p class="muted">Supported via upload form below</p></div>
</div>

<div class="grid cols-2">
    <div class="card">
        <div class="section-title">
            <div>
                <div class="kicker">CSV import</div>
                <h3>Upload question bank</h3>
            </div>
        </div>
        <form method="POST" action="{{ route('admin.questions.import') }}" enctype="multipart/form-data">
            @csrf
            <label for="csv_file">CSV file</label>
            <input id="csv_file" type="file" name="csv_file" accept=".csv,text/csv" required>
            <p class="muted" style="margin-bottom: 1rem;">Required columns: question_text, option_a, option_b, option_c, option_d, correct_option. Optional: explanation, category, is_active.</p>
            <button class="btn warning" type="submit">Import CSV</button>
        </form>
    </div>
    <div class="card">
        <div class="section-title">
            <div>
                <div class="kicker">Formatting guide</div>
                <h3>Import expectations</h3>
            </div>
        </div>
        <div class="feature-list">
            <div class="feature-item">
                <strong>Correct option values</strong>
                <p class="muted">Use a, b, c, or d in the correct_option column.</p>
            </div>
            <div class="feature-item">
                <strong>Activation</strong>
                <p class="muted">Set is_active to 1, true, yes, or active to publish immediately.</p>
            </div>
            <div class="feature-item">
                <strong>Categories</strong>
                <p class="muted">Use category names like AWS, Azure, GCP, Networking, or Security to support filtered practice.</p>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="section-title">
        <div>
            <div class="kicker">Question table</div>
            <h3>Current catalog</h3>
        </div>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Category</th>
                    <th>Question</th>
                    <th>Correct</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($questions as $question)
                    <tr>
                        <td>{{ $question->id }}</td>
                        <td>{{ $question->category ?? '-' }}</td>
                        <td>{{ \Illuminate\Support\Str::limit($question->question_text, 90) }}</td>
                        <td>{{ strtoupper($question->correct_option) }}</td>
                        <td>
                            <span class="pill {{ $question->is_active ? '' : 'danger' }}">
                                {{ $question->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <div class="row-actions">
                                <a class="btn secondary" href="{{ route('admin.questions.edit', $question) }}">Edit</a>
                                <form method="POST" action="{{ route('admin.questions.destroy', $question) }}" onsubmit="return confirm('Delete this question?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn danger" type="submit">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="muted">No questions added yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top:1rem;">{{ $questions->links() }}</div>
</div>
@endsection
