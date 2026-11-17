<?php
declare(strict_types=1);

require_once __DIR__ . '/auth.php';

const ADMIN_USERNAME = 'admin';
const ADMIN_PASSWORD = 'admin12345';

function requireAdmin(): void
{
    if (empty($_SESSION['admin_authenticated'])) {
        header('Location: login.php');
        exit;
    }
}