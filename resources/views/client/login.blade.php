@extends('client.layout')

@section('content')

<div class="bg-white text-gray-900 rounded-2xl shadow-xl p-6">

    <!-- TITLE -->
    <div class="text-center mb-6">
        <div class="text-3xl">🔐</div>
        <h1 class="text-xl font-bold mt-2">Connexion</h1>
        <p class="text-sm text-gray-500">
            Connectez-vous pour accéder au WiFi
        </p>
    </div>

    <!-- FORM -->
    <form method="POST" action="#">

        @csrf

        <!-- TELEPHONE -->
        <div class="mb-4">
            <label class="text-sm text-gray-600">Numéro de téléphone</label>
            <input type="text"
                   name="telephone"
                   placeholder="Ex: 622xxxxxx"
                   class="w-full mt-1 p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <!-- PASSWORD -->
        <div class="mb-4">
            <label class="text-sm text-gray-600">Mot de passe</label>
            <input type="password"
                   name="password"
                   placeholder="********"
                   class="w-full mt-1 p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <!-- BUTTON -->
        <button type="submit"
                class="w-full bg-blue-600 text-white p-3 rounded-lg font-bold hover:bg-blue-700">

            Se connecter

        </button>

    </form>

    <!-- LINK REGISTER -->
    <p class="text-xs text-center text-gray-500 mt-4">
        Pas encore de compte ?
        <a href="#" class="text-green-600 font-bold">S’inscrire</a>
    </p>

</div>

@endsection