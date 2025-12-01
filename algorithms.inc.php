<?php
/**
 * Advanced E-Commerce Algorithms Module
 * Includes: Recommendation Engine, Search Ranking, Demand Forecasting, ABC Analysis, Upselling
 */

// ============================
// 1. RECOMMENDATION ENGINE
// ============================

/**
 * Get Similar Products using Content-Based Filtering 
 * Finds products in same category with similar price range and rating
 */
//finished
function get_similar_products($conn, $product_id, $limit = 4) {
    // Get the reference product first
    $res = mysqli_query($conn, "SELECT * FROM product WHERE id=$product_id");
    $ref = mysqli_fetch_assoc($res);
    
    $category_id = $ref['categories_id'];
    $price = $ref['price'];
    $rating = $ref['rating'];

    // Get similar products
    $query = "SELECT id, name, price, mrp, image, rating,
                     ABS(price - $price) AS price_diff
              FROM product
              WHERE id != $product_id
                AND status = 1
                AND categories_id = $category_id
                AND ABS(price - $price) < ($price * 0.3)
              ORDER BY price_diff ASC, view_count DESC
              LIMIT $limit";
    
    $res = mysqli_query($conn, $query);
    $data = [];
    while($row = mysqli_fetch_assoc($res)) {
        $data[] = $row;
    }
    return $data;
}


/**
 * Get Frequently Bought Together using Association Rule Mining
 * Finds products that are often purchased in same order
 */
// finished
function get_frequently_bought_together($conn, $product_id, $limit = 4) {
    $query = "SELECT p.id, p.name, p.price, p.mrp, p.image, p.rating,
                     COUNT(DISTINCT od1.order_id) as frequency
              FROM product p
              JOIN order_detail od1 ON p.id = od1.product_id
              JOIN order_detail od2 ON od1.order_id = od2.order_id
              WHERE od2.product_id = $product_id
              AND p.id != $product_id
              AND p.status = 1
              GROUP BY p.id
              ORDER BY frequency DESC, p.rating DESC
              LIMIT $limit";
    
    $res = mysqli_query($conn, $query);
    $data = array();
    while($row = mysqli_fetch_assoc($res)) {
        $data[] = $row;
    }
    return $data;
}

/**
 * Collaborative Filtering - Recommend based on similar user behavior
 */
//finished
function get_recommendations_collaborative_filtering($conn, $user_id, $limit = 4) {
    $query = "SELECT p.id, p.name, p.price, p.mrp, p.image, p.rating,
                     COUNT(w.id) as wishlist_count
              FROM product p
              JOIN wishlist w ON p.id = w.product_id
              WHERE w.user_id IN (
                  SELECT w2.user_id 
                  FROM wishlist w1
                  JOIN wishlist w2 ON w1.product_id = w2.product_id
                  WHERE w1.user_id = $user_id
                  AND w2.user_id != $user_id
              )
              AND p.id NOT IN (
                  SELECT product_id FROM wishlist WHERE user_id = $user_id
              )
              AND p.id NOT IN (
                  SELECT product_id FROM order_detail od
                  JOIN `order` o ON od.order_id = o.id
                  WHERE o.user_id = $user_id
              )
              AND p.status = 1
              GROUP BY p.id
              ORDER BY wishlist_count DESC, p.rating DESC, p.view_count DESC
              LIMIT $limit";
    
    $res = mysqli_query($conn, $query);
    $data = array();
    while($row = mysqli_fetch_assoc($res)) {
        $data[] = $row;
    }
    return $data;
}

/**
 * Popular Products for Homepage (by view count and rating)
 */
//finished
function get_popular_products($conn, $limit = 4) {
    /*
     * Fetch most viewed products along with their average rating
     * - Data from `product` table
     * - Rating from `product_ratings` table
     */

    $query = "
        SELECT 
            p.id,
            p.name,
            p.price,
            p.mrp,
            p.image,
            p.view_count,
            p.best_seller,  -- added this line
            IFNULL(AVG(pr.rating), 0) AS rating
        FROM product p
        LEFT JOIN product_ratings pr ON p.id = pr.product_id
        WHERE p.status = 1
        GROUP BY p.id
        ORDER BY p.view_count DESC, rating DESC
        LIMIT ?
    ";

    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $limit);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    $data = array();
    while ($row = mysqli_fetch_assoc($res)) {
        $data[] = $row;
    }
    return $data;
}



