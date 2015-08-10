<?php
include("header.php");
//$page = "admin-login.php";
//pageview($client,$user,$page);
?>


<div class="container">

  <div class="content">

    <div class="content-container">

      

      <div class="content-header">
        <h2 class="content-header-title">Companies</h2>
        <ol class="breadcrumb">
          <!--<li><a href="./index.html">Home</a></li>
          <li><a href="javascript:;">Extra Pages</a></li>-->
          <li class="active">List of Current Companies</li>
        </ol>
      </div> <!-- /.content-header -->

<?php
//GRID
$xcrud = Xcrud::get_instance();
$xcrud->table('company');
$xcrud->table_name('Data-Companies');
//$xcrud->relation('user_id','user','user_id','name');
//$xcrud->where("","client_id = $client");
$xcrud->order_by('company_name','asc');
//$xcrud->readonly('login');
$xcrud->columns('company_name,industry,sector');
$xcrud->fields('company_name,industry,sector,cik,sic,sic_code,state,stock_symbol');
//$xcrud->fields('comment,active');
//$xcrud->subselect('suppliers','SELECT COUNT(supplier_id) FROM supplier WHERE category_id = {category_id}');
$xcrud->theme('bootstrap');
$xcrud->unset_title();
$xcrud->label('user_ip', 'IP Address');
$xcrud->label('user_id', 'User');
$xcrud->unset_add();
$xcrud->unset_edit();
//$xcrud->unset_view();
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
//include("footer.php");
?>