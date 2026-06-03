<?php

use App\Http\Controllers\Auth\SimpleLoginController;
use App\Http\Controllers\InvoiceController;
use App\Http\Middleware\SimpleAuth;
use Illuminate\Support\Facades\Route;

Route::get('login', [SimpleLoginController::class, 'showLogin'])->name('login');
Route::post('login', [SimpleLoginController::class, 'login'])->name('login.store');
Route::post('logout', [SimpleLoginController::class, 'logout'])->name('logout');

Route::middleware(SimpleAuth::class)->group(function () {
    Route::get('/', [InvoiceController::class, 'create'])->name('invoices.create');
    Route::post('/invoices/preview', [InvoiceController::class, 'preview'])->name('invoices.preview');
    Route::post('/invoices', [InvoiceController::class, 'store'])->name('invoices.store');
    Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');
    Route::get('/invoices/{filename}/download', [InvoiceController::class, 'download'])->name('invoices.download')->where('filename', '[a-zA-Z0-9_\-\.]+');
    Route::get('/invoices/{filename}/view', [InvoiceController::class, 'show'])->name('invoices.show')->where('filename', '[a-zA-Z0-9_\-\.]+');
    Route::delete('/invoices/{filename}', [InvoiceController::class, 'destroy'])->name('invoices.destroy')->where('filename', '[a-zA-Z0-9_\-\.]+');
});

require __DIR__.'/settings.php';
