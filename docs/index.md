# Wiki Index

## Overview

| Page | Summary |
|------|---------|
| [overview.md](overview.md) | High-level system summary |

## Architecture

| Page | Summary |
|------|---------|
| [architecture/stack.md](architecture/stack.md) | Tech stack and dependencies |
| [architecture/no-database.md](architecture/no-database.md) | Why no SQL DB and how state is handled |
| [architecture/deployment.md](architecture/deployment.md) | Oracle Cloud deployment with Docker |

## Features

| Page | Summary |
|------|---------|
| [features/invoice-generation.md](features/invoice-generation.md) | Core invoice generation from form input |
| [features/invoice-form.md](features/invoice-form.md) | Form fields and validation |
| [features/pdf-output.md](features/pdf-output.md) | PDF rendering, auto-save, and download |
| [features/invoice-listing.md](features/invoice-listing.md) | Index page listing saved invoices |
| [features/phase-1-prd.md](features/phase-1-prd.md) | Phase 1 customer handoff PRD |

## Workflows

| Page | Summary |
|------|---------|
| [workflows/create-invoice.md](workflows/create-invoice.md) | End-to-end user flow for creating an invoice |
| [workflows/local-setup.md](workflows/local-setup.md) | Developer setup instructions |

## Decisions

| Page | Summary |
|------|---------|
| [decisions/001-no-database.md](decisions/001-no-database.md) | Decision to operate without a SQL database |
| [decisions/002-local-only.md](decisions/002-local-only.md) | ~~Decision to deploy locally only~~ (deprecated) |
| [decisions/003-oracle-cloud.md](decisions/003-oracle-cloud.md) | Decision to deploy on Oracle Cloud free tier |
| [decisions/004-env-auth.md](decisions/004-env-auth.md) | Env-based authentication to prevent unauthorized access |
