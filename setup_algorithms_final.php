<?php
/**
 * Final Database Setup for Algorithms
 * Creates only missing tables - columns already exist
 */

require('connection.inc.php');

echo "<h2>Algorithm Database Setup</h2>";
echo "<p>Creating required tables for algorithm integration...</p>";

$tables = array(
    "product_ratings" => "CREATE TABLE IF NOT EXISTS product_ratings (
        id INT PRIMARY KEY AUTO_INCREMENT,
        product_id INT NOT NULL,
        user_id INT NOT NULL,
        rating INT NOT NULL,
        review TEXT,
        added_on DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (product_id) REFERENCES product(id),
        FOREIGN KEY (user_id) REFERENCES users(id),
        UNIQUE KEY unique_rating (product_id, user_id)
    )",
    
    "product_bundles" => "CREATE TABLE IF NOT EXISTS product_bundles (
        id INT PRIMARY KEY AUTO_INCREMENT,
        bundle_name VARCHAR(255) NOT NULL,
        product_1 INT NOT NULL,
        product_2 INT NOT NULL,
        bundle_discount DECIMAL(5,2) DEFAULT 0,
        status INT DEFAULT 1,
        created_on DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (product_1) REFERENCES product(id),
        FOREIGN KEY (product_2) REFERENCES product(id)
    )",
    
    "recommendation_log" => "CREATE TABLE IF NOT EXISTS recommendation_log (
        id INT PRIMARY KEY AUTO_INCREMENT,
        user_id INT,
        product_id INT NOT NULL,
        recommendation_type VARCHAR(50),
        clicked INT DEFAULT 0,
        purchased INT DEFAULT 0,
        created_on DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (product_id) REFERENCES product(id)
    )",
    
    "abandoned_carts" => "CREATE TABLE IF NOT EXISTS abandoned_carts (
        id INT PRIMARY KEY AUTO_INCREMENT,
        user_id INT NOT NULL,
        cart_data JSON,
        total_price DECIMAL(10,2),
        abandoned_on DATETIME DEFAULT CURRENT_TIMESTAMP,
        recovered INT DEFAULT 0,
        FOREIGN KEY (user_id) REFERENCES users(id)
    )"
);

$success = 0;
foreach($tables as $name => $sql) {
    if(mysqli_query($conn, $sql)) {
        echo "✓ Table '$name' ready<br>";
        $success++;
    } else {
        echo "✗ Error with '$name': " . mysqli_error($conn) . "<br>";
    }
}

echo "<br><h3>✅ Setup Complete!</h3>";
echo "<p><strong>$success tables configured</strong></p>";
echo "<p>All algorithms are now ready to use!</p>";
echo "<hr>";
echo "<h3>Next Steps:</h3>";
echo "<ol>";
echo "<li>Visit a product page: <a href='product.php?id=1'>product.php?id=1</a></li>";
echo "<li>You should see: Similar Products, Frequently Bought Together, Upgrade sections</li>";
echo "<li>Check admin dashboard: <a href='AdminTheme/analytics_dashboard.php'>Analytics Dashboard</a></li>";
echo "<li>Try improved search: Use the search bar on homepage</li>";
echo "</ol>";
?>
