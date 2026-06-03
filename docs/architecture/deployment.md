---
title: Deployment
category: architecture
updated: 2026-06-02
tags: [deployment, oracle-cloud, docker, php-fpm]
---

# Deployment

## Target Environment

Oracle Cloud free tier.

> ⚠️ This supersedes the previous local-only Windows deployment model. See [ADR-003](../decisions/003-oracle-cloud.md).

## Container Setup

- **Dockerfile** with PHP-FPM
- **docker-compose** for orchestration

## Object Storage

Auto-saved PDF invoices are stored in Oracle Object Storage using the S3-compatible API. Laravel's Storage facade (`s3` driver) is used for read/write operations.

## Previous Setup (Deprecated)

Previously deployed locally on Windows via `php artisan serve` with auto-start and `invoicer.local` domain aliasing. This is no longer the target.

## Related Pages

- [Stack](stack.md)
- [Decision: Oracle Cloud](../decisions/003-oracle-cloud.md)
- [Feature: PDF Output](../features/pdf-output.md)
- [Feature: Invoice Listing](../features/invoice-listing.md)
