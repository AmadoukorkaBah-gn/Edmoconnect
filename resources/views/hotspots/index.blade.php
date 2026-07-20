@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Hotspots</h1>
            <p class="text-gray-500 text-sm">Gestion des points d'accès WiFi</p>
        </div>

        <a href="{{ route('hotspots.create') }}"
            class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg font-medium transition inline-flex items-center">
            <i class="fa-solid fa-plus mr-2"></i>
            Nouveau hotspot
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
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">Nom</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">Serveur MikroTik</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">Interface</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">Adresse</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">Statut</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600 text-right">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-100">

                @forelse ($hotspots as $hotspot)
                    <tr class="hover:bg-gray-50 transition">

                        <td class="px-6 py-4 font-medium text-gray-800">{{ $hotspot->name }}</td>

                        <td class="px-6 py-4 text-gray-700">
                            @if ($hotspot->mikrotikServer)
                                <span class="inline-flex items-center">
                                    <i class="fa-solid fa-server text-gray-400 mr-2"></i>
                                    {{ $hotspot->mikrotikServer->name }}
                                </span>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>

                        <td class="px-6 py-4 text-gray-700">{{ $hotspot->interface ?? '-' }}</td>
                        <td class="px-6 py-4 text-gray-700">{{ $hotspot->address ?? '-' }}</td>

                        <td class="px-6 py-4">
                            @if ($hotspot->is_active)
                                <span class="bg-green-100 text-green-700 text-xs font-medium px-3 py-1 rounded-full">Actif</span>
                            @else
                                <span class="bg-gray-100 text-gray-500 text-xs font-medium px-3 py-1 rounded-full">Inactif</span>
                            @endif
                        </td>
                       <td class="px-6 py-4 text-right">
    <div class="flex items-center justify-end space-x-3">
        <a href="{{ route('hotspots.forfaits', $hotspot) }}"
            class="text-purple-600 hover:text-purple-800" title="Gérer les forfaits">
            <i class="fa-solid fa-box"></i>
        </a>

        <a href="{{ route('hotspots.edit', $hotspot) }}"
            class="text-blue-600 hover:text-blue-800" title="Modifier">
            <i class="fa-solid fa-pen"></i>
        </a>

        <form action="{{ route('hotspots.destroy', $hotspot) }}" method="POST"
            onsubmit="return confirm('Supprimer ce hotspot définitivement ?');">
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
                        <td colspan="6" class="px-6 py-10 text-center text-gray-400">
                            Aucun hotspot pour le moment.
                        </td>
                    </tr>
                @endforelse

            </tbody>

        </table>

    </div>

    <div class="mt-6">
        {{ $hotspots->links() }}
    </div>

</div>

@endsection