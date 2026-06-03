---
title: Invoice Generation
category: feature
updated: 2026-05-29
tags: [invoice, core, pdf]
---

# Invoice Generation

## Summary

The core feature: user fills a form with job details, submits it, and receives a formatted invoice as PDF.

## Flow

1. User navigates to invoice form
2. Fills in customer, vehicle, and service/parts details
3. Submits the form
4. Server validates input, renders invoice view, converts to PDF
5. PDF is streamed to browser (download or inline view)

## Data Transformation

```
Form Input (Request) → Validated Data → Blade View (invoice template) → PDF
```

No data is stored. The entire pipeline is stateless within a single HTTP request-response cycle.

## Invoice Content

A generated invoice includes:
- Workshop header (name, address, phone, logo)
- Invoice number (auto-generated, e.g., timestamp-based)
- Date
- Customer details
- Vehicle details
- Line items (services + parts with quantities and prices)
- Subtotal, tax, total

## Related Pages

- [Invoice Form](invoice-form.md)
- [PDF Output](pdf-output.md)
- [Workflow: Create Invoice](../workflows/create-invoice.md)
