<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
}
auth_logout();
flash('success', 'You have been signed out.');
redirect('/');
