<?php
require('top.inc.php');
$categories='';
$msg='';
$id ='';
 if(isset($_GET['id']) && $_GET['id']!=''){
      $id=get_safe_value($conn, $_GET['id']);
      $res=mysqli_query($conn,"select * from categories where id ='$id'");
      $check=mysqli_num_rows($res);
      if($check>0){
         $row=mysqli_fetch_assoc( $res);
         $categories=$row['categories'];

      }else{
         header('location:categories.php');
         die();
      }
   }

if(isset($_POST['submit'])){
   $categories=get_safe_value($conn, $_POST['categories']);
   $res=mysqli_query($conn,"select * from categories where categories='$categories' AND id!='$id'");
   $check=mysqli_num_rows($res);
   if($check>0){
      if(isset($_GET['id'])&& $_GET['id']!=''){
         $getData=mysqli_fetch_assoc($res);
         if($id==$getData['id']){
      }else{
     
         $msg="Category already exist";
             echo"
    <script>
    alert('Categories already exits');
    window.location.href= 'categories.php';
    </script>
    ";

      }
   }else{ 
      $msg="Category already exist";
         echo"
    <script>
    alert('Categories already exits');
    window.location.href= 'categories.php';
    </script>
    ";
   }
   }
   

   if($msg==''){

      if(isset($_GET['id']) && $_GET['id']!=''){
      mysqli_query($conn, "update categories set categories='$categories' where id='$id'");
       echo"
    <script>
    alert('Category updated successfully');
    window.location.href= 'categories.php';
    </script>";
      }else{
       mysqli_query($conn,"insert into categories(categories,status) values('$categories','1')");
          echo"
    <script>
    alert('Category added successfully');
    window.location.href= 'categories.php';
    </script>";
    exit;
    }

   }
}


?>
   <div class="content pb-0">
            <div class="animated fadeIn">
               <div class="row">
                  <div class="col-lg-12">
                     <div class="card">
                        <div class="card-header"><strong>Categories</strong><small> Form</small></div>
                        <form method="post">
                           <div class="card-body card-block">
                        <div class="form-group">
                        <label for="categories" class=" form-control-label">Categories</label>
                        <input type="text" name="categories"  placeholder="Enter categories name" class=
                        "form-control" required value="<?php echo  $categories ?>" >
                        </div>
                        <button name="submit" type="submit" class="btn btn-lg btn-info" >
                           <span id="payment-button-amount" name="submit" >Submit</span>
                        </button>
                           
                           
                        </div>
                        </form>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="clearfix"></div>
         <footer class="site-footer">
            <div class="footer-inner bg-white">
               <div class="row">
                  <div class="col-sm-6">
                     Copyright &copy; 2018 Ela Admin
                  </div>
                  <div class="col-sm-6 text-right">
                     Designed by <a href="https://colorlib.com/">Colorlib</a>
                  </div>
               </div>
            </div>
         </footer>
      </div>
      <script src="assets/js/vendor/jquery-2.1.4.min.js" type="text/javascript"></script>
      <script src="assets/js/popper.min.js" type="text/javascript"></script>
      <script src="assets/js/plugins.js" type="text/javascript"></script>
      <script src="assets/js/main.js" type="text/javascript"></script>
   </body>
</html>     

<?php
require('footer.inc.php');
?>
        