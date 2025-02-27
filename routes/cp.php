<?php

use Illuminate\Support\Facades\Route;
use TheBiggerBoat\StatamicAdvancedEmails\AdvancedEmailsController;

Route::name('advanced-emails.')->prefix('advanced-emails')->group(function () {
    Route::get('/', [AdvancedEmailsController::class, 'index'])->name('index');

    Route::get('/create', [AdvancedEmailsController::class, 'create'])->name('create');
    Route::post('/create', [AdvancedEmailsController::class, 'store'])->name('store');

    Route::get('/edit/{id}', [AdvancedEmailsController::class, 'edit'])->name('edit');
    Route::post('/edit/{id}', [AdvancedEmailsController::class, 'update'])->name('update');

    Route::get('/delete/{id}', [AdvancedEmailsController::class, 'delete'])->name('destroy');
});