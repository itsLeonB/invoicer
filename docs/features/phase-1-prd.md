---
title: "Phase 1 — Product Requirements Document"
category: feature
updated: 2026-06-02
tags: [prd, phase-1, handoff, customer]
---

# Invoicer — Phase 1 Product Requirements

## What You're Getting

A cloud-hosted invoice generator for your workshop. Staff logs in, fills in a form on a web page, clicks submit, and gets a PDF invoice ready to print or share with the customer. All generated invoices are saved and listed for future reference.

## Features

### Login

- Protected by a username and password (configured by the developer)
- All pages require login — no public access
- Simple session-based gate; no user management needed

### Invoice Form

A single-page web form with the following sections:

| Section | Fields |
|---------|--------|
| Transaction date | Date of the transaction |
| Customer info | Name, mobile number |
| Line items | Description, quantity, unit price (physical items only — parts, supplies, etc.) |
| Summary | Total (auto-calculated, no tax/discounts) |
| Payment method | BCA or QRIS |

Workshop info (business name, logo, address, phone) is pre-configured — no need to type it every time.

### PDF Invoice Output

On form submission, the system generates a professional A4 PDF invoice containing:

- Your workshop header with business logo
- Auto-generated invoice number
- Transaction date
- Customer name and mobile number
- Itemized table of parts/supplies with quantities and prices
- Total amount
- Payment method

The PDF is automatically saved to cloud storage and displayed to the user for download/print.

### Invoice Listing

- An index page listing all previously generated invoices
- View or re-download any saved PDF

### Cloud Deployment

- Hosted on Oracle Cloud (free tier)
- Accessible from any device with the URL
- Runs in Docker (PHP-FPM + docker-compose)
- PDFs stored in Oracle Object Storage (S3-compatible)

## What It Does NOT Do

- No customer database — customer info is entered fresh each invoice
- No multi-user accounts — single shared login
- No service-type line items (labor, diagnostics) — physical items only
- No tax or discount calculations

## Technical Notes (for reference)

- Built with Laravel (PHP framework)
- React + TailwindCSS + shadcn/ui frontend
- PDF generated via DomPDF library
- No database — file/session-based storage
- Authentication via environment variables
