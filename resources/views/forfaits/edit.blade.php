@extends('layouts.app')

@section('content')

<div class="max-w-3xl mx-auto">

    <div class="flex items-center mb-6">
        <a href="{{ route('forfaits.index') }}" class="text-gray-500 hover:text-gray-700 mr-4">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Modifier le forfait</h1>
    </div>

    <form action="{{ route('forfaits.update', $forfait) }}" method="POST" class="bg-white rounded-xl shadow-sm p-8 space-y-6">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nom du forfait</label>
            <input type="text" name="nom" value="{{ old('nom', $forfait->nom) }}"
                class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            @error('nom')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-2 gap-6">

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Prix (GNF)</label>
                <input type="number" step="0.01" name="prix" value="{{ old('prix', $forfait->prix) }}"
                    class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                @error('prix')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Durée (heures)</label>
                <input type="number" name="duree" value="{{ old('duree', $forfait->duree) }}"
                    class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                @error('duree')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

        </div>

        <div class="grid grid-cols-2 gap-6">

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Débit descendant (Mbps)</label>
                <input type="number" name="download_speed" value="{{ old('download_speed', $forfait->download_speed) }}"
                    class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                @error('download_speed')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Débit montant (Mbps)</label>
                <input type="number" name="upload_speed" value="{{ old('upload_speed', $forfait->upload_speed) }}"
                    class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                @error('upload_speed')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Profil MikroTik</label>
            <input type="text" name="mikrotik_profile" value="{{ old('mikrotik_profile', $forfait->mikrotik_profile) }}"
                class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            @error('mikrotik_profile')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
            <textarea name="description" rows="3"
                class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('description', $forfait->description) }}</textarea>
            @error('description')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center">
            <input type="checkbox" name="is_active" id="is_active" value="1"
                {{ old('is_active', $forfait->is_active) ? 'checked' : '' }}
                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
            <label for="is_active" class="ml-2 text-sm text-gray-700">Forfait actif</label>
        </div>

        <div class="flex justify-end space-x-3 pt-4 border-t border-gray-100">
            <a href="{{ route('forfaits.index') }}"
                class="px-5 py-2.5 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50 transition">
                Annuler
            </a>
            <button type="submit"
                class="px-5 py-2.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-medium transition">
                Enregistrer les modifications
            </button>
        </div>

    </form>

</div>

@endsection