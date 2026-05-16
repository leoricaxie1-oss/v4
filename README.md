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

See `docs/` (forthcoming) for module deep-dives.
