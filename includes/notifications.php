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
          ['Month', 'Agents'],
          <?php
          		$result = mysql_query("select distinct EXTRACT(Month FROM loan_date)month, count(distinct loan_agent_mobile)agents from loan_application where EXTRACT(Year FROM loan_date) = '$filter_year' group by EXTRACT(Month FROM loan_date)");
          		$intcount = 0;
			while ($row = mysql_fetch_array($result))
			{
				$intcount++;
				$month = $row['month'];
				$agents = $row['agents'];
				$result_tender = mysql_query("select month from calender where id = '$month'");
				while ($row = mysql_fetch_array($result_tender))
				{
					$month_name = $row['month'];
				}
				?>
					['<?php echo $month_name ?>',  <?php echo $agents ?>,],

				<?php
			}
		?>
        ]);

        var options = {
          	backgroundColor: '#F8F2F2',
	   	chartArea:{left: 70, top:10, width:'90%', height:'80%'},
	    	hAxis: {title: 'Month',  titleTextStyle: {color: '#333'}},
		vAxis: {title: 'Number of Agents',  titleTextStyle: {color: '#333'}},
        };

        var chart = new google.visualization.ColumnChart(document.getElementById('total_agents'));
        chart.draw(data, options);
      }
    </script>
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Staff Nuggets', 'Popularity'],
          <?php
          	if($adminstatus == 1 || $adminstatus == 2){
          		$result = mysql_query("select distinct helpdesk_categories.category, count(support_logs.id)nuggets from support_logs inner join helpdesk_categories on helpdesk_categories.id = support_logs.report_type where user_detail = '$userid' and EXTRACT(Year FROM support_logs.transactiontime) = '$filter_year' group by report_type");
          	}
          	else{
          		$result = mysql_query("select distinct helpdesk_categories.category, count(support_logs.id)nuggets from support_logs inner join helpdesk_categories on helpdesk_categories.id = support_logs.report_type where user_detail = '$userid' and EXTRACT(Year FROM support_logs.transactiontime) = '$filter_year' group by report_type");
          	}
          		$intcount = 0;
			while ($row = mysql_fetch_array($result))
			{
				$intcount++;
				$nuggets_staff = $row['nuggets'];
				$category_staff = $row['category'];
				?>
					['<?php echo $category_staff ?>',  <?php echo $nuggets_staff ?>,],

				<?php
			}
		?>
        ]);

        var options = {
          	pieHole: 0.4,
          	backgroundColor: '#F8F2F2',
	  	chartArea:{left: 70, top:10, width:'90%', height:'80%'},
        };

        var chart = new google.visualization.PieChart(document.getElementById('nugget_log_staff'));
        chart.draw(data, options);
      }
    </script>
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Overall Nuggets', 'Popularity'],
          <?php
          	$result = mysql_query("select distinct helpdesk_categories.category, count(support_logs.id)nuggets from support_logs inner join helpdesk_categories on helpdesk_categories.id = support_logs.report_type where EXTRACT(Year FROM support_logs.transactiontime) = '$filter_year' group by report_type");
          	$intcount = 0;
		while ($row = mysql_fetch_array($result))
		{
			$nuggets = $row['nuggets'];
			$category = $row['category'];
			?>
				['<?php echo $category ?>',  <?php echo $nuggets ?>,],
			<?php
		}
		?>
        ]);

        var options = {
          	pieHole: 0.4,
          	backgroundColor: '#F8F2F2',
	  	chartArea:{left: 70, top:10, width:'90%', height:'80%'},
        };

        var chart = new google.visualization.PieChart(document.getElementById('nugget_log'));
        chart.draw(data, options);
      }
    </script>

