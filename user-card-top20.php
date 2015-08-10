<?php
include("header.php");
//$page = "admin-pageview.php";
//pageview($client,$user,$page);
?>


<div class="container">

  <div class="content">

    <div class="content-container">

      

      <div class="content-header">
        <h2 class="content-header-title">Top 20 Card Creators</h2>
        <ol class="breadcrumb">
          <!--<li><a href="./index.html">Home</a></li>
          <li><a href="javascript:;">Extra Pages</a></li>-->
          <li class="active">The top 20 users as card creators</li>
        </ol>
      </div> <!-- /.content-header -->

<?php
//GRID
$xcrud = Xcrud::get_instance();
//$xcrud->table('user_login');
$xcrud->table_name('Top 20 Card Creators');
$xcrud->query('select concat_ws(" ",first_name,last_name) as user, count(card.id) as cards from card,user where card.user = user.id group by user.id order by cards desc LIMIT 20');
//$xcrud->relation('user_id','user','user_id','name');
//$xcrud->where("","client_id = $client");
//$xcrud->order_by('login','desc');
$xcrud->readonly('login');
//$xcrud->columns('user_id,user_ip,login');
//$xcrud->fields('comment,active');
//$xcrud->subselect('suppliers','SELECT COUNT(supplier_id) FROM supplier WHERE category_id = {category_id}');
$xcrud->theme('bootstrap');
$xcrud->unset_title();
//$xcrud->label('user_ip', 'IP Address');
//$xcrud->label('user_id', 'User');
$xcrud->limit(20);
$xcrud->unset_add();
$xcrud->unset_edit();
$xcrud->unset_view();
$xcrud->unset_remove();
$xcrud->unset_csv();
$xcrud->unset_limitlist();
$xcrud->unset_pagination();
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