<?php
require('top.php');
include_once('function.inc.php');
include_once('algorithms.inc.php');

$user_id = isset($_SESSION['USER_ID']) ? $_SESSION['USER_ID'] : 0;

// Validate and sanitize product ID
if(isset($_GET['id']) && $_GET['id'] > 0) {
    $product_id = mysqli_real_escape_string($conn, $_GET['id']);
    $get_product = get_product($conn, '', '', $product_id);
    track_product_view($conn, $product_id);
} else {
    echo "<script>window.location.href='index.php';</script>";
    die();
}

// Handle rating submission
if(isset($_POST['submit_rating']) && $user_id) {
    $rating = floatval($_POST['rating']);
    if($rating >= 1 && $rating <= 5) {
        $stmt = $conn->prepare("INSERT INTO product_ratings (product_id, user_id, rating) 
                                VALUES (?, ?, ?)
                                ON DUPLICATE KEY UPDATE rating=?");
        $stmt->bind_param("iidd", $product_id, $user_id, $rating, $rating);
        $stmt->execute();
        $stmt->close();

        // UPDATE PRODUCT TABLE WITH NEW AVERAGE
        $avg_res = mysqli_query($conn, "SELECT AVG(rating) AS avg_rating 
                                        FROM product_ratings 
                                        WHERE product_id = $product_id");

        $avg_row = mysqli_fetch_assoc($avg_res);
        $new_avg = round($avg_row['avg_rating'], 1);

        mysqli_query($conn, "UPDATE product SET rating = $new_avg WHERE id = $product_id");

        echo "<script>alert('Thank you for rating!'); window.location.href='product.php?id=".$product_id."';</script>";
    } else {
        echo "<script>alert('Rating must be between 1 and 5');</script>";
    }
}


// Get average rating
$result = mysqli_query($conn, "SELECT AVG(rating) as avg_rating FROM product_ratings WHERE product_id = $product_id");
$row = mysqli_fetch_assoc($result);
$avg_rating = round($row['avg_rating'], 1);

// Get recommendations
$similar_products = get_similar_products($conn, $product_id, 4);
$frequently_bought = get_frequently_bought_together($conn, $product_id, 4);
$upsell_products = get_upsell_products($conn, $product_id, 3);
$crosssell_products = get_crosssell_products($conn, $product_id, 3);
?>

<!-- Start Bradcaump area -->
<div class="ht__bradcaump__area">
    <div class="ht__bradcaump__wrap">
        <div class="row align-items-center">
            <div class="col-xs-12 col-sm-6 col-md-6">
                <div class="bg-image">
                    <img src="images/bg/6.jpeg" alt="Banner Image">
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6">
                <div class="bradcaump__inner">
                    <nav class="bradcaump-inner">
                        <a class="breadcrumb-item" href="index.php">Home</a>
                        <span class="brd-separetor"><i class="zmdi zmdi-chevron-right"></i></span>
                        <a class="breadcrumb-item" href="categories.php?id=<?php echo $get_product[0]['categories_id'];?>"> 
                            <?php echo $get_product[0]['categories']; ?>
                        </a>
                        <span class="brd-separetor"><i class="zmdi zmdi-chevron-right"></i></span>
                        <span class="breadcrumb-item active"><?php echo $get_product[0]['name']; ?></span>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Bradcaump area -->

<!-- Start Product Details Area -->
<section class="htc__product__details bg__white ptb--100">
    <div class="htc__product__details__top">
        <div class="container">
            <div class="row">
                <!-- Product Image -->
                <div class="col-md-5 col-lg-5 col-sm-12 col-xs-12">
                    <div class="htc__product__details__tab__content">
                        <div class="product__big__images">
                            <div class="portfolio-full-image tab-content">
                                <div role="tabpanel" class="tab-pane fade in active" id="img-tab-1">
                                    <img src="media/product/<?php echo htmlspecialchars($get_product[0]['image']); ?>" alt="product image">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product Details -->
                <div class="col-md-7 col-lg-7 col-sm-12 col-xs-12 smt-40 xmt-40">
                    <div class="ht__product__dtl">
                        <h2><?php echo $get_product[0]['name']; ?></h2>
                        <ul class="pro__prize">
                            <li class="old__prize">₹<?php echo $get_product[0]['mrp']; ?></li>
                            <li>₹<?php echo $get_product[0]['price']; ?></li>
                        </ul>
                        <p class="pro__info"><?php echo $get_product[0]['short_desc']; ?></p>

                        <div class="ht__pro__desc">
                            <div class="sin__desc">
                                <p><span>Availability:</span> In Stock</p>
                            </div>
                               <!-- Average Rating -->
                        <div class="product-rating" style="margin-top:15px; display:flex; align-items:center; gap:10px;">
                            <h4 style="margin:0; margin-right:10px;">Average Rating:</h4>
                            <span style="font-size:16px; color:#ffcc00;">⭐ <?php echo $avg_rating > 0 ? $avg_rating : 'No ratings'; ?>/5</span>
                        </div>

                        <!-- Rating Form -->
                        <?php if($user_id) { ?>
                        <div class="product-rating" style="margin-top:10px; display:flex; align-items:center; gap:10px;">
                            <h4 style="margin:0; margin-right:10px;">Rate this product (1–5):</h4>
                            <form method="POST" style="display:flex; gap:5px; align-items:center;">
                                <input type="number" name="rating" step="0.1" min="1" max="5" required style="width:60px; padding:5px;">
                                <button type="submit" name="submit_rating" class="fr__btn" style="height:45px;">Submit</button>
                            </form>
                        </div>
                        <?php } else { ?>
                        <p><a href="login.php">Login</a> to rate this product.</p>
                        <?php } ?>
                            <div class="sin__desc">
                                <p><span>Qty:</span>
                                    <select id="qty">
                                        <?php for($i=1;$i<=10;$i++) { ?>
                                        <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                        <?php } ?>
                                    </select>
                                </p>
                            </div>
                           
                            </div>
                            <a class="fr__btn" href="javascript:void(0)" onclick="manage_cart('<?php echo $get_product[0]['id']; ?>','add')">Add to cart</a>
                        </div>

                     

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End Product Details Area -->

<!-- Start Product Description -->
<section class="htc__produc__decription bg__white">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <ul class="pro__details__tab" role="tablist">
                    <li role="presentation" class="description active"><a href="#description" role="tab" data-toggle="tab">description</a></li>
                </ul>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="ht__pro__details__content">
                    <div role="tabpanel" id="description" class="pro__single__content tab-pane fade in active">
                        <div class="pro__tab__content__inner">
                            <p class="pro__info"><?php echo $get_product[0]['description']; ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End Product Description -->

<!-- Start Frequently Bought Together -->
<?php if(!empty($frequently_bought)) { ?>
<section class="ftr__product__area ptb--100 bg__white">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <div class="section__title--2 text-center">
                    <h2 class="title__line">Frequently Bought Together</h2>
                    <p>Customers who bought this item also purchased these products</p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="product__list clearfix mt--30">
                <?php foreach($frequently_bought as $product) { ?>
                <div class="col-md-4 col-lg-3 col-sm-4 col-xs-12">
                    <div class="category">
                        <div class="ht__cat__thumb">
                            <a href="product.php?id=<?php echo $product['id']?>">
                                <img src="media/product/<?php echo htmlspecialchars($product['image'])?>" alt="product images">
                            </a>
                        </div>
                        <div class="fr__hover__info">
                            <ul class="product__action">
                                <li><a href="javascript:void(0)" onclick="wishlist_manage('<?php echo $product['id']?>','add')"><i class="icon-heart icons"></i></a></li>
                                <li><a href="javascript:void(0)" onclick="manage_cart('<?php echo $product['id']?>','add')"><i class="icon-handbag icons"></i></a></li>
                            </ul>
                        </div>
                        <div class="fr__product__inner">
                            <h4><a href="product.php?id=<?php echo $product['id']?>"><?php echo $product['name']?></a></h4>
                            <ul class="fr__pro__prize">
                                <li class="old__prize">₹<?php echo $product['mrp']?></li>
                                <li>₹<?php echo $product['price']?></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</section>
<?php } ?>
<!-- End Frequently Bought Together -->

<!-- Start Similar Products -->
<?php if(!empty($similar_products)) { ?>
<section class="ftr__product__area ptb--100 bg__white">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <div class="section__title--2 text-center">
                    <h2 class="title__line">Similar Products</h2>
                    <p>Products you might also like</p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="product__list clearfix mt--30">
                <?php foreach($similar_products as $product) { ?>
                <div class="col-md-4 col-lg-3 col-sm-4 col-xs-12">
                    <div class="category">
                        <div class="ht__cat__thumb">
                            <a href="product.php?id=<?php echo $product['id']?>">
                                <img src="media/product/<?php echo htmlspecialchars($product['image'])?>" alt="product images">
                            </a>
                        </div>
                        <div class="fr__hover__info">
                            <ul class="product__action">
                                <li><a href="javascript:void(0)" onclick="wishlist_manage('<?php echo $product['id']?>','add')"><i class="icon-heart icons"></i></a></li>
                                <li><a href="javascript:void(0)" onclick="manage_cart('<?php echo $product['id']?>','add')"><i class="icon-handbag icons"></i></a></li>
                            </ul>
                        </div>
                        <div class="fr__product__inner">
                            <h4><a href="product.php?id=<?php echo $product['id']?>"><?php echo $product['name']?></a></h4>
                            <ul class="fr__pro__prize">
                                <li class="old__prize">₹<?php echo $product['mrp']?></li>
                                <li>₹<?php echo $product['price']?></li>
                            </ul>
                            <?php if($product['rating'] > 0) { ?>
                            <p style="font-size:12px; color:#999;">⭐ <?php echo $product['rating']?>/5</p>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</section>
<?php } ?>
<!-- End Similar Products -->

<!-- Start Upsell Products -->
<?php if(!empty($upsell_products)) { ?>
<section class="ftr__product__area ptb--100 bg__white">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <div class="section__title--2 text-center">
                    <h2 class="title__line">Upgrade Your Choice</h2>
                    <p>Premium alternatives with better features</p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="product__list clearfix mt--30">
                <?php foreach($upsell_products as $product) { ?>
                <div class="col-md-4 col-lg-3 col-sm-4 col-xs-12">
                    <div class="category">
                        <div class="ht__cat__thumb">
                            <a href="product.php?id=<?php echo $product['id']?>">
                                <img src="media/product/<?php echo htmlspecialchars($product['image'])?>" alt="product images">
                            </a>
                        </div>
                        <div class="fr__hover__info">
                            <ul class="product__action">
                                <li><a href="javascript:void(0)" onclick="wishlist_manage('<?php echo $product['id']?>','add')"><i class="icon-heart icons"></i></a></li>
                                <li><a href="javascript:void(0)" onclick="manage_cart('<?php echo $product['id']?>','add')"><i class="icon-handbag icons"></i></a></li>
                            </ul>
                        </div>
                        <div class="fr__product__inner">
                            <h4><a href="product.php?id=<?php echo $product['id']?>"><?php echo $product['name']?></a></h4>
                            <ul class="fr__pro__prize">
                                <li class="old__prize">₹<?php echo $product['mrp']?></li>
                                <li>₹<?php echo $product['price']?></li>
                            </ul>
                            <?php if($product['rating'] > 0) { ?>
                            <p style="font-size:12px; color:#999;">⭐ <?php echo $product['rating']?>/5</p>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</section>
<?php } ?>
<!-- End Upsell Products -->

<?php require('footer.php'); ?>
