<?php 
/**
 * ====================================================================================
 *                           PREMIUM URL SHORTENER (c) KBRmedia
 * ----------------------------------------------------------------------------------
 * @copyright This software is exclusively sold at CodeCanyon.net. If you have downloaded this
 *  from another site or received it from someone else than me, then you are engaged
 *  in an illegal activity. You must delete this software immediately or buy a proper
 *  license from http://gempixel.com/buy/short.
 *
 *  Thank you for your cooperation and don't hesitate to contact me if anything :)
 * ====================================================================================
 *
 * @author KBRmedia (http://gempixel.com)
 * @link http://gempixel.com 
 * @package Premium URL Shortener
 * @subpackage Main Helper Class (Main.class.php)
 */
class Main{
    protected static $title="";
    protected static $description="";
    protected static $url="";
    protected static $image="";
    protected static $video="";
    protected static $body_class="";
    protected static $lang="";
    protected static $plugin=array();
    private static $config=array();
  /**

  * Clean a string
  * @param string, cleaning level (1=lowest,2,3=highest)
  * @return cleaned string
  */

    public static function clean($string,$level='1',$chars=FALSE,$leave=""){        
        if(is_array($string)) return array_map("Main::clean",$string);

        $string=preg_replace('/<script[^>]*>([\s\S]*?)<\/script[^>]*>/i', '', $string);      
        switch ($level) {
          case '3':
            $search = array('@<script[^>]*?>.*?</script>@si',
                           '@<[\/\!]*?[^<>]*?>@si',
                           '@<style[^>]*?>.*?</style>@siU',
                           '@<![\s\S]*?--[ \t\n\r]*>@'
            ); 
            $string = preg_replace($search, '', $string);           
            $string=strip_tags($string,$leave);      
            if($chars) {
              if(phpversion() >= 5.4){
                $string=htmlspecialchars($string, ENT_QUOTES | ENT_HTML5,"UTF-8");  
              }else{
                $string=htmlspecialchars($string, ENT_QUOTES,"UTF-8");  
              }
            }
            break;
          case '2':
            $string=strip_tags($string,'<b><i><s><u><strong><span>');
            break;
          case '1':
            $string=strip_tags($string,'<b><i><s><u><strong><a><pre><code><p><div><span>');
            break;
        }   
        $string=str_replace('href=','rel="nofollow" href=', $string);   
        return $string; 
    }
  /**

  /**
   * Get IP
   * @since 1.0 
   **/
  public static function ip(){
     $ipaddress = '';
      if(isset($_SERVER['HTTP_CF_CONNECTING_IP'])) {
          $ipaddress =  $_SERVER['HTTP_CF_CONNECTING_IP'];
      } else if (isset($_SERVER['HTTP_X_REAL_IP'])) {
          $ipaddress = $_SERVER['HTTP_X_REAL_IP'];
      }
      else if (isset($_SERVER['HTTP_CLIENT_IP']))
          $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
      else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
          $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
      else if(isset($_SERVER['HTTP_X_FORWARDED']))
          $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
      else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
          $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
      else if(isset($_SERVER['HTTP_FORWARDED']))
          $ipaddress = $_SERVER['HTTP_FORWARDED'];
      else if(isset($_SERVER['REMOTE_ADDR']))
          $ipaddress = $_SERVER['REMOTE_ADDR'];
      else
          $ipaddress = 'UNKNOWN';
      return $ipaddress;
  }
  /**

  /**
  * Encode string
  * @param string, encode= MD5, SHA1 or SHA256 
  * @return hash
  */   
    public static function encode($string,$encoding="phppass"){      
      if($encoding=="phppass"){
        if(!class_exists("PasswordHash")) require_once(ROOT."/includes/class-phpass.php");
        $e = new PasswordHash(8, FALSE);
        return $e->HashPassword($string);
      }else{
        return hash($encoding,$string);
      }
    }
	
