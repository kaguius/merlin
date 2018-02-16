<?php
	$userid = "";
	$adminstatus = 3;
	$property_manager_id = "";
	if (!empty($_SESSION)){
		$userid = $_SESSION["userid"] ;
		$adminstatus = $_SESSION["adminstatus"] ;
		$username = $_SESSION["username"];
		$station = $_SESSION["station"] ;
	}
	include_once('includes/db_conn.php');

	$filter_month = date("m");
	$filter_year = date("Y");
	$filter_day = date("d");
	$day_one = 01;
	$report_day_one = 16;
	$report_day_last = 15;
	
	$start_date = $filter_year.'-'.$filter_month.'-'.$day_one;
	$current_date = $filter_year.'-'.$filter_month.'-'.$filter_day;
	
	$report_start_date = $filter_year.'-'.$filter_month.'-'.$report_day_one;
	$report_end_date = $filter_year.'-'.$filter_month.'-'.$day_one;
	
	$current_start_date = $current_date.' 00:00:00';
	$current_end_date = $current_date.' 23:59:59';
	
	$diff = abs(strtotime($current_date) - strtotime($start_date));

	$years = floor($diff / (365*60*60*24));
	$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
	$days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
	if (!empty($_GET)){	
		$month_drill = $_GET['month'];
		$page_title = $_GET['title'];
	}
	//echo $view;
	//$station = 2 ;

?>
    
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
 <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Time', 'Loans Disbursed', { role: 'style' }],
          <?php
          	$result = mysql_query("select distinct EXTRACT(month FROM loan_date)day, sum(loan_amount)loan_payments from loan_application where EXTRACT(year FROM loan_date) = '$filter_year' and customer_station = '$station'  and customer_station != '0' and loan_failure_status = '0' and loan_status != '10' and loan_status != '9' and loan_status != '11' and loan_status != '12' and loan_status != '14' group by EXTRACT(month FROM loan_date) order by EXTRACT(month FROM loan_date) asc");
          	
			while ($row = mysql_fetch_array($result))
			{
				$day = $row['day'];
				$loan_payments = $row['loan_payments'];
				$result_tender = mysql_query("select month from calender where id = '$day'");
				while ($row = mysql_fetch_array($result_tender))
				{
					$month_name = $row['month'];
				}
				?>
					['<?php echo $month_name ?>',  <?php echo $loan_payments ?>, '#E86424'],

				<?php
			}
		?>
        ]);

        var options = {
          	backgroundColor: '#F5F5F5',
	   	chartArea:{left: 80, top:10, width:'90%', height:'80%'},
	    	hAxis: {title: 'Month',  titleTextStyle: {color: '#333'}},
		vAxis: {title: 'Loans Disbursed',  titleTextStyle: {color: '#333'}},
        };

        var chart = new google.visualization.ColumnChart(document.getElementById('branches_breakdown'));
        chart.draw(data, options);
      }
    </script>
