<?php
// Drop books table if present and remove specific migration rows so migrations can run cleanly.
$dbHost = '127.0.0.1';
$dbPort = 3308;
$dbName = 'atividade';
$dbUser = 'root';
$dbPass = 'root';

try {
    $pdo = new PDO("mysql:host={$dbHost};port={$dbPort};dbname={$dbName}", $dbUser, $dbPass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    echo "Connected to {$dbName}\n";

    // Drop books table if exists
    $pdo->exec('DROP TABLE IF EXISTS books');
    echo "Dropped table books if it existed.\n";

    // Remove problematic migration rows so they can be re-run
    $migrations = [
        '2025_10_31_000001_create_authors_table',
        '2025_10_31_000002_create_categories_table',
        '2025_10_31_000003_create_publishers_table'
    ];

    $in = rtrim(str_repeat('?,', count($migrations)), ',');
    $stmt = $pdo->prepare("DELETE FROM migrations WHERE migration IN ({$in})");
    $stmt->execute($migrations);
    echo "Deleted " . $stmt->rowCount() . " migration rows (authors/categories/publishers).\n";

} catch (Exception $e) {
    echo 'ERROR: ' . $e->getMessage() . "\n";
    exit(1);
}

echo "Done.\n";
