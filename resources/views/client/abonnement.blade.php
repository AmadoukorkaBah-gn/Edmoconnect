@extends('layouts.client')

@section('title', 'Mon abonnement — WiFi Zone')

@section('content')

<h1 class="font-display text-2xl font-semibold text-white mb-6">
    Mon abonnement
</h1>

@if ($abonnement)

    <div class="recharge-card p-6">

        <div class="flex items-center justify-between mb-6">

            <span class="inline-flex items-center gap-2 text-green-400 text-xs font-medium bg-green-500/10 border border-green-500/30 rounded-full px-3 py-1">
                <i class="fa-solid fa-circle text-[6px]"></i>
                Abonnement actif
            </span>

            <span class="text-[var(--muted)] text-xs font-mono">
                {{ $abonnement->hotspot->name ?? '—' }}
            </span>

        </div>

        <div class="text-[var(--muted)] text-sm mb-1">
            Forfait
        </div>

        <div class="font-display text-xl font-semibold text-white mb-6">
            {{ $abonnement->forfait->nom ?? '—' }}
        </div>

        <div class="grid grid-cols-2 gap-4 text-sm">

            <div>

                <div class="text-[var(--muted)] mb-1">
                    Valide jusqu'au
                </div>

                <div class="text-white font-mono">
                    {{ $abonnement->date_fin->format('d/m/Y H:i') }}
                </div>

            </div>

            <div>

                <div class="text-[var(--muted)] mb-1">
                    Temps restant
                </div>

                <div class="text-white font-mono"
                     id="compteRebours"
                     data-fin="{{ $abonnement->date_fin->toIso8601String() }}">
                    —
                </div>

            </div>

        </div>

    </div>

    <script>

        const el = document.getElementById('compteRebours');
        const fin = new Date(el.dataset.fin).getTime();

        function tick(){

            const now = new Date().getTime();
            const diff = fin - now;

            if(diff <= 0){

                el.textContent = "Expiré";
                return;

            }

            const h = Math.floor(diff/(1000*60*60));
            const m = Math.floor((diff%(1000*60*60))/(1000*60));

            el.textContent = h + "h " + m + "min";

        }

        tick();

        setInterval(tick,60000);

    </script>

@elseif(isset($ticket) && $ticket)

    <div class="recharge-card p-6">

        <div class="flex items-center justify-between mb-6">

            <span class="inline-flex items-center gap-2 text-blue-400 text-xs font-medium bg-blue-500/10 border border-blue-500/30 rounded-full px-3 py-1">

                <i class="fa-solid fa-ticket"></i>

                Ticket actif

            </span>

        </div>

        <div class="space-y-5">

            <div>

                <div class="text-[var(--muted)] text-sm">
                    Identifiant WiFi
                </div>

                <div class="text-white font-mono text-lg">
                    {{ $ticket->username }}
                </div>

            </div>

            <div>

                <div class="text-[var(--muted)] text-sm">
                    Mot de passe
                </div>

                <div class="text-white font-mono text-lg">
                    {{ $ticket->password }}
                </div>

            </div>

            <div>

                <div class="text-[var(--muted)] text-sm">
                    Forfait
                </div>

                <div class="text-white">
                    {{ $ticket->forfait->nom ?? '—' }}
                </div>

            </div>

            <div>

                <div class="text-[var(--muted)] text-sm">
                    Activé le
                </div>

                <div class="text-white">
                    {{ optional($ticket->activated_at)->format('d/m/Y H:i') }}
                </div>

            </div>

        </div>

    </div>

@else

    <div class="recharge-card p-6">

        <p class="text-[var(--muted)] text-sm mb-4">
            Entrez votre numéro de téléphone pour retrouver votre connexion.
        </p>

        <form action="{{ route('client.abonnement') }}" method="GET">

            <input
                type="tel"
                name="telephone"
                value="{{ $telephone }}"
                placeholder="Ex : 620 00 00 00"
                class="w-full rounded-xl px-4 py-3.5 mb-4">

            <button
                type="submit"
                class="w-full bg-[var(--signal-deep)] hover:bg-blue-700 text-white font-medium py-3.5 rounded-xl transition">

                Rechercher

            </button>

        </form>

        @if($telephone)

            <p class="text-[var(--muted)] text-sm mt-4 text-center">

                Aucun abonnement ou ticket actif trouvé pour ce numéro.

            </p>

        @endif

    </div>

@endif

<div class="h-16"></div>

@include('client.partials.bottom-nav')

@endsection