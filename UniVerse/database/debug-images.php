<?php
/**
 * Debug Article Image Paths
 * Check what's happening with the image paths
 */

// Include the core system
require '../app/core/init.php';

echo "🔍 Debugging Article Image Paths...\n\n";

try {
    // Create Article model instance
    $articleModel = new Article();
    
    // Get articles
    $articles = $articleModel->getAllArticles(3);
    
    echo "📊 Found " . count($articles) . " articles\n\n";
    
    foreach ($articles as $index => $article) {
        echo "Article " . ($index + 1) . ":\n";
        echo "  ID: " . $article['id'] . "\n";
        echo "  Title: " . $article['title'] . "\n";
        echo "  Image field: '" . $article['image'] . "'\n";
        
        // Check what the full path would be
        $fullPath = "http://localhost/UniVerse/public/assets/images/" . $article['image'];
        echo "  Full URL: " . $fullPath . "\n";
        
        // Check if file exists locally
        $localPath = "../public/assets/images/" . $article['image'];
        echo "  Local path: " . $localPath . "\n";
        echo "  File exists: " . (file_exists($localPath) ? "YES" : "NO") . "\n";
        
        if (!file_exists($localPath)) {
            echo "  ⚠️  IMAGE FILE MISSING!\n";
        }
        
        echo "\n";
    }
    
    // Check BASE_URL
    echo "🔧 Configuration:\n";
    echo "  BASE_URL: " . BASE_URL . "\n\n";
    
    // List what's actually in the images directory
    echo "📁 Files in /assets/images/:\n";
    $imageDir = "../public/assets/images/";
    if (is_dir($imageDir)) {
        $files = scandir($imageDir);
        foreach ($files as $file) {
            if ($file != '.' && $file != '..') {
                echo "  • " . $file . "\n";
            }
        }
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n🏁 Debug completed.\n";
?>
