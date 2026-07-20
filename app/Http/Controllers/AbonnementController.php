<?php

namespace App\Http\Controllers;

use App\Models\Abonnement;
use App\Models\Forfait;
use App\Models\Hotspot;
use App\Models\User;
use Illuminate\Http\Request;

class AbonnementController extends Controller
{
    public function index()
    {
        $abonnements = Abonnement::with(['user', 'hotspot', 'forfait'])
            ->latest()
            ->paginate(10);

        return view('abonnements.index', compact('abonnements'));
    }

    public function create()
    {
        $users = User::orderBy('name')->get();
        $hotspots = Hotspot::where('is_active', true)->orderBy('name')->get();
        $forfaits = Forfait::where('is_active', true)->orderBy('nom')->get();

        return view('abonnements.create', compact('users', 'hotspots', 'forfaits'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'hotspot_id' => 'required|exists:hotspots,id',
            'forfait_id' => 'required|exists:forfaits,id',
            'date_debut' => 'required|date',
            'statut' => 'required|in:pending,active,expired,suspended,cancelled',
            'reference_paiement' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $forfait = Forfait::findOrFail($validated['forfait_id']);

        // Calcule automatiquement la date de fin selon la durée du forfait (en heures)
        $validated['date_fin'] = \Carbon\Carbon::parse($validated['date_debut'])
            ->addHours($forfait->duree);

        Abonnement::create($validated);

        return redirect()
            ->route('abonnements.index')
            ->with('success', 'Abonnement créé avec succès.');
    }

    public function edit(Abonnement $abonnement)
    {
        $users = User::orderBy('name')->get();
        $hotspots = Hotspot::orderBy('name')->get();
        $forfaits = Forfait::orderBy('nom')->get();

        return view('abonnements.edit', compact('abonnement', 'users', 'hotspots', 'forfaits'));
    }

    public function update(Request $request, Abonnement $abonnement)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'hotspot_id' => 'required|exists:hotspots,id',
            'forfait_id' => 'required|exists:forfaits,id',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut',
            'statut' => 'required|in:pending,active,expired,suspended,cancelled',
            'reference_paiement' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $abonnement->update($validated);

        return redirect()
            ->route('abonnements.index')
            ->with('success', 'Abonnement mis à jour avec succès.');
    }

    public function destroy(Abonnement $abonnement)
    {
        $abonnement->delete();

        return redirect()
            ->route('abonnements.index')
            ->with('success', 'Abonnement supprimé avec succès.');
    }
}