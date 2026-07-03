# ElectroMart — Electronics & Hardware Store

A full-featured Laravel e-commerce application for selling electronic items (bulbs, capacitors, wiring, pipes, bolts, nuts, and more).

## Tech Stack

| Layer | Technology |
|-------|------------|
| Backend | Laravel 12, PHP 8.2+ |
| Frontend | Blade, Tailwind CSS, Alpine.js, Vite |
| Database | SQLite (dev) / MySQL (production) |
| Auth | Laravel Breeze + Spatie Permission |
| Payments | Stripe (PaymentIntents) + Cash on Delivery |
| PDF | DomPDF (invoices) |

## Features

### Customer Storefront
- Home, About Us, Contact Us
- Product catalog with search, filters, sorting, pagination
- Product detail with specs and related items
- Shopping cart (guest + logged-in, merges on login)
- Checkout with COD and Stripe card payment
- User registration, login, profile, addresses, order history
- Invoice PDF download

### Admin Panel (`/admin`)
- Dashboard with revenue, orders, low-stock alerts
- Product CRUD with image upload
- Category CRUD (nested)
- Order management with status updates and invoices
- Customer list
- Contact message inbox
- CMS page editor (Home/About content)
- Shop settings (name, tax, shipping, contact info)

## Design Theme

- **Teal** primary — clean and trustworthy
- **Amber** accent — clear call-to-action buttons
- Subtle fade-in animations, card hover effects, smooth transitions

## Quick Start

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed
php artisan storage:link
npm install
npm run build
php artisan serve
```

Visit: http://127.0.0.1:8000

## Demo Accounts

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@electromart.local | password |
| Customer | customer@electromart.local | password |

## Environment Variables

```env
STRIPE_KEY=pk_test_...
STRIPE_SECRET=sk_test_...
STRIPE_WEBHOOK_SECRET=whsec_...

SHOP_NAME=ElectroMart
SHOP_CURRENCY=PKR
SHOP_SHIPPING_FLAT=250
```

## Running Tests

```bash
php artisan test
```

## Project Structure

```
app/
├── Enums/              OrderStatus, PaymentMethod, PaymentStatus
├── Http/Controllers/
│   ├── Admin/          Full admin CRUD
│   ├── Account/        Addresses, order history
│   ├── Storefront/     Customer-facing pages
│   └── Webhook/        Stripe webhooks
├── Models/             Product, Order, Category, etc.
├── Services/           Cart, Checkout, Order, Payment, Inventory
└── Mail/               OrderPlaced, ContactFormSubmitted
routes/
├── web.php             Storefront + account routes
└── admin.php           Admin panel routes
```

## Production Checklist

- Set `APP_ENV=production`, `APP_DEBUG=false`
- Configure MySQL database
- Set Stripe live keys and webhook endpoint: `POST /webhooks/stripe`
- Configure SMTP for order/contact emails
- Run `php artisan config:cache` and `php artisan route:cache`
- Set up queue worker for mail: `php artisan queue:work`
- Enable HTTPS