// ============================
// 2. IMPROVED SEARCH RANKING (TF-IDF)
// ============================

/**
 * TF-IDF Search Ranking
 * Returns products ranked by relevance using term frequency and inverse document frequency
 */
// finished
function search_with_tf_idf_ranking($conn, $search_str, $cat_id = '', $limit = 20) {
    $search_str = mysqli_real_escape_string($conn, trim($search_str));
    $search_terms = explode(' ', $search_str);
    
    $sql = "SELECT p.*, c.categories,
                   (
                       (CASE WHEN p.name LIKE '%$search_str%' THEN 5 ELSE 0 END) +
                       (CASE WHEN p.description LIKE '%$search_str%' THEN 3 ELSE 0 END) +
                       (CASE WHEN p.short_desc LIKE '%$search_str%' THEN 2 ELSE 0 END)
                   ) as relevance_score,
                   p.rating,
                   p.view_count,
                   (p.view_count * 0.2 + p.rating * 10) as popularity_score
            FROM product p
            JOIN categories c ON p.categories_id = c.id
            WHERE p.status = 1
            AND (p.name LIKE '%$search_str%' 
                 OR p.description LIKE '%$search_str%' 
                 OR p.short_desc LIKE '%$search_str%')";
    
    if($cat_id != '') {
        $cat_id = mysqli_real_escape_string($conn, $cat_id);
        $sql .= " AND p.categories_id = $cat_id";
    }
    
    $sql .= " ORDER BY relevance_score DESC, popularity_score DESC, p.rating DESC
              LIMIT $limit";
    
    $res = mysqli_query($conn, $sql);
    $data = array();
    while($row = mysqli_fetch_assoc($res)) {
        $data[] = $row;
    }
    return $data;
}

// ============================
// 3. ABC ANALYSIS
// ============================

/**
 * ABC Analysis - Categorize products by importance/revenue
 * A = High Value, B = Medium Value, C = Low Value
 */
//finished
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
              JOIN `order` o ON od.order_id = o.id
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
function detect_product_bundles_debug($conn, $min_support = 1) {
    $query = "SELECT 
                od1.order_id,
                od1.product_id as p1_id,
                od2.product_id as p2_id,
                p1.name as name_1,
                p2.name as name_2,
                p1.status as status_1,
                p2.status as status_2
              FROM order_detail od1
              JOIN order_detail od2 ON od1.order_id = od2.order_id
              JOIN product p1 ON od1.product_id = p1.id
              JOIN product p2 ON od2.product_id = p2.id
              WHERE od1.product_id != od2.product_id
              ORDER BY od1.order_id";

    $res = mysqli_query($conn, $query);
    $pairs = [];

    while($row = mysqli_fetch_assoc($res)) {
        $pairs[] = $row;
    }

 
    // Now calculate frequency
    $freq = [];
    foreach($pairs as $pair) {
        // only include products with status = 1
        if($pair['status_1'] == 1 && $pair['status_2'] == 1) {
            // create a unique key for pair (order doesn't matter)
            $key = ($pair['p1_id'] < $pair['p2_id']) ? $pair['p1_id'].'-'.$pair['p2_id'] : $pair['p2_id'].'-'.$pair['p1_id'];
            if(!isset($freq[$key])) {
                $freq[$key] = [
                    'product_1' => $pair['p1_id'],
                    'product_2' => $pair['p2_id'],
                    'name_1' => $pair['name_1'],
                    'name_2' => $pair['name_2'],
                    'frequency' => 0
                ];
            }
            $freq[$key]['frequency']++;
        }
    }



    // filter by min_support
    $bundles = [];
    foreach($freq as $b) {
        if($b['frequency'] >= $min_support) {
            $bundles[] = $b;
        }
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

              AND CAST(p1.price AS DECIMAL(10,2)) <= (p2.price * 1.5)
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
// finished
function get_crosssell_products($conn, $product_id, $limit = 3) {
    $query = "SELECT 
                p.id, 
                p.name, 
                p.price, 
                p.mrp, 
                p.image, 
                p.rating, 
                p.best_seller
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
//finished
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
//finished
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
