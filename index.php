<?php
session_start();

if (!isset($_SESSION['USER_LOGIN'])) {
    header("Location: login.php");
    exit();
}
require('top.php');
include_once('function.inc.php');
include_once('algorithms.inc.php');
?>
        <div class="body__overlay"></div>
        <!-- Start Offset Wrapper -->
        <div class="offset__wrapper">
            <!-- Start Search Popap -->
            <div class="search__area">
                <div class="container" >
                    <div class="row" >
                        <div class="col-md-12" >
                            <div class="search__inner">
                                <form action="#" method="get">
                                    <input placeholder="Search here... " type="text">
                                    <button type="submit"></button>
                                </form>
                                <div class="search__close__btn">
                                    <span class="search__close__btn_icon"><i class="zmdi zmdi-close"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Search Popap -->
            <!-- Start Cart Panel -->
            <div class="shopping__cart">
                <div class="shopping__cart__inner">
                    <div class="offsetmenu__close__btn">
                        <a href="#"><i class="zmdi zmdi-close"></i></a>
                    </div>
                    <div class="shp__cart__wrap">
                        <div class="shp__single__product">
                            <div class="shp__pro__thumb">
                                <a href="#">
                                    <img src="images/product-2/sm-smg/1.jpg" alt="product images">
                                </a>
                            </div>
                            <div class="shp__pro__details">
                                <h2><a href="product-details.html">BO&Play Wireless Speaker</a></h2>
                                <span class="quantity">QTY: 1</span>
                                <span class="shp__price">$105.00</span>
                            </div>
                            <div class="remove__btn">
                                <a href="#" title="Remove this item"><i class="zmdi zmdi-close"></i></a>
                            </div>
                        </div>
                        <div class="shp__single__product">
                            <div class="shp__pro__thumb">
                                <a href="#">
                                    <img src="images/product-2/sm-smg/2.jpg" alt="product images">
                                </a>
                            </div>
                            <div class="shp__pro__details">
                                <h2><a href="product-details.html">Brone Candle</a></h2>
                                <span class="quantity">QTY: 1</span>
                                <span class="shp__price">$25.00</span>
                            </div>
                            <div class="remove__btn">
                                <a href="#" title="Remove this item"><i class="zmdi zmdi-close"></i></a>
                            </div>
                        </div>
                    </div>
                    <ul class="shoping__total">
                        <li class="subtotal">Subtotal:</li>
                        <li class="total__price">$130.00</li>
                    </ul>
                    <ul class="shopping__btn">
                        <li><a href="cart.html">View Cart</a></li>
                        <li class="shp__checkout"><a href="checkout.html">Checkout</a></li>
                    </ul>
                </div>
            </div>
            <!-- End Cart Panel -->
        </div>
        <!-- End Offset Wrapper -->
       
        <!-- Start Category Area -->
        <section class="htc__category__area ptb--100">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="section__title--2 text-center">
                            <h2 class="title__line">New Arrivals</h2>
                           <p>Check out the latest products just added to our store.</p>

                        </div>
                    </div>
                </div>
                <div class="htc__product__container">
                    <div class="row">
                        <div class="product__list clearfix mt--30">
                           
                              <?php
                              $get_product = get_product($conn,4);
                              if(!empty($get_product)&& is_array($get_product)){
                              foreach($get_product as $list){
                              ?>
                            
                            <!-- Start Single Category -->
                            <div class="col-md-4 col-lg-3 col-sm-4 col-xs-12">
                                <div class="category">
                                    <div class="ht__cat__thumb">
                                       <a href="product.php?id=<?php echo $list['id']; ?>">
                                          <img src="Media/product/<?php echo htmlspecialchars($list['image']); ?>" alt="product image">


                                        </a>
                                    </div>
                                    <div class="fr__hover__info">
                                        <ul class="product__action">
                                           <li><a href="javascript:void(0)" onclick="wishlist_manage('<?php echo $list['id']?>','add')"><i class="icon-heart icons"></i></a></li>

                                            <li><a href="javascript:void(0)" onclick="manage_cart('<?php echo $list['id']?>','add')"><i class="icon-handbag icons"></i></a></li>

                                            <li><a href="#"><i class="icon-shuffle icons"></i></a></li>
                                        </ul>
                                    </div>
                                    <div class="fr__product__inner">
                                        <h4><a href="product-details.html"><?php echo $list['name']?> </a></h4>
                                        <ul class="fr__pro__prize">
                                            <li class="old__prize"><?php echo $list['mrp']?> </li>
                                            <li> <?php echo $list['price']?> </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <?php
                            }
                        }else{
                            echo "<p>NO products found.</p>";

                        }
                            ?>
                            <!-- End Single Category -->
                           
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- End Category Area -->
        <!-- Start Product Area -->
        <section class="ftr__product__area ptb--100">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="section__title--2 text-center">
                            <h2 class="title__line">Best Seller</h2>
                            <p>Our top-selling products loved by customers</p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="product__list clearfix mt--30">
							<?php
							$get_product=get_product($conn,4,'','','','','yes');
							foreach($get_product as $list){
							?>
                            <!-- Start Single Category -->
                            <div class="col-md-4 col-lg-3 col-sm-4 col-xs-12">
                                <div class="category">
                                    <div class="ht__cat__thumb">
                                        <a href="product.php?id=<?php echo $list['id']?>">
                                            <img src="Media/product/<?php echo $list['image']?>" alt="product images">
                                        </a>
                                    </div>
                                    <div class="fr__hover__info">
										<ul class="product__action">
											<li><a href="javascript:void(0)" onclick="wishlist_manage('<?php echo $list['id']?>','add')"><i class="icon-heart icons"></i></a></li>
											<li><a href="javascript:void(0)" onclick="manage_cart('<?php echo $list['id']?>','add')"><i class="icon-handbag icons"></i></a></li>
										</ul>
									</div>
                                    <div class="fr__product__inner">
                                        <h4><a href="product.php?id=<?php echo $list['id']?>"><?php echo $list['name']?></a></h4>
                                        <ul class="fr__pro__prize">
                                            <li class="old__prize"><?php echo $list['mrp']?></li>
                                            <li><?php echo $list['price']?></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <!-- End Single Category -->
							<?php } ?>
                        </div>
                </div>
            </div>
        </section>
        <!-- End Product Area -->
        
        <!-- START: POPULAR PRODUCTS (Most Viewed & Rated) -->
        <section class="ftr__product__area ptb--100 bg__white">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="section__title--2 text-center">
                            <h2 class="title__line">Popular Products</h2>
                            <p>Most viewed and highly rated by customers</p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="product__list clearfix mt--30">
                        <?php
                        $popular = get_popular_products($conn, 4);
                        foreach($popular as $product) {
                        ?>
                        <div class="col-md-4 col-lg-3 col-sm-4 col-xs-12">
                            <div class="category">
                                <div class="ht__cat__thumb">
                                    <a href="product.php?id=<?php echo $product['id']?>">
                                        <img src="Media/product/<?php echo htmlspecialchars($product['image'])?>" alt="product images">
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
                                    <p style="font-size:12px; color:#999;">⭐ <?php echo $product['rating']?>/5 | 👁 <?php echo $product['view_count']?> views</p>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        <?php
                     } ?>
                    </div>
                </div>
            </div>
        </section>
        <!-- END: POPULAR PRODUCTS -->
         
    

        
        <!-- START: PERSONALIZED RECOMMENDATIONS (if user logged in) -->
        <?php if(isset($_SESSION['USER_ID'])) {
            $uid = $_SESSION['USER_ID'];
            $recommendations = get_recommendations_collaborative_filtering($conn, $uid, 4);
            if(!empty($recommendations)) {
        ?>
        <section class="ftr__product__area ptb--100">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="section__title--2 text-center">
                            <h2 class="title__line">Recommended For You</h2>
                            <p>Based on your shopping preferences</p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="product__list clearfix mt--30">
                        <?php foreach($recommendations as $product) { ?>
                        <div class="col-md-4 col-lg-3 col-sm-4 col-xs-12">
                            <div class="category">
                                <div class="ht__cat__thumb">
                                    <a href="product.php?id=<?php echo $product['id']?>">
                                        <img src="Media/product/<?php echo htmlspecialchars($product['image'])?>" alt="product images">
                                    </a>
                                </div>
                                <div class="fr__hover__info">
                                    <ul class="product__action">
                                        <li><a href="javascript:void(0)" onclick="wishlist_manage('<?php echo $product['id']?>','add')"><i class="icon-heart icons"></i></a></li>
                                        <li><a href="javascript:void(0)" onclick="manage_cart('<?php echo $product['id']?>','add')"><i class="icon-handbag icons"></i></a></li>
                                    </ul>
                                </div>
                                <div class="fr__product__inner">
                                    <h4><a href="product.php?id=<?php echo $product['id']?>"><?php echo htmlspecialchars($product['name'])?></a></h4>
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
        <?php } } ?>
        <!-- END: PERSONALIZED RECOMMENDATIONS -->
        
        <?php
        require('footer.php');

        ?>