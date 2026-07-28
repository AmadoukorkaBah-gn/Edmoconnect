@extends('layouts.app')

@section('title','Aperçu des tickets')

@section('content')

<div class="max-w-7xl mx-auto">

    {{-- Header --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 mb-8">

        <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center gap-5">

            <div>

                <h1 class="text-3xl font-bold text-slate-800">

                    Aperçu des tickets

                </h1>

                <p class="mt-2 text-slate-500">

                    Lot :

                    <span class="font-semibold text-blue-600">

                        {{ $batch }}

                    </span>

                </p>

                <p class="text-slate-500">

                    {{ $tickets->count() }} ticket(s) généré(s)

                </p>

            </div>

            <div class="flex flex-wrap gap-3">

                <a href="{{ route('tickets.index') }}"
                   class="px-5 py-3 rounded-lg bg-slate-200 hover:bg-slate-300">

                    ← Retour

                </a>

                <a href="{{ route('tickets.preview.print', $batch) }}"
                   target="_blank"
                   class="px-5 py-3 rounded-lg bg-slate-800 text-white hover:bg-black">
                    🖨️ Imprimer
                </a>

                <a href="{{ route('tickets.preview.pdf', $batch) }}"
                   class="px-5 py-3 rounded-lg bg-red-600 text-white hover:bg-red-700">
                    📄 Télécharger PDF
                </a>

            </div>

        </div>

    </div>

    {{-- Message --}}
    @if(session('success'))

        <div class="mb-6 rounded-lg bg-green-100 border border-green-200 px-5 py-4 text-green-700">

            {{ session('success') }}

        </div>

    @endif

    {{-- Statistiques --}}
    <div class="grid md:grid-cols-4 gap-5 mb-8">

        <div class="bg-white rounded-xl shadow border p-5">

            <p class="text-slate-500 text-sm">

                Tickets

            </p>

            <h2 class="text-3xl font-bold text-blue-600 mt-2">

                {{ $tickets->count() }}

            </h2>

        </div>

        <div class="bg-white rounded-xl shadow border p-5">

            <p class="text-slate-500 text-sm">

                Disponibles

            </p>

            <h2 class="text-3xl font-bold text-green-600 mt-2">

                {{ $tickets->where('status','available')->count() }}

            </h2>

        </div>

        <div class="bg-white rounded-xl shadow border p-5">

            <p class="text-slate-500 text-sm">

                Activés

            </p>

            <h2 class="text-3xl font-bold text-blue-600 mt-2">

                {{ $tickets->where('status','activated')->count() }}

            </h2>

        </div>

        <div class="bg-white rounded-xl shadow border p-5">

            <p class="text-slate-500 text-sm">

                Annulés

            </p>

            <h2 class="text-3xl font-bold text-red-600 mt-2">

                {{ $tickets->where('status','cancelled')->count() }}

            </h2>

        </div>

    </div>

    {{-- Cartes --}}
    <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-5">

        @foreach($tickets as $ticket)

            <div>

                @include('tickets.components.card',[
                    'ticket'=>$ticket
                ])

                <div class="mt-3 flex justify-between px-1">

                    <a href="{{ route('tickets.show',$ticket) }}"
                       class="text-blue-600 hover:text-blue-800 font-semibold text-sm">

                        👁️ Voir

                    </a>

                    <span class="text-slate-400 text-xs">

                        {{ $ticket->created_at->format('d/m/Y') }}

                    </span>

                </div>

            </div>

        @endforeach

    </div>

</div>

@endsection