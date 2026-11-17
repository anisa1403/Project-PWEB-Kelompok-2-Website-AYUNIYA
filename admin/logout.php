<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/admin_auth.php';

unset($_SESSION['admin_authenticated'], $_SESSION['admin_username']);
header('Location: login.php');
exit;