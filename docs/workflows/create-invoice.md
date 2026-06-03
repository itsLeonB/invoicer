---
title: Create Invoice Workflow
category: workflow
updated: 2026-05-29
tags: [workflow, user, invoice]
---

# Create Invoice — User Workflow

## Steps

1. **Open app** — Navigate to `http://localhost:8000`
2. **Fill form** — Enter customer name, vehicle info, add line items (services/parts)
3. **Review** — Check totals calculated in real-time (JS) or on submit
4. **Submit** — Click "Generate Invoice"
5. **Download** — PDF opens in browser or downloads automatically

## Error Handling

- Validation errors redirect back to form with error messages and old input preserved
- Missing required fields highlighted

## Time to Complete

Under 2 minutes for a typical invoice with 3–5 line items.

## Related Pages

- [Invoice Form](../features/invoice-form.md)
- [Invoice Generation](../features/invoice-generation.md)
