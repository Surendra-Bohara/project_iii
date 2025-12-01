<?php

// ============================
// 3. ABC ANALYSIS
// ============================

/**
 * ABC Analysis - Categorize products by importance/revenue
 * A = High Value, B = Medium Value, C = Low Value
 */
function get_abc_analysis($conn) {
    $query = "SELECT p.id, p.name, p.price, p.categories_id,
                     COUNT(od.id) as order_count,
                     SUM(od.qty * od.price) as total_revenue,
                     SUM(od.qty) as total_qty_sold
              FROM product p
              LEFT JOIN order_detail od ON p.id = od.product_id
              WHERE p.status = 1
              GROUP BY p.id
              ORDER BY total_revenue DESC";
    
    $res = mysqli_query($conn, $query);
    $total_revenue = 0;
    $products = array();
    $cumulative_revenue = 0;
    $revenue_array = array();
    
    while($row = mysqli_fetch_assoc($res)) {
        $products[] = $row;
        $total_revenue += ($row['total_revenue'] ?? 0);
    }
    
    // Assign ABC classification
    foreach($products as &$product) {
        $cumulative_revenue += ($product['total_revenue'] ?? 0);
        $percentage = ($total_revenue > 0) ? ($cumulative_revenue / $total_revenue) * 100 : 0;
        
        if($percentage <= 80) {
            $product['abc_class'] = 'A'; // Top 80% revenue
        } elseif($percentage <= 95) {
            $product['abc_class'] = 'B'; // Next 15% revenue
        } else {
            $product['abc_class'] = 'C'; // Bottom 5% revenue
        }
    }
    
    return $products;
}

// ============================
// 4. DEMAND FORECASTING
// ============================

/**
 * Simple Demand Forecasting using Moving Average
 * Predicts future demand based on historical data
 */
