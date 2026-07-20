<?php

namespace App\Http\Controllers;

use App\Models\Abonnement;
use App\Models\Hotspot;
use App\Models\Paiement;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_utilisateurs' => User::count(),
            'hotspots_actifs' => Hotspot::where('is_active', true)->count(),
            'abonnements_actifs' => Abonnement::where('statut', 'active')->count(),
            'revenus_aujourdhui' => Paiement::where('statut', 'success')
                ->whereDate('created_at', today())
                ->sum('montant'),
            'revenus_mois' => Paiement::where('statut', 'success')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('montant'),
        ];

        $derniersPaiements = Paiement::with(['user', 'forfait'])
            ->where('statut', 'success')
            ->latest()
            ->limit(5)
            ->get();

        $derniersAbonnements = Abonnement::with(['user', 'hotspot', 'forfait'])
            ->latest()
            ->limit(5)
            ->get();

        // Revenus des 7 derniers jours pour le graphique
        $revenusParJour = Paiement::where('statut', 'success')
            ->where('created_at', '>=', now()->subDays(6)->startOfDay())
            ->select(DB::raw('DATE(created_at) as jour'), DB::raw('SUM(montant) as total'))
            ->groupBy('jour')
            ->orderBy('jour')
            ->get()
            ->keyBy('jour');

        $labels = [];
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $labels[] = now()->subDays($i)->translatedFormat('d M');
            $data[] = (float) ($revenusParJour[$date]->total ?? 0);
        }

        return view('dashboard', [
            'stats' => $stats,
            'derniersPaiements' => $derniersPaiements,
            'derniersAbonnements' => $derniersAbonnements,
            'chartLabels' => $labels,
            'chartData' => $data,
        ]);
    }
}