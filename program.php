<?php
include("header.php");
$today = date("Y-m-d 00:00:00");
$programs =0;
$programusers =0;
$cards =0;
$boards=0;
//$page = "admin-login.php";
//pageview($client,$user,$page);
//CARDS

$result = $db->select("select COUNT(distinct current_program) as programs
from user
where current_program !='' 
");while ($row = $db->get_row($result, 'MYSQL_ASSOC')) {	$programs = stripslashes ($row["programs"]);
	if($programs == ''){$programs = 0;}
	//$timecount = number_format($timecount1,0);
	}

$result = $db->select("select COUNT(id) as programusers
from user
where current_program !='' 
");while ($row = $db->get_row($result, 'MYSQL_ASSOC')) {	$programusers = stripslashes ($row["programusers"]);
	if($programusers == ''){$programusers = 0;}
	//$timecount = number_format($timecount1,0);
	}
	
$result = $db->select("select COUNT(card.id) as cards
from card,user
where user.current_program != ''
and user.id = card.user
");while ($row = $db->get_row($result, 'MYSQL_ASSOC')) {
	$cards = stripslashes ($row["cards"]);
	if($cards == ''){$cards = 0;}
	//$timecount = number_format($timecount1,0);
	}	
	
$result = $db->select("select COUNT(storyboard.id) as boards
from storyboard,user
where user.current_program != ''
and user.id = storyboard.user
");while ($row = $db->get_row($result, 'MYSQL_ASSOC')) {
	$boards = stripslashes ($row["boards"]);
	if($boards == ''){$boards = 0;}
	//$timecount = number_format($timecount1,0);
	}


?>


<div class="container">

  <div class="content">

    <div class="content-container">

      

      <div class="content-header">
        <h2 class="content-header-title">Programs</h2>
        <ol class="breadcrumb">
          <!--<li><a href="./index.html">Home</a></li>
          <li><a href="javascript:;">Extra Pages</a></li>-->
          <li class="active">Programs listed latest first</li>
        </ol>
      </div> <!-- /.content-header -->
      <div class="row">

        
        
        
          <div class="col-sm-6 col-md-3">
          <div class="row-stat">
            <p class="row-stat-label">Programs</p>
            <h3 class="row-stat-value"><?php echo $programs ?></h3>
          </div> <!-- /.row-stat -->
        </div> <!-- /.col -->
        
        <div class="col-sm-6 col-md-3">
          <div class="row-stat">
            <p class="row-stat-label">Program Users</p>
            <h3 class="row-stat-value"><?php echo $programusers ?></h3>
          </div> <!-- /.row-stat -->
        </div> <!-- /.col -->
        
        
        
        
        <div class="col-sm-6 col-md-3">
          <div class="row-stat">
            <p class="row-stat-label">Program Cards</p>
            <h3 class="row-stat-value"><?php echo $cards ?></h3>
                      </div> <!-- /.row-stat -->
        </div> <!-- /.col -->

        <div class="col-sm-6 col-md-3">
          <div class="row-stat">
            <p class="row-stat-label">Program Boards</p>
            <h3 class="row-stat-value"><?php echo $boards ?></h3>
           
            
          </div> <!-- /.row-stat -->
        </div> <!-- /.col -->
        
      </div> <!-- /.row -->

<?php
//GRID
$xcrud = Xcrud::get_instance();
$xcrud->table('user');
$xcrud->table_name('Programs');
//$xcrud->relation('user','user','id',array('first_name','last_name'));
//$xcrud->where("","client_id = $client");
//$xcrud->order_by('creation_time','desc');
//$xcrud->readonly('login');
//$xcrud->columns('type,name,user,Views,creation_time');
//$xcrud->fields('type,name,description,public,user');
//$xcrud->fields('comment,active');
//$xcrud->subselect('Views','SELECT COUNT(card) FROM view_card WHERE card = {id}');
//$xcrud->subselect('Email','SELECT email FROM user WHERE id = {user}');
$xcrud->query('
select distinct current_program as program,
(select count(id) from user where current_program = program) as users,
(select count(card.id) from card,user where card.user = user.id and current_program = program) as cards,
(select count(storyboard.id) from storyboard,user where storyboard.user = user.id and current_program = program) as boards
from user
where user.current_program != "" 
');
$xcrud->theme('bootstrap');
$xcrud->unset_title();
$xcrud->label('creation_time', 'Created');
$xcrud->label('user', 'Owner');
//$xcrud->emails_label('Send', 'Email');
$xcrud->unset_add();
$xcrud->unset_edit();
//$xcrud->unset_view();
$xcrud->unset_remove();
$xcrud->unset_csv();
$xcrud->unset_limitlist();
$xcrud->unset_pagination();
$xcrud->unset_search();
$xcrud->button('program-user.php?id={program}','Program Users','glyphicon glyphicon-user');
//GRID
echo $xcrud->render();
?>

      

    </div> <!-- /.content-container -->
      
  </div> <!-- /.content -->

</div> <!-- /.container -->

<?php 
//include("footer.php");
?>