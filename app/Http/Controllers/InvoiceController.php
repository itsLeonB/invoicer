<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInvoiceRequest;
use Barryvdh\DomPDF\Facade\Pdf;
use Barryvdh\DomPDF\PDF as DomPDF;
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
        return $this->generatePdf($request->validated())->stream('preview.pdf');
    }

    public function store(StoreInvoiceRequest $request): HttpResponse
    {
        $data = $request->validated();
        $pdf = $this->generatePdf($data);

        $total = collect($data['items'])->sum(fn (array $item) => $item['quantity'] * $item['unit_price']);
        $slug = Str::slug($data['customer_name']);
        $timestamp = now()->format('His');
        $filename = "{$data['date']}_{$slug}_{$total}_{$timestamp}.pdf";

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
                $parts = explode('_', $filename, 4);

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
        $this->validateFilename($filename);

        return Storage::download("invoices/{$filename}");
    }

    public function show(string $filename): HttpResponse
    {
        $this->validateFilename($filename);

        return response(Storage::get("invoices/{$filename}"), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$filename.'"',
        ]);
    }

    public function destroy(string $filename)
    {
        $this->validateFilename($filename);

        Storage::delete("invoices/{$filename}");

        return redirect()->route('invoices.index');
    }

    private function generatePdf(array $data): DomPDF
    {
        $total = collect($data['items'])->sum(fn (array $item) => $item['quantity'] * $item['unit_price']);

        return Pdf::loadView('pdf.invoice', [
            'date' => $data['date'],
            'customerName' => $data['customer_name'],
            'customerMobile' => $data['customer_mobile'],
            'items' => $data['items'],
            'total' => $total,
            'shop' => config('shop'),
        ]);
    }

    private function validateFilename(string $filename): void
    {
        abort_unless(
            ! str_contains($filename, '/') && ! str_contains($filename, '..') && Storage::exists("invoices/{$filename}"),
            404
        );
    }
}
