@extends('layouts.client')

@section('title', 'Paiement annulé — WiFi Zone')

@section('content')

<div class="flex flex-col items-center text-center py-16">

    <div class="w-20 h-20 rounded-full bg-gray-500/10 border border-gray-500/30 flex items-center justify-center mb-6">
        <i class="fa-solid fa-ban text-gray-400 text-3xl"></i>
    </div>

    <h1 class="font-display text-2xl font-semibold text-white mb-2">Paiement annulé</h1>
    <p class="text-[var(--muted)]">Tu peux réessayer quand tu veux depuis le hotspot.</p>

</div>

@endsection