function forecast_demand($conn, $product_id, $months_back = 3) {
    $query = "SELECT 
                MONTH(o.added_on) as month,
                YEAR(o.added_on) as year,
                SUM(od.qty) as total_qty
              FROM order_detail od
              JOIN \`order\` o ON od.order_id = o.id
              WHERE od.product_id = $product_id
              AND o.added_on >= DATE_SUB(NOW(), INTERVAL $months_back MONTH)
              GROUP BY YEAR(o.added_on), MONTH(o.added_on)
              ORDER BY YEAR(o.added_on), MONTH(o.added_on)
              LIMIT $months_back";
    
    $res = mysqli_query($conn, $query);
    $historical_data = array();
    $total_demand = 0;
    
    while($row = mysqli_fetch_assoc($res)) {
        $historical_data[] = (int)$row['total_qty'];
        $total_demand += $row['total_qty'];
    }
    
    // Calculate moving average
    $forecast = 0;
    if(count($historical_data) > 0) {
        $forecast = $total_demand / count($historical_data);
    }
    
    return array(
        'product_id' => $product_id,
        'historical_data' => $historical_data,
        'average_monthly_demand' => round($forecast, 2),
        'trend' => (count($historical_data) >= 2) ? 
                   (($historical_data[count($historical_data)-1] - $historical_data[0]) / $historical_data[0]) * 100 : 0
    );
}

// ============================
// 5. BUNDLE DETECTION
// ============================

/**
 * Detect Product Bundles using Association Rule Mining
 * Find products frequently bought together to create bundles
 */
function detect_product_bundles($conn, $min_support = 10) {
    $query = "SELECT 
                p1.id as product_1,
                p2.id as product_2,
                p1.name as name_1,
                p2.name as name_2,
                p1.price as price_1,
                p2.price as price_2,
                COUNT(DISTINCT od1.order_id) as frequency,
                COUNT(DISTINCT od1.order_id) / (SELECT COUNT(DISTINCT order_id) FROM order_detail) * 100 as support_percentage
              FROM order_detail od1
              JOIN order_detail od2 ON od1.order_id = od2.order_id
              JOIN product p1 ON od1.product_id = p1.id
              JOIN product p2 ON od2.product_id = p2.id
              WHERE od1.product_id < od2.product_id
              AND p1.status = 1
              AND p2.status = 1
              GROUP BY od1.product_id, od2.product_id
              HAVING frequency >= $min_support
              ORDER BY frequency DESC
              LIMIT 10";
    
    $res = mysqli_query($conn, $query);
    $bundles = array();
    while($row = mysqli_fetch_assoc($res)) {
        $bundles[] = $row;
    }
    return $bundles;
}

// ============================
// 6. UPSELLING & CROSS-SELLING
// ============================

/**
 * Upsell Recommendations - Suggest higher-priced alternatives
 */
// finished
function get_upsell_products($conn, $product_id, $limit = 3) {
    $query = "SELECT p1.id, p1.name, p1.price, p1.mrp, p1.image, p1.rating
              FROM product p1
              JOIN product p2 ON p1.id = $product_id
              WHERE p1.categories_id = p2.categories_id
              AND p1.status = 1
              AND p1.price > p2.price
              AND CAST(p1.price AS DECIMAL(10,2)) > p1.price 
              AND CAST(p1.price AS DECIMAL(10,2)) <= (p2.price * 1.5)

              AND p1.price <= (p2.price * 1.5)
              AND p1.rating >= 3
              AND p1.id != $product_id
              ORDER BY (p1.rating * 10 + p1.view_count * 0.5) DESC,
                       p1.price ASC
              LIMIT $limit";
    
    $res = mysqli_query($conn, $query);
    $data = array();
    while($row = mysqli_fetch_assoc($res)) {
        $data[] = $row;
    }
    return $data;
}

/**
 * Cross-sell Recommendations - Suggest complementary products
 */
function get_crosssell_products($conn, $product_id, $limit = 3) {
    $query = "SELECT p.id, p.name, p.price, p.mrp, p.image, p.rating
              FROM product p
              WHERE p.status = 1
              AND p.categories_id != (SELECT categories_id FROM product WHERE id = $product_id)
              AND p.id != $product_id
              AND p.price < (SELECT price FROM product WHERE id = $product_id)
              ORDER BY p.rating DESC, p.view_count DESC, p.best_seller DESC
              LIMIT $limit";
    
    $res = mysqli_query($conn, $query);
    $data = array();
    while($row = mysqli_fetch_assoc($res)) {
        $data[] = $row;
    }
    return $data;
}

// ============================
// 7. PRODUCT VIEW TRACKING
// ============================

/**
 * Track Product Views for popularity metrics
 */
function track_product_view($conn, $product_id) {
    $query = "UPDATE product SET view_count = view_count + 1 WHERE id = $product_id";
    return mysqli_query($conn, $query);
}

// ============================
// 8. ABANDONED CART ALERTS
// ============================

/**
 * Find Abandoned Carts (not purchased within 24 hours)
 */
function get_abandoned_carts($conn) {
    $query = "SELECT DISTINCT u.id, u.email, u.name,
                     GROUP_CONCAT(p.name SEPARATOR ', ') as products,
                     COUNT(DISTINCT p.id) as product_count
              FROM users u
              WHERE u.id IN (
                  SELECT DISTINCT user_id FROM order_detail od
                  JOIN `order` o ON od.order_id = o.id
                  WHERE o.payment_status != 'success'
                  AND o.added_on < DATE_SUB(NOW(), INTERVAL 24 HOUR)
              )
              GROUP BY u.id";
    
    $res = mysqli_query($conn, $query);
    $data = array();
    while($row = mysqli_fetch_assoc($res)) {
        $data[] = $row;
    }
    return $data;
}

// ============================
// 9. CUSTOMER SEGMENTATION
// ============================

/**
 * Segment customers by purchase behavior (RFM Analysis)
 * R = Recency, F = Frequency, M = Monetary
 */
function get_customer_segments($conn) {
    $query = "SELECT u.id, u.name, u.email,
                     DATEDIFF(NOW(), MAX(o.added_on)) as recency_days,
                     COUNT(DISTINCT o.id) as frequency,
                     COALESCE(SUM(o.total_price), 0) as monetary_value,
                     CASE 
                        WHEN DATEDIFF(NOW(), MAX(o.added_on)) <= 30 AND COUNT(DISTINCT o.id) >= 3 AND SUM(o.total_price) >= 5000 THEN 'VIP'
                        WHEN DATEDIFF(NOW(), MAX(o.added_on)) <= 60 AND COUNT(DISTINCT o.id) >= 2 THEN 'Active'
                        WHEN DATEDIFF(NOW(), MAX(o.added_on)) <= 180 THEN 'At Risk'
                        ELSE 'Inactive'
                     END as segment
              FROM users u
              LEFT JOIN `order` o ON u.id = o.user_id
              GROUP BY u.id
              ORDER BY segment, monetary_value DESC";
    
    $res = mysqli_query($conn, $query);
    $data = array();
    while($row = mysqli_fetch_assoc($res)) {
        $data[] = $row;
    }
    return $data;
}

// ============================
// 10. PRICE OPTIMIZATION
// ============================

/**
 * Dynamic Price Recommendations based on demand and competition
 */
function get_price_optimization($conn, $product_id) {
    $query = "SELECT p.id, p.name, p.price, p.mrp,
                     AVG(similar_products.price) as competitor_avg_price,
                     COUNT(DISTINCT od.order_id) as sales_volume,
                     SUM(od.qty) as total_qty_sold
              FROM product p
              LEFT JOIN order_detail od ON p.id = od.product_id
              LEFT JOIN product similar_products ON p.categories_id = similar_products.categories_id 
                                                  AND p.id != similar_products.id
              WHERE p.id = $product_id
              GROUP BY p.id";
    
    $res = mysqli_query($conn, $query);
    $product = mysqli_fetch_assoc($res);
    
    if($product) {
        $competitor_avg = $product['competitor_avg_price'] ?? $product['price'];
        $sales_volume = $product['sales_volume'] ?? 0;
        
        // Simple pricing strategy
        $recommended_price = $competitor_avg;
        if($sales_volume < 5) {
            // Low sales: reduce price by 5-10%
            $recommended_price = $product['price'] * 0.90;
        } elseif($sales_volume > 20) {
            // High sales: increase price by 5-10%
            $recommended_price = $product['price'] * 1.05;
        }
        
        $product['recommended_price'] = round($recommended_price, 2);
        $product['price_adjustment'] = round((($recommended_price - $product['price']) / $product['price']) * 100, 2);
    }
    
    return $product;
}

?>
