@extends('layouts.client')

@section('title', 'Paiement — WiFi Zone')

@section('content')

<a href="{{ route('client.accueil', $hotspot) }}" class="inline-flex items-center gap-2 text-[var(--muted)] hover:text-white text-sm mb-6 transition">
    <i class="fa-solid fa-arrow-left"></i>
    Retour aux forfaits
</a>

<div class="recharge-card p-6 mb-6">
    <div class="text-[var(--muted)] text-xs font-mono mb-2">FORFAIT SÉLECTIONNÉ</div>
    <div class="flex items-center justify-between">
        <div>
            <div class="font-display font-semibold text-white text-lg">{{ $forfait->nom }}</div>
            <div class="text-[var(--muted)] text-sm">{{ $forfait->duree_label }} de connexion</div>
        </div>
        <div class="text-right">
            <div class="font-mono text-2xl font-bold text-white">{{ number_format($forfait->prix, 0, ',', ' ') }}</div>
            <div class="font-mono text-xs text-[var(--signal)]">GNF</div>
        </div>
    </div>
</div>

<form action="{{ route('client.payer', [$hotspot, $forfait]) }}" method="POST">
    @csrf

    <label class="block text-sm font-medium text-white mb-2">Ton numéro de téléphone</label>
    <input type="tel" name="telephone" value="{{ old('telephone') }}" placeholder="ex: 620 00 00 00"
        class="w-full rounded-xl px-4 py-3.5 mb-1" required>
    @error('telephone')
        <p class="text-red-400 text-sm mb-3">{{ $message }}</p>
    @enderror

    <p class="text-[var(--muted)] text-xs mb-6">
        Tu recevras la demande de paiement sur ce numéro (Orange Money ou MTN MoMo).
    </p>

    <button type="submit"
        class="w-full bg-[var(--signal-deep)] hover:bg-blue-700 text-white font-medium py-4 rounded-xl transition flex items-center justify-center gap-2">
        Payer {{ number_format($forfait->prix, 0, ',', ' ') }} GNF
        <i class="fa-solid fa-arrow-right text-sm"></i>
    </button>

</form>

@endsection