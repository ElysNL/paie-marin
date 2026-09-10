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
    BanqueController
};

Route::prefix('v1')->group(function () {
    // Authentification
    Route::post('auth/login', [AuthController::class, 'login']);
    Route::get('auth/me', [AuthController::class, 'me'])->middleware('auth');
    Route::post('auth/logout', [AuthController::class, 'logout'])->middleware('auth');

    // Tableau de bord (lecture seule)
    Route::get('dashboard', [DashboardController::class, 'index'])->middleware('auth');

    // Référentiels
    Route::apiResource('pays', PaysController::class);
    Route::apiResource('devises', DeviseController::class);
    Route::apiResource('fonctions', FonctionController::class);
    Route::apiResource('classifications', ClassificationController::class);
    Route::apiResource('compagnies', CompagnieController::class);
    Route::apiResource('banques', BanqueController::class);

    // Entités principales
    Route::apiResource('armateurs', ArmateurController::class);
    Route::apiResource('navires', NavireController::class);
    Route::apiResource('contrats-armateur', ContratArmateurController::class);
    Route::apiResource('employes', EmployeController::class);
    Route::apiResource('affectations', AffectationController::class);

    // Paie
    Route::apiResource('paies', PaieController::class);
    Route::apiResource('bulletins', BulletinController::class)->only(['index', 'show', 'destroy']);
    Route::apiResource('avances', AvanceController::class);
    Route::apiResource('delegations', DelegationController::class);

    // Actions personnalisées
    Route::post('paies/{paie}/calculer', [PaieController::class, 'calculer']);
    Route::post('paies/{paie}/valider', [PaieController::class, 'valider']);
    Route::post('paies/{paie}/cloturer', [PaieController::class, 'cloturer']);
    Route::get('paies/{paie}/eligibles', [PaieController::class, 'eligibles']);

    // Exports
    Route::get('bulletins/{bulletin}/pdf', [BulletinController::class, 'exportPdf']);
    Route::get('bulletins/{bulletin}/excel', [BulletinController::class, 'exportExcel']);
});
