@extends('layouts.app')

@section('content')

<div class="max-w-3xl mx-auto">

    <div class="flex items-center mb-6">
        <a href="{{ route('hotspots.index') }}" class="text-gray-500 hover:text-gray-700 mr-4">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Nouveau hotspot</h1>
    </div>

    @if ($servers->isEmpty())
        <div class="bg-yellow-50 border border-yellow-200 text-yellow-700 px-4 py-3 rounded-lg mb-6">
            Aucun serveur MikroTik actif. <a href="{{ route('mikrotik-servers.create') }}" class="underline font-medium">Crée d'abord un serveur</a>.
        </div>
    @endif

    <form action="{{ route('hotspots.store') }}" method="POST" class="bg-white rounded-xl shadow-sm p-8 space-y-6">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Serveur MikroTik</label>
            <select name="mikrotik_server_id"
                class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">-- Sélectionner un serveur --</option>
                @foreach ($servers as $server)
                    <option value="{{ $server->id }}" {{ old('mikrotik_server_id') == $server->id ? 'selected' : '' }}>
                        {{ $server->name }} ({{ $server->host }})
                    </option>
                @endforeach
            </select>
            @error('mikrotik_server_id')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nom du hotspot</label>
            <input type="text" name="name" value="{{ old('name') }}" placeholder="ex: WiFi Zone Sonfonia - Entrée"
                class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            @error('name')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="grid grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Interface</label>
                <input type="text" name="interface" value="{{ old('interface') }}" placeholder="ex: wlan1"
                    class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                @error('interface')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Profil MikroTik</label>
                <input type="text" name="profile" value="{{ old('profile') }}" placeholder="ex: hotspot-profile-1"
                    class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                @error('profile')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Adresse / IP réseau</label>
            <input type="text" name="address" value="{{ old('address') }}" placeholder="ex: 192.168.10.1/24"
                class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            @error('address')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
            <textarea name="description" rows="3"
                class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('description') }}</textarea>
            @error('description')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="flex items-center">
            <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
            <label for="is_active" class="ml-2 text-sm text-gray-700">Hotspot actif</label>
        </div>

        <div class="flex justify-end space-x-3 pt-4 border-t border-gray-100">
            <a href="{{ route('hotspots.index') }}"
                class="px-5 py-2.5 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50 transition">
                Annuler
            </a>
            <button type="submit"
                class="px-5 py-2.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-medium transition">
                Créer le hotspot
            </button>
        </div>

    </form>

</div>

@endsection