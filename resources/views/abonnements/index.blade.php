@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Abonnements</h1>
            <p class="text-gray-500 text-sm">Suivi des abonnements clients</p>
        </div>

        <a href="{{ route('abonnements.create') }}"
            class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg font-medium transition inline-flex items-center">
            <i class="fa-solid fa-plus mr-2"></i>
            Nouvel abonnement
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
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">Client</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">Hotspot</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">Forfait</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">Début</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">Fin</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">Statut</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600 text-right">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-100">

                @forelse ($abonnements as $abonnement)
                    <tr class="hover:bg-gray-50 transition">

                        <td class="px-6 py-4 font-medium text-gray-800">
                            {{ $abonnement->user->name ?? '—' }}
                        </td>

                        <td class="px-6 py-4 text-gray-700">
                            {{ $abonnement->hotspot->name ?? '—' }}
                        </td>

                        <td class="px-6 py-4 text-gray-700">
                            {{ $abonnement->forfait->nom ?? '—' }}
                        </td>

                        <td class="px-6 py-4 text-gray-700 text-sm">
                            {{ $abonnement->date_debut->format('d/m/Y H:i') }}
                        </td>

                        <td class="px-6 py-4 text-gray-700 text-sm">
                            {{ $abonnement->date_fin->format('d/m/Y H:i') }}
                        </td>

                        <td class="px-6 py-4">
                            @php
                                $statutColors = [
                                    'pending' => 'bg-yellow-100 text-yellow-700',
                                    'active' => 'bg-green-100 text-green-700',
                                    'expired' => 'bg-gray-100 text-gray-500',
                                    'suspended' => 'bg-orange-100 text-orange-700',
                                    'cancelled' => 'bg-red-100 text-red-700',
                                ];
                                $statutLabels = [
                                    'pending' => 'En attente',
                                    'active' => 'Actif',
                                    'expired' => 'Expiré',
                                    'suspended' => 'Suspendu',
                                    'cancelled' => 'Annulé',
                                ];
                            @endphp
                            <span class="{{ $statutColors[$abonnement->statut] ?? 'bg-gray-100 text-gray-500' }} text-xs font-medium px-3 py-1 rounded-full">
                                {{ $statutLabels[$abonnement->statut] ?? $abonnement->statut }}
                            </span>
                        </td>

                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end space-x-3">
                                <a href="{{ route('abonnements.edit', $abonnement) }}"
                                    class="text-blue-600 hover:text-blue-800" title="Modifier">
                                    <i class="fa-solid fa-pen"></i>
                                </a>

                                <form action="{{ route('abonnements.destroy', $abonnement) }}" method="POST"
                                    onsubmit="return confirm('Supprimer cet abonnement définitivement ?');">
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
                            Aucun abonnement pour le moment.
                        </td>
                    </tr>
                @endforelse

            </tbody>

        </table>

    </div>

    <div class="mt-6">
        {{ $abonnements->links() }}
    </div>

</div>

@endsection