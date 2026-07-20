@extends('layouts.client')

@section('title', 'Choisis ton hotspot — WiFi Zone')

@section('content')

<a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-[var(--muted)] hover:text-white text-sm mb-6 transition">
    <i class="fa-solid fa-arrow-left"></i>
    Retour
</a>

<h1 class="font-display text-2xl font-semibold text-white mb-2">
    Où es-tu ?
</h1>
<p class="text-[var(--muted)] text-sm mb-8">
    Choisis le hotspot auquel tu es connecté.
</p>

@if ($hotspots->isEmpty())

    <div class="text-center py-16 text-[var(--muted)]">
        <i class="fa-solid fa-wifi text-3xl mb-3 opacity-30"></i>
        <p class="text-sm">Aucun hotspot actif pour le moment.</p>
    </div>

@else

    <div class="space-y-3">
        @foreach ($hotspots as $hotspot)
            <a href="{{ route('client.accueil', $hotspot) }}"
                class="flex items-center justify-between recharge-card p-4 hover:translate-x-1 transition">

                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-[var(--signal)]/10 flex items-center justify-center">
                        <i class="fa-solid fa-wifi text-[var(--signal)]"></i>
                    </div>
                    <div>
                        <div class="text-white font-medium text-sm">{{ $hotspot->name }}</div>
                        @if ($hotspot->description)
                            <div class="text-[var(--muted)] text-xs">{{ $hotspot->description }}</div>
                        @endif
                    </div>
                </div>

                <i class="fa-solid fa-chevron-right text-[var(--muted)] text-sm"></i>

            </a>
        @endforeach
    </div>

@endif

@endsection