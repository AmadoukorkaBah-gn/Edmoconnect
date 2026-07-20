@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Paiements</h1>
            <p class="text-gray-500 text-sm">Historique des transactions</p>
        </div>

        <a href="{{ route('paiements.create') }}"
            class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg font-medium transition inline-flex items-center">
            <i class="fa-solid fa-plus mr-2"></i>
            Enregistrer un paiement cash
        </a>
    </div>

    @if (session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">

        <table class="w-full text-left">

            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">Référence</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">Client</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">Forfait</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">Montant</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">Méthode</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">Statut</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600 text-right">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-100">

                @forelse ($paiements as $paiement)
                    <tr class="hover:bg-gray-50 transition">

                        <td class="px-6 py-4">
                            <code class="bg-gray-100 px-2 py-1 rounded text-xs">{{ $paiement->reference }}</code>
                        </td>

                        <td class="px-6 py-4 font-medium text-gray-800">
                            {{ $paiement->user->name ?? '—' }}
                        </td>

                        <td class="px-6 py-4 text-gray-700">
                            {{ $paiement->forfait->nom ?? '—' }}
                        </td>

                        <td class="px-6 py-4 text-gray-700">
                            {{ number_format($paiement->montant, 0, ',', ' ') }} GNF
                        </td>

                        <td class="px-6 py-4 text-gray-700 text-sm">
                            {{ $paiement->methode ?? '-' }}
                        </td>

                        <td class="px-6 py-4">
                            @php
                                $statutColors = [
                                    'pending' => 'bg-yellow-100 text-yellow-700',
                                    'success' => 'bg-green-100 text-green-700',
                                    'failed' => 'bg-red-100 text-red-700',
                                    'cancelled' => 'bg-gray-100 text-gray-500',
                                ];
                                $statutLabels = [
                                    'pending' => 'En attente',
                                    'success' => 'Réussi',
                                    'failed' => 'Échoué',
                                    'cancelled' => 'Annulé',
                                ];
                            @endphp
                            <span class="{{ $statutColors[$paiement->statut] ?? 'bg-gray-100 text-gray-500' }} text-xs font-medium px-3 py-1 rounded-full">
                                {{ $statutLabels[$paiement->statut] ?? $paiement->statut }}
                            </span>
                        </td>

                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end space-x-3">
                                <a href="{{ route('paiements.show', $paiement) }}"
                                    class="text-gray-600 hover:text-gray-800" title="Voir le détail">
                                    <i class="fa-solid fa-eye"></i>
                                </a>

                                <form action="{{ route('paiements.destroy', $paiement) }}" method="POST"
                                    onsubmit="return confirm('Supprimer ce paiement définitivement ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800" title="Supprimer">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-10 text-center text-gray-400">
                            Aucun paiement pour le moment.
                        </td>
                    </tr>
                @endforelse

            </tbody>

        </table>

    </div>

    <div class="mt-6">
        {{ $paiements->links() }}
    </div>

</div>

@endsection