@extends('layouts.app')

@section('content')

<div class="max-w-3xl mx-auto">

   <button
type="submit"
class="w-full bg-blue-600 hover:bg-blue-700 text-white py-4 rounded-xl font-semibold transition">

🎫 Générer les tickets

</button>

    <form action="{{ route('tickets.generate') }}" method="POST">

        @csrf

        <div class="bg-white rounded-xl shadow p-6 space-y-5">

            <div>

                <label class="block mb-2 font-medium">
                    Hotspot
                </label>

                <select
                    name="hotspot_id"
                    class="w-full border rounded-lg p-3">

                    @foreach($hotspots as $hotspot)

                        <option value="{{ $hotspot->id }}">
                            {{ $hotspot->name }}
                        </option>

                    @endforeach

                </select>

            </div>

            <div>

                <label class="block mb-2 font-medium">
                    Forfait
                </label>

                <select
                    name="forfait_id"
                    class="w-full border rounded-lg p-3">

                    @foreach($forfaits as $forfait)

                        <option value="{{ $forfait->id }}">
                            {{ $forfait->nom }}
                        </option>

                    @endforeach

                </select>

            </div>

            <div>

                <label class="block mb-2 font-medium">
                    Nombre de tickets
                </label>

                <input
                    type="number"
                    name="quantity"
                    value="10"
                    min="1"
                    max="1000"
                    class="w-full border rounded-lg p-3">

            </div>

            <button
                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg">

                Générer les tickets

            </button>

        </div>

    </form>

</div>

@endsection