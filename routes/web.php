<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\AvisController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DisponibiliteController;


// Routes inscription
Route::get('/inscription/client',
    [App\Http\Controllers\InscriptionController::class, 'formClient']);
Route::post('/inscription/client',
    [App\Http\Controllers\InscriptionController::class, 'storeClient']);
Route::get('/inscription/prestataire',
    [App\Http\Controllers\InscriptionController::class, 'formPrestataire']);
Route::post('/inscription/prestataire',
    [App\Http\Controllers\InscriptionController::class, 'storePrestataire']);

// ===== PAGE D'ACCUEIL =====
Route::get('/', [ServiceController::class, 'index'])->name('home');

// ===== SERVICES =====
Route::get('/services', [ServiceController::class, 'liste'])->name('services');

// ===== AVIS =====
Route::get('/avis/{id}', [AvisController::class, 'index'])->name('avis');
Route::post('/avis/{id}', [AvisController::class, 'store'])->name('avis.store')->middleware('auth');

// ===== ROUTES PROTÉGÉES (connecté) =====
Route::middleware('auth')->group(function () {

    // Réservation
    Route::get('/reservation/{id}',  [ReservationController::class, 'index'])->name('reservation');
    Route::post('/reservation/{id}', [ReservationController::class, 'store'])->name('reservation.store');
    Route::post('/paiement',         [ReservationController::class, 'payer'])->name('paiement');
    Route::post('/reservation/annuler', [ReservationController::class, 'annuler'])->name('reservation.annuler');

    // Messagerie
    Route::get('/messagerie',        [MessageController::class, 'index'])->name('messagerie');
    Route::post('/messagerie',       [MessageController::class, 'store'])->name('message.store');

    // Dashboard Client
    Route::get('/dashboard/client',  [DashboardController::class, 'client'])->name('dashboard.client');

    // Dashboard Prestataire
    Route::get('/dashboard/prestataire', [DashboardController::class, 'prestataire'])->name('dashboard.prestataire');
    Route::post('/service/ajouter',      [ServiceController::class, 'store'])->name('service.store');
    Route::post('/service/supprimer',    [ServiceController::class, 'destroy'])->name('service.destroy');
    Route::get('/service/{id}/editer',   [ServiceController::class, 'editService'])->name('service.edit');
    Route::post('/service/{id}/mettre-a-jour', [ServiceController::class, 'updateService'])->name('service.update');
    Route::post('/disponibilite/ajouter',[DisponibiliteController::class, 'store'])->name('disponibilite.store');
    Route::post('/disponibilite/{id}/supprimer', [DisponibiliteController::class, 'destroy'])->name('disponibilite.destroy');
    Route::get('/disponibilite/{id}/editer', [DisponibiliteController::class, 'edit'])->name('disponibilite.edit');
    Route::post('/disponibilite/{id}/mettre-a-jour', [DisponibiliteController::class, 'update'])->name('disponibilite.update');
    Route::post('/reservation/confirmer',[ReservationController::class, 'confirmer'])->name('reservation.confirmer');
    Route::get('/profil/client/editer', [DashboardController::class, 'editClientProfile'])->name('profil.client.edit');
    Route::post('/profil/client/mettre-a-jour', [DashboardController::class, 'updateClientProfile'])->name('profil.client.update');
    Route::get('/profil/prestataire/editer', [DashboardController::class, 'editPrestataireProfile'])->name('profil.prestataire.edit');
    Route::post('/profil/prestataire/mettre-a-jour', [DashboardController::class, 'updatePrestataireProfile'])->name('profil.prestataire.update');
    // Redirect dashboard
Route::get('/dashboard', function () {
    if (auth()->user()->client) {
        return redirect('/dashboard/client');
    }
    return redirect('/dashboard/prestataire');
})->name('dashboard');
});

// ===== AUTHENTIFICATION (Breeze) =====
require __DIR__.'/auth.php';
// API pour la carte
Route::get('/api/prestataires', function() {
    return response()->json(\App\Models\Prestataire::all());
});
// Chatbot IA accessible sans authentification
Route::get('/chatbot', [App\Http\Controllers\ChatbotController::class, 'index'])->name('chatbot');
Route::post('/chatbot', [App\Http\Controllers\ChatbotController::class, 'ask'])->name('chatbot.ask');