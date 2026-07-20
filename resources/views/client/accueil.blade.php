@extends('layouts.client')

@section('title', $hotspot->name . ' — WiFi Zone')

@section('content')

<div class="mb-6">
    <div class="inline-flex items-center gap-2 text-[var(--signal)] text-xs font-mono border border-[var(--signal)]/30 rounded-full px-3 py-1 mb-3">
        <i class="fa-solid fa-signal"></i>
        {{ $hotspot->name }}
    </div>
    <h1 class="font-display text-2xl font-semibold text-white">
        Choisis ton forfait
    </h1>
    <p class="text-[var(--muted)] text-sm mt-1">
        Sélectionne une durée pour te connecter.
    </p>
</div>

<div class="space-y-4">

    @forelse ($forfaits as $forfait)
        <a href="{{ route('client.paiement', [$hotspot, $forfait]) }}" class="block recharge-card p-5">

            <div class="flex items-center justify-between">

                <div>
                    <div class="font-display font-semibold text-white mb-1">
                        {{ $forfait->nom }}
                    </div>
                    <div class="text-[var(--muted)] text-sm">
                        {{ $forfait->duree_label }} de connexion
                    </div>

                    @if ($forfait->download_speed || $forfait->upload_speed)
                        <div class="text-xs font-mono text-[var(--muted)] mt-1">
                            ↓ {{ $forfait->download_speed ?? '—' }} · ↑ {{ $forfait->upload_speed ?? '—' }} Mbps
                        </div>
                    @endif
                </div>

                <div class="text-right shrink-0 ml-4">
                    <div class="signal-bars flex items-end justify-end mb-2">
                        @for ($i = 1; $i <= 4; $i++)
                            <span class="{{ $i <= $forfait->signal_bars ? 'active' : '' }}"></span>
                        @endfor
                    </div>
                    <div class="font-mono text-lg font-bold text-white">
                        {{ number_format($forfait->prix, 0, ',', ' ') }}
                    </div>
                    <div class="font-mono text-xs text-[var(--signal)]">GNF</div>
                </div>

            </div>

        </a>
    @empty
        <div class="text-center py-16 text-[var(--muted)]">
            <i class="fa-solid fa-wifi text-3xl mb-3 opacity-30"></i>
            <p>Aucun forfait disponible sur ce hotspot pour le moment.</p>
        </div>
    @endforelse

</div>

<div class="mt-8 text-center">
    <a href="{{ route('client.abonnement') }}" class="text-sm text-[var(--muted)] hover:text-white transition">
        J'ai déjà un abonnement actif →
    </a>
</div>

@endsection