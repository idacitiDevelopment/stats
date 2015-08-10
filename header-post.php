<?php
include("includes/session-start.php");
require_once ("includes/db.class.php");
$db = new db_class;
include("sdba/sdba.php");
include("includes/mysql-connect.php");
//include("mysql-connect-old.php");
include("includes/form-extend.php");
include("includes/user-verify.php");
date_default_timezone_set("Europe/London");
//REDIRECT
function redirect($redirect){
    if (headers_sent()){
      die('<script type="text/javascript">window.location=\'' . $redirect . '\';</script>');
    }else{
      header('Location: ' . $redirect);
      die();
    }    
}
//TOKEN
function token(){
$token=sha1(uniqid(mt_rand(), true));
return $token;       
}
?>