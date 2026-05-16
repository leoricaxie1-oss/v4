# PanipOne — Barangay Management System

A **plain PHP + MySQL** web application for the day-to-day operations of
**Barangay Panipuan**. The app runs out of the box on **XAMPP**: no
Composer, no Node.js, no build step, no framework — just drop it into
`htdocs/`, import the SQL through phpMyAdmin, and go.

## Modules

- Account registration with Secretary approval
- Landing page with announcements and a community directory
- Resident dashboard: profile, document requests, complaints, notifications
- Skills & Services + Business directory
- Document request workflow (Barangay Clearance, Residency, Indigency,
  Business Clearance)
- Complaint filing with status tracking

## Stack

- **PHP 7.4+** (uses PDO, `password_hash`, sessions — works on XAMPP 7.4
  through 8.x)
- **MySQL / MariaDB** via XAMPP phpMyAdmin
- **Vanilla HTML / CSS** — no build chain
- Apache + `mod_rewrite` for clean URLs

## Setup on XAMPP

1. **Install XAMPP** (https://www.apachefriends.org/) and start the
   **Apache** and **MySQL** modules from the XAMPP Control Panel.

2. **Place the project in `htdocs/`.** Copy or clone this repository to
   `C:\xampp\htdocs\panipone` on Windows (or `/opt/lampp/htdocs/panipone`
   on Linux). You can name the folder anything — the app auto-detects the
   base URL.

3. **Create the database in phpMyAdmin.**
   - Open http://localhost/phpmyadmin.
   - Click **New** in the left sidebar.
   - Enter database name `panipone`, collation `utf8mb4_unicode_ci`, and
     click **Create**.

4. **Import the schema.**
   - Click the new `panipone` database in the sidebar.
   - Click the **Import** tab.
   - Choose `database/panipone.sql` from this project.
   - Click **Go**. Tables and seed data are created.

5. **Open the app.** Visit `http://localhost/panipone/` (replace
   `panipone` with whatever you named the htdocs folder).

That's it. There is no `composer install`, no `npm install`, no `.env`,
no migrations to run.

## Demo accounts

The SQL seed creates these users. The password for all of them is
`password` — change them after first login.

| Role      | Email                       |
|-----------|-----------------------------|
| Captain   | captain@panipone.local      |
| Secretary | secretary@panipone.local    |
| Kagawad   | kagawad@panipone.local      |
| Resident  | resident@panipone.local     |
| Pending   | pending@panipone.local      |

## Custom credentials or paths

If your XAMPP MySQL uses different credentials, or you renamed the
htdocs folder and need a hard-coded URL, edit `config.php`:

```php
const DB_HOST = '127.0.0.1';
const DB_NAME = 'panipone';
const DB_USER = 'root';
const DB_PASS = '';
const BASE_URL = '';  // leave empty for auto-detect, or set explicitly
```

## Project layout

```
.
├── index.php              Front controller / router
├── config.php             DB credentials, app settings
├── .htaccess              mod_rewrite rules for clean URLs
├── assets/css/style.css   Vanilla stylesheet
├── database/panipone.sql  Schema + seed data, importable in phpMyAdmin
├── includes/              Shared bootstrap, helpers, layout
│   ├── bootstrap.php
│   ├── db.php             PDO connection, friendly error page on failure
│   ├── auth.php           Session-based authentication
│   ├── csrf.php           CSRF token helpers
│   ├── flash.php          Flash messages + old form input
│   ├── helpers.php        Escape, URL, query helpers
│   └── layout/            header.php, footer.php
└── pages/                 One PHP file per route
    ├── home.php
    ├── announcements.php
    ├── directory.php
    ├── login.php
    ├── register.php
    ├── logout.php
    ├── dashboard.php
    ├── profile.php
    ├── documents.php
    ├── document_new.php
    ├── complaints.php
    ├── complaint_new.php
    └── 404.php
```

## Apache requirements

- `mod_rewrite` enabled (on by default in XAMPP)
- `AllowOverride All` for `htdocs/` (default in XAMPP)

If clean URLs return 404, verify both above are active. The
`pages/`, `includes/`, and `database/` folders are protected by their
own `.htaccess` files so they cannot be served directly even without
mod_rewrite.

## Security notes

- All SQL goes through PDO prepared statements.
- All user-facing output is escaped via `e()` (htmlspecialchars).
- All POST forms include a per-session CSRF token verified by
  `csrf_check()`.
- Passwords are stored as bcrypt hashes via `password_hash()`.
- Sessions use HttpOnly cookies and regenerate their ID on login.

## Troubleshooting

- **"Cannot connect to MySQL"** — the app shows a setup page with the
  exact host, port, database, and user it tried. Start XAMPP MySQL or
  fix credentials in `config.php`.
- **Blank page or 500 error** — set `APP_DEBUG = true` in `config.php`
  (it is by default) and reload to see the error. Also check
  `xampp/apache/logs/error.log`.
- **Clean URLs return 404** — confirm `mod_rewrite` is enabled in
  `httpd.conf` and that the `<Directory "C:/xampp/htdocs">` block has
  `AllowOverride All`.
