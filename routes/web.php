<?php

use App\Http\Controllers\AbonnementController;
use App\Http\Controllers\Client\AchatController;
use App\Http\Controllers\Client\DjomyWebhookController;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ForfaitController;
use App\Http\Controllers\HotspotController;
use App\Http\Controllers\MikrotikServerController;
use App\Http\Controllers\PaiementController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| ESPACE CLIENT (PUBLIC)
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/connexion', [HomeController::class, 'hotspots'])->name('client.hotspots');

Route::prefix('hotspot/{hotspot}')->name('client.')->group(function () {
    Route::get('/', [AchatController::class, 'index'])->name('accueil');
    Route::get('/forfait/{forfait}', [AchatController::class, 'paiement'])->name('paiement');
    Route::post('/forfait/{forfait}/payer', [AchatController::class, 'initierPaiement'])->name('payer');
});

Route::get('/paiement/succes', [AchatController::class, 'retourSucces'])->name('client.paiement.succes');
Route::get('/paiement/annule', [AchatController::class, 'retourAnnule'])->name('client.paiement.annule');
Route::get('/mon-abonnement', [AchatController::class, 'monAbonnement'])->name('client.abonnement');

Route::post('/webhooks/djomy', [DjomyWebhookController::class, 'handle'])->name('webhooks.djomy');

/*
|--------------------------------------------------------------------------
| DASHBOARD ADMIN (BREEZE)
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

/*
|--------------------------------------------------------------------------
| ADMIN — CRUD (protégés par auth via Breeze)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('parametres', [\App\Http\Controllers\ParametreController::class, 'edit'])->name('parametres.edit');
Route::put('parametres', [\App\Http\Controllers\ParametreController::class, 'update'])->name('parametres.update');

    Route::resource('mikrotik-servers', MikrotikServerController::class);
    Route::post('mikrotik-servers/{mikrotikServer}/test', [MikrotikServerController::class, 'testConnection'])
        ->name('mikrotik-servers.test');

    Route::resource('hotspots', HotspotController::class);
    Route::get('hotspots/{hotspot}/forfaits', [HotspotController::class, 'forfaits'])
        ->name('hotspots.forfaits');
    Route::put('hotspots/{hotspot}/forfaits', [HotspotController::class, 'syncForfaits'])
        ->name('hotspots.forfaits.update');

    Route::resource('forfaits', ForfaitController::class);

    Route::resource('utilisateurs', UserController::class)
        ->parameters(['utilisateurs' => 'utilisateur']);

    Route::resource('abonnements', AbonnementController::class);

    Route::resource('paiements', PaiementController::class)
        ->except(['edit', 'update']);

        Route::get('statistiques', [\App\Http\Controllers\StatistiqueController::class, 'index'])
    ->name('statistiques.index');

    Route::get('clients', [\App\Http\Controllers\ClientController::class, 'index'])->name('clients.index');
Route::get('clients/{client}', [\App\Http\Controllers\ClientController::class, 'show'])->name('clients.show');



});

/*
|--------------------------------------------------------------------------
| PROFILE (BREEZE DEFAULT)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';