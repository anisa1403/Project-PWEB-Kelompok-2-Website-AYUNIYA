<?php
declare(strict_types=1);

date_default_timezone_set('Asia/Jakarta');

$databaseHost = '127.0.0.1';
<<<<<<< HEAD
$databasePort = 3308;
=======
$databasePort = 3306;
>>>>>>> a3bd38c (perbaikan alur dan penambahan halaman admin)
$databaseName = 'ayuniya_db';
$databaseUser = 'root';
$databasePassword = '';

try {
    $pdo = new PDO(
        "mysql:host={$databaseHost};port={$databasePort};dbname={$databaseName};charset=utf8mb4",
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
    $deadlineType = $pdo->query(
        "SELECT DATA_TYPE FROM information_schema.columns
         WHERE table_schema = DATABASE() AND table_name = 'goals' AND column_name = 'deadline'"
    )->fetchColumn();
    if ($deadlineType !== false && $deadlineType !== 'datetime') {
        $pdo->exec('ALTER TABLE goals MODIFY COLUMN deadline DATETIME NULL');
    }
} catch (PDOException $exception) {
    http_response_code(500);
    exit('Database belum siap. Import database/schema.sql melalui phpMyAdmin lalu coba lagi.');
}
