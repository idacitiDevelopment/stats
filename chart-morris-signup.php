<?php
require_once ("includes/db.class.php");
$db = new db_class;
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
$(function () {

	if (!$('#line-chart').length) { return false; }
	
	line ();

	$(window).resize (target_admin.debounce (line, 325));

});

function line () {
	$('#line-chart').empty ();

	Morris.Line({
  element: 'line-chart',
  data: [
      <?php foreach($chart_array as $value){ ?>
        { signup:'<?php echo $value[0]; ?>',signups: <?php echo $value[1]; ?> },
   <?php } ?>
         ],
  xkey: 'signup',
  ykeys: ['signup,signups'],
  labels: ['Date,Signups''],
  lineColors: ['#0b62a4','#4da74d','#FF0000']
  
});

}
</script>