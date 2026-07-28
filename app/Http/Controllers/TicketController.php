<?php

namespace App\Http\Controllers;

use App\Models\Forfait;
use App\Models\Hotspot;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class TicketController extends Controller
{
    /**
     * ------------------------------------------------------------------
     * Liste des tickets
     * ------------------------------------------------------------------
     */
    public function index()
    {
        $tickets = Ticket::with([
                'hotspot',
                'forfait'
            ])
            ->latest()
            ->paginate(20);

        return view('tickets.index', compact('tickets'));
    }

    /**
     * ------------------------------------------------------------------
     * Formulaire de génération
     * ------------------------------------------------------------------
     */
    public function create()
    {
        $hotspots = Hotspot::orderBy('name')->get();

        $forfaits = Forfait::orderBy('nom')->get();

        return view('tickets.create', compact(
            'hotspots',
            'forfaits'
        ));
    }

    /**
     * ------------------------------------------------------------------
     * Générer un nouveau lot de tickets
     * ------------------------------------------------------------------
     */
    public function generate(Request $request)
    {
        $request->validate([
            'hotspot_id' => ['required', 'exists:hotspots,id'],
            'forfait_id' => ['required', 'exists:forfaits,id'],
            'quantity'   => ['required', 'integer', 'min:1', 'max:1000'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Batch
        |--------------------------------------------------------------------------
        | Tous les tickets générés en une seule opération auront
        | exactement le même identifiant.
        |
        */

        $batch = 'EDMO-' . now()->format('YmdHis') . '-' . strtoupper(Str::random(5));

        $tickets = collect();

        for ($i = 0; $i < $request->quantity; $i++) {

            $ticket = Ticket::create([

                'batch'       => $batch,

                'hotspot_id'  => $request->hotspot_id,

                'forfait_id'  => $request->forfait_id,

                'code'        => $this->generateTicketCode(),

                'username'    => $this->generateUsername(),

                'password'    => strtoupper(Str::random(8)),

                'status'      => 'available',

                'activated_at'=> null,

            ]);

            $tickets->push($ticket);
        }

        return redirect()
    ->route('tickets.preview.print', $batch)
    ->with(
        'success',
        "{$tickets->count()} ticket(s) généré(s) avec succès."
    );
             }
    /**
     * ------------------------------------------------------------------
     * Aperçu d'un lot de tickets
     * ------------------------------------------------------------------
     */
    public function preview(string $batch)
    {
        $tickets = Ticket::with([
                'hotspot',
                'forfait'
            ])
            ->where('batch', $batch)
            ->orderBy('id')
            ->get();

        if ($tickets->isEmpty()) {
            return redirect()
                ->route('tickets.index')
                ->with('error', 'Le lot demandé est introuvable.');
        }

        return view('tickets.preview', [
            'batch'   => $batch,
            'tickets' => $tickets,
        ]);
    }

    /**
     * ------------------------------------------------------------------
     * Afficher un ticket
     * ------------------------------------------------------------------
     */
    public function show(Ticket $ticket)
    {
        $ticket->load([
            'hotspot',
            'forfait',
        ]);

        return view('tickets.show', compact('ticket'));
    }

    /**
     * ------------------------------------------------------------------
     * Impression d'un lot
     * ------------------------------------------------------------------
     */
    public function print(string $batch)
    {
        $tickets = Ticket::with([
                'hotspot',
                'forfait'
            ])
            ->where('batch', $batch)
            ->orderBy('id')
            ->get();

        if ($tickets->isEmpty()) {
            return redirect()
                ->route('tickets.index')
                ->with('error', 'Aucun ticket à imprimer.');
        }

        return view('tickets.print', [
            'batch'   => $batch,
            'tickets' => $tickets,
        ]);
    }

    /**
     * ------------------------------------------------------------------
     * Télécharger un lot en PDF
     * ------------------------------------------------------------------
     */
    public function pdf(string $batch)
    {
        $tickets = Ticket::with([
                'hotspot',
                'forfait'
            ])
            ->where('batch', $batch)
            ->orderBy('id')
            ->get();

        if ($tickets->isEmpty()) {
            return redirect()
                ->route('tickets.index')
                ->with('error', 'Aucun ticket trouvé.');
        }

        $pdf = Pdf::loadView(
            'tickets.pdf',
            [
                'batch'   => $batch,
                'tickets' => $tickets,
            ]
        );

        $pdf->setPaper('a4', 'portrait');

        return $pdf->download(
            'tickets-'.$batch.'.pdf'
        );
    }
    /**
     * ------------------------------------------------------------------
     * Supprimer un ticket
     * ------------------------------------------------------------------
     */
    public function destroy(Ticket $ticket)
    {
        // Empêcher la suppression d'un ticket déjà utilisé
        if ($ticket->status === 'activated') {

            return redirect()
                ->route('tickets.index')
                ->with(
                    'error',
                    'Impossible de supprimer un ticket déjà activé.'
                );
        }

        $ticket->delete();

        return redirect()
            ->route('tickets.index')
            ->with(
                'success',
                'Le ticket a été supprimé avec succès.'
            );
    }

    /**
     * ------------------------------------------------------------------
     * Génère un code de ticket unique
     * Exemple :
     * EDMO-8KX9P2QW
     * ------------------------------------------------------------------
     */
    private function generateTicketCode(): string
    {
        do {

            $code = 'EDMO-' . strtoupper(Str::random(8));

        } while (
            Ticket::where('code', $code)->exists()
        );

        return $code;
    }

    /**
     * ------------------------------------------------------------------
     * Génère un identifiant Hotspot unique
     * Exemple :
     * wifi654321
     * ------------------------------------------------------------------
     */
    private function generateUsername(): string
    {
        do {

            $username = 'wifi' . random_int(
                100000,
                999999
            );

        } while (
            Ticket::where('username', $username)->exists()
        );

        return $username;
    }

}
   