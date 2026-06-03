---
title: No Database Architecture
category: architecture
updated: 2026-05-29
tags: [database, stateless, architecture]
---

# No Database Architecture

## Rationale

The system generates invoices from form input without persisting data. Each invoice is a stateless transformation: form data → formatted PDF. No history, no lookups, no relational queries needed.

## How State Is Handled

| Concern | Approach |
|---------|----------|
| Form data | Lives in the HTTP request only |
| Invoice rendering | Generated in-memory from request data |
| PDF output | Streamed or downloaded directly — not stored on disk |
| Session | File-based (Laravel default), used only for flash messages/CSRF |

## Implications

- No migrations, no seeders
- No Eloquent models (or models used only as data transfer objects)
- `config/database.php` connections are unused
- Application boots faster without DB connection overhead

## Future Considerations

> ⚠️ If invoice history or customer lookup is needed later, a database (SQLite recommended for local-only) would be the natural addition. This would require migrations, models, and a rethink of the stateless flow.

## Related Pages

- [Decision Record: No Database](../decisions/001-no-database.md)
- [Stack](stack.md)
