<?php
include("login.php"); // Memasuk-kan skrip Login 
 
if(isset($_SESSION['login'])){
header("location: /ww/user.php");
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
    <link href="/ww/static/css/bootstrap.min.css" rel="stylesheet">
    <!-- Component CSS -->
    <link rel="stylesheet" type="text/css" href="/ww/themes/style.css">
    <link rel="stylesheet" type="text/css" href="/ww/static/css/components.min.css">
	<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js?v=2.0.3"></script>
    <script type="text/javascript" src="/ww/static/bootstrap.min.js"></script>
    <script type="text/javascript" src="/ww/static/js/zclip.js"></script>
    <script type="text/javascript" src="/ww/static/application.fn.js?v=1.0"></script>
    <script type="text/javascript" src="/ww/static/application.js?v=1.0"></script>  
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
          <a href="/ww"><img src="/ww/content/auto_site_logo.png" alt="Aditya Website"></a>
	  </div>
      <form role="form" class="live_form form" id="login_form" method="post" action="">        
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