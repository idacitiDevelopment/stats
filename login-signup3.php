<?php
include("header-nolog.php");
if ($_GET["id"]) { $user_token = $_GET["id"]; }

//GET CLIENT
$db = Sdba::db();
$db -> query("SELECT client_id FROM client WHERE token = '$user_token'");
$row = $db -> row();
$user_client = $row['client_id'];

//GET USER
$db = Sdba::db();
$db -> query("SELECT user_id FROM user WHERE token = '$user_token'");
$row = $db -> row();
$user_id = $row['user_id'];

if($user_id > 0)
{
//UPDATE USER ACCOUNT
$db=Sdba::db();
$db->query("UPDATE user SET active = 1,confirmed = 1,client_id = $user_client where user_id = $user_id");

//INSERT AUDIT
$db=Sdba::db();
$db->query("INSERT INTO user_audit (client_id,user_id,type,entity) VALUES ($user_client,$user_id,'Confirmed','Account')");	

//REDIRECT
$redirect = "login.php";
echo '<script type="text/javascript">alert(\'Your account is confirmed. You can now login.\');</script>';
redirect($redirect);
} else {
$redirect = "login.php";
echo '<script type="text/javascript">alert(\'Your account confirmation failed. Please try again.\');</script>';
redirect($redirect);	
}
?>