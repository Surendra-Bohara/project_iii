<?php


if (!function_exists('prx')) {
    function prx($arr){
        echo '<pre>';
        print_r($arr);
        echo '</pre>';
        die();
    }
}


if (!function_exists('get_safe_value')) {
    function get_safe_value($conn, $str){
      if(($str!='')){
        $str = trim($str);
        return mysqli_real_escape_string($conn, $str);
      }
    }
}



if (!function_exists('get_product')) {
    function get_product($conn, $limit = '', $cat_id = '', $product_id = '', $search_str = '', $sort_order = '', $is_best_seller=''){
        $sql = "SELECT product.*, categories.categories 
                FROM product, categories 
                WHERE product.status = 1 ";

        if($cat_id != ''){
            $sql .= " AND product.categories_id = $cat_id ";
        }
        if($product_id != ''){
            $sql .= " AND product.id = $product_id ";
        }
        if($is_best_seller!=''){
            $sql.=" and product.best_seller=1 ";
        }

        $sql .= " AND product.categories_id = categories.id ";

        if($search_str != ''){
            $sql .= " AND (product.name LIKE '%$search_str%' OR product.description LIKE '%$search_str%') ";
        }

        if($sort_order != ''){
            $sql .= $sort_order;
        } else {
            $sql .= " ORDER BY product.id DESC ";
        }

        if($limit != ''){
            $sql .= " LIMIT $limit";
        }

        $res = mysqli_query($conn, $sql);

        if(!$res) {
            die("SQL Error: " . mysqli_error($conn));
        }

        $data = array();
        while($row = mysqli_fetch_assoc($res)){
            $data[] = $row;
        }
        return $data;
    }
}
?>