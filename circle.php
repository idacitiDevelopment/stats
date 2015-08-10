<?php
include("header.php");
//$page = "admin-login.php";
//pageview($client,$user,$page);
?>


<div class="container">

  <div class="content">

    <div class="content-container">

      

      <div class="content-header">
        <h2 class="content-header-title">Circles</h2>
        <ol class="breadcrumb">
          <!--<li><a href="./index.html">Home</a></li>
          <li><a href="javascript:;">Extra Pages</a></li>-->
          <li class="active">List of User Circles</li>
        </ol>
      </div> <!-- /.content-header -->

<?php
//GRID
$xcrud = Xcrud::get_instance();
$xcrud->table('circle');
$xcrud->table_name('User Circles');
$xcrud->relation('admin','user','id',array('first_name','last_name'));
//$xcrud->where("","is_active = 1 AND is_root = 0");
$xcrud->order_by('name','asc');
//$xcrud->readonly('login');
$xcrud->columns('name,admin,Members');
//$xcrud->fields('financial_category,decision_category,name,description');
$xcrud->subselect('Members','SELECT COUNT(user) FROM user_circle WHERE circle = {id}');
$xcrud->theme('bootstrap');
$xcrud->unset_title();
$xcrud->label('user_ip', 'IP Address');
$xcrud->emails_label('Send');
$xcrud->unset_add();
$xcrud->unset_edit();
$xcrud->unset_view();
$xcrud->unset_remove();
$xcrud->unset_csv();
$xcrud->unset_limitlist();
$xcrud->unset_search();
//GRID
echo $xcrud->render();
?>

      

    </div> <!-- /.content-container -->
      
  </div> <!-- /.content -->

</div> <!-- /.container -->

<?php 
//include("footer.php");
?>