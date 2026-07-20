@extends('layouts.app')

@section('content')

<div class="max-w-4xl mx-auto">

    <div class="flex items-center mb-6">
        <a href="{{ route('clients.index') }}" class="text-gray-500 hover:text-gray-700 mr-4">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <div class="flex items-center">
            <img src="https://ui-avatars.com/api/?name={{ urlencode($client->name) }}&background=2563eb&color=fff"
                class="w-12 h-12 rounded-full mr-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">{{ $client->name }}</h1>
                <p class="text-gray-500 text-sm">{{ $client->telephone }}</p>
            </div>
        </div>
    </div>

    <!-- KPIs -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="text-gray-500 text-sm mb-2">Total depense</div>
            <div class="text-2xl font-bold text-gray-800">{{ number_format($totalDepense, 0, ',', ' ') }} GNF</div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="text-gray-500 text-sm mb-2">Nombre d'abonnements</div>
            <div class="text-2xl font-bold text-gray-800">{{ $client->abonnements->count() }}</div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="text-gray-500 text-sm mb-2">Abonnement actif</div>
            @if ($abonnementActif)
                <div class="text-lg font-semibold text-green-600">
                    Oui, jusqu'au {{ $abonnementActif->date_fin->format('d/m/Y H:i') }}
                </div>
            @else
                <div class="text-lg font-semibold text-gray-400">Aucun</div>
            @endif
        </div>

    </div>

    <!-- Historique abonnements -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-8">
        <div class="p-6 border-b border-gray-100">
            <h2 class="font-semibold text-gray-800">Historique des abonnements</h2>
        </div>

        <table class="w-full text-left text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-4 py-3 font-semibold text-gray-600">Hotspot</th>
                    <th class="px-4 py-3 font-semibold text-gray-600">Forfait</th>
                    <th class="px-4 py-3 font-semibold text-gray-600">Debut</th>
                    <th class="px-4 py-3 font-semibold text-gray-600">Fin</th>
                    <th class="px-4 py-3 font-semibold text-gray-600">Statut</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($client->abonnements as $abonnement)
                    <tr>
                        <td class="px-4 py-3 text-gray-800">{{ $abonnement->hotspot->name ?? '—' }}</td>
                        <td class="px-4 py-3 text-gray-700">{{ $abonnement->forfait->nom ?? '—' }}</td>
                        <td class="px-4 py-3 text-gray-700">{{ $abonnement->date_debut->format('d/m/Y H:i') }}</td>
                        <td class="px-4 py-3 text-gray-700">{{ $abonnement->date_fin->format('d/m/Y H:i') }}</td>
                        <td class="px-4 py-3">
                            @php
                                $statutColors = [
                                    'pending' => 'bg-yellow-100 text-yellow-700',
                                    'active' => 'bg-green-100 text-green-700',
                                    'expired' => 'bg-gray-100 text-gray-500',
                                    'suspended' => 'bg-orange-100 text-orange-700',
                                    'cancelled' => 'bg-red-100 text-red-700',
                                ];
                            @endphp
                            <span class="{{ $statutColors[$abonnement->statut] ?? 'bg-gray-100 text-gray-500' }} text-xs font-medium px-2.5 py-1 rounded-full">
                                {{ $abonnement->statut }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-gray-400">Aucun abonnement.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Historique paiements -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-100">
            <h2 class="font-semibold text-gray-800">Historique des paiements</h2>
        </div>

        <table class="w-full text-left text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-4 py-3 font-semibold text-gray-600">Reference</th>
                    <th class="px-4 py-3 font-semibold text-gray-600">Forfait</th>
                    <th class="px-4 py-3 font-semibold text-gray-600">Montant</th>
                    <th class="px-4 py-3 font-semibold text-gray-600">Methode</th>
                    <th class="px-4 py-3 font-semibold text-gray-600">Statut</th>
                    <th class="px-4 py-3 font-semibold text-gray-600">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($client->paiements as $paiement)
                    <tr>
                        <td class="px-4 py-3">
                            <code class="bg-gray-100 px-2 py-1 rounded text-xs">{{ $paiement->reference }}</code>
                        </td>
                        <td class="px-4 py-3 text-gray-700">{{ $paiement->forfait->nom ?? '—' }}</td>
                        <td class="px-4 py-3 text-gray-700 font-mono">{{ number_format($paiement->montant, 0, ',', ' ') }} GNF</td>
                        <td class="px-4 py-3 text-gray-700">{{ $paiement->methode ?? '-' }}</td>
                        <td class="px-4 py-3">
                            @php
                                $pStatutColors = [
                                    'pending' => 'bg-yellow-100 text-yellow-700',
                                    'success' => 'bg-green-100 text-green-700',
                                    'failed' => 'bg-red-100 text-red-700',
                                    'cancelled' => 'bg-gray-100 text-gray-500',
                                ];
                            @endphp
                            <span class="{{ $pStatutColors[$paiement->statut] ?? 'bg-gray-100 text-gray-500' }} text-xs font-medium px-2.5 py-1 rounded-full">
                                {{ $paiement->statut }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-700 text-xs">{{ $paiement->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-gray-400">Aucun paiement.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

@endsection