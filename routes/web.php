<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('monitors', 'monitors.index')->name('monitors.index');
    Route::view('monitors/create', 'monitors.create')->name('monitors.create');
    Route::view('monitors/{monitor}/edit', 'monitors.edit')->name('monitors.edit');
    
    Route::view('status-pages', 'status-pages.index')->name('status-pages.index');
    Route::view('status-pages/create', 'status-pages.create')->name('status-pages.create');
    
    Route::view('incidents', 'incidents.index')->name('incidents.index');
});

// Public status page routes
Route::get('status/{slug}', [\App\Http\Controllers\StatusPageController::class, 'show'])->name('status.show');

// Heartbeat webhook endpoint
Route::post('heartbeat/{monitor}', [\App\Http\Controllers\HeartbeatController::class, 'ping'])->name('heartbeat.ping');

require __DIR__.'/auth.php';
