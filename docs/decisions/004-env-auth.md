---
title: Env-Based Authentication
category: decision
updated: 2026-06-02
tags: [auth, security, env]
---

# ADR-004: Env-Based Authentication

## Status

Accepted

## Context

Invoicer is deployed on Oracle Cloud and accessible over the network. Without authentication, anyone with the URL can access the application and create/view invoices. Unauthorized access must be prevented.

## Decision

Implement authentication using credentials stored in environment variables (e.g. `APP_USER`, `APP_PASSWORD`). No database-backed user system — consistent with the [no-database decision](001-no-database.md).

## Consequences

- Simple to configure per-environment via `.env`
- Single-user or shared-credential model (sufficient for a small workshop)
- No user management UI needed
- Credentials must be kept out of version control
- Session-based login gate protects all routes
