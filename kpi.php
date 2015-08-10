<?php
include("header.php");
//$page = "admin-login.php";
//pageview($client,$user,$page);

$result = $db->select("select COUNT(term_id) as kpis
from kpi
");while ($row = $db->get_row($result, 'MYSQL_ASSOC')) {	$kpis = stripslashes ($row["kpis"]);
	//if($timecount1 == ''){$timecount1 = 0;}
	//$timecount = number_format($timecount1,0);
	}
?>


<div class="container">

  <div class="content">

    <div class="content-container">

      

      <div class="content-header">
        <h2 class="content-header-title">KPIs</h2>
        <ol class="breadcrumb">
          <!--<li><a href="./index.html">Home</a></li>
          <li><a href="javascript:;">Extra Pages</a></li>-->
          <li class="active">List of Current KPIS</li>
        </ol>
      </div> <!-- /.content-header -->
       <div class="row">

        <div class="col-sm-6 col-md-3">
          <div class="row-stat">
            <p class="row-stat-label">KPIs</p>
            <h3 class="row-stat-value"><?php echo $kpis ?></h3>
            </div> <!-- /.row-stat -->
        </div> <!-- /.col -->
        
            </div> <!-- /.row -->

<?php
//GRID
$xcrud = Xcrud::get_instance();
$xcrud->table('kpi');
$xcrud->table_name('Data-KPIs');
//$xcrud->relation('user_id','user','user_id','name');
//$xcrud->where("","client_id = $client");
$xcrud->order_by('financial_category','asc');
//$xcrud->readonly('login');
$xcrud->columns('financial_category,name');
$xcrud->fields('financial_category,decision_category,name,description');
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