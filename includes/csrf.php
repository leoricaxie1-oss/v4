<?php
/**
 * Per-session CSRF token. Every POST form must include the hidden field
 * rendered by csrf_field(); every POST handler must call csrf_check().
 */

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="_token" value="' . e(csrf_token()) . '">';
}

function csrf_check(): void
{
    $sent = $_POST['_token'] ?? '';
    if (!is_string($sent) || !hash_equals(csrf_token(), $sent)) {
        http_response_code(419);
        echo 'CSRF token mismatch. Please reload the page and try again.';
        exit;
    }
}
