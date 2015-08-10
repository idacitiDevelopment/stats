<?php
include("header.php");
$today = date("Y-m-d 00:00:00");
//$page = "admin-login.php";
//pageview($client,$user,$page);
//CARDS

$result = $db->select("select COUNT(id) as cards
from card
");while ($row = $db->get_row($result, 'MYSQL_ASSOC')) {	$cards = stripslashes ($row["cards"]);
	//if($timecount1 == ''){$timecount1 = 0;}
	//$timecount = number_format($timecount1,0);
	}

$result = $db->select("select COUNT(id) as cards2
from card
where date(creation_time) = CURDATE() 
");while ($row = $db->get_row($result, 'MYSQL_ASSOC')) {	$cards2 = stripslashes ($row["cards2"]);
	//if($timecount1 == ''){$timecount1 = 0;}
	//$timecount = number_format($timecount1,0);
	}
	
$result = $db->select("select COUNT(id) as cards, type
from card
group by type
order by cards DESC
LIMIT 1
");while ($row = $db->get_row($result, 'MYSQL_ASSOC')) {
	$cardtypes = stripslashes ($row["cards"]);
	$typename = stripslashes ($row["type"]);
	//if($timecount1 == ''){$timecount1 = 0;}
	//$timecount = number_format($timecount1,0);
	}	
	
$result = $db->select("select user.id as userid,concat_ws(' ',user.first_name, user.last_name) as user, COUNT(card.id) as cards
from card,user
where card.user = user.id
and user.is_active = 1 and user.is_root = 0
group by userid
order by cards DESC
LIMIT 1
");
while ($row = $db->get_row($result, 'MYSQL_ASSOC')) {	$usercards = stripslashes ($row["cards"]);
	$carduser = stripslashes ($row["user"]);
	$userid = stripslashes ($row["userid"]);
	$avatar = "http://application.idaciti.com/data/upload/".$userid."/avatar.jpg";
	//if($timecount1 == ''){$timecount1 = 0;}
	//$timecount = number_format($timecount1,0);
	}
//TOP CARD
$result = $db->select("select card.id as cardid,name,COUNT(card.id) as views
from card,view_card
where card.id = view_card.card
group by cardid
order by views DESC
LIMIT 1
");
while ($row = $db->get_row($result, 'MYSQL_ASSOC')) {	$cardviews = stripslashes ($row["views"]);
	$cardname = stripslashes ($row["name"]);
	//if($timecount1 == ''){$timecount1 = 0;}
	//$timecount = number_format($timecount1,0);
	}


?>


<div class="container">

  <div class="content">

    <div class="content-container">

      

      <div class="content-header">
        <h2 class="content-header-title">Cards</h2>
        <ol class="breadcrumb">
          <!--<li><a href="./index.html">Home</a></li>
          <li><a href="javascript:;">Extra Pages</a></li>-->
          <li class="active">Cards listed latest first</li>
        </ol>
      </div> <!-- /.content-header -->
      <div class="row">

        
        
        
          <div class="col-sm-6 col-md-3">
          <div class="row-stat">
            <p class="row-stat-label">Cards</p>
            <h3 class="row-stat-value"><?php echo $cards ?></h3>
            <?php if($cards2 > 0) { ?>
            <span class="label label-success row-stat-badge">+<?php echo $cards2 ?> Today</span>
            <?php } else { ?>
            <span class="label label-danger row-stat-badge"> <?php echo $cards2 ?> Today</span>
              <?php } ?>
          </div> <!-- /.row-stat -->
        </div> <!-- /.col -->
        
        <div class="col-sm-6 col-md-3">
          <div class="row-stat">
            <p class="row-stat-label">Top Card-Type</p>
            <h3 class="row-stat-value"><?php echo $cardtypes ?></h3>
            <span class="label label-info row-stat-badge"><?php echo $typename ?></span>
          </div> <!-- /.row-stat -->
        </div> <!-- /.col -->
        
        
        
        
        <div class="col-sm-6 col-md-3">
          <div class="row-stat">
            <p class="row-stat-label">Top Card-Views</p>
            <h3 class="row-stat-value"><?php echo $cardviews ?></h3>
            <span class="label label-success row-stat-badge"><?php echo $cardname ?></span>
          </div> <!-- /.row-stat -->
        </div> <!-- /.col -->

        <div class="col-sm-6 col-md-3">
          <div class="row-stat">
            <p class="row-stat-label">Top User-Cards Created</p>
            <h3 class="row-stat-value"><?php echo $usercards ?> <img src="<?php echo $avatar ?>" height=25px width=25px/> </h3>
            <span class="label label-success row-stat-badge"><?php echo $carduser ?></span>
            
          </div> <!-- /.row-stat -->
        </div> <!-- /.col -->
        
      </div> <!-- /.row -->

<?php
//GRID
$xcrud = Xcrud::get_instance();
$xcrud->table('card');
$xcrud->table_name('Cards');
$xcrud->relation('user','user','id',array('first_name','last_name'));
//$xcrud->where("","client_id = $client");
$xcrud->order_by('creation_time','desc');
//$xcrud->readonly('login');
$xcrud->columns('type,name,user,Views,creation_time');
$xcrud->fields('type,name,description,public,user');
//$xcrud->fields('comment,active');
$xcrud->subselect('Views','SELECT COUNT(card) FROM view_card WHERE card = {id}');
//$xcrud->subselect('Email','SELECT email FROM user WHERE id = {user}');
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