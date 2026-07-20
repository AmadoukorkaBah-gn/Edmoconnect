<?php

namespace App\Http\Controllers;

use App\Models\Forfait;
use Illuminate\Http\Request;

class ForfaitController extends Controller
{
    public function index()
    {
        $forfaits = Forfait::latest()->paginate(10);

        return view('forfaits.index', compact('forfaits'));
    }

    public function create()
    {
        return view('forfaits.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prix' => 'required|numeric|min:0',
            'duree' => 'required|integer|min:1',
            'download_speed' => 'nullable|integer|min:0',
            'upload_speed' => 'nullable|integer|min:0',
            'mikrotik_profile' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        Forfait::create($validated);

        return redirect()
            ->route('forfaits.index')
            ->with('success', 'Forfait créé avec succès.');
    }

    public function edit(Forfait $forfait)
    {
        return view('forfaits.edit', compact('forfait'));
    }

    public function update(Request $request, Forfait $forfait)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prix' => 'required|numeric|min:0',
            'duree' => 'required|integer|min:1',
            'download_speed' => 'nullable|integer|min:0',
            'upload_speed' => 'nullable|integer|min:0',
            'mikrotik_profile' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $forfait->update($validated);

        return redirect()
            ->route('forfaits.index')
            ->with('success', 'Forfait mis à jour avec succès.');
    }

    public function destroy(Forfait $forfait)
    {
        $forfait->delete();

        return redirect()
            ->route('forfaits.index')
            ->with('success', 'Forfait supprimé avec succès.');
    }
}