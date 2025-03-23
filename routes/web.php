<?php

use App\Http\Controllers\InvoiceController;
use Illuminate\Support\Facades\Route;

Route::get('/invoice/preview/{transaction}', [InvoiceController::class, 'preview'])->name('invoice.preview');
