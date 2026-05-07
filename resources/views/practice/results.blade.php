@extends('layouts.app', ['title' => 'Results'])

@section('content')
<style>
    .ai-response-box {
        margin-top: 0.65rem;
        padding: 0.75rem 0.85rem;
        border: 1px solid rgba(49, 25, 96, 0.16);
        border-radius: 12px;
        background: rgba(245, 248, 255, 0.9);
        color: #1f1733;
        line-height: 1.5;
    }

    .ai-response-box .ai-title {
        font-weight: 700;
        margin-bottom: 0.45rem;
        color: #3d2c7a;
    }

    .ai-response-box ul {
        margin: 0;
        padding-left: 1.05rem;
    }

    .ai-response-box li {
        margin-bottom: 0.35rem;
    }

    .ai-response-box li:last-child {
        margin-bottom: 0;
    }

    .question-row-with-ai td {
        border-bottom: none;
    }
    </style>

<div class="hero">
    <div class="hero-body split" style="align-items: center;">
        <div>
            <div class="eyebrow">Session completed</div>
            <h1>Your results are ready.</h1>
            <p class="hero-copy">Review your score, timing, and the categories you missed before starting the next focused session.</p>
            <div class="inline-stack" style="margin-top: 1rem;">
                <span class="pill">{{ $score }} correct</span>
                <span class="pill warning">{{ $totalQuestions }} questions</span>
                <span class="pill">{{ gmdate('i:s', $elapsedSeconds) }} elapsed</span>
            </div>
            <div class="inline-stack" style="margin-top: 1rem;">
                <a class="btn" href="{{ route('practice.index') }}">Try Another Quiz</a>
            </div>
            @if(!$canUseAiAssistant)
                <div class="card soft" style="margin-top: 1rem;">
                    <div class="kicker">Premium unlock</div>
                    <p class="muted" style="margin-top: 0.45rem;">Get AI assistance for questions you miss and continue past the 2-attempt trial.</p>
                    <form method="POST" action="{{ route('practice.upgrade') }}" style="margin-top: 0.8rem;">
                        @csrf
                        <button class="btn warning" type="submit">Upgrade To Premium ({{ $premiumPriceLabel }})</button>
                    </form>
                </div>
            @endif
        </div>
        <div class="card soft" style="text-align: center;">
            <div class="score-ring" style="--score: {{ $percentage }};">
                <strong>{{ $percentage }}%</strong>
            </div>
            <p class="muted" style="margin-top: 0.8rem;">Time limit set: {{ $timeLimitMinutes }} minutes</p>
        </div>
    </div>
</div>

