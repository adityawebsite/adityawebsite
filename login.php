<?php
define( "ROOT" , __DIR__ );
require_once (ROOT. "/includes/Main.class.php");
require_once (ROOT. "/includes/config.php");
$error=''; // Variabel untuk menyimpan pesan error
if(isset($_SESSION['login'])){
header("location: user.php");
}

$action = isset($_GET["action"]) ? $_GET["action"] : "user";

switch ($action) {
  		case 'logout' :
		session_destroy(); // Menghapus Sessions
		return Main::redirect(array("success",e("Logout Berhasil.")));
	case 'user' :
	default:	

if (isset($_POST['submit'])) {
	if (empty($_POST['username']) || empty($_POST['password'])) {
			return Main::redirect(array("danger",e("Email atau password belum terisi.")));
	}  else {
		// Variabel username dan password
		$username = $_POST['username'];
		$password = $_POST["password"];
		// Membangun koneksi ke database
		$connection = mysqli_connect("173.208.189.34", "adityaw1_wp197", "Putuaditya97");
		// Mencegah MySQL injection 
		// Seleksi Database
		$db = mysqli_select_db($connection, "adityaw1_wp197");
		// SQL query untuk memeriksa apakah karyawan terdapat di database?
		$query = mysqli_query($connection, "select * from user");
		$row = mysqli_fetch_array($query);
		if($password == $row["password"]) {
				$_SESSION['login']=$username; // Membuat Sesi/session
				header("location: login.php"); // Mengarahkan ke halaman profil
				} 
			return Main::redirect(array("danger",e("Email atau Password salah")),"/login.php");
		}
}
break;
}


?>
<!DOCTYPE html>
<html lang="en" prefix="og: http://ogp.me/ns#">
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">    
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, maximum-scale=1.0" />  
    <meta name="description" content="Play werewolf online" />
	<!-- Bootstrap core CSS -->
    <link href="/static/css/bootstrap.min.css" rel="stylesheet">
    <!-- Component CSS -->
    <link rel="stylesheet" type="text/css" href="/themes/style.css">
    <link rel="stylesheet" type="text/css" href="/static/css/components.min.css">
	<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js?v=2.0.3"></script>
    <script type="text/javascript" src="/static/bootstrap.min.js"></script>
    <script type="text/javascript" src="/static/js/zclip.js"></script>
    <script type="text/javascript" src="/static/application.fn.js?v=1.0"></script>
    <script type="text/javascript" src="/static/application.js?v=1.0"></script>  
	<!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
	<title>Werewolf - Aditya Website</title>
</head>
<body>
<section>
	<div class="container">    
		<div class="centered form">      
      <div class="site_logo">
          <a href="/ww"><img src="/content/auto_site_logo.png" alt="Aditya Website"></a>
	  </div>
	  <?php echo Main::message() ?>
      <form role="form" class="live_form form" id="login_form" method="post" action="/login.php">        
        <div class="form-group">
          <label for="email">Email or Username
          </label>
          <input type="text" class="form-control" id="email" placeholder="Enter email" name="username">
        </div>
        <div class="form-group">
          <label for="pass">Password</label>
          <input type="password" class="form-control" id="pass" placeholder="Password" name="password">
        </div>         
        <div class="form-group">
        </div>                  
        <button type="submit" name="submit" class="btn btn-primary">Login</button>
      </form>    
		</div>
	</div>
</section>
</body>
</html>
