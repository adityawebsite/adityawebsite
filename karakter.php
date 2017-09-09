<?php 
define( "ROOT" , __DIR__ );
require_once (ROOT. "/includes/Main.class.php");
require_once (ROOT. "/includes/config.php");
include(ROOT. "/themes/user/playheader.php");
$connection = mysqli_connect("localhost", "root", "");
// Seleksi Database
$db = mysqli_select_db($connection, "adityaw1_wp197");
	if(!isset($_SESSION)){
	  session_start();
	}
$id = isset($_GET["id"]) ? $_GET["id"] : "0";
$spemain = mysqli_query($connection, "SELECT * FROM pemain where id='$id'");
$gpemain = mysqli_fetch_array($spemain);
if($id == $gpemain["id"]) {
	$dapat = $gpemain["karakter"];
	$gkarakter = mysqli_query($connection, "select * from karakter");
	$skarakter = mysqli_fetch_array($gkarakter);
	if($dapat == "Werewolf"){
			$penjelasan = $skarakter["werewolf"];
		} elseif($dapat == "Lycan"){
			$penjelasan = $skarakter["lycan"];
		} elseif($dapat == "Guard"){
			$penjelasan = $skarakter["guard"];
		} elseif($dapat == "Seer"){
			$penjelasan = $skarakter["seer"];	
		} elseif($dapat == "Sorcerer"){
			$penjelasan = $skarakter["sorcerer"];
		} else {
			$penjelasan = $skarakter["villager"];
		}
}


?>
<section>
<div class="main-options clearfix">
	<div class="col-md-10 url-info">
	<?php echo Main::message() ?>
		<div class="centered form"> 
		<form role="form" class="live_form form" id="login_form" method="post" action=""> 
          <div class="form-group">
          <h2>Anda bermain sebagai <?php echo $dapat; ?></h2>
		 </div>
		 <div class="form-group">
         <label><?php echo $penjelasan; ?></label>
		 </div>
        <a class="btn btn-primary" href="/ww/play.php?id=<?php echo $gpemain["idroom"]; ?>">Kembali</a>
 	   </form>
	   </div>
	</div>
</div>
</section>
<?php include(ROOT. "/themes/footer.php"); ?>