<?php

use App\Http\Controllers\Api\CompanyFormationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

/*
|--------------------------------------------------------------------------
| Company Formation API Routes
|--------------------------------------------------------------------------
*/

// Health check
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now(),
        'version' => '1.0.0',
        'laravel' => app()->version(),
        'php' => PHP_VERSION
    ]);
})->name('health');

Route::prefix('v1')->group(function () {
    
    // Company Formation Routes
    Route::prefix('company-formation')->group(function () {
        // Submit complete application
        Route::post('/', [CompanyFormationController::class, 'store'])
            ->name('company-formation.store');
        
        // Auto-save form data
        Route::post('/auto-save', [CompanyFormationController::class, 'autoSave'])
            ->name('company-formation.auto-save');
        
        // Get saved form data
        Route::get('/form-data', [CompanyFormationController::class, 'getFormData'])
            ->name('company-formation.form-data');
        
        // Get application status by reference number
        Route::get('/status/{referenceNumber}', [CompanyFormationController::class, 'getStatus'])
            ->name('company-formation.status');
    });
});

