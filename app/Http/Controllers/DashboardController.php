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
            'paiements_mois' => Paiement::where('statut', 'success')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('montant'),
        ];

        $derniersPaiements = Paiement::with(['user'])
            ->where('statut', 'success')
            ->latest()
            ->limit(5)
            ->get();

        $derniersAbonnements = Abonnement::with(['user', 'hotspot', 'forfait'])
            ->latest()
            ->limit(5)
            ->get();

        // Graphique 1 : nouveaux abonnements vs abonnements actifs, 7 derniers jours
        $labels = [];
        $nouveauxAbonnements = [];
        $abonnementsActifsParJour = [];

        for ($i = 6; $i >= 0; $i--) {
            $jour = now()->subDays($i);

            $labels[] = $jour->translatedFormat('d M');

            $nouveauxAbonnements[] = Abonnement::whereDate('created_at', $jour->format('Y-m-d'))->count();

            $abonnementsActifsParJour[] = Abonnement::where('date_debut', '<=', $jour->endOfDay())
                ->where('date_fin', '>=', $jour->startOfDay())
                ->count();
        }

        // Graphique 2 : répartition des utilisateurs par statut
        $repartitionUtilisateurs = [
            'active' => User::where('status', 'active')->count(),
            'inactive' => User::where('status', 'inactive')->count(),
            'blocked' => User::where('status', 'blocked')->count(),
        ];

        return view('admin.dashboard', [
            'stats' => $stats,
            'derniersPaiements' => $derniersPaiements,
            'derniersAbonnements' => $derniersAbonnements,
            'chartLabels' => $labels,
            'chartNouveauxAbonnements' => $nouveauxAbonnements,
            'chartAbonnementsActifs' => $abonnementsActifsParJour,
            'repartitionUtilisateurs' => $repartitionUtilisateurs,
        ]);
    }
}