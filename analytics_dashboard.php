<?php
require('top.inc.php');
require_once('connection.inc.php');
require('../algorithms.inc.php');

$page = isset($_GET['page']) ? $_GET['page'] : 'overview';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Algorithm Analytics Dashboard</title>
    <style>
        .analytics-header { background: #f8f9fa; padding: 20px; margin-bottom: 30px; border-radius: 5px; }
        .metric-card { background: #fff; padding: 20px; margin: 10px 0; border-left: 5px solid #007bff; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .metric-card h3 { margin-top: 0; color: #333; }
        .metric-value { font-size: 28px; font-weight: bold; color: #007bff; }
        .metric-label { color: #999; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        table th { background: #f8f9fa; padding: 10px; text-align: left; border-bottom: 2px solid #dee2e6; }
        table td { padding: 10px; border-bottom: 1px solid #dee2e6; }
        table tr:hover { background: #f9f9f9; }
        .abc-a { color: #28a745; font-weight: bold; }
        .abc-b { color: #ffc107; font-weight: bold; }
        .abc-c { color: #dc3545; font-weight: bold; }
        .nav-tabs { margin-bottom: 20px; }
        .nav-tabs li { display: inline-block; margin-right: 10px; }
        .nav-tabs a { padding: 10px 15px; display: block; background: #f8f9fa; color: #333; text-decoration: none; border-radius: 3px; }
        .nav-tabs a.active { background: #007bff; color: #fff; }
        .chart-container { background: #fff; padding: 20px; margin: 20px 0; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
    </style>
</head>
<body>
<div class="analytics-header">
    <h1>📊 Algorithm Analytics Dashboard</h1>
    <p>Performance metrics, recommendations, and data analysis</p>
</div>

<ul class="nav-tabs">
    <li><a href="?page=overview" class="<?php echo ($page=='overview'?'active':''); ?>">Overview</a></li>
    <li><a href="?page=abc_analysis" class="<?php echo ($page=='abc_analysis'?'active':''); ?>">ABC Analysis</a></li>
    <li><a href="?page=demand_forecast" class="<?php echo ($page=='demand_forecast'?'active':''); ?>">Demand Forecast</a></li>
    <li><a href="?page=bundles" class="<?php echo ($page=='bundles'?'active':''); ?>">Product Bundles</a></li>
    <li><a href="?page=customer_segments" class="<?php echo ($page=='customer_segments'?'active':''); ?>">Customer Segments</a></li>
    <li><a href="?page=popular_products" class="<?php echo ($page=='popular_products'?'active':''); ?>">Popular Products</a></li>
</ul>

<div class="container">
    <?php
    if($page == 'overview') {
        // Overview Statistics
        $total_products = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as cnt FROM product WHERE status=1"))['cnt'];
        $total_views = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(view_count) as total FROM product"))['total'] ?? 0;
        $total_orders = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as cnt FROM `order` WHERE order_status='5'"))['cnt'];
        $avg_rating = mysqli_fetch_assoc(mysqli_query($conn, "SELECT AVG(rating) as avg FROM product"))['avg'] ?? 0;
    ?>
    <div class="metric-card">
        <h3>📈 Key Metrics</h3>
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px;">
        <div class="metric-card">
            <div class="metric-label">Total Products</div>
            <div class="metric-value"><?php echo $total_products; ?></div>
        </div>
        <div class="metric-card">
            <div class="metric-label">Total Views</div>
            <div class="metric-value"><?php echo number_format($total_views); ?></div>
        </div>
        <div class="metric-card">
            <div class="metric-label">Total Orders</div>
            <div class="metric-value"><?php echo $total_orders; ?></div>
        </div>
        <div class="metric-card">
            <div class="metric-label">Average Rating</div>
            <div class="metric-value"><?php echo round($avg_rating, 2); ?>/5</div>
        </div>
    </div>
    
    <?php
    } elseif($page == 'abc_analysis') {
        $abc_data = get_abc_analysis($conn);
    ?>
    <div class="chart-container">
        <h2>ABC Analysis - Product Classification</h2>
        <p><strong>A Class:</strong> High value products (80% of revenue) | 
           <strong>B Class:</strong> Medium value (15% of revenue) | 
           <strong>C Class:</strong> Low value (5% of revenue)</p>
        
        <table>
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Total Revenue</th>
                    <th>Units Sold</th>
                    <th>Orders</th>
                    <th class="abc-a" style="color: #28a745;">Classification</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($abc_data as $item) { ?>
                <tr>
                    <td><?php echo $item['name']; ?></td>
                    <td>₹<?php echo number_format($item['total_revenue'] ?? 0, 2); ?></td>
                    <td><?php echo $item['total_qty_sold'] ?? 0; ?></td>
                    <td><?php echo $item['order_count'] ?? 0; ?></td>
                    <td class="abc-<?php echo strtolower($item['abc_class']); ?>"><?php echo $item['abc_class']; ?> Class</td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    
    <?php
    } elseif($page == 'demand_forecast') {
        // Get top 10 products by sales
        $top_products = mysqli_query($conn, "SELECT id, name FROM product WHERE status=1 ORDER BY id DESC LIMIT ");
    ?>
    <div class="chart-container">
        <h2>Demand Forecasting - Moving Average Analysis</h2>
        <table>
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Avg Monthly Demand</th>
                    <th>Trend (%)</th>
                    <th>Recommendation</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                while($product = mysqli_fetch_assoc($top_products)) {
                    $forecast = forecast_demand($conn, $product['id'], 3);
                    $trend = $forecast['trend'];
                    $recommendation = ($trend > 10) ? '📈 Increase Stock' : (($trend < -10) ? '📉 Reduce Stock' : '➡️ Maintain Stock');
                ?>
                <tr>
                    <td><?php echo $product['name']; ?></td>
                    <td><?php echo round($forecast['average_monthly_demand'], 0); ?> units</td>
                    <td><?php echo round($trend, 1); ?>%</td>
                    <td><?php echo $recommendation; ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    
    <?php
    } elseif($page == 'bundles') {
        
        $bundles = detect_product_bundles_debug($conn, 1); // use frequency >=1 for testing

if(!empty($bundles)) {
    echo '<table>
        <thead>
            <tr>
                <th>Product 1</th>
                <th>Product 2</th>
                <th>Times Bought Together</th>
                <th>Bundle Price (15% discount)</th>
            </tr>
        </thead>
        <tbody>';

    foreach($bundles as $bundle) {
        $bundle_price = ($bundle['product_1_price'] ?? $bundle['product_1']) + ($bundle['product_2_price'] ?? $bundle['product_2']);
        $bundle_price = $bundle_price * 0.85; // 15% discount
        echo '<tr>
                <td>'.htmlspecialchars($bundle['name_1']).'</td>
                <td>'.htmlspecialchars($bundle['name_2']).'</td>
                <td>'.$bundle['frequency'].'</td>
                <td>₹'.round($bundle_price,2).'</td>
              </tr>';
    }

    echo '</tbody></table>';
} else {
    echo '<p style="text-align:center;">No bundles detected yet</p>';
}

    ?>
 
    
    <?php
    } elseif($page == 'customer_segments') {
        $segments = get_customer_segments($conn);
    ?>
    <div class="chart-container">
        <h2>Customer Segmentation (RFM Analysis)</h2>
        <table>
            <thead>
                <tr>
                    <th>Customer Name</th>
                    <th>Email</th>
                    <th>Days Since Last Purchase</th>
                    <th>Purchase Frequency</th>
                    <th>Total Spent</th>
                    <th>Segment</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($segments as $customer) { 
                    $segment_color = ($customer['segment'] == 'VIP') ? '#28a745' : (
                        ($customer['segment'] == 'Active') ? '#007bff' : (
                            ($customer['segment'] == 'At Risk') ? '#ffc107' : '#dc3545'
                        )
                    );
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($customer['name']); ?></td>
                    <td><?php echo htmlspecialchars($customer['email']); ?></td>
                    <td><?php echo $customer['recency_days'] ?? 'N/A'; ?> days</td>
                    <td><?php echo $customer['frequency'] ?? 0; ?> orders</td>
                    <td>₹<?php echo number_format($customer['monetary_value'], 2); ?></td>
                    <td style="color: <?php echo $segment_color; ?>; font-weight: bold;"><?php echo $customer['segment']; ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    
    <?php
    } elseif($page == 'popular_products') {
        $popular = get_popular_products($conn, 20);
    ?>
    <div class="chart-container">
        <h2>Popular Products - By Views & Rating</h2>
        <table>
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Views</th>
                    <th>Rating</th>
                    <th>Price</th>
                    <th>Best Seller</th>
                    <th>Score</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($popular as $product) { 
                    $score = ($product['view_count'] * 0.4 + $product['rating'] * 20 + $product['best_seller'] * 30);
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($product['name']); ?></td>
                    <td><?php echo $product['view_count']; ?></td>
                    <td>⭐ <?php echo $product['rating']; ?>/5</td>
                    <td>₹<?php echo $product['price']; ?></td>
                    <td><?php echo ($product['best_seller'] ? '✓ Yes' : '✗ No'); ?></td>
                    <td><strong><?php echo round($score, 2); ?></strong></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <?php } ?>
</div>

<?php require('footer.inc.php'); ?>
</body>
</html>
