@extends('layouts.app')

@section('content')

<div class="h-full flex flex-col gap-4">

    {{-- En-tête --}}
    <div class="flex items-center justify-between shrink-0">
        <div>
            <h1 class="text-xl font-bold text-gray-800">Tableau de bord</h1>
            <p class="text-gray-500 text-xs">Bienvenue, {{ Auth::user()->name ?? 'Administrateur' }}</p>
        </div>

        <div class="text-xs text-gray-500">
            <a href="{{ route('dashboard') }}" class="text-blue-600 hover:underline">Accueil</a>
            <span class="mx-1">/</span>
            <span>Tableau de bord</span>
        </div>
    </div>

    <!-- Cartes statistiques -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 shrink-0">

        <div class="bg-white rounded-xl shadow-sm p-4 flex items-center">
            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3 shrink-0">
                <i class="fa-solid fa-users text-blue-600"></i>
            </div>
            <div class="min-w-0">
                <div class="text-gray-500 text-xs">Utilisateurs</div>
                <div class="text-xl font-bold text-gray-800">{{ $stats['total_utilisateurs'] }}</div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-4 flex items-center">
            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-3 shrink-0">
                <i class="fa-solid fa-wifi text-green-600"></i>
            </div>
            <div class="min-w-0">
                <div class="text-gray-500 text-xs">Hotspots</div>
                <div class="text-xl font-bold text-gray-800">{{ $stats['hotspots_actifs'] }}</div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-4 flex items-center">
            <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center mr-3 shrink-0">
                <i class="fa-solid fa-id-card text-purple-600"></i>
            </div>
            <div class="min-w-0">
                <div class="text-gray-500 text-xs">Abonnements actifs</div>
                <div class="text-xl font-bold text-gray-800">{{ $stats['abonnements_actifs'] }}</div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-4 flex items-center">
            <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center mr-3 shrink-0">
                <i class="fa-solid fa-dollar-sign text-orange-600"></i>
            </div>
            <div class="min-w-0">
                <div class="text-gray-500 text-xs">Paiements (mois)</div>
                <div class="text-lg font-bold text-gray-800 truncate">{{ number_format($stats['paiements_mois'], 0, ',', ' ') }} GNF</div>
            </div>
        </div>

    </div>

    {{-- Zone restante : tableaux, calée sur toute la hauteur disponible --}}
    <div class="flex-1 min-h-0">

        <!-- Tableaux -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 h-full min-h-0">

            <div class="bg-white rounded-xl shadow-sm overflow-hidden flex flex-col min-h-0">
                <div class="px-4 py-3 border-b border-gray-100 shrink-0">
                    <h2 class="font-semibold text-gray-800 text-sm">Derniers abonnements</h2>
                </div>

                <div class="flex-1 min-h-0 overflow-y-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-gray-50 border-b border-gray-200 sticky top-0">
                            <tr>
                                <th class="px-3 py-2 font-semibold text-gray-600">Utilisateur</th>
                                <th class="px-3 py-2 font-semibold text-gray-600">Forfait</th>
                                <th class="px-3 py-2 font-semibold text-gray-600">Hotspot</th>
                                <th class="px-3 py-2 font-semibold text-gray-600">Fin</th>
                                <th class="px-3 py-2 font-semibold text-gray-600">Statut</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($derniersAbonnements as $abonnement)
                                <tr>
                                    <td class="px-3 py-2 text-gray-800">{{ $abonnement->user->name ?? '—' }}</td>
                                    <td class="px-3 py-2 text-gray-700">{{ $abonnement->forfait->nom ?? '—' }}</td>
                                    <td class="px-3 py-2 text-gray-700">{{ $abonnement->hotspot->name ?? '—' }}</td>
                                    <td class="px-3 py-2 text-gray-700">{{ $abonnement->date_fin->format('d/m/Y') }}</td>
                                    <td class="px-3 py-2">
                                        <span class="text-[10px] font-medium px-2 py-0.5 rounded-full bg-green-100 text-green-700">
                                            {{ $abonnement->statut }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-6 text-center text-gray-400">Aucun abonnement récent.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm overflow-hidden flex flex-col min-h-0">
                <div class="px-4 py-3 border-b border-gray-100 shrink-0">
                    <h2 class="font-semibold text-gray-800 text-sm">Derniers paiements</h2>
                </div>

                <div class="flex-1 min-h-0 overflow-y-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-gray-50 border-b border-gray-200 sticky top-0">
                            <tr>
                                <th class="px-3 py-2 font-semibold text-gray-600">Utilisateur</th>
                                <th class="px-3 py-2 font-semibold text-gray-600">Montant</th>
                                <th class="px-3 py-2 font-semibold text-gray-600">Méthode</th>
                                <th class="px-3 py-2 font-semibold text-gray-600">Date</th>
                                <th class="px-3 py-2 font-semibold text-gray-600">Statut</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($derniersPaiements as $paiement)
                                <tr>
                                    <td class="px-3 py-2 text-gray-800">{{ $paiement->user->name ?? '—' }}</td>
                                    <td class="px-3 py-2 text-gray-700">{{ number_format($paiement->montant, 0, ',', ' ') }} GNF</td>
                                    <td class="px-3 py-2 text-gray-700">{{ $paiement->methode ?? '-' }}</td>
                                    <td class="px-3 py-2 text-gray-700">{{ $paiement->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="px-3 py-2">
                                        <span class="text-[10px] font-medium px-2 py-0.5 rounded-full bg-green-100 text-green-700">
                                            Réussi
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-6 text-center text-gray-400">Aucun paiement récent.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

    </div>

</div>

@endsection


