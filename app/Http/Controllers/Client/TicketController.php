<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Hotspot;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TicketController extends Controller
{
    /**
     * Affiche le formulaire d'utilisation d'un ticket.
     */
    public function form(Request $request, Hotspot $hotspot)
    {
        return view('client.ticket', [
            'hotspot'       => $hotspot,
            'linkLogin'     => $request->link_login,
            'linkOrig'      => $request->link_orig,
            'mac'           => $request->mac,
            'chapId'        => $request->chap_id,
            'chapChallenge' => $request->chap_challenge,
        ]);
    }

    /**
     * Vérifie et active un ticket.
     */
    public function connect(Request $request, Hotspot $hotspot)
    {
        $request->validate([
            'telephone' => ['required', 'string', 'max:20'],
            'ticket'    => ['required', 'string'],
        ]);

        // Nettoyage du numéro
        $telephone = preg_replace('/\D/', '', $request->telephone);

        // Recherche du ticket
        $ticket = Ticket::where('hotspot_id', $hotspot->id)
            ->where('code', strtoupper(trim($request->ticket)))
            ->first();

        if (!$ticket) {
            return back()->withErrors([
                'ticket' => 'Ticket invalide.',
            ])->withInput();
        }

        if ($ticket->status !== 'available') {
            return back()->withErrors([
                'ticket' => 'Ce ticket n’est plus disponible.',
            ])->withInput();
        }

        // Recherche ou création automatique du client
        $user = User::where('telephone', $telephone)->first();

        if (!$user) {

            $user = User::create([
                'name'       => 'Client ' . $telephone,
                'telephone'  => $telephone,
                'email'      => null,
                'password'   => Hash::make('wifi-zone'),
                'status'     => 'active',
                 // rôle Client (à adapter selon ta base)
            ]);
        }

        // Activation du ticket
        $ticket->update([
            'user_id'      => $user->id,
            'status'       => 'activated',
            'used'         => true,
            'activated_at' => now(),
            'used_at'      => now(),
        ]);

        $ticket->refresh();

        return view('client.ticket-success', [
            'username'      => $ticket->username,
            'password'      => $ticket->password,
            'telephone'     => $telephone,
            'linkLogin'     => $request->link_login,
            'linkOrig'      => $request->link_orig,
            'mac'           => $request->mac,
            'chapId'        => $request->chap_id,
            'chapChallenge' => $request->chap_challenge,
        ]);
    }
}