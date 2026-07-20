@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto">

    <div class="flex items-center justify-between mb-6">

        <div>
            <h1 class="text-2xl font-bold text-gray-800">Forfaits</h1>
            <p class="text-gray-500 text-sm">Gestion des forfaits internet</p>
        </div>

        <a href="{{ route('forfaits.create') }}"
            class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg font-medium transition inline-flex items-center">
            <i class="fa-solid fa-plus mr-2"></i>
            Nouveau forfait
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
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">Prix</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">Durée</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">Débit</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">Profil MikroTik</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">Statut</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600 text-right">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-100">

                @forelse ($forfaits as $forfait)
                    <tr class="hover:bg-gray-50 transition">

                        <td class="px-6 py-4 font-medium text-gray-800">
                            {{ $forfait->nom }}
                        </td>

                        <td class="px-6 py-4 text-gray-700">
                            {{ number_format($forfait->prix, 0, ',', ' ') }} GNF
                        </td>

                        <td class="px-6 py-4 text-gray-700">
                            {{ $forfait->duree }} h
                        </td>

                        <td class="px-6 py-4 text-gray-700">
                            @if ($forfait->download_speed || $forfait->upload_speed)
                                {{ $forfait->download_speed ?? '-' }} / {{ $forfait->upload_speed ?? '-' }} Mbps
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>

                        <td class="px-6 py-4 text-gray-700">
                            <code class="bg-gray-100 px-2 py-1 rounded text-xs">{{ $forfait->mikrotik_profile }}</code>
                        </td>

                        <td class="px-6 py-4">
                            @if ($forfait->is_active)
                                <span class="bg-green-100 text-green-700 text-xs font-medium px-3 py-1 rounded-full">
                                    Actif
                                </span>
                            @else
                                <span class="bg-gray-100 text-gray-500 text-xs font-medium px-3 py-1 rounded-full">
                                    Inactif
                                </span>
                            @endif
                        </td>

                        <td class="px-6 py-4 text-right">

                            <div class="flex items-center justify-end space-x-3">

                                <a href="{{ route('forfaits.edit', $forfait) }}"
                                    class="text-blue-600 hover:text-blue-800" title="Modifier">
                                    <i class="fa-solid fa-pen"></i>
                                </a>

                                <form action="{{ route('forfaits.destroy', $forfait) }}" method="POST"
                                    onsubmit="return confirm('Supprimer ce forfait définitivement ?');">
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
                            Aucun forfait pour le moment.
                        </td>
                    </tr>
                @endforelse

            </tbody>

        </table>

    </div>

    <div class="mt-6">
        {{ $forfaits->links() }}
    </div>

</div>

@endsection