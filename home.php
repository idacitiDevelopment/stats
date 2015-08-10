<?php
include("header.php");
//$page = "home.php";
//pageview($client,$user,$page);
$today = date("Y-m-d 00:00:00");

//STATS COUNTS
//USERS
$result = $db->select("select COUNT(id) as users
from user
where is_active = 1 and is_root = 0
");while ($row = $db->get_row($result, 'MYSQL_ASSOC')) {	$users = stripslashes ($row["users"]);
	//if($timecount1 == ''){$timecount1 = 0;}
	//$timecount = number_format($timecount1,0);
	}
	
$result = $db->select("select COUNT(id) as users2
from user
where is_active = 1 and is_root = 0
and date(creation_time) = CURDATE() 
");while ($row = $db->get_row($result, 'MYSQL_ASSOC')) {	$users2 = stripslashes ($row["users2"]);
	//if($timecount1 == ''){$timecount1 = 0;}
	//$timecount = number_format($timecount1,0);
	}
	
//$users3 = ((($users2/$users)*100) - 100);

//KPIS
$result = $db->select("select COUNT(term_id) as kpis
from kpi
");while ($row = $db->get_row($result, 'MYSQL_ASSOC')) {	$kpis = stripslashes ($row["kpis"]);
	//if($timecount1 == ''){$timecount1 = 0;}
	//$timecount = number_format($timecount1,0);
	}
//COMPANYS
$result = $db->select("select COUNT(entity_id) as companies
from company
");while ($row = $db->get_row($result, 'MYSQL_ASSOC')) {	$companies = stripslashes ($row["companies"]);
	//if($timecount1 == ''){$timecount1 = 0;}
	//$timecount = number_format($timecount1,0);
	}	
	
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
	
//BOARDS
$result = $db->select("select COUNT(id) as boards
from storyboard
");while ($row = $db->get_row($result, 'MYSQL_ASSOC')) {	$boards = stripslashes ($row["boards"]);
	//if($timecount1 == ''){$timecount1 = 0;}
	//$timecount = number_format($timecount1,0);
	}
	
$result = $db->select("select COUNT(id) as boards2
from storyboard
where date(creation_time) = CURDATE()
");while ($row = $db->get_row($result, 'MYSQL_ASSOC')) {	$boards2 = stripslashes ($row["boards2"]);
	//if($timecount1 == ''){$timecount1 = 0;}
	//$timecount = number_format($timecount1,0);
	}		
	
//LEADERBOARD
//CARDS
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



	
//BOARDS
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
	
//CIRCLES

