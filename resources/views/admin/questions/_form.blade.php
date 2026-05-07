@csrf

@if ($errors->any())
    <div class="error">
        @foreach ($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
    </div>
@endif

<div class="grid cols-2">
    <div>
        <label>Category</label>
        <input type="text" name="category" value="{{ old('category', $question->category ?? '') }}" placeholder="e.g. AWS, Azure, GCP">
    </div>
    <div>
        <label>Correct Option</label>
        <select name="correct_option" required>
            @foreach(['a' => 'A', 'b' => 'B', 'c' => 'C', 'd' => 'D'] as $value => $label)
                <option value="{{ $value }}" @selected(old('correct_option', $question->correct_option ?? '') === $value)>
                    {{ $label }}
                </option>
            @endforeach
        </select>
    </div>
</div>

<label>Question</label>
<textarea name="question_text" rows="4" required>{{ old('question_text', $question->question_text ?? '') }}</textarea>

<div class="grid cols-2">
    <div>
        <label>Option A</label>
        <input type="text" name="option_a" value="{{ old('option_a', $question->option_a ?? '') }}" required>
    </div>
    <div>
        <label>Option B</label>
        <input type="text" name="option_b" value="{{ old('option_b', $question->option_b ?? '') }}" required>
    </div>
    <div>
        <label>Option C</label>
        <input type="text" name="option_c" value="{{ old('option_c', $question->option_c ?? '') }}" required>
    </div>
    <div>
        <label>Option D</label>
        <input type="text" name="option_d" value="{{ old('option_d', $question->option_d ?? '') }}" required>
    </div>
</div>

<label>Explanation (optional)</label>
<textarea name="explanation" rows="3">{{ old('explanation', $question->explanation ?? '') }}</textarea>

<label style="display:flex;align-items:center;gap:0.5rem;font-weight:400;">
    <input type="checkbox" name="is_active" value="1" style="width:auto;margin:0;" @checked(old('is_active', $question->is_active ?? true))>
    Active and visible to learners
</label>

<button class="btn" type="submit">Save Question</button>
