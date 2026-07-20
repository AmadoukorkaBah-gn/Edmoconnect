<?php

namespace App\Http\Controllers;

use App\Models\Parametre;
use Illuminate\Http\Request;

class ParametreController extends Controller
{
    public function edit()
    {
        $parametre = Parametre::courant();

        $djomyConfigure = filled(config('djomy.client_id')) && filled(config('djomy.client_secret'));
        $nimbaConfigure = filled(config('nimbasms.service_id')) && filled(config('nimbasms.secret_token'));
        $nombreServeursMikrotik = \App\Models\MikrotikServer::where('is_active', true)->count();

        return view('parametres.edit', compact(
            'parametre',
            'djomyConfigure',
            'nimbaConfigure',
            'nombreServeursMikrotik'
        ));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'nom_entreprise' => 'required|string|max:255',
            'telephone_support' => 'nullable|string|max:20',
            'email_support' => 'nullable|email|max:255',
            'adresse' => 'nullable|string|max:255',
            'rappel_expiration_minutes' => 'required|integer|min:5|max:180',
        ]);

        Parametre::courant()->update($validated);

        return redirect()
            ->route('parametres.edit')
            ->with('success', 'Parametres mis a jour avec succes.');
    }
}