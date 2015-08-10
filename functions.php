<?php
//TODAY
date_default_timezone_set("Europe/London");
$today = date("Y-m-d");

//PAGEVIEW

function pageview($client,$user,$page)
{
$db=Sdba::db();
$db->query("INSERT INTO pageview (client_id,user_id,page) VALUES ($client,$user,'$page')");		
}

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
