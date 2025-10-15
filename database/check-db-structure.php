<?php
/**
 * Check Database Table Structure
 * Debug what tables and columns actually exist
 */

// Include the core system
require '../app/core/config.php';

echo "🔍 Checking Database Structure...\n\n";

try {
    // Connect to database
    $pdo = new PDO("mysql:host=" . DBHOST . ";dbname=" . DBNAME, DBUSER, DBPASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Connected to database: " . DBNAME . "\n\n";
    
    // Show all tables
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "📋 Tables in database:\n";
    foreach ($tables as $table) {
        echo "   • $table\n";
    }
    echo "\n";
    
    // Check if achievements table exists and show its structure
    if (in_array('achievements', $tables)) {
        echo "🔧 Structure of achievements table:\n";
        $stmt = $pdo->query("DESCRIBE achievements");
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($columns as $column) {
            echo "   • {$column['Field']} ({$column['Type']}) - {$column['Null']} - {$column['Key']}\n";
        }
        echo "\n";
        
        // Check if there are any records
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM achievements");
        $count = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "📊 Records in achievements table: " . $count['count'] . "\n\n";
    } else {
        echo "❌ Achievements table does not exist!\n\n";
    }
    
} catch (PDOException $e) {
    echo "❌ Database error: " . $e->getMessage() . "\n";
}

echo "🏁 Database structure check completed.\n";
?>
