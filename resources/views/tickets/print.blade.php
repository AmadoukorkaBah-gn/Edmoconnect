<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="UTF-8">

    <title>Impression des tickets</title>

    <style>

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
        }

        body{
            font-family:Arial,Helvetica,sans-serif;
            background:#f1f5f9;
            padding:15px;
        }

        /* Entête */
        .page-header{

            background:linear-gradient(135deg,#e0f2fe 0%,#bae6fd 45%,#7dd3fc 100%);

            border-radius:14px;

            padding:20px 15px 18px;

            margin-bottom:22px;

            text-align:center;

        }

        .title-row{

            display:flex;

            align-items:center;

            justify-content:center;

            gap:14px;

            margin-bottom:8px;

        }

        .title-line{

            flex:1;

            max-width:120px;

            height:3px;

            background:linear-gradient(90deg,rgba(29,78,216,0),#1d4ed8);

            border-radius:2px;

        }

        .title-line.right{

            background:linear-gradient(90deg,#1d4ed8,rgba(29,78,216,0));

        }

        h1{

            font-size:26px;

            color:#1e3a8a;

            letter-spacing:2px;

            white-space:nowrap;

        }

        .subtitle{
            color:#1e40af;
            font-size:13px;
            font-weight:600;
        }

        .actions{
            text-align:center;
            margin-bottom:25px;
        }

        .actions a,
        .actions button{
            display:inline-block;
            padding:10px 20px;
            border-radius:8px;
            text-decoration:none;
            font-size:14px;
            font-weight:bold;
            border:none;
            cursor:pointer;
            margin:0 6px;
        }

        .btn-pdf{
            background:#dc2626;
            color:white;
        }

        .btn-print{
            background:#2563eb;
            color:white;
        }

        .btn-back{
            background:#e2e8f0;
            color:#1e293b;
        }

        /* 3 cartes par ligne, 4 lignes = 12 par page A4 */
        .container{

            display:grid;

            grid-template-columns:repeat(3,1fr);

            gap:8px;

        }

        .ticket{

            background:white;

            border:1px solid #dbeafe;

            border-radius:10px;

            overflow:hidden;

            box-shadow:0 1px 3px rgba(0,0,0,0.08);

            page-break-inside:avoid;

            break-inside:avoid;

        }

        .header{

            background:linear-gradient(135deg,#1d4ed8,#3b82f6);

            color:white;

            padding:8px 10px;

            text-align:center;

        }

        .header .brand{

            font-size:12px;

            font-weight:bold;

            letter-spacing:0.5px;

        }

        .header .tagline{

            font-size:8px;

            opacity:0.9;

        }

        .body{

            padding:12px 10px;

            text-align:center;

        }

        .code-label{

            font-size:8px;

            text-transform:uppercase;

            letter-spacing:1px;

            color:#94a3b8;

            margin-bottom:4px;

        }

        .code{

            font-size:15px;

            font-weight:bold;

            color:#2563eb;

            word-break:break-word;

            margin-bottom:10px;

            font-family:'Courier New',monospace;

        }

        .divider{

            border-top:1px dashed #cbd5e1;

            margin:8px 0;

        }

        .info-row{

            display:flex;

            justify-content:space-between;

            font-size:9px;

            padding:2px 0;

        }

        .info-row .label{

            color:#94a3b8;

        }

        .info-row .value{

            font-weight:bold;

            color:#1e293b;

        }

        .footer{

            background:#f8fafc;

            border-top:1px solid #e2e8f0;

            padding:5px 8px;

            text-align:center;

            font-size:7px;

            color:#94a3b8;

        }

        .page-break{

            page-break-after:always;
            break-after:page;

        }

        @media print{

            body{

                padding:0;
                background:white;

            }

            .page-header{

                background:linear-gradient(135deg,#e0f2fe 0%,#bae6fd 45%,#7dd3fc 100%) !important;
                -webkit-print-color-adjust:exact;
                print-color-adjust:exact;

            }

            .no-print{

                display:none !important;

            }

        }

    </style>

</head>

<body>

<div class="page-header">

    <div class="title-row">

        <div class="title-line"></div>

        <h1>EDMO CONNECT</h1>

        <div class="title-line right"></div>

    </div>

    <div class="subtitle">

        Lot : {{ $batch }}

        —

        {{ $tickets->count() }} ticket(s)

    </div>

</div>

<div class="actions no-print">

    <a href="{{ route('tickets.preview.pdf', $batch) }}" class="btn-pdf">
        📄 Télécharger PDF
    </a>

    <button onclick="window.print()" class="btn-print">
        🖨️ Imprimer
    </button>

    <a href="{{ route('tickets.index') }}" class="btn-back">
        ← Retour à la liste
    </a>

</div>

<div class="container">

@foreach($tickets as $ticket)

<div class="ticket">

    <div class="header">

        <div class="brand">

            EDMO CONNECT

        </div>

        <div class="tagline">

            Accès Internet WiFi

        </div>

    </div>

    <div class="body">

        <div class="code-label">

            Code d'accès

        </div>

        <div class="code">

            {{ $ticket->code }}

        </div>

        <div class="divider"></div>

        <div class="info-row">

            <span class="label">Forfait</span>

            <span class="value">{{ $ticket->forfait->nom }}</span>

        </div>

        @if(isset($ticket->forfait->prix))

        <div class="info-row">

            <span class="label">Prix</span>

            <span class="value">{{ number_format($ticket->forfait->prix,0,',',' ') }} GNF</span>

        </div>

        @endif

    </div>

    <div class="footer">

        EDMO CONNECT — {{ now()->format('d/m/Y H:i') }}

    </div>

</div>

{{-- Saut de page toutes les 12 cartes (sauf après la dernière) --}}
@if($loop->iteration % 12 === 0 && !$loop->last)

    </div><div class="container page-break">

@endif

@endforeach

</div>

</body>

</html>