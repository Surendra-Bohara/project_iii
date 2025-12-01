<?php 

require('top.php');
require_once('connection.inc.php');




$login_msg = "";
$register_msg = "";

// ------------------------
// REGISTER
// ------------------------
if (isset($_POST['register'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $mobile = mysqli_real_escape_string($conn, $_POST['mobile']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $added_on = date('Y-m-d H:i:s');

    // Check email already exists
    $check = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");

    if (mysqli_num_rows($check) > 0) {
        echo"
        <script>
         alert('Email already Exists');
            window.location.href= 'login.php';
        </script>
        ";
    } 

       // Basic validation
    if ($name == "" || $email == "" || $mobile == "" || $password == "") {
        echo "<script>alert('All fields are required'); window.location.href='login.php';</script>";
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid Email Format'); window.location.href='login.php';</script>";
        exit;
    }

    if (!preg_match('/^[0-9]{10}$/', $mobile)) {
        echo "<script>alert('Mobile number must be 10 digits'); window.location.href='login.php';</script>";
        exit;
    }

    if (strlen($password_raw) < 6) {
        echo "<script>alert('Password must be at least 6 characters'); window.location.href='login.php';</script>";
        exit;
    }

    $password = password_hash($password_raw, PASSWORD_BCRYPT);
    $added_on = date('Y-m-d H:i:s');

    // Check email exists
    $check = mysqli_query($conn, "SELECT id FROM users WHERE email='$email'");

    if (mysqli_num_rows($check) > 0) {
        echo "<script>alert('Email already exists'); window.location.href='login.php';</script>";
        exit;
    }

    $insert = mysqli_query($conn, "INSERT INTO users(name,email,mobile,password,added_on)
        VALUES('$name','$email','$mobile','$password','$added_on')");

    if ($insert) {
        echo "<script>
        alert('Registration successful! Please login.');
         window.location.href='login.php';
         </script>";
    } else {
        echo "<script>
        alert('Error registering!');
         window.location.href='login.php';
         </script>";
    }
}
// ------------------------
// LOGIN
// ------------------------
if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, $_POST['login_email']);
    $password = $_POST['login_password'];

    $check = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");

    if (mysqli_num_rows($check) > 0) {
        $row = mysqli_fetch_assoc($check);
        


        if (password_verify($password, $row['password'])) {
            $_SESSION['USER_LOGIN'] = 'yes';
            $_SESSION['USER_ID'] = $row['id'];
            $_SESSION['USER_NAME'] = $row['name'];
             echo"
        <script>
         alert('login Successfullu!');
            window.location.href='index.php';
        </script>
        ";

            exit();
        } else {
             echo"
        <script>
         alert('invalid password or username');
            window.location.href= 'index.php';
        </script>
        ";
        }
    } else {
        $login_msg = "Email not found!";
    }
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
                            <span class="breadcrumb-item active">login</span>
                        </nav>
                    </div>
                </div>
            </div>
       
    </div>
</div>

        <!-- End Bradcaump area -->


        <!-- Start Contact Area -->
        <section class="htc__contact__area ptb--100 bg__white">
            <div class="container">
                <div class="row">
					<div class="col-md-6">
						<div class="contact-form-wrap mt--60">
							<div class="col-xs-12">
								<div class="contact-title">
									<h2 class="title__line--6">Login</h2>
								</div>
							</div>
							<div class="col-xs-12">
								<form id="login-form" method="post">
									<div class="single-contact-form">
										<div class="contact-box name">
											<input type="text" name="login_email" id="login_email"  placeholder="Your Email*" style="width:100%">
										</div>
									</div>
									<div class="single-contact-form">
										<div class="contact-box name">
											<input type="password"  name="login_password" id="login_password"  placeholder="Your Password*" style="width:100%">
										</div>
									</div>
									
									<div class="contact-btn">
										<button type="submit" name="login" value="login" class="fv-btn">Login</button>
									</div>
								</form>
								<div class="form-output">
									<p class="form-messege"></p>
								</div>
							</div>
						</div> 
                
				</div>
				

					<div class="col-md-6">
						<div class="contact-form-wrap mt--60">
							<div class="col-xs-12">
								<div class="contact-title">
									<h2 class="title__line--6">Register</h2>
								</div>
							</div>
							<div class="col-xs-12">
								<form method="post">
									<div class="single-contact-form">
										<div class="contact-box name">
											<input type="text" name="name" id="name" placeholder="Your Name*" style="width:100%">
										</div>
									</div>
									<div class="single-contact-form">
										<div class="contact-box name">
											<input type="text" name="email" id="email" placeholder="Your Email*" style="width:100%">
										</div>
									</div>
									<div class="single-contact-form">
										<div class="contact-box name">
											<input type="text" name="mobile" id="mobile" placeholder="Your Mobile*" style="width:100%">
										</div>
									</div>
									<div class="single-contact-form">
										<div class="contact-box name">
											<input type="password" name="password" id="password" placeholder="Your Password*" style="width:100%">
										</div>
									</div>
									
									<div class="contact-btn">
										<button type="submit" name="register" value="register"  class="fv-btn">Register</button>
									</div>
								</form>
								<div class="form-output">
									<p class="form-messege"></p>
								</div>
							</div>
						</div> 
                
				</div>
					
            </div>
        </section>
        <!-- End Contact Area -->
        <!-- End Banner Area -->
        <!-- Start Footer Area -->
        <footer id="htc__footer">
            <!-- Start Footer Widget -->
            <div class="footer__container bg__cat--1">
                <div class="container">
                    <div class="row">
                        <!-- Start Single Footer Widget -->
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="footer">
                                <h2 class="title__line--2">ABOUT US</h2>
                                <div class="ft__details">
                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim</p>
                                    <div class="ft__social__link">
                                        <ul class="social__link">
                                            <li><a href="#"><i class="icon-social-twitter icons"></i></a></li>

                                            <li><a href="#"><i class="icon-social-instagram icons"></i></a></li>

                                            <li><a href="#"><i class="icon-social-facebook icons"></i></a></li>

                                            <li><a href="#"><i class="icon-social-google icons"></i></a></li>

                                            <li><a href="#"><i class="icon-social-linkedin icons"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Single Footer Widget -->
                        <!-- Start Single Footer Widget -->
                        <div class="col-md-2 col-sm-6 col-xs-12 xmt-40">
                            <div class="footer">
                                <h2 class="title__line--2">information</h2>
                                <div class="ft__inner">
                                    <ul class="ft__list">
                                        <li><a href="#">About us</a></li>
                                        <li><a href="#">Delivery Information</a></li>
                                        <li><a href="#">Privacy & Policy</a></li>
                                        <li><a href="#">Terms  & Condition</a></li>
                                        <li><a href="#">Manufactures</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!-- End Single Footer Widget -->
                        <!-- Start Single Footer Widget -->
                        <div class="col-md-2 col-sm-6 col-xs-12 xmt-40 smt-40">
                            <div class="footer">
                                <h2 class="title__line--2">my account</h2>
                                <div class="ft__inner">
                                    <ul class="ft__list">
                                        <li><a href="#">My Account</a></li>
                                        <li><a href="cart.html">My Cart</a></li>
                                        <li><a href="#">Login</a></li>
                                        <li><a href="wishlist.html">Wishlist</a></li>
                                        <li><a href="checkout.html">Checkout</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!-- End Single Footer Widget -->
                        <!-- Start Single Footer Widget -->
                        <div class="col-md-2 col-sm-6 col-xs-12 xmt-40 smt-40">
                            <div class="footer">
                                <h2 class="title__line--2">Our service</h2>
                                <div class="ft__inner">
                                    <ul class="ft__list">
                                        <li><a href="#">My Account</a></li>
                                        <li><a href="cart.html">My Cart</a></li>
                                        <li><a href="#">Login</a></li>
                                        <li><a href="wishlist.html">Wishlist</a></li>
                                        <li><a href="checkout.html">Checkout</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!-- End Single Footer Widget -->
                        <!-- Start Single Footer Widget -->
                        <div class="col-md-3 col-sm-6 col-xs-12 xmt-40 smt-40">
                            <div class="footer">
                                <h2 class="title__line--2">NEWSLETTER </h2>
                                <div class="ft__inner">
                                    <div class="news__input">
                                        <input type="text" placeholder="Your Mail*">
                                        <div class="send__btn">
                                            <a class="fr__btn" href="#">Send Mail</a>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                        <!-- End Single Footer Widget -->
                    </div>
                </div>
            </div>
            <!-- End Footer Widget -->
            <!-- Start Copyright Area -->
            <div class="htc__copyright bg__cat--5">
                <div class="container">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="copyright__inner">
                                <p>Copyright© <a href="https://freethemescloud.com/">Free themes Cloud</a> 2018. All right reserved.</p>
                                <a href="#"><img src="images/others/shape/paypol.png" alt="payment images"></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Copyright Area -->
        </footer>
        <!-- End Footer Style -->
    </div>
    <!-- Body main wrapper end -->


<?php

require('footer.php');
?>