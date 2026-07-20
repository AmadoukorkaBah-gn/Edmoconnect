@extends('layouts.app')

@section('content')

<div class="max-w-full mx-auto">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Tableau de bord</h1>
            <p class="text-gray-500 text-sm">Bienvenue, {{ Auth::user()->name ?? 'Administrateur' }}</p>
        </div>

        <div class="text-sm text-gray-500">
            <a href="{{ route('dashboard') }}" class="text-blue-600 hover:underline">Accueil</a>
            <span class="mx-1">/</span>
            <span>Tableau de bord</span>
        </div>
    </div>

    <!-- Cartes statistiques -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

        <div class="bg-white rounded-xl shadow-sm p-6 flex items-center">
            <div class="w-14 h-14 bg-blue-100 rounded-full flex items-center justify-center mr-4 shrink-0">
                <i class="fa-solid fa-users text-blue-600 text-xl"></i>
            </div>
            <div>
                <div class="text-gray-500 text-sm">Utilisateurs</div>
                <div class="text-2xl font-bold text-gray-800">{{ $stats['total_utilisateurs'] }}</div>
                <div class="text-xs text-blue-600 font-medium">Total utilisateurs</div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 flex items-center">
            <div class="w-14 h-14 bg-green-100 rounded-full flex items-center justify-center mr-4 shrink-0">
                <i class="fa-solid fa-wifi text-green-600 text-xl"></i>
            </div>
            <div>
                <div class="text-gray-500 text-sm">Hotspots</div>
                <div class="text-2xl font-bold text-gray-800">{{ $stats['hotspots_actifs'] }}</div>
                <div class="text-xs text-green-600 font-medium">Total hotspots</div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 flex items-center">
            <div class="w-14 h-14 bg-purple-100 rounded-full flex items-center justify-center mr-4 shrink-0">
                <i class="fa-solid fa-id-card text-purple-600 text-xl"></i>
            </div>
            <div>
                <div class="text-gray-500 text-sm">Abonnements actifs</div>
                <div class="text-2xl font-bold text-gray-800">{{ $stats['abonnements_actifs'] }}</div>
                <div class="text-xs text-purple-600 font-medium">En cours</div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 flex items-center">
            <div class="w-14 h-14 bg-orange-100 rounded-full flex items-center justify-center mr-4 shrink-0">
                <i class="fa-solid fa-dollar-sign text-orange-600 text-xl"></i>
            </div>
            <div>
                <div class="text-gray-500 text-sm">Paiements (ce mois)</div>
                <div class="text-xl font-bold text-gray-800">{{ number_format($stats['paiements_mois'], 0, ',', ' ') }} GNF</div>
                <div class="text-xs text-orange-600 font-medium">Total encaissé</div>
            </div>
        </div>

    </div>

    <!-- Graphiques -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">

        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm p-6">
            <h2 class="font-semibold text-gray-800 mb-4">Statistiques des abonnements</h2>
            <canvas id="abonnementsChart" height="110"></canvas>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
            <h2 class="font-semibold text-gray-800 mb-4">Répartition des utilisateurs</h2>
            <canvas id="repartitionChart" height="180"></canvas>

            <div class="mt-4 space-y-2 text-sm">
                <div class="flex items-center justify-between">
                    <span class="flex items-center"><span class="w-2.5 h-2.5 rounded-full bg-blue-500 mr-2"></span>Actifs</span>
                    <span class="text-gray-600">{{ $repartitionUtilisateurs['active'] }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="flex items-center"><span class="w-2.5 h-2.5 rounded-full bg-green-500 mr-2"></span>Inactifs</span>
                    <span class="text-gray-600">{{ $repartitionUtilisateurs['inactive'] }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="flex items-center"><span class="w-2.5 h-2.5 rounded-full bg-orange-500 mr-2"></span>Bloqués</span>
                    <span class="text-gray-600">{{ $repartitionUtilisateurs['blocked'] }}</span>
                </div>
            </div>
        </div>

    </div>

    <!-- Tableaux -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <h2 class="font-semibold text-gray-800">Derniers abonnements</h2>
            </div>

            <table class="w-full text-left text-sm">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3 font-semibold text-gray-600">Utilisateur</th>
                        <th class="px-4 py-3 font-semibold text-gray-600">Forfait</th>
                        <th class="px-4 py-3 font-semibold text-gray-600">Hotspot</th>
                        <th class="px-4 py-3 font-semibold text-gray-600">Fin</th>
                        <th class="px-4 py-3 font-semibold text-gray-600">Statut</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($derniersAbonnements as $abonnement)
                        <tr>
                            <td class="px-4 py-3 text-gray-800">{{ $abonnement->user->name ?? '—' }}</td>
                            <td class="px-4 py-3 text-gray-700">{{ $abonnement->forfait->nom ?? '—' }}</td>
                            <td class="px-4 py-3 text-gray-700">{{ $abonnement->hotspot->name ?? '—' }}</td>
                            <td class="px-4 py-3 text-gray-700">{{ $abonnement->date_fin->format('d/m/Y') }}</td>
                            <td class="px-4 py-3">
                                <span class="text-xs font-medium px-2.5 py-1 rounded-full bg-green-100 text-green-700">
                                    {{ $abonnement->statut }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-gray-400">Aucun abonnement récent.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <h2 class="font-semibold text-gray-800">Derniers paiements</h2>
            </div>

            <table class="w-full text-left text-sm">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3 font-semibold text-gray-600">Utilisateur</th>
                        <th class="px-4 py-3 font-semibold text-gray-600">Montant</th>
                        <th class="px-4 py-3 font-semibold text-gray-600">Méthode</th>
                        <th class="px-4 py-3 font-semibold text-gray-600">Date</th>
                        <th class="px-4 py-3 font-semibold text-gray-600">Statut</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($derniersPaiements as $paiement)
                        <tr>
                            <td class="px-4 py-3 text-gray-800">{{ $paiement->user->name ?? '—' }}</td>
                            <td class="px-4 py-3 text-gray-700">{{ number_format($paiement->montant, 0, ',', ' ') }} GNF</td>
                            <td class="px-4 py-3 text-gray-700">{{ $paiement->methode ?? '-' }}</td>
                            <td class="px-4 py-3 text-gray-700">{{ $paiement->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-4 py-3">
                                <span class="text-xs font-medium px-2.5 py-1 rounded-full bg-green-100 text-green-700">
                                    Réussi
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-gray-400">Aucun paiement récent.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

</div>

@endsection

@push('scripts')
<script>
    // Graphique 1 : Nouveaux abonnements vs Abonnements actifs
    new Chart(document.getElementById('abonnementsChart'), {
        type: 'line',
        data: {
            labels: @json($chartLabels),
            datasets: [
                {
                    label: 'Nouveaux abonnements',
                    data: @json($chartNouveauxAbonnements),
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.3,
                    fill: true,
                },
                {
                    label: 'Abonnements actifs',
                    data: @json($chartAbonnementsActifs),
                    borderColor: '#22c55e',
                    backgroundColor: 'rgba(34, 197, 94, 0.1)',
                    tension: 0.3,
                    fill: true,
                }
            ]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'top' } },
            scales: { y: { beginAtZero: true } }
        }
    });

    // Graphique 2 : Répartition des utilisateurs
    new Chart(document.getElementById('repartitionChart'), {
        type: 'doughnut',
        data: {
            labels: ['Actifs', 'Inactifs', 'Bloqués'],
            datasets: [{
                data: [
                    {{ $repartitionUtilisateurs['active'] }},
                    {{ $repartitionUtilisateurs['inactive'] }},
                    {{ $repartitionUtilisateurs['blocked'] }}
                ],
                backgroundColor: ['#3b82f6', '#22c55e', '#f97316'],
                borderWidth: 0,
            }]
        },
        options: {
            responsive: true,
            cutout: '65%',
            plugins: {
                legend: { display: false }
            }
        }
    });
</script>
@endpush