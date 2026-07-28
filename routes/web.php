<?php

use Illuminate\Support\Facades\Route;

// Controllers Admin
use App\Http\Controllers\AbonnementController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ForfaitController;
use App\Http\Controllers\HotspotController;
use App\Http\Controllers\MikrotikServerController;
use App\Http\Controllers\PaiementController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\ClientController;

// Controllers Client
use App\Http\Controllers\Client\AchatController;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Client\DjomyWebhookController;
use App\Http\Controllers\Client\TicketController as ClientTicketController;


/*
|--------------------------------------------------------------------------
| ESPACE CLIENT (PUBLIC)
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])
    ->name('home');


Route::get('/connexion', [HomeController::class, 'hotspots'])
    ->name('client.hotspots');



/*
|--------------------------------------------------------------------------
| ACHAT WIFI
|--------------------------------------------------------------------------
*/

Route::prefix('hotspot/{hotspot}')
    ->name('client.')
    ->group(function () {

        Route::get('/', [AchatController::class, 'index'])
            ->name('accueil');


        Route::get('/forfait/{forfait}', [AchatController::class, 'paiement'])
            ->name('paiement');


        Route::post('/forfait/{forfait}/payer', [AchatController::class, 'initierPaiement'])
            ->name('payer');



        /*
        |--------------------------------------------------------------------------
        | UTILISER UN TICKET
        |--------------------------------------------------------------------------
        */

        Route::get('/ticket', [ClientTicketController::class, 'form'])
            ->name('ticket.form');


        Route::post('/ticket', [ClientTicketController::class, 'connect'])
            ->name('ticket.connect');

    });



Route::get('/paiement/succes',
    [AchatController::class, 'retourSucces']
)->name('client.paiement.succes');


Route::get('/paiement/annule',
    [AchatController::class, 'retourAnnule']
)->name('client.paiement.annule');


Route::get('/mon-abonnement',
    [AchatController::class, 'monAbonnement']
)->name('client.abonnement');



/*
|--------------------------------------------------------------------------
| WEBHOOK DJOMY
|--------------------------------------------------------------------------
*/

Route::post('/webhooks/djomy',
    [DjomyWebhookController::class, 'handle']
)->name('webhooks.djomy');





/*
|--------------------------------------------------------------------------
| DASHBOARD ADMIN
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])
    ->group(function () {

        Route::get('/dashboard',
            [DashboardController::class, 'index']
        )->name('dashboard');

    });





/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/

Route::middleware('auth')
    ->group(function () {


        /*
        |--------------------------------------------------------------------------
        | PARAMETRES
        |--------------------------------------------------------------------------
        */

        Route::get('parametres',
            [\App\Http\Controllers\ParametreController::class, 'edit']
        )->name('parametres.edit');


        Route::put('parametres',
            [\App\Http\Controllers\ParametreController::class, 'update']
        )->name('parametres.update');




        /*
        |--------------------------------------------------------------------------
        | SERVEURS MIKROTIK
        |--------------------------------------------------------------------------
        */

        Route::resource(
            'mikrotik-servers',
            MikrotikServerController::class
        );


        Route::post(
            'mikrotik-servers/{mikrotikServer}/test',
            [MikrotikServerController::class, 'testConnection']
        )->name('mikrotik-servers.test');





       /*
|--------------------------------------------------------------------------
| TICKETS ADMIN
|--------------------------------------------------------------------------
*/

Route::prefix('tickets')
    ->name('tickets.')
    ->group(function () {

        // Liste des tickets
        Route::get('/', [TicketController::class, 'index'])
            ->name('index');

        // Formulaire de génération
        Route::get('/create', [TicketController::class, 'create'])
            ->name('create');

        // Génération des tickets
        Route::post('/generate', [TicketController::class, 'generate'])
            ->name('generate');

        // Impression de tickets sélectionnés (checkboxes dans index.blade.php)
        Route::post('/print', [TicketController::class, 'print'])
            ->name('print');

        // PDF de tickets sélectionnés (checkboxes dans index.blade.php)
        Route::post('/pdf', [TicketController::class, 'pdf'])
            ->name('pdf');

        // Aperçu d'un lot fraîchement généré (ex: /tickets/preview/EDMO-20260727-AB12CD)
        Route::get('/preview/{batch}', [TicketController::class, 'preview'])
            ->name('preview');

        // Impression du lot (par batch)
        Route::get('/preview/{batch}/print', [TicketController::class, 'print'])
    ->name('preview.print');

        // PDF du lot (par batch)
        Route::get('/preview/{batch}/pdf', [TicketController::class, 'pdf'])
    ->name('preview.pdf');
        // Voir un ticket
        Route::get('/{ticket}', [TicketController::class, 'show'])
            ->name('show');

        // Suppression
        Route::delete('/{ticket}', [TicketController::class, 'destroy'])
            ->name('destroy');

    });






        /*
        |--------------------------------------------------------------------------
        | HOTSPOTS
        |--------------------------------------------------------------------------
        */

        Route::resource(
            'hotspots',
            HotspotController::class
        );


        Route::get(
            'hotspots/{hotspot}/forfaits',
            [HotspotController::class, 'forfaits']
        )->name('hotspots.forfaits');


        Route::put(
            'hotspots/{hotspot}/forfaits',
            [HotspotController::class, 'syncForfaits']
        )->name('hotspots.forfaits.update');





        /*
        |--------------------------------------------------------------------------
        | FORFAITS
        |--------------------------------------------------------------------------
        */

        Route::resource(
            'forfaits',
            ForfaitController::class
        );





        /*
        |--------------------------------------------------------------------------
        | UTILISATEURS
        |--------------------------------------------------------------------------
        */

        Route::resource(
            'utilisateurs',
            UserController::class
        )->parameters([
            'utilisateurs' => 'utilisateur'
        ]);





        /*
        |--------------------------------------------------------------------------
        | ABONNEMENTS
        |--------------------------------------------------------------------------
        */

        Route::resource(
            'abonnements',
            AbonnementController::class
        );





        /*
        |--------------------------------------------------------------------------
        | PAIEMENTS
        |--------------------------------------------------------------------------
        */

        Route::resource(
            'paiements',
            PaiementController::class
        )->except([
            'edit',
            'update'
        ]);





        /*
        |--------------------------------------------------------------------------
        | STATISTIQUES
        |--------------------------------------------------------------------------
        */

        Route::get(
            'statistiques',
            [\App\Http\Controllers\StatistiqueController::class, 'index']
        )->name('statistiques.index');





        /*
        |--------------------------------------------------------------------------
        | CLIENTS
        |--------------------------------------------------------------------------
        */

        Route::get(
            'clients',
            [ClientController::class, 'index']
        )->name('clients.index');


        Route::get(
            'clients/{client}',
            [ClientController::class, 'show']
        )->name('clients.show');


    });






/*
|--------------------------------------------------------------------------
| PROFILE BREEZE
|--------------------------------------------------------------------------
*/

Route::middleware('auth')
    ->group(function () {

        Route::get('/profile',
            [ProfileController::class, 'edit']
        )->name('profile.edit');


        Route::patch('/profile',
            [ProfileController::class, 'update']
        )->name('profile.update');


        Route::delete('/profile',
            [ProfileController::class, 'destroy']
        )->name('profile.destroy');

    });



require __DIR__.'/auth.php';