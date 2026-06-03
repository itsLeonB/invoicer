---
title: Tech Stack
category: architecture
updated: 2026-06-02
tags: [laravel, php, react, tailwind, docker, stack]
---

# Tech Stack

## Core

| Layer | Technology |
|-------|-----------|
| Language | PHP 8.x |
| Framework | Laravel |
| Frontend | React starter kit, TailwindCSS, shadcn/ui |
| PDF Generation | DomPDF (via `barryvdh/laravel-dompdf`) or Snappy |
| Server | PHP-FPM (Docker) |
| Object Storage | Oracle Object Storage (S3-compatible API) |
| Deployment | Docker + docker-compose, Oracle Cloud free tier |

## External Services

- Oracle Object Storage (S3-compatible) — for auto-saved PDF invoices
- No SQL database
- No queue worker
- No mail server

## Laravel Configuration Notes

- `DB_CONNECTION` can be left unset or set to `null`
- Session driver: `file` or `array`
- Cache driver: `array`
- Filesystem disk: `s3` (pointing to Oracle Object Storage)

## Related Pages

- [No Database](no-database.md)
- [Deployment](deployment.md)
