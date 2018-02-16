<?php
	$userid = "";
	$adminstatus = 3;
	$property_manager_id = "";
	if (!empty($_SESSION)){
		$userid = $_SESSION["userid"] ;
		$adminstatus = $_SESSION["adminstatus"] ;
		$username = $_SESSION["username"];
	}
	include_once('includes/db_conn.php');

	$filter_month = date("m");
	$filter_year = date("Y");
	$filter_day = date("d");
	$day_one = 01;
	
	$start_date = $filter_year.'-'.$filter_month.'-'.$day_one;
	$current_date = $filter_year.'-'.$filter_month.'-'.$filter_day;
	
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
?>
    
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Month', 'Loans', { role: 'style' }],
          <?php
          		$result = mysql_query("select distinct customer_id, stations, sum(loan_total_interest)loans from loan_application inner join users on users.id = loan_application.customer_id group by customer_id");
          		$intcount = 0;
			while ($row = mysql_fetch_array($result))
			{
				$intcount++;
				$stations = $row['stations'];
				$loans = $row['loans'];
				$result_tender = mysql_query("select stations from stations where id = '$stations'");
				while ($row = mysql_fetch_array($result_tender))
				{
					$stations_name = $row['stations'];
				}
				?>
					['<?php echo $stations_name ?>',  <?php echo $loans ?>, 'color: #66B845'],

				<?php
			}
		?>
        ]);

        var options = {
          	backgroundColor: '#F5F5F5',
			chartArea:{left: 70, top:10, width:'90%', height:'80%'},
	    	hAxis: {title: 'Branch',  titleTextStyle: {color: '#4F85C5'}},
			vAxis: {title: 'Total Loans',  titleTextStyle: {color: '#4F85C5'}},
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
          ['Time', 'Lending Rate', { role: 'style' }],
          <?php
          	$result = mysql_query("select distinct extract(hour from loan_date)hour, sum(loan_amount)loans from loan_application where loan_date between '$current_start_date' and '$current_end_date' group by  extract(hour from loan_date)");
          	$intcount = 0;
			while ($row = mysql_fetch_array($result))
			{
				$intcount++;
				$hour = $row['hour'];
				$loans = $row['loans'];
				?>
					['<?php echo $hour ?>',  <?php echo $loans ?>, 'color: #66B845'],

				<?php
			}
		?>
        ]);

        var options = {
          	backgroundColor: '#F5F5F5',
	   		chartArea:{left: 70, top:10, width:'90%', height:'80%'},
	    	hAxis: {title: 'Time',  titleTextStyle: {color: '#4F85C5'}},
			vAxis: {title: 'Loans',  titleTextStyle: {color: '#4F85C5'}},
        };

        var chart = new google.visualization.ColumnChart(document.getElementById('loan_rate'));
        chart.draw(data, options);
      }
    </script>
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Time', 'Repayment Rate', { role: 'style' }],
          <?php
          	$result = mysql_query("select distinct extract(hour from loan_rep_date)hour, sum(loan_rep_amount)repyment from loan_repayments where loan_rep_date between '$current_start_date' and '$current_end_date' group by  extract(hour from loan_rep_date)");
          	$intcount = 0;
			while ($row = mysql_fetch_array($result))
			{
				$intcount++;
				$hour = $row['hour'];
				$repyment = $row['repyment'];
				?>
					['<?php echo $hour ?>',  <?php echo $repyment ?>, 'color: #66B845'],

				<?php
			}
		?>
        ]);

        var options = {
          	backgroundColor: '#F5F5F5',
	   		chartArea:{left: 70, top:10, width:'90%', height:'80%'},
	    	hAxis: {title: 'Time',  titleTextStyle: {color: '#4F85C5'}},
			vAxis: {title: 'Repayments',  titleTextStyle: {color: '#4F85C5'}},
        };

        var chart = new google.visualization.ColumnChart(document.getElementById('repay_rate'));
        chart.draw(data, options);
      }
    </script>
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Accounts', 'Popularity'],
          <?php
          		$result = mysql_query("select distinct loan_status, customer_status.status, count(loan_application.loan_id)counts from loan_application inner join customer_status on customer_status.id = loan_application.loan_status group by loan_status");
          		$intcount = 0;
			while ($row = mysql_fetch_array($result))
			{
				$intcount++;
				$status = $row['status'];
				$counts = $row['counts'];
				?>
					['<?php echo $status ?>',  <?php echo $counts ?>,],

				<?php
			}
		?>
        ]);

        var options = {
          	pieHole: 0.4,
          	backgroundColor: '#F5F5F5',
	  	chartArea:{left: 70, top:10, width:'90%', height:'80%'},
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
          ['Month', 'Loans', { role: 'style' }],
          <?php
          		$result = mysql_query("select distinct EXTRACT(Month FROM loan_date)month, sum(loan_amount)loan from loan_application where EXTRACT(Year FROM loan_date) = '$filter_year' group by EXTRACT(Month FROM loan_date)");
          		$intcount = 0;
			while ($row = mysql_fetch_array($result))
			{
				$intcount++;
				$month = $row['month'];
				$loan = $row['loan'];
				$result_tender = mysql_query("select month from calender where id = '$month'");
				while ($row = mysql_fetch_array($result_tender))
				{
					$month_name = $row['month'];
				}
				?>
					['<?php echo $month_name ?>',  <?php echo $loan ?>, 'color: #66B845'],

				<?php
			}
		?>
        ]);

        var options = {
          	backgroundColor: '#F5F5F5',
	   	chartArea:{left: 70, top:10, width:'90%', height:'80%'},
	    	hAxis: {title: 'Month',  titleTextStyle: {color: '#4F85C5'}},
		vAxis: {title: 'Loans Applied',  titleTextStyle: {color: '#4F85C5'}},
        };

        var chart = new google.visualization.ColumnChart(document.getElementById('total_loans_amounts'));
        chart.draw(data, options);
      }
    </script>
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Month', 'Repayments', { role: 'style' }],
          <?php
          		$result = mysql_query("select distinct EXTRACT(Month FROM loan_rep_date)month, sum(loan_rep_amount)repayments from loan_repayments where EXTRACT(Year FROM loan_rep_date) = '$filter_year' group by EXTRACT(Month FROM loan_rep_date)");
          		$intcount = 0;
			while ($row = mysql_fetch_array($result))
			{
				$intcount++;
				$month = $row['month'];
				$repayments = $row['repayments'];
				$result_tender = mysql_query("select month from calender where id = '$month'");
				while ($row = mysql_fetch_array($result_tender))
				{
					$month_name = $row['month'];
				}
				?>
					['<?php echo $month_name ?>',  <?php echo $repayments ?>, 'color: #66B845'],

				<?php
			}
		?>
        ]);

        var options = {
          	backgroundColor: '#F5F5F5',
			chartArea:{left: 70, top:10, width:'90%', height:'80%'},
	    	hAxis: {title: 'Month',  titleTextStyle: {color: '#4F85C5'}},
			vAxis: {title: 'Loan Repayments',  titleTextStyle: {color: '#4F85C5'}},
        };

        var chart = new google.visualization.ColumnChart(document.getElementById('total_loan_repayments'));
        chart.draw(data, options);
      }
    </script>
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Month', 'Clients', { role: 'style' }],
          <?php
          		$result = mysql_query("select distinct EXTRACT(Month FROM loan_date)month, count(distinct loan_mobile)clients from loan_application where EXTRACT(Year FROM loan_date) = '$filter_year' group by EXTRACT(Month FROM loan_date)");
          		$intcount = 0;
			while ($row = mysql_fetch_array($result))
			{
				$intcount++;
				$month = $row['month'];
				$clients = $row['clients'];
				$result_tender = mysql_query("select month from calender where id = '$month'");
				while ($row = mysql_fetch_array($result_tender))
				{
					$month_name = $row['month'];
				}
				?>
					['<?php echo $month_name ?>',  <?php echo $clients ?>, 'color: #66B845'],

				<?php
			}
		?>
        ]);

        var options = {
          	backgroundColor: '#F5F5F5',
	   	chartArea:{left: 70, top:10, width:'90%', height:'80%'},
	    	hAxis: {title: 'Month',  titleTextStyle: {color: '#4F85C5'}},
		vAxis: {title: 'Clients Enrolled',  titleTextStyle: {color: '#4F85C5'}},
        };

        var chart = new google.visualization.ColumnChart(document.getElementById('total_clients'));
        chart.draw(data, options);
      }
    </script>
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Issues', 'Popularity', { role: 'style' }],
          <?php
          		$result = mysql_query("select distinct (helpdesk_categories.category)category, count(support_logs.id)logs from support_logs inner join helpdesk_categories on support_logs.report_type = helpdesk_categories.id where EXTRACT(Year FROM support_logs.transactiontime) = '2014' group by report_type");
			while ($row = mysql_fetch_array($result))
			{
				$category = $row['category'];
				$logs = $row['logs'];
				?>
					['<?php echo $category ?>',  <?php echo $logs ?>,],

				<?php
			}
		?>
        ]);

        var options = {
          	pieHole: 0.4,
          	backgroundColor: '#F5F5F5',
	  	chartArea:{left: 70, top:10, width:'90%', height:'80%'},
        };

        var chart = new google.visualization.PieChart(document.getElementById('total_issues'));
        chart.draw(data, options);
      }
    </script>
        <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Month', 'Loans', { role: 'style' }],
          <?php
          		$result = mysql_query("select distinct EXTRACT(Month FROM loan_date)month, sum(loan_total_interest)loan_count from loan_application where EXTRACT(Year FROM loan_date) = '$filter_year' and loan_agent_mobile = '$username' group by EXTRACT(Month FROM loan_date)");
          		$intcount = 0;
			while ($row = mysql_fetch_array($result))
			{
				$intcount++;
				$month = $row['month'];
				$loan_count = $row['loan_count'];
				$result_tender = mysql_query("select month from calender where id = '$month'");
				while ($row = mysql_fetch_array($result_tender))
				{
					$month_name = $row['month'];
				}
				?>
					['<?php echo $month_name ?>',  <?php echo $loan_count ?>, 'color: #66B845'],

				<?php
			}
		?>
        ]);

        var options = {
          	backgroundColor: '#F5F5F5',
	   	chartArea:{left: 70, top:10, width:'90%', height:'80%'},
	    	hAxis: {title: 'Month',  titleTextStyle: {color: '#4F85C5'}},
		vAxis: {title: 'Loans Applied',  titleTextStyle: {color: '#4F85C5'}},
        };

        var chart = new google.visualization.ColumnChart(document.getElementById('agent_total_loans_counts'));
        chart.draw(data, options);
      }
    </script>
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Month', 'Clients', { role: 'style' }],
          <?php
          		$result = mysql_query("select distinct EXTRACT(Month FROM loan_date)month, count(distinct loan_mobile)clients from loan_application where EXTRACT(Year FROM loan_date) = '$filter_year' and loan_agent_mobile = '$username' group by EXTRACT(Month FROM loan_date)");
          		$intcount = 0;
			while ($row = mysql_fetch_array($result))
			{
				$intcount++;
				$month = $row['month'];
				$clients = $row['clients'];
				$result_tender = mysql_query("select month from calender where id = '$month'");
				while ($row = mysql_fetch_array($result_tender))
				{
					$month_name = $row['month'];
				}
				?>
					['<?php echo $month_name ?>',  <?php echo $clients ?>, 'color: #66B845' ],

				<?php
			}
		?>
        ]);

        var options = {
          	backgroundColor: '#F5F5F5',
	   	chartArea:{left: 70, top:10, width:'90%', height:'80%'},
	    	hAxis: {title: 'Month',  titleTextStyle: {color: '#4F85C5'}},
		vAxis: {title: 'Clients Enrolled',  titleTextStyle: {color: '#4F85C5'}},
        };

        var chart = new google.visualization.ColumnChart(document.getElementById('agent_total_clients'));
        chart.draw(data, options);
      }
    </script>
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Month', 'Commissions', { role: 'style' }],
          <?php
          		$result = mysql_query("select distinct EXTRACT(Month FROM age_com_date)month, sum(age_com_amount)commissions from agent_commissions where EXTRACT(Year FROM age_com_date) = '$filter_year' and age_com_ag_mobile = '$username' group by EXTRACT(Month FROM age_com_date)");
          		$intcount = 0;
			while ($row = mysql_fetch_array($result))
			{
				$intcount++;
				$month = $row['month'];
				$commissions = $row['commissions'];
				$result_tender = mysql_query("select month from calender where id = '$month'");
				while ($row = mysql_fetch_array($result_tender))
				{
					$month_name = $row['month'];
				}
				?>
					['<?php echo $month_name ?>',  <?php echo $commissions ?>, 'color: #66B845'],
				<?php
			}
		?>
        ]);

        var options = {
          	backgroundColor: '#F5F5F5',
	   	chartArea:{left: 70, top:10, width:'90%', height:'80%'},
	    	hAxis: {title: 'Month',  titleTextStyle: {color: '#4F85C5'}},
		vAxis: {title: 'Commissions Enrolled',  titleTextStyle: {color: '#4F85C5'}},
        };

        var chart = new google.visualization.ColumnChart(document.getElementById('agent_total_commissions'));
        chart.draw(data, options);
      }
    </script>

