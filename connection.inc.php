<?php
//Start session

define('SITEURL', 'http://localhost:808/ppp/');
define('LOCALHOST', 'localhost');
define('DB_USERNAME','root');
define('DB_PASSWORD', 'root');
define('DB_NAME','ppp');
$conn = mysqli_connect(LOCALHOST,DB_USERNAME,DB_PASSWORD) or die(mysqli_connect_error());
$db_select = mysqli_select_db($conn,DB_NAME) or die(mysqli_connect_error());


?>