<div class='container'>    
		<div class='centered form'>      
      <div class='site_logo'>
          <a href='/'><img src='/content/auto_site_logo.png' alt='Aditya Website'></a>
	  </div>
	  <?php echo Main::message() ?>
	  <?php echo pesan() ?>
      <form role='form' class='live_form form' id='login_form' method='post' action='/login.php'>        
        <div class='form-group'>
          <label for='email'>Email or Username
          </label>
          <input type='email' class='form-control' id='email' placeholder='Enter email' name='username'>
        </div>
        <div class='form-group'>
          <label for='pass'>Password</label>
          <input type='password' class='form-control' id='pass' placeholder='Password' name='password'>
        </div>         
        <div class='form-group'>
        </div>                  
        <button type='submit' name='submit' class='btn btn-primary'>Login</button>
      </form>    
		</div>
	</div>
</section>
</body>
</html>