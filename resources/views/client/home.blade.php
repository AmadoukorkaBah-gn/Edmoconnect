@extends('layouts.client')

@section('title', 'WiFi Zone — Internet à Conakry')

@section('content')

<!-- Hero -->
<div class="text-center py-6 mb-2">
    <span class="inline-block font-mono text-xs tracking-wider text-[var(--signal)] border border-[var(--signal)]/30 rounded-full px-3 py-1 mb-5">
        Votre Wifi de Proximité
    </span>

    <h1 class="font-display text-3xl font-semibold text-white leading-tight mb-3">
        Connectez-vous.<br>
        <span class="text-[var(--signal)]">Simplement.</span>
    </h1>

    <p class="text-[var(--muted)] text-sm leading-relaxed">
        Achetez un forfait WiFi, payez avec Orange Money ou MTN MoMo,
        et naviguez immédiatement.
    </p>
</div>

<!-- CTA principal -->
<div class="text-center mb-10">
    <a href="{{ route('client.hotspots') }}"
        class="inline-flex items-center gap-2 bg-[var(--signal-deep)] hover:bg-blue-700 text-white font-medium px-6 py-3.5 rounded-xl transition">
        Me connecter
        <i class="fa-solid fa-arrow-right text-sm"></i>
    </a>
</div>

<!-- Forfaits -->
<div class="mb-2">
    <h2 class="font-display text-lg font-semibold text-white mb-4">Nos forfaits</h2>

    @if ($forfaits->isEmpty())

        <div class="text-center py-12 text-[var(--muted)]">
            <i class="fa-solid fa-wifi text-3xl mb-3 opacity-30"></i>
            <p class="text-sm">Aucun forfait disponible pour le moment.</p>
        </div>

    @else

        <div class="space-y-4">
            @foreach ($forfaits as $forfait)
                <div class="recharge-card p-5">

                    <div class="flex items-center justify-between mb-2">
                        <h3 class="font-display font-semibold text-white">
                            {{ $forfait->nom }}
                        </h3>

                        <div class="signal-bars flex items-end">
                            @for ($i = 1; $i <= 4; $i++)
                                <span class="{{ $i <= $forfait->signal_bars ? 'active' : '' }}"></span>
                            @endfor
                        </div>
                    </div>

                    <div class="flex items-baseline gap-1 mb-1">
                        <span class="font-mono text-2xl font-bold text-white">
                            {{ number_format($forfait->prix, 0, ',', ' ') }}
                        </span>
                        <span class="font-mono text-sm text-[var(--signal)]">GNF</span>
                    </div>

                    <div class="text-[var(--muted)] text-sm">
                        {{ $forfait->duree_label }} de connexion
                    </div>

                    @if ($forfait->download_speed || $forfait->upload_speed)
                        <div class="text-xs font-mono text-[var(--muted)] mt-2">
                            ↓ {{ $forfait->download_speed ?? '—' }} · ↑ {{ $forfait->upload_speed ?? '—' }} Mbps
                        </div>
                    @endif

                </div>
            @endforeach
        </div>

    @endif
</div>

<!-- Comment ça marche -->
<div class="mt-10 mb-6">
    <h2 class="font-display text-lg font-semibold text-white mb-5">Comment ça marche</h2>

    <div class="space-y-5">

        <div class="flex gap-4">
            <span class="font-mono text-sm text-[var(--signal)] shrink-0">01</span>
            <div>
                <div class="text-white font-medium text-sm mb-1">Connecte-toi au hotspot</div>
                <div class="text-[var(--muted)] text-sm">Repère le réseau WiFi Zone disponible sur le lieu.</div>
            </div>
        </div>

        <div class="flex gap-4">
            <span class="font-mono text-sm text-[var(--signal)] shrink-0">02</span>
            <div>
                <div class="text-white font-medium text-sm mb-1">Choisis un forfait</div>
                <div class="text-[var(--muted)] text-sm">Sélectionne la durée et entre ton numéro de téléphone.</div>
            </div>
        </div>

        <div class="flex gap-4">
            <span class="font-mono text-sm text-[var(--signal)] shrink-0">03</span>
            <div>
                <div class="text-white font-medium text-sm mb-1">Paye et navigue</div>
                <div class="text-[var(--muted)] text-sm">Orange Money ou MTN MoMo. Accès activé instantanément.</div>
            </div>
        </div>

    </div>
</div>

<!-- Footer -->
<div class="border-t border-white/5 pt-6 pb-4 text-center">

    @if ($parametre->telephone_support || $parametre->email_support)
        <div class="flex items-center justify-center gap-4 text-xs text-[var(--muted)] mb-2">
            @if ($parametre->telephone_support)
                <span class="flex items-center gap-1">
                    <i class="fa-solid fa-phone text-[var(--signal)]"></i>
                    {{ $parametre->telephone_support }}
                </span>
            @endif
            @if ($parametre->email_support)
                <span class="flex items-center gap-1">
                    <i class="fa-solid fa-envelope text-[var(--signal)]"></i>
                    {{ $parametre->email_support }}
                </span>
            @endif
        </div>
    @endif

    <p class="text-[var(--muted)] text-xs">
        {{ $parametre->adresse ?? 'Conakry, Guinée' }} · &copy; {{ date('Y') }} {{ $parametre->nom_entreprise }}
    </p>

</div>
<div class="h-16"></div>
@include('client.partials.bottom-nav')
@endsection