@extends('client.layout')

@section('content')

<!-- CARD INSCRIPTION -->
<div class="bg-white text-gray-900 rounded-2xl shadow-xl p-6">

    <!-- TITLE -->
    <div class="text-center mb-6">
        <div class="text-3xl">📝</div>
        <h1 class="text-xl font-bold mt-2">Créer un compte</h1>
        <p class="text-sm text-gray-500">
            Inscrivez-vous pour accéder au WiFi
        </p>
    </div>

    <!-- FORM -->
    <form method="POST" action="#">

        @csrf

        <!-- NOM -->
        <div class="mb-4">
            <label class="text-sm text-gray-600">Nom complet</label>
            <input type="text"
                   name="name"
                   placeholder="Ex: Amadou Bah"
                   class="w-full mt-1 p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <!-- TELEPHONE -->
        <div class="mb-4">
            <label class="text-sm text-gray-600">Numéro de téléphone</label>
            <input type="text"
                   name="telephone"
                   placeholder="Ex: 622xxxxxx"
                   class="w-full mt-1 p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <!-- MOT DE PASSE -->
        <div class="mb-4">
            <label class="text-sm text-gray-600">Mot de passe</label>
            <input type="password"
                   name="password"
                   placeholder="********"
                   class="w-full mt-1 p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <!-- BUTTON -->
        <button type="submit"
                class="w-full bg-green-600 text-white p-3 rounded-lg font-bold hover:bg-green-700">

            S’inscrire

        </button>

    </form>

    <!-- LINK LOGIN -->
    <p class="text-xs text-center text-gray-500 mt-4">
        Déjà un compte ?
        <a href="#" class="text-blue-600 font-bold">Se connecter</a>
    </p>

</div>

@endsection