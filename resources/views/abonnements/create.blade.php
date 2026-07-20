@extends('layouts.app')

@section('content')

<div class="max-w-3xl mx-auto">

    <div class="flex items-center mb-6">
        <a href="{{ route('abonnements.index') }}" class="text-gray-500 hover:text-gray-700 mr-4">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Nouvel abonnement</h1>
    </div>

    <form action="{{ route('abonnements.store') }}" method="POST" class="bg-white rounded-xl shadow-sm p-8 space-y-6">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Client</label>
            <select name="user_id"
                class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">-- Sélectionner un client --</option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                        {{ $user->name }} ({{ $user->telephone }})
                    </option>
                @endforeach
            </select>
            @error('user_id')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="grid grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Hotspot</label>
                <select name="hotspot_id"
                    class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">-- Sélectionner --</option>
                    @foreach ($hotspots as $hotspot)
                        <option value="{{ $hotspot->id }}" {{ old('hotspot_id') == $hotspot->id ? 'selected' : '' }}>
                            {{ $hotspot->name }}
                        </option>
                    @endforeach
                </select>
                @error('hotspot_id')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Forfait</label>
                <select name="forfait_id"
                    class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">-- Sélectionner --</option>
                    @foreach ($forfaits as $forfait)
                        <option value="{{ $forfait->id }}" {{ old('forfait_id') == $forfait->id ? 'selected' : '' }}>
                            {{ $forfait->nom }} ({{ $forfait->duree }}h - {{ number_format($forfait->prix, 0, ',', ' ') }} GNF)
                        </option>
                    @endforeach
                </select>
                @error('forfait_id')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Date de début</label>
            <input type="datetime-local" name="date_debut" value="{{ old('date_debut', now()->format('Y-m-d\TH:i')) }}"
                class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            @error('date_debut')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            <p class="text-xs text-gray-400 mt-1">La date de fin sera calculée automatiquement selon la durée du forfait choisi.</p>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
            <select name="statut"
                class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="pending" {{ old('statut', 'pending') == 'pending' ? 'selected' : '' }}>En attente</option>
                <option value="active" {{ old('statut') == 'active' ? 'selected' : '' }}>Actif</option>
                <option value="expired" {{ old('statut') == 'expired' ? 'selected' : '' }}>Expiré</option>
                <option value="suspended" {{ old('statut') == 'suspended' ? 'selected' : '' }}>Suspendu</option>
                <option value="cancelled" {{ old('statut') == 'cancelled' ? 'selected' : '' }}>Annulé</option>
            </select>
            @error('statut')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Référence paiement (facultatif)</label>
            <input type="text" name="reference_paiement" value="{{ old('reference_paiement') }}"
                class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            @error('reference_paiement')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
            <textarea name="notes" rows="3"
                class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('notes') }}</textarea>
            @error('notes')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="flex justify-end space-x-3 pt-4 border-t border-gray-100">
            <a href="{{ route('abonnements.index') }}"
                class="px-5 py-2.5 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50 transition">
                Annuler
            </a>
            <button type="submit"
                class="px-5 py-2.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-medium transition">
                Créer l'abonnement
            </button>
        </div>

    </form>

</div>

@endsection