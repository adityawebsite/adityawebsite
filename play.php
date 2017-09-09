<?php define( "ROOT" , __DIR__ ); ?>
<?php
require_once (ROOT. "/includes/Main.class.php");
require_once (ROOT. "/includes/config.php");
$connection = mysqli_connect("localhost", "root", "");
// Seleksi Database
$db = mysqli_select_db($connection, "adityaw1_wp197");
// SQL query untuk memeriksa apakah karyawan terdapat di database?
?>
<?php
if(!isset($_SESSION)){
	  session_start();
	}
$action = isset($_GET["action"]) ? $_GET["action"] : "play";
switch ($action) {
	case "play" :
	default:
	$id = isset($_GET["id"]) ? $_GET["id"] : "0";
		if($_GET["id"] == "0") {
			return Main::redirect(array("danger",e("ID Room salah.")),"/ww/index.php");
		}
		$id = $_GET["id"];
		$iroom = mysqli_query($connection, "select * from room where id='$id'");
		$groom = mysqli_fetch_array($iroom);
		if($id != $groom["id"]) {
			return Main::redirect(array("danger",e("ID Room salah.")),"/ww/index.php");
		}
		if(!isset($_SESSION['masuk'])){
		return Main::redirect(array("danger",e("Masukkan data diri.")),"/ww/play.php?id=".$groom['id']."&action=masuk&tahap=1");
		}
		if(!isset($_SESSION['karakter'])) {
			return Main::redirect(array("danger",e("")),"/ww/play.php?id=".$groom['id']."&action=masuk&tahap=2");
		} 
		if($groom["malam"] == 0 && $groom["vote"] == 0 && $groom["debat"] == 0 && $groom["hasilvote"] == 0 && $groom["vote2"] == 0){
			$pesan = mysqli_query($connection, "update pesan set pesan='Menunggu room penuh' where id='1'");
		}
		include(ROOT. "/themes/user/playheader.php");
		include(ROOT. "/includes/playgame.php");
	break;
	case "masuk" : 
	include(ROOT. "/themes/header.php");
	if(!isset($_GET["id"])) {
			return Main::redirect(array("danger",e("ID Room salah.")),"/ww/index.php");
	}
		$id = $_GET["id"];
		$iroom = mysqli_query($connection, "select * from room where id='$id'");
		$groom = mysqli_fetch_array($iroom);
		$spemain = mysqli_query($connection, "select * from pemain");
		$gpemain = mysqli_fetch_array($spemain);
		if($id != $groom["id"]) {
			return Main::redirect(array("danger",e("ID Room salah.")),"/ww/index.php");
		}
		if($groom["pemain"] == 0) {
			return Main::redirect(array("danger",e("Room sudah penuh.")),"/ww/index.php");
		}
	$tahap = isset($_GET["tahap"]) ? $_GET["tahap"] : "1";
	if($tahap == 1 ){
	if(isset($_POST["submitnama"])) {
		if(empty($_POST["namapemain"])){
			return Main::redirect(array("danger",e("Data belum diisi.")),"/ww/play.php?id=".$groom['id']."&action=masuk&tahap=1");
		}
		$cek_user=mysqli_num_rows(mysqli_query($connection, "SELECT * FROM pemain WHERE nama='$_POST[namapemain]'"));
		if ($cek_user > 0) {
        	return Main::redirect(array("danger",e("Sudah ada yang menggunakan nama tersebut.")),"/ww/play.php?id=".$groom['id']."&action=masuk&tahap=1");
		}
		if(isset($_SESSION["masuk"])){
			return Main::redirect("","/ww/play.php?id=".$groom['id']."&action=masuk&tahap=2");
		}
		$namapemain = $_POST["namapemain"];
		if(isset($_GET["id"])) {
			$idroom = $_GET["id"];
		}
		$ipemain = mysqli_query($connection, "INSERT INTO pemain SET nama='$namapemain', idroom='$idroom'");
		$_SESSION['masuk'] = $namapemain;
		$_SESSION["idroom"] = $idroom;
		return Main::redirect("","/ww/play.php?id=".$groom['id']."&action=masuk&tahap=2");
	}
	?>
<section>
		<div class="centered form">
		<?php echo Main::message() ?>
  		<form role="form" class="live_form form" id="login_form" method="post" action="/ww/play.php?id=<?php echo $groom["id"] ?>&action=masuk&tahap=1">   
        <div class="form-group">
          <label for="email">Nama*</label>
          <input type="text" class="form-control" id="email" placeholder="Nama (wajib)" name="namapemain">
		</div>
        <button type="submit" name="submitnama" class="btn btn-primary">Play</button>
      </form>  
	  </div>
</section>
	<?php
		break;
	}
	$tahap = isset($_GET["tahap"]) ? $_GET["tahap"] : "1";
	if($tahap == 2 ){
	$id = $_GET["id"];
	$sroom = mysqli_query($connection, "select * from room where id='$id'");
	$groom = mysqli_fetch_array($sroom);
	if(!isset($_SESSION['masuk'])){
		return Main::redirect(array("danger",e("Masukkan data diri.")),"/ww/play.php?id=".$groom['id']."&action=masuk&tahap=1");
		}
	if(isset($_SESSION['karakter'])){
		return Main::redirect(array("danger",e("Anda sudah memiliki karakter.")),"/ww/play.php?id=".$groom['id']."");
		}
	if($groom["pemain"] == 0) {
			return Main::redirect(array("danger",e("Room sudah penuh.")),"/ww/index.php");
		}
	$karakter = array();
	for($z=1 ; $z<=$groom["guard"] ; $z++) {
		array_push($karakter, "Guard");
	}
	for($z=1 ; $z<=$groom["seer"] ; $z++) {
		array_push($karakter, "Seer");
	}
	for($z=1 ; $z<=$groom["werewolf"] ; $z++) {
		array_push($karakter, "Werewolf");
	}
	for($z=1; $z<=$groom["lycan"]; $z++) {
		array_push($karakter, "Lycan");
	}
	for($z=1; $z<=$groom["sorcerer"]; $z++) {
		array_push($karakter, "Sorcerer");
	}
	for($z=1; $z<=$groom["villager"]; $z++) {
		array_push($karakter, "Villager");
	}
	$acak = array_rand($karakter);
	$dapat = $karakter[$acak];
	if($dapat == "Werewolf"){
		$jumlah = $groom["werewolf"] - 1;
	} elseif($dapat == "Lycan"){
		$jumlah = $groom["lycan"] - 1;
	} elseif($dapat == "Guard"){
		$jumlah = $groom["guard"] - 1;
	} elseif($dapat == "Seer"){
		$jumlah = $groom["seer"] - 1;	
	} elseif($dapat == "Sorcerer"){
		$jumlah = $groom["sorcerer"] - 1;
	} else {
		$jumlah = $groom["villager"] - 1;
	}
	$jumlahpemain = $groom["pemain"] - 1;
	$namapemain = $_SESSION["masuk"];
	$iroom = mysqli_query($connection, "update room set $dapat='$jumlah', pemain='$jumlahpemain'");
	$ipemain = mysqli_query($connection, "update pemain set karakter='$dapat' where nama='$namapemain'");
	$_SESSION['karakter'] = $dapat;
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
	?>
	<section>
	<div class="centered form">
	  <?php echo Main::message() ?>
	  <form role="form" class="live_form form" id="login_form" method="post" action="">
	  <div class="form-group">
  	  <h2>Anda bermain sebagai <?php echo $dapat; ?></h2>
	  </div>
	  <div class="form-group">
	  <label><?php echo $penjelasan; ?></label>
	  </div>
	  <a class="btn btn-primary" href="/ww/play.php?id=<?php echo $groom["id"] ?>">Start</a>
	  </form>
	</div>
	</section>
	<?php
	break;	
	}
	case "hasilvote" :
	$id = $_GET["id"];
	$groom = mysqli_fetch_array(mysqli_query($connection, "select * from room where id='$id'"));
	$uroom = mysqli_query($connection, "update room set debat='0', malam='0', vote='0', hasilvote='1', vote2='0' where id='$id'");
	$max = mysqli_fetch_array(mysqli_query($connection, "select max(dipilih) as maksimal from pemain where hidup='1'"));
	$hasil = $max["maksimal"];
	$pemain = mysqli_fetch_array(mysqli_query($connection, "select * from pemain where dipilih='$hasil'"));
	$pemain2 = mysqli_query($connection, "select * from pemain where dipilih='$hasil'");
	$namamati = $pemain["nama"];
	if (mysqli_num_rows($pemain2) > 1 ) {
		$pesan = mysqli_query($connection, "update pesan set vote='Tidak ada pembunuhan. Ada lebih dari 1 pemain mendapatkan vote terbanyak.' where id='$id'");
		$votelagi = mysqli_query($connection, "update pemain set milih='0', milih2='1' where dipilih!='$hasil' AND idroom='$id'");
	} else {	
		$pesan = mysqli_query($connection, "update pesan set vote='Hasil Vote: Warga desa sepakat membunuh ".$namamati.".' where id='$id'");
		$mati = mysqli_query($connection, "update pemain set hidup='0' where dipilih='$hasil'");
	}
	if($uroom){
		return Main::redirect(array("success",e("Berhasil mengaktifkan Hasil vote.")),"/ww/play.php?id=".$groom["id"]."");
	}
	return Main::redirect(array("danger",e("Gagal.")),"/ww/play.php?id=".$groom["id"]."");
	break;
	
	case "admindebat" :
	$id = $_GET["id"];
	$werewolf = mysqli_fetch_array(mysqli_query($connection, "select * from pemain where werewolf='1' AND idroom='$id'"));
	$groom = mysqli_fetch_array(mysqli_query($connection, "select * from room where id='$id'"));
	$uroom = mysqli_query($connection, "update room set debat='1', malam='0', vote='0', hasilvote='0', vote2='0' where id='$id'");
	if($werewolf["dilindungi"] == 1) {
		$namamati = $werewolf['nama'];
		$pesan = mysqli_query($connection, "update pesan set debat='Hari sudah pagi. Semalam Guard berhasil menggagalkan pembunuhan, Werewolf gagal membunuh ".$werewolf['nama'].".' where id='$id'");
	} elseif ($werewolf["dilindungi"] == 0 ) {
		$namamati = $werewolf['nama'];
		$pesan = mysqli_query($connection, "update pesan set debat='Hari sudah pagi. Semalam terjadi pembunuhan, ".$namamati." mati termakan werewolf.' where id='$id'");
		$mati = mysqli_query($connection, "update pemain set hidup='0', werewolf='0' where nama='$namamati'");
	}
	
	if($uroom){
		return Main::redirect(array("success",e("Berhasil mengaktifkan Siang.")),"/ww/play.php?id=".$groom["id"]."");
	}
	return Main::redirect(array("danger",e("Gagal.")),"/ww/play.php?id=".$groom["id"]."");
	break;
	
	case "adminmalam" :
	$id = $_GET["id"];
	$groom = mysqli_fetch_array(mysqli_query($connection, "select * from room where id='$id'"));
	$uroom = mysqli_query($connection, "update room set debat='0', malam='1', vote='0', hasilvote='0', vote2='0', mulai='1' where id='$id'");
	$upemain = mysqli_query($connection, "update pemain set werewolf='0' where hidup='1'");
	$useer = mysqli_query($connection, "update pemain set terawang='0' where karakter='Seer' AND idroom='$id'");
	$uwerewolf = mysqli_query($connection, "update pemain set bunuh='0' where karakter='Werewolf' AND idroom='$id'");
	$dilindungi = mysqli_query($connection, "update pemain set dilindungi='0' where idroom='$id'");
	$uguard = mysqli_query($connection, "update pemain set lindungi='0' where karakter='Guard' AND idroom='$id'");
	if($uroom){
		return Main::redirect(array("success",e("Berhasil mengaktifkan Malam Hari.")),"/ww/play.php?id=".$groom["id"]."");
	}
	return Main::redirect(array("danger",e("Gagal.")),"/ww/play.php?id=".$groom["id"]."");
	break;
	case "adminvote" :
	$id = $_GET["id"];
	$on="1";
	$off="0";
	$groom = mysqli_fetch_array(mysqli_query($connection, "select * from room where id='$id'"));
	$uroom = mysqli_query($connection, "update room set debat='$off', malam='$off', vote='1', hasilvote='0', vote2='0' where id='$id'");
	$divote = mysqli_query($connection, "update pemain set milih='0', milih2='0', milihsiapa='(kosong)', dipilih='0' where hidup='1' AND idroom='$id'");
	$divote2 = mysqli_query($connection, "update pemain set dipilih='0' where hidup='0' AND idroom='$id'");
	if($uroom){
		return Main::redirect(array("success",e("Berhasil mengaktifkan vote.")),"/ww/play.php?id=".$groom["id"]."");
	}
	return Main::redirect(array("danger",e("Gagal.")),"/ww/play.php?id=".$groom["id"]."");
	break;
	case "vote2" :
	$id = $_GET["id"];
	$on="1";
	$off="0";
	$groom = mysqli_fetch_array(mysqli_query($connection, "select * from room where id='$id'"));
	$uroom = mysqli_query($connection, "update room set debat='$off', malam='$off', vote='0', hasilvote='0', vote2='1' where id='$id'");
	$pemain = mysqli_query($connection, "update pemain set milihsiapa='(kosong)' where idroom='$id'");
	$divote = mysqli_query($connection, "update pemain set milih='0', dipilih='0', milihsiapa='(kosong)' where hidup='1' AND idroom='$id'");
	if($uroom){
		return Main::redirect(array("success",e("Berhasil mengaktifkan vote.")),"/ww/play.php?id=".$groom["id"]."");
	}
	return Main::redirect(array("danger",e("Gagal.")),"/ww/play.php?id=".$groom["id"]."");
	break;
	
	case "terawang" :
	include(ROOT. "/themes/user/playheader.php");
	$id = $_GET["id"];
	$nama = $_SESSION["masuk"];
	$groom = mysqli_fetch_array(mysqli_query($connection, "select * from room where id='$id'"));
	$gpemain = mysqli_fetch_array(mysqli_query($connection, "select * from pemain where nama='$nama' AND idroom='$id'"));
	$pilih = mysqli_query($connection, "select * from pemain where hidup !='0' AND seer ='0' AND nama !='$nama' AND idroom='$id'");
	if($gpemain["hidup"] == 0) {
		return Main::redirect(array("danger",e("Anda sudah mati.")),"/ww/play.php?id=".$groom['id']."");
	}
	if($gpemain["karakter"] != "Seer") {
		return Main::redirect(array("danger",e("Anda bukan Seer.")),"/ww/play.php?id=".$groom["id"]."");
	}
	if($gpemain["terawang"] == "1"){
		return Main::redirect(array("danger",e("Anda sudah menerawang malam ini. Coba lagi malam berikutnya")),"/ww/play.php?id=".$groom["id"]."");
	} 
	if(isset($_POST["btnterawang"])) {
		$pilihan = $_POST["pilihanterawang"];
		$nama = $_SESSION["masuk"];
		$terawang = mysqli_fetch_array(mysqli_query($connection, "select * from pemain where nama='$pilihan' AND idroom='$id'"));
		if($terawang["seer"] == 1 ) {
			return Main::redirect(array("danger",e("Tidak boleh memilih pemain yang sama")),"/ww/play.php?action=terawang&id=".$groom["id"]."");
		}
		$upilihan = mysqli_query($connection, "update pemain set seer='1' where nama='$pilihan' AND idroom='$id'");
		$upilihan2 = mysqli_query($connection, "update pemain set terawang='1' where nama='$nama' AND idroom='$id'");
		if($terawang["karakter"] == "Werewolf" || $terawang["karakter"] == "Lycan" ){
			return Main::redirect(array("success",e("".$terawang['nama']." = Werewolf ( W )")),"/ww/play.php?id=".$groom["id"]."");
		} else {
			return Main::redirect(array("success",e("".$terawang['nama']." = Villager ( V )")),"/ww/play.php?id=".$groom["id"]."");
		}
		
	}	
	?>
	<section>
		<div class="centered form">
	  		<?php echo Main::message() ?> 
	  		<form role="form" class="live_form form" id="login_form" method="post" action="/ww/play.php?action=terawang&id=<?php echo $groom["id"]; ?>">
				<div class="form-group">
					<h2>Pilihlah salah satu pemain yang ingin anda terawang.</h2>
				</div>
				<div class="form-group">
				Pilihan: <br>
				<select name="pilihanterawang">
				 <?php if (mysqli_num_rows($pilih) > 0 ) {
			 		while($row = mysqli_fetch_array($pilih)) { ?>
						<option><?php echo $row["nama"] ?></option> 
<?php				}
				 } ?>
				</select>
				</div>
				<button class="btn btn-primary" type="submit" name="btnterawang">Terawang</button>
	  		</form>
		</div>
	</section>
<?php	break;
case "lindungi" :
	include(ROOT. "/themes/user/playheader.php");
	$id = $_GET["id"];
	$nama = $_SESSION["masuk"];
	$groom = mysqli_fetch_array(mysqli_query($connection, "select * from room where id='$id'"));
	$gpemain = mysqli_fetch_array(mysqli_query($connection, "select * from pemain where nama='$nama' AND idroom='$id'"));
	$pilih = mysqli_query($connection, "select * from pemain where hidup !='0' AND guard ='0' AND idroom='$id'");
	if($gpemain["hidup"] == 0){
		return Main::redirect(array("danger",e("Anda sudah mati.")),"/ww/play.php?id=".$groom['id']."");
	}
	if($gpemain["karakter"] != "Guard") {
		return Main::redirect(array("danger",e("Anda bukan Guard.")),"/ww/play.php?id=".$groom["id"]."");
	}
	if($gpemain["lindungi"] == "1"){
		return Main::redirect(array("danger",e("Anda sudah melindungi seseorang malam ini. Coba lagi malam berikutnya")),"/ww/play.php?id=".$groom["id"]."");
	} 
	if(isset($_POST["btnlindungi"])) {
		$pilihan = $_POST["pilihanlindungi"];
		$nama = $_SESSION["masuk"];
		$terawang = mysqli_fetch_array(mysqli_query($connection, "select * from pemain where nama='$pilihan' AND idroom='$id'"));
		if($terawang["guard"] == 1 ) {
			return Main::redirect(array("danger",e("Tidak boleh memilih pemain yang sama")),"/ww/play.php?action=lindungi&id=".$groom["id"]."");
		}
		$upilihan = mysqli_query($connection, "update pemain set guard='1', dilindungi='1' where nama='$pilihan' AND idroom='$id'");
		$upilihan2 = mysqli_query($connection, "update pemain set lindungi='1' where nama='$nama' AND idroom='$id'");
		if($upilihan && $upilihan2)	{
			return Main::redirect(array("success",e("Anda memilih melindungi ".$terawang['nama']."")),"/ww/play.php?id=".$groom["id"]."");
		}	
	}	
	?>
	<section>
		<div class="centered form">
	  		<?php echo Main::message() ?> 
	  		<form role="form" class="live_form form" id="login_form" method="post" action="/ww/play.php?action=lindungi&id=<?php echo $groom["id"]; ?>">
				<div class="form-group">
					<h2>Pilihlah salah satu pemain yang ingin dilindungi dari serangan werewolf.</h2>
				</div>
				<div class="form-group">
				Pilihan: <br>
				<select name="pilihanlindungi">
				 <?php if (mysqli_num_rows($pilih) > 0 ) {
			 		while($row = mysqli_fetch_array($pilih)) { ?>
						<option><?php echo $row["nama"] ?></option> 
<?php				}
				 } ?>
				</select>
				</div>
				<button class="btn btn-primary" type="submit" name="btnlindungi">Lindungi</button>
	  		</form>
		</div>
	</section>
<?php	break;
case "bunuh" :
	include(ROOT. "/themes/user/playheader.php");
	$id = $_GET["id"];
	$nama = $_SESSION["masuk"];
	$groom = mysqli_fetch_array(mysqli_query($connection, "select * from room where id='$id'"));
	$gpemain = mysqli_fetch_array(mysqli_query($connection, "select * from pemain where nama='$nama' AND idroom='$id'"));
	$guardi = mysqli_fetch_array(mysqli_query($connection, "select * from pemain where idroom='$id'"));
	$pilih = mysqli_query($connection, "select * from pemain where hidup !='0' AND nama != '$nama' AND karakter != 'Werewolf' AND  idroom='$id'");
	if($gpemain["hidup"] == 0){
		return Main::redirect(array("danger",e("Anda sudah mati.")),"/ww/play.php?id=".$groom['id']."");
	}
	if($gpemain["karakter"] != "Werewolf") {
		return Main::redirect(array("danger",e("Anda bukan Werewolf.")),"/ww/play.php?id=".$groom["id"]."");
	}
	if($gpemain["bunuh"] == "1"){
		return Main::redirect(array("danger",e("Anda sudah membunuh seseorang malam ini. Coba lagi malam berikutnya")),"/ww/play.php?id=".$groom["id"]."");
	} 
	if(isset($_POST["btnbunuh"])) {
		$pilihan = $_POST["pilihanbunuh"];
		if(!isset($pilihan)){
			return Main::redirect(array("danger",e("Tentukan pilihanmu.")),"/ww/play.php?action=bunuh&id=".$groom["id"]."");
		}
		$nama = $_SESSION["masuk"];
		$terawang = mysqli_fetch_array(mysqli_query($connection, "select * from pemain where nama='$pilihan' AND idroom='$id'"));
		if($terawang["werewolf"] == 1 ) {
			return Main::redirect(array("danger",e("Tidak boleh memilih pemain yang sama")),"/ww/play.php?action=bunuh&id=".$groom["id"]."");
		}
		$upilihan = mysqli_query($connection, "update pemain set werewolf='1' where nama='$pilihan' AND idroom='$id'");
		$upilihan2 = mysqli_query($connection, "update pemain set bunuh='1' where karakter='Werewolf' AND idroom='$id'");
		if($upilihan && $upilihan2)	{
			return Main::redirect(array("success",e("Anda memilih membunuh ".$terawang['nama']."")),"/ww/play.php?id=".$groom["id"]."");
		}	
	}	
	?>
	<section>
		<div class="centered form">
	  		<?php echo Main::message() ?> 
	  		<form role="form" class="live_form form" id="login_form" method="post" action="/ww/play.php?action=bunuh&id=<?php echo $groom["id"]; ?>">
				<div class="form-group">
					<h2>Pilihlah salah satu pemain yang ingin dibunuh.</h2>
					*Tidak setiap werewolf dapat membunuh. Jadi berdiskusilah ke sesama werewolf.
				</div>
				<div class="form-group">
				Pilihan: <br>
				<select name="pilihanbunuh">
				 <?php if (mysqli_num_rows($pilih) > 0 ) {
			 		while($row = mysqli_fetch_array($pilih)) { ?>
						<option><?php echo $row["nama"] ?></option> 
<?php				}
				 } ?>
				</select>
				</div>
				<button class="btn btn-primary" type="submit" name="btnbunuh">Bunuh</button>
	  		</form>
		</div>
	</section>
<?php	break;
case "vote" :
	include(ROOT. "/themes/user/playheader.php");
	$id = $_GET["id"];
	$nama = $_SESSION["masuk"];
	$groom = mysqli_fetch_array(mysqli_query($connection, "select * from room where id='$id'"));
	$gpemain = mysqli_fetch_array(mysqli_query($connection, "select * from pemain where nama='$nama' AND idroom='$id'"));
	$pilih = mysqli_query($connection, "select * from pemain where hidup !='0' AND milih2='0' AND nama != '$nama'  AND  idroom='$id'");
	if($gpemain["hidup"] == 0){
		return Main::redirect(array("danger",e("Anda sudah mati.")),"/ww/play.php?id=".$groom['id']."");
	}
	if($gpemain["milih"] == "1"){
		return Main::redirect(array("danger",e("Anda sudah mem-vote. ")),"/ww/play.php?id=".$groom["id"]."");
	} 
	if(isset($_POST["btnvote"])) {
		$pilihan = $_POST["pilihanvote"];
		if(!isset($pilihan)){
			return Main::redirect(array("danger",e("Tentukan pilihanmu.")),"/ww/play.php?action=vote&id=".$groom["id"]."");
		}
		$nama = $_SESSION["masuk"];
		$gdipilih = mysqli_fetch_array(mysqli_query($connection, "select * from pemain where nama='$pilihan' AND id='$id'"));
		$jumlah = $gdipilih["dipilih"] + 1;
		$upilihan = mysqli_query($connection, "update pemain set dipilih='$jumlah' where nama='$pilihan' AND idroom='$id'");
		$upilihan2 = mysqli_query($connection, "update pemain set milih='1', milihsiapa='$pilihan' where nama='$nama' AND idroom='$id'");
		if($upilihan && $upilihan2)	{
			return Main::redirect(array("success",e("Anda mem-vote ".$pilihan."")),"/ww/play.php?id=".$groom["id"]."");
		}	
	}
	?>
	<section>
		<div class="centered form">
	  		<?php echo Main::message() ?> 
	  		<form role="form" class="live_form form" id="login_form" method="post" action="/ww/play.php?action=vote&id=<?php echo $groom["id"]; ?>">
				<div class="form-group">
					<h2>Vote-lah salah satu pemain yang dicurigai sebagai Werewolf.</h2>
					*Data yang sudah terkirim tidak dapat diubah sampai proses vote selanjutnya.
				</div>
				<div class="form-group">
				Pilihan: <br>
				<select name="pilihanvote">
				 <?php if (mysqli_num_rows($pilih) > 0 ) {
			 		while($row = mysqli_fetch_array($pilih)) { ?>
						<option><?php echo $row["nama"] ?></option> 
<?php				}
				 } ?>
				</select>
				</div>
				<button class="btn btn-primary" type="submit" name="btnvote">Vote</button>
	  		</form>
		</div>
	</section>
<?php break;
}
?>
<?php include(ROOT. "/themes/footer.php");?>