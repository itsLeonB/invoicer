---
title: "ADR-003: Deploy to Oracle Cloud"
category: decision
updated: 2026-06-02
tags: [adr, deployment, oracle-cloud, docker]
---

# ADR-003: Deploy to Oracle Cloud

## Status

Accepted

## Context

The previous decision ([ADR-002](002-local-only.md)) constrained the app to local-only deployment on Windows. Requirements have evolved — the offline-only constraint is no longer necessary.

## Decision

Deploy to **Oracle Cloud free tier** using Docker (PHP-FPM + docker-compose). Use Oracle Object Storage (S3-compatible API) for persisting auto-saved PDF invoices.

## Consequences

**Positive:**
- Free hosting via Oracle Cloud free tier
- Accessible from any device (no longer tied to a single machine)
- PDF persistence via object storage — enables invoice listing/history
- Standard containerized deployment (portable)

**Negative:**
- Internet dependency
- Requires Oracle Cloud account management
- Slightly more complex setup than `php artisan serve`

## Supersedes

- [ADR-002: Local Only Deployment](002-local-only.md) — now deprecated

## Related Pages

- [Architecture: Deployment](../architecture/deployment.md)
- [Architecture: Stack](../architecture/stack.md)
