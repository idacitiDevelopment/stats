<?php
include("header.php");
$page = "admin-recent.php";
pageview($client,$user,$page);
?>


<div class="container">

  <div class="content">

    <div class="content-container">

      

      <div class="content-header">
        <h2 class="content-header-title">Admin | Recent Signups</h2>
        <ol class="breadcrumb">
          <!--<li><a href="./index.html">Home</a></li>
          <li><a href="javascript:;">Extra Pages</a></li>-->
          <li class="active">Track last 50 user signups.</li>
        </ol>
      </div> <!-- /.content-header -->

<?php
//GRID
$xcrud = Xcrud::get_instance();
$xcrud->table('user');
$xcrud->table_name('Recent User Signups');
//$xcrud->relation('user_id','user','user_id','name');
$xcrud->where("","client_id = $client");
$xcrud->order_by('updated','desc');
$xcrud->readonly('updated');
$xcrud->columns('name,email,updated');
//$xcrud->fields('comment,active');
//$xcrud->subselect('suppliers','SELECT COUNT(supplier_id) FROM supplier WHERE category_id = {category_id}');
$xcrud->theme('bootstrap');
$xcrud->unset_title();
$xcrud->limit(50);
$xcrud->emails_label('Send');
$xcrud->label('user_id', 'User');
$xcrud->unset_add();
$xcrud->unset_edit();
$xcrud->unset_view();
$xcrud->unset_remove();
$xcrud->unset_csv();
$xcrud->unset_limitlist();
//$xcrud->unset_search();
//GRID
echo $xcrud->render();
?>

      

    </div> <!-- /.content-container -->
      
  </div> <!-- /.content -->

</div> <!-- /.container -->

<?php 
include("footer.php");
?>