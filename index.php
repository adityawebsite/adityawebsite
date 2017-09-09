<?php 
define( "ROOT" , __DIR__ );
require_once (ROOT. "/includes/Main.class.php");
require_once (ROOT. "/includes/config.php");
$connection = mysqli_connect("173.208.189.34", "adityaw1_wp197", "Putuaditya97");
// Seleksi Database
$db = mysqli_select_db($connection, "adityaw1_wp197");
	if(!isset($_SESSION)){
	  session_start();
	}
$action = isset($_GET["action"]) ? $_GET["action"] : "index" ;
switch ($action) {
	case "reset" :
	include(ROOT. "/themes/user/loginheader.php");
	if(!isset($_SESSION["masuk"])) {
		return Main::redirect(array("success",e("Anda telah keluar")),"/ww/index.php");
	}
	if(isset($_POST["yakin"])){
		$nama = $_SESSION["masuk"];
		$dpemain = mysqli_query($connection, "DELETE from pemain where nama='$nama'");
		unset($_SESSION["masuk"]);
		unset($_SESSION["karakter"]);
		unset($_SESSION["idroom"]);
		return Main::redirect(array("success",e("Anda telah keluar")),"/ww/index.php");
	}
	?>
	<section>
	<div class="centered form">
	  	<form role="form" class="live_form form" id="login_form" method="post" action="/ww/index.php?action=reset">
		<div class="form-group">
		<h2>Apakah anda yakin? Data anda (Nama dan Karakter) akan direset pada room.</h2>
		</div>
		<table><tr>
		<td style="text-align:left"><button class="btn btn-primary" name="yakin" type="submit" >Yes</button></td>
		</tr></table>
	  	</form>
	</div>
	</section>
<?php 
	break;
	case "index" :
	default:
	if(isset($_SESSION['login'])){
		$error = "<div class='alert alert-success'>Login in</div>";
		header("location: user.php");
	}
	if(isset($_SESSION["idroom"])){
		$idroom = $_SESSION["idroom"]; 
		return Main::redirect("","/ww/play.php?id=".$idroom."");
	}
	include(ROOT."/themes/header.php"); 
	if(isset($_POST['submitnama'])) {
		if(!isset($_POST["idroom"])) {
			return Main::redirect(array("danger",e("ID room salah.")),"/ww/index.php");
		}
		$idroom = $_POST["idroom"];
		return Main::redirect("","/ww/play.php?id=".$idroom."");
		} ?>
		<section>
<div class="main-options clearfix">
<div class="col-md-10 url-info">
<?php echo Main::message() ?>
<div style="text-align:center" class="call-to-action">
    <a href="" class="btn btn-primary advanced">Enter room</a>
  	<div class="main-advanced slideup"> 
		<div class="centered form">
		<?php echo Main::message() ?>
  		<form role="form" class="live_form form" id="login_form" method="post" action="index.php">   
        <div class="form-group">
          <label for="email">ID Room*</label>
          <input type="text" class="form-control main-input" name="idroom" value="" placeholder="Id room">
		</div>
        <button type="submit" name="submitnama" class="btn btn-primary">Play</button>
      </form>  
 </div>
</div>
</div>
</div>
</section> <?php
		break;
}
?>
<?php include(ROOT. "/themes/footer.php"); ?>
