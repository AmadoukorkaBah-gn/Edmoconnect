@extends('layouts.app')

@section('content')

<div class="max-w-3xl mx-auto">

    <div class="flex items-center mb-6">
        <a href="{{ route('hotspots.index') }}" class="text-gray-500 hover:text-gray-700 mr-4">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Forfaits disponibles</h1>
            <p class="text-gray-500 text-sm">{{ $hotspot->name }}</p>
        </div>
    </div>

    @if ($forfaits->isEmpty())

        <div class="bg-yellow-50 border border-yellow-200 text-yellow-700 px-4 py-3 rounded-lg">
            Aucun forfait actif. <a href="{{ route('forfaits.create') }}" class="underline font-medium">Crée d'abord un forfait</a>.
        </div>

    @else

        <form action="{{ route('hotspots.forfaits.update', $hotspot) }}" method="POST" class="bg-white rounded-xl shadow-sm p-8">
            @csrf
            @method('PUT')

            <p class="text-sm text-gray-500 mb-6">
                Coche les forfaits que les clients pourront acheter sur ce hotspot.
            </p>

            <div class="space-y-3 mb-6">

                @foreach ($forfaits as $forfait)
                    <label class="flex items-center justify-between p-4 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition">

                        <div class="flex items-center">
                            <input type="checkbox" name="forfait_ids[]" value="{{ $forfait->id }}"
                                {{ in_array($forfait->id, $forfaitsLies) ? 'checked' : '' }}
                                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 mr-4">

                            <div>
                                <div class="font-medium text-gray-800">{{ $forfait->nom }}</div>
                                <div class="text-sm text-gray-500">{{ $forfait->duree }} h de connexion</div>
                            </div>
                        </div>

                        <div class="text-right">
                            <div class="font-mono font-semibold text-gray-800">
                                {{ number_format($forfait->prix, 0, ',', ' ') }} GNF
                            </div>
                        </div>

                    </label>
                @endforeach

            </div>

            <div class="flex justify-end space-x-3 pt-4 border-t border-gray-100">
                <a href="{{ route('hotspots.index') }}"
                    class="px-5 py-2.5 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50 transition">
                    Annuler
                </a>
                <button type="submit"
                    class="px-5 py-2.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-medium transition">
                    Enregistrer
                </button>
            </div>

        </form>

    @endif

</div>

@endsection