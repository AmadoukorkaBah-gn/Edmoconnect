@extends('layouts.app')

@section('content')


<div class="flex justify-between items-center mb-6">


<h1 class="text-2xl font-bold">
Tickets
</h1>


<div class="flex gap-3">


<a href="{{ route('tickets.create') }}"
class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">

+ Générer

</a>


</div>


</div>




@if(session('success'))

<div class="bg-green-100 text-green-700 p-4 rounded-lg mb-4">

{{ session('success') }}

</div>

@endif





<form id="tickets-form" method="POST">

@csrf


<div class="bg-white rounded-xl shadow overflow-hidden">


<table class="w-full">


<thead>

<tr class="bg-gray-100">


<th class="p-3">
<input type="checkbox" id="checkAll">
</th>


<th class="p-3">
Code
</th>


<th>
Hotspot
</th>


<th>
Forfait
</th>


<th>
Utilisateur
</th>


<th>
Etat
</th>


<th>
Action
</th>


</tr>


</thead>




<tbody>


@forelse($tickets as $ticket)


<tr class="border-t">


<td class="text-center">

<input 
type="checkbox"
name="tickets[]"
value="{{ $ticket->id }}"
class="ticket-check">

</td>




<td class="p-3 font-mono font-bold">

{{ $ticket->code }}

</td>




<td class="text-center">

{{ $ticket->hotspot->name }}

</td>




<td class="text-center">

{{ $ticket->forfait->nom }}

</td>




<td class="text-center">

{{ $ticket->username }}

</td>




<td class="text-center">


@if($ticket->status == 'activated')

<span class="bg-green-100 text-green-700 px-3 py-1 rounded-full">

Activé

</span>


@elseif($ticket->status == 'cancelled')


<span class="bg-red-100 text-red-700 px-3 py-1 rounded-full">

Annulé

</span>


@else


<span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full">

Disponible

</span>


@endif


</td>




<td class="text-center">


<a href="{{ route('tickets.show',$ticket) }}"
class="text-blue-600 font-bold">

👁️

</a>


</td>


</tr>


@empty


<tr>

<td colspan="7" class="p-5 text-center">

Aucun ticket

</td>

</tr>


@endforelse



</tbody>


</table>


</div>


</form>



<div class="mt-5">

{{ $tickets->links() }}

</div>





<script>

document
.getElementById('checkAll')
.addEventListener('change', function(){

document
.querySelectorAll('.ticket-check')
.forEach(cb => cb.checked=this.checked);

});


</script>


@endsection