<?php
$msg='';
require('top.inc.php');
if(isset($_GET['type']) && $_GET['type']!=''){
   $type=get_safe_value($conn,$_GET['type']);
   if($type=='status'){
      $operation=get_safe_value($conn,$_GET['operation']);
      $id=get_safe_value($conn,$_GET['id']); 
      if($operation=='active'){
         $status='1';

      }else{
         $status='0';
      }
      $update_status_sql="update product set status='$status' where id='$id'";
      mysqli_query($conn,$update_status_sql);
   }
    if($type=='delete'){
      $id=get_safe_value($conn,$_GET['id']); 
      $delete_sql="delete from product where id='$id'";
      mysqli_query($conn,$delete_sql);
   }
}
$sql = "SELECT product.*, categories.categories 
        FROM product, categories 
        WHERE product.categories_id = categories.id 
        ORDER BY product.id DESC";

$res = mysqli_query($conn, $sql);

?>
<div class="content pb-0">
    <div class="orders">
         <div class="row">
             <div class="col-xl-12">
                 <div class="card">
                     <div class="card-body">
                         <h4 class="box-title">Products </h4>
                        </div>
                        <div class="card-body--">
                           <div class="table-stats order-table ov-h">
                              <table class="table ">
                                 <thead>
                                    <tr>
                                       <th class="serial">#</th>
                                       <th>ID</th>
                                       <th>Categories</th>
                                       <th>Name</th>
                                       <th>Image</th>
                                       <th>Mrp</th>
                                       <th>Price</th>
                                       <th>Quantity</th>
                                    </tr>
                                 </thead>
                                 <tbody>
                                    <?php
                                    $i=1;
                                     while ($row=mysqli_fetch_assoc($res)) {?>
                                    <tr>
                                        <td class="serial"><?php echo $i?></td>
                                        <td><?php echo $row['id']?></td>
                                        <td><?php echo $row['categories']?></td>
                                        <td><?php echo $row['name']?></td>
                                        <td><img src="../Media/product/<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>"/></td>
                                        <td><?php echo $row['mrp']?></td>
                                        <td><?php echo $row['price']?></td>
                                        <td class="text-center"><?php echo $row['qty']; ?></td>

                                        <td><?php 
                                        if($row['status']==1){
                                          echo "<span class='badge badge-complete'><a href='?type=status&operation=deactive&id=".$row['id']."'>ACTIVE</a></span> &nbsp";
                                        }else{
                                          echo "<span class='badge badge-pending'><a href='?type=status&operation=active&id=".$row['id']."'>DEACTIVE</a> </span> &nbsp";
                                        }
                                        echo "<span class='badge badge-edit'> <a href='manage_product.php?type=edit&id=".$row['id']."'>Edit</a></span> &nbsp";
                                        echo "<span class='badge badge-delete'><a href='?type=delete&id=".$row['id']."'>DELETE</a></span> &nbsp";
                                        
                                        ?>
                                         </td>
                                    </tr>
                                    <?php
                                     $i++;
                                  } ?>
                                    
                                   
                                   
                                   
                                   
                                 </tbody>
                              </table>
                              <?php if($msg != ""){ ?>
   <div style="margin-top:10px; font-weight:bold; color:green;">
      <?php echo $msg; ?>
   </div>
<?php } ?>
                               
                           </div>
                           
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <h4 class="box-link"><a href="manage_product.php">Add Products</a>  </h4>
		  </div>
       

<?php
require('footer.inc.php');
?>
        