<div class="grid cols-2" style="margin-top: 1rem;">
    <div class="card">
        <div class="section-title">
            <div>
                <div class="kicker">Performance snapshot</div>
                <h3>What this session says</h3>
            </div>
        </div>
        <div class="feature-list">
            <div class="feature-item">
                <strong>Accuracy</strong>
                <p class="muted">{{ $score }} out of {{ $totalQuestions }} correct.</p>
            </div>
            <div class="feature-item">
                <strong>Time used</strong>
                <p class="muted">{{ gmdate('i:s', $elapsedSeconds) }} spent during this session.</p>
            </div>
            <div class="feature-item">
                <strong>Exam pacing</strong>
                <p class="muted">Use the timer again if you want to compare pacing across categories.</p>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="section-title">
            <div>
                <div class="kicker">Missed topics</div>
                <h3>Where to focus next</h3>
            </div>
        </div>
        <div class="topic-list">
            @forelse($missedCategories as $category => $missedCount)
                <div class="topic-item">
                    <div class="inline-stack" style="justify-content: space-between; align-items: center;">
                        <strong>{{ $category }}</strong>
                        <span class="pill danger">{{ $missedCount }} missed</span>
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <p class="muted">No weak categories in this session. Everything was correct.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<div class="card">
    <div class="section-title">
        <div>
            <div class="kicker">Answer review</div>
            <h3>Question-by-question breakdown</h3>
        </div>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Question</th>
                    <th>Your Answer</th>
                    <th>Correct</th>
                    <th>Status</th>
                    <th>AI Helper</th>
                </tr>
            </thead>
            <tbody>
                @foreach($resultRows as $row)
                    <tr class="{{ !$row['is_correct'] && $canUseAiAssistant ? 'question-row-with-ai' : '' }}">
                        <td>
                            <strong>{{ $row['question']->question_text }}</strong>
                            <div class="muted" style="margin-top: 0.35rem;">{{ $row['question']->category ?: 'Uncategorized' }}</div>
                        </td>
                        <td>{{ strtoupper($row['selected'] ?? '-') }}</td>
                        <td>{{ strtoupper($row['question']->correct_option) }}</td>
                        <td>
                            <span class="pill {{ $row['is_correct'] ? '' : 'danger' }}">
                                {{ $row['is_correct'] ? 'Correct' : 'Incorrect' }}
                            </span>
                        </td>
                        <td>
                            @if($row['is_correct'])
                                <span class="muted">Not needed</span>
                            @elseif($canUseAiAssistant)
                                <button
                                    type="button"
                                    class="btn secondary ai-explain-btn"
                                    data-question="{{ e($row['question']->question_text) }}"
                                    data-selected="{{ strtoupper($row['selected'] ?? '-') }}"
                                    data-correct="{{ strtoupper($row['question']->correct_option) }}"
                                    data-explanation="{{ e($row['question']->explanation ?? '') }}"
                                    data-target="ai-response-{{ $row['question']->id }}"
                                >Explain With AI</button>
                            @else
                                <span class="pill warning">Premium only</span>
                            @endif
                        </td>
                    </tr>
                    @if(!$row['is_correct'] && $canUseAiAssistant)
                        <tr>
                            <td colspan="5" style="padding-top: 0;">
                                <div id="ai-response-{{ $row['question']->id }}" class="ai-response-box" style="display:none;"></div>
                            </td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@if($canUseAiAssistant)
    <script>
        (function () {
            const csrfToken = '{{ csrf_token() }}';
            const endpoint = '{{ route('practice.ai-explain') }}';
            const buttons = Array.from(document.querySelectorAll('.ai-explain-btn'));

            function normalizeAiLines(rawText) {
                if (!rawText) {
                    return [];
                }

                let cleaned = String(rawText)
                    .replace(/\*\*/g, '')
                    .replace(/\r/g, '\n')
                    .replace(/\t/g, ' ')
                    .trim();

                if (cleaned.startsWith('- ')) {
                    cleaned = cleaned.slice(2);
                }

                let pieces = [];

                if (cleaned.includes('\n')) {
                    pieces = cleaned.split(/\n+/);
                } else if (cleaned.includes(' - ')) {
                    pieces = cleaned.split(/\s-\s+/);
                } else {
                    pieces = [cleaned];
                }

                return pieces
                    .map(function (line) {
                        return line
                            .replace(/^[-*]\s+/, '')
                            .replace(/^\d+\.\s+/, '')
                            .replace(/\s{2,}/g, ' ')
                            .trim();
                    })
                    .filter(function (line) { return line.length > 0; });
            }

            function renderAiResponse(target, message, isError) {
                target.innerHTML = '';
                target.style.display = 'block';

                if (isError) {
                    target.className = 'ai-response-box muted';
                    target.textContent = message;
                    return;
                }

                target.className = 'ai-response-box';

                const title = document.createElement('div');
                title.className = 'ai-title';
                title.textContent = 'AI explanation';
                target.appendChild(title);

                const lines = normalizeAiLines(message);
                if (lines.length === 0) {
                    const fallback = document.createElement('div');
                    fallback.textContent = 'No explanation generated yet. Please try again.';
                    target.appendChild(fallback);
                    return;
                }

                const list = document.createElement('ul');
                lines.forEach(function (line) {
                    const item = document.createElement('li');
                    item.textContent = line;
                    list.appendChild(item);
                });

                target.appendChild(list);
            }

            buttons.forEach(function (button) {
                button.addEventListener('click', async function () {
                    const targetId = button.dataset.target;
                    const target = targetId ? document.getElementById(targetId) : null;
                    if (!target) {
                        return;
                    }

                    button.disabled = true;
                    renderAiResponse(target, 'Thinking...', true);

                    try {
                        const response = await fetch(endpoint, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                question: button.dataset.question || '',
                                selected: button.dataset.selected || '',
                                correct: button.dataset.correct || '',
                                explanation: button.dataset.explanation || ''
                            })
                        });

                        const payload = await response.json();

                        if (!response.ok) {
                            renderAiResponse(target, payload.message || 'Could not fetch AI explanation right now.', true);
                            return;
                        }

                        renderAiResponse(target, payload.answer || 'No explanation generated yet. Please try again.', false);
                    } catch (error) {
                        renderAiResponse(target, 'Connection issue while requesting AI explanation.', true);
                    } finally {
                        button.disabled = false;
                    }
                });
            });
        })();
    </script>
@endif
@endsection
