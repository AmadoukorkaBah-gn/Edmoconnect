<?php

namespace App\Http\Controllers;

use App\Models\MikrotikServer;
use Illuminate\Http\Request;
use App\Services\MikrotikService;

class MikrotikServerController extends Controller
{
    public function index()
    {
        $servers = MikrotikServer::latest()->paginate(10);

        return view('mikrotik-servers.index', compact('servers'));
    }

    public function create()
    {
        return view('mikrotik-servers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'host' => 'required|string|max:255',
            'port' => 'required|integer|min:1|max:65535',
            'username' => 'required|string|max:255',
            'password' => 'required|string|max:255',
            'ssl' => 'boolean',
            'location' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        $validated['ssl'] = $request->has('ssl');
        $validated['is_active'] = $request->has('is_active');

        MikrotikServer::create($validated);

        return redirect()
            ->route('mikrotik-servers.index')
            ->with('success', 'Serveur MikroTik créé avec succès.');
    }

    public function edit(MikrotikServer $mikrotikServer)
    {
        return view('mikrotik-servers.edit', ['server' => $mikrotikServer]);
    }

    public function update(Request $request, MikrotikServer $mikrotikServer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'host' => 'required|string|max:255',
            'port' => 'required|integer|min:1|max:65535',
            'username' => 'required|string|max:255',
            'password' => 'nullable|string|max:255',
            'ssl' => 'boolean',
            'location' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        $validated['ssl'] = $request->has('ssl');
        $validated['is_active'] = $request->has('is_active');

        // Ne pas écraser le mot de passe si le champ est laissé vide
        if (empty($validated['password'])) {
            unset($validated['password']);
        }

        $mikrotikServer->update($validated);

        return redirect()
            ->route('mikrotik-servers.index')
            ->with('success', 'Serveur MikroTik mis à jour avec succès.');
    }

    public function destroy(MikrotikServer $mikrotikServer)
    {
        $mikrotikServer->delete();

        return redirect()
            ->route('mikrotik-servers.index')
            ->with('success', 'Serveur MikroTik supprimé avec succès.');
    }
    public function testConnection(MikrotikServer $mikrotikServer)
{
    $service = new MikrotikService($mikrotikServer);
    $result = $service->testConnection();

    if ($result['success']) {
        return back()->with('success', "Connexion réussie ! Identité du routeur : {$result['identity']}");
    }

    return back()->with('error', "Échec de connexion : {$result['error']}");
}
}