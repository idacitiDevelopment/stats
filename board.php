<?php
include("header.php");
//$page = "admin-login.php";
//pageview($client,$user,$page);
$today = date("Y-m-d 00:00:00");
//BOARDS
$result = $db->select("select COUNT(id) as boards
from storyboard
");while ($row = $db->get_row($result, 'MYSQL_ASSOC')) {	$boards = stripslashes ($row["boards"]);
	//if($timecount1 == ''){$timecount1 = 0;}
	//$timecount = number_format($timecount1,0);
	}

$result = $db->select("select COUNT(id) as boards
from storyboard
where public = 1
");while ($row = $db->get_row($result, 'MYSQL_ASSOC')) {	$publicboards = stripslashes ($row["boards"]);
	//if($timecount1 == ''){$timecount1 = 0;}
	//$timecount = number_format($timecount1,0);
	}

$percent = ($publicboards/$boards) * 100;
$percent = number_format($percent,0);
	
$result = $db->select("select COUNT(id) as boards2
from storyboard
where date(creation_time) = CURDATE()
");while ($row = $db->get_row($result, 'MYSQL_ASSOC')) {	$boards2 = stripslashes ($row["boards2"]);
	//if($timecount1 == ''){$timecount1 = 0;}
	//$timecount = number_format($timecount1,0);
	}	

$result = $db->select("select user.id as userid,concat_ws(' ',user.first_name, user.last_name) as user, COUNT(storyboard.id) as boards
from storyboard,user
where storyboard.user = user.id
and user.is_active = 1 and user.is_root = 0
group by userid
order by boards DESC
LIMIT 1
");
while ($row = $db->get_row($result, 'MYSQL_ASSOC')) {	$userboards = stripslashes ($row["boards"]);
	$boarduser = stripslashes ($row["user"]);
	$userid2 = stripslashes ($row["userid"]);
	$avatar2 = "http://application.idaciti.com/data/upload/".$userid2."/avatar.jpg";
	//if($timecount1 == ''){$timecount1 = 0;}
	//$timecount = number_format($timecount1,0);
	}
	
//TOP BOARD
$result = $db->select("select storyboard.id as boardid,title,COUNT(storyboard.id) as views
from storyboard,view_storyboard
where storyboard.id = view_storyboard.storyboard
group by boardid
order by views DESC
LIMIT 1
");
while ($row = $db->get_row($result, 'MYSQL_ASSOC')) {	$boardviews = stripslashes ($row["views"]);
	$boardname = stripslashes ($row["title"]);
	//if($timecount1 == ''){$timecount1 = 0;}
	//$timecount = number_format($timecount1,0);
	}

?>


<div class="container">

  <div class="content">

    <div class="content-container">

      

      <div class="content-header">
        <h2 class="content-header-title">Storyboards</h2>
        <ol class="breadcrumb">
          <!--<li><a href="./index.html">Home</a></li>
          <li><a href="javascript:;">Extra Pages</a></li>-->
          <li class="active">Storyboards listed latest first</li>
        </ol>
      </div> <!-- /.content-header -->

 <div class="row">

        
        
        
          <div class="col-sm-6 col-md-3">
          <div class="row-stat">
            <p class="row-stat-label">Storyboards</p>
            <h3 class="row-stat-value"><?php echo $boards ?></h3>
            <?php if($boards2 > 0) { ?>
            <span class="label label-success row-stat-badge">+<?php echo $boards2 ?> Today </span>
            <?php } else { ?>
            <span class="label label-danger row-stat-badge"><?php echo $boards2 ?> Today</span>
              <?php } ?>
          </div> <!-- /.row-stat -->
        </div> <!-- /.col -->
        
        <div class="col-sm-6 col-md-3">
          <div class="row-stat">
            <p class="row-stat-label">Public Storyboards</p>
            <h3 class="row-stat-value"><?php echo $percent ?>%</h3>
            <span class="label label-info row-stat-badge">Public</span>
          </div> <!-- /.row-stat -->
        </div> <!-- /.col -->
        
        
        
        
        <div class="col-sm-6 col-md-3">
          <div class="row-stat">
            <p class="row-stat-label">Top Storyboard-Views</p>
            <h3 class="row-stat-value"><?php echo $boardviews ?></h3>
            <span class="label label-success row-stat-badge"><?php echo $boardname ?></span>

          </div> <!-- /.row-stat -->
        </div> <!-- /.col -->

        <div class="col-sm-6 col-md-3">
          <div class="row-stat">
            <p class="row-stat-label">Top User-Storyboards Created</p>
            <h3 class="row-stat-value"><?php echo $userboards ?> <img src="<?php echo $avatar2 ?>" height=25px width=25px/></h3>
            <span class="label label-success row-stat-badge"><?php echo $boarduser ?></span>
            </div> <!-- /.row-stat -->
        </div> <!-- /.col -->
        
      </div> <!-- /.row -->
      





<?php
//GRID
$xcrud = Xcrud::get_instance();
$xcrud->table('storyboard');
$xcrud->table_name('Storyboards');
$xcrud->relation('user','user','id',array('first_name','last_name'));
//$xcrud->where("","client_id = $client");
$xcrud->order_by('creation_time','desc');
//$xcrud->readonly('login');
$xcrud->columns('title,public,user,Views,creation_time');
$xcrud->fields('title,public,description,user');
//$xcrud->fields('comment,active');
$xcrud->subselect('Views','SELECT COUNT(storyboard) FROM view_storyboard WHERE storyboard = {id}');
$xcrud->theme('bootstrap');
$xcrud->unset_title();
$xcrud->label('creation_time', 'Created');
$xcrud->label('user', 'Owner');
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