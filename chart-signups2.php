<?php
include("header.php");
include("chart-morris-signup.php");

?>
<!-- Morris TARGET -->
  <!-- Plugin JS -->
  <script src="./js/libs/raphael-2.1.2.min.js"></script>
  <script src="./js/plugins/morris/morris.min.js"></script> 
  <script src="./js/demos/charts/morris/line.js"></script>

<!-- Morris TODO
  <script src="js/charts/morris/raphael-min.js" cache="false"></script>
  <script src="js/charts/morris/morris.min.js" cache="false"></script> -->
  
    <!-- App JS -->
  <script src="./js/target-admin.js"></script>

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
//include("footer.php");
?>