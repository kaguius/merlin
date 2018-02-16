<?php
	$userid = "";
	$adminstatus = 3;
	$property_manager_id = "";
	if (!empty($_SESSION)){
		$userid = $_SESSION["userid"] ;
		$adminstatus = $_SESSION["adminstatus"] ;
	}
	include_once('includes/db_conn.php');
	$expense_month = date("m");
	$expense_year = date("Y");
	if (!empty($_GET)){
		$filter_start_date = $_GET['report_start_date'];
		$filter_start_date = date('Y-m-d', strtotime(str_replace('-', '/', $filter_start_date)));
		$filter_end_date = $_GET['report_end_date'];
		$filter_end_date = date('Y-m-d', strtotime(str_replace('-', '/', $filter_end_date)));	
		$filter_start_date_1 = $filter_start_date.' 00:00:00';
		$filter_end_date_1 = $filter_end_date.' 23:59:59';
	}
?>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        	var data = google.visualization.arrayToDataTable([
        	['Day', 'Commissions'],
        	<?php
			$result = mysql_query("select distinct com_date, sum(com_amount)commissions from commissions where com_date between '$filter_start_date_1' and '$filter_end_date_1' group by com_date");
			while ($row = mysql_fetch_array($result))
			{
				$com_date = $row['com_date'];
				$commissions = $row['commissions'];

				?>
					['<?php echo $com_date ?>', <?php echo $commissions ?>],
				<?php
			}
		?>
       
        ]);

        var options = {
         	backgroundColor: '#F8F2F2',
    		chartArea:{left:70, top:10, width:'90%', height:'80%'},
    		hAxis: {title: 'Day',  titleTextStyle: {color: '#333'}},
		vAxis: {title: 'Commissions',  titleTextStyle: {color: '#333'}},
        };

        var chart = new google.visualization.ColumnChart(document.getElementById('commissions'));
        chart.draw(data, options);
      }
    </script>
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        	var data = google.visualization.arrayToDataTable([
        	['Day', 'Overdue Loan Balance'],
        	<?php
			$result = mysql_query("select Loan_Sched_Due_Date, loan_sched_due_amount from loan_schedule inner join loan_application on loan_application.Loan_id = loan_schedule.Loan_Sched_Loan_ID where Loan_Sched_Due_Date between '$filter_start_date' and '$filter_end_date' and loan_sched_type = '3' and Loan_Sched_Paid_Amount = '0'");
			while ($row = mysql_fetch_array($result))
			{
				$Loan_Sched_Due_Date = $row['Loan_Sched_Due_Date'];
				$loan_sched_due_amount = $row['loan_sched_due_amount'];

				?>
					['<?php echo $Loan_Sched_Due_Date ?>', <?php echo $loan_sched_due_amount ?>],
				<?php
			}
		?>
       
        ]);

        var options = {
         	backgroundColor: '#F8F2F2',
    		chartArea:{left:70, top:10, width:'90%', height:'80%'},
    		hAxis: {title: 'Day',  titleTextStyle: {color: '#333'}},
		vAxis: {title: 'Overdue Loan Balance',  titleTextStyle: {color: '#333'}},
        };

        var chart = new google.visualization.ColumnChart(document.getElementById('loan_balance'));
        chart.draw(data, options);
      }
    </script>
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        	var data = google.visualization.arrayToDataTable([
        	['Day', 'Revenue'],
        	<?php
			$result = mysql_query("select distinct loan_rep_date, sum(loan_rep_amount)loan_rep_amount from loan_repayments inner join users on users.Client_Mobile = loan_repayments.Loan_rep_mobile inner join loan_schedule on loan_schedule.Loan_Sched_ID = loan_repayments.Loan_Rep_Sched_ID where loan_rep_date between '$filter_start_date' and '$filter_end_date' and loan_schedule.Loan_Sched_Type = '3' group by loan_rep_date");
			while ($row = mysql_fetch_array($result))
			{
				$loan_rep_date = $row['loan_rep_date'];
				$loan_rep_amount = $row['loan_rep_amount'];

				?>
					['<?php echo $loan_rep_date ?>', <?php echo $loan_rep_amount ?>],
				<?php
			}
		?>
       
        ]);

        var options = {
         	backgroundColor: '#F8F2F2',
    		chartArea:{left:70, top:10, width:'90%', height:'80%'},
    		hAxis: {title: 'Day',  titleTextStyle: {color: '#333'}},
		vAxis: {title: 'Revenue',  titleTextStyle: {color: '#333'}},
        };

        var chart = new google.visualization.ColumnChart(document.getElementById('revenue'));
        chart.draw(data, options);
      }
    </script>
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        	var data = google.visualization.arrayToDataTable([
        	['Day', 'Interest'],
        	<?php
			$result = mysql_query("select distinct loan_rep_date, sum(loan_rep_amount)loan_rep_amount from loan_repayments inner join users on users.Client_Mobile = loan_repayments.Loan_rep_mobile inner join loan_schedule on loan_schedule.Loan_Sched_ID = loan_repayments.Loan_Rep_Sched_ID where loan_rep_date between '$filter_start_date' and '$filter_end_date' and loan_schedule.Loan_Sched_Type = '1' group by loan_rep_date");
			while ($row = mysql_fetch_array($result))
			{
				$loan_rep_date = $row['loan_rep_date'];
				$loan_rep_amount = $row['loan_rep_amount'];

				?>
					['<?php echo $loan_rep_date ?>', <?php echo $loan_rep_amount ?>],
				<?php
			}
		?>
       
        ]);

        var options = {
         	backgroundColor: '#F8F2F2',
    		chartArea:{left:70, top:10, width:'90%', height:'80%'},
    		hAxis: {title: 'Day',  titleTextStyle: {color: '#333'}},
		vAxis: {title: 'Interest',  titleTextStyle: {color: '#333'}},
        };

        var chart = new google.visualization.ColumnChart(document.getElementById('interest'));
        chart.draw(data, options);
      }
    </script>
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        	var data = google.visualization.arrayToDataTable([
        	['Day', 'Penalties'],
        	<?php
			$result = mysql_query("select distinct loan_rep_date, sum(loan_rep_amount)loan_rep_amount from loan_repayments inner join users on users.Client_Mobile = loan_repayments.Loan_rep_mobile inner join loan_schedule on loan_schedule.Loan_Sched_ID = loan_repayments.Loan_Rep_Sched_ID where loan_rep_date between '$filter_start_date' and '$filter_end_date' and loan_schedule.Loan_Sched_Type = '2' group by loan_rep_date");
			while ($row = mysql_fetch_array($result))
			{
				$loan_rep_date = $row['loan_rep_date'];
				$loan_rep_amount = $row['loan_rep_amount'];

				?>
					['<?php echo $loan_rep_date ?>', <?php echo $loan_rep_amount ?>],
				<?php
			}
		?>
       
        ]);

        var options = {
         	backgroundColor: '#F8F2F2',
    		chartArea:{left:70, top:10, width:'90%', height:'80%'},
    		hAxis: {title: 'Day',  titleTextStyle: {color: '#333'}},
		vAxis: {title: 'Penalties',  titleTextStyle: {color: '#333'}},
        };

        var chart = new google.visualization.ColumnChart(document.getElementById('penalties'));
        chart.draw(data, options);
      }
    </script>
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        	var data = google.visualization.arrayToDataTable([
        	['Day', 'Transactions'],
        	<?php
			$result = mysql_query("select distinct loan_rep_date, sum(loan_rep_amount)loan_rep_amount from loan_repayments inner join users on users.Client_Mobile = loan_repayments.Loan_rep_mobile inner join loan_schedule on loan_schedule.Loan_Sched_ID = loan_repayments.Loan_Rep_Sched_ID where loan_rep_date between '$filter_start_date' and '$filter_end_date' and loan_schedule.Loan_Sched_Type = '5' group by loan_rep_date");
			while ($row = mysql_fetch_array($result))
			{
				$loan_rep_date = $row['loan_rep_date'];
				$loan_rep_amount = $row['loan_rep_amount'];

				?>
					['<?php echo $loan_rep_date ?>', <?php echo $loan_rep_amount ?>],
				<?php
			}
		?>
       
        ]);

        var options = {
         	backgroundColor: '#F8F2F2',
    		chartArea:{left:70, top:10, width:'90%', height:'80%'},
    		hAxis: {title: 'Day',  titleTextStyle: {color: '#333'}},
		vAxis: {title: 'Transactions',  titleTextStyle: {color: '#333'}},
        };

        var chart = new google.visualization.ColumnChart(document.getElementById('transactions'));
        chart.draw(data, options);
      }
    </script>
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        	var data = google.visualization.arrayToDataTable([
        	['Day', 'Defaulters'],
        	<?php
			$result = mysql_query("select distinct Loan_Sched_Due_Date, count(loan_sched_due_amount)members from loan_schedule inner join loan_application on loan_application.Loan_id = loan_schedule.Loan_Sched_Loan_ID where Loan_Sched_Due_Date between '$filter_start_date' and '$filter_end_date' and Loan_Sched_Paid_Amount = '0' group by Loan_Sched_Due_Date");
			while ($row = mysql_fetch_array($result))
			{
				$Loan_Sched_Due_Date = $row['Loan_Sched_Due_Date'];
				$members = $row['members'];

				?>
					['<?php echo $Loan_Sched_Due_Date ?>', <?php echo $members ?>],
				<?php
			}
		?>
       
        ]);

        var options = {
         	backgroundColor: '#F8F2F2',
    		chartArea:{left:70, top:10, width:'90%', height:'80%'},
    		hAxis: {title: 'Day',  titleTextStyle: {color: '#333'}},
		vAxis: {title: 'Defaulters',  titleTextStyle: {color: '#333'}},
        };

        var chart = new google.visualization.ColumnChart(document.getElementById('defaulters'));
        chart.draw(data, options);
      }
    </script>
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        	var data = google.visualization.arrayToDataTable([
        	['Day', 'Expected Payments'],
        	<?php
			$result = mysql_query("select distinct Loan_Sched_Due_Date, sum(loan_sched_due_amount)loan_rep_amount from loan_schedule inner join loan_application on loan_application.Loan_id = loan_schedule.Loan_Sched_Loan_ID where loan_sched_due_date between '$filter_start_date' and '$filter_end_date' and loan_sched_paid_amount = '0' group by Loan_Sched_Due_Date");
			while ($row = mysql_fetch_array($result))
			{
				$Loan_Sched_Due_Date = $row['Loan_Sched_Due_Date'];
				$loan_rep_amount = $row['loan_rep_amount'];

				?>
					['<?php echo $Loan_Sched_Due_Date ?>', <?php echo $loan_rep_amount ?>],
				<?php
			}
		?>
       
        ]);

        var options = {
         	backgroundColor: '#F8F2F2',
    		chartArea:{left:70, top:10, width:'90%', height:'80%'},
    		hAxis: {title: 'Day',  titleTextStyle: {color: '#333'}},
		vAxis: {title: 'Expected Payments',  titleTextStyle: {color: '#333'}},
        };

        var chart = new google.visualization.ColumnChart(document.getElementById('expected_payments'));
        chart.draw(data, options);
      }
    </script>
