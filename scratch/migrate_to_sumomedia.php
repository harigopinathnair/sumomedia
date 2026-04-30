<?php
/**
 * Migration Script: Harigopinath.com -> Sumomedia.in
 * This script migrates all data from the old database to the new database.
 */

// OLD DATABASE CREDENTIALS (Harigopinath.com)
$old_db = [
    'host' => 'localhost',
    'name' => 'harigopinathfi26', // As seen in original db.php
    'user' => 'root',
    'pass' => ''
];

// NEW DATABASE CREDENTIALS (Sumomedia.in)
$new_db = [
    'host' => 'localhost',
    'name' => 'sumomedia', // As updated in new db.php
    'user' => 'root',
    'pass' => ''
];

try {
    // 1. Connect to Old DB
    $pdo_old = new PDO("mysql:host={$old_db['host']};dbname={$old_db['name']}", $old_db['user'], $old_db['old_pass'] ?? $old_db['pass']);
    $pdo_old->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✓ Connected to OLD database: {$old_db['name']}\n";

    // 2. Connect to New DB (Create if not exists)
    $pdo_root = new PDO("mysql:host={$new_db['host']}", $new_db['user'], $new_db['pass']);
    $pdo_root->exec("CREATE DATABASE IF NOT EXISTS `{$new_db['name']}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    
    $pdo_new = new PDO("mysql:host={$new_db['host']};dbname={$new_db['name']}", $new_db['user'], $new_db['pass']);
    $pdo_new->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✓ Connected to NEW database: {$new_db['name']}\n";

    // 3. List of tables to migrate
    $tables = ['users', 'posts', 'leads', 'subscribers', 'chats', 'settings'];

    foreach ($tables as $table) {
        echo "Migrating table: $table...\n";
        
        // Get schema from old table
        $res = $pdo_old->query("SHOW CREATE TABLE `$table`")->fetch(PDO::FETCH_ASSOC);
        $create_sql = $res['Create Table'];
        
        // Create table in new DB
        $pdo_new->exec("DROP TABLE IF EXISTS `$table` ");
        $pdo_new->exec($create_sql);
        
        // Copy data
        $rows = $pdo_old->query("SELECT * FROM `$table`")->fetchAll(PDO::FETCH_ASSOC);
        if ($rows) {
            $cols = array_keys($rows[0]);
            $placeholders = implode(',', array_fill(0, count($cols), '?'));
            $insert_sql = "INSERT INTO `$table` (" . implode(',', $cols) . ") VALUES ($placeholders)";
            $stmt = $pdo_new->prepare($insert_sql);
            
            foreach ($rows as $row) {
                $stmt->execute(array_values($row));
            }
            echo "  - Migrated " . count($rows) . " rows.\n";
        } else {
            echo "  - Table is empty.\n";
        }
    }

    echo "\n🚀 Migration Complete! SumoMedia.in is now powered by the new database.\n";

} catch (Exception $e) {
    die("\n❌ Error during migration: " . $e->getMessage() . "\n");
}
