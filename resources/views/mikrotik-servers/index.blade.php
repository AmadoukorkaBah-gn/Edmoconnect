@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Serveurs MikroTik</h1>
            <p class="text-gray-500 text-sm">Gestion des routeurs connectés</p>
        </div>

        <a href="{{ route('mikrotik-servers.create') }}"
            class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg font-medium transition inline-flex items-center">
            <i class="fa-solid fa-plus mr-2"></i>
            Nouveau serveur
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
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">Hôte</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">Port</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">SSL</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">Emplacement</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">Statut</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600 text-right">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-100">

                @forelse ($servers as $server)
                    <tr class="hover:bg-gray-50 transition">

                        <td class="px-6 py-4 font-medium text-gray-800">{{ $server->name }}</td>
                        <td class="px-6 py-4 text-gray-700">{{ $server->host }}</td>
                        <td class="px-6 py-4 text-gray-700">{{ $server->port }}</td>

                        <td class="px-6 py-4">
                            @if ($server->ssl)
                                <span class="bg-blue-100 text-blue-700 text-xs font-medium px-3 py-1 rounded-full">Oui</span>
                            @else
                                <span class="bg-gray-100 text-gray-500 text-xs font-medium px-3 py-1 rounded-full">Non</span>
                            @endif
                        </td>

                        <td class="px-6 py-4 text-gray-700">{{ $server->location ?? '-' }}</td>

                        <td class="px-6 py-4">
                            @if ($server->is_active)
                                <span class="bg-green-100 text-green-700 text-xs font-medium px-3 py-1 rounded-full">Actif</span>
                            @else
                                <span class="bg-gray-100 text-gray-500 text-xs font-medium px-3 py-1 rounded-full">Inactif</span>
                            @endif
                        </td>

                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end space-x-3">
                                <a href="{{ route('mikrotik-servers.edit', $server) }}"
                                    class="text-blue-600 hover:text-blue-800" title="Modifier">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                     <form action="{{ route('mikrotik-servers.test', $server) }}" method="POST" class="inline">
    @csrf
    <button type="submit" class="text-green-600 hover:text-green-800" title="Tester la connexion">
        <i class="fa-solid fa-plug"></i>
    </button>
</form>
@if (session('error'))
    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
        {{ session('error') }}
    </div>
@endif
                                <form action="{{ route('mikrotik-servers.destroy', $server) }}" method="POST"
                                    onsubmit="return confirm('Supprimer ce serveur définitivement ?');">
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
                            Aucun serveur MikroTik pour le moment.
                        </td>
                    </tr>
                @endforelse

            </tbody>

        </table>

    </div>

    <div class="mt-6">
        {{ $servers->links() }}
    </div>

</div>

@endsection