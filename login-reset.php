<?php
include("header-nolog.php");
$today = date('Y-m-d');

//POST
$email = $_POST["email"];
//$email = mysql_real_escape_string($email);
//$pin = randomPassword();
//$password = sha1($pin);

//GET APPLICATION
$result = $db->select("select *
from application
");
while ($row = $db->get_row($result, 'MYSQL_ASSOC')) 
{
	$appname = stripslashes ($row["name"]);
	$appadmin = stripslashes ($row["admin"]);
	$appemail = stripslashes ($row["email"]);
	$appurl = stripslashes ($row["url"]);
	$applogo = stripslashes ($row["logo"]);
	$applogo = $appurl."/upload/logo/".$applogo;
}


//CHECK USER
$result = $db->select("select token,name 
from user
where email = '$email'
");
while ($row = $db->get_row($result, 'MYSQL_ASSOC')) 
{
	$usertoken = stripslashes ($row["token"]);
	$username = stripslashes ($row["name"]);
}

if($usertoken != "")
{
$resetlink = $appurl."/login-reset2.php?id=".$usertoken;
		
		//EMAIL USER TO CONFIRM
		$to = "$email";
		$from = "$appemail";
		$headers = "From:" . $from;
		$subject = $appname." Password Reset";
		$body =
		"
		Dear $username: \n
		Please click the link below to reset your password: \n\n
		
		$resetlink\n\n
		
		Regards \n
		$appname Administrator";		

		//send email
		mail($to, $subject, $body, $headers);
		
//REDIRECT
$redirect = "login.php";
echo '<script type="text/javascript">alert(\'Your password reset link has been emailed to you.\');</script>';
redirect($redirect);
} else {
$redirect = "login.php";
echo '<script type="text/javascript">alert(\'Your password could not be reset. Please try again.\');</script>';
redirect($redirect);	
}		
		?>