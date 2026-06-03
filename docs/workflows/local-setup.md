---
title: Local Setup
category: workflow
updated: 2026-05-29
tags: [setup, developer, local]
---

# Local Setup — Developer Workflow

## Prerequisites

- PHP >= 8.1
- Composer
- No Node.js / npm required

## Steps

```bash
git clone <repo-url> invoicer
cd invoicer
composer install
cp .env.example .env
php artisan key:generate
php artisan serve
```

## Environment Notes

In `.env`:
```
APP_URL=http://localhost:8000
DB_CONNECTION=null
SESSION_DRIVER=file
CACHE_DRIVER=array
```

No database setup, no migrations needed.

## Related Pages

- [Deployment](../architecture/deployment.md)
- [Stack](../architecture/stack.md)
