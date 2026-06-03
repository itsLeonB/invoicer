---
title: "ADR-001: No Database"
category: decision
updated: 2026-05-29
tags: [adr, database, architecture]
---

# ADR-001: No Database

## Status

Accepted

## Context

The application generates invoices from form input. The workshop does not need to look up past invoices digitally — they keep paper copies. The goal is simplicity: minimal infrastructure, instant setup, zero maintenance.

## Decision

No database. Invoice data lives only in the HTTP request and is transformed directly into a PDF response. Nothing is persisted.

## Consequences

**Positive:**
- Zero setup friction (no DB server, no migrations)
- No data maintenance burden
- Application is stateless and trivially restartable

**Negative:**
- No invoice history or search
- No customer auto-complete from past entries
- Adding persistence later requires introducing a DB layer

## Related Pages

- [Architecture: No Database](../architecture/no-database.md)
