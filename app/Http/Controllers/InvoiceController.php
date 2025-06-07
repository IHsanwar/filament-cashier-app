<?php
namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
class InvoiceController extends Controller
{
    public function show($id)
    {
        $transaction = Transaction::with('items.product')->findOrFail($id);
        return view('invoices.show', compact('transaction'));
    }
        
    public function download($id)
    {
        $transaction = Transaction::with('items.product')->findOrFail($id);

        $pdf = Pdf::loadView('invoices.show', compact('transaction'));

        return $pdf->download("Invoice-{$transaction->id}.pdf");
    }
}
