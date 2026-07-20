@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Utilisateurs</h1>
            <p class="text-gray-500 text-sm">Gestion des comptes</p>
        </div>

        <a href="{{ route('utilisateurs.create') }}"
            class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg font-medium transition inline-flex items-center">
            <i class="fa-solid fa-plus mr-2"></i>
            Nouvel utilisateur
        </a>
    </div>

    @if (session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">

        <table class="w-full text-left">

            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">Utilisateur</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">Téléphone</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">Rôle</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">Statut</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600 text-right">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-100">

                @forelse ($users as $user)
                    <tr class="hover:bg-gray-50 transition">

                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=2563eb&color=fff"
                                    class="w-9 h-9 rounded-full mr-3">
                                <div>
                                    <div class="font-medium text-gray-800">{{ $user->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $user->email ?? '-' }}</div>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-4 text-gray-700">{{ $user->telephone }}</td>

                        <td class="px-6 py-4">
                            @if ($user->role)
                                <span class="bg-blue-100 text-blue-700 text-xs font-medium px-3 py-1 rounded-full">
                                    {{ $user->role->display_name }}
                                </span>
                            @else
                                <span class="text-gray-400 text-sm">Aucun</span>
                            @endif
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
                                    'blocked' => 'Bloqué',
                                ];
                            @endphp
                            <span class="{{ $statusColors[$user->status] ?? 'bg-gray-100 text-gray-500' }} text-xs font-medium px-3 py-1 rounded-full">
                                {{ $statusLabels[$user->status] ?? $user->status }}
                            </span>
                        </td>

                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end space-x-3">
                                <a href="{{ route('utilisateurs.edit', $user) }}"
                                    class="text-blue-600 hover:text-blue-800" title="Modifier">
                                    <i class="fa-solid fa-pen"></i>
                                </a>

                                <form action="{{ route('utilisateurs.destroy', $user) }}" method="POST"
                                    onsubmit="return confirm('Supprimer cet utilisateur définitivement ?');">
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
                        <td colspan="5" class="px-6 py-10 text-center text-gray-400">
                            Aucun utilisateur pour le moment.
                        </td>
                    </tr>
                @endforelse

            </tbody>

        </table>

    </div>

    <div class="mt-6">
        {{ $users->links() }}
    </div>

</div>

@endsection