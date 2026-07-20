<header class="bg-white shadow-sm border-b sticky top-0 z-30">

    <div class="flex items-center justify-between h-20 px-6">

        <!-- Partie gauche -->
        <div class="flex items-center">

            <!-- Bouton mobile -->
            <button
                id="menuButton"
                class="lg:hidden text-gray-700 text-2xl mr-4">

                <i class="fa-solid fa-bars"></i>

            </button>

            <div>

                <h1 class="text-2xl font-bold text-gray-800">
                    Tableau de bord
                </h1>

                <p class="text-gray-500 text-sm">
                    Bienvenue {{ Auth::user()->name ?? 'Administrateur' }}
                </p>

            </div>

        </div>

        <!-- Centre -->
        <div class="hidden md:block w-1/3">

            <div class="relative">

                <input
                    type="text"
                    placeholder="Rechercher..."
                    class="w-full rounded-lg border border-gray-300 pl-10 pr-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">

                <i class="fa-solid fa-search absolute left-3 top-3 text-gray-400"></i>

            </div>

        </div>

        <!-- Partie droite -->
        <div class="flex items-center space-x-6">

            <!-- Date -->
            <div class="hidden lg:block text-right">

                <div class="font-semibold text-gray-700">
                    {{ now()->format('d/m/Y') }}
                </div>

                <div class="text-sm text-gray-500">
                    {{ now()->format('H:i') }}
                </div>

            </div>

            <!-- Notification -->
            <button class="relative">

                <i class="fa-solid fa-bell text-2xl text-gray-700"></i>

                <span
                    class="absolute -top-1 -right-2 bg-red-500 text-white rounded-full text-xs px-2">

                    3

                </span>

            </button>

            <!-- Profil -->
            <div class="flex items-center">

                <img
                    src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'Admin') }}&background=2563eb&color=fff"
                    class="w-11 h-11 rounded-full border">

                <div class="ml-3 hidden md:block">

                    <div class="font-semibold">

                        {{ Auth::user()->name ?? 'Administrateur' }}

                    </div>

                    <div class="text-sm text-gray-500">

                        Administrateur

                    </div>

                </div>

            </div>

        </div>

    </div>

</header>

<script>

const menuButton = document.getElementById('menuButton');
const sidebar = document.getElementById('sidebar');
const overlay = document.getElementById('sidebarOverlay');

menuButton.addEventListener('click', ()=>{

    sidebar.classList.remove('-translate-x-full');

    overlay.classList.remove('hidden');

});

overlay.addEventListener('click', ()=>{

    sidebar.classList.add('-translate-x-full');

    overlay.classList.add('hidden');

});

</script>