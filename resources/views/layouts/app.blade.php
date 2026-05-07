<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Passmark - {{ $title ?? 'Cloud Exam Practice' }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=space-grotesk:400,500,700|figtree:400,500,600,700" rel="stylesheet" />
    <style>
        :root {
            --bg: #f7f4ff;
            --bg-2: #fff8ef;
            --surface: rgba(255, 255, 255, 0.82);
            --surface-strong: #ffffff;
            --text: #1f1733;
            --muted: #655b7f;
            --brand: #6d28d9;
            --brand-strong: #5b21b6;
            --accent: #f59e0b;
            --accent-strong: #d97706;
            --danger: #b91c1c;
            --success: #15803d;
            --border: rgba(49, 25, 96, 0.14);
            --shadow: 0 24px 58px rgba(59, 25, 131, 0.12);
            --radius: 22px;
        }

        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            font-family: 'Figtree', sans-serif;
            color: var(--text);
            background:
                radial-gradient(circle at 12% 10%, rgba(109, 40, 217, 0.22), transparent 28%),
                radial-gradient(circle at 86% 14%, rgba(245, 158, 11, 0.26), transparent 22%),
                linear-gradient(180deg, var(--bg) 0%, var(--bg-2) 100%);
        }

        a { color: inherit; text-decoration: none; }
        h1, h2, h3, h4 { margin: 0 0 0.55rem; font-family: 'Space Grotesk', sans-serif; }
        p { margin: 0; }

        .topbar {
            position: sticky;
            top: 0;
            z-index: 40;
            backdrop-filter: blur(14px);
            background: rgba(247, 244, 255, 0.86);
            border-bottom: 1px solid rgba(255, 255, 255, 0.9);
        }
        .topbar-inner {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0.9rem 1.2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .brand {
            display: inline-flex;
            align-items: center;
            gap: 0.72rem;
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 700;
            letter-spacing: 0.01em;
        }
        .brand-mark {
            width: 2.35rem;
            height: 2.35rem;
            border-radius: 16px;
            display: grid;
            place-items: center;
            color: #fff;
            background: linear-gradient(140deg, var(--brand), #7c3aed 65%, var(--accent) 100%);
            box-shadow: 0 16px 28px rgba(109, 40, 217, 0.3);
        }

        .actions { display: flex; gap: 0.6rem; flex-wrap: wrap; align-items: center; }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 1.25rem;
        }

        .hero, .card {
            border: 1px solid var(--border);
            border-radius: var(--radius);
            background: var(--surface);
            box-shadow: var(--shadow);
            backdrop-filter: blur(10px);
        }
        .hero { overflow: hidden; }
        .hero-body { padding: 2rem; }

        .grid { display: grid; gap: 1rem; }
        .grid.cols-2 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .grid.cols-3 { grid-template-columns: repeat(3, minmax(0, 1fr)); }
        .split { display: grid; grid-template-columns: 1.5fr 0.8fr; gap: 1rem; align-items: start; }

        .card { padding: 1.1rem; margin-bottom: 1rem; }
        .card.soft { background: rgba(255, 255, 255, 0.68); }

        .hero-title { font-size: clamp(2rem, 4.8vw, 4rem); line-height: 0.95; letter-spacing: -0.03em; }
        .hero-copy { max-width: 58ch; color: var(--muted); margin-top: 0.9rem; }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            padding: 0.36rem 0.78rem;
            border-radius: 999px;
            background: rgba(109, 40, 217, 0.12);
            color: var(--brand-strong);
            font-weight: 700;
            font-size: 0.8rem;
            margin-bottom: 0.8rem;
        }
        .kicker { color: var(--muted); text-transform: uppercase; letter-spacing: 0.12em; font-size: 0.74rem; font-weight: 700; }
        .muted { color: var(--muted); }
        .metric { font-family: 'Space Grotesk', sans-serif; font-size: clamp(1.6rem, 3.4vw, 2.5rem); margin-top: 0.2rem; }
        .section-title { display: flex; justify-content: space-between; align-items: end; gap: 1rem; margin-bottom: 0.8rem; }

        .btn {
            border: 1px solid transparent;
            background: linear-gradient(140deg, var(--brand), var(--brand-strong));
            color: #fff;
            border-radius: 999px;
            padding: 0.72rem 1.08rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font: inherit;
            font-weight: 700;
            transition: transform 140ms ease, box-shadow 140ms ease;
            box-shadow: 0 14px 24px rgba(109, 40, 217, 0.22);
        }
        .btn:hover { transform: translateY(-1px); }
        .btn.secondary {
            background: rgba(255, 255, 255, 0.76);
            color: var(--text);
            border-color: var(--border);
            box-shadow: none;
        }
        .btn.warning {
            background: linear-gradient(140deg, var(--accent), var(--accent-strong));
            box-shadow: 0 14px 24px rgba(245, 158, 11, 0.25);
        }
        .btn.danger { background: linear-gradient(140deg, #dc2626, var(--danger)); box-shadow: none; }

        label { display: block; font-weight: 700; margin-bottom: 0.42rem; }
        input, select, textarea {
            width: 100%;
            border: 1px solid rgba(70, 39, 128, 0.2);
            border-radius: 14px;
            padding: 0.85rem 0.9rem;
            margin-bottom: 0.85rem;
            background: rgba(255, 255, 255, 0.92);
            font: inherit;
            color: var(--text);
        }
        input:focus, select:focus, textarea:focus { outline: 3px solid rgba(109, 40, 217, 0.16); border-color: rgba(109, 40, 217, 0.5); }
        textarea { min-height: 110px; resize: vertical; }

        .notice {
            border-radius: 16px;
            border: 1px solid rgba(21, 128, 61, 0.2);
            background: rgba(240, 253, 244, 0.95);
            color: #166534;
            padding: 0.85rem 1rem;
            margin-bottom: 1rem;
        }

        .pill {
            display: inline-flex;
            align-items: center;
            border-radius: 999px;
            padding: 0.32rem 0.72rem;
            font-size: 0.8rem;
            font-weight: 700;
            background: rgba(109, 40, 217, 0.1);
            color: var(--brand-strong);
        }
        .pill.warning { background: rgba(245, 158, 11, 0.15); color: #92400e; }
        .pill.danger { background: rgba(220, 38, 38, 0.14); color: #991b1b; }
        .pill.success { background: rgba(21, 128, 61, 0.13); color: #166534; }

        .feature-list, .topic-list { display: grid; gap: 0.7rem; }
        .feature-item, .topic-item {
            border: 1px solid rgba(49, 25, 96, 0.12);
            background: rgba(255, 255, 255, 0.66);
            border-radius: 16px;
            padding: 0.85rem 0.95rem;
        }

        .table-wrap {
            overflow: auto;
            border-radius: 16px;
            border: 1px solid rgba(49, 25, 96, 0.12);
            background: rgba(255, 255, 255, 0.7);
        }
        table { width: 100%; border-collapse: collapse; min-width: 680px; }
        th, td {
            border-bottom: 1px solid rgba(49, 25, 96, 0.1);
            padding: 0.84rem 0.9rem;
            text-align: left;
            vertical-align: top;
        }
        th { color: var(--muted); text-transform: uppercase; letter-spacing: 0.08em; font-size: 0.76rem; font-family: 'Space Grotesk', sans-serif; }
        tr:last-child td { border-bottom: none; }

        .stats-strip { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 1rem; }
        .inline-stack { display: flex; flex-wrap: wrap; align-items: center; gap: 0.65rem; }
        .row-actions { display: flex; gap: 0.5rem; flex-wrap: wrap; }
        .empty-state { text-align: center; padding: 1.8rem 1rem; }

        .quiz-card {
            border: 1px solid rgba(49, 25, 96, 0.12);
            border-radius: 18px;
            background: rgba(255, 255, 255, 0.84);
            padding: 1.15rem;
            margin-bottom: 1rem;
        }
        .answer-option {
            display: flex;
            align-items: flex-start;
            gap: 0.78rem;
            border: 1px solid rgba(49, 25, 96, 0.14);
            border-radius: 14px;
            padding: 0.8rem 0.85rem;
            margin-bottom: 0.7rem;
            background: rgba(255, 255, 255, 0.92);
            cursor: pointer;
        }
        .answer-option input { width: auto; margin: 0.1rem 0 0; }
        .answer-option.correct { border-color: rgba(21, 128, 61, 0.5); background: rgba(220, 252, 231, 0.88); }
        .answer-option.incorrect { border-color: rgba(185, 28, 28, 0.45); background: rgba(254, 226, 226, 0.88); }

        .timer-box {
            position: sticky;
            top: 5.8rem;
            border-radius: 18px;
            padding: 0.95rem 1rem;
            color: #fff;
            background: linear-gradient(140deg, #4c1d95, #7e22ce 62%, #f59e0b 100%);
            box-shadow: 0 20px 32px rgba(76, 29, 149, 0.26);
        }
        .timer-value { font-family: 'Space Grotesk', sans-serif; font-size: 2rem; line-height: 1; margin-top: 0.34rem; }

        .auth-shell { display: grid; grid-template-columns: 1.1fr 0.9fr; gap: 1rem; }
        .auth-panel { min-height: 100%; display: flex; flex-direction: column; justify-content: center; }
        .hero-mini { color: #fff; background: linear-gradient(150deg, #4c1d95, #7e22ce 64%, #f59e0b 100%); }
        .hero-mini .muted { color: rgba(255, 255, 255, 0.8); }

        .media-photo {
            width: 100%;
            height: 220px;
            object-fit: cover;
            border-radius: 16px;
            border: 1px solid rgba(49, 25, 96, 0.14);
            margin-bottom: 0.8rem;
        }

        .error { color: #b91c1c; font-size: 0.92rem; margin: -0.2rem 0 0.8rem; }

        @media (max-width: 980px) {
            .grid.cols-2, .grid.cols-3, .split, .auth-shell, .stats-strip { grid-template-columns: 1fr; }
            .timer-box { position: static; }
        }
    </style>
</head>
<body>
    <header class="topbar">
        <div class="topbar-inner">
            <a class="brand" href="{{ route('home') }}">
                <span class="brand-mark">PM</span>
                <span>Passmark</span>
            </a>

            <div class="actions">
                @auth
                    <a class="btn secondary" href="{{ route('dashboard') }}">Dashboard</a>
                    <a class="btn secondary" href="{{ route('practice.index') }}">Practice</a>
                    @if(auth()->user()->is_admin)
                        <a class="btn warning" href="{{ route('admin.dashboard') }}">Admin</a>
                    @endif
                    <form action="{{ route('logout') }}" method="POST" style="margin:0;">
                        @csrf
                        <button class="btn danger" type="submit">Logout</button>
                    </form>
                @else
                    <a class="btn secondary" href="{{ route('home') }}">Home</a>
                    <a class="btn secondary" href="{{ route('about') }}">About Us</a>
                    <a class="btn secondary" href="{{ route('blog') }}">Blog</a>
                    <a class="btn secondary" href="{{ route('contact') }}">Contact</a>
                    <a class="btn secondary" href="{{ route('login') }}">Login</a>
                    <a class="btn" href="{{ route('register') }}">Sign Up</a>
                @endauth
            </div>
        </div>
    </header>

    <main class="container">
        @if(session('status'))
            <div class="notice">{{ session('status') }}</div>
        @endif

        @yield('content')
    </main>
</body>
</html>
