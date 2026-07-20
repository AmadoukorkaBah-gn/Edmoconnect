<div class="fixed inset-x-0 bottom-0 z-30 border-t border-white/5 bg-[var(--ink)]/95 backdrop-blur">
    <div class="max-w-md mx-auto grid grid-cols-2 text-center">

        <a href="{{ route('home') }}"
            class="flex flex-col items-center gap-1 py-3 transition {{ request()->routeIs('home') ? 'text-[var(--signal)]' : 'text-[var(--muted)]' }}">
            <i class="fa-solid fa-house text-lg"></i>
            <span class="text-[11px] font-medium">Accueil</span>
        </a>

        <a href="{{ route('client.abonnement') }}"
            class="flex flex-col items-center gap-1 py-3 transition {{ request()->routeIs('client.abonnement') ? 'text-[var(--signal)]' : 'text-[var(--muted)]' }}">
            <i class="fa-solid fa-id-card text-lg"></i>
            <span class="text-[11px] font-medium">Mon abonnement</span>
        </a>

    </div>
</div>