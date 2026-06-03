---
title: Invoice Listing
category: feature
updated: 2026-06-02
tags: [invoice, listing, storage]
---

# Invoice Listing

## Purpose

A new index page that lists previously generated invoices.

## Implementation

- Uses Laravel's **Storage facade** to list PDF files from Object Storage (S3-compatible)
- No SQL database — the file listing itself serves as the invoice history
- Each entry links to the stored PDF for viewing/downloading

## Related Pages

- [PDF Output](pdf-output.md)
- [Architecture → No Database](../architecture/no-database.md)
- [Architecture → Deployment](../architecture/deployment.md)
