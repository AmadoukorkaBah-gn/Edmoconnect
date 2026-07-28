<!DOCTYPE html>
<html lang="fr">

<head>

<meta charset="UTF-8">

<title>Tickets WiFi</title>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{

    font-family:DejaVu Sans,sans-serif;
    font-size:50px;
    color:#333;

}

.title{

    text-align:center;
    font-size:24px;
    color:#2563eb;
    font-weight:bold;

}

.subtitle{

    text-align:center;
    margin-top:5px;
    margin-bottom:25px;
    color:#666;

}

.ticket{

    width:48%;
    display:inline-block;
    vertical-align:top;
    margin:1%;
    border:1px solid #2563eb;
    border-radius:10px;
    overflow:hidden;
    page-break-inside:avoid;

}

.header{

    background:#2563eb;
    color:white;
    padding:12px;
    text-align:center;

}

.header h2{

    font-size:18px;

}

.content{

    padding:15px;

}

.code{

    text-align:center;
    font-size:18px;
    font-weight:bold;
    color:#2563eb;
    margin-bottom:15px;

}

table{

    width:100%;
    border-collapse:collapse;

}

td{

    padding:5px 0;

}

.label{

    color:#666;
    width:40%;

}

.value{

    font-weight:bold;

}

.qrcode{

    width:70px;
    height:70px;
    border:1px dashed #999;
    margin:20px auto;
    text-align:center;
    line-height:70px;
    color:#999;
    font-size:10px;

}

.footer{

    border-top:1px dashed #ccc;
    text-align:center;
    padding:10px;
    font-size:10px;
    color:#777;

}

</style>

</head>

<body>

<div class="title">

EDMO CONNECT

</div>

<div class="subtitle">

Lot :

<strong>

{{ $batch }}

</strong>

&nbsp;&nbsp;|&nbsp;&nbsp;

{{ $tickets->count() }}

ticket(s)

</div>

@foreach($tickets as $ticket)

<div class="ticket">

<div class="header">

<h2>

Ticket WiFi

</h2>

</div>

<div class="content">

<div class="code">

{{ $ticket->code }}

</div>

<table>

<tr>

<td class="label">

Hotspot

</td>

<td class="value">

{{ $ticket->hotspot->name }}

</td>

</tr>

<tr>

<td class="label">

Forfait

</td>

<td class="value">

{{ $ticket->forfait->nom }}

</td>

</tr>

<tr>

<td class="label">

Durée

</td>

<td class="value">

{{ $ticket->forfait->duree }}

</td>

</tr>

@if(isset($ticket->forfait->prix))

<tr>

<td class="label">

Prix

</td>

<td class="value">

{{ number_format($ticket->forfait->prix,0,',',' ') }}

GNF

</td>

</tr>

@endif

<tr>

<td class="label">

Utilisateur

</td>

<td class="value">

{{ $ticket->username }}

</td>

</tr>

<tr>

<td class="label">

Mot de passe

</td>

<td class="value">

{{ $ticket->password }}

</td>

</tr>

<tr>

<td class="label">

Etat

</td>

<td class="value">

{{ ucfirst($ticket->status) }}

</td>

</tr>

</table>

<div class="qrcode">

QR CODE

</div>

</div>

<div class="footer">

EDMO CONNECT

<br>

{{ now()->format('d/m/Y H:i') }}

</div>

</div>

@endforeach

</body>

</html>