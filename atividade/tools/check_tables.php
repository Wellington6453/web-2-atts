<?php
$dsn = "mysql:host=127.0.0.1;port=3308;dbname=information_schema";
$user = 'root';
$pass = 'root';
try {
    $pdoInfo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    $schema = 'atividade';
    $tables = ['authors','categories','publishers','books'];
    echo "Table engines in schema '$schema':\n";
    $stmt = $pdoInfo->prepare('SELECT TABLE_NAME, ENGINE FROM TABLES WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ?');
    foreach ($tables as $t) {
            $stmt->execute([$schema, $t]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            echo "- {$row['TABLE_NAME']}: {$row['ENGINE']}\n";
        } else {
            echo "- $t: MISSING\n";
        }
    }

    echo "\nSHOW CREATE TABLE (when available):\n";
    foreach ($tables as $t) {
        try {
            $q = $pdoInfo->query("SHOW CREATE TABLE `{$schema}`.`{$t}`");
            $res = $q->fetch(PDO::FETCH_NUM);
            if ($res) {
                echo "--- $t ---\n";
                echo $res[1] . "\n\n";
            }
        } catch (Exception $e) {
            echo "--- $t ---\n";
            echo "(not available) {$e->getMessage()}\n\n";
        }
    }

} catch (Exception $e) {
    echo 'ERROR: ' . $e->getMessage() . "\n";
    exit(1);
}

// Connect to the application database and list tables + migrations
try {
    $pdoApp = new PDO("mysql:host=127.0.0.1;port=3308;dbname={$schema}", $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    echo "\nAll tables in schema '{$schema}':\n";
    $q = $pdoApp->query("SHOW TABLES");
    $rows = $q->fetchAll(PDO::FETCH_NUM);
    foreach ($rows as $r) echo "- {$r[0]}\n";

    echo "\nMigrations table entries (recent):\n";
    $q = $pdoApp->query("SELECT migration, batch FROM migrations ORDER BY batch, migration");
    foreach ($q as $row) {
        echo "- {$row['migration']} [batch {$row['batch']}]\n";
    }
} catch (Exception $e) {
    echo "(could not query application DB) {$e->getMessage()}\n";
}
