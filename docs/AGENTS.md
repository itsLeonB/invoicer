# System Documentation Wiki — Schema

This file defines how the LLM maintains this wiki. It is the configuration layer that turns the LLM into a disciplined documentation maintainer for the Invoicer system.

## System Identity

**Invoicer** — A Laravel fullstack web application that automatically generates invoices from form input. Deployed locally. No database (file/session-based storage).

## Wiki Structure

```
docs/
├── SCHEMA.md          ← this file (conventions, workflows)
├── index.md           ← catalog of all wiki pages
├── log.md             ← chronological record of changes
├── overview.md        ← high-level system summary
├── architecture/      ← system design and structure
├── features/          ← feature documentation
├── workflows/         ← user and developer workflows
└── decisions/         ← architectural decision records
```

## Conventions

### Page Format

Every wiki page uses this frontmatter:

```yaml
---
title: Page Title
category: architecture | feature | workflow | decision
updated: YYYY-MM-DD
tags: [relevant, tags]
---
```

### Cross-References

Use relative markdown links: `[Link Text](../category/page.md)`

### Naming

- Files: `kebab-case.md`
- Directories: lowercase single words

### Content Rules

- Write for a developer joining the project
- State facts, not aspirations — document what IS, not what SHOULD BE
- Flag contradictions or unknowns explicitly with `> ⚠️ ...`
- Keep pages focused — one concept per page

## Operations

### Ingest

When new system knowledge is added (a new feature, a config change, a decision):
1. Create or update relevant wiki pages
2. Update cross-references on affected pages
3. Update `index.md`
4. Append entry to `log.md`

### Query

When answering questions about the system:
1. Read `index.md` to find relevant pages
2. Read those pages
3. Synthesize answer with page references
4. If the answer reveals a gap, create a new page

### Lint

Periodically check for:
- Orphan pages (not linked from index or other pages)
- Stale information contradicted by code
- Missing pages for concepts referenced but not documented
- Broken cross-references
