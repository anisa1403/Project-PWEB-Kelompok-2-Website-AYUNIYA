<?php
declare(strict_types=1);

date_default_timezone_set('Asia/Jakarta');

$databaseHost = '127.0.0.1';
$databaseName = 'ayuniya_db';
$databaseUser = 'root';
$databasePassword = '';

try {
    $pdo = new PDO(
        "mysql:host={$databaseHost};dbname={$databaseName};charset=utf8mb4",
        $databaseUser,
        $databasePassword,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
    $columnExists = $pdo->query(
        "SELECT COUNT(*) FROM information_schema.columns
         WHERE table_schema = DATABASE() AND table_name = 'users' AND column_name = 'profile_picture'"
    )->fetchColumn();
    if ((int) $columnExists === 0) {
        $pdo->exec('ALTER TABLE users ADD COLUMN profile_picture VARCHAR(255) NULL AFTER password');
    }
} catch (PDOException $exception) {
    http_response_code(500);
    exit('Database belum siap. Import database/schema.sql melalui phpMyAdmin lalu coba lagi.');
}
