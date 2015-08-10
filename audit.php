<?php
include('header.php');
$count = 0;
$cyear = date("Y");
$chart = 'Line';
$chart_array = '';
$pie_array = '';
$title = '10 Year Trend: Income, Expense & Grants Paid';
$title2 = 'Current Year: Income vs. Expense';

//YEAR START
$result = $db->select("SELECT year_start_is
FROM  client
WHERE client_id = $client
");
while ($row = $db->get_row($result, 'MYSQL_ASSOC')) {
	$year_start_is = stripslashes ($row["year_start_is"]);
	$year_start_is2 = strtotime($year_start_is);
	$year_start_is2 = date("d-M-Y", $year_start_is2);
	}

//CHART area/bar/line
$result = $db->select("select year,income,expense,grants
from audit
where client_id = $client
and active = 1
order by year desc
LIMIT 10
");
while ($row = $db->get_row($result, 'MYSQL_ASSOC')) {
	$year = stripslashes ($row["year"]);
	$income = stripslashes ($row["income"]);
	$expense = stripslashes ($row["expense"]);
	$grants = stripslashes ($row["grants"]);
	$chart_array[$count] = array ($year,$income,$expense,$grants);
				$count ++;}

if($chart_array != '')
{
asort($chart_array);
}

//PIE
$result = $db->select("
select 'Income' as label, round(sum(amount),0) as value
from ledger
where client_id = $client
and type = 'Receipt'
and date > '$year_start_is'
UNION
select 'Expense' as label, round(sum(amount),0) as value
from ledger
where client_id = $client
and type = 'Expense'
and date > '$year_start_is'
");
while ($row = $db->get_row($result, 'MYSQL_ASSOC')) {
	$label = stripslashes ($row["label"]);
	$value = stripslashes ($row["value"]);
	if($value == ''){$value = 0;}
	if($value < 1){$value = $value * -1;}
	//$value = number_format($value,0);
	$pie_array[$count] = array ($label,$value);
				$count ++;}
?>
<!-- Morris -->
  <script src="js/charts/morris/raphael-min.js" cache="false"></script>
  <script src="js/charts/morris/morris.min.js" cache="false"></script>
<body>
  <section class="hbox stretch">
    <!-- .aside -->
<?php include('sidebar.php') ?>
    <!-- /.aside -->
    <!-- .vbox -->
    <section id="content">
      <section class="hbox stretch">
        <aside>
          <section class="vbox">
          <?php include('header-page.php'); ?>
            <section class="scrollable wrapper w-f">
              <section class="panel">
              <table>
              <tbody>
              <tr>
              
              <!--CHARTS-->
                      <!--PIE-->
                 <?php if($pie_array !='') { ?>
                 
                 <td width=40% valign = "top">
                  <div>
                  <section class="panel">
                    <header class="panel-heading"><h4><?php echo $title2 ?></h4></header>
                    <center><div id="morris-pie" style="width: 250px;height: 200px;"></div></center><br />
                    <footer class="panel-footer">
                    <p class="text-muted">
                    <h6><strong>Postings from <?php echo $year_start_is2 ?></strong></h6> 
                    </footer>
                   </section>
                   
                   
                   
                                     
                    <script>
Morris.Donut({
  element: 'morris-pie',
  data: [
      <?php foreach($pie_array as $value){ ?>
        { label:'<?php echo $value[0]; ?>',value: <?php echo $value[1]; ?> },
    <?php } ?>
         ],
          colors: ['#0b62a4','#FF0000']
});

</script>
</div>
</td>
<?php } ?>
<!-- BAR -->
                <?php if($chart == 'Bar') { ?>
                 <td width=60% valign = "top">
                  <div>
                  <section class="panel">
                    <header class="panel-heading"><h4><?php echo $title ?></h4></header>
                    <center><div id="morris-bar" style="width: 600px;height: 200px;"></div></center><br />
                    <footer class="panel-footer">
                    <p class="text-muted">
                       <!--<h4>Insight Owner's Perspective</h4>
                    <?php echo $value[1] ?>-->
                    </footer>
                   </section>
                   
                                     
                    <script>
Morris.Bar({
  element: 'morris-bar',
  data: [
      <?php foreach($chart_array as $value){ ?>
        { date:'<?php echo $value[0]; ?>',cost: <?php echo $value[1]; ?>,value: <?php echo $value[2]; ?>,date2: '<?php echo $value[3]; ?>' },
    <?php } ?>
         ],
   xkey: 'date',
  ykeys: ['cost','value'],
  labels: ['Cost','Value']
});

</script>
                    
                  
                </div>
                </td>
                <?php } ?>
                <!-- AREA -->
                <?php if($chart == 'Area') { ?>
                 <td width=60% valign = "top">
                  <div>
                  <section class="panel">
                    <header class="panel-heading"><h4><?php echo $title ?></h4></header>
                    <center><div id="morris-area" style="width: 600px;height: 200px;"></div></center><br />
                    <footer class="panel-footer">
                    <p class="text-muted">
                       <!--<h4>Insight Owner's Perspective</h4>
                    <?php echo $value[1] ?>-->
                    </footer>
                   </section>
                    
                    <script>
Morris.Area({
  element: 'morris-area',
  data: [
      <?php foreach($chart_array as $value){ ?>
        { date:'<?php echo $value[0]; ?>',cost: <?php echo $value[1]; ?>,value: <?php echo $value[2]; ?> },
    <?php } ?>
         ],
   xkey: 'date',
  ykeys: ['cost','value'],
  labels: ['Cost','Value']
});

</script>
                    
                  
                </div>
</td>
                <?php } ?>
                <!-- LINE -->
                <?php if($chart == 'Line') { ?>
                 <td width=60% valign = "top">
                  <div>
                  <section class="panel">
                    <header class="panel-heading"><h4><?php echo $title ?></h4></header>
                    <center><div id="morris-line" style="width: 600px;height: 200px;"></div></center><br />
                    <footer class="panel-footer">
                    <p class="text-muted">
                    <h6><strong>Based on valuation in annual accounts.</strong></h6> 
                    </footer>
                   </section>
                   
                    <script>
Morris.Line({
  element: 'morris-line',
  
  data: [
      <?php foreach($chart_array as $value){ ?>
        { year:'<?php echo $value[0]; ?>',income: <?php echo $value[1]; ?>,expense: <?php echo $value[2]; ?>, grants: <?php echo $value[3]; ?> },
    <?php } ?>
         ],
   xkey: 'year',
  ykeys: ['income','grants','expense'],
  labels: ['Income','Grants','Expenses'],
  lineColors: ['#0b62a4','#4da74d','#FF0000']
  
});

</script>
                    
                  
                </div>
</td>
                <?php } ?>
                
                
                <!-- END CHARTS -->
 </tbody>
 </tr>
 </table>             


<?php
//COMPANY GRID
$xcrud = Xcrud::get_instance();
$xcrud->table('audit');
$xcrud->theme('bootstrap');
//SQL
//$xcrud->relation('country_id','country','country_id','name');
//$xcrud->relation('type_id','category_type','type_id','name',array('active' => 1, 'client_id' => $client));

//$xcrud->subselect('Scenes','SELECT COUNT(account_id) FROM scene WHERE script_id = {script_id}');
$xcrud->where("","active = 1 and client_id = $client");
$xcrud->order_by('year','desc');
//$xcrud->readonly('inserted');
//GRID
$xcrud->columns('year,income,expense,grants,portfolio,file');
//FORM
$xcrud->fields('year,income,expense,grants,portfolio,file');
//$xcrud->fields('link,file,image','','Optional');

//ADD EDIT VARIABLES FOR SAVE
$xcrud->pass_var('inserted', date('Y-m-d'), 'create');
$xcrud->pass_var('insertby', $user, 'create');
$xcrud->pass_var('updateby', $user, 'create');
$xcrud->pass_var('user_id', $user, 'create');
$xcrud->pass_var('client_id', $client, 'create');
$xcrud->before_insert('xcrud_token', 'xcrud-function.php'); // token
//$xcrud->after_insert('myapplication_post', 'xcrud-function.php');
$xcrud->pass_var('updateby', $user, 'edit');
//ABOUT MODAL
//$xcrud->change_type('about','textarea','','');
//$xcrud->modal('about','icon-question-sign');
//$xcrud->column_class('about','align-center');
//IMAGE MODAL
$xcrud->modal('image');
    $xcrud->change_type('image', 'image', false, array(
        'width' => 450,
        'path' => '../upload/image',
        'thumbs' => array(array(
                'height' => 55,
                'width' => 120,
                'crop' => true,
                'marker' => '_th'))));
//FILE UPLOAD
$xcrud->change_type('file','file','',array('text' => '<i class = "icon-search"></i>','path'=>'../upload/audit'));
//LABELS
$xcrud->label('grants', 'Grants Paid');
$xcrud->label('file', 'PDF');

//LINKS AND EMAILS
$xcrud->links_label('View');
//$xcrud->emails_label('Send');
if($admin != 1)
{
$xcrud->unset_add();
$xcrud->unset_edit();
$xcrud->unset_remove();
}
//UNSETS
$xcrud->unset_title();
//$xcrud->unset_view();
$xcrud->unset_csv();
$xcrud->unset_print();
//$xcrud->unset_pagination();
$xcrud->unset_limitlist();
//SEARCH
$xcrud->unset_search();
//$xcrud->search_columns('name','name');
//PAGE LINK BUTTONS
//$xcrud->button('company-subs.php?id={token}','Company Subsidiaries','glyphicon glyphicon-home');
//$xcrud->button('company-metadata.php?id={token}','Company Subsidiaries','glyphicon glyphicon-move');
//$xcrud->button('trackboard-public.php?id={token}&id2=0','Company Trackboard','glyphicon glyphicon-stats');
//$xcrud->button('sec-company.php?id={token}','Company SEC Feed','glyphicon glyphicon-bell');
//$xcrud->button('company-kpi.php?id={token}','Company KPIs','glyphicon glyphicon-signal');
//$xcrud->button('company-disclosure.php?id={token}','Company Disclosures','glyphicon glyphicon-exclamation-sign');
//$xcrud->button('follow-company.php?id={token}','Follow this Company','glyphicon glyphicon-ok');
?>
<div class="row m-t-sm">
<div class="col-sm-12 m-b-xs">
<header class="panel-heading font-bold"><h1> Annual Audited Accounts</h1>
<!--<a class="btn btn-success pull-right" data-toggle="modal" href="#add"><i class="icon-plus"></i></a>-->
<hr>
<h4> Audited accounts listed latest first.</h4>
</header>
<div class="panel-body">
<div class="col-sm-12 m-b-xs">

										
<?php
//GRID
echo $xcrud->render();
?>

                </div>
              </section>
            </section>
          </section>
        </aside>
      </section>      
      <button type="button" class="btn btn-primary btn-cons"><i class="fa fa-check"></i>&nbsp;Submit</button>
    </section>
    <!-- /.vbox -->
  </section>
   <!-- ADD -->
					<div class="modal fade" id="add" tabindex="-1" role="dialog" aria-labelledby="add" aria-hidden="true">
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header modal-success">
									<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="icon-remove"></i></button>
									<h4 class="modal-title"><?php echo $page_add ?></h4>
								</div>

								<div class="modal-body">
									<div class="emailError"></div>
										<form action="idea-post.php" method="post" data-validate="parsley">
											
											<div class="form-group">
											<label for="category">Category</label>
											<select name="category" class="form-control m-b parsley-validated" data-rule-required="true">
											<option value="0">-- Please select --</option>
												<?php foreach ($category_array as $value) { ?>
												<option value="<?php echo $value[0] ?>"><?php echo $value[1] ?></option>
												<?php }

/* My Comment */ ?>
											</select>
										</div>
											<div class="form-group">
												<label for="name">Title</label>
												<input type="text" data-required = "true" class="form-control parsley-validated" name="name" id="name"  value="" placeholder="Name of customer" />
											</div>
											<div class="form-group">
											<label for="about">About</label>
									<textarea class="form-control" id="comment" name="about" rows="4"></textarea>
								</div>
											
								
											
					<input name="entity" id="entity" value="idea" type="hidden">
											
										<div class="modal-footer">
											<button type="button" class="btn btn-danger pull-left" value="cancel" data-dismiss="modal"><i class="icon-remove-sign"></i> Cancel</button>
											<button type="input" name="submit" class="btn btn-success pull-right" value="add" ><i class="icon-check"></i> Save</button>
											</br>
																	
										</form>
										</div>
										</div>
										</div>
										</div>
  </div>
  
<?php include('footer.php'); ?>