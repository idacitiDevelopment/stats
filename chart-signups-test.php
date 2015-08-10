<?php
include("header-nolog.php");
$count = 0;
$chart_array = '';

//CHART area/bar/line
$result = $db->select("select date(creation_time) as signup,count(id) as signups
from user
where is_active = 1 and is_root = 0
group by date(creation_time)
");
while ($row = $db->get_row($result, 'MYSQL_ASSOC')) {
	$signup = stripslashes ($row["signup"]);
	$signups = stripslashes ($row["signups"]);
	$chart_array[$count] = array ($signup,$signups);
				$count ++;}
?>
<script>
Morris.Line({
  element: 'line-chart',
  data: [
      <?php foreach($chart_array as $value){ ?>
        { signup:'<?php echo $value[0]; ?>',signups: <?php echo $value[1]; ?> },
   <?php } ?>
         ],
  xkey: 'signup',
  ykeys: ['signups'],
  labels: ['Date','Signups'],
  lineColors: ['#4da74d']
  
});

</script>

<div class="container">

  <div class="content">

    <div class="content-container">

      

      <div class="content-header">
        <h2 class="content-header-title">Signups</h2>
        <ol class="breadcrumb">
          <!--<li><a href="./index.html">Home</a></li>
          <li><a href="javascript:;">Extra Pages</a></li>-->
          <li class="active">User Signups by Week</li>
        </ol>
      </div> <!-- /.content-header -->



<div class="portlet">

            <div class="portlet-header">

              <h3>
                <i class="fa fa-bar-chart-o"></i>
                Line Chart
              </h3>

            </div> <!-- /.portlet-header -->

            <div class="portlet-content">

              <div id="line-chart" class="chart-holder" style="position: relative;"></div>              

            </div> <!-- /.portlet-content -->

          </div> <!-- /.portlet -->
                
      

    </div> <!-- /.content-container -->
      
  </div> <!-- /.content -->

</div> <!-- /.container -->

<?php 
include("footer-chart.php");
?>