<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'EDMO WIFI')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Inter:wght@400;500;600&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

   @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --ink: #0B1220;
            --ink-2: #121C31;
            --ink-3: #1B2740;
            --signal: #38BDF8;
            --signal-deep: #2563EB;
            --amber: #FFB020;
            --paper: #F5F7FA;
            --muted: #8CA0BC;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--ink);
            min-height: 100vh;
        }

        .font-display { font-family: 'Space Grotesk', sans-serif; }
        .font-mono { font-family: 'JetBrains Mono', monospace; }

        .recharge-card {
            position: relative;
            background: linear-gradient(155deg, var(--ink-2) 0%, var(--ink) 100%);
            border-radius: 18px;
            overflow: hidden;
            border: 1px solid rgba(255,255,255,0.06);
        }

        .recharge-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
            background: repeating-linear-gradient(90deg, var(--signal) 0px, var(--signal) 8px, transparent 8px, transparent 16px);
            opacity: 0.6;
        }

        .signal-bars span {
            display: inline-block;
            width: 4px;
            background: rgba(148, 163, 184, 0.3);
            margin-right: 3px;
            border-radius: 2px;
        }
        .signal-bars span.active { background: var(--signal); }
        .signal-bars span:nth-child(1) { height: 6px; }
        .signal-bars span:nth-child(2) { height: 10px; }
        .signal-bars span:nth-child(3) { height: 14px; }
        .signal-bars span:nth-child(4) { height: 18px; }

        input[type="tel"], input[type="text"] {
            background: var(--ink-3);
            border: 1px solid rgba(255,255,255,0.08);
            color: white;
        }
        input[type="tel"]::placeholder, input[type="text"]::placeholder {
            color: var(--muted);
        }
        input[type="tel"]:focus, input[type="text"]:focus {
            outline: none;
            border-color: var(--signal);
            box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.15);
        }
    </style>
</head>

<body class="antialiased">

    <div class="max-w-md mx-auto min-h-screen flex flex-col px-5 py-6">

        <!-- Header commun -->
        <div class="flex items-center gap-2 mb-6">
            <i class="fa-solid fa-wifi text-[var(--signal)] text-lg"></i>
            <span class="font-display font-semibold text-white"> EDMO WIFI</span>
        </div>

        @if (session('error'))
            <div class="bg-red-500/10 border border-red-500/30 text-red-300 text-sm px-4 py-3 rounded-xl mb-4">
                {{ session('error') }}
            </div>
        @endif

        <div class="flex-1">
            @yield('content')
        </div>

    </div>

</body>

</html>