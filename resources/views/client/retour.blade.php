@extends('layouts.client')

@section('title', 'Résultat du paiement — WiFi Zone')

@section('content')

<div class="flex flex-col items-center text-center py-10">

    @if ($paiement->statut === 'success')

        <div class="w-20 h-20 rounded-full bg-green-500/10 border border-green-500/30 flex items-center justify-center mb-6">
            <i class="fa-solid fa-check text-green-400 text-3xl"></i>
        </div>

        <h1 class="font-display text-2xl font-semibold text-white mb-2">Paiement réussi !</h1>
        <p class="text-[var(--muted)] mb-8">Ton accès WiFi est maintenant actif.</p>

        <div class="recharge-card p-6 w-full text-left mb-6">
            <div class="flex justify-between mb-3">
                <span class="text-[var(--muted)] text-sm">Forfait</span>
                <span class="text-white font-medium">{{ $paiement->forfait->nom ?? '—' }}</span>
            </div>
            <div class="flex justify-between mb-3">
                <span class="text-[var(--muted)] text-sm">Montant</span>
                <span class="text-white font-mono">{{ number_format($paiement->montant, 0, ',', ' ') }} GNF</span>
            </div>
            <div class="flex justify-between">
                <span class="text-[var(--muted)] text-sm">Référence</span>
                <span class="text-white font-mono text-xs">{{ $paiement->reference }}</span>
            </div>
        </div>

        <a href="{{ route('client.abonnement') }}?telephone={{ $paiement->user->telephone ?? '' }}"
            class="w-full bg-[var(--signal-deep)] hover:bg-blue-700 text-white font-medium py-4 rounded-xl transition text-center">
            Voir mon abonnement
        </a>

    @elseif ($paiement->statut === 'pending')

        <div class="w-20 h-20 rounded-full bg-yellow-500/10 border border-yellow-500/30 flex items-center justify-center mb-6">
            <i class="fa-solid fa-clock text-yellow-400 text-3xl"></i>
        </div>

        <h1 class="font-display text-2xl font-semibold text-white mb-2">Paiement en attente</h1>
        <p class="text-[var(--muted)] mb-8">
            Ta transaction est en cours de confirmation. Ça peut prendre quelques instants.
        </p>

        <button onclick="location.reload()"
            class="w-full bg-[var(--signal-deep)] hover:bg-blue-700 text-white font-medium py-4 rounded-xl transition">
            Actualiser
        </button>

    @else

        <div class="w-20 h-20 rounded-full bg-red-500/10 border border-red-500/30 flex items-center justify-center mb-6">
            <i class="fa-solid fa-xmark text-red-400 text-3xl"></i>
        </div>

        <h1 class="font-display text-2xl font-semibold text-white mb-2">Paiement échoué</h1>
        <p class="text-[var(--muted)] mb-8">Le paiement n'a pas pu être confirmé. Réessaie.</p>

        <a href="{{ route('client.paiement', [$paiement->hotspot, $paiement->forfait]) }}"
            class="w-full bg-[var(--signal-deep)] hover:bg-blue-700 text-white font-medium py-4 rounded-xl transition text-center">
            Réessayer
        </a>

    @endif

</div>

@endsection