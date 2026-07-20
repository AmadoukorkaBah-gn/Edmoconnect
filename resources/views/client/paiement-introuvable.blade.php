@extends('layouts.client')

@section('title', 'Paiement introuvable — WiFi Zone')

@section('content')

<div class="flex flex-col items-center text-center py-16">
    <i class="fa-solid fa-triangle-exclamation text-3xl text-[var(--muted)] mb-4"></i>
    <h1 class="font-display text-xl font-semibold text-white mb-2">Paiement introuvable</h1>
    <p class="text-[var(--muted)]">Cette transaction n'existe pas ou a expiré.</p>
</div>

@endsection