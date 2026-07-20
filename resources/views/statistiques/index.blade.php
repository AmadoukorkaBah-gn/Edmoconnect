@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Statistiques</h1>
            <p class="text-gray-500 text-sm">Analyse de la periode selectionnee</p>
        </div>
    </div>

    <!-- Filtre de periode -->
    <form action="{{ route('statistiques.index') }}" method="GET" class="bg-white rounded-xl shadow-sm p-6 mb-6 flex items-end gap-4 flex-wrap">

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Du</label>
            <input type="date" name="debut" value="{{ $debut->format('Y-m-d') }}"
                class="rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Au</label>
            <input type="date" name="fin" value="{{ $fin->format('Y-m-d') }}"
                class="rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>

        <button type="submit"
            class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg font-medium transition">
            Filtrer
        </button>

        <div class="flex gap-2 ml-auto">
            <a href="{{ route('statistiques.index', ['debut' => now()->subDays(6)->format('Y-m-d'), 'fin' => now()->format('Y-m-d')]) }}"
                class="text-sm px-3 py-2 rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50">7 jours</a>
            <a href="{{ route('statistiques.index', ['debut' => now()->subDays(29)->format('Y-m-d'), 'fin' => now()->format('Y-m-d')]) }}"
                class="text-sm px-3 py-2 rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50">30 jours</a>
            <a href="{{ route('statistiques.index', ['debut' => now()->startOfMonth()->format('Y-m-d'), 'fin' => now()->format('Y-m-d')]) }}"
                class="text-sm px-3 py-2 rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50">Ce mois</a>
        </div>

    </form>

    <!-- KPIs -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="text-gray-500 text-sm mb-2">Revenu total</div>
            <div class="text-2xl font-bold text-gray-800">{{ number_format($revenuTotal, 0, ',', ' ') }} GNF</div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="text-gray-500 text-sm mb-2">Paiements reussis</div>
            <div class="text-2xl font-bold text-gray-800">{{ $nombrePaiements }}</div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="text-gray-500 text-sm mb-2">Panier moyen</div>
            <div class="text-2xl font-bold text-gray-800">{{ number_format($panierMoyen, 0, ',', ' ') }} GNF</div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="text-gray-500 text-sm mb-2">Taux d'echec</div>
            <div class="text-2xl font-bold {{ $tauxEchec > 20 ? 'text-red-600' : 'text-gray-800' }}">
                {{ $tauxEchec }}%
            </div>
        </div>

    </div>

    <!-- Graphique revenus -->
    <div class="bg-white rounded-xl shadow-sm p-6 mb-8">
        <h2 class="font-semibold text-gray-800 mb-4">Evolution des revenus</h2>
        <canvas id="revenusChart" height="90"></canvas>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Top forfaits -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <h2 class="font-semibold text-gray-800">Forfaits les plus vendus</h2>
            </div>

            <table class="w-full text-left text-sm">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3 font-semibold text-gray-600">Forfait</th>
                        <th class="px-4 py-3 font-semibold text-gray-600">Ventes</th>
                        <th class="px-4 py-3 font-semibold text-gray-600">Revenu</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($topForfaits as $ligne)
                        <tr>
                            <td class="px-4 py-3 text-gray-800">{{ $ligne->forfait->nom ?? '—' }}</td>
                            <td class="px-4 py-3 text-gray-700">{{ $ligne->nb_ventes }}</td>
                            <td class="px-4 py-3 text-gray-700">{{ number_format($ligne->revenu, 0, ',', ' ') }} GNF</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-4 py-8 text-center text-gray-400">Aucune vente sur cette periode.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Par hotspot -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <h2 class="font-semibold text-gray-800">Revenus par hotspot</h2>
            </div>

            <table class="w-full text-left text-sm">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3 font-semibold text-gray-600">Hotspot</th>
                        <th class="px-4 py-3 font-semibold text-gray-600">Ventes</th>
                        <th class="px-4 py-3 font-semibold text-gray-600">Revenu</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($parHotspot as $ligne)
                        <tr>
                            <td class="px-4 py-3 text-gray-800">{{ $ligne->hotspot->name ?? '—' }}</td>
                            <td class="px-4 py-3 text-gray-700">{{ $ligne->nb_ventes }}</td>
                            <td class="px-4 py-3 text-gray-700">{{ number_format($ligne->revenu, 0, ',', ' ') }} GNF</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-4 py-8 text-center text-gray-400">Aucune vente sur cette periode.</td>
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
    new Chart(document.getElementById('revenusChart'), {
        type: 'line',
        data: {
            labels: @json($chartLabels),
            datasets: [{
                label: 'Revenus (GNF)',
                data: @json($chartData),
                borderColor: '#2563eb',
                backgroundColor: 'rgba(37, 99, 235, 0.1)',
                tension: 0.3,
                fill: true,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
        }
    });
</script>
@endpush