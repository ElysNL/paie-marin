<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\{
    AuthController,
    DashboardController,
    PaysController,
    DeviseController,
    FonctionController,
    ClassificationController,
    CompagnieController,
    ArmateurController,
    NavireController,
    ContratArmateurController,
    EmployeController,
    AffectationController,
    PaieController,
    BulletinController,
    AvanceController,
    DelegationController,
    BanqueController,
    UserController
};

Route::prefix('v1')->group(function () {
    Route::post('auth/login', [AuthController::class, 'login'])
        ->middleware('throttle:5,1');

    Route::middleware('auth')->group(function () {
        // Tous les rôles authentifiés
        Route::get('auth/me', [AuthController::class, 'me']);
        Route::post('auth/logout', [AuthController::class, 'logout']);
        Route::get('dashboard', [DashboardController::class, 'index']);

        // admin, rh
        Route::middleware('role:admin,rh')->group(function () {
            Route::apiResource('pays', PaysController::class);
            Route::apiResource('devises', DeviseController::class);
            Route::apiResource('fonctions', FonctionController::class);
            Route::apiResource('classifications', ClassificationController::class);
            Route::apiResource('compagnies', CompagnieController::class);
            Route::apiResource('banques', BanqueController::class);
            Route::apiResource('armateurs', ArmateurController::class);
            Route::apiResource('navires', NavireController::class);
            Route::apiResource('contrats-armateur', ContratArmateurController::class);
            Route::apiResource('employes', EmployeController::class);
        });

        // admin, rh, paie
        Route::middleware('role:admin,rh,paie')->group(function () {
            Route::apiResource('affectations', AffectationController::class);
        });

        // admin, paie
        Route::middleware('role:admin,paie')->group(function () {
            Route::apiResource('paies', PaieController::class);
            Route::apiResource('bulletins', BulletinController::class)->only(['index', 'show', 'destroy']);
            Route::apiResource('avances', AvanceController::class);
            Route::apiResource('delegations', DelegationController::class);

            Route::post('paies/{paie}/calculer', [PaieController::class, 'calculer']);
            Route::get('paies/{paie}/statut-calcul', [PaieController::class, 'statutCalcul']);
            Route::get('paies/{paie}/navires-eligibles', [PaieController::class, 'naviresEligibles']);
            Route::post('paies/{paie}/valider', [PaieController::class, 'valider']);
            Route::post('paies/{paie}/cloturer', [PaieController::class, 'cloturer']);
            Route::get('paies/{paie}/eligibles', [PaieController::class, 'eligibles']);
        });

        // admin
        Route::middleware('role:admin')->group(function () {
            Route::apiResource('users', UserController::class)->except(['edit', 'create']);
            Route::post('users/{user}/toggle-actif', [UserController::class, 'toggleActif']);
        });

        Route::get('bulletins/{bulletin}/pdf', [BulletinController::class, 'exportPdf']);
        Route::get('bulletins/{bulletin}/excel', [BulletinController::class, 'exportExcel']);
    });
});
