<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\EntryController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\KitController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();

Route::get('/', function () {
    return view('welcome');
});

// Ensure all routes below require authentication
Route::middleware(['auth'])->group(function () {

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::view('/dashboard', 'admin.dashboard')->name('dashboard');
    Route::view('/tickets', 'admin.ticket')->name('tickets');

    // Reports routes
    Route::prefix('reports')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('reports.index');
        Route::post('/generate', [ReportController::class, 'generate'])->name('reports.generate');
        Route::post('/export', [ReportController::class, 'export'])->name('reports.export');
    });

    // Form routes
    Route::get('/form', [FormController::class, 'index'])->name('form.index');
    Route::get('/get-sites', [FormController::class, 'getSites'])->name('getSites');
    Route::get('/get-kits', [FormController::class, 'getKits'])->name('getKits');
    Route::post('/form-save', [FormController::class, 'saveForm'])->name('form.save');

    // Clients routes
    Route::prefix('clients')->group(function () {
        Route::get('/', [ClientController::class, 'index'])->name('clients.index');
        Route::get('create', [ClientController::class, 'create'])->name('clients.create');
        Route::post('store', [ClientController::class, 'store'])->name('clients.store');
        Route::get('{client}', [ClientController::class, 'show'])->name('clients.show');
        Route::get('{client}/edit', [ClientController::class, 'edit'])->name('clients.edit');
        Route::put('{client}', [ClientController::class, 'update'])->name('clients.update');
        Route::delete('{client}', [ClientController::class, 'destroy'])->name('clients.destroy');
    });

    // Sites routes
    Route::prefix('sites')->group(function () {
        Route::get('/', [SiteController::class, 'index'])->name('sites.index');
        Route::get('create', [SiteController::class, 'create'])->name('sites.create');
        Route::post('store', [SiteController::class, 'store'])->name('sites.store');
        Route::get('{site}', [SiteController::class, 'show'])->name('sites.show');
        Route::get('{site}/edit', [SiteController::class, 'edit'])->name('sites.edit');
        Route::put('{site}', [SiteController::class, 'update'])->name('sites.update');
        Route::delete('{site}', [SiteController::class, 'destroy'])->name('sites.destroy');
    });

    // Kits routes
    Route::prefix('kits')->group(function () {
        Route::get('/', [KitController::class, 'index'])->name('kits.index');
        Route::get('create', [KitController::class, 'create'])->name('kits.create');
        Route::post('store', [KitController::class, 'store'])->name('kits.store');
        Route::get('{kit}', [KitController::class, 'show'])->name('kits.show');
        Route::get('{kit}/edit', [KitController::class, 'edit'])->name('kits.edit');
        Route::put('{kit}', [KitController::class, 'update'])->name('kits.update');
        Route::delete('{kit}', [KitController::class, 'destroy'])->name('kits.destroy');
        Route::get('generate-number', [KitController::class, 'generateKitNumber'])->name('kits.generateNumber');
    });

    // Tickets routes
    Route::prefix('tickets')->group(function () {
        Route::get('/', [TicketController::class, 'index'])->name('tickets.index');
        Route::get('create', [TicketController::class, 'create'])->name('tickets.create');
        Route::post('store', [TicketController::class, 'store'])->name('tickets.store');
        Route::get('{ticket}', [TicketController::class, 'show'])->name('tickets.show');
        Route::get('{ticket}/edit', [TicketController::class, 'edit'])->name('tickets.edit');
        Route::put('update/{ticket}', [TicketController::class, 'update'])->name('tickets.update');
        Route::delete('{ticket}', [TicketController::class, 'destroy'])->name('tickets.destroy');
        Route::put('/admin/{ticket}/close', [TicketController::class, 'close'])->name('tickets.close');
        Route::put('/admin/{ticket}/reopen', [TicketController::class, 'reopen'])->name('tickets.reopen');
    });

    // Entries routes (commented out, uncomment if needed)
    // Route::prefix('entries')->group(function () {
    //     Route::get('/', [EntryController::class, 'index'])->name('entries.index');
    //     Route::get('create', [EntryController::class, 'create'])->name('entries.create');
    //     Route::post('store', [EntryController::class, 'store'])->name('entries.store');
    //     Route::get('{entry}', [EntryController::class, 'show'])->name('entries.show');
    //     Route::get('{entry}/edit', [EntryController::class, 'edit'])->name('entries.edit');
    //     Route::put('{entry}', [EntryController::class, 'update'])->name('entries.update');
    //     Route::delete('{entry}', [EntryController::class, 'destroy'])->name('entries.destroy');
    // });
});
