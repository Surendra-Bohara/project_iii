<?php 
require('top.php');
require('function.inc.php');
require('algorithms.inc.php');

$str = mysqli_real_escape_string($conn, $_GET['str']);
$cat_id = isset($_GET['cat_id']) ? mysqli_real_escape_string($conn, $_GET['cat_id']) : '';

if($str != ''){
    // Use improved TF-IDF ranking for better search results
    $get_product = search_with_tf_idf_ranking($conn, $str, $cat_id, 20);
} else {
    ?>
    <script>
        window.location.href='index.php';
    </script>
    <?php
}                                       
?>
<div class="body__overlay"></div>

   <!-- Start Bradcaump area -->
       <div class="ht__bradcaump__area">
    <div class="ht__bradcaump__wrap">
      
            <div class="row align-items-center"> <!-- flex vertical align center -->
                <div class="col-xs-12 col-sm-6 col-md-6">
                    <!-- Image on left -->
                    <div class="bg-image">
                        <img src="images/bg/6.jpeg" alt="Banner Image">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-6">
                    <!-- Breadcrumb on right -->
                    <div class="bradcaump__inner">
                        <nav class="bradcaump-inner">
                            <a class="breadcrumb-item" href="index.php">Home</a>
                            <span class="brd-separetor"><i class="zmdi zmdi-chevron-right"></i></span>
                            <span class="breadcrumb-item active">Search Result</span>
                        </nav>
                    </div>
                </div>
            </div>
       
    </div>
</div>

        <!-- End Bradcaump area -->

<!-- Start Product Grid -->
<section class="htc__product__grid bg__white ptb--100">
    <div class="container">
        <div class="row">

            <?php if(count($get_product) > 0){ ?>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="htc__product__rightidebar">
                    <div style="margin-bottom: 20px;">
                        <h4>Search Results for "<?php echo htmlspecialchars($str); ?>"</h4>
                        <p style="color: #999;"><?php echo count($get_product); ?> products found</p>
                    </div>

                    <div class="row">
                        <div class="shop__grid__view__wrap">
                            <div role="tabpanel" id="grid-view" class="single-grid-view tab-pane fade in active clearfix">

                                <?php foreach($get_product as $list){ ?>
                                <!-- Start Single Category -->
                                <div class="col-md-4 col-lg-3 col-sm-4 col-xs-12">
                                    <div class="category">
                                        <div class="ht__cat__thumb">
                                            <a href="product.php?id=<?php echo $list['id'] ?>">
                                                <img src="Media/product/<?php echo htmlspecialchars($list['image']) ?>" alt="product images">
                                            </a>
                                        </div>
                                        <div class="fr__hover__info">
													<ul class="product__action">
														<li><a href="javascript:void(0)" onclick="wishlist_manage('<?php echo $list['id']?>','add')"><i class="icon-heart icons"></i></a></li>
														<li><a href="javascript:void(0)" onclick="manage_cart('<?php echo $list['id']?>','add')"><i class="icon-handbag icons"></i></a></li>
													</ul>
												</div>
                                        
                                        <div class="fr__product__inner">
                                            <h4><a href="product.php?id=<?php echo $list['id'] ?>"><?php echo htmlspecialchars($list['name']) ?></a></h4>
                                            <ul class="fr__pro__prize">
                                                <li class="old__prize">₹<?php echo $list['mrp'] ?></li>
                                                <li>₹<?php echo $list['price'] ?></li>
                                            </ul>
                                            <?php if(isset($list['rating']) && $list['rating'] > 0) { ?>
                                            <p style="font-size:12px; color:#999;">⭐ <?php echo $list['rating']?>/5 | 👁 <?php echo $list['view_count']?> views</p>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <?php } ?>

                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <?php } else { 
                echo "Data not found";
            } ?>

        </div>
    </div>
</section>
<!-- End Product Grid -->

<?php require('footer.php') ?>
