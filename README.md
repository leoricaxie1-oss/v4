# PanipOne v3 — Barangay Management System

A modern, scalable, secure, responsive **Laravel** web application for the
day-to-day operations of **Barangay Panipuan**.

This repository contains the v3 rewrite that replaces the legacy procedural
PHP implementation under `../panipone_v2`. The application is built on
**Laravel 11**, **Tailwind CSS**, **Alpine.js** and **MySQL**.

## Modules

- Account registration with sequential multi-level approval
  (Secretary → Kagawad → Captain)
- Landing page with announcements, approved skills/services/businesses
- Resident dashboard: profile, document requests, complaints, blotter,
  appointments, messaging, notifications
- Skills & Services directory with provider profiles, inquiries, ratings
- Document request workflow (Barangay Clearance, Residency, Indigency,
  Business Clearance) with pickup scheduling
- Complaint & Blotter management with evidence uploads, mediation and
  hearing schedules, status tracking
- Role dashboards: Resident, Tanod, Secretary, Kagawad, Captain, Admin
- Analytics: population, demographics, requests, complaints, business stats
- Notifications: SMS, Email, in-system

## Architecture

- MVC (Laravel framework conventions)
- RESTful routing and resource controllers
- Middleware-protected routes (RBAC)
- Eloquent ORM with normalized schema (foreign keys, soft deletes,
  timestamps, indexes)
- Blade components for reusable UI
- Activity logging + audit trail
- CSRF, XSS, SQL-injection protection (Laravel built-ins)

## Getting started

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm install && npm run build
php artisan serve
```

## Running on XAMPP

The project is designed to also run under XAMPP's bundled Apache + MySQL
without any virtual-host configuration.

1. **Install PHP dependencies.** XAMPP does not ship Composer, so install
   it separately (https://getcomposer.org/) and run:

   ```bash
   composer install
   npm install && npm run build
   ```

2. **Drop the project into `htdocs/`.** Copy or clone this repository to
   `C:\xampp\htdocs\panipone` (Windows) or `/opt/lampp/htdocs/panipone`
   (Linux). The bundled root `.htaccess` rewrites every incoming request
   into `public/`, so you do **not** need to point Apache's DocumentRoot
   at `public/` — just start Apache from the XAMPP control panel.

3. **Create the database.** Open phpMyAdmin
   (`http://localhost/phpmyadmin`), create a database named `panipone`
   with collation `utf8mb4_unicode_ci`. The default XAMPP MySQL
   credentials (`root` / empty password) are already wired into
   `.env.example`.

4. **Configure the app.**

   ```bash
   cp .env.example .env
   php artisan key:generate
   php artisan migrate --seed
   ```

   You can also import `database/sql/panipone.sql` directly through
   phpMyAdmin if you prefer not to run migrations.

5. **Open the app** at `http://localhost/panipone/` (replace `panipone`
   with whatever you named the folder inside `htdocs/`). Apache's
   `mod_rewrite` must be enabled — it is on by default in XAMPP.

### Apache requirements

- `mod_rewrite` enabled (default in XAMPP)
- `AllowOverride All` for the `htdocs/` directory (default in XAMPP)
- PHP 8.2+ — XAMPP 8.2.x and newer ship a compatible PHP build

If you see a blank page or a 500 error, check
`storage/logs/laravel.log` and the Apache error log under
`xampp/apache/logs/error.log`.

See `docs/` (forthcoming) for module deep-dives.
