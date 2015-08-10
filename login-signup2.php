<?php 
include("includes/session-start.php");
require_once ("includes/db.class.php");
$db = new db_class;
include('sdba/sdba.php'); 
include('includes/mysql-connect.php'); 
include('includes/form-extend.php');
date_default_timezone_set("Europe/London");
$today = date("Y-m-d");

//TOKEN
function token()
{
$token=sha1(uniqid(mt_rand(), true));
return $token;       
}
$user_token = token();

//REDIRECT
function redirect($redirect){
    if (headers_sent()){
      die('<script type="text/javascript">window.location=\'' . $redirect . '\';</script>');
   	 }else{
      header('Location: ' . $redirect);
      die();
    }    
}

//POST
$email = $_POST["email"];
$signup_email = mysql_real_escape_string($email);
$name = $_POST["name"];
$signup_name = mysql_real_escape_string($name);
$pin = $_POST["pin"];
$agree = $_POST["agree"];
$agree = mysql_real_escape_string($agree);
$pin = mysql_real_escape_string($pin);
$signup_pin = SHA1($pin);

//CHECK AGREE
if($agree != 1)
{
$redirect = "login-signup.php";
echo '<script type="text/javascript">alert(\'Your Signup Failed. You must agree to the terms and conditions.\');</script>';
redirect($redirect);
}

//CHECK EMAIL
if (!filter_var($signup_email, FILTER_VALIDATE_EMAIL)) 
{
$redirect = "login-signup.php";
echo '<script type="text/javascript">alert(\'Your Signup Failed. You used an invalid email address.\');</script>';
redirect($redirect);}

//CHECK EMAIL DUPLICATE
$db = Sdba::db();
$db -> query("SELECT email FROM user WHERE email = '$signup_email' AND active = 1");
$row = $db -> row();
if($row['email'] != '')
{
$redirect = "login-signup.php";
echo '<script type="text/javascript">alert(\'Your Signup Failed. Your email is already in use.\');</script>';
redirect($redirect);
}

//GET APPLICATION
$db = Sdba::db();
$db -> query("SELECT * FROM application");
$row = $db -> row();
{
	$appname = stripslashes ($row["name"]);
	$appadmin = stripslashes ($row["admin"]);
	$appemail = stripslashes ($row["email"]);
	$appurl = stripslashes ($row["url"]);
	$applogo = stripslashes ($row["logo"]);
	$applogo = $appurl."/upload/logo/".$applogo;
}			
//INSERT USER
$db=Sdba::db();
$db->query("INSERT INTO user (name,email,password,inserted,token) VALUES ('$signup_name','$signup_email','$signup_pin','$today','$user_token')");

//INSERT CLIENT
$db=Sdba::db();
$db->query("INSERT INTO client (admin,admin_email,inserted,token) VALUES ('$signup_name','$signup_email','$today','$user_token')");

$confirmlink = $appurl."/login-signup3.php?id=".$user_token;
		
		//EMAIL USER TO CONFIRM
		$to = "$signup_email";
		$from = "$appemail";
		$headers = "From:" . $from;
		$subject = $appname." Signup Confirmation";
		$body =
		"
		Dear $signup_name: \n
		Please click the link below to confirm your account: \n\n
		
		$confirmlink\n\n
		
		Regards \n
		$appname Administrator";		

		//send email
		mail($to, $subject, $body, $headers);
						
$redirect = "login.php";
echo '<script type="text/javascript">alert(\'An account confirmation email has been sent to you.\');</script>';
redirect($redirect);
?>