---
title: "ADR-002: Local Only Deployment"
category: decision
updated: 2026-05-29
tags: [adr, deployment, local]
---

# ADR-002: Local Only Deployment

## Status

Deprecated — superseded by [ADR-003: Deploy to Oracle Cloud](003-oracle-cloud.md)

## Context

The store operates from a single location. Staff use one shared computer at the front desk. There is no need for remote access, multi-user concurrency, or cloud hosting.

## Decision

Deploy locally using `php artisan serve`. No web server, no HTTPS, no domain, no hosting provider.

## Consequences

**Positive:**
- No hosting cost
- No internet dependency
- No security surface (not exposed to the internet)
- Instant startup

**Negative:**
- Not accessible from other devices (unless on same LAN and bound to `0.0.0.0`)
- No automatic backups (irrelevant without a database)
- Not suitable if remote access is ever needed

## Related Pages

- [Architecture: Deployment](../architecture/deployment.md)
