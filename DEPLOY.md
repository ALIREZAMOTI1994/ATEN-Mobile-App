# Deploying ATEN to Liara

This repo ships two Docker apps (`apps/api`, `apps/web`) plus a GitHub Actions
workflow (`.github/workflows/deploy-liara.yml`) that deploys both to
[Liara](https://liara.ir) automatically on every push to `main`. The workflow
runs on GitHub's own servers, so nothing needs to run from your machine.

Everything below is a one-time setup. After it's done, every push to `main`
redeploys the live site automatically.

## 1. Create a Liara account and two apps

1. Sign up / log in at [console.liara.ir](https://console.liara.ir).
2. Create two apps, both with **platform = Docker**:
   - one for the API, e.g. `aten-api`
   - one for the web frontend, e.g. `aten-web`
3. Note down the two app names — you'll need them below.

## 2. Create a MySQL and a Redis database

In the Liara console, create:
- a **MySQL** database
- a **Redis** database

Open each one's connection info and note the host, port, database name,
username and password (MySQL) — Liara gives internal hostnames that only
work from apps running on your Liara account, which is what we want.

## 3. Set environment variables on the API app

In the `aten-api` app's **Environment Variables** page in the Liara console,
add:

| Key | Value |
|---|---|
| `APP_NAME` | `ATEN Industrial Connections` |
| `APP_ENV` | `production` |
| `APP_DEBUG` | `false` |
| `APP_KEY` | generate one locally with `php artisan key:generate --show` and paste it here, or ask a developer to generate one |
| `APP_URL` | `https://aten-api.liara.run` (use your actual api app's URL) |
| `FRONTEND_URL` | `https://aten-web.liara.run` (use your actual web app's URL) |
| `CORS_ALLOWED_ORIGINS` | same as `FRONTEND_URL` |
| `DB_CONNECTION` | `mysql` |
| `DB_HOST` / `DB_PORT` / `DB_DATABASE` / `DB_USERNAME` / `DB_PASSWORD` | from your Liara MySQL database's connection info |
| `REDIS_CLIENT` | `predis` |
| `REDIS_HOST` / `REDIS_PORT` / `REDIS_PASSWORD` | from your Liara Redis database's connection info |
| `SESSION_DRIVER` / `CACHE_STORE` / `QUEUE_CONNECTION` | `redis` |
| `SANCTUM_STATEFUL_DOMAINS` | your web app's domain, no `https://` (e.g. `aten-web.liara.run`) |

## 4. Get a Liara API token

In the Liara console: **Account → API Tokens → Create a new token**. Copy it —
you won't be able to see it again.

## 5. Add secrets and variables to GitHub

In this repo on GitHub: **Settings → Secrets and variables → Actions**.

Under **Secrets**, add:
- `LIARA_API_TOKEN` — the token from step 4

Under **Variables**, add:
- `LIARA_API_APP` — the API app's name from step 1 (e.g. `aten-api`)
- `LIARA_WEB_APP` — the web app's name from step 1 (e.g. `aten-web`)
- `LIARA_API_PUBLIC_URL` — the API app's public URL (e.g. `https://aten-api.liara.run`) — this gets baked into the frontend build so it knows where to call the API

## 6. Deploy

Push to `main`, or open the **Actions** tab on GitHub and run the
**"Deploy to Liara"** workflow manually. It builds and deploys both apps.

The API container runs migrations and re-syncs the product catalog
automatically on every boot (safe to repeat — nothing gets duplicated), so
the first deploy is enough to get a fully working, fully stocked site with
no extra manual step.

## Notes

- Product photography is not stored in this repo (see `ProductSeeder`'s
  doc comment) — upload real photos through Laravel's filesystem/S3 disk
  once storage is wired up; until then the frontend shows a clean
  "image available on request" placeholder instead of a broken image.
- `docker-compose.yml` at the repo root runs the exact same images locally
  for development — useful for testing changes before they hit `main`.
