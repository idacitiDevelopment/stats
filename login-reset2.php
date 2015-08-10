<?php
include("header-nolog.php");
if ($_GET["id"]) { $user_token = $_GET["id"]; }

?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
  <title>Login - Target Admin</title>

  <meta charset="utf-8">
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width">

  <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,700italic,400,600,700">
  <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Oswald:400,300,700">
  <link rel="stylesheet" href="./css/font-awesome.min.css">
  <link rel="stylesheet" href="./js/libs/css/ui-lightness/jquery-ui-1.9.2.custom.min.css">
  <link rel="stylesheet" href="./css/bootstrap.min.css">

    <!-- App CSS -->
  <link rel="stylesheet" href="./css/target-admin.css">
  <link rel="stylesheet" href="./css/custom.css">


  <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
  <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
  <![endif]-->
</head>

<body class="account-bg">

<div class="navbar">

  <div class="container">
  
<?php if($_POST)
{
if(!empty($_POST))
{
$pin = $_POST["pin"];
//$pin = mysql_real_escape_string($pin);
$new_pin = SHA1($pin);

//GET USER
$db = Sdba::db();
$db -> query("SELECT * FROM user WHERE token = '$user_token'");
$row = $db -> row();
$user_client = $row['client_id'];
$user_id = $row['user_id'];

//UPDATE PASSWORD
$db=Sdba::db();
$db->query("UPDATE user SET password = '$new_pin' where user_id = $user_id");

//INSERT AUDIT
$db=Sdba::db();
$db->query("INSERT INTO user_audit (client_id,user_id,type,entity) VALUES ($user_client,$user_id,'Reset','Password')");	

//REDIRECT
$redirect = "login.php";
echo '<script type="text/javascript">alert(\'Your Password was reset.\');</script>';
redirect($redirect);
} else {
$redirect = "login.php";
echo '<script type="text/javascript">alert(\'Your Password reset failed. Please try again.\');</script>';
redirect($redirect);	
} }
?>
    <div class="navbar-header">

      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
        <i class="fa fa-cogs"></i>
      </button>

      <a class="navbar-brand navbar-brand-image" href="./index.html">
        <img src="./img/logo.png" alt="Site Logo">
      </a>

    </div> <!-- /.navbar-header -->

    <div class="navbar-collapse collapse">

      



       
        

      <ul class="nav navbar-nav navbar-right">   

        <li>
          <a href="javascript:;">
            <i class="fa fa-angle-double-left"></i> 
            &nbsp;Back to Homepage
          </a>
        </li> 

      </ul>
       

    </div> <!--/.navbar-collapse -->

  </div> <!-- /.container -->

</div> <!-- /.navbar -->

<hr class="account-header-divider">

<div class="account-wrapper">

  <div class="account-logo">
    <img src="./img/logo-login.png" alt="Target Admin">
  </div>

    <div class="account-body">

      <h3 class="account-body-title">Reset Your Password</h3>

      <h5 class="account-body-subtitle">Enter a new password</h5>

      <form class="form account-form" method="POST" action="login-reset2.php?id=<?php echo $user_token ?>">

        <div class="form-group">
          <label for="pin" class="placeholder-hidden">Password</label>
          <input type="password" class="form-control" name="pin" id="pin" placeholder="Your new password" tabindex="2">
        </div> <!-- /.form-group -->

    

        <div class="form-group">
          <button type="submit" class="btn btn-primary btn-block btn-lg" tabindex="4">
            Reset &nbsp; <i class="fa fa-play-circle"></i>
          </button>
        </div> <!-- /.form-group -->

      </form>


    </div> <!-- /.account-body -->

   

  </div> <!-- /.account-wrapper -->



        

  <script src="./js/libs/jquery-1.10.1.min.js"></script>
  <script src="./js/libs/jquery-ui-1.9.2.custom.min.js"></script>
  <script src="./js/libs/bootstrap.min.js"></script>

  <!--[if lt IE 9]>
  <script src="./js/libs/excanvas.compiled.js"></script>
  <![endif]-->
  <!-- App JS -->
  <script src="./js/target-admin.js"></script>
  
  <!-- Plugin JS -->
  <script src="./js/target-account.js"></script>

  


  

</body>
</html>
