<?php
include("header-nolog.php");
?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
  <title>Login - Idaciti Stats</title>

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

    <div class="navbar-header">

      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
        <i class="fa fa-cogs"></i>
      </button>

      <!--<a class="navbar-brand navbar-brand-image" href="login.php">
        <img src="./img/idaciti.png" alt="Site Logo">
      </a>-->

    </div> <!-- /.navbar-header -->

    <div class="navbar-collapse collapse">

      



       
        

      <ul class="nav navbar-nav navbar-right">   

        <!--<li>
          <a href="javascript:;">
            <i class="fa fa-angle-double-left"></i> 
            &nbsp;Back to Homepage
          </a>
        </li>--> 

      </ul>
       

    </div> <!--/.navbar-collapse -->

  </div> <!-- /.container -->

</div> <!-- /.navbar -->

<hr class="account-header-divider">

<div class="account-wrapper">

  <!--<div class="account-logo">
    <img src="./img/idaciti.png" width= 100px height= 100px alt="Target Admin">
  </div>-->

    <div class="account-body">

      <h3 class="account-body-title">Welcome to idaciti App Stats</h3>

      <h5 class="account-body-subtitle">Please sign in.</h5>

      <form class="form account-form" method="POST" action="login-post.php">

        <div class="form-group">
          <label for="email" class="placeholder-hidden">Email</label>
          <input type="text" class="form-control" name="email" id="email" placeholder="Your email" tabindex="1">

        </div> <!-- /.form-group -->

        <div class="form-group">
          <label for="pin" class="placeholder-hidden">Password</label>
          <input type="password" class="form-control" name="pin" id="pin" placeholder="Your password" tabindex="2">
        </div> <!-- /.form-group -->

        <div class="form-group clearfix">
          <!--<div class="pull-left">         
            <label class="checkbox-inline">
            <input type="checkbox" class="" value="" tabindex="3"> Remember Me
            </label>
          </div>

          <div class="pull-right">
            <a href="./login-forgot.php">Forgot Password?</a>
          </div>-->
        </div> <!-- /.form-group -->

        <div class="form-group">
          <button type="submit" class="btn btn-success btn-block btn-lg" tabindex="4">
            Signin &nbsp; <i class="fa fa-play-circle"></i>
          </button>
        </div> <!-- /.form-group -->

      </form>


    </div> <!-- /.account-body -->

    <div class="account-footer">
      <!--<p>
      Don't have an account? &nbsp;
      <a href="login-signup.php" class="">Create an Account!</a>
      </p>-->
    </div> <!-- /.account-footer -->

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