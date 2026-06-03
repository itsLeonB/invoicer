<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class InvoiceTest extends TestCase
{
    protected function authenticate(): static
    {
        return $this->withSession(['simple_authenticated' => true]);
    }

    public function test_create_page_requires_auth(): void
    {
        $response = $this->get('/');

        $response->assertRedirect('/login');
    }

    public function test_create_page_is_accessible(): void
    {
        $response = $this->authenticate()->get('/');

        $response->assertStatus(200);
    }

    public function test_store_invoice_generates_and_streams_pdf(): void
    {
        Storage::fake('local');

        $this->travelTo(now()->setTime(10, 30, 45));

        $response = $this->authenticate()->postJson('/invoices', [
            'date' => '2026-06-03',
            'customer_name' => 'John Doe',
            'customer_mobile' => '081234567890',
            'items' => [
                ['description' => 'Brake Pad', 'quantity' => 2, 'unit_price' => 150000],
                ['description' => 'Oil Filter', 'quantity' => 1, 'unit_price' => 75000],
            ],
        ]);

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');

        Storage::assertExists('invoices/2026-06-03_john-doe_375000_103045.pdf');
    }

    public function test_preview_streams_pdf_without_saving(): void
    {
        Storage::fake('local');

        $response = $this->authenticate()->postJson('/invoices/preview', [
            'date' => '2026-06-03',
            'customer_name' => 'John Doe',
            'customer_mobile' => '081234567890',
            'items' => [
                ['description' => 'Brake Pad', 'quantity' => 2, 'unit_price' => 150000],
            ],
        ]);

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');

        $files = Storage::files('invoices');
        $this->assertEmpty($files);
    }

    public function test_store_invoice_validation(): void
    {
        $response = $this->authenticate()->post('/invoices', []);

        $response->assertSessionHasErrors(['date', 'customer_name', 'customer_mobile', 'items']);
    }

    public function test_store_invoice_requires_at_least_one_item(): void
    {
        $response = $this->authenticate()->post('/invoices', [
            'date' => '2026-06-03',
            'customer_name' => 'John Doe',
            'customer_mobile' => '081234567890',
            'items' => [],
        ]);

        $response->assertSessionHasErrors('items');
    }

    public function test_index_page_lists_invoices(): void
    {
        Storage::fake('local');
        Storage::put('invoices/2026-06-03_john-doe_375000_103045.pdf', 'fake pdf content');

        $response = $this->authenticate()->get('/invoices');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('invoices/index')
            ->has('invoices', 1)
            ->where('invoices.0.filename', '2026-06-03_john-doe_375000_103045.pdf')
            ->where('invoices.0.date', '2026-06-03')
            ->where('invoices.0.customer_name', 'John Doe')
            ->where('invoices.0.total', 375000)
        );
    }

    public function test_download_invoice(): void
    {
        Storage::fake('local');
        Storage::put('invoices/2026-06-03_john-doe_375000_103045.pdf', 'fake pdf content');

        $response = $this->authenticate()->get('/invoices/2026-06-03_john-doe_375000_103045.pdf/download');

        $response->assertStatus(200);
        $response->assertDownload('2026-06-03_john-doe_375000_103045.pdf');
    }

    public function test_download_non_existent_invoice_returns_404(): void
    {
        Storage::fake('local');

        $response = $this->authenticate()->get('/invoices/nonexistent.pdf/download');

        $response->assertStatus(404);
    }

    public function test_show_invoice_inline(): void
    {
        Storage::fake('local');
        Storage::put('invoices/2026-06-03_john-doe_375000_103045.pdf', 'fake pdf content');

        $response = $this->authenticate()->get('/invoices/2026-06-03_john-doe_375000_103045.pdf/view');

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }

    public function test_show_non_existent_invoice_returns_404(): void
    {
        Storage::fake('local');

        $response = $this->authenticate()->get('/invoices/nonexistent.pdf/view');

        $response->assertStatus(404);
    }

    public function test_destroy_invoice(): void
    {
        Storage::fake('local');
        Storage::put('invoices/2026-06-03_john-doe_375000_103045.pdf', 'fake pdf content');

        $response = $this->authenticate()->delete('/invoices/2026-06-03_john-doe_375000_103045.pdf');

        $response->assertRedirect('/invoices');
        Storage::assertMissing('invoices/2026-06-03_john-doe_375000_103045.pdf');
    }

    public function test_destroy_non_existent_invoice_returns_404(): void
    {
        Storage::fake('local');

        $response = $this->authenticate()->delete('/invoices/nonexistent.pdf');

        $response->assertStatus(404);
    }

    public function test_path_traversal_is_blocked(): void
    {
        Storage::fake('local');

        $response = $this->authenticate()->get('/invoices/..%2F..%2F.env/download');

        $response->assertStatus(404);
    }
}
