<?php
include("header-blog.php");
$today = date("Y-m-d 00:00:00");
//$page = "admin-login.php";
//pageview($client,$user,$page);
//CARDS

$result = $db->select("
select count(ID) as posts
from wp_idaposts
where post_parent = 0
and post_type = 'post'
");while ($row = $db->get_row($result, 'MYSQL_ASSOC')) {	$posts = stripslashes ($row["posts"]);
	//if($timecount1 == ''){$timecount1 = 0;}
	//$timecount = number_format($timecount1,0);
	}

$result = $db->select("select SUM(CAST(meta_value AS UNSIGNED)) as postviews
from wp_idapostmeta,wp_idaposts
where wp_idapostmeta.post_id = wp_idaposts.id
and wp_idapostmeta.meta_key = '_count-views_all'
and wp_idaposts.post_type = 'Post'
and wp_idaposts.post_title != 'Auto Draft'
");while ($row = $db->get_row($result, 'MYSQL_ASSOC')) {	$postviews = stripslashes ($row["postviews"]);
	//if($timecount1 == ''){$timecount1 = 0;}
	//$timecount = number_format($timecount1,0);
	}
	
$result = $db->select("select CAST(meta_value AS UNSIGNED) as maxviews,post_title,post_date
from wp_idapostmeta,wp_idaposts
where wp_idapostmeta.post_id = wp_idaposts.id
and wp_idapostmeta.meta_key = '_count-views_all'
and wp_idaposts.post_type = 'Post'
and wp_idaposts.post_title != 'Auto Draft'
group by wp_idaposts.id
order by maxviews DESC,post_date DESC
LIMIT 1
");while ($row = $db->get_row($result, 'MYSQL_ASSOC')) {
	$post_date = stripslashes ($row["post_date"]);
	$post_date = date("d-M-Y", strtotime($post_date));	
	$post_title = stripslashes ($row["post_title"]);	$maxviews = stripslashes ($row["maxviews"]);
	//if($timecount1 == ''){$timecount1 = 0;}
	//$timecount = number_format($timecount1,0);
	}	
	
$result = $db->select("select COUNT(comment_ID) as comments
from wp_idacomments
where comment_approved = 1
");while ($row = $db->get_row($result, 'MYSQL_ASSOC')) {	$comments = stripslashes ($row["comments"]);
	//if($timecount1 == ''){$timecount1 = 0;}
	//$timecount = number_format($timecount1,0);
	}


?>


<div class="container">

  <div class="content">

    <div class="content-container">

      

      <div class="content-header">
        <h2 class="content-header-title">Blog</h2>
        <ol class="breadcrumb">
          <!--<li><a href="./index.html">Home</a></li>
          <li><a href="javascript:;">Extra Pages</a></li>-->
          <li class="active">Posts by Number of Views</li>
        </ol>
      </div> <!-- /.content-header -->
      <div class="row">

        
        
        
          <div class="col-sm-4 col-md-2">
          <div class="row-stat text-center">
            <p class="row-stat-label">Posts</p>
            <h3 class="row-stat-value"><?php echo $posts ?></h3>
          </div> <!-- /.row-stat -->
        </div> <!-- /.col -->
        
        <div class="col-sm-4 col-md-2">
          <div class="row-stat text-center">
            <p class="row-stat-label">Views</p>
            <h3 class="row-stat-value"><?php echo $postviews ?></h3>
            <!--<span class="label label-info row-stat-badge"><?php echo $typename ?></span>-->
          </div> <!-- /.row-stat -->
        </div> <!-- /.col -->
        
        
        <div class="col-sm-4 col-md-2">
          <div class="row-stat text-center">
            <p class="row-stat-label">Comments</p>
            <h3 class="row-stat-value"><?php echo $comments ?></h3>
            <!--<span class="label label-success row-stat-badge"><?php echo $carduser ?></span>-->
            
          </div> <!-- /.row-stat -->
        </div> <!-- /.col -->

        
        
        <div class="col-sm-12 col-md-6">
          <div class="row-stat text-center">
            <p class="row-stat-label">Top Post Views - Latest Post</p>
            <h3 class="row-stat-value"><?php echo $maxviews ?></h3></br>
            <span class="label label-success row-stat-badge"><?php echo $post_title ?></span></br>
            <h6 class="row-stat-value">Posted: <?php echo $post_date ?></h6>
          </div> <!-- /.row-stat -->
        </div> <!-- /.col -->
        
      </div> <!-- /.row -->

<?php
//GRID
$xcrud = Xcrud::get_instance();
$xcrud->connection('db187835','Id@citi1026','db187835_wp_ida','internal-db.s187835.gridserver.com');
$xcrud->table('wp_idaposts');
$xcrud->table_name('Blog Posts');
$xcrud->query('select post_title,date(post_date) as Posted,CAST(meta_value AS UNSIGNED) as Views,guid as Post
from wp_idaposts,wp_idapostmeta
where wp_idapostmeta.post_id = wp_idaposts.id
and wp_idapostmeta.meta_key = "_count-views_all"
and wp_idaposts.post_type = "Post"
and wp_idaposts.post_title != "Auto Draft"
group by wp_idaposts.ID
order by Views DESC, Posted DESC');

$xcrud->columns('post_title,post_date,postcount,guid');
//$xcrud->fields('type,name,description,public,user');
//$xcrud->fields('comment,active');
$xcrud->subselect('Views','SELECT COUNT(card) FROM view_card WHERE card = {id}');
//$xcrud->subselect('Email','SELECT email FROM user WHERE id = {user}');
$xcrud->theme('bootstrap');
$xcrud->unset_title();
$xcrud->label('postcount', 'Views');
$xcrud->label('guid', 'Link');
$xcrud->links_label('View', 'guid');
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