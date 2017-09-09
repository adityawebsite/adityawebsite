<?php define( "ROOT" , __DIR__ ); ?>
<?php include(ROOT. "/themes/user/header.php");
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
if(!isset($_SESSION['login'])){
	return Main::redirect(array("danger",e("Login terlebih dahulu.")),"/ww/login.php");
}

$action = isset($_GET["action"]) ? $_GET["action"] : "user";
		switch ($action) {
			case 'create' :
	if (isset($_POST['submit'])) {
	if(empty($_POST["namaroom"]) || empty($_POST["jumlahpemain"])) {
		return Main::redirect(array("danger",e("Data belum diisi.")),"/ww/user.php?action=create");
	}
	$aa = $_POST["jumlahwerewolf"] + $_POST["jumlahseer"] + $_POST["jumlahguard"] + $_POST["jumlahlycan"] + $_POST["jumlahsorcerer"] + $_POST["jumlahvillager"];
	$ab = $jumlahpemain = $_POST["jumlahpemain"];
	if($aa != $ab){
		return Main::redirect(array("danger",e("Jumlah pemain dengan jumlah karakter tidak sama.")),"/ww/user.php?action=create");
	}
	$namaroom = $_POST["namaroom"];
	$cek_user=mysqli_num_rows(mysqli_query($connection, "SELECT * FROM room WHERE nama='$namaroom'"));
		if ($cek_user > 0) {
        	return Main::redirect(array("danger",e("Sudah ada yang menggunakan nama tersebut.")),"/ww/user.php?action=create");
		}
	$namaroom = $_POST["namaroom"];
	$jumlahpemain = $_POST["jumlahpemain"];
	$jumlahwerewolf = $_POST["jumlahwerewolf"];
	$jumlahseer = $_POST["jumlahseer"];
	$jumlahguard = $_POST["jumlahguard"];
	$jumlahlycan = $_POST["jumlahlycan"];
	$jumlahsorcerer = $_POST["jumlahsorcerer"];
	$jumlahvillager = $_POST["jumlahvillager"];
	$iroom = mysqli_query($connection, "INSERT INTO room SET nama='$namaroom', pemain='$jumlahpemain', werewolf='$jumlahwerewolf', seer='$jumlahseer', guard='$jumlahguard', lycan='$jumlahlycan', sorcerer='$jumlahsorcerer', villager='$jumlahvillager'");
	$iroom2 = mysqli_query($connection, "INSERT INTO room2 SET nama='$namaroom', pemain='$jumlahpemain', werewolf='$jumlahwerewolf', seer='$jumlahseer', guard='$jumlahguard', lycan='$jumlahlycan', sorcerer='$jumlahsorcerer', villager='$jumlahvillager'");
	$ipesan = mysqli_query($connection, "insert into pesan set debat='tes', vote='tes'");
	if($iroom) {
		return Main::redirect(array("success",e("Room berhasil dibuat.")),"/ww/user.php");
	}
	return Main::redirect(array("danger",e("Gagal.")),"/ww/user.php?action=create");
}
?>
		<section> 
		<div class="centered form">
		<?php echo Main::message() ?>
  			<form role="form" class="live_form form" id="login_form" method="post" action="/ww/user.php?action=create">   
        <div class="form-group">
          <label for="email">Nama room*</label>
          <input type="text" class="form-control" id="email" placeholder="Nama room (wajib)" name="namaroom">
		</div>
		<div class="form-group">
		  <label>Jumlah pemain keseluruhan*</label>
          <input type="number" class="form-control" id="email" placeholder="Jumlah pemain (wajib)" name="jumlahpemain">
        </div> 
		<div class="form-group">
		  <label>Jumlah Werewolf**</label>
          <input type="number" class="form-control" id="email" placeholder="Jumlah werewolf" name="jumlahwerewolf">
        </div>  
		<div class="form-group">
		  <label>Jumlah Villager**</label>
          <input type="number" class="form-control" id="email" placeholder="Jumlah villager" name="jumlahvillager">
        </div>   
		<div class="form-group">
		  <label>Jumlah Seer**</label>
          <input type="number" class="form-control" id="email" placeholder="Jumlah seer" name="jumlahseer" value="1" readonly>
        </div> 
		<div class="form-group">
		  <label>Jumlah Guard**</label>
          <input type="number" class="form-control" id="email" placeholder="Jumlah guard" name="jumlahguard" value="1" readonly>
        </div> 
		<div class="form-group">
		  <label>Jumlah Lycan**</label>
          <input type="number" class="form-control" id="email" placeholder="Jumlah lycan" name="jumlahlycan">
        </div> 
		<div class="form-group">
		  <label>Jumlah Sorcerer**</label>
          <input type="number" class="form-control" id="email" placeholder="Jumlah sorcerer" name="jumlahsorcerer">
        </div>
		<em>***Jumlah pemain harus sama dengan jumlah karakter!<br>**Jika tidak menggunakan karakter tertentu, biarkan bernilai kosong!<br>*Wajib diisi!</em><br><br>           
        <button type="submit" name="submit" class="btn btn-primary">Buat</button>
      </form>  
	  </div>
  </div>
  </section>
<?php break;
		case 'editroom' :
	$id = $_GET["id"];
	$groom = mysqli_fetch_array(mysqli_query($connection, "select * from room where id='$id'"));
	$namaroom = $groom["nama"];
	if (isset($_POST['submit'])) {
	if(empty($_POST["namaroom"]) || empty($_POST["jumlahpemain"])) {
		return Main::redirect(array("danger",e("Data belum diisi.")),"/ww/user.php?action=editroom&id=".$groom['id']."");
	}
	$aa = $_POST["jumlahwerewolf"] + $_POST["jumlahseer"] + $_POST["jumlahguard"] + $_POST["jumlahlycan"] + $_POST["jumlahsorcerer"] + $_POST["jumlahvillager"];
	$ab = $jumlahpemain = $_POST["jumlahpemain"];
	if($aa != $ab){
		return Main::redirect(array("danger",e("Jumlah pemain dengan jumlah karakter tidak sama.")),"/ww/user.php?action=editroom&id=".$groom['id']."");
	}
	$namaroom = $_POST["namaroom"];
	$cek_user=mysqli_num_rows(mysqli_query($connection, "SELECT * FROM room WHERE nama='$namaroom' AND id!='$id'"));
		if ($cek_user > 0) {
        	return Main::redirect(array("danger",e("Sudah ada yang menggunakan nama tersebut.")),"/ww/user.php?action=editroom&id=".$groom['id']."");
		}
	$namaroom = $_POST["namaroom"];
	$jumlahpemain = $_POST["jumlahpemain"];
	$jumlahwerewolf = $_POST["jumlahwerewolf"];
	$jumlahseer = $_POST["jumlahseer"];
	$jumlahguard = $_POST["jumlahguard"];
	$jumlahlycan = $_POST["jumlahlycan"];
	$jumlahsorcerer = $_POST["jumlahsorcerer"];
	$jumlahvillager = $_POST["jumlahvillager"];
	$iroom = mysqli_query($connection, "update room SET nama='$namaroom', pemain='$jumlahpemain', werewolf='$jumlahwerewolf', seer='$jumlahseer', guard='$jumlahguard', lycan='$jumlahlycan', sorcerer='$jumlahsorcerer', villager='$jumlahvillager' where nama='$namaroom'");
	$iroom2 = mysqli_query($connection, "update room2 SET nama='$namaroom', pemain='$jumlahpemain', werewolf='$jumlahwerewolf', seer='$jumlahseer', guard='$jumlahguard', lycan='$jumlahlycan', sorcerer='$jumlahsorcerer', villager='$jumlahvillager' where nama='$namaroom'");
	if($iroom) {
		return Main::redirect(array("success",e("Room berhasil diedit.")),"/ww/user.php");
	}
	return Main::redirect(array("danger",e("Gagal.")),"/ww/user.php?action=editroom&id=".$groom['id']."");
}
?>
		<section> 
		<div class="centered form">
		<?php echo Main::message() ?>
  			<form role="form" class="live_form form" id="login_form" method="post" action="/ww/user.php?action=editroom&id=<?php echo $id; ?>">   
        <div class="form-group">
          <label for="email">Nama room*</label>
          <input type="text" class="form-control" id="email" placeholder="Nama room (wajib)" value="<?php echo $groom['nama']; ?>" name="namaroom">
		</div>
		<div class="form-group">
		  <label>Jumlah pemain keseluruhan*</label>
          <input type="number" class="form-control" id="email" placeholder="Jumlah pemain (wajib)" value="<?php echo $groom["pemain"] ?>" name="jumlahpemain">
        </div> 
		<div class="form-group">
		  <label>Jumlah Werewolf**</label>
          <input type="number" class="form-control" id="email" placeholder="Jumlah werewolf" value="<?php echo $groom["werewolf"] ?>" name="jumlahwerewolf">
        </div>  
		<div class="form-group">
		  <label>Jumlah Villager**</label>
          <input type="number" class="form-control" id="email" placeholder="Jumlah villager" value="<?php echo $groom["villager"] ?>" name="jumlahvillager">
        </div>   
		<div class="form-group">
		  <label>Jumlah Seer**</label>
          <input type="number" class="form-control" id="email" placeholder="Jumlah seer" name="jumlahseer" value="1" readonly>
        </div> 
		<div class="form-group">
		  <label>Jumlah Guard**</label>
          <input type="number" class="form-control" id="email" placeholder="Jumlah guard" name="jumlahguard" value="1" readonly>
        </div> 
		<div class="form-group">
		  <label>Jumlah Lycan**</label>
          <input type="number" class="form-control" id="email" placeholder="Jumlah lycan" value="<?php echo $groom["lycan"] ?>" name="jumlahlycan">
        </div> 
		<div class="form-group">
		  <label>Jumlah Sorcerer**</label>
          <input type="number" class="form-control" id="email" placeholder="Jumlah sorcerer" name="jumlahsorcerer">
        </div>
		<em>***Jumlah pemain harus sama dengan jumlah karakter!<br>**Jika tidak menggunakan karakter tertentu, biarkan bernilai kosong!<br>*Wajib diisi!</em><br><br>           
        <button type="submit" name="submit" class="btn btn-primary">Buat</button>
      </form>  
	  </div>
  </div>
  </section>
<?php break;
  		case 'user' :
		default:
		?>
		<br><br>
		<?php echo Main::message() ?>
	<div class="container">
	   <div style="text-align:right" class="call-to-action">
	   	  <a href="/ww/user.php?action=resetpemain" class="btn btn-primary btn-lg">Reset pemain</a>
      	  <a href="/ww/user.php?action=create" class="btn btn-primary btn-lg">Create room</a>
		</div>
	</div>
<section>
<div class="main-form">
		<div class="col-md-10 url-info">
			<h1>Daftar Room:</h1>
			<table>
				<?php 
				$sroom = mysqli_query($connection, "SELECT * FROM room");
				$groom = mysqli_fetch_array($sroom);
				$idroom = $groom["id"];
				$spemain = mysqli_query($connection, "SELECT * FROM pemain where idroom='$idroom'");
				$rows = mysqli_num_rows($spemain);
					foreach ($sroom as $row) {
					echo "	<tr>
            				<td width='500px'><h2 class='title'>".$row['nama']."</td>
            				<td><a class='btn btn-primary' href='/ww/play.php?id=".$row['id']."'>Play</a></td>
							<td><a class='btn btn-primary' href='/ww/user.php?action=delete&id=".$row["id"]."'>Delete</a></td>
							<td><a class='btn btn-primary' href='/ww/user.php?action=editroom&id=".$row["id"]."'>Edit</a></td>
							<td><a class='btn btn-primary' href='/ww/user.php?action=resetroom&id=".$row["id"]."'>Reset</a></td>
              			    </tr><tr>
							<td>".$rows." pemain</td>
							</tr>";
					}
				?>
			</table>
	</div>
</div>
</section>
  <?php
	break;
		case "delete" :
	$id_delete = $_GET['id'];
	$droom = mysqli_query($connection, "DELETE from room where id='$id_delete'");
	if(!$droom){
		return Main::redirect(array("danger",e("Room gagal dihapus.")),"/ww/user.php");
	}
	return Main::redirect(array("success",e("Room berhasil dihapus.")),"/ww/user.php");
	break;
		case "resetpemain" :
		$resetpemain = mysqli_query($connection, "TRUNCATE TABLE pemain");
		if($resetpemain) {
			return Main::redirect(array("success",e("Pemain telah dihapus dari semua room.")),"/ww/user.php");
		}
		break;
		case "resetroom" :
		if(!isset($_GET["id"])) {
			return Main::redirect(array("danger",e("ID salah.")),"/ww/user.php");
		}
		$id_delete = $_GET['id'];
		$sroom = mysqli_query($connection, "SELECT * FROM room where id='$id_delete'");
		$groom = mysqli_fetch_array($sroom);
		$namaroom = $groom["nama"];
		$aaaa = mysqli_query($connection, "UPDATE room, room2 SET room.mulai = room2.mulai, room.selesai = room2.selesai, room.vote2 = room2.vote2, room.hasilvote = room2.hasilvote, room.pemain = room2.pemain, room.werewolf = room2.werewolf, room.seer = room2.seer, room.guard = room2.guard, room.lycan = room2.lycan , room.sorcerer = room2.sorcerer , room.villager = room2.villager, room.malam = room2.malam, room.vote = room2.vote, room.debat = room2.debat WHERE room.nama='$namaroom'");
		if($aaaa) {
			return Main::redirect(array("success",e("Karakter telah direset.")),"/ww/user.php");
		}
		break;
}
?>
<?php include(ROOT. "/themes/footer.php"); ?>