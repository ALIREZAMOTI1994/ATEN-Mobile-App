# ATEN Industrial Connections

Enterprise B2B Progressive Web App for ATEN Industrial Connections — an industrial hose,
fitting and connection sourcing platform. Product discovery and RFQ (Request for Quotation)
instead of checkout, per the platform's non-negotiable business rules (see `CLAUDE.md`).

## Stack

- **Frontend** — `apps/web`: Next.js (App Router) + TypeScript + Tailwind CSS, installable PWA
  with an offline-capable service worker.
- **Backend** — `apps/api`: Laravel REST API (`/api/v1`), MySQL, Redis (cache/queue/session),
  Sanctum token authentication.
- **Deployment** — each app is a single self-contained Docker image (the API image bundles
  nginx + PHP-FPM via supervisord); `docker-compose.yml` runs both plus MySQL and Redis for
  local/self-hosted use. See [`DEPLOY.md`](./DEPLOY.md) for deploying to Liara with automatic
  deploys on every push via GitHub Actions.

## Project structure

```
apps/
  api/    Laravel REST API — products, categories, industries, RFQs, auth, contact
  web/    Next.js PWA — catalog, product detail, RFQ cart & submission, i18n (en/fa)
docs/     Original architecture, PRD and master prompt planning documents
docker-compose.yml
```

## Local development (without Docker)

Backend:

```bash
cd apps/api
cp .env.example .env
composer install
php artisan key:generate
# point DB_* / REDIS_* in .env at a local MySQL + Redis, then:
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

Frontend:

```bash
cd apps/web
cp .env.example .env.local   # NEXT_PUBLIC_API_URL should point at the API above
npm install
npm run dev
```

## Running the full stack with Docker Compose

```bash
cp .env.example .env            # set DB_PASSWORD / DB_ROOT_PASSWORD
cp apps/api/.env.example apps/api/.env
docker compose up --build -d
docker compose exec api php artisan key:generate
docker compose exec api php artisan storage:link
```

Migrations and the product catalog seed run automatically every time the `api` container
starts (the seeders are idempotent, so this never duplicates data).

- Web: http://localhost:3000
- API: http://localhost:8080/api/v1
- MySQL: localhost:3306, Redis: internal only

## Production deployment

See [`DEPLOY.md`](./DEPLOY.md) for a step-by-step guide to deploying both apps to Liara with
zero manual steps after the one-time setup — every push to `main` redeploys automatically via
GitHub Actions.

## Data

The product catalog (10 categories, ~52 products with real specifications, applications and
industries) is seeded from `apps/api/database/seeders/`, ported from the ATEN 2025 printed
catalog. Product photography is not committed to the repository — upload real photos through
the API's storage disk (`php artisan storage:link`, or S3 in production per `docs/SYSTEM_ARCHITECTURE.md`)
under `products/{slug}/`; until then, the frontend shows a clearly labelled placeholder.

## What's implemented vs. next steps

Implemented: product catalog + filtering/search, product detail with QR code, RFQ cart and
submission (with email confirmation), RFQ tracking, contact form, customer auth (register/login),
PWA install + offline app shell, Docker Compose deployment.

Not yet built: an admin panel for managing products/RFQs (the `Rfq`/`Product` models and API are
ready for one), Meilisearch-backed search, Arabic locale (English/Persian are implemented), and
automated tests.
