<?php
$nama = $_SESSION["masuk"];
$spemain = mysqli_query($connection, "select * from pemain where nama='$nama'");
$gpemain = mysqli_fetch_array($spemain);
$pesan="";
$connection = mysqli_connect("localhost", "root", "");
$db = mysqli_select_db($connection, "adityaw1_wp197");
$groom = mysqli_fetch_array(mysqli_query($connection, "select * from room where id='$id'"));
$pemain = mysqli_query($connection, "select * from pemain");

function menang() {
	$connection = mysqli_connect("localhost", "root", "");
	$db = mysqli_select_db($connection, "adityaw1_wp197");
	$idrooms = $_GET["id"];
	$groom = mysqli_fetch_array(mysqli_query($connection, "select * from room where id='$idrooms'"));
	$jahat1 = mysqli_query($connection, "select * from pemain where karakter='Werewolf' AND hidup='1' AND idroom='$idrooms'");
	$jahat2 = mysqli_query($connection, "select * from pemain where karakter='Sorcerer' AND hidup='1' AND idroom='$idrooms'");
	$baik1 = mysqli_query($connection, "select * from pemain where karakter='Villager' AND hidup='1' AND idroom='$idrooms'");
	$baik2 = mysqli_query($connection, "select * from pemain where karakter='Seer' AND hidup='1' AND idroom='$idrooms'");
	$baik3 = mysqli_query($connection, "select * from pemain where karakter='Guard' AND hidup='1' AND idroom='$idrooms'");
	$baik4 = mysqli_query($connection, "select * from pemain where karakter='Lycan' AND hidup='1' AND idroom='$idrooms'");
	$baik = mysqli_num_rows($baik1) + mysqli_num_rows($baik2) + mysqli_num_rows($baik3) + mysqli_num_rows($baik4);
	$jahat = mysqli_num_rows($jahat1) + mysqli_num_rows($jahat2);
	if($groom["mulai"] == 1) {
		if($baik <= $jahat) {
			$update = mysqli_query($connection, "update room set selesai='1', vote='0', malam='0', debat='0', vote2='0', hasilvote='0' where id='$idrooms'");
			?> <div class="alert alert-success">Game telah selesai. Tim Werewolf menang.</div> <?php
		} elseif ($jahat == 0){
			$update = mysqli_query($connection, "update room set selesai='1', vote='0', malam='0', debat='0', vote2='0', hasilvote='0' where id='$idrooms'");
			?><div class="alert alert-success">Game telah selesai. Tim Villager menang.</div><?php
		}
	}
}
function login(){
	if(isset($_SESSION["login"])) { 
		$id = $_GET["id"];
		$connection = mysqli_connect("localhost", "root", "");
		$db = mysqli_select_db($connection, "adityaw1_wp197");
		$groom = mysqli_fetch_array(mysqli_query($connection, "select * from room where id='$id'"));
		?>
			<table>
			  <tr>
		 	  <td style="text-align:left"><a class="btn btn-primary" href="/ww/play.php?action=adminmalam&id=<?php echo $id; ?>">1. Malam</a></td>
		 	  <td style="text-align:center"><a class="btn btn-primary" href="/ww/play.php?action=admindebat&id=<?php echo $id; ?>">2. Siang</a></td>
		  	  <td style="text-align:right"><a class="btn btn-primary" href="/ww/play.php?action=adminvote&id=<?php echo $id; ?>">3. Vote</a></td>
			  <?php if( $groom["hasilvote"] == 1) { ?>
			  <td style="text-align:right"><a class="btn btn-primary" href="/ww/play.php?action=vote2&id=<?php echo $id; ?>">3. Vote Lagi</a></td>
			  <?php } ?>
			  <?php if($groom["vote"] == 1 || $groom["vote2"] == 1) { ?>
			  <td style="text-align:right"><a class="btn btn-primary" href="/ww/play.php?action=hasilvote&id=<?php echo $id; ?>">4. Hasil Vote</a></td>
			  <?php } ?>
		 	  </tr>
		    </table>
		<?php
	}
}
function refresh(){
$id = $_GET["id"];
$connection = mysqli_connect("localhost", "root", "");
$db = mysqli_select_db($connection, "adityaw1_wp197");
$groom = mysqli_fetch_array(mysqli_query($connection, "select * from room where id='$id'"));
if($groom["selesai"] == 0 ) {
?>  <form action ="/ww/play.php?id=<?php echo $id; ?>" method="POST">
	<button class="btn btn-primary" name="yakin" type="submit">Refresh</button>
	</form> <?php
	$nama = $_SESSION["masuk"];
	$id = $_GET["id"];
	$gpemain = mysqli_fetch_array(mysqli_query($connection, "select * from pemain where nama='$nama'"));
	$pesan = mysqli_fetch_array(mysqli_query($connection, "select * from pesan where id='1'"));
	if(isset($_POST["yakin"])){
		if($groom["vote"] == "0" && $groom["malam"] == "0" && $groom["debat"] == "0" && $groom["hasilvote"] == "0" && $groom["vote2"] == "0" ) {
			return Main::redirect(array("danger",e("Menunggu room penuh.")),"/ww/play.php?id=".$groom['id']."");
		}
		if($groom["vote"] == 1) {
			if($gpemain["hidup"] == 0){
				return Main::redirect(array("danger",e("Anda sudah mati. (voting telah dibuka)")),"/ww/play.php?id=".$groom['id']."");
			}
			if($gpemain["milih"] == 1) {
					return Main::redirect(array("success",e("Anda sudah mem-vote. Tunggu hingga hasil vote ditampilkan.")),"/ww/play.php?id=".$groom['id']."");
			}
			return Main::redirect(array("danger",e("Voting telah dibuka, silahkan memilih pemain yang akan dibunuh.")),"/ww/play.php?id=".$groom['id']."");
		}
		if($groom["malam"] == 1) {
			if($gpemain["hidup"] == 0){
				return Main::redirect(array("danger",e("Anda sudah mati. (Hari sudah malam)")),"/ww/play.php?id=".$groom['id']."");
			}
			if($_SESSION["karakter"] == "Werewolf"){
				if($gpemain["bunuh"] == 0) {
					return Main::redirect(array("danger",e("Malam telah tiba, bunuhlah salah satu pemain.")),"/ww/play.php?id=".$groom['id']."");
				}
				return Main::redirect(array("success",e("Tunggu pagi hari tiba.")),"/ww/play.php?id=".$groom['id']."");
			}
			if($_SESSION["karakter"] == "Seer"){
				if($gpemain["terawang"] == 0) {
					return Main::redirect(array("danger",e("Malam telah tiba, terawanglah salah satu pemain yang belum pernah diterawang.")),"/ww/play.php?id=".$groom['id']."");
				}
				return Main::redirect(array("success",e("Tunggu pagi hari tiba.")),"/ww/play.php?id=".$groom['id']."");
			}
			if($_SESSION["karakter"] == "Guard"){
				if($gpemain["lindungi"] == 0) {
					return Main::redirect(array("danger",e("Malam telah tiba, lindungilah salah satu pemain dari ancaman pembunuhan werewolf.")),"/ww/play.php?id=".$groom['id']."");
				}
				return Main::redirect(array("danger",e("Tunggu pagi hari tiba.")),"/ww/play.php?id=".$groom['id']."");
			}
			if($_SESSION["karakter"] == "Villager"){
				return Main::redirect(array("danger",e("Tidur coeg, ini malam hari.")),"/ww/play.php?id=".$groom['id']."");
			}
		}
		if($groom["debat"] == 1) {
			if($gpemain["hidup"] == 0){
				return Main::redirect(array("danger",e("Anda sudah mati. Info: ".$pesan['pesan']."")),"/ww/play.php?id=".$groom['id']."");
			}
			return Main::redirect(array("success",e("".$pesan['debat']."")),"/ww/play.php?id=".$groom['id']."");
		}
		if($groom["hasilvote"] == 1) {
			if($gpemain["hidup"] == 0){
				return Main::redirect(array("danger",e("Anda sudah mati. Info: ".$pesan['pesan']."")),"/ww/play.php?id=".$groom['id']."");
			}
			return Main::redirect(array("success",e("".$pesan['vote']."")),"/ww/play.php?id=".$groom['id']."");
		}
		if($groom["vote2"] == 1) {
			if($gpemain["hidup"] == 0){
				return Main::redirect(array("danger",e("Anda sudah mati. (voting telah dibuka)")),"/ww/play.php?id=".$groom['id']."");
			}
			if($gpemain["milih"] == 1) {
					return Main::redirect(array("success",e("Anda sudah mem-vote. Tunggu hingga hasil vote ditampilkan.")),"/ww/play.php?id=".$groom['id']."");
			}
			if($gpemain["milih2"] == 0){
				return Main::redirect(array("danger",e("Voting kedua telah dibuka, tetapi anda tidak bisa memilih.")),"/ww/play.php?id=".$groom['id']."");
			}
			return Main::redirect(array("danger",e("Voting telah dibuka, silahkan memilih pemain yang akan dibunuh.")),"/ww/play.php?id=".$groom['id']."");
		}
	}
}
}
function cek(){
$nama = $_SESSION["masuk"];
$connection = mysqli_connect("localhost", "root", "");
$db = mysqli_select_db($connection, "adityaw1_wp197");
$id = $_GET["id"];
$gpemain = mysqli_fetch_array(mysqli_query($connection, "select * from pemain where nama='$nama'"));
$groom = mysqli_fetch_array(mysqli_query($connection, "select * from room where id='$id'"));
	if($groom["vote"] == 1 && $gpemain["hidup"] == 1 || $gpemain["milih2"] == 1) { 
		 ?><a class="btn btn-primary pull-right" href="/ww/play.php?action=vote&id=<?php echo $id; ?>">Vote</a> <?php		  
	}
	if($groom["malam"] == 1 && $gpemain["hidup"] == 1) {
		if($_SESSION["karakter"] == "Werewolf"){
			if(!isset($_SESSION["bunuh"])) {
				?><a class="btn btn-primary pull-right" href="/ww/play.php?action=bunuh&id=<?php echo $id; ?>">Bunuh</a> <?php	
			}
		}
		if($_SESSION["karakter"] == "Seer"){
			if(!isset($_SESSION["terawang"])) {
				?><a class="btn btn-primary pull-right" href="/ww/play.php?action=terawang&id=<?php echo $id; ?>">Terawang</a> <?php
			}	
		}
		if($_SESSION["karakter"] == "Guard"){
			if(!isset($_SESSION["lindungi"])) {
				?><a class="btn btn-primary pull-right" href="/ww/play.php?action=lindungi&id=<?php echo $id; ?>">Lindungi</a> <?php
			}	
		}
	}
	
}

