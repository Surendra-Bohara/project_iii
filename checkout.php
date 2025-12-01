<?php
require('top.php');
include_once('function.inc.php'); 
if(!isset($_SESSION['cart']) || count($_SESSION['cart'])==0){
   ?>
    <script>
        window.location.href='index.php';
    </script>
   <?php
}
$cart_total=0;
foreach($_SESSION['cart'] as $key=>$val){
    $productArr=get_product($conn, '', '', $key);
    $price = (float)$productArr[0]['price'];
$qty   = (int)$val['qty'];

$cart_total = $cart_total + ($price * $qty);

    

}


if(isset($_POST['submit'])){
  $address= get_safe_value($conn, $_POST['address']);
  $city= get_safe_value($conn, $_POST['city']);
  $pincode= get_safe_value($conn, $_POST['pincode']);
  $payment_type= get_safe_value($conn, $_POST['payment_type']);
  $user_id= $_SESSION['USER_ID'];
  $total_price=$cart_total;
  $payment_status='pending';
  if($payment_type=='cod'){
     $payment_status='success';
  }
  $order_status='1';
  $added_on = date('Y-m-d H:i:s');
 
mysqli_query($conn, "INSERT INTO `order`(user_id, address, city, pincode, payment_type, payment_status, order_status, total_price, added_on)
VALUES('$user_id', '$address', '$city', '$pincode', '$payment_type', '$payment_status', '$order_status', '$total_price', '$added_on')");

$order_id=mysqli_insert_id($conn);

foreach($_SESSION['cart'] as $key=>$val){
    $productArr=get_product($conn, '', '', $key);
    $price=$productArr[0]['price'];
    $qty=$val['qty'];
  

    mysqli_query($conn, "INSERT INTO order_detail(order_id, product_id, qty, price)
    VALUES('$order_id',  '$key', '$qty', '$price')");
   
}
 unset($_SESSION['cart'])
    ?>
    <script>
        window.location.href='thank_you.php';
    </script>

    <?php

}

?>
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
                            <span class="breadcrumb-item active">Checkout</span>
                        </nav>
                    </div>
                </div>
            </div>
       
    </div>
</div>

        <!-- End Bradcaump area -->
        <!-- cart-main-area start -->
        <div class="checkout-wrap ptb--100">
            <div class="container">
                <div class="row">
                    <div class="col-md-8">
                        <div class="checkout__inner">
                            <div class="accordion-list">
                                <div class="accordion">
                                   
                                
                                    <div class="accordion__title">
                                        Checkout Method
                                    </div>
                                    
                                    <div class="accordion__title">
                                         
                                        Address Information
                                    </div>
                                    <form method="post">
                                    <div class="accordion__body">
                                        <div class="bilinfo">
                                           
                                                <div class="row">
                                                    <div class="col-md-12">
                                                       
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="single-input">
                                                            <input type="text" name="address" placeholder="Street Address" required>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-md-6">
                                                        <div class="single-input">
                                                            <input type="text" name="city" placeholder="City/State" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="single-input">
                                                            <input type="text" name="pincode" placeholder="Post code/ zip" required >
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                       
                                                    </div>
                                                    
                                                </div>
                                            
                                        </div>
                                    </div>
                                    <div class="accordion__title">
                                        payment information
                                    </div>
                                    <div class="accordion__body">
                                        <div class="paymentinfo">
                                            <div class="single-method">
                                                COD <input type="radio" name="payment_type" value="COD" required>
                                               &nbsp;&nbsp;e-Sewa <input type="radio" name="payment_type" value="Esewa" required>
                                            </div>
                                             
                                           
                                        </div>
                                    </div>
                                     <input type="submit" name="submit" >
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="order-details">
                            <h5 class="order-details__title">Your Order</h5>
                            <div class="order-details__item">
                                                        <?php
                                $cart_total=0;
                                foreach($_SESSION['cart'] as $key=>$val){
                                    $productArr=get_product($conn, '', '', $key);
                                    $pname=$productArr[0]['name'];
                                    $mrp=$productArr[0]['mrp'];
                                    $price = (int)$productArr[0]['price'];
                                    $image=$productArr[0]['image'];
                                    $cart_total= $cart_total+($price*$qty);
                                 
                                    $qty   = (int)$val['qty'];

                                        $cart_total = $cart_total + ($price * $qty);

                                     
                            ?>
                                <div class="single-item">
                                    <div class="single-item__thumb">
                                       <img src="Media/product/<?php echo $image;?>" alt="product img" /></a></td>
                                    </div>
                                    <div class="single-item__content">
                                        <a href="#"><?php echo $pname ?></a>
                                        <span class="price"><?php echo $price * $qty ?></span>
                                    </div>
                                    <div class="single-item__remove">
                                        <td class="product-remove"><a href="javascript:void(0)" onclick="manage_cart('<?php echo $key?>','remove')"><i class="icon-trash icons"></i></a></td>
                                       
                                    </div>
                                </div>
                                 <?php } ?>
                            </div>
                            <div class="ordre-details__total">
                                <h5>Order total</h5>
                                <span class="price"><?php echo $cart_total ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- cart-main-area end -->
         <?php
         require('footer.php');

         ?>