<?php	
//USER DATA
$user = $_SESSION["user"];
$client = $_SESSION["client"];		
$user_first = $_SESSION["first"];
$user_last = $_SESSION["last"];
$user_title = $_SESSION["title"];
$user_tel = $_SESSION["telephone"];
$user_mobile = $_SESSION["mobile"];
$username = $_SESSION["username"];
$user_email = $_SESSION["useremail"];
$user_token = $_SESSION["usertoken"];
$language = $_SESSION["language"];
$company = $_SESSION["company"];
$company_name = $_SESSION["company_name"];

//LOGIN
$login = $_SESSION["login"];
$ip_address = $_SESSION["ip_address"];
$avatar = $_SESSION["avatar"];
$avatar = "/upload/avatar/".$avatar;

//USER RIGHTS
$admin = $_SESSION["admin"];	
$sysadmin = $_SESSION["sysadmin"];
$role = $_SESSION["role"];
$role_name = $_SESSION["rolename"];
$group = $_SESSION["group"];

//CLIENT
$client_name = $_SESSION["clientname"];
$client_email = $_SESSION["clientemail"];


//LOGIN CHECKif (empty ($login) && $login_required == TRUE) 
{ 
//REDIRECT
$redirect = "index.php";
echo '<script type="text/javascript">alert(\'Login required to access this page\');</script>';
redirect($redirect); 
}

?>