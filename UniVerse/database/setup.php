<?php
/**
 * UniVerse Database Setup Script
 * Run this script to create the complete database structure
 * 
 * Usage: 
 * - Via browser: http://localhost/UniVerse/database/setup.php
 * - Via command line: php setup.php
 * - use this when you need to reset the database.
 */

require '../app/core/config.php';

echo "🚀 Setting up UniVerse Database...\n\n";

try {
    // Connect to MySQL server (without specifying database)
    $pdo = new PDO("mysql:host=" . DBHOST, DBUSER, DBPASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Connected to MySQL server\n";
    
    // Read and execute the complete schema
    $sql = file_get_contents('complete_schema.sql');
    
    if ($sql === false) {
        throw new Exception("Could not read complete_schema.sql file");
    }
    
    echo "📖 Reading database schema...\n";
    
    // Split by semicolon and execute each statement
    $statements = explode(';', $sql);
    $successCount = 0;
    
    foreach ($statements as $statement) {
        $statement = trim($statement);
        if (!empty($statement) && !preg_match('/^(--|\/\*|\*)/', $statement)) {
            try {
                $pdo->exec($statement);
                $successCount++;
            } catch (PDOException $e) {
                // Skip duplicate entry errors and other non-critical errors
                if (strpos($e->getMessage(), 'Duplicate entry') === false) {
                    echo "⚠️  Warning: " . $e->getMessage() . "\n";
                }
            }
        }
    }
    
    echo "\n✅ Main schema executed successfully! ($successCount statements)\n";
    
    // Read and execute the alumni schema
    $alumniSql = file_get_contents('alumni_schema.sql');
    
    if ($alumniSql !== false) {
        echo "📖 Reading alumni schema...\n";
        
        // Split by semicolon and execute each statement
        $alumniStatements = explode(';', $alumniSql);
        $alumniSuccessCount = 0;
        
        foreach ($alumniStatements as $statement) {
            $statement = trim($statement);
            if (!empty($statement) && !preg_match('/^(--|\/\*|\*)/', $statement)) {
                try {
                    $pdo->exec($statement);
                    $alumniSuccessCount++;
                } catch (PDOException $e) {
                    // Skip duplicate entry errors and other non-critical errors
                    if (strpos($e->getMessage(), 'Duplicate entry') === false && 
                        strpos($e->getMessage(), 'already exists') === false) {
                        echo "⚠️  Alumni Schema Warning: " . $e->getMessage() . "\n";
                    }
                }
            }
        }
        echo "✅ Alumni schema executed successfully! ($alumniSuccessCount statements)\n";
    } else {
        echo "ℹ️  Alumni schema file not found, skipping...\n";
    }
    
    echo "\n🎉 Database setup completed successfully!\n";
    echo "📊 Executed $successCount SQL statements\n\n";
    
    echo "📋 Created tables:\n";
    echo "   • users (with sample data)\n";
    echo "   • articles (with sample articles)\n";
    echo "   • achievements (with sample achievements)\n";
    echo "   • jobs (with sample job posting)\n";
    echo "   • job_applications\n";
    echo "   • notifications\n";
    echo "   • user_sessions\n";
    echo "   • system_settings\n\n";
    
    echo "👤 Default users created:\n";
    echo "   • Admin: admin@universe.edu (password: password)\n";
    echo "   • Student: john.doe@universe.edu (password: password)\n";
    echo "   • Company: hr@techcorp.com (password: password)\n\n";
    
    echo "🌐 You can now access:\n";
    echo "   • Home: http://localhost/UniVerse/\n";
    echo "   • Articles: http://localhost/UniVerse/articles\n";
    echo "   • Student Profile: http://localhost/UniVerse/umyprofile\n";
    echo "   • Achievements: http://localhost/UniVerse/uachievements\n\n";
    
    echo "💡 Next steps:\n";
    echo "   1. Update your config.php with correct database credentials\n";
    echo "   2. Test the login functionality\n";
    echo "   3. Add your own content through the admin panel\n\n";
    
} catch (PDOException $e) {
    echo "❌ Database connection failed: " . $e->getMessage() . "\n";
    echo "\n🔧 Troubleshooting:\n";
    echo "   1. Make sure XAMPP MySQL is running\n";
    echo "   2. Check database credentials in app/core/config.php\n";
    echo "   3. Ensure the database user has CREATE privileges\n\n";
} catch (Exception $e) {
    echo "❌ Setup failed: " . $e->getMessage() . "\n";
}

echo "🏁 Setup script completed.\n";
?>
