# KhonYab - Blood Donation Management System

KhonYab is a multilingual blood donation platform built with Laravel 12. It connects donors, receivers, and laboratories, and gives administrators a full dashboard to manage donations, inventory, blood requests, users, reports, and site settings.

After login, `/dashboard` sends each account to the correct area:

| Role | Dashboard |
|------|-----------|
| Admin | `/admin/dashboard` |
| Donor | `/donor/dashboard` |
| Receiver | `/receiver/dashboard` |
| Laboratory | `/laboratory/dashboard` |

## Features

### Public site
- Home, about, and contact pages
- Contact form (submissions appear in the admin contact-message inbox)
- Public search of pending, approved, and completed blood requests (filter by blood type, province, and city)
- Registration as a **donor**, **receiver**, or **laboratory**
- Language switcher: English (`en`), Persian (`fa`), and Pashto (`ps`), including RTL layouts

### Donor dashboard
- Personal donation statistics, eligibility, and next eligible donation date
- Create, view, update, and cancel donation records
- Personal donation reports (whole blood, plasma, platelets)
- Profile and messaging

### Receiver dashboard
- Blood request statistics (pending, approved, completed, rejected)
- Create, edit, cancel, and print blood requests
- Profile and messaging

### Laboratory dashboard
- Record and manage blood donations (including printable receipts)
- Record and update blood tests for donations
- Create and manage blood requests, with printable receipts
- Download receipts and update laboratory profile
- Messaging

### Admin dashboard
- Overview statistics and live notifications (pending blood requests and unread contact messages)
- User management (including toggling admin access)
- Donor, receiver, and laboratory management (health status, donation ability, laboratory verification)
- Blood donation records and blood tests
- Blood inventory (in stock, used, expired, discarded)
- Blood request approval, rejection, and completion
- Province and city management
- Languages and translations (import from language files, set default, toggle active)
- Reports with Excel export (donations, requests, inventory, users, summary, active donors, shortage alerts, approved requests, donation history, by province, monthly/yearly, bag expiration)
- Contact message inbox
- Database backups (create, download, clean old backups)
- Site settings
- Messaging with other users

### Other capabilities
- Role-based access control (`admin` middleware for the admin area)
- In-app messaging between users
- Queued email verification and password-reset messages (registration does not wait on SMTP)
- Donation interval and bag expiration rules from site settings
- Dark mode on public and authenticated pages

## Technology stack

- **PHP** 8.2+ (developed on 8.4)
- **Laravel** 12
- **MySQL**
- **Laravel Breeze** (authentication, registration, password reset, email verification)
- **Tailwind CSS** 3 and **Alpine.js** 3
- **Vite** 7
- **Maatwebsite Excel** (report exports)
- **PHPUnit** 11 and **Laravel Pint**

## Requirements

- PHP >= 8.2 with common Laravel extensions (OpenSSL, PDO, Mbstring, Tokenizer, XML, Ctype, JSON, BCMath)
- Composer
- Node.js and npm
- MySQL 8+
- PHP built-in server (`php artisan serve`) or Apache/Nginx

## Installation

### 1. Clone the repository

```bash
git clone https://github.com/ironman0019/khon_yab.git
cd khon_yab
```

### 2. Install PHP dependencies

```bash
composer install
```

### 3. Install Node dependencies

```bash
npm install
```

### 4. Create the environment file and application key

Linux / macOS:

```bash
cp .env.example .env
php artisan key:generate
```

Windows (PowerShell):

```powershell
Copy-Item .env.example .env
php artisan key:generate
```

### 5. Configure the database

Create an empty MySQL database, then edit `.env`:

```env
APP_NAME=KhonYab
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=khonyab
DB_USERNAME=root
DB_PASSWORD=
```

Use your own MySQL username and password.

Queues use the database driver by default (`QUEUE_CONNECTION=database`). Mail can stay on the `log` driver for local development (`MAIL_MAILER=log`). In that mode emails are written to `storage/logs/laravel.log` instead of being sent, so you do not need SMTP or a queue worker to register.

