@extends('layouts.app')

@section('content')

<div class="max-w-3xl mx-auto space-y-6">

    <div>
        <h1 class="text-2xl font-bold text-gray-800">Parametres</h1>
        <p class="text-gray-500 text-sm">Configuration generale de la plateforme</p>
    </div>

    @if (session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <!-- Informations generales -->
    <form action="{{ route('parametres.update') }}" method="POST" class="bg-white rounded-xl shadow-sm p-8 space-y-6">
        @csrf
        @method('PUT')

        <h2 class="font-semibold text-gray-800 border-b border-gray-100 pb-3">Informations de l'entreprise</h2>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nom de l'entreprise</label>
            <input type="text" name="nom_entreprise" value="{{ old('nom_entreprise', $parametre->nom_entreprise) }}"
                class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            @error('nom_entreprise')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="grid grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Telephone support</label>
                <input type="text" name="telephone_support" value="{{ old('telephone_support', $parametre->telephone_support) }}"
                    placeholder="ex: 620 00 00 00"
                    class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                @error('telephone_support')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email support</label>
                <input type="email" name="email_support" value="{{ old('email_support', $parametre->email_support) }}"
                    class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                @error('email_support')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Adresse</label>
            <input type="text" name="adresse" value="{{ old('adresse', $parametre->adresse) }}" placeholder="ex: Conakry, Guinee"
                class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            @error('adresse')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        <h2 class="font-semibold text-gray-800 border-b border-gray-100 pb-3 pt-4">Notifications clients</h2>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Delai du SMS de rappel avant expiration (minutes)</label>
            <input type="number" name="rappel_expiration_minutes" value="{{ old('rappel_expiration_minutes', $parametre->rappel_expiration_minutes) }}"
                min="5" max="180"
                class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            @error('rappel_expiration_minutes')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            <p class="text-xs text-gray-400 mt-1">Le client recevra un SMS ce nombre de minutes avant l'expiration de son forfait.</p>
        </div>

        <div class="flex justify-end pt-4 border-t border-gray-100">
            <button type="submit"
                class="px-5 py-2.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-medium transition">
                Enregistrer
            </button>
        </div>

    </form>

    <!-- Etat des integrations -->
    <div class="bg-white rounded-xl shadow-sm p-8">
        <h2 class="font-semibold text-gray-800 border-b border-gray-100 pb-3 mb-6">Etat des integrations</h2>

        <div class="space-y-4">

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <i class="fa-solid fa-money-bill-wave text-gray-400 w-8"></i>
                    <span class="text-gray-700">Djomy (paiements)</span>
                </div>
                @if ($djomyConfigure)
                    <span class="text-xs font-medium px-3 py-1 rounded-full bg-green-100 text-green-700">Configure</span>
                @else
                    <span class="text-xs font-medium px-3 py-1 rounded-full bg-red-100 text-red-700">Non configure</span>
                @endif
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <i class="fa-solid fa-sms text-gray-400 w-8"></i>
                    <span class="text-gray-700">Nimba SMS</span>
                </div>
                @if ($nimbaConfigure)
                    <span class="text-xs font-medium px-3 py-1 rounded-full bg-green-100 text-green-700">Configure</span>
                @else
                    <span class="text-xs font-medium px-3 py-1 rounded-full bg-red-100 text-red-700">Non configure</span>
                @endif
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <i class="fa-solid fa-server text-gray-400 w-8"></i>
                    <span class="text-gray-700">Serveurs MikroTik actifs</span>
                </div>
                <span class="text-xs font-medium px-3 py-1 rounded-full bg-blue-100 text-blue-700">
                    {{ $nombreServeursMikrotik }}
                </span>
            </div>

        </div>

        <p class="text-xs text-gray-400 mt-6">
            Les cles API (Djomy, Nimba SMS) se configurent dans le fichier .env du serveur, pas ici, pour des raisons de securite.
        </p>
    </div>

</div>

@endsection