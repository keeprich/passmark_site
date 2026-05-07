@extends('layouts.app', ['title' => 'Practice'])

@section('content')
@if($upgradeRequired)
    <div class="hero">
        <div class="hero-body">
            <div class="eyebrow">Trial complete</div>
            <h1>Your 2 free attempts are used.</h1>
            <p class="hero-copy">Upgrade to Premium for £5/month to unlock unlimited practice sessions and AI assistance for any question you do not understand.</p>
            <div class="inline-stack" style="margin-top: 1rem;">
                <span class="pill danger">Attempts used: {{ $attemptCount }}</span>
                <span class="pill warning">Premium: £5/month</span>
            </div>
            <div class="inline-stack" style="margin-top: 1rem;">
                <form method="POST" action="{{ route('practice.upgrade') }}" style="margin: 0;">
                    @csrf
                    <button class="btn warning" type="submit">Upgrade To Premium (£5/month)</button>
                </form>
                <a class="btn secondary" href="{{ route('dashboard') }}">Back To Dashboard</a>
            </div>
        </div>
    </div>
@elseif(!$lessonLoaded)
    @if(!$hasPremiumAccess)
        <div class="card" style="margin-bottom: 1rem;">
            <div class="inline-stack" style="justify-content: space-between; align-items: center; gap: 0.8rem;">
                <div>
                    <div class="kicker">Trial status</div>
                    <p class="muted" style="margin-top: 0.35rem;">{{ $trialAttemptsRemaining }} of 2 free attempts remaining.</p>
                </div>
                <form method="POST" action="{{ route('practice.upgrade') }}" style="margin: 0;">
                    @csrf
                    <button class="btn warning" type="submit">Upgrade £5/month</button>
                </form>
            </div>
        </div>
    @endif

    <div class="hero">
        <div class="hero-body">
            <div class="eyebrow">Passmark practice studio</div>
            <h1>Train with focused chunks and immediate feedback.</h1>
            <p class="hero-copy">Pick a category, choose 10 or 20 questions, and move through one question at a time with clear correct/incorrect highlights.</p>

            <form method="GET" action="{{ route('practice.index') }}" class="grid cols-3" style="margin-top: 1.5rem; align-items: end;">
                <input type="hidden" name="lesson_loaded" value="1">
                <div>
                    <label for="category">Category</label>
                    <select id="category" name="category">
                        <option value="">All active categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category }}" @selected($selectedCategory === $category)>{{ $category }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="time_limit">Time limit</label>
                    <select id="time_limit" name="time_limit">
                        @foreach([10, 15, 20, 30, 45, 60] as $minutes)
                            <option value="{{ $minutes }}" @selected($timeLimit === $minutes)>{{ $minutes }} minutes</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="question_count">Question chunk</label>
                    <select id="question_count" name="question_count">
                        <option value="10" @selected($questionCount === 10)>10 questions</option>
                        <option value="20" @selected($questionCount === 20)>20 questions</option>
                    </select>
                </div>
                <div class="inline-stack" style="grid-column: 1 / -1; margin-bottom: 0.2rem;">
                    <button class="btn warning" type="submit">Load Session</button>
                </div>
            </form>
        </div>
    </div>
@elseif($questions->isEmpty())
    <div class="card empty-state">
        <h3>No active questions found</h3>
        <p class="muted">Try another category or ask admin to activate questions.</p>
        <div class="inline-stack" style="justify-content: center; margin-top: 0.8rem;">
            <a class="btn secondary" href="{{ route('practice.index') }}">Back To Session Setup</a>
        </div>
    </div>
@else
    <form method="POST" action="{{ route('practice.submit') }}" id="practice-form">
        @csrf
        <input type="hidden" name="elapsed_seconds" id="elapsed_seconds" value="0">
        <input type="hidden" name="time_limit_minutes" value="{{ $timeLimit }}">

        <div id="submit-loading-overlay" style="display: none; position: fixed; inset: 0; z-index: 9999; background: rgba(20, 16, 36, 0.82); backdrop-filter: blur(2px); align-items: center; justify-content: center; padding: 1rem;">
            <div class="card" style="max-width: 420px; width: 100%; text-align: center;">
                <div class="pill warning" style="margin-bottom: 0.8rem;">Submitting</div>
                <h3 style="margin-bottom: 0.6rem;">Preparing your results...</h3>
                <p class="muted">Please wait while Passmark computes your score.</p>
            </div>
        </div>

        <div style="max-width: 900px; margin: 0 auto;">
            <div class="timer-box" style="position: static; margin-bottom: 1rem;">
                <div class="kicker" style="color: rgba(255,255,255,0.74);">Session timer</div>
                <div class="timer-value" id="timer-display">{{ str_pad((string) $timeLimit, 2, '0', STR_PAD_LEFT) }}:00</div>
                <p style="margin-top: 0.6rem; color: rgba(255,255,255,0.9);">Time auto-submits when it reaches zero.</p>
            </div>

            <div class="card" style="margin-bottom: 1rem;">
                <div class="inline-stack" style="margin-top: 0.75rem;">
                    <a class="btn secondary" href="{{ route('practice.index') }}">Change Session Setup</a>
                </div>
            </div>

            <div>
                @foreach($questions as $index => $question)
                    <div class="quiz-card question-step" data-step="{{ $index }}" style="display: {{ $index === 0 ? 'block' : 'none' }};">
                        <input type="hidden" name="question_ids[]" value="{{ $question->id }}">
                        <div class="inline-stack" style="justify-content: space-between; margin-bottom: 0.8rem;">
                            <span class="pill">Question {{ $index + 1 }} of {{ $questions->count() }}</span>
                            <span class="pill warning">{{ $question->category ?: 'Uncategorized' }}</span>
                        </div>

                        <h3 style="margin-bottom: 1rem;">{{ $question->question_text }}</h3>

                        @foreach(['a' => $question->option_a, 'b' => $question->option_b, 'c' => $question->option_c, 'd' => $question->option_d] as $value => $option)
                            <label class="answer-option" data-option="{{ $value }}">
                                <input type="radio" name="answers[{{ $question->id }}]" value="{{ $value }}" data-correct="{{ $question->correct_option }}">
                                <span><strong>{{ strtoupper($value) }}.</strong> {{ $option }}</span>
                            </label>
                        @endforeach

                        <div style="margin-top: 1rem;">
                            <span class="feedback-text muted" aria-live="polite">Select an answer to continue.</span>
                        </div>
                        <div class="inline-stack" style="justify-content: flex-end; margin-top: 0.85rem;">
                            <button type="button" class="btn next-btn" disabled>{{ $index + 1 === $questions->count() ? 'Finish and Submit' : 'Next Question' }}</button>
                        </div>
                    </div>
                @endforeach

                <button class="btn" id="final-submit" type="submit" style="display:none; margin-top: 0.5rem;">Submit Practice</button>
            </div>
        </div>
    </form>

    <script>
        (function () {
            const totalSeconds = {{ $timeLimit * 60 }};
            const form = document.getElementById('practice-form');
            const display = document.getElementById('timer-display');
            const elapsedField = document.getElementById('elapsed_seconds');
            const steps = Array.from(document.querySelectorAll('.question-step'));
            const submitButton = document.getElementById('final-submit');
            const loadingOverlay = document.getElementById('submit-loading-overlay');
            let current = 0;
            let elapsed = 0;
            let isSubmitting = false;

            function showLoadingOverlay() {
                if (isSubmitting) {
                    return;
                }

                isSubmitting = true;
                if (loadingOverlay) {
                    loadingOverlay.style.display = 'flex';
                }

                document.querySelectorAll('button, input, select').forEach(function (element) {
                    element.disabled = true;
                });
            }

            function renderTimer() {
                const remaining = Math.max(totalSeconds - elapsed, 0);
                const minutes = String(Math.floor(remaining / 60)).padStart(2, '0');
                const seconds = String(remaining % 60).padStart(2, '0');
                display.textContent = `${minutes}:${seconds}`;
                elapsedField.value = String(elapsed);
            }

            function goNext() {
                if (current < steps.length - 1) {
                    steps[current].style.display = 'none';
                    current += 1;
                    steps[current].style.display = 'block';
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                } else {
                    showLoadingOverlay();
                    submitButton.click();
                }
            }

            steps.forEach(function (step) {
                const nextBtn = step.querySelector('.next-btn');
                const feedback = step.querySelector('.feedback-text');
                const options = Array.from(step.querySelectorAll('input[type="radio"]'));

                options.forEach(function (radio) {
                    radio.addEventListener('change', function () {
                        const correct = String(radio.dataset.correct || '').toLowerCase();
                        const selected = String(radio.value || '').toLowerCase();

                        step.querySelectorAll('.answer-option').forEach(function (opt) {
                            opt.classList.remove('correct', 'incorrect');
                        });

                        const selectedWrap = radio.closest('.answer-option');
                        if (!selectedWrap) {
                            return;
                        }

                        if (selected === correct) {
                            selectedWrap.classList.add('correct');
                            feedback.textContent = 'Correct answer. Great work.';
                            feedback.className = 'feedback-text pill success';
                        } else {
                            selectedWrap.classList.add('incorrect');
                            const rightWrap = step.querySelector('.answer-option[data-option="' + correct + '"]');
                            if (rightWrap) {
                                rightWrap.classList.add('correct');
                            }
                            feedback.textContent = 'Incorrect. The correct answer is highlighted.';
                            feedback.className = 'feedback-text pill danger';
                        }

                        nextBtn.disabled = false;
                    });
                });

                nextBtn.addEventListener('click', function () {
                    goNext();
                });
            });

            renderTimer();

            const interval = window.setInterval(function () {
                elapsed += 1;
                renderTimer();
                if (elapsed >= totalSeconds) {
                    window.clearInterval(interval);
                    showLoadingOverlay();
                    form.submit();
                }
            }, 1000);

            form.addEventListener('submit', function () {
                showLoadingOverlay();
                elapsedField.value = String(elapsed);
                window.clearInterval(interval);
            });
        })();
    </script>
@endif
@endsection
