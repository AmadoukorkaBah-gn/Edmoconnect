@extends('layouts.app')

@section('content')

<div class="max-w-3xl mx-auto">

    <div class="flex items-center mb-6">
        <a href="{{ route('paiements.index') }}" class="text-gray-500 hover:text-gray-700 mr-4">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Enregistrer un paiement cash</h1>
    </div>

    <div class="bg-yellow-50 border border-yellow-200 text-yellow-700 px-4 py-3 rounded-lg mb-6 text-sm">
        À utiliser uniquement pour les paiements reçus en espèces sur place, hors Djomy.
    </div>

    <form action="{{ route('paiements.store') }}" method="POST" class="bg-white rounded-xl shadow-sm p-8 space-y-6">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Client</label>
            <select name="user_id"
                class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">-- Sélectionner --</option>
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
                <select name="forfait_id" id="forfait_id"
                    class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">-- Sélectionner --</option>
                    @foreach ($forfaits as $forfait)
                        <option value="{{ $forfait->id }}" data-prix="{{ $forfait->prix }}"
                            {{ old('forfait_id') == $forfait->id ? 'selected' : '' }}>
                            {{ $forfait->nom }} ({{ number_format($forfait->prix, 0, ',', ' ') }} GNF)
                        </option>
                    @endforeach
                </select>
                @error('forfait_id')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="grid grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Montant (GNF)</label>
                <input type="number" step="0.01" name="montant" id="montant" value="{{ old('montant') }}"
                    class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                @error('montant')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                <select name="statut"
                    class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="success" {{ old('statut', 'success') == 'success' ? 'selected' : '' }}>Réussi</option>
                    <option value="pending" {{ old('statut') == 'pending' ? 'selected' : '' }}>En attente</option>
                    <option value="failed" {{ old('statut') == 'failed' ? 'selected' : '' }}>Échoué</option>
                </select>
                @error('statut')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Méthode</label>
            <input type="text" name="methode" value="{{ old('methode', 'Cash') }}"
                class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            @error('methode')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="flex justify-end space-x-3 pt-4 border-t border-gray-100">
            <a href="{{ route('paiements.index') }}"
                class="px-5 py-2.5 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50 transition">
                Annuler
            </a>
            <button type="submit"
                class="px-5 py-2.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-medium transition">
                Enregistrer
            </button>
        </div>

    </form>

</div>

@endsection

@push('scripts')
<script>
    document.getElementById('forfait_id').addEventListener('change', function () {
        const selected = this.options[this.selectedIndex];
        const prix = selected.getAttribute('data-prix');
        if (prix) {
            document.getElementById('montant').value = prix;
        }
    });
</script>
@endpush