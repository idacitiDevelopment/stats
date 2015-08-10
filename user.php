<?php
include("header.php");
//$page = "admin-login.php";
//pageview($client,$user,$page);

//USERS COUNT
$result = $db->select("select COUNT(id) as users
from user
where is_active = 1 and is_root = 0
");
while ($row = $db->get_row($result, 'MYSQL_ASSOC')) {
	$users = stripslashes ($row["users"]);
	//if($timecount1 == ''){$timecount1 = 0;}
	//$timecount = number_format($timecount1,0);
	}
	

//CARDS
//CREATED LAST 30 DAYS
$result = $db->select("select COUNT(card.id) as cards
from card,user
where card.user = user.id
and user.is_active = 1 and user.is_root = 0
and DATE(card.creation_time) > DATE_SUB(CURDATE(), INTERVAL 30 DAY)
");
while ($row = $db->get_row($result, 'MYSQL_ASSOC')) {
	$cards30 = stripslashes ($row["cards"]);
	//if($timecount1 == ''){$timecount1 = 0;}
	//$timecount = number_format($timecount1,0);
	}
	
//VIEWED IN LAST 30 DAYS
$result = $db->select("select COUNT(view_card.card) as cardviews
from view_card,user,card
where view_card.user = user.id
and view_card.card = card.id
and card.user != user.id
and user.is_active = 1 and user.is_root = 0
and DATE(card.creation_time) > DATE_SUB(CURDATE(), INTERVAL 30 DAY)
");
while ($row = $db->get_row($result, 'MYSQL_ASSOC')) {
	$cardviews30 = stripslashes ($row["cardviews"]);
	//if($timecount1 == ''){$timecount1 = 0;}
	//$timecount = number_format($timecount1,0);
	}
	

//TOP USER CREATES
$result = $db->select("select user.id as userid,concat_ws(' ',user.first_name, user.last_name) as user, COUNT(card.id) as cards
from card,user
where card.user = user.id
and user.is_active = 1 and user.is_root = 0
group by userid
order by cards DESC
LIMIT 1
");
while ($row = $db->get_row($result, 'MYSQL_ASSOC')) {
	$usercards = stripslashes ($row["cards"]);
	$carduser = stripslashes ($row["user"]);
	$userid = stripslashes ($row["userid"]);
	$avatar = "http://application.idaciti.com/data/upload/".$userid."/avatar.jpg";
	//if($timecount1 == ''){$timecount1 = 0;}
	//$timecount = number_format($timecount1,0);
	}
//TOP USER VIEWS
$result = $db->select("select COUNT(view_card.user) as cardviews, concat_ws(' ',user.first_name, user.last_name) as username
from view_card,user,card
where view_card.user = user.id
and view_card.card = card.id
and card.user != user.id
and user.is_active = 1 and user.is_root = 0
group by user.id
order by cardviews desc
limit 1
");
while ($row = $db->get_row($result, 'MYSQL_ASSOC')) {
	$username = stripslashes ($row["username"]);
	$cardviews = stripslashes ($row["cardviews"]);
	//if($timecount1 == ''){$timecount1 = 0;}
	//$timecount = number_format($timecount1,0);
	}




//BOARDS
//CREATED LAST 30 DAYS
$result = $db->select("select COUNT(storyboard.id) as boards
from storyboard,user
where storyboard.user = user.id
and user.is_active = 1 and user.is_root = 0
and DATE(storyboard.creation_time) > DATE_SUB(CURDATE(), INTERVAL 30 DAY)
");
while ($row = $db->get_row($result, 'MYSQL_ASSOC')) {
	$boards30 = stripslashes ($row["boards"]);
	//if($timecount1 == ''){$timecount1 = 0;}
	//$timecount = number_format($timecount1,0);
	}
	
//VIEWED IN LAST 30 DAYS
$result = $db->select("select COUNT(view_storyboard.storyboard) as boardviews
from view_storyboard,user,storyboard
where view_storyboard.user = user.id
and view_storyboard.storyboard = storyboard.id
and storyboard.user != user.id
and user.is_active = 1 and user.is_root = 0
and DATE(storyboard.creation_time) > DATE_SUB(CURDATE(), INTERVAL 30 DAY)
");
while ($row = $db->get_row($result, 'MYSQL_ASSOC')) {
	$boardviews30 = stripslashes ($row["boardviews"]);
	//if($timecount1 == ''){$timecount1 = 0;}
	//$timecount = number_format($timecount1,0);
	}

//TOP USER CREATES
$result = $db->select("select user.id as userid,concat_ws(' ',user.first_name, user.last_name) as user, COUNT(storyboard.id) as boards
from storyboard,user
where storyboard.user = user.id
and user.is_active = 1 and user.is_root = 0
group by userid
order by boards DESC
LIMIT 1
");
while ($row = $db->get_row($result, 'MYSQL_ASSOC')) {
	$userboards = stripslashes ($row["boards"]);
	$boarduser = stripslashes ($row["user"]);
	$userid2 = stripslashes ($row["userid"]);
	$avatar2 = "http://application.idaciti.com/data/upload/".$userid2."/avatar.jpg";
	//if($timecount1 == ''){$timecount1 = 0;}
	//$timecount = number_format($timecount1,0);
	}
//TOP USER VIEWS	
$result = $db->select("select COUNT(view_storyboard.user) as boardviews, concat_ws(' ',user.first_name, user.last_name) as username
from view_storyboard,user,storyboard
where view_storyboard.user = user.id
and view_storyboard.storyboard = storyboard.id
and storyboard.user != user.id
and user.is_active = 1 and user.is_root = 0
group by user.id
order by boardviews desc
limit 1
");
while ($row = $db->get_row($result, 'MYSQL_ASSOC')) {
	$username2 = stripslashes ($row["username"]);
	$boardviews = stripslashes ($row["boardviews"]);
	//if($timecount1 == ''){$timecount1 = 0;}
	//$timecount = number_format($timecount1,0);
	}	

?>


<div class="container">

  <div class="content">

    <div class="content-container">

      

      <div class="content-header">
        <h2 class="content-header-title">Users</h2>
        <ol class="breadcrumb">
          <!--<li><a href="./index.html">Home</a></li>
          <li><a href="javascript:;">Extra Pages</a></li>-->
          <li class="active"><strong><?php echo $users ?></strong> Active Users (External non-idaciti) listed latest first</li>
        </ol>
      </div> <!-- /.content-header -->
      <div class="row">
      
        
        <div class="col-sm-6 col-md-3">
          <div class="row-stat">
            <p class="row-stat-label">Top User-Cards Created</p>
            <h3 class="row-stat-value"><?php echo $usercards ?></h3>
            <span class="label label-success row-stat-badge"><?php echo $carduser ?></span>
            
          </div> <!-- /.row-stat -->
        </div> <!-- /.col -->
        
        <div class="col-sm-6 col-md-3">
          <div class="row-stat">
            <p class="row-stat-label">Top User-Card Views</p>
            <h3 class="row-stat-value"><?php echo $cardviews ?></h3>
            <span class="label label-success row-stat-badge"><?php echo $username ?></span>
            
          </div> <!-- /.row-stat -->
        </div> <!-- /.col -->

        <div class="col-sm-6 col-md-3">
          <div class="row-stat">
            <p class="row-stat-label">Top User-Boards Created</p>
            <h3 class="row-stat-value"><?php echo $userboards ?></h3>
            <span class="label label-success row-stat-badge"><?php echo $boarduser ?></span>
            </div> <!-- /.row-stat -->
        </div> <!-- /.col -->
        
        <div class="col-sm-6 col-md-3">
          <div class="row-stat">
            <p class="row-stat-label">Top User-Board Views</p>
            <h3 class="row-stat-value"><?php echo $boardviews ?></h3>
            <span class="label label-success row-stat-badge"><?php echo $username2 ?></span>
            
          </div> <!-- /.row-stat -->
        </div> <!-- /.col -->
        
        
        
          </div> <!-- /.row -->
          
          <div class="row">
      
        
        <div class="col-sm-6 col-md-3">
          <div class="row-stat">
            <p class="row-stat-label">Cards Created-Last 30 Days</p>
            <h3 class="row-stat-value"><?php echo $cards30 ?></h3>            
          </div> <!-- /.row-stat -->
        </div> <!-- /.col -->
        
        <div class="col-sm-6 col-md-3">
          <div class="row-stat">
            <p class="row-stat-label">Cards Viewed-Last 30 Days</p>
            <h3 class="row-stat-value"><?php echo $cardviews30 ?></h3>
 
          </div> <!-- /.row-stat -->
        </div> <!-- /.col -->

        <div class="col-sm-6 col-md-3">
          <div class="row-stat">
            <p class="row-stat-label">Boards Created-Last 30 Days</p>
            <h3 class="row-stat-value"><?php echo $boards30 ?></h3>
                        </div> <!-- /.row-stat -->
        </div> <!-- /.col -->
        
        <div class="col-sm-6 col-md-3">
          <div class="row-stat">
            <p class="row-stat-label">Boards Viewed-Last 30 Days</p>
            <h3 class="row-stat-value"><?php echo $boardviews30 ?></h3>
                        
          </div> <!-- /.row-stat -->
        </div> <!-- /.col -->
        
        
        
          </div> <!-- /.row -->

<?php
//GRID
$xcrud = Xcrud::get_instance();
$xcrud->table('user');
$xcrud->table_name('Active Users');
//$xcrud->relation('user_id','user','user_id','name');
$xcrud->where("","is_active = 1 AND is_root = 0");
$xcrud->order_by('creation_time','desc');
//$xcrud->readonly('login');
$xcrud->columns('last_name,first_name,creation_time,email');
//$xcrud->fields('financial_category,decision_category,name,description');
//$xcrud->subselect('suppliers','SELECT COUNT(supplier_id) FROM supplier WHERE category_id = {category_id}');
$xcrud->theme('bootstrap');
$xcrud->unset_title();
$xcrud->label('user_ip', 'IP Address');
//$xcrud->emails_label('Send');
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
//include("footer.php");
?>