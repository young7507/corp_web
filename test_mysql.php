<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=entweb;charset=utf8mb4', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    echo "Tables: " . implode(", ", $tables) . "\n";

    $admin = $pdo->query("SELECT * FROM lh_admin")->fetchAll(PDO::FETCH_ASSOC);
    echo "Admin: " . json_encode($admin) . "\n";
} catch(Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}