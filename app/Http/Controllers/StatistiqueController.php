<?php

namespace App\Http\Controllers;

use App\Models\Abonnement;
use App\Models\Hotspot;
use App\Models\Paiement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatistiqueController extends Controller
{
    public function index(Request $request)
    {
        $debut = $request->filled('debut')
            ? \Carbon\Carbon::parse($request->debut)->startOfDay()
            : now()->subDays(29)->startOfDay();

        $fin = $request->filled('fin')
            ? \Carbon\Carbon::parse($request->fin)->endOfDay()
            : now()->endOfDay();

        // KPIs generaux sur la periode
        $revenuTotal = Paiement::where('statut', 'success')
            ->whereBetween('created_at', [$debut, $fin])
            ->sum('montant');

        $nombrePaiements = Paiement::where('statut', 'success')
            ->whereBetween('created_at', [$debut, $fin])
            ->count();

        $nombreAbonnements = Abonnement::whereBetween('created_at', [$debut, $fin])->count();

        $panierMoyen = $nombrePaiements > 0 ? $revenuTotal / $nombrePaiements : 0;

        $tauxEchec = $this->calculerTauxEchec($debut, $fin);

        // Revenus par jour (graphique)
        $revenusParJour = Paiement::where('statut', 'success')
            ->whereBetween('created_at', [$debut, $fin])
            ->select(DB::raw('DATE(created_at) as jour'), DB::raw('SUM(montant) as total'))
            ->groupBy('jour')
            ->orderBy('jour')
            ->get()
            ->keyBy('jour');

        $labels = [];
        $data = [];
        $curseur = $debut->copy();

        while ($curseur->lte($fin)) {
            $cle = $curseur->format('Y-m-d');
            $labels[] = $curseur->translatedFormat('d M');
            $data[] = (float) ($revenusParJour[$cle]->total ?? 0);
            $curseur->addDay();
        }

        // Top forfaits vendus sur la periode
        $topForfaits = Paiement::where('statut', 'success')
            ->whereBetween('created_at', [$debut, $fin])
            ->select('forfait_id', DB::raw('COUNT(*) as nb_ventes'), DB::raw('SUM(montant) as revenu'))
            ->groupBy('forfait_id')
            ->orderByDesc('nb_ventes')
            ->with('forfait')
            ->limit(5)
            ->get();

        // Repartition par hotspot
        $parHotspot = Paiement::where('statut', 'success')
            ->whereBetween('created_at', [$debut, $fin])
            ->select('hotspot_id', DB::raw('COUNT(*) as nb_ventes'), DB::raw('SUM(montant) as revenu'))
            ->groupBy('hotspot_id')
            ->orderByDesc('revenu')
            ->with('hotspot')
            ->get();

        return view('statistiques.index', [
            'debut' => $debut,
            'fin' => $fin,
            'revenuTotal' => $revenuTotal,
            'nombrePaiements' => $nombrePaiements,
            'nombreAbonnements' => $nombreAbonnements,
            'panierMoyen' => $panierMoyen,
            'tauxEchec' => $tauxEchec,
            'chartLabels' => $labels,
            'chartData' => $data,
            'topForfaits' => $topForfaits,
            'parHotspot' => $parHotspot,
        ]);
    }

    private function calculerTauxEchec($debut, $fin): float
    {
        $total = Paiement::whereBetween('created_at', [$debut, $fin])->count();

        if ($total === 0) {
            return 0;
        }

        $echecs = Paiement::whereBetween('created_at', [$debut, $fin])
            ->whereIn('statut', ['failed', 'cancelled'])
            ->count();

        return round(($echecs / $total) * 100, 1);
    }
}