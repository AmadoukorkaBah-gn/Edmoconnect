<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WiFi Hotspot</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body class="bg-[#0b1220] text-white min-h-screen">

    <!-- TOP BAR -->
    <header class="w-full flex items-center justify-between px-6 py-4 border-b border-white/10">

        <div class="flex items-center gap-2">
            <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center">
                📶
            </div>
            <span class="font-bold">WiFi Hotspot</span>
        </div>

        <div class="text-xs text-gray-400">
            Connexion sécurisée
        </div>

    </header>

    <!-- MAIN -->
    <main class="max-w-6xl mx-auto px-6 py-10">

        <!-- HERO SECTION -->
        <div class="text-center mb-10">

            <h1 class="text-3xl md:text-5xl font-bold">
                Internet rapide <span class="text-blue-500">partout</span>
            </h1>

            <p class="text-gray-400 mt-3">
                Choisissez un forfait et connectez-vous instantanément
            </p>

        </div>

        <!-- CONTENT GRID -->
        <div class="grid md:grid-cols-2 gap-8 items-center">

            <!-- LEFT: CONTENT -->
            <div>

                @yield('content')

            </div>

            <!-- RIGHT: VISUAL MOCKUP -->
            <div class="hidden md:flex justify-center">

                <div class="grid grid-cols-2 gap-4">

                    <div class="bg-white/10 p-3 rounded-2xl border border-white/10">
                        <div class="text-xs text-gray-300">Connexion</div>
                        <div class="text-sm font-bold">Login WiFi</div>
                    </div>

                    <div class="bg-white/10 p-3 rounded-2xl border border-white/10">
                        <div class="text-xs text-gray-300">Forfaits</div>
                        <div class="text-sm font-bold">Choix rapide</div>
                    </div>

                    <div class="bg-white/10 p-3 rounded-2xl border border-white/10">
                        <div class="text-xs text-gray-300">Paiement</div>
                        <div class="text-sm font-bold">Djomy API</div>
                    </div>

                    <div class="bg-green-500/20 p-3 rounded-2xl border border-green-500/30">
                        <div class="text-xs text-gray-300">Statut</div>
                        <div class="text-sm font-bold text-green-400">Actif</div>
                    </div>

                </div>

            </div>

        </div>

    </main>

    <!-- FOOTER -->
    <footer class="text-center text-xs text-gray-500 py-6 border-t border-white/10">
        © {{ date('Y') }} WiFi Hotspot System • Powered by Laravel
    </footer>

</body>
</html>