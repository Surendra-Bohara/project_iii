<?php 
require('top.php');
require('function.inc.php');
if(!isset($_SESSION['USER_LOGIN'])){
	?>
	<script>
	window.location.href='index.php';
	</script>
	<?php
}
$order_id=get_safe_value($conn,$_GET['id']);
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
                        <span class="breadcrumb-item active">Order Details</span>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

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
                                        <th class="product-thumbnail">Product Name</th>
                                        <th class="product-thumbnail">Product Image</th>
                                        <th class="product-name">Qty</th>
                                        <th class="product-price">Price</th>
                                        <th class="product-price">Total Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $uid = $_SESSION['USER_ID'];
                                    
                                    // Debug: Check if we have the order_id and user_id
                                    echo "<!-- Debug: Order ID: $order_id, User ID: $uid -->";
                                    
                                    // Modified SQL query - removed DISTINCT and simplified joins
                                    $res = mysqli_query($conn, "SELECT od.*, p.name, p.image 
                                                              FROM order_detail od 
                                                              JOIN product p ON od.product_id = p.id 
                                                              JOIN `order` o ON od.order_id = o.id 
                                                              WHERE od.order_id = '$order_id' 
                                                              AND o.user_id = '$uid'");
                                    
                                    if(!$res) {
                                        echo "<!-- SQL Error: " . mysqli_error($conn) . " -->";
                                    }
                                    
                                    $total_price = 0;
                                    $row_count = 0;
                                    
                                    while($row = mysqli_fetch_assoc($res)) {
                                        $row_count++;
                                        $total_price = $total_price + ($row['qty'] * $row['price']);
                                        
                                        // Debug each row
                                        echo "<!-- Debug Row: ";
                                        print_r($row);
                                        echo " -->";
                                    ?>
                                    <tr>
                                        <td class="product-name"><?php echo $row['name']?></td>
                                        <td class="product-name">
                                            <?php 
                                            // Check if image exists and display it
                                            if(!empty($row['image'])) {
                                                echo '<img src="Media/product/' . $row['image'] . '" alt="' . $row['name'] . '" width="50">';
                                            } else {
                                                echo 'No Image';
                                            }
                                            ?>
                                        </td>
                                        <td class="product-name"><?php echo $row['qty']?></td>
                                        <td class="product-name">₹<?php echo $row['price']?></td>
                                        <td class="product-name">₹<?php echo $row['qty'] * $row['price']?></td>
                                    </tr>
                                    <?php } ?>
                                    
                                    <?php if($row_count == 0) { ?>
                                    <tr>
                                        <td colspan="5" class="product-name" style="text-align:center;">
                                            No order details found or you don't have permission to view this order.
                                        </td>
                                    </tr>
                                    <?php } else { ?>
                                    <tr>
                                        <td colspan="4" class="product-name" style="text-align:right; font-weight:bold;">Total Price:</td>
                                        <td class="product-name" style="font-weight:bold;">₹<?php echo $total_price?></td>
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

<?php require('footer.php')?>