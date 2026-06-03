---
title: Invoice Form
category: feature
updated: 2026-06-02
tags: [form, input, validation]
---

# Invoice Form

## Fields

### Business Info (the workshop itself, pre-filled or configurable)

| Field | Type | Notes |
|-------|------|-------|
| Workshop name | text | Set in config/env |
| Address | text | |
| Phone | text | |
| Logo | image | Displayed in invoice header |

### Transaction Date

| Field | Type | Required |
|-------|------|----------|
| Date | date | yes |

### Customer Info

| Field | Type | Required |
|-------|------|----------|
| Customer name | text | yes |
| Mobile number | text | yes |

### Line Items (repeatable)

Line items can only be **physical items** (supplies, auto parts, etc.) — no service-type line items.

| Field | Type | Required |
|-------|------|----------|
| Description | text | yes |
| Quantity | number | yes |
| Unit price | number | yes |

### Transaction Summary

Totals only — no tax, no discounts.

### Payment Method

| Option | Notes |
|--------|-------|
| BCA | Bank transfer |
| QRIS | QR code payment |

Only these two options for now.

### Additional



## Validation Rules

- Customer name: required, string, max 255
- Mobile number: required, string
- At least one line item
- Quantity: required, numeric, min 1
- Unit price: required, numeric, min 0

## Related Pages

- [Invoice Generation](invoice-generation.md)
- [Workflow: Create Invoice](../workflows/create-invoice.md)
