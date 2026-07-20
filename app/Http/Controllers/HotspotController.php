<?php

namespace App\Http\Controllers;

use App\Models\Hotspot;
use App\Models\MikrotikServer;
use Illuminate\Http\Request;
use App\Models\Forfait;

class HotspotController extends Controller
{
    public function index()
    {
        $hotspots = Hotspot::with('mikrotikServer')->latest()->paginate(10);

        return view('hotspots.index', compact('hotspots'));
    }

    public function create()
    {
        $servers = MikrotikServer::where('is_active', true)->orderBy('name')->get();

        return view('hotspots.create', compact('servers'));
    }
    

    public function store(Request $request)
    {
        $validated = $request->validate([
            'mikrotik_server_id' => 'required|exists:mikrotik_servers,id',
            'name' => 'required|string|max:255',
            'profile' => 'nullable|string|max:255',
            'interface' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        Hotspot::create($validated);

        return redirect()
            ->route('hotspots.index')
            ->with('success', 'Hotspot créé avec succès.');
    }

    public function edit(Hotspot $hotspot)
    {
        $servers = MikrotikServer::where('is_active', true)->orderBy('name')->get();

        return view('hotspots.edit', compact('hotspot', 'servers'));
    }

    public function update(Request $request, Hotspot $hotspot)
    {
        $validated = $request->validate([
            'mikrotik_server_id' => 'required|exists:mikrotik_servers,id',
            'name' => 'required|string|max:255',
            'profile' => 'nullable|string|max:255',
            'interface' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $hotspot->update($validated);

        return redirect()
            ->route('hotspots.index')
            ->with('success', 'Hotspot mis à jour avec succès.');
    }

    public function destroy(Hotspot $hotspot)
    {
        $hotspot->delete();

        return redirect()
            ->route('hotspots.index')
            ->with('success', 'Hotspot supprimé avec succès.');
    }
    public function forfaits(Hotspot $hotspot)
{
    $forfaits = Forfait::where('is_active', true)->orderBy('nom')->get();
    $forfaitsLies = $hotspot->forfaits()->pluck('forfaits.id')->toArray();

    return view('hotspots.forfaits', compact('hotspot', 'forfaits', 'forfaitsLies'));
}

public function syncForfaits(Request $request, Hotspot $hotspot)
{
    $validated = $request->validate([
        'forfait_ids' => 'array',
        'forfait_ids.*' => 'exists:forfaits,id',
    ]);

    $hotspot->forfaits()->sync($validated['forfait_ids'] ?? []);

    return redirect()
        ->route('hotspots.index')
        ->with('success', "Forfaits mis à jour pour \"{$hotspot->name}\".");
}
}