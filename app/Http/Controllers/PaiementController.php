<?php

namespace App\Http\Controllers;

use App\Models\Forfait;
use App\Models\Hotspot;
use App\Models\Paiement;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PaiementController extends Controller
{
    public function index()
    {
        $paiements = Paiement::with(['user', 'forfait', 'hotspot'])
            ->latest()
            ->paginate(10);

        return view('paiements.index', compact('paiements'));
    }

    public function show(Paiement $paiement)
    {
        $paiement->load(['user', 'forfait', 'hotspot']);

        return view('paiements.show', compact('paiement'));
    }

    public function create()
    {
        $users = User::orderBy('name')->get();
        $hotspots = Hotspot::where('is_active', true)->orderBy('name')->get();
        $forfaits = Forfait::where('is_active', true)->orderBy('nom')->get();

        return view('paiements.create', compact('users', 'hotspots', 'forfaits'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'forfait_id' => 'required|exists:forfaits,id',
            'hotspot_id' => 'required|exists:hotspots,id',
            'montant' => 'required|numeric|min:0',
            'methode' => 'required|string|max:255',
            'statut' => 'required|in:pending,success,failed,cancelled',
        ]);

        $validated['reference'] = 'MANUEL-' . strtoupper(Str::random(10));

        Paiement::create($validated);

        return redirect()
            ->route('paiements.index')
            ->with('success', 'Paiement enregistré avec succès.');
    }

    public function edit(Paiement $paiement)
    {
        return view('paiements.edit', compact('paiement'));
    }

    public function update(Request $request, Paiement $paiement)
    {
        $validated = $request->validate([
            'statut' => 'required|in:pending,success,failed,cancelled',
        ]);

        $paiement->update($validated);

        return redirect()
            ->route('paiements.index')
            ->with('success', 'Statut du paiement mis à jour.');
    }

    public function destroy(Paiement $paiement)
    {
        $paiement->delete();

        return redirect()
            ->route('paiements.index')
            ->with('success', 'Paiement supprimé avec succès.');
    }
}