<script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Branch', 'Disbursement Target', 'Disbursement Actual', 'Daily Collectable', 'Daily Collectible Actual', { role: 'style' }],
          <?php
          	$result_tender = mysql_query("select station, age_months, disbursement_target, disbursement_actual, daily_collectable, collectable_actual from business_totals_daily");
			while ($row = mysql_fetch_array($result_tender))
			{
				$stations = $row['station'];
				$age_months = $row['age_months'];
				$disbursement_target = $row['disbursement_target'];
				$disbursement_actual = $row['disbursement_actual'];
				$daily_collectable = $row['daily_collectable'];
				$collectable_actual = $row['collectable_actual'];
				$result = mysql_query("select stations from stations where id = '$stations'");
				while ($row = mysql_fetch_array($result))
				{
					$stations_name = $row['stations'];
				}
				?>
					['<?php echo $stations_name ?>', <?php echo $disbursement_target ?>, <?php echo $disbursement_actual ?>, <?php echo $daily_collectable ?>, <?php echo $collectable_actual ?>, '#66B845'],

				<?php
			}
		?>
        ]);

        var options = {
          hAxis: {title: 'Branch',  titleTextStyle: {color: '#333'}},
          chartArea:{left: 100, top:10, width:'90%', height:'80%'},
          curveType: 'function',
          vAxis: {minValue: 0},
          legend: { position: 'bottom' }
          
        };

        var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
        chart.draw(data, options);
      }
    </script>
    
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Month', 'Target', 'Disbursement', 'Collectable', 'Collectible Actual'],
          <?php
          	$result_tender = mysql_query("select month, branches, target, disbursement, collectable, collectable_actual from business_totals_monthly");
			while ($row = mysql_fetch_array($result_tender))
			{
				$month = $row['month'];
				$age_months = $row['branches'];
				$target = $row['target'];
				$disbursement = $row['disbursement'];
				$collectable = $row['collectable'];
				$collectable_actual = $row['collectable_actual'];
				$result = mysql_query("select month from calender where id = '$month'");
				while ($row = mysql_fetch_array($result))
				{
					$month_name = $row['month'];
				}
				?>
					['<?php echo $month_name ?>',  <?php echo $target ?>, <?php echo $disbursement ?>, <?php echo $collectable ?>, <?php echo $collectable_actual ?>],

				<?php
			}
		?>
        ]);

        var options = {
          hAxis: {title: 'Month',  titleTextStyle: {color: '#333'}},
          chartArea:{left: 70, top:10, width:'90%', height:'80%'},
          curveType: 'function',
          vAxis: {minValue: 0},
          legend: { position: 'bottom' }
          
        };

        var chart = new google.visualization.ColumnChart(document.getElementById('monthly_chart_div'));
        chart.draw(data, options);
      }
    </script>
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {

        var data = google.visualization.arrayToDataTable([
          ['Loan Status', 'Totals'],
          <?php
          	$result_tender = mysql_query("select distinct loan_status, count(loan_id)counts from loan_application where customer_station = '$station' and loan_status != '8' and loan_status != '11' and loan_status != '12' and loan_status != '14' and loan_status != '16' group by loan_status");
			while ($row = mysql_fetch_array($result_tender))
			{
				$loan_status = $row['loan_status'];
				$counts = $row['counts'];
				$result = mysql_query("select status from customer_status where id = '$loan_status'");
				while ($row = mysql_fetch_array($result))
				{
					$status = $row['status'];
				}
				?>
					['<?php echo $status ?>',  <?php echo $counts ?>],

				<?php
			}
		?>
        ]);

        var options = {
           pieHole: 0.4,
           backgroundColor: '#F5F5F5',
	  	   chartArea:{left: 70, top:10, width:'90%', height:'80%'},
	  	   is3D: true,
        };

        var chart = new google.visualization.PieChart(document.getElementById('loan_status'));

        chart.draw(data, options);
      }
    </script>

    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {

        var data = google.visualization.arrayToDataTable([
          ['Loan Status', 'Totals'],
          <?php
          	$result_tender = mysql_query("select distinct call_outcome, count(id)counts from promise_to_pay where call_outcome != '0' group by call_outcome");
			while ($row = mysql_fetch_array($result_tender))
			{
				$call_outcome = $row['call_outcome'];
				$counts = $row['counts'];
				$result = mysql_query("select reason_code from call_outcome where id = '$call_outcome'");
				while ($row = mysql_fetch_array($result))
				{
					$reason_code = $row['reason_code'];
				}
				?>
					['<?php echo $reason_code ?>',  <?php echo $counts ?>],

				<?php
			}
		?>
        ]);

        var options = {
           pieHole: 0.4,
           backgroundColor: '#F5F5F5',
	  	   chartArea:{left: 70, top:10, width:'90%', height:'80%'},
	  	   is3D: true,
        };

        var chart = new google.visualization.PieChart(document.getElementById('call_outcomes'));

        chart.draw(data, options);
      }
    </script>
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Time', 'Loans', { role: 'style' }, { role: 'annotation' }],
          <?php
          	$result = mysql_query("select distinct collections_agent, sum(loan_rep_amount)loan_rep_amount from loan_repayments inner join loan_application on loan_application.loan_code = loan_repayments.loan_rep_code where EXTRACT(month FROM loan_rep_date) = '$filter_month' and  EXTRACT(year FROM loan_rep_date) = '$filter_year' and vintage != '' and collections_agent != '0' group by collections_agent order by loan_rep_amount asc");
			while ($row = mysql_fetch_array($result))
			{
				$collections_agent = $row['collections_agent'];
				$loan_rep_amount = $row['loan_rep_amount'];
				$result_tender = mysql_query("select first_name, last_name from user_profiles where id = '$collections_agent'");
				while ($row = mysql_fetch_array($result_tender))
				{
					$first_name = $row['first_name'];
					$last_name = $row['last_name'];
					$collections_agent_name = $first_name.' '.$last_name;
				}
				?>
					['<?php echo $collections_agent_name ?>',  <?php echo $loan_rep_amount ?>, '#66B845', <?php echo $loan_rep_amount ?>],

				<?php
			}
		?>
        ]);

        var options = {
          	backgroundColor: '#F5F5F5',
	   		chartArea:{left: 70, top:10, width:'90%', height:'80%'},
	    	hAxis: {title: 'Collections Agent',  titleTextStyle: {color: '#333'}},
			vAxis: {title: 'Payments',  titleTextStyle: {color: '#333'}},
        };

        var chart = new google.visualization.ColumnChart(document.getElementById('collections'));
        chart.draw(data, options);
      }
    </script>
