<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $recherche = $request->input('q');

        $clients = User::whereNull('role_id')
            ->when($recherche, function ($query) use ($recherche) {
                $query->where(function ($q) use ($recherche) {
                    $q->where('name', 'like', "%{$recherche}%")
                        ->orWhere('telephone', 'like', "%{$recherche}%");
                });
            })
            ->withCount('abonnements')
            ->withSum(['paiements as total_depense' => function ($q) {
                $q->where('statut', 'success');
            }], 'montant')
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('clients.index', compact('clients', 'recherche'));
    }

    public function show(User $client)
    {
        abort_if(! is_null($client->role_id), 404);

        $client->load([
            'abonnements' => fn ($q) => $q->latest()->with(['hotspot', 'forfait']),
            'paiements' => fn ($q) => $q->latest()->with(['forfait', 'hotspot']),
        ]);

        $totalDepense = $client->paiements->where('statut', 'success')->sum('montant');

        $abonnementActif = $client->abonnements->first(function ($a) {
            return $a->statut === 'active' && $a->date_fin->isFuture();
        });

        return view('clients.show', compact('client', 'totalDepense', 'abonnementActif'));
    }
}