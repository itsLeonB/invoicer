<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Blade;

class InvoiceController
{
    public function preview(Transaction $transaction)
    {
        $pdf = Pdf::loadHtml(
            Blade::render('invoice', ['transaction' => $transaction])
        );

        return response($pdf->stream())
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="invoice-'.$transaction->id.'.pdf"');
    }
}
