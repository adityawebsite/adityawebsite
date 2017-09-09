<?php 
/**
 * ====================================================================================
 *                           Premium URL Shortener (c) KBRmedia
 * ----------------------------------------------------------------------------------
 * @copyright This software is exclusively sold at CodeCanyon.net. If you have downloaded this
 *  from another site or received it from someone else than me, then you are engaged
 *  in an illegal activity. You must delete this software immediately or buy a proper
 *  license from http://codecanyon.net/user/KBRmedia/portfolio?ref=KBRmedia.
 *
 *  Thank you for your cooperation and don't hesitate to contact me if anything :)
 * ====================================================================================
 *
 * @author KBRmedia (http://gempixel.com)
 * @link http://gempixel.com 
 * @license http://gempixel.com/license
 * @package Premium URL Shortener
 * @subpackage Configuration File
 */

 if(!isset($_SESSION)){
	  session_start();
	}
// Database Configuration
  $dbinfo = array(
    "host" => 'localhost',        // Your mySQL Host (usually Localhost)
    "db" => 'adityaw1_wp197',            // The database where you have dumped the included sql file
    "user" => 'root',        // Your mySQL username
    "password" => '',    //  Your mySQL Password 
    "prefix" => ''      // Prefix for your tables if you are using same db for multiple scripts, e.g. short_
  );

  $config = array(
    // Your Server's Timezone - List of available timezones (Pick the closest): https://php.net/manual/en/timezones.php
    "timezone" => "Asia/Jakarta",
    // Cache Data - If you notice anomalies, disable this. You should enable this when you get high hits
    "cache" => FALSE,
    // Use CDN to host libraries for faster loading
    "cdn" => TRUE,
    // Enable Compression? Makes your website faster
    "gzip" => TRUE,
    /*
     ====================================================================================
     *  Security Key & Token - Please don't change this if your site is live.
     * ----------------------------------------------------------------------------------
     *  - Setup a security phrase - This is used to encode some important user 
     *    information such as password. The longer the key the more secure they are.
     *
     *  - If you change this, many things such as user login and even admin login will 
     *    fail.
     *
     *  - If the two config below don't have any values or have 2b184cb4c493c06c5e4d2fbf41f67025 or d5abd6d24a54ccc8907065c266d07e97, replace these by a random key.
     ====================================================================================
    */
    "security" => '2b184cb4c493c06c5e4d2fbf41f67025',  // !!!! DON'T CHANGE THIS IF YOUR SITE IS LIVE !!!!
    "public_token" => 'd5abd6d24a54ccc8907065c266d07e97', // This is randomly generated and it is a public key

    "debug" => 0,   // Enable debug mode (outputs errors) - 0 = OFF, 1 = Error message, 2 = Error + Queries (Don't enable this if your site is live!)
    "demo" => 0 // Demo mode
  );

//Note: The following fields should be filled as follows e.g. define('ga_email','test@gmail.com')
define('ga_email','');  // Your google analytics email
define('ga_password','');  // Your google analytics password
define('ga_profile_id','');  // Your google analytics profile id (now known as view ID) not account id (See documentation)
define('folder','');  // If you installed this script in a folder, you add the path of the folder here e.g. /short

// Connect to database
	include(ROOT."/includes/Database.class.php");	
	$db = new Database($config, $dbinfo);
	$config=$db->get_config();	
	$config["smtp"]=json_decode($config["smtp"],TRUE);
	$config["prefix"] = $dbinfo["prefix"];
	
// Read string function
	function e($text){
		return Main::e($text);
	}
?>