---
title: Invoicer — System Overview
category: overview
updated: 2026-06-02
tags: [system, overview, invoice]
---

# Invoicer

An invoice generation web application.

## What It Does

A user fills in a form with transaction details (customer info, line items for items) and the system generates a formatted invoice — viewable on screen and downloadable as PDF. Generated invoices are auto-saved to object storage and listed on an index page.

## Key Characteristics

- **Framework**: Laravel (with React starter kit frontend)
- **Database**: None (SQL). Invoices stored as PDF files via Laravel Storage facade (S3-compatible object storage)
- **Deployment**: Oracle Cloud free tier, Docker (PHP-FPM), object storage for PDFs
- **Users**: User (single-user, no auth required)
- **Output**: PDF invoice (auto-saved + displayed)

## System Boundaries

- No customer database — customer info is entered per invoice
- No authentication — trusted environment
- No SQL database — file-based storage only

## Related Pages

- [Architecture → Stack](architecture/stack.md)
- [Architecture → No Database](architecture/no-database.md)
- [Architecture → Deployment](architecture/deployment.md)
- [Features → Invoice Generation](features/invoice-generation.md)
- [Features → Invoice Listing](features/invoice-listing.md)
- [Workflows → Create Invoice](workflows/create-invoice.md)
