   <?php
   require('top.php');
   ?>   
      
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
                            <span class="breadcrumb-item active">MyOrder</span>
                        </nav>
                    </div>
                </div>
            </div>
       
    </div>
</div>
        <!-- End Bradcaump area -->
 <!-- wishlist-area start -->
        <div class="wishlist-area ptb--100 bg__white">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="wishlist-content">
                            <form action="#">
                                <div class="wishlist-table table-responsive">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th class="product-remove"><span class="nobr">Order ID</span></th>
                                                <th class="product-thumbnail">Order Date</th>
                                                <th class="product-name"><span class="nobr">Address</span></th>
                                                <th class="product-price"><span class="nobr"> Payment Type</span></th>
                                                <th class="product-stock-stauts"><span class="nobr"> Payment Status </span></th>
                                                <th class="product-add-to-cart"><span class="nobr">Order Status</span></th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $uid=$_SESSION['USER_ID'];
                                            $res= mysqli_query($conn, "select `order`.*,order_status.name as order_status_str from
                                             `order`,order_status where user_id='$uid' and order_status.id=`order`.order_status ");
                                            while($row=mysqli_fetch_assoc($res)){

                                            ?>
                                            <tr>
                                                <td class="product-add-to-cart"><a href="my_order_detail.php?id=<?php echo $row['id']; ?>"><?php echo $row['id']; ?></a></td>
                                                <td class="product-name"> <?php echo $row['added_on'] ?> </a></td>
                                                <td class="product-name">
                                                <?php echo $row['address'] ?> <br/>
                                                <?php echo $row['city'] ?> <br/>
                                                <?php echo $row['pincode'] ?> 
                                                </td>
                                                <td class="product-name"> <?php echo $row['payment_type'] ?> </a></td>
                                                <td class="product-name"> <?php echo $row['payment_status'] ?> </td>
                                                <td class="product-name"> <?php echo $row['order_status_str'] ?> </td>
                                               
                                                
                                            </tr>
                                            <?php } ?>
                                            
                                        </tbody>
                                      
                                    </table>
                                </div>  
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- wishlist-area end -->
   <?php
   require('footer.php');
?>