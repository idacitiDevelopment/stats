<?php
include("header.php");
$count = 0;
$count2 = 0;
$count3 = 0;
$byday_array = '';
$byqtr_array = '';
$byyear_array = '';

//CHART BY DAY IN MONTH
$result = $db->select("select date(creation_time) as created,count(id) as cards
from card
where DATE(creation_time) > DATE_SUB(CURDATE(), INTERVAL 90 DAY)
group by date(creation_time)
");
while ($row = $db->get_row($result, 'MYSQL_ASSOC')) {
	$created = stripslashes ($row["created"]);
	$cards = stripslashes ($row["cards"]);
	$byday_array[$count] = array ($created,$cards);
				$count ++;}

//CHART BY WEEK IN QTR
$result = $db->select("select date_format(creation_time,'%v-Week-%M') as created,count(id) as cards
from card
where DATE(creation_time) > DATE_SUB(CURDATE(), INTERVAL 90 DAY)
group by weekofyear(creation_time)
");
while ($row = $db->get_row($result, 'MYSQL_ASSOC')) {
	$created = stripslashes ($row["created"]);
	$cards = stripslashes ($row["cards"]);
	$byqtr_array[$count2] = array ($created,$cards);
				$count2 ++;}

//CHART BY MONTH IN YEAR
$result = $db->select("select date_format(creation_time,'%m-%M') as created,count(id) as cards
from card
where DATE(creation_time) > DATE_SUB(CURDATE(), INTERVAL 365 DAY)
group by month(creation_time)
");
while ($row = $db->get_row($result, 'MYSQL_ASSOC')) {
	$created = stripslashes ($row["created"]);
	$cards = stripslashes ($row["cards"]);
	$byyear_array[$count3] = array ($created,$cards);
				$count3 ++;}

?>
<script src="./js/demos/charts/morris/signups.js"></script>

<div class="container">

  <div class="content">

    <div class="content-container">

      

      <div class="content-header">
        <h2 class="content-header-title">Cards Created</h2>
        <ol class="breadcrumb">
          <!--<li><a href="./index.html">Home</a></li>
          <li><a href="javascript:;">Extra Pages</a></li>
          <li class="active">User Cards Created</li>-->
        </ol>
      </div> <!-- /.content-header -->



<div class="portlet">

            <div class="portlet-header">

              <h3>
                <i class="fa fa-bar-chart-o"></i>
                User Card Timelines
              </h3>

            </div> <!-- /.portlet-header -->

            <div class="portlet-content">
            <ul id="tabs" class="nav nav-pills">
            <li class="active">
              <a href="#tab1" data-toggle="tab">By Day in Month</a>
            </li>
            <li>
              <a href="#tab2" data-toggle="tab">By Week in QTR</a>
            </li>
            <li>
              <a href="#tab3" data-toggle="tab">By Month in Year</a>
            </li>
            
          </ul>

              <div id="tabs" class="tab-content">
		<!-- BY DAY -->
		<div class="tab-pane fade in active" id="tab1">
      <script>
function byday () {
	$('#byday').empty ();
Morris.Line({
  element: 'byday',
  data: [
      <?php foreach($byday_array as $value){ ?>
        { created:'<?php echo $value[0]; ?>',cards: <?php echo $value[1]; ?> },
   <?php } ?>
         ],
  xkey: 'created',
  ykeys: ['cards'],
  labels: ['Cards','Date'],
  lineColors: ['#4da74d']
  
});
}
</script>
              <div id="byday" class="chart-holder" style="position: relative;"></div> 
              
              </div>
              <!-- BY QTR -->
              <div class="tab-pane fade in" id="tab2">
              <script>
              function byqtr () {
	$('#byqtr').empty ();
Morris.Line({
  element: 'byqtr',
  data: [
      <?php foreach($byqtr_array as $value){ ?>
        { created:'<?php echo $value[0]; ?>',cards: <?php echo $value[1]; ?> },
   <?php } ?>
         ],
  xkey: 'created',
  ykeys: ['cards'],
  labels: ['Cards','Date'],
  lineColors: ['#4da74d']
  
});
}
</script>
      
              <div id="byqtr" class="chart-holder" style="position: relative;"></div> 
              
              </div>
              <!-- BY YEAR -->
              <div class="tab-pane fade in" id="tab3">
              <script>
              function byyear () {
	$('#byyear').empty ();
Morris.Line({
  element: 'byyear',
  data: [
      <?php foreach($byyear_array as $value){ ?>
        { created:'<?php echo $value[0]; ?>',cards: <?php echo $value[1]; ?> },
   <?php } ?>
         ],
  xkey: 'created',
  ykeys: ['cards'],
  labels: ['Cards','Date'],
  lineColors: ['#4da74d']
  
});
}
</script>
      
              <div id="byyear" class="chart-holder" style="position: relative;"></div> 
              
              </div>
           
              </div>  <!-- /.TABS -->              

            </div> <!-- /.portlet-content -->

          </div> <!-- /.portlet -->
                
      

    </div> <!-- /.content-container -->
      
  </div> <!-- /.content -->

</div> <!-- /.container -->

<?php 
include("footer-chart.php");
?>