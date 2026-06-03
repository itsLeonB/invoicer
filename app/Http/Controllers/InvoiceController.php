<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInvoiceRequest;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class InvoiceController extends Controller
{
    public function create(): Response
    {
        return Inertia::render('invoices/create');
    }

    public function preview(StoreInvoiceRequest $request): HttpResponse
    {
        $data = $request->validated();

        $total = collect($data['items'])->sum(fn (array $item) => $item['quantity'] * $item['unit_price']);

        $pdf = Pdf::loadView('pdf.invoice', [
            'date' => $data['date'],
            'customerName' => $data['customer_name'],
            'customerMobile' => $data['customer_mobile'],
            'items' => $data['items'],
            'total' => $total,
            'shop' => config('shop'),
        ]);

        return $pdf->stream('preview.pdf');
    }

    public function store(StoreInvoiceRequest $request): HttpResponse
    {
        $data = $request->validated();

        $total = collect($data['items'])->sum(fn (array $item) => $item['quantity'] * $item['unit_price']);

        $pdf = Pdf::loadView('pdf.invoice', [
            'date' => $data['date'],
            'customerName' => $data['customer_name'],
            'customerMobile' => $data['customer_mobile'],
            'items' => $data['items'],
            'total' => $total,
            'shop' => config('shop'),
        ]);

        $slug = Str::slug($data['customer_name']);
        $filename = "{$data['date']}_{$slug}_{$total}.pdf";

        Storage::makeDirectory('invoices');
        Storage::put("invoices/{$filename}", $pdf->output());

        return $pdf->stream($filename);
    }

    public function index(): Response
    {
        $files = Storage::files('invoices');

        $invoices = collect($files)
            ->filter(fn (string $path) => str_ends_with($path, '.pdf'))
            ->map(function (string $path) {
                $filename = basename($path, '.pdf');
                $parts = explode('_', $filename, 3);

                return [
                    'filename' => basename($path),
                    'date' => $parts[0] ?? '',
                    'customer_name' => Str::headline($parts[1] ?? ''),
                    'total' => (int) ($parts[2] ?? 0),
                ];
            })
            ->sortByDesc('date')
            ->values()
            ->all();

        return Inertia::render('invoices/index', [
            'invoices' => $invoices,
        ]);
    }

    public function download(string $filename): StreamedResponse
    {
        abort_unless(Storage::exists("invoices/{$filename}"), 404);

        return Storage::download("invoices/{$filename}");
    }
}
