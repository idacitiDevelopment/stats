<?php
include("includes/session-start.php");
require_once ("includes/db.class-blog.php");
$db = new db_class;
include("sdba/sdba.php");
include("includes/mysql-connect.php");
$login_required = TRUE;
include("includes/user-verify.php");
include("xcrud/xcrud.php");
include("functions.php");
//date_default_timezone_set("Europe/London");
//include_once("includes/googleanalytics.php");


?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
  <title>Idaciti Stats</title>

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
  
  <!-- JAVASCRIPT -->
  
  <script src="./js/libs/jquery-1.10.1.min.js"></script>
  <script src="./js/libs/jquery-ui-1.9.2.custom.min.js"></script>
  <script src="./js/libs/bootstrap.min.js"></script>


  <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
  <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
  <![endif]-->
</head>

<body>

  <div class="navbar">

  <div class="container">

    <div class="navbar-header">

      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
        <i class="fa fa-cogs"></i>
      </button>
      <a class="navbar-brand navbar-brand-image" href="./index.html">
        <img src="./img/idaciti-logo.png" alt="Site Logo">
      </a>

    </div> <!-- /.navbar-header -->

    <div class="navbar-collapse collapse">

      

     

       



       

    </div> <!--/.navbar-collapse -->

  </div> <!-- /.container -->

</div> <!-- /.navbar -->

  <div class="mainbar">

  <div class="container">

    <button type="button" class="btn mainbar-toggle" data-toggle="collapse" data-target=".mainbar-collapse">
      <i class="fa fa-bars"></i>
    </button>

    <div class="mainbar-collapse collapse">

      <ul class="nav navbar-nav mainbar-nav">

        <li class="">
          <a href="home.php">
            <i class="fa fa-home"></i>
            Home
          </a>
        </li>
        
        <li class="dropdown ">
          <a href="#about" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown">
            <i class="fa fa-stack-exchange"></i>
            Data
            <span class="caret"></span>
          </a>

          <ul class="dropdown-menu">   
            
            <li><a href="data-company.php"><i class="fa fa-sitemap nav-icon"></i> Companies</a></li>
            <li><a href="data-kpi.php"><i class="fa fa-dashboard nav-icon"></i> KPIs</a></li>
             <li class="divider"></li>
            <li><a href="data-company-top20.php"><i class="fa fa-sitemap nav-icon"></i> Top 20 Companies</a></li>
            <li><a href="data-kpi-top20.php"><i class="fa fa-dashboard nav-icon"></i> Top 20 KPIs</a></li>
            
          </ul>
        </li>
        <li class="dropdown ">
          <a href="#about" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown">
            <i class="fa fa-tags"></i>
            Cards
            <span class="caret"></span>
          </a>

          <ul class="dropdown-menu">   
           <li><a href="card.php"><i class="fa fa-tags nav-icon"></i> Cards</a></li>
             <li class="divider"></li>
            <li><a href="chart-cards.php"><i class="fa fa-wrench nav-icon"></i> Created</a></li>
            <li><a href="chart-cardviews.php"><i class="fa fa-search nav-icon"></i> Viewed</a></li>              
          </ul>
        </li>
        <li class="dropdown ">
          <a href="#about" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown">
            <i class="fa fa-film"></i>
            Boards
            <span class="caret"></span>
          </a>

          <ul class="dropdown-menu">   
           <li><a href="board.php"><i class="fa fa-film nav-icon"></i> Boards</a></li>
           <li class="divider"></li>
           <li><a href="chart-boards.php"><i class="fa fa-wrench nav-icon"></i> Created</a></li> 
           <li><a href="chart-boardviews.php"><i class="fa fa-search nav-icon"></i> Viewed</a></li>
            
          </ul>
        </li>
        <li class="dropdown ">
          <a href="#about" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown">
            <i class="fa fa-circle-o"></i>
            Circles
            <span class="caret"></span>
          </a>

          <ul class="dropdown-menu">   
           
           <li><a href="circle.php"><i class="fa fa-group nav-icon"></i> Circles</a></li> 
            
          </ul>
        </li>
        <li class="dropdown ">
          <a href="#about" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown">
            <i class="fa fa-user"></i>
            Users
            <span class="caret"></span>
          </a>

          <ul class="dropdown-menu">   
           
           <li><a href="user.php"><i class="fa fa-group nav-icon"></i> Users</a></li> 
            <li class="divider"></li>
            <li><a href="chart-signups.php"><i class="fa fa-pencil nav-icon"></i> Signups</a></li>
           <li><a href="user-card-top20.php"><i class="fa fa-tags nav-icon"></i> Top 20-Cards</a></li> 
            <li><a href="user-board-top20.php"><i class="fa fa-film nav-icon"></i> Top 20-Boards</a></li>
            <li class="divider"></li>
            <li><a href="user-internal.php"><i class="fa fa-group nav-icon"></i> Internal Users</a></li>
            
            
          </ul>
        </li>
        <li class="">
          <a href="blog.php">
            <i class="fa fa-pencil"></i>
            Blog
          </a>
        </li>
        <li class="">
          <a href="logout.php">
            <i class="fa fa-power-off"></i>
            Logout
          </a>
        </li>

        

      </ul>

    </div> <!-- /.navbar-collapse -->   

  </div> <!-- /.container --> 

</div> <!-- /.mainbar -->