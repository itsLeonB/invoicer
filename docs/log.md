# Wiki Log

## [2026-06-02] update | Phase 1 PRD refresh

Updated `features/phase-1-prd.md` to reflect current decisions: Oracle Cloud deployment (ADR-003), env-based auth (ADR-004), invoice listing, PDF auto-save, totals-only summary, BCA/QRIS payment methods, business logo, React/Tailwind frontend.

## [2026-06-02] ingest | ADR-004 env-based auth

Decision recorded: implement env-based authentication (credentials in `.env`, session-gated routes) to prevent unauthorized access. Created `decisions/004-env-auth.md`. Updated `index.md`.

## [2026-06-02] ingest | Daily notes 2026-06-02

Major direction change. Ingested notes covering:

- **Deployment pivot**: Moving from local-only Windows to Oracle Cloud free tier. Docker (PHP-FPM) + docker-compose. Created `decisions/003-oracle-cloud.md`, deprecated `decisions/002-local-only.md`, rewrote `architecture/deployment.md`.
- **Frontend pivot**: React starter kit, TailwindCSS, shadcn/ui replacing Blade-only frontend. Updated `architecture/stack.md`.
- **Invoice summary**: Totals only — no tax, no discounts. Updated `features/invoice-form.md`.
- **Payment methods**: BCA + QRIS only for now. Updated `features/invoice-form.md`.
- **Invoice header**: Business logo added. Updated `features/invoice-form.md`, `features/pdf-output.md`.
- **PDF auto-save**: On generate, PDF auto-saves to Oracle Object Storage (S3-compatible). Saved file displayed to user. Updated `features/pdf-output.md`.
- **Invoice listing**: New index page listing saved invoices via Storage facade. Created `features/invoice-listing.md`.
- Updated `overview.md`, `index.md`.

## [2026-05-29] ingest | Daily notes 2026-05-29

Ingested daily notes covering three topics:
- **Deployment**: Target is Windows, must auto-start with OS, use local domain aliasing (`invoicer.local`). Updated `architecture/deployment.md`.
- **Invoice form**: Simplified fields — removed vehicle info, line items restricted to physical items only, customer requires name + mobile number, added transaction date and payment method. Updated `features/invoice-form.md`, `overview.md`.
- **Dependencies**: No Node.js/npm — frontend uses Blade views only. Updated `architecture/stack.md`, `workflows/local-setup.md`.

## [2026-05-29] bootstrap | Wiki initialized

Created wiki structure for Invoicer system documentation. Initial pages: overview, architecture (stack, no-database, deployment), features (invoice-generation, invoice-form, pdf-output), workflows (create-invoice, local-setup), decisions (001-no-database, 002-local-only).

## 2026-05-29 — Phase 1 PRD created

- Created `features/phase-1-prd.md` — customer-facing handoff document summarizing all proposed Phase 1 features (invoice form, PDF output, local deployment)
- Updated `index.md`
