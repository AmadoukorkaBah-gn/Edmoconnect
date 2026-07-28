@extends('layouts.client')

@section('title', 'Utiliser un ticket')

@section('content')

<div class="max-w-xl mx-auto">

    <h1 class="text-2xl font-semibold text-white mb-6">
        Utiliser un ticket
    </h1>

    @if(session('success'))
        <div class="mb-6 rounded-xl border border-green-500 bg-green-500/10 p-4 text-green-300">
            {{ session('success') }}
        </div>
    @endif

    @if(session('ticket'))

        @php
            $ticket = session('ticket');
        @endphp

        <div class="rounded-2xl border border-green-500/30 bg-green-500/10 p-6 mb-6">

            <div class="flex items-center justify-between mb-5">

                <span
                    class="inline-flex items-center gap-2 text-xs font-medium bg-green-500/20 border border-green-500/30 rounded-full px-3 py-1 text-green-300">

                    <i class="fa-solid fa-circle text-[6px]"></i>

                    Ticket activé

                </span>

            </div>

            <div class="space-y-4">

                <div>

                    <p class="text-sm text-gray-400">
                        Numéro de téléphone
                    </p>

                    <p class="text-white font-medium">
                        {{ $ticket['telephone'] ?? '-' }}
                    </p>

                </div>

                <div>

                    <p class="text-sm text-gray-400">
                        Identifiant WiFi
                    </p>

                    <p class="text-white font-mono">
                        {{ $ticket['username'] }}
                    </p>

                </div>

                <div>

                    <p class="text-sm text-gray-400">
                        Mot de passe
                    </p>

                    <p class="text-white font-mono">
                        {{ $ticket['password'] }}
                    </p>

                </div>

                <div>

                    <p class="text-sm text-gray-400">
                        Forfait
                    </p>

                    <p class="text-white">
                        {{ $ticket['forfait'] }}
                    </p>

                </div>

                <div>

                    <p class="text-sm text-gray-400">
                        Durée
                    </p>

                    <p class="text-white">
                        {{ $ticket['duree'] }}
                    </p>

                </div>

            </div>

            <div class="mt-6 rounded-xl bg-blue-500/10 border border-blue-500/30 p-4">

                <p class="text-sm text-blue-300">

                    Vos identifiants sont maintenant enregistrés.

                    Vous pourrez les retrouver plus tard avec votre numéro de téléphone.

                </p>

            </div>

        </div>

    @endif

    @if($errors->any())

        <div class="mb-6 rounded-xl border border-red-500 bg-red-500/10 p-4 text-red-300">

            {{ $errors->first() }}

        </div>

    @endif

    <div class="rounded-2xl bg-gray-900 border border-gray-800 p-6">

        <form method="POST" action="{{ route('client.ticket.connect', $hotspot) }}">

            @csrf

            <div class="space-y-5">

                <div>

                    <label class="block text-sm text-gray-400 mb-2">
                        Numéro de téléphone
                    </label>

                    <input
                        type="tel"
                        name="telephone"
                        value="{{ old('telephone') }}"
                        placeholder="Ex : 620 00 00 00"
                        required
                        class="w-full rounded-xl bg-gray-800 border border-gray-700 px-4 py-3 text-white focus:border-green-500 focus:ring-2 focus:ring-green-500 outline-none">

                    <p class="text-xs text-gray-500 mt-2">

                        Ce numéro permettra de retrouver votre ticket et de recevoir les informations de connexion.

                    </p>

                </div>

                <div>

                    <label class="block text-sm text-gray-400 mb-2">
                        Code du ticket
                    </label>

                    <input
                        type="text"
                        name="ticket"
                        value="{{ old('ticket') }}"
                        placeholder="Ex : EDMO-4H8K-92PL"
                        required
                        autocomplete="off"
                        class="w-full rounded-xl bg-gray-800 border border-gray-700 px-4 py-3 text-white uppercase focus:border-green-500 focus:ring-2 focus:ring-green-500 outline-none">

                </div>

                <button
                    type="submit"
                    class="w-full rounded-xl bg-green-600 hover:bg-green-700 py-4 font-semibold text-white transition">

                    Se connecter

                </button>

            </div>

            {{-- Paramètres MikroTik --}}

            <input type="hidden" name="link_login" value="{{ $linkLogin }}">
            <input type="hidden" name="link_orig" value="{{ $linkOrig }}">
            <input type="hidden" name="mac" value="{{ $mac }}">
            <input type="hidden" name="chap_id" value="{{ $chapId }}">
            <input type="hidden" name="chap_challenge" value="{{ $chapChallenge }}">

        </form>

    </div>

</div>

<div class="h-16"></div>

@include('client.partials.bottom-nav')

@endsection