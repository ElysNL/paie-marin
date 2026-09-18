# paie-marin — Agent Guide

Maritime payroll management system for AUXIMAD (Madagascar). Laravel 13 API + Vue 3 SPA.

## Quick commands

```sh
composer setup          # install + .env + key + migrate + npm install + build
composer test           # clear config cache → php artisan test
composer dev            # artisan dev (serves API + Vite)
npm run dev             # Vite only (HMR on :5173)
php artisan test        # run all tests (excludes SmokePgsqlTest)
php artisan test --filter CalculateurDePaieTest   # single test class
vendor/bin/pint         # code style fix (Laravel default preset)
vendor/bin/phpstan analyse   # static analysis level 5 on app/
```

## Stack

- **PHP 8.3+** / Laravel 13 / Sanctum 4 (session cookies, not tokens)
- **PostgreSQL 17** production (`docker-compose.yml` exposes 5432)
- **SQLite in-memory** for tests (phpunit.xml overrides DB_CONNECTION)
- **Vue 3** + Pinia + Vue Router + Tailwind CSS 4 + Vite 8
- **PHPUnit 12.5**, **Pint** (formatter), **PHPStan level 5** (Larastan)

## Architecture

### Backend (app/)

- `Http/Controllers/Api/V1/` — all API controllers under `/api/v1/` prefix
- `Services/CalculateurDePaie.php` — core payroll calculation engine (IGR tax, cotisations, advances, delegations, exchange rates)
- `Jobs/CalculerPaieJob.php` — async payroll calculation (queue, timeout 300s, single try)
- `Policies/` — authorization policies for every resource
- `Models/` — Eloquent models; French domain terms: `Paie`, `BulletinPaie`, `AffectationMarin`, `Armateur`, `Navire`
- `Exports/BulletinExport.php` — Excel export via maatwebsite/excel

### Frontend (resources/js/)

- `stores/` — one Pinia store per entity (e.g., `paieStore.js`, `employeStore.js`)
- `services/api.js` — Axios client at `/api/v1`, handles 401/423/403 globally
- `router/index.js` — all routes defined here; role-based guards via `meta.roles`
- `components/` — organized by entity: `Paies/List.vue`, `Paies/Form.vue`, etc.

### Key routes (routes/api.php)

All protected routes require `auth` middleware (Sanctum session). Login is `POST /api/v1/auth/login` with rate limit `5,1`.

Payroll workflow endpoints on Paie:
- `POST paies/{id}/calculer` — dispatches CalculerPaieJob (returns 202)
- `GET paies/{id}/statut-calcul` — poll job status
- `POST paies/{id}/valider` / `POST paies/{id}/cloturer` — state transitions

### Config

- `config/paie.php` — payroll-specific env vars: `PAIE_DEVISE_PAIEMENT` (MGA), `PAIE_ABATTEMENT_PAR_CHARGE` (2000), `PAIE_PRIME_NAVIGATION` (5000)

## Testing

### General

Tests use **SQLite in-memory** with RefreshDatabase. No external services needed.

```sh
php artisan test --filter=Unit           # unit tests only
php artisan test --filter=Feature/Api    # API feature tests
```

### SmokePgsqlTest

`tests/Feature/Api/SmokePgsqlTest.php` is **excluded from the default test suite** (phpunit.xml). It tests against a **real PostgreSQL** database. Requirements:

- PostgreSQL running (e.g., `docker compose up -d`)
- `.env` configured with `DB_CONNECTION=pgsql` and valid credentials
- Seed the database first: `php artisan db:seed`
- The test uses `Origin: http://127.0.0.1:8000` header to activate Sanctum's stateful stack

### Seeders for payroll tests

Unit tests for `CalculateurDePaie` require these seeders (seeded in `setUp()`):

- `ElemPaieSeeder` — pay element codes: SAL_BASE, PRIME_NAVIGATION, IGR, DELEGATION, etc.
- `CotisationSeeder` — CNAPS (1% sal / 5% pat), SMIDS (1.5% sal / 3.5% pat)
- `IgrParametreSeeder` — progressive IGR tax brackets (0%–20%)

### Auth in feature tests

Feature tests use Sanctum's `actingAs()` or simulate the browser flow with XSRF-TOKEN cookie. See `SmokePgsqlTest` for the full cookie-based flow.

## Gotchas

- **Sanctum uses session cookies**, not bearer tokens. The API middleware stack includes `EnsureFrontendRequestsAreStateful` + custom `ValidateSession` (checks locked accounts and password changes).
- **Payroll calculation is async**: `POST paies/{id}/calculer` dispatches a job. Poll `GET paies/{id}/statut-calcul` for results.
- **SQLite in tests differs from PostgreSQL**: some PostgreSQL-specific features (enums, ILIKE, etc.) won't work in tests. SmokePgsqlTest exists to verify PG-specific behavior.
- **French domain terminology**: models and columns use French names. `bulletin` = payslip, `affectation` = crew assignment, `armateur` = shipowner, `cotisation` = social contribution.
- **Domain knowledge is in `planAgent/`**: development plans and technical notes for each feature area. Consult `planAgent/INDEX.md` for an overview.
- **DB schema reference**: `database/schema/pgsql-schema.sql` is a PG dump serving as the canonical schema reference.

## Project structure notes

- No `.github/` CI workflows — tests are run locally
- `database/schema/pgsql-schema.sql` — canonical PostgreSQL schema dump (useful for understanding table structures)
- `planAgent/` — development plans, technical specs (gitignored, local reference)
- `.npmrc` has `ignore-scripts=true` — npm scripts won't run automatically during install
