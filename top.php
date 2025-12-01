
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


require('connection.inc.php');
require_once('function.inc.php');
require('add_to_cart.inc.php');
$cat_res=mysqli_query($conn, "select * from categories where status=1 order by categories asc");
$cat_arr=array();
while($row=mysqli_fetch_assoc($cat_res)){
$cat_arr[]=$row;
}
$obj=new add_to_cart();
$totalProduct=$obj->totalProduct();
if(isset($_SESSION['USER_LOGIN'])){
     $uid=$_SESSION['USER_ID'];

    if(isset($_GET['wishlist_id'])){
    $wid=$_GET['wishlist_id'];
    mysqli_query($conn, "delete from wishlist where id='$wid' and user_id='$uid'");
}

   
$wishlist_count=mysqli_num_rows(mysqli_query($conn,"select product.name,product.image,product.price,product.mrp,wishlist.id from product,wishlist where wishlist.product_id=product.id and wishlist.user_id='$uid'"));
}
$script_name=$_SERVER['SCRIPT_NAME'];

$script_name_arr=explode('/',$script_name);
$mypage=$script_name_arr[count($script_name_arr)-1];

$meta_title="My Ecom Website";
$meta_desc="My Ecom Website";
$meta_keyword="My Ecom Website";
if($mypage=='product.php'){
	$product_id=get_safe_value($conn,$_GET['id']);
	$product_meta=mysqli_fetch_assoc(mysqli_query($conn,"select * from product where id='$product_id'"));
	$meta_title=$product_meta['meta_title'];
	$meta_desc=$product_meta['meta_desc'];
	$meta_keyword=$product_meta['meta_keyword'];
}if($mypage=='contact.php'){
	$meta_title='Contact Us';
}

?>
<!doctype html>
<html class="no-js" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
     <title><?php echo $meta_title?></title>
    <meta name="description" content="<?php echo $meta_desc?>">
	<meta name="keywords" content="<?php echo $meta_keyword?>">
   
    <!-- Place favicon.ico in the root directory -->
    <link rel="shortcut icon" type="image/x-icon" href="images/favicon.ico">
    <link rel="apple-touch-icon" href="apple-touch-icon.png">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <!-- Owl Carousel min css -->
    <link rel="stylesheet" href="css/owl.carousel.min.css">
    <link rel="stylesheet" href="css/owl.theme.default.min.css">
    <!-- This core.css file contents all plugings css file. -->
    <link rel="stylesheet" href="css/core.css">
    <!-- Theme shortcodes/elements style -->
    <link rel="stylesheet" href="css/shortcode/shortcodes.css">
    <link rel="stylesheet" href="style.css">
    <!-- Responsive css -->
    <link rel="stylesheet" href="css/responsive.css">
    <!-- User style -->
    <link rel="stylesheet" href="css/custom.css">
    <!-- Modernizr JS -->
    <script src="js/vendor/modernizr-3.5.0.min.js"></script>
</head>

<body>
    
    <!--[if lt IE 8]>
        <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
    <![endif]-->  

    <!-- Body main wrapper start -->
    <div class="wrapper">
        <!-- Start Header Style -->
        <header id="htc__header" class="htc__header__area header--one">
            <!-- Start Mainmenu Area -->
            <div id="sticky-header-with-topbar" class="mainmenu__wrap sticky__header">
                <div class="container">
                    <div class="row">
                        <div class="menumenu__container clearfix">
                            <div class="col-lg-2 col-md-2 col-sm-3 col-xs-5"> 
                                <div class="logo">
                                     <a href="index.php"><img src="images/logo/10.png" alt="logo mages"></a>
                                </div>
                            </div>
                            <div class="col-md-7 col-lg-6 col-sm-5 col-xs-3">
                                <nav class="main__menu__nav hidden-xs hidden-sm">
                                    <ul class="main__menu">
                                        <li class="drop"><a href="index.php">Home</a></li>
                                        <?php
                                        foreach($cat_arr as $list){
                                            ?>
                                            <li><a href="categories.php?id=<?php echo $list['id']?>">
                                            <?php
                                            echo htmlspecialchars($list['categories']) ?> </a> </li> 
                                            <?php
                                        }
                                        ?>
                                        
                                       
                                       
                                        
                                        </li>
                                        <li><a href="contact.php">contact</a></li>
                                    </ul>
                               
                                                <!-- SEARCH FORM INLINE WITH ICON -->
<div style="width: 100%; text-align: center; margin: 15px 0;">
    <form action="search.php" method="get" style="display: inline-flex; align-items: center;">
        <input 
            type="text" 
            name="str" 
            placeholder="Search products..." 
            value="<?php echo isset($_GET['str']) ? htmlspecialchars($_GET['str']) : '' ?>" 
            required
            style="padding: 8px 12px; font-size: 14px; border: 1px solid #ccc; border-radius: 4px 0 0 4px; outline: none; width: 250px;"
        >
        <button 
            type="submit" 
            style="padding: 8px 12px; font-size: 14px; background-color: #333; color: white; border: 1px solid #333; border-radius: 0 4px 4px 0; cursor: pointer;">
            <i class="fa fa-search"></i>
        </button>
    </form>
</div>


                                </nav>


                                <div class="mobile-menu clearfix visible-xs visible-sm">
                                    <nav id="mobile_dropdown">
                                        <ul>
                                            <li><a href="index.php">Home</a></li>
                                            <?php
                                        foreach($cat_arr as $list){
                                            ?>
                                            <li><a href="categories.php?id=<?php echo $list['categories']?>">
                                            <?php
                                            echo $list['categories']?> </a> </li>
                                            <?php
                                        }
                                        ?>
                                           
                                            <li><a href="contact.php">contact</a></li>
                                        </ul>
                                    </nav>
                                </div>  
                            </div>
                            <div class="col-md-3 col-lg-4 col-sm-4 col-xs-4">
                               <div class="header__right">
                                        <!-- LOGIN / REGISTER LINKS -->
                                        <div class="header__account">
                                            <?php if(isset($_SESSION['USER_LOGIN'])){
                                                echo '<a href="logout.php"> Logout</a> <a href="my_order.php"> My Order</a>';

                                            }else{
                                                echo '<a href="login.php"> Login/Register</a>  ';
                                            }
                                            ?>
                                        </div>
                                        <?php 
                                        if(isset($_SESSION['USER_ID'])){

                                        
                                        ?>

                                    <div class="htc__shopping__cart">
                                        <a class="cart__menu" href="#"><i class="icon-heart icons"></i></a>
                                        <a href="wishlist.php"><span class="htc__wishlist"><?php echo $wishlist_count; ?> </span></a>
                                    
                                    <?php }?>

                                
                                        <a class="cart__menu" href="#"><i class="icon-handbag icons"></i></a>
                                        <a href="cart.php"><span class="htc__qua"><?php echo $totalProduct; ?> </span></a>
                                        
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="mobile-menu-area"></div>
                </div>
            </div>
            <!-- End Mainmenu Area -->
        </header>


        <!-- End Header Area -->

        