	public static function encodedd($string,$encoding="phppass"){      
      if($encoding=="phppass"){
        if(!class_exists("PasswordHash")) require_once(ROOT."/includes/class-phpass.php");
        $e = new PasswordHash(8, FALSE);
        return $e->HashPassword($string);
      }else{
        return hash($encoding,$string);
      }
    }
	
  /**
  * Check Password
  * @param string, encode= MD5, SHA1 or SHA256 
  * @return hash
  */   
    
	
	public static function validate_pass($string, $hash) {
		if(!class_exists("PasswordHash")) {
        	require_once( ROOT. "/includes/class-phpass.php");
	        // By default, use the portable hash from phpass
	        $check = new PasswordHash(8, true);
		}
	    return $check->CheckPassword($string, $hash);
	}
/**
 * Read user cookie and extract user info
 * @param 
 * @return array of info
 * @since v1.0
 */
  public static function user(){
    if(isset($_COOKIE["login"])){
      $data=json_decode(base64_decode($_COOKIE["login"]),TRUE);
    }elseif(isset($_SESSION["login"])){
      $data=json_decode(base64_decode($_SESSION["login"]),TRUE);     
    }
    if(isset($data["loggedin"]) && !empty($data["key"])){  
      return array(self::clean(substr($data["key"],60)),self::clean(substr($data["key"],0,60)));
    }     
    return FALSE;  
  }    
  /**
  * Generate api or random string
  * @param length, start
  * @return 
  */    
    public static function strrand($length=12,$api=""){    
        $use = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890"; 
        srand((double)microtime()*1000000); 
        for($i=0; $i<$length; $i++) { 
          $api.= $use[rand()%strlen($use)]; 
        } 
      return $api; 
    }
	
 	public static function href($default,$base=TRUE){      
      return (!$base)?"$default":"".self::$config["url"]."/$default";
    }
  /**
  * Redirect function
  * @param url/path (not including base), message and header code
  * @return nothing
  */   

    public static function redirect($message=array(),$url,$header="",$fullurl=FALSE){      

      if(!empty($message)){      
        $_SESSION["msg"]=self::clean("{$message[0]}::{$message[1]}",2);
      } 
	  if(!empty($url)) { 
      header("Location: $url");
	  }
      exit;
    }

  /**
  * Notification Function
  * @param none
  * @return message
  */     

    public static function message(){
      if(isset($_SESSION["msg"]) && !empty($_SESSION["msg"])) {
        $message=explode("::",self::clean($_SESSION["msg"],2));
          $message="<div class='alert alert-{$message[0]}'>{$message[1]}</div>";
          unset($_SESSION["msg"]);
      }else {
        $message="";
      }
      return $message;
    }

  /**
  * Show error message
  * @param message
  * @return formatted message
  */  
    public static function error($message){
      return "<div class='alert alert-danger'>$message</div>";
    }
	public static function success($message){
      return "<div class='alert alert-success'>$message</div>";
    }


  /**
  * Ajax Button
  * @param type, max number of page, current page, url, text, class
  * @return formatted button
  */ 
    public static function ajax_button($type, $max, $current,$url,$text='',$class="ajax_load"){
      if($current >= $max) return FALSE;
      return "<a href='$url' data-page='$current' data-type='$type' class='button fullwidth $class'>Load More $text</a>";
    }
  
  /**
   * Translate strings
   * @since v1.0
   */   
    public static function e($text){
      if(!is_array(Main::$lang)) return $text;
      if(isset(Main::$lang[$text]) && !empty(Main::$lang[$text])) {
        return ucfirst(Main::$lang[$text]);
      }
      return $text;    
    }  
	
	public static function database($op, $tabel, $set, $where, $kolom){      
		if($op == "select"){
			if(isset($set)){
				$aahasil = mysqli_query($connection, "$op * from $tabel");
				$hasil = mysqli_fetch_array($aahasil);
			}
			$aahasil = mysqli_query($connection, "$op * from $tabel where $set");
			$hasil = mysqli_fetch_array($aahasil);
		} else {
			$hasil = mysqli_query($connection, "$op $tabel $set where $where");
		}
		return $hasil[$kolom];
    }
	
}