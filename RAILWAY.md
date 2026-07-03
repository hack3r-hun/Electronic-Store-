# Deploy ElectroMart on Railway

This guide deploys the **full Laravel app** (storefront + admin + API) on [Railway](https://railway.app).

## What you need

- GitHub account
- [Railway](https://railway.app) account
- ~10 minutes

## 1. Push code to GitHub

```bash
git init
git add .
git commit -m "Prepare ElectroMart for Railway"
git branch -M main
git remote add origin https://github.com/YOUR_USERNAME/electromart.git
git push -u origin main
```

## 2. Create Railway project

1. Go to [railway.app](https://railway.app) → **New Project**
2. Choose **Deploy from GitHub repo**
3. Select your repository
4. Railway detects the `Dockerfile` automatically

## 3. Add MySQL database

1. In your project → **+ New** → **Database** → **MySQL**
2. Wait until MySQL is running
3. Open your **web service** → **Variables**
4. Add these (use Railway variable references):

| Variable | Value |
|----------|-------|
| `DB_CONNECTION` | `mysql` |
| `DB_HOST` | `${{MySQL.MYSQLHOST}}` |
| `DB_PORT` | `${{MySQL.MYSQLPORT}}` |
| `DB_DATABASE` | `${{MySQL.MYSQLDATABASE}}` |
| `DB_USERNAME` | `${{MySQL.MYSQLUSER}}` |
| `DB_PASSWORD` | `${{MySQL.MYSQLPASSWORD}}` |

> Replace `MySQL` with your database service name if different.

## 4. Required environment variables

Generate an app key locally:

```bash
php artisan key:generate --show
```

Copy the `base64:...` value.

| Variable | Value |
|----------|-------|
| `APP_NAME` | `ElectroMart` |
| `APP_ENV` | `production` |
| `APP_KEY` | `base64:...` (from command above) |
| `APP_DEBUG` | `false` |
| `APP_URL` | Your Railway URL (e.g. `https://electromart-production.up.railway.app`) |
| `LOG_CHANNEL` | `stderr` |
| `SESSION_DRIVER` | `database` |
| `CACHE_STORE` | `database` |
| `FILESYSTEM_DISK` | `public` |
| `RUN_SEEDER` | `true` (first deploy only) |

After first successful deploy, set `RUN_SEEDER` to `false`.

## 5. Optional — email (OTP verification)

| Variable | Example |
|----------|---------|
| `MAIL_MAILER` | `smtp` |
| `MAIL_HOST` | `smtp.mailtrap.io` |
| `MAIL_PORT` | `587` |
| `MAIL_USERNAME` | your SMTP user |
| `MAIL_PASSWORD` | your SMTP password |
| `MAIL_ENCRYPTION` | `tls` |
| `MAIL_FROM_ADDRESS` | `noreply@yourdomain.com` |

## 6. Optional — Stripe payments

| Variable | Value |
|----------|-------|
| `STRIPE_KEY` | `pk_live_...` |
| `STRIPE_SECRET` | `sk_live_...` |
| `STRIPE_WEBHOOK_SECRET` | `whsec_...` |

Webhook URL: `https://YOUR-APP.up.railway.app/webhooks/stripe`

## 7. Deploy

Railway builds the Docker image and runs:

- `php artisan migrate --force`
- `php artisan db:seed --force` (if `RUN_SEEDER=true`)
- `php artisan serve` on Railway's `PORT`

Health check: `/up`

## 8. Custom domain (optional)

1. Railway project → **Settings** → **Networking** → **Generate Domain** (free `.up.railway.app`)
2. Or add your own domain under **Custom Domain**
3. Update `APP_URL` to match

## Demo logins (after seeding)

| Role | Email | Password |
|------|-------|----------|
| Admin | `admin@electromart.local` | `password` |
| Customer | `customer@electromart.local` | `password` |

## Product images

Uploaded images are stored on the server disk. On Railway, add a **Volume** mounted to `/var/www/html/storage/app/public` if you need uploads to survive redeploys.

For production at scale, use S3-compatible storage (`AWS_*` variables).

## Troubleshooting

| Issue | Fix |
|-------|-----|
| 500 error | Check **Deploy Logs**; ensure `APP_KEY` and DB vars are set |
| CSS/JS missing | Rebuild — assets are compiled in Docker during deploy |
| Database error | Verify MySQL plugin is linked and `DB_*` variables reference it |
| OTP email not sent | Configure `MAIL_*` variables |

## Redeploy

Push to GitHub `main` branch — Railway redeploys automatically.