To actually send mail (Gmail or another SMTP server), see [Email and queues](#email-and-queues).

### 6. Run migrations and seeders

```bash
php artisan migrate --seed
```

This creates the tables and loads demo data: languages, translations, Iranian provinces and cities, users (see [Demo accounts](#demo-accounts)), extra donor/receiver/laboratory profiles, donation records, blood tests, inventory, blood requests, settings, backups, and messages.

To reset and reseed later:

```bash
php artisan migrate:fresh --seed
```

### 7. Build frontend assets

```bash
npm run build
```

### 8. Start the application

```bash
php artisan serve
```

Open [http://127.0.0.1:8000](http://127.0.0.1:8000) and sign in at [http://127.0.0.1:8000/login](http://127.0.0.1:8000/login).

For local development with the PHP server, queue worker, and Vite hot reload together:

```bash
composer run dev
```

That starts `php artisan serve`, `php artisan queue:listen`, and `npm run dev`. The queue listener is required when `MAIL_MAILER` is `smtp`, because verification and password-reset emails are queued.

If the UI does not update after a frontend change, run `npm run dev` or `npm run build` (or `composer run dev`).

## Quick setup script

```bash
composer run setup
```

This installs Composer and npm dependencies, copies `.env.example` if `.env` is missing, generates the app key, runs migrations, and builds assets.

It does **not** seed the database. After setup, configure `.env` and run:

```bash
php artisan db:seed
```

## Email and queues

Registration and “forgot password” enqueue mail jobs instead of talking to SMTP in the HTTP request. The user is created (or the reset is accepted) immediately. A queue worker then sends the message in the background.

`.env.example` already has `QUEUE_CONNECTION=database`. After `php artisan migrate`, the `jobs` and `failed_jobs` tables exist.

### Local development

Keep the default mailer for a first run:

```env
MAIL_MAILER=log
QUEUE_CONNECTION=database
```

Use `composer run dev` so a queue worker is running. If you only run `php artisan serve`, queued mail will sit in the `jobs` table until you start a worker:

```bash
php artisan queue:work
```

### Sending real email (Gmail)

Use an [App Password](https://support.google.com/accounts/answer/185833), not your normal Gmail password. SMTPS on port 465 is the supported setup:

```env
MAIL_MAILER=smtp
MAIL_SCHEME=smtps
MAIL_HOST=smtp.gmail.com
MAIL_PORT=465
MAIL_USERNAME=you@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=ssl
MAIL_TIMEOUT=15
MAIL_SOURCE_IP=0.0.0.0
MAIL_FROM_ADDRESS=you@gmail.com
MAIL_FROM_NAME="${APP_NAME}"
```

`MAIL_SOURCE_IP=0.0.0.0` forces IPv4. Some hosts resolve `smtp.gmail.com` to IPv6 first; that path can hang until timeout and leave a failed job.

After changing mail settings in production, rebuild the config cache and restart the worker:

```bash
php artisan config:cache
php artisan queue:restart
```

Retry a failed mail job with `php artisan queue:retry all`, or inspect `failed_jobs`.

### Production: keep the worker running

A cron that runs once a minute is not enough. The worker must run continuously (systemd or Supervisor). Example systemd unit:

```ini
[Unit]
Description=Laravel queue worker
After=network.target mysql.service

[Service]
Type=simple
User=www-data
Group=www-data
Restart=always
RestartSec=5
WorkingDirectory=/path/to/khon_yab
ExecStart=/usr/bin/php artisan queue:work database --sleep=3 --tries=3 --timeout=90 --max-time=3600 --max-jobs=500

[Install]
WantedBy=multi-user.target
```

Adjust `User`, `WorkingDirectory`, and the PHP binary to match the server. Enable it with `systemctl enable --now your-queue.service`.

Also add the Laravel scheduler cron (prunes old failed jobs daily):

```cron
* * * * * cd /path/to/khon_yab && php artisan schedule:run >> /dev/null 2>&1
```

After deploying PHP changes, run `php artisan queue:restart` so the worker reloads the new code.

### HTTPS behind a reverse proxy

`bootstrap/app.php` trusts proxy headers (`X-Forwarded-*`) so signed verification links stay on `https://` when nginx (or a CDN) terminates TLS. That setting is harmless on `php artisan serve` with no proxy.

Set `APP_URL` to the public URL (`https://your-domain` in production, `http://127.0.0.1:8000` locally). When `APP_URL` starts with `https://`, the app also forces the HTTPS scheme for generated links.

## Demo accounts

All seeded users from `UserSeeder` share the same password and have a verified email, so you can sign in immediately.

**Password for every account below:** `password`

Use these accounts to try each dashboard. After login you are redirected from `/dashboard` to the matching area.

### Admins (`/admin/dashboard`)

Full access to user, donor, receiver, laboratory, donation, inventory, request, report, language, backup, contact-message, and settings pages.

| Name | Email | Password |
|------|-------|----------|
| Admin User | `admin@khonyab.ir` | `password` |
| مدیر سیستم | `admin-fa@khonyab.ir` | `password` |
| د سیسټم مدیر | `admin-ps@khonyab.ir` | `password` |

Recommended starting account: **`admin@khonyab.ir` / `password`**.

### Receivers (`/receiver/dashboard`)

Blood request list, create/edit/print requests, profile, and messages.

| Name | Email | Password |
|------|-------|----------|
| John Smith | `user1@example.com` | `password` |
| احمد محمدی | `user2@example.com` | `password` |
| احمد خان | `user3@example.com` | `password` |
| Receiver User | `receiver@khonyab.ir` | `password` |
| کاربر گیرنده | `receiver-fa@khonyab.ir` | `password` |
| د ترلاسه کوونکي کارن | `receiver-ps@khonyab.ir` | `password` |

Recommended starting account: **`receiver@khonyab.ir` / `password`**.

### Donors (`/donor/dashboard`)

Donation records, eligibility, personal reports, profile, and messages.

| Name | Email | Password |
|------|-------|----------|
| Donor User | `donor@khonyab.ir` | `password` |
| کاربر اهداکننده | `donor-fa@khonyab.ir` | `password` |
| د ورکوونکي کارن | `donor-ps@khonyab.ir` | `password` |

Recommended starting account: **`donor@khonyab.ir` / `password`**.

### Laboratories (`/laboratory/dashboard`)

Donation recording, blood tests, blood requests, receipts, profile, and messages.

| Name | Email | Password |
|------|-------|----------|
| Laboratory User | `laboratory@khonyab.ir` | `password` |
| کاربر آزمایشگاه | `laboratory-fa@khonyab.ir` | `password` |
| د لابراتوار کارن | `laboratory-ps@khonyab.ir` | `password` |

Recommended starting account: **`laboratory@khonyab.ir` / `password`**.

The `-fa` and `-ps` accounts are the same roles with Persian and Pashto display names, useful for checking translations and RTL layouts.

Other seeders also create extra donors, receivers, laboratories, donations, tests, inventory, requests, and messages so lists and reports are not empty.

## Typical testing paths

1. **Admin** — sign in as `admin@khonyab.ir`, open `/admin/dashboard`, then try user management, blood requests (approve/reject/complete), inventory, reports (and Excel export), contact messages, and settings.
2. **Donor** — sign in as `donor@khonyab.ir`, open `/donor/dashboard`, add or review donation records, and check `/donor/reports`.
3. **Receiver** — sign in as `receiver@khonyab.ir`, open `/receiver/dashboard`, create a blood request, then approve it from the admin account.
4. **Laboratory** — sign in as `laboratory@khonyab.ir`, record a donation, add a blood test, and create a blood request.
5. **Public site** — visit `/`, `/about`, `/contact`, and `/search` without logging in. Submit the contact form and read it in the admin contact-message inbox.
6. **Messaging** — from any role dashboard, open Messages and start a conversation with another seeded user.

## Development

### Tests

```bash
php artisan test
php artisan test tests/Feature/ExampleTest.php
php artisan test --filter=testName
```

### Code formatting

```bash
vendor/bin/pint --dirty
```

### Frontend

```bash
npm run dev
npm run build
```

## Project structure

```
app/
├── Enums/              # UserType, BloodRequestStatus, DonationRecordStatus, BloodInventoryStatus
├── Exports/            # Excel export classes
├── Http/
│   ├── Controllers/    # Home, Auth, Admin, Donor, Receiver, Laboratory
│   ├── Middleware/     # Custom middleware (admin)
│   └── Requests/       # Form request validation
├── Models/             # Eloquent models
├── Notifications/      # Queued VerifyEmail and ResetPassword notifications
├── Services/           # Business logic (for example admin dashboard statistics)
└── View/               # Blade components

database/
├── factories/
├── migrations/
└── seeders/            # Includes UserSeeder with the demo accounts above

resources/
├── css/
├── js/
└── views/              # Public, auth, admin, donor, receiver, laboratory Blade views

routes/
├── auth.php
├── console.php
└── web.php

lang/                   # Translation files (en, fa, ps)
```

## Key Features Explained

### Blood Donation Tracking
- Tracks donation type (Whole Blood, Plasma, or Platelets)
- Calculates donor eligibility from last donation date and type-specific intervals (defaults: 56 days for whole blood, 28 for plasma, 7 for platelets; configurable in site settings)
- Records donation location (province and city)
- Links each donation to a laboratory, blood test, and inventory bag
- Donation statuses: test pending, safe, unsafe, discarded
- Donors can submit and cancel their own records; laboratories and admins can record donations and print receipts

### Blood Testing
- Laboratories and admins attach a test result to a donation
- Safe results can move the unit into inventory; unsafe or discarded results keep the bag out of stock
- Test records can be created and updated from the donation record screens

### Inventory Management
- Tracks blood bags by blood type, Rh factor, donation type, and status
- Statuses: in stock, used, expired, discarded
- Expiration is based on donation type (defaults: 42 days for whole blood, 365 for plasma, 5 for platelets)
- Admins can mark bags as used or expired
- Dashboard and reports surface low-stock and expired-bag alerts

### Blood Request Workflow
- Receivers and laboratories create requests with patient details, blood type, bag count, medical center, and location
- Request statuses: pending, approved, rejected, completed
- Admins approve, reject, or complete requests
- Approved and completed requests can be printed as receipts
- The public `/search` page lists pending, approved, and completed requests and can filter by blood type, province, and city

### Reporting System
Available admin reports (all support Excel export):
- Donations
- Blood requests
- Inventory status
- User statistics
- Summary
- Active donors
- Shortage alerts
- Approved requests
- Donation history
- Reports by province
- Monthly / yearly statistics
- Bag expiration

Donors also have a personal donation report on `/donor/reports`.

### Multi-language Support
- Dynamic language switching (English, Persian, Pashto)
- RTL layout for Persian and Pashto
- Admin language and translation management
- Set a default language, toggle languages active/inactive
- Import translations from language files

### Messaging
- In-app conversations between admins, donors, receivers, and laboratories
- Unread counts and conversation lists on each role dashboard

### Contact Messages
- Public contact form on `/contact`
- Submissions appear in the admin contact-message inbox
- Admins can read, mark unread, and delete messages; unread contacts also show in admin notifications

## Security

- Authentication via Laravel Breeze (login, registration, password reset, email verification)
- Verification and password-reset emails are queued so a slow SMTP server cannot time out the register request
- Admin routes protected by `auth`, `verified`, and `admin` middleware
- Form request validation
- CSRF protection
- Database access through Eloquent (parameter binding)
- Trusted proxies when the app runs behind nginx or a CDN (needed for correct HTTPS links)

Change the seeded passwords before using this project outside local development.

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
