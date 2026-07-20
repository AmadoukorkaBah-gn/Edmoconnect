@extends('layouts.app')

@section('content')

<div class="max-w-6xl mx-auto">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Clients</h1>
            <p class="text-gray-500 text-sm">Clients ayant achete un acces WiFi</p>
        </div>
    </div>

    <form action="{{ route('clients.index') }}" method="GET" class="mb-6">
        <div class="relative max-w-md">
            <input type="text" name="q" value="{{ $recherche }}" placeholder="Rechercher par nom ou telephone..."
                class="w-full rounded-lg border-gray-300 pl-10 pr-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            <i class="fa-solid fa-search absolute left-3 top-3 text-gray-400"></i>
        </div>
    </form>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">

        <table class="w-full text-left">

            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">Client</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">Abonnements</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">Total depense</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">Statut</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600 text-right">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-100">

                @forelse ($clients as $client)
                    <tr class="hover:bg-gray-50 transition">

                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($client->name) }}&background=2563eb&color=fff"
                                    class="w-9 h-9 rounded-full mr-3">
                                <div>
                                    <div class="font-medium text-gray-800">{{ $client->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $client->telephone }}</div>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-4 text-gray-700">
                            {{ $client->abonnements_count }}
                        </td>

                        <td class="px-6 py-4 text-gray-700 font-mono">
                            {{ number_format($client->total_depense ?? 0, 0, ',', ' ') }} GNF
                        </td>

                        <td class="px-6 py-4">
                            @php
                                $statusColors = [
                                    'active' => 'bg-green-100 text-green-700',
                                    'inactive' => 'bg-gray-100 text-gray-500',
                                    'blocked' => 'bg-red-100 text-red-700',
                                ];
                                $statusLabels = [
                                    'active' => 'Actif',
                                    'inactive' => 'Inactif',
                                    'blocked' => 'Bloque',
                                ];
                            @endphp
                            <span class="{{ $statusColors[$client->status] ?? 'bg-gray-100 text-gray-500' }} text-xs font-medium px-3 py-1 rounded-full">
                                {{ $statusLabels[$client->status] ?? $client->status }}
                            </span>
                        </td>

                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('clients.show', $client) }}"
                                class="text-blue-600 hover:text-blue-800" title="Voir le profil">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-gray-400">
                            Aucun client pour le moment.
                        </td>
                    </tr>
                @endforelse

            </tbody>

        </table>

    </div>

    <div class="mt-6">
        {{ $clients->links() }}
    </div>

</div>

@endsection