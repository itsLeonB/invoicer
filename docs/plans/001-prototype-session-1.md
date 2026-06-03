---
title: "Prototype Session 1"
category: plan
updated: 2026-05-29
tags: [prototype, invoice, pdf]
---

# Implementation Plan — Invoicer Prototype (Session 1)

## Problem Statement

Build the first working vertical slice of Invoicer: a form with a single customer name field that generates a PDF invoice.

## Requirements

- Laravel fullstack, Blade views only, no Node.js/npm
- No database — stateless form → PDF pipeline
- Single "customer name" field to start
- PDF output via `barryvdh/laravel-dompdf`

## Background

- Fresh Laravel 13 skeleton, Composer dependencies installed
- PHPUnit configured, ExampleTest passing
- No custom controllers, views, or routes yet
- `barryvdh/laravel-dompdf` not yet installed

## Solution

Two tasks delivering incremental working functionality: first an HTML response from the form, then PDF generation.

```
Form View --POST--> InvoiceController --validate--> Validated Data --render--> Invoice Blade Template --DomPDF--> PDF Download
```

## Task 1: Minimal form with customer name field — HTML response

**Objective:** Create the invoice form with a single "customer name" field that submits and returns a simple HTML invoice page.

**Implementation:**
- Create `InvoiceController` with `create()` and `store()` methods
- Create `resources/views/invoices/create.blade.php` — form with one text input
- Create `resources/views/invoices/show.blade.php` — HTML page displaying "Invoice for: {name}"
- Routes: `GET /` → `create`, `POST /invoices` → `store`
- Validate customer name is required
- On validation failure, redirect back with errors

**Tests:**
- GET `/` returns 200 with form
- POST `/invoices` with valid name returns 200 with invoice view
- POST `/invoices` without name redirects back with validation error

## Task 2: PDF generation from the single-field form

**Objective:** Replace the HTML response with a PDF download using DomPDF.

**Implementation:**
- Install `barryvdh/laravel-dompdf`
- Create `resources/views/invoices/template.blade.php` — print-styled A4 layout with workshop header and customer name
- Update `InvoiceController@store` to render via DomPDF and stream the PDF
- Add `config/workshop.php` for workshop info

**Tests:**
- POST `/invoices` with valid name returns response with content-type `application/pdf`
