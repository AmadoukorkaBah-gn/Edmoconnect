<aside
    id="sidebar"
    class="fixed lg:static inset-y-0 left-0 z-50 w-72 bg-slate-900 text-white transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out shadow-xl">

    <!-- Logo -->
    <div class="h-20 flex items-center justify-center border-b border-slate-700">
        <div class="text-center">
            <div class="text-3xl font-bold text-blue-400">
                WiFi Zone
            </div>
            <div class="text-sm text-gray-400">
                Administration
            </div>
        </div>
    </div>

    <!-- Profil -->
    <div class="p-6 border-b border-slate-700">
        <div class="flex items-center">
            <img
                src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'Admin') }}&background=2563eb&color=fff"
                class="w-12 h-12 rounded-full">
            <div class="ml-3">
                <h2 class="font-semibold">
                    {{ Auth::user()->name ?? 'Administrateur' }}
                </h2>
                <span class="text-xs text-green-400">
                    ● En ligne
                </span>
            </div>
        </div>
    </div>

    <!-- Menu -->
    <nav class="mt-4 overflow-y-auto pb-24" style="max-height: calc(100vh - 180px);">

        @php
            $links = [
                ['route' => 'dashboard', 'icon' => 'fa-gauge-high', 'label' => 'Tableau de bord'],
                ['route' => 'utilisateurs.index', 'icon' => 'fa-users', 'label' => 'Utilisateurs'],
                ['route' => 'mikrotik-servers.index', 'icon' => 'fa-server', 'label' => 'Serveurs MikroTik'],
                ['route' => 'hotspots.index', 'icon' => 'fa-wifi', 'label' => 'Hotspots'],
                ['route' => 'forfaits.index', 'icon' => 'fa-box', 'label' => 'Forfaits'],
                ['route' => 'abonnements.index', 'icon' => 'fa-id-card', 'label' => 'Abonnements'],
                ['route' => 'paiements.index', 'icon' => 'fa-money-bill-wave', 'label' => 'Paiements'],
                ['route' => 'statistiques.index', 'icon' => 'fa-chart-line', 'label' => 'Statistiques'],
                ['route' => 'clients.index', 'icon' => 'fa-bell', 'label' => 'Clients'],
                ['route' => 'parametres.edit', 'icon' => 'fa-gear', 'label' => 'Paramètres'],
            ];
        @endphp

        @foreach ($links as $link)

            @php
                $isActive = Route::has($link['route']) && request()->routeIs(explode('.', $link['route'])[0] . '.*');
                $href = Route::has($link['route']) ? route($link['route']) : '#';
            @endphp

            <a href="{{ $href }}"
                class="flex items-center px-6 py-4 transition {{ $isActive ? 'bg-blue-600' : 'hover:bg-blue-600' }} {{ Route::has($link['route']) ? '' : 'opacity-40 cursor-not-allowed' }}">

                <i class="fa-solid {{ $link['icon'] }} w-6"></i>

                <span class="ml-3">
                    {{ $link['label'] }}
                </span>

            </a>

        @endforeach

    </nav>

    <!-- Déconnexion -->
    <div class="absolute bottom-0 left-0 right-0 p-6 border-t border-slate-700 bg-slate-900 shadow-[0_-4px_10px_rgba(0,0,0,0.3)]">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button
                class="w-full bg-red-600 hover:bg-red-700 rounded-lg py-3 transition">
                <i class="fa-solid fa-right-from-bracket mr-2"></i>
                Déconnexion
            </button>
        </form>
    </div>

</aside>

<!-- Fond sombre mobile -->
<div
    id="sidebarOverlay"
    class="fixed inset-0 bg-black/50 hidden z-40 lg:hidden">
</div>