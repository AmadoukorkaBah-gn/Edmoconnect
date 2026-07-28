@extends('layouts.client')

@section('content')

<div class="text-center">

    <h1 class="text-2xl text-white font-bold mb-4">
        Connexion réussie
    </h1>

    <p class="text-gray-400 mb-6">
        Activation de votre accès WiFi...
    </p>

    <form id="login"
          method="POST"
          action="{{ $linkLogin }}">

        <input type="hidden" name="username" value="{{ $username }}">
        <input type="hidden" name="password" value="{{ $password }}">
        <input type="hidden" name="dst" value="{{ $linkOrig }}">

    </form>

</div>

<script>
    document.getElementById('login').submit();
</script>

@endsection