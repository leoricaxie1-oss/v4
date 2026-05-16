<?php
/**
 * One-shot flash messages stored in the session.
 *
 *   flash('success', 'Saved.');
 *   foreach (flash_pull_all() as $type => $messages) { ... }
 */

function flash(string $type, string $message): void
{
    $_SESSION['_flash'][$type][] = $message;
}

function flash_pull_all(): array
{
    $all = $_SESSION['_flash'] ?? [];
    unset($_SESSION['_flash']);
    return $all;
}

function old(string $key, string $default = ''): string
{
    return (string) ($_SESSION['_old'][$key] ?? $default);
}

function remember_old(array $input): void
{
    // Don't persist password fields.
    unset($input['password'], $input['password_confirmation']);
    $_SESSION['_old'] = $input;
}

function forget_old(): void
{
    unset($_SESSION['_old']);
}
