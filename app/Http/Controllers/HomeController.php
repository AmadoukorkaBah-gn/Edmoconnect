<?php

namespace App\Http\Controllers;

use App\Models\Forfait;

class HomeController extends Controller
{
    public function index()
    {
        $forfaits = Forfait::where('is_active', true)
            ->orderBy('prix')
            ->get();

        $maxSpeed = $forfaits->max('download_speed') ?: 1;

        $forfaits = $forfaits->map(function ($forfait) use ($maxSpeed) {
            $ratio = $forfait->download_speed
                ? $forfait->download_speed / $maxSpeed
                : 0.5;

            $forfait->signal_bars = max(1, min(4, (int) ceil($ratio * 4)));
            $forfait->duree_label = $this->formatDuree($forfait->duree);

            return $forfait;
        });

        return view('client.home', compact('forfaits', 'parametre'));
    }

    private function formatDuree(int $heures): string
    {
        if ($heures < 24) {
            return $heures . ' h';
        }

        $jours = intdiv($heures, 24);
        $reste = $heures % 24;

        if ($reste === 0) {
            return $jours . ' jour' . ($jours > 1 ? 's' : '');
        }

        return $jours . 'j ' . $reste . 'h';
    }
}