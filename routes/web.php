<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\OpportunityController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::resource('leads', LeadController::class);
    Route::post('/leads/{lead}/convert', [LeadController::class, 'convert'])->name('leads.convert');

    Route::resource('customers', CustomerController::class);
    Route::post('/customers/{customer}/contacts', [CustomerController::class, 'storeContact'])->name('customers.contacts.store');
    Route::delete('/customers/{customer}/contacts/{contact}', [CustomerController::class, 'destroyContact'])->name('customers.contacts.destroy');

    Route::get('/pipeline', [OpportunityController::class, 'pipeline'])->name('pipeline');
    Route::patch('/opportunities/{opportunity}/stage', [OpportunityController::class, 'updateStage'])->name('opportunities.stage');
    Route::resource('opportunities', OpportunityController::class);

    Route::patch('/activities/{activity}/complete', [ActivityController::class, 'complete'])->name('activities.complete');
    Route::resource('activities', ActivityController::class);

    Route::middleware('role:admin,manager')->group(function () {
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/export/{type}', [ReportController::class, 'export'])->name('reports.export');
    });

    Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {
        Route::resource('users', UserController::class);
        Route::resource('settings', SettingController::class)->except(['show']);
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
