<?php 
include("includes/session-start.php");
require_once ("includes/db.class.php");
$db = new db_class;
include('sdba/sdba.php'); 
include('includes/mysql-connect.php'); 
include('includes/form-extend.php');

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
$login_email = mysql_real_escape_string($email);
$pin = $_POST["pin"];
$login_pin = mysql_real_escape_string($pin);
//$login_pin = SHA1($pin);

//CHECK
$db = Sdba::db();
$db -> query("SELECT * FROM user WHERE email = '$login_email' AND password = '$login_pin' AND is_active = 1 AND is_root = 1");
$row = $db -> row();
if($row['id'] == '')
{
$redirect = "login.php";
echo '<script type="text/javascript">alert(\'Your Login Failed or is not authorized. Please Try Again.\');</script>';
redirect($redirect);
}
//echo $login_email;
//echo $login_pin;
//echo 'true';
if(strcasecmp($row['email'], $login_email) === 0 && $row['password'] == $login_pin)
			{
				
				//USER DATA
				//$_SESSION["username"] = $row["name"];
				//$_SESSION["nickname"] = $row["nickname"];
				//$_SESSION["avatar"] = $row["avatar"];
				//$_SESSION["user"] = $row["user_id"];
				//$_SESSION["client"] = $row["client_id"];
				//$_SESSION["language"] = $row["language_id"];
				//$_SESSION["company"] = $row["company_id"];
				//$_SESSION["useremail"] = $row["email"];
				//$_SESSION["first"] = $row["first"];
				//$_SESSION["last"] = $row["last"];
				//$_SESSION["title"] = $row["title"];
				//$_SESSION["telephone"] = $row["telephone"];
				//$_SESSION["mobile"] = $row["mobile"];
				
				//LOGIN
				//$_SESSION['ip_address'] = $_SERVER['REMOTE_ADDR'];
				//$_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
				//$_SESSION['last_access'] = time();
				$_SESSION["login"] = TRUE;
				
				//RIGHTS
				//$_SESSION["level"] = $row["level"];
				//$_SESSION["admin"] = $row["admin"];
				//$_SESSION["sysadmin"] = $row["sysadmin"];
				//$_SESSION["role"] = $row["role_id"];
				//$_SESSION["contact"] = $row["contact"];
				//$_SESSION["address"] = $row["address"];
				//$_SESSION["division"] = $row["division"];
				//$_SESSION["finance"] = $row["finance"];
				//$_SESSION["sales"] = $row["sales"];
				//$_SESSION["service"] = $row["service"];
				//$_SESSION["marketing"] = $row["marketing"];
				//$_SESSION["group"] = $row["group"];
				
				//VARIABLES
				//$user_id = $row["user_id"];
				//$user_ip = $_SERVER['REMOTE_ADDR'];
				//$user_client = $row["client_id"];
				//$user_roleid = $row["role_id"];
				
				//GET CLIENT
				//$db = Sdba::db();
				//$db -> query("SELECT * FROM client WHERE client_id = $user_client");
				//$row = $db -> row();

				//$_SESSION["clientname"] = stripslashes ($row['name']);
				//$_SESSION["clientemail"] = stripslashes ($row['admin_email']);
				//$_SESSION["clientsymbol"] = stripslashes ($row['symbol']);
				
				//GET ROLENAME
				//$db = Sdba::db();
				//$db -> query("SELECT name FROM sys_role WHERE role_id = $user_roleid");
				//$row = $db -> row();
				//$_SESSION["rolename"] = stripslashes ($row['name']);
			
//INSERT LOGIN
//$db=Sdba::db();
//$db->query("INSERT INTO user_login (client_id,user_id,user_ip) VALUES ($user_client,$user_id,'$user_ip')");			
				
				if($_SESSION["sysadmin"] == 1)
				{
				header("location: home.php");
				} else {
				header("location: home.php");}
			
		} else {
$redirect = "login.php";
echo '<script type="text/javascript">alert(\'Your Login Failed Authorization Check. Please Try Again.\');</script>';
redirect($redirect);
}

?>