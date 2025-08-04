<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LegalCaseController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Legal Cases Routes with Role-based Middleware
    Route::get('/legal-cases', [LegalCaseController::class, 'index'])->name('legal-cases.index');
    Route::get('/legal-cases/create', [LegalCaseController::class, 'create'])
        ->middleware('role:admin,manager')->name('legal-cases.create');
    Route::post('/legal-cases', [LegalCaseController::class, 'store'])
        ->middleware('role:admin,manager')->name('legal-cases.store');
    Route::get('/legal-cases/{legalCase}', [LegalCaseController::class, 'show'])->name('legal-cases.show');
    Route::get('/legal-cases/{legalCase}/edit', [LegalCaseController::class, 'edit'])
        ->middleware('role:admin,manager')->name('legal-cases.edit');
    Route::put('/legal-cases/{legalCase}', [LegalCaseController::class, 'update'])
        ->middleware('role:admin,manager')->name('legal-cases.update');
    Route::delete('/legal-cases/{legalCase}', [LegalCaseController::class, 'destroy'])
        ->middleware('role:admin')->name('legal-cases.destroy');
    Route::patch('/legal-cases/{legalCase}/progress', [LegalCaseController::class, 'updateProgress'])
        ->name('legal-cases.update-progress');
    
    // Document Management Routes
    Route::post('/legal-cases/{legalCase}/documents', [\App\Http\Controllers\DocumentController::class, 'upload'])
        ->name('documents.upload');
    Route::get('/documents/{document}/download', [\App\Http\Controllers\DocumentController::class, 'download'])
        ->name('documents.download');
    Route::get('/documents/{document}/view', [\App\Http\Controllers\DocumentController::class, 'show'])
        ->name('documents.view');
    Route::delete('/documents/{document}', [\App\Http\Controllers\DocumentController::class, 'destroy'])
        ->middleware('role:admin,manager')
        ->name('documents.destroy');
    
    // Case Parties Routes
    Route::prefix('legal-cases/{legalCase}/parties')->group(function () {
        Route::get('/', [\App\Http\Controllers\CasePartyController::class, 'index'])
            ->name('case-parties.index');
        Route::get('/create', [\App\Http\Controllers\CasePartyController::class, 'create'])
            ->middleware('role:admin,manager')
            ->name('case-parties.create');
        Route::post('/', [\App\Http\Controllers\CasePartyController::class, 'store'])
            ->middleware('role:admin,manager')
            ->name('case-parties.store');
        Route::get('/{party}', [\App\Http\Controllers\CasePartyController::class, 'show'])
            ->name('case-parties.show');
        Route::get('/{party}/edit', [\App\Http\Controllers\CasePartyController::class, 'edit'])
            ->middleware('role:admin,manager')
            ->name('case-parties.edit');
        Route::put('/{party}', [\App\Http\Controllers\CasePartyController::class, 'update'])
            ->middleware('role:admin,manager')
            ->name('case-parties.update');
        Route::delete('/{party}', [\App\Http\Controllers\CasePartyController::class, 'destroy'])
            ->middleware('role:admin,manager')
            ->name('case-parties.destroy');
    });
    
    // Case Types Management Routes (Admin & Manager)
    Route::prefix('case-types')->group(function () {
        Route::get('/', [\App\Http\Controllers\CaseTypeController::class, 'index'])
            ->middleware('role:admin,manager')
            ->name('case-types.index');
        Route::get('/create', [\App\Http\Controllers\CaseTypeController::class, 'create'])
            ->middleware('role:admin')
            ->name('case-types.create');
        Route::post('/', [\App\Http\Controllers\CaseTypeController::class, 'store'])
            ->middleware('role:admin')
            ->name('case-types.store');
        Route::get('/{caseType}', [\App\Http\Controllers\CaseTypeController::class, 'show'])
            ->middleware('role:admin,manager')
            ->name('case-types.show');
        Route::get('/{caseType}/edit', [\App\Http\Controllers\CaseTypeController::class, 'edit'])
            ->middleware('role:admin')
            ->name('case-types.edit');
        Route::put('/{caseType}', [\App\Http\Controllers\CaseTypeController::class, 'update'])
            ->middleware('role:admin')
            ->name('case-types.update');
        Route::delete('/{caseType}', [\App\Http\Controllers\CaseTypeController::class, 'destroy'])
            ->middleware('role:admin')
            ->name('case-types.destroy');
        Route::patch('/{caseType}/toggle-status', [\App\Http\Controllers\CaseTypeController::class, 'toggleStatus'])
            ->middleware('role:admin')
            ->name('case-types.toggle-status');
    });
    
    // User Management Routes (Admin Only)
    Route::middleware('role:admin')->group(function () {
        Route::resource('users', \App\Http\Controllers\UserController::class);
        Route::patch('/users/{user}/role', [\App\Http\Controllers\UserController::class, 'updateRole'])
            ->name('users.update-role');
    });
});

require __DIR__.'/auth.php';
