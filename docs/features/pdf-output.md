---
title: PDF Output
category: feature
updated: 2026-06-02
tags: [pdf, dompdf, output, storage]
---

# PDF Output

## Library

`barryvdh/laravel-dompdf` — wraps DomPDF for Laravel with a clean API.

## How It Works

```php
use Barryvdh\DomPDF\Facade\Pdf;

$pdf = Pdf::loadView('invoices.template', $data);
return $pdf->download("invoice-{$invoiceNumber}.pdf");
```

## Auto-Save Behavior

On generate, the PDF is automatically saved to Object Storage (Oracle, S3-compatible API) via Laravel's Storage facade. After saving, the stored file path/URL is displayed to the user.

## Invoice Template

A Blade view (`resources/views/invoices/template.blade.php`) styled for print/PDF:
- A4 paper size
- **Business logo** in header
- Workshop branding at top
- Structured table for line items
- Totals section (totals only — no tax, no discounts)
- Payment method (BCA / QRIS)

## PDF Settings

| Setting | Value |
|---------|-------|
| Paper size | A4 |
| Orientation | Portrait |
| Font | Sans-serif (system or embedded) |

## Related Pages

- [Invoice Generation](invoice-generation.md)
- [Invoice Listing](invoice-listing.md)
- [Stack](../architecture/stack.md)
- [Deployment](../architecture/deployment.md)