function tampil(){
	$nama = $_SESSION["masuk"];
	$karakter = $_SESSION["karakter"];
	$connection = mysqli_connect("localhost", "root", "");
	$db = mysqli_select_db($connection, "adityaw1_wp197");
	$id = $_GET["id"];
	$gpemain = mysqli_fetch_array(mysqli_query($connection, "select * from pemain where nama='$nama'"));
	$groom = mysqli_fetch_array(mysqli_query($connection, "select * from room where id='$id'"));
	$werewolf = mysqli_query($connection, "select * from pemain where karakter='Werewolf' AND nama != '$nama'");
	$vote = mysqli_query($connection, "select * from pemain where hidup='1'");
	$pemain = mysqli_query($connection, "select * from pemain");
	if($groom["selesai"] == 1) {
		?><h3>Pemain :</h3>
		<?php if (mysqli_num_rows($pemain) > 0 ) { ?>
			<table><tr>
				<td width="30px"><b>No</b></td><td width="200px"><b>Nama</b></td><td width="100px"><b>Karakter</b></td>
				</tr><tr><?php
			    $z='1';
				while($rows = mysqli_fetch_array($pemain)) {
			?>	
					<td><?php echo $z ?></td>
					<td><?php echo $rows["nama"] ?></td>
					<td><?php echo $rows["karakter"] ?></td>
					</tr> <?php
					$z++;
			}
		?>  </table> <?php
		} ?>  <br><hr />  <?php
	}
	if($karakter == "Werewolf"){
		?><h3>Teman sesama Werewolf :</h3>
		<?php if (mysqli_num_rows($werewolf) > 0 ) { ?>
			<table><tr>
				<td width="30px"><b>No</b></td><td width="200px"><b>Nama</b></td><td width="100px"><b>Status</b></td>
				</tr><tr><?php
			    $z='1';
				while($rows = mysqli_fetch_array($werewolf)) {
				if($rows["hidup"] == 1 ){
					$status = "Hidup";
				} else {
					$status = "Mati";
				}
			?>	
					<td><?php echo $z ?></td>
					<td><?php echo $rows["nama"] ?></td>
					<td><?php echo $status ?></td>
					</tr> <?php
					$z++;
			}
		?>  </table> <?php
		} ?>  <br><hr />  <?php
	}
	if($karakter == "Sorcerer"){
		?><h3>Yang menjadi Werewolf :</h3>
		<?php if (mysqli_num_rows($werewolf) > 0 ) { ?>
			<table><tr>
				<td width="30px"><b>No</b></td><td width="200px"><b>Nama</b></td><td width="100px"><b>Status</b></td>
				</tr><tr><?php
			    $z='1';
				while($rows = mysqli_fetch_array($werewolf)) {
				if($rows["hidup"] == 1 ){
					$status = "Hidup";
				} else {
					$status = "Mati";
				}
			?>	
					<td><?php echo $z ?></td>
					<td><?php echo $rows["nama"] ?></td>
					<td><?php echo $status ?></td>
					</tr> <?php
					$z++;
			}
		?>  </table>  <?php
		} ?>  <br><hr />  <?php
	}
	if($groom["hasilvote"] == 1 || $groom["vote"] == 1 || $groom["vote2"] == 1){
		?><h3>Hasil Vote :</h3>
			<table><tr><?php
				while($rowss = mysqli_fetch_array($vote)) {
			?>	
					<td width="100px"><?php echo $rowss["nama"] ?></td>
					<td width="60px"><?php echo $rowss["dipilih"] ?>  suara</td>
					<td width="100px">memilih&nbsp;<?php echo $rowss["milihsiapa"] ?></td>
					</tr> <?php
			}
		?>  </table><br><hr />  <?php
	}
		?><h3>Daftar Pemain :</h3> 
		<?php if (mysqli_num_rows($pemain) > 0 ) { ?>
			<table><tr>
				<td width="30px"><b>No</b></td><td width="200px"><b>Nama</b></td><td width="100px"><b>Status</b></td>
				</tr></tr><?php
			    $z='1';
				while($rows = mysqli_fetch_array($pemain)) {
				if($rows["hidup"] == 1 ){
					$status = "Hidup";
				} else {
					$status = "Mati";
				}
			?>	
					<td><?php echo $z ?></td>
					<td><?php echo $rows["nama"] ?></td>
					<td><?php echo $status ?></td>
					</tr> <?php
					$z++;
			}
		?>  </table>  <?php
		}
	}
?>
<section>
	<div class="centered form">
	  	<?php echo Main::message(); menang(); ?> 
		<?php echo cek();  refresh(); ?>
		<?php echo login(); ?> 
	  	<form role="form" class="live_form form" id="login_form" method="post" action="/ww/play.php?id=<?php echo $groom["id"]; ?>">
			<?php echo tampil(); ?>
	  	</form>
	</div>
</section>