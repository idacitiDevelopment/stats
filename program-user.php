<?php
include("header.php");
$today = date("Y-m-d 00:00:00");
$cardviews = 0;
$boardviews = 0;
//GET PROGRAM
if ($_GET["id"]) { $current_program = $_GET["id"]; }

/* My Comment */
//$page = "admin-login.php";
//pageview($client,$user,$page);
//CARDS


	
//TOP CARD VIEWS
$result = $db->select("select card.id as cardid,card.name as card,concat_ws(' ',user.first_name, user.last_name) as user,COUNT(card.id) as views
from card,view_card,user
where card.id = view_card.card
and card.user = user.id
and user.current_program = '$current_program'
group by cardid
order by views DESC
LIMIT 1
");
while ($row = $db->get_row($result, 'MYSQL_ASSOC')) {	$cardviews = stripslashes ($row["views"]);
	$cardname = stripslashes ($row["card"]);
	$cardowner = stripslashes ($row["user"]);
	//if($timecount1 == ''){$timecount1 = 0;}
	//$timecount = number_format($timecount1,0);
	}
	
	
//TOP BOARD VIEWS
$result = $db->select("select storyboard.id as boardid,storyboard.title as board,concat_ws(' ',user.first_name, user.last_name) as user,COUNT(storyboard.id) as views
from storyboard,view_storyboard,user
where storyboard.id = view_storyboard.storyboard
and storyboard.user = user.id
and user.current_program = '$current_program'
group by boardid
order by views DESC
LIMIT 1
");
while ($row = $db->get_row($result, 'MYSQL_ASSOC')) {	$boardviews = stripslashes ($row["views"]);
	$boardname = stripslashes ($row["board"]);
	$boardowner = stripslashes ($row["user"]);
	//if($timecount1 == ''){$timecount1 = 0;}
	//$timecount = number_format($timecount1,0);
	}

?>


<div class="container">

  <div class="content">

    <div class="content-container">

      

      <div class="content-header">
        <h1><a href="program.php" /><i class="icon-circle-arrow-left" ></i>Programs</a> | Users
<!--<a class="btn btn-success pull-right" data-toggle="modal" href="#add"><i class="icon-plus"></i></a>-->
</h1>      </div> <!-- /.content-header -->
     
      <h4><?php echo $current_program ?></h4>
      <hr>
      <div class="row">

        
        
        
          <div class="col-sm-6 col-md-3">
          <div class="row-stat text-center">
            <p class="row-stat-label">Top Card Views</p>
            <h3 class="row-stat-value"><?php echo $cardviews ?></h3>
            <p><?php echo $cardname ?></p>
             <span class="label label-success row-stat-badge"><?php echo $cardowner ?></span>
          </div> <!-- /.row-stat -->
        </div> <!-- /.col -->
        
        <div class="col-sm-6 col-md-3">
          <div class="row-stat text-center">
            <p class="row-stat-label">Top Board Views</p>
            <h3 class="row-stat-value"><?php echo $boardviews ?></h3>
            <p><?php echo $boardname ?></p>
             <span class="label label-success row-stat-badge"><?php echo $boardowner ?></span>
          </div> <!-- /.row-stat -->
        </div> <!-- /.col -->
        

        
      </div> <!-- /.row -->

<?php
//GRID
$xcrud = Xcrud::get_instance();
$xcrud->table('user');
$xcrud->table_name('Program Users');
//$xcrud->relation('user','user','id',array('first_name','last_name'));
$xcrud->where("","current_program = '$current_program'");
$xcrud->order_by('last_name','asc');
//$xcrud->readonly('login');
$xcrud->columns('last_name,first_name,Cards,Boards');
//$xcrud->fields('type,name,description,public,user');
//$xcrud->fields('comment,active');
//$xcrud->subselect('Views','SELECT COUNT(card) FROM view_card WHERE card = {id}');
$xcrud->subselect('Cards','SELECT COUNT(id) FROM card WHERE user = {id}');
$xcrud->subselect('Boards','SELECT COUNT(id) FROM storyboard WHERE user = {id}');
$xcrud->theme('bootstrap');
$xcrud->unset_title();
$xcrud->limit(20);
$xcrud->label('creation_time', 'Created');
$xcrud->label('user', 'Owner');
//$xcrud->emails_label('Send', 'Email');
$xcrud->unset_add();
$xcrud->unset_edit();
$xcrud->unset_view();
$xcrud->unset_remove();
$xcrud->unset_csv();
$xcrud->unset_limitlist();
$xcrud->unset_pagination();
$xcrud->unset_search();
//$xcrud->button('program-user.php?id={program}','Program Users','glyphicon glyphicon-user');
//GRID
echo $xcrud->render();
?>

      

    </div> <!-- /.content-container -->
      
  </div> <!-- /.content -->

</div> <!-- /.container -->

<?php 
//include("footer.php");
?>