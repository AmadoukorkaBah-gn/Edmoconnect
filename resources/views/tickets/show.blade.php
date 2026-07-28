@extends('layouts.app')

@section('title','Détail du ticket')

@section('content')

<div class="max-w-3xl mx-auto">

    {{-- Fil d'ariane --}}
    <div class="mb-6 flex items-center justify-between">

        <a href="{{ route('tickets.index') }}"
           class="text-blue-600 hover:text-blue-800 font-semibold text-sm">

            ← Retour à la liste des tickets

        </a>

        <a href="{{ route('tickets.preview', $ticket->batch) }}"
           class="text-slate-500 hover:text-slate-700 font-semibold text-sm">

            Voir le lot complet →

        </a>

    </div>

    {{-- Message --}}
    @if(session('success'))

        <div class="mb-6 rounded-lg bg-green-100 border border-green-200 px-5 py-4 text-green-700">

            {{ session('success') }}

        </div>

    @endif

    @if(session('error'))

        <div class="mb-6 rounded-lg bg-red-100 border border-red-200 px-5 py-4 text-red-700">

            {{ session('error') }}

        </div>

    @endif

    {{-- Le ticket, identique au rendu d'impression (même forme, même taille) --}}
    <div class="flex justify-center mb-8">

        <div class="w-full" style="max-width:280px;">

            <div class="bg-white rounded-[10px] border border-blue-100 shadow overflow-hidden">

                {{-- Entête dégradé --}}
                <div class="bg-gradient-to-br from-blue-700 to-blue-500 text-white text-center py-2 px-2.5">

                    <div class="text-xs font-bold tracking-wide">
                        EDMO CONNECT
                    </div>

                    <div class="text-[8px] opacity-90">
                        Accès Internet WiFi
                    </div>

                </div>

                {{-- Corps --}}
                <div class="text-center py-3 px-2.5">

                    <div class="text-[8px] uppercase tracking-widest text-slate-400 mb-1">
                        Code d'accès
                    </div>

                    <div class="text-[15px] font-bold text-blue-600 font-mono break-all mb-2.5">
                        {{ $ticket->code }}
                    </div>

                    <hr class="border-dashed border-slate-300 my-2">

                    <div class="flex justify-between text-[9px] py-0.5">
                        <span class="text-slate-400">Forfait</span>
                        <span class="font-bold text-slate-800">{{ $ticket->forfait->nom }}</span>
                    </div>

                    @if(isset($ticket->forfait->prix))

                        <div class="flex justify-between text-[9px] py-0.5">
                            <span class="text-slate-400">Prix</span>
                            <span class="font-bold text-slate-800">
                                {{ number_format($ticket->forfait->prix,0,',',' ') }} GNF
                            </span>
                        </div>

                    @endif

                </div>

                {{-- Pied --}}
                <div class="bg-slate-50 border-t border-slate-200 text-center py-1 px-2">
                    <span class="text-[7px] text-slate-400">
                        EDMO CONNECT — {{ now()->format('d/m/Y H:i') }}
                    </span>
                </div>

            </div>

            {{-- Statut --}}
            <div class="text-center mt-3">

                @if($ticket->status == 'activated')

                    <span class="bg-green-100 text-green-700 px-4 py-1.5 rounded-full text-sm font-semibold">
                        ✅ Activé
                    </span>

                @elseif($ticket->status == 'cancelled')

                    <span class="bg-red-100 text-red-700 px-4 py-1.5 rounded-full text-sm font-semibold">
                        ❌ Annulé
                    </span>

                @else

                    <span class="bg-blue-100 text-blue-700 px-4 py-1.5 rounded-full text-sm font-semibold">
                        ⏳ Disponible
                    </span>

                @endif

            </div>

        </div>

    </div>

    {{-- Informations complémentaires (admin) --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">

        <h3 class="text-xs uppercase tracking-widest text-slate-400 mb-4">
            Détails administrateur
        </h3>

        <div class="grid sm:grid-cols-2 gap-x-8 gap-y-3 text-sm">

            <div class="flex justify-between">
                <span class="text-slate-500">Hotspot</span>
                <strong class="text-slate-800">{{ $ticket->hotspot->name }}</strong>
            </div>

            <div class="flex justify-between">
                <span class="text-slate-500">Durée</span>
                <strong class="text-slate-800">{{ $ticket->forfait->duree }}</strong>
            </div>

            <div class="flex justify-between">
                <span class="text-slate-500">Utilisateur</span>
                <strong class="text-slate-800 font-mono">{{ $ticket->username }}</strong>
            </div>

            <div class="flex justify-between">
                <span class="text-slate-500">Mot de passe</span>
                <strong class="text-slate-800 font-mono">{{ $ticket->password }}</strong>
            </div>

            <div class="flex justify-between">
                <span class="text-slate-500">Lot (batch)</span>
                <strong class="text-slate-800 font-mono text-xs">{{ $ticket->batch }}</strong>
            </div>

            <div class="flex justify-between">
                <span class="text-slate-500">Généré le</span>
                <strong class="text-slate-800">{{ $ticket->created_at->format('d/m/Y H:i') }}</strong>
            </div>

            @if($ticket->activated_at)

                <div class="flex justify-between">
                    <span class="text-slate-500">Activé le</span>
                    <strong class="text-slate-800">{{ $ticket->activated_at->format('d/m/Y H:i') }}</strong>
                </div>

            @endif

        </div>

        @if($ticket->status !== 'activated')

            <div class="mt-6 pt-4 border-t text-right">

                <form action="{{ route('tickets.destroy', $ticket) }}" method="POST"
                      onsubmit="return confirm('Supprimer ce ticket ?');" class="inline">
                    @csrf
                    @method('DELETE')

                    <button type="submit"
                            class="text-red-600 hover:text-red-800 font-semibold text-sm">
                        🗑️ Supprimer ce ticket
                    </button>
                </form>

            </div>

        @endif

    </div>

</div>

@endsection