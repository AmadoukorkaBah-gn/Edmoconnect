@extends('layouts.app')

@section('content')

<div class="max-w-3xl mx-auto">

    <div class="flex items-center mb-6">
        <a href="{{ route('paiements.index') }}" class="text-gray-500 hover:text-gray-700 mr-4">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Détail du paiement</h1>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-8 space-y-6">

        <div class="grid grid-cols-2 gap-6">

            <div>
                <div class="text-sm text-gray-500 mb-1">Référence</div>
                <code class="bg-gray-100 px-2 py-1 rounded text-sm">{{ $paiement->reference }}</code>
            </div>

            <div>
                <div class="text-sm text-gray-500 mb-1">Montant</div>
                <div class="font-semibold text-gray-800">{{ number_format($paiement->montant, 0, ',', ' ') }} GNF</div>
            </div>

            <div>
                <div class="text-sm text-gray-500 mb-1">Client</div>
                <div class="text-gray-800">{{ $paiement->user->name ?? '—' }}</div>
            </div>

            <div>
                <div class="text-sm text-gray-500 mb-1">Forfait</div>
                <div class="text-gray-800">{{ $paiement->forfait->nom ?? '—' }}</div>
            </div>

            <div>
                <div class="text-sm text-gray-500 mb-1">Hotspot</div>
                <div class="text-gray-800">{{ $paiement->hotspot->name ?? '—' }}</div>
            </div>

            <div>
                <div class="text-sm text-gray-500 mb-1">Méthode</div>
                <div class="text-gray-800">{{ $paiement->methode ?? '-' }}</div>
            </div>

            <div>
                <div class="text-sm text-gray-500 mb-1">Statut</div>
                <div class="text-gray-800">{{ $paiement->statut }}</div>
            </div>

            <div>
                <div class="text-sm text-gray-500 mb-1">Date</div>
                <div class="text-gray-800">{{ $paiement->created_at->format('d/m/Y H:i') }}</div>
            </div>

        </div>

        @if ($paiement->response_api)
            <div>
                <div class="text-sm text-gray-500 mb-2">Réponse brute de l'API Djomy</div>
                <pre class="bg-gray-900 text-green-400 text-xs p-4 rounded-lg overflow-x-auto">{{ json_encode($paiement->response_api, JSON_PRETTY_PRINT) }}</pre>
            </div>
        @endif

    </div>

</div>

@endsection