<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WiFi Zone — Internet haut débit à Conakry</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Inter:wght@400;500;600&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    @vite(['resources/css/app.css'])

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
            background: var(--paper);
        }

        .font-display {
            font-family: 'Space Grotesk', sans-serif;
        }

        .font-mono {
            font-family: 'JetBrains Mono', monospace;
        }

        /* --- Hero radar pulse --- */
        .radar {
            position: absolute;
            border-radius: 9999px;
            border: 1px solid rgba(56, 189, 248, 0.35);
            animation: pulse-ring 3.6s cubic-bezier(0.2, 0.6, 0.4, 1) infinite;
        }
        .radar.r2 { animation-delay: 1.2s; }
        .radar.r3 { animation-delay: 2.4s; }

        @keyframes pulse-ring {
            0%   { transform: scale(0.35); opacity: 0.9; }
            80%  { opacity: 0; }
            100% { transform: scale(1); opacity: 0; }
        }

        @media (prefers-reduced-motion: reduce) {
            .radar { animation: none; opacity: 0.15; }
        }

        /* --- Carte de recharge (signature element) --- */
        .recharge-card {
            position: relative;
            background: linear-gradient(155deg, var(--ink-2) 0%, var(--ink) 100%);
            border-radius: 18px;
            overflow: hidden;
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }

        .recharge-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 40px -12px rgba(37, 99, 235, 0.35);
        }

        .recharge-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: repeating-linear-gradient(
                90deg,
                var(--signal) 0px,
                var(--signal) 8px,
                transparent 8px,
                transparent 16px
            );
            opacity: 0.6;
        }

        .perforation {
            position: relative;
            border-top: 2px dashed rgba(140, 160, 188, 0.3);
        }

        .perforation::before,
        .perforation::after {
            content: '';
            position: absolute;
            top: -8px;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: var(--paper);
        }

        .perforation::before { left: -8px; }
        .perforation::after  { right: -8px; }

        .signal-bars span {
            display: inline-block;
            width: 4px;
            background: rgba(148, 163, 184, 0.3);
            margin-right: 3px;
            border-radius: 2px;
        }

        .signal-bars span.active {
            background: var(--signal);
        }

        .signal-bars span:nth-child(1) { height: 6px; }
        .signal-bars span:nth-child(2) { height: 10px; }
        .signal-bars span:nth-child(3) { height: 14px; }
        .signal-bars span:nth-child(4) { height: 18px; }
    </style>
</head>

<body class="antialiased">

    <!-- Nav -->
    <header class="sticky top-0 z-40 backdrop-blur bg-[var(--ink)]/95 border-b border-white/5">
        <div class="max-w-6xl mx-auto px-6 h-18 flex items-center justify-between py-4">

            <div class="flex items-center gap-2">
                <i class="fa-solid fa-wifi text-[var(--signal)] text-xl"></i>
                <span class="font-display font-semibold text-white text-lg">WiFi Zone</span>
            </div>

            <nav class="hidden md:flex items-center gap-8 text-sm text-[var(--muted)]">
                <a href="#forfaits" class="hover:text-white transition">Forfaits</a>
                <a href="#comment-ca-marche" class="hover:text-white transition">Comment ça marche</a>
                <a href="#contact" class="hover:text-white transition">Contact</a>
            </nav>

            <a href="{{ route('login') }}"
                class="text-sm font-medium text-white bg-white/10 hover:bg-white/15 px-4 py-2 rounded-lg transition">
                Espace admin
            </a>

        </div>
    </header>

    <!-- Hero -->
    <section class="relative bg-[var(--ink)] overflow-hidden">

        <div class="absolute right-[-120px] top-1/2 -translate-y-1/2 w-[420px] h-[420px] pointer-events-none hidden md:block">
            <div class="radar r1 w-full h-full"></div>
            <div class="radar r2 w-full h-full"></div>
            <div class="radar r3 w-full h-full"></div>
            <div class="absolute inset-0 flex items-center justify-center">
                <i class="fa-solid fa-wifi text-[var(--signal)] text-6xl opacity-80"></i>
            </div>
        </div>

        <div class="max-w-6xl mx-auto px-6 py-24 md:py-32 relative">
            <div class="max-w-xl">

                <span class="inline-block font-mono text-xs tracking-wider text-[var(--signal)] border border-[var(--signal)]/30 rounded-full px-3 py-1 mb-6">
                    HOTSPOTS ACTIFS À CONAKRY
                </span>

                <h1 class="font-display text-4xl md:text-5xl font-semibold text-white leading-tight mb-6">
                    Connectez-vous.<br>
                    <span class="text-[var(--signal)]">Simplement.</span>
                </h1>

                <p class="text-[var(--muted)] text-lg leading-relaxed mb-8">
                    Achetez un forfait WiFi en quelques secondes, payez avec Orange Money ou MTN MoMo,
                    et naviguez immédiatement — sans engagement, sans câble, sans complication.
                </p>

                <a href="#forfaits"
                    class="inline-flex items-center gap-2 bg-[var(--signal-deep)] hover:bg-blue-700 text-white font-medium px-6 py-3.5 rounded-xl transition">
                    Voir les forfaits
                    <i class="fa-solid fa-arrow-down text-sm"></i>
                </a>

            </div>
        </div>
    </section>

    <!-- Forfaits -->
    <section id="forfaits" class="max-w-6xl mx-auto px-6 py-20">

        <div class="text-center max-w-lg mx-auto mb-14">
            <span class="font-mono text-xs tracking-wider text-[var(--signal-deep)]">NOS FORFAITS</span>
            <h2 class="font-display text-3xl font-semibold text-gray-900 mt-2">
                Choisissez votre durée de connexion
            </h2>
            <p class="text-gray-500 mt-3">
                Tous les forfaits sont valables dans nos hotspots partenaires à Conakry.
            </p>
        </div>

        @if ($forfaits->isEmpty())

            <div class="text-center py-16 text-gray-400">
                <i class="fa-solid fa-wifi text-4xl mb-4 opacity-30"></i>
                <p>Aucun forfait disponible pour le moment. Revenez bientôt.</p>
            </div>

        @else

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

                @foreach ($forfaits as $forfait)
                    <div class="recharge-card p-6 flex flex-col">

                        <div class="flex items-start justify-between mb-6">
                            <div>
                                <div class="font-mono text-xs text-[var(--muted)] mb-1">FORFAIT</div>
                                <h3 class="font-display text-xl font-semibold text-white">
                                    {{ $forfait->nom }}
                                </h3>
                            </div>

                            <div class="signal-bars flex items-end" title="Vitesse relative">
                                @for ($i = 1; $i <= 4; $i++)
                                    <span class="{{ $i <= $forfait->signal_bars ? 'active' : '' }}"></span>
                                @endfor
                            </div>
                        </div>

                        <div class="flex items-baseline gap-1 mb-1">
                            <span class="font-mono text-3xl font-bold text-white">
                                {{ number_format($forfait->prix, 0, ',', ' ') }}
                            </span>
                            <span class="font-mono text-sm text-[var(--signal)]">GNF</span>
                        </div>

                        <div class="text-[var(--muted)] text-sm mb-6">
                            {{ $forfait->duree_label }} de connexion
                        </div>

                        @if ($forfait->download_speed || $forfait->upload_speed)
                            <div class="text-xs font-mono text-[var(--muted)] mb-4">
                                ↓ {{ $forfait->download_speed ?? '—' }} Mbps · ↑ {{ $forfait->upload_speed ?? '—' }} Mbps
                            </div>
                        @endif

                        @if ($forfait->description)
                            <p class="text-sm text-[var(--muted)] mb-6 leading-relaxed">
                                {{ $forfait->description }}
                            </p>
                        @endif

                        <div class="perforation pt-5 mt-auto">
                            <span class="inline-flex items-center gap-2 text-sm font-medium text-[var(--signal)]">
                                <i class="fa-solid fa-bolt"></i>
                                Disponible maintenant
                            </span>
                        </div>

                    </div>
                @endforeach

            </div>

        @endif

    </section>

    <!-- Comment ça marche -->
    <section id="comment-ca-marche" class="bg-white border-y border-gray-100 py-20">

        <div class="max-w-6xl mx-auto px-6">

            <div class="text-center max-w-lg mx-auto mb-14">
                <span class="font-mono text-xs tracking-wider text-[var(--signal-deep)]">LE PROCESSUS</span>
                <h2 class="font-display text-3xl font-semibold text-gray-900 mt-2">
                    Comment ça marche
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">

                <div>
                    <div class="font-mono text-sm text-[var(--signal-deep)] mb-3">01</div>
                    <h3 class="font-display font-semibold text-gray-900 mb-2">Connectez-vous au hotspot</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">
                        Repérez le réseau WiFi Zone disponible sur le lieu et sélectionnez-le sur votre téléphone.
                    </p>
                </div>

                <div>
                    <div class="font-mono text-sm text-[var(--signal-deep)] mb-3">02</div>
                    <h3 class="font-display font-semibold text-gray-900 mb-2">Choisissez un forfait</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">
                        Sélectionnez la durée qui vous convient et entrez votre numéro de téléphone.
                    </p>
                </div>

                <div>
                    <div class="font-mono text-sm text-[var(--signal-deep)] mb-3">03</div>
                    <h3 class="font-display font-semibold text-gray-900 mb-2">Payez et naviguez</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">
                        Réglez avec Orange Money ou MTN MoMo. Votre accès s'active instantanément.
                    </p>
                </div>

            </div>

        </div>

    </section>

    <!-- Footer -->
    <footer id="contact" class="bg-[var(--ink)] py-12">
        <div class="max-w-6xl mx-auto px-6 flex flex-col md:flex-row items-center justify-between gap-4">

            <div class="flex items-center gap-2">
                <i class="fa-solid fa-wifi text-[var(--signal)]"></i>
                <span class="font-display font-semibold text-white">WiFi Zone</span>
            </div>

            <p class="text-[var(--muted)] text-sm">
                Conakry, Guinée
            </p>

            <p class="text-[var(--muted)] text-sm">
                &copy; {{ date('Y') }} WiFi Zone — Tous droits réservés
            </p>

        </div>
    </footer>

</body>

</html>