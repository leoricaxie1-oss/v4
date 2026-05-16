<?php
/**
 * PanipOne — application configuration
 *
 * Tune these values to match your XAMPP installation. The defaults below
 * line up with a stock XAMPP setup (MySQL on 127.0.0.1:3306, root user,
 * empty password) and a project folder dropped into htdocs/panipone.
 *
 * If you renamed the htdocs folder (e.g. htdocs/v5), update BASE_URL
 * accordingly — every link and form action is built from BASE_URL.
 */

// ---- Database (XAMPP MySQL / MariaDB via phpMyAdmin) -----------------------
const DB_HOST     = '127.0.0.1';
const DB_PORT     = 3306;
const DB_NAME     = 'panipone';
const DB_USER     = 'root';
const DB_PASS     = '';
const DB_CHARSET  = 'utf8mb4';

// ---- Application -----------------------------------------------------------
const APP_NAME    = 'PanipOne';
const APP_TAGLINE = 'Barangay Panipuan Management System';
const APP_TZ      = 'Asia/Manila';

/**
 * Public base URL, no trailing slash.
 *
 * Examples:
 *   http://localhost/panipone   (project lives in htdocs/panipone)
 *   http://localhost/v5         (project lives in htdocs/v5)
 *   http://localhost            (project lives directly in htdocs/)
 *
 * The router auto-detects this when BASE_URL is left as an empty string,
 * but you can hard-code it here if the auto-detection ever guesses wrong.
 */
const BASE_URL = '';

// ---- Session --------------------------------------------------------------
const SESSION_NAME      = 'panipone_sid';
const SESSION_LIFETIME  = 60 * 60 * 2; // 2 hours

// ---- Misc -----------------------------------------------------------------
const APP_DEBUG = true; // set to false on production
