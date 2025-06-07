<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransactionPrintController;
use App\Http\Controllers\InvoiceController;
Route::get('/transactions/{transaction}/print', [TransactionPrintController::class, 'print'])->name('transactions.print');
Route::get('/transactions/{transaction}/print/preview', [TransactionPrintController::class, 'preview'])->name('transactions.print.preview');
Route::get('/', function () {
    return redirect('/admin');
});
Route::get('/invoice/{id}', [InvoiceController::class, 'show'])->name('invoice.show');
Route::get('/invoice/{id}/download', [InvoiceController::class, 'download'])->name('invoice.download');

