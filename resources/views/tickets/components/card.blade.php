<div class="bg-white rounded-2xl border border-slate-200 shadow-md hover:shadow-lg transition-shadow overflow-hidden ticket-card">

    {{-- Bandeau supérieur --}}
    <div class="bg-gradient-to-r from-blue-700 via-blue-600 to-blue-500 text-white px-5 py-4">

        <div class="flex items-center justify-between">

            <div>

                <h2 class="text-base font-bold tracking-wide leading-tight">
                    EDMO CONNECT
                </h2>

                <p class="text-xs opacity-90">
                    Accès Internet WiFi
                </p>

            </div>

            <div class="text-right">

                <span class="text-[10px] uppercase opacity-80">
                    Ticket
                </span>

                <div class="font-bold text-sm">
                    #{{ $ticket->id }}
                </div>

            </div>

        </div>

    </div>

    {{-- Corps --}}
    <div class="p-5">

        {{-- Code --}}
        <div class="text-center mb-4">

            <div class="text-[10px] uppercase tracking-widest text-slate-400">
                Code d'accès
            </div>

            <div class="mt-1 text-xl font-bold font-mono text-blue-600 break-all">

                {{ $ticket->code }}

            </div>

        </div>

        <hr class="mb-4 border-dashed">

        {{-- Informations --}}
        <div class="space-y-2 text-sm">

            <div class="flex justify-between">

                <span class="text-slate-400">
                    Forfait
                </span>

                <strong class="text-slate-700">

                    {{ $ticket->forfait->nom }}

                </strong>

            </div>

            @if(isset($ticket->forfait->prix))

            <div class="flex justify-between">

                <span class="text-slate-400">
                    Prix
                </span>

                <strong class="text-slate-700">

                    {{ number_format($ticket->forfait->prix,0,',',' ') }} GNF

                </strong>

            </div>

            @endif

        </div>

    </div>

    {{-- Pied --}}
    <div class="border-t bg-slate-50 px-5 py-3 flex justify-between items-center">

        <small class="text-slate-400 text-[11px]">

            {{ $ticket->created_at->format('d/m/Y H:i') }}

        </small>

        <small class="font-semibold text-blue-600 text-[11px]">

            EDMO CONNECT

        </small>

    </div>

</div>