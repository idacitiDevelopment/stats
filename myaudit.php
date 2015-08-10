<?php
include("header.php");
$page = "myaudit.php";
pageview($client,$user,$page);
?>


<div class="container">

  <div class="content">

    <div class="content-container">

      

      <div class="content-header">
        <h2 class="content-header-title">My Audit</h2>
        <ol class="breadcrumb">
          <!--<li><a href="./index.html">Home</a></li>
          <li><a href="javascript:;">Extra Pages</a></li>-->
          <li class="active">Track my activity against database tables.</li>
        </ol>
      </div> <!-- /.content-header -->

     <?php
//GRID
$xcrud = Xcrud::get_instance();
$xcrud->table('user_audit');
$xcrud->table_name('User Audit History');
$xcrud->relation('user_id','user','user_id','name');
$xcrud->where("","client_id = $client and user_id = $user");
$xcrud->order_by('updated','desc');
$xcrud->readonly('updated');
$xcrud->columns('type,entity,updated');
//$xcrud->fields('comment,active');
//$xcrud->subselect('suppliers','SELECT COUNT(supplier_id) FROM supplier WHERE category_id = {category_id}');
$xcrud->theme('bootstrap');
$xcrud->unset_title();
$xcrud->label('user_id', 'User');
$xcrud->unset_add();
$xcrud->unset_edit();
$xcrud->unset_view();
$xcrud->unset_remove();
$xcrud->unset_csv();
//$xcrud->unset_limitlist();
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