//COUNT
$result = $db->select("select COUNT(id) as circles
from circle
");
while ($row = $db->get_row($result, 'MYSQL_ASSOC')) {	$circles = stripslashes ($row["circles"]);
	//if($timecount1 == ''){$timecount1 = 0;}
	//$timecount = number_format($timecount1,0);
	}
//BIGGEST
$result = $db->select("select circle.name as circle,COUNT(user_circle.user) as users
from circle,user_circle
where user_circle.status = 2
and user_circle.circle = circle.id
group by circle.id
order by users DESC
LIMIT 1
");
while ($row = $db->get_row($result, 'MYSQL_ASSOC')) {	$circleusers = stripslashes ($row["users"]);
	$circlename = stripslashes ($row["circle"]);
	//if($timecount1 == ''){$timecount1 = 0;}
	//$timecount = number_format($timecount1,0);
	}
	
//MOST CARDS
$result = $db->select("select circle.name as circle,COUNT(card_circle.card) as cards
from circle,card_circle
where card_circle.circle = circle.id
group by circle.id
order by cards DESC
LIMIT 1
");
while ($row = $db->get_row($result, 'MYSQL_ASSOC')) {	$circlecards = stripslashes ($row["cards"]);
	$circlename2 = stripslashes ($row["circle"]);
	//if($timecount1 == ''){$timecount1 = 0;}
	//$timecount = number_format($timecount1,0);
	}
//MOST BOARDS
$result = $db->select("select circle.name as circle,COUNT(storyboard_circle.storyboard) as boards
from circle,storyboard_circle
where storyboard_circle.circle = circle.id
group by circle.id
order by boards DESC
LIMIT 1
");
while ($row = $db->get_row($result, 'MYSQL_ASSOC')) {	$circleboards = stripslashes ($row["boards"]);
	$circlename3 = stripslashes ($row["circle"]);
	//if($timecount1 == ''){$timecount1 = 0;}
	//$timecount = number_format($timecount1,0);
	}	
					

?>


<div class="container">

  <div class="content">

    <div class="content-container">

      

      <div class="content-header">
        <h2 class="content-header-title">Stats</h2>
        <ol class="breadcrumb">
          <!--<li><a href="./index.html">Home</a></li>
          <li><a href="javascript:;">Extra Pages</a></li>-->
          <li class="active">Application Summary Statistics</li>
        </ol>
      </div> <!-- /.content-header -->

            <div class="row">

        <div class="col-sm-6 col-md-3">
          <div class="row-stat">
            <p class="row-stat-label">KPIs | Companies</p>
            <h3 class="row-stat-value"><?php echo $kpis ?> | <?php echo $companies ?></h3>
            </div> <!-- /.row-stat -->
        </div> <!-- /.col -->
        
        
        <div class="col-sm-6 col-md-3">
          <div class="row-stat">
            <p class="row-stat-label">Users</p>
            <h3 class="row-stat-value"><?php echo $users ?></h3>
            <?php if($users2 > 0) { ?>
            <span class="label label-success row-stat-badge">+<?php echo $users2 ?> Today</span>
            <?php } else { ?>
            <span class="label label-danger row-stat-badge"><?php echo $users2 ?> Today</span>
              <?php } ?>
          </div> <!-- /.row-stat -->
        </div> <!-- /.col -->


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
            <p class="row-stat-label">Storyboards</p>
            <h3 class="row-stat-value"><?php echo $boards ?></h3>
            <?php if($boards2 > 0) { ?>
            <span class="label label-success row-stat-badge">+<?php echo $boards2 ?> Today </span>
            <?php } else { ?>
            <span class="label label-danger row-stat-badge"><?php echo $boards2 ?> Today</span>
              <?php } ?>
          </div> <!-- /.row-stat -->
        </div> <!-- /.col -->
        
      </div> <!-- /.row -->
      <!-- LEADERBOARD -->
      <div class="content-header">
        <h2 class="content-header-title">Leaderboard</h2>
        <ol class="breadcrumb">
          <!--<li><a href="./index.html">Home</a></li>
          <li><a href="javascript:;">Extra Pages</a></li>-->
          <li class="active">Top User Contributors and Content Views</li>
        </ol>
      </div> <!-- /.content-header -->

            <div class="row">

        <div class="col-sm-6 col-md-3">
          <div class="row-stat text-center">
            <p class="row-stat-label">Top User-Cards Created</p>
            <h3 class="row-stat-value"><?php echo $usercards ?></h3></br>
            <span class="label label-success row-stat-badge"><?php echo $carduser ?></span>
            
          </div> <!-- /.row-stat -->
        </div> <!-- /.col -->

        <div class="col-sm-6 col-md-3">
          <div class="row-stat text-center">
            <p class="row-stat-label">Top User-Storyboards Created</p>
            <h3 class="row-stat-value"><?php echo $userboards ?></h3></br>
            <span class="label label-success row-stat-badge"><?php echo $boarduser ?></span>
            </div> <!-- /.row-stat -->
        </div> <!-- /.col -->

        <div class="col-sm-6 col-md-3">
          <div class="row-stat text-center">
            <p class="row-stat-label">Top Card-Views</p>
            <h3 class="row-stat-value"><?php echo $cardviews ?></h3></br>
            <span class="label label-success row-stat-badge"><?php echo $cardname ?></span>
          </div> <!-- /.row-stat -->
        </div> <!-- /.col -->

        <div class="col-sm-6 col-md-3">
          <div class="row-stat text-center">
            <p class="row-stat-label">Top Storyboard-Views</p>
            <h3 class="row-stat-value"><?php echo $boardviews ?></h3></br>
            <span class="label label-success row-stat-badge"><?php echo $boardname ?></span>

          </div> <!-- /.row-stat -->
        </div> <!-- /.col -->
         </div> <!-- /.row -->
        
         <div class="content-header">
        <h2 class="content-header-title">Circles</h2>
        <ol class="breadcrumb">
          <!--<li><a href="./index.html">Home</a></li>
          <li><a href="javascript:;">Extra Pages</a></li>-->
          <li class="active">Top Communities</li>
        </ol>
      </div> <!-- /.content-header -->

            <div class="row">

        <div class="col-sm-6 col-md-3">
          <div class="row-stat text-center">
            <p class="row-stat-label">Circles</p>
            <h3 class="row-stat-value"><?php echo $circles ?></h3>
            
            
          </div> <!-- /.row-stat -->
        </div> <!-- /.col -->

        <div class="col-sm-6 col-md-3">
          <div class="row-stat text-center">
            <p class="row-stat-label">Circle-Most Members</p>
            <h3 class="row-stat-value"><?php echo $circleusers ?></h3></br>
            <span class="label label-info row-stat-badge"><?php echo $circlename ?></span>
            
          </div> <!-- /.row-stat -->
        </div> <!-- /.col -->

        <div class="col-sm-6 col-md-3">
          <div class="row-stat text-center">
            <p class="row-stat-label">Circle-Most Cards</p>
            <h3 class="row-stat-value"><?php echo $circlecards ?></h3></br>
            <span class="label label-info row-stat-badge"><?php echo $circlename2 ?></span>
          </div> <!-- /.row-stat -->
        </div> <!-- /.col -->

        <div class="col-sm-6 col-md-3">
          <div class="row-stat text-center">
            <p class="row-stat-label">Circle-Most Storyboards</p>
            <h3 class="row-stat-value"><?php echo $circleboards ?></h3></br>
            <span class="label label-info row-stat-badge"><?php echo $circlename3 ?></span>

          </div> <!-- /.row-stat -->
        </div> <!-- /.col -->
        
      </div> <!-- /.row -->

      

    </div> <!-- /.content-container -->
      
  </div> <!-- /.content -->

</div> <!-- /.container -->

<?php 
//include("footer.php");
?>