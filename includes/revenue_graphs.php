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
		$filter_start_date = $_GET['report_start_date'];
		$filter_start_date = date('Y-m-d', strtotime(str_replace('-', '/', $filter_start_date)));
		$filter_end_date = $_GET['report_end_date'];
		$filter_end_date = date('Y-m-d', strtotime(str_replace('-', '/', $filter_end_date)));
		$mode = $_GET['mode'];
		$subcounty = $_GET['subcounty'];
		$revenue = $_GET['revenue'];
		$category = $_GET['category'];
		$cat = $_GET['cat'];
		$product = $_GET['product'];
		$filter = $_GET['filter'];
		$clerk = $_GET['clerk'];
		$date_paid = $_GET['paid_date'];
	}
?>
   
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
      	google.load("visualization", "1", {packages:["corechart"]});
      	google.setOnLoadCallback(drawChart);
      	function drawChart() {
        	var data = google.visualization.arrayToDataTable([
        		//['Month', 'Revenue'],
        		['Sub County', 'link', 'Revenue', { role: 'annotation' }],
        		<?php
				//$results = mysql_query("select distinct invoice_month, sum(invoice_amount)invoice_amount from juja_market_fees.statement_report group by invoice_month;");
				$result_tender = mysql_query("select sum(juja_quarry.statements.invoice_amount)juja_quarry, (select sum(juja_market_fees.statements.invoice_amount)juja_market_fees from juja_market_fees.statements where EXTRACT(YEAR FROM juja_market_fees.statements.paid_date) = '$filter_year' and juja_market_fees.statements.status = 'Paid')juja_market_fees, (select sum(ruiru_parking.statements.invoice_amount)ruiru_parking from ruiru_parking.statements where EXTRACT(YEAR FROM ruiru_parking.statements.paid_date) = '$filter_year' and ruiru_parking.statements.status = 'Paid')ruiru_parking, (select sum(kiambu_parking.statements.invoice_amount)kiambu_parking from kiambu_parking.statements where EXTRACT(YEAR FROM kiambu_parking.statements.paid_date) = '$filter_year' and kiambu_parking.statements.status = 'Paid')kiambu_parking, (select sum(kiambu_market_fees.statements.invoice_amount)kiambu_market_fees from kiambu_market_fees.statements where EXTRACT(YEAR FROM kiambu_market_fees.statements.paid_date) = '$filter_year' and kiambu_market_fees.statements.status = 'Paid')kiambu_market_fees, (select sum(ruiru_market_fees.statements.invoice_amount)ruiru_market_fees from ruiru_market_fees.statements where EXTRACT(YEAR FROM ruiru_market_fees.statements.paid_date) = '$filter_year' and ruiru_market_fees.statements.status = 'Paid')ruiru_market_fees, (select sum(thika_parking.statements.invoice_amount)thika_parking from thika_parking.statements where EXTRACT(YEAR FROM thika_parking.statements.paid_date) = '$filter_year' and thika_parking.statements.status = 'Paid')thika_parking, (select sum(thika_market_fees.statements.invoice_amount)thika_market_fees from thika_market_fees.statements where EXTRACT(YEAR FROM thika_market_fees.statements.paid_date) = '$filter_year' and thika_market_fees.statements.status = 'Paid')thika_market_fees from juja_quarry.statements where EXTRACT(YEAR FROM paid_date) = '$filter_year' and status = 'Paid';");
				while ($row = mysql_fetch_array($result_tender))
				{
					$juja_quarry = $row['juja_quarry'];
					$juja_market_fees = $row['juja_market_fees'];
					$kiambu_market_fees = $row['kiambu_market_fees'];
					$kiambu_parking = $row['kiambu_parking'];
					$ruiru_market_fees = $row['ruiru_market_fees'];
					$ruiru_parking = $row['ruiru_parking'];
					$thika_market_fees = $row['thika_market_fees'];
					$thika_parking = $row['thika_parking'];
					
					$parking = $ruiru_parking + $thika_parking + $juja_parking;
					$markets = $juja_market_fees + $ruiru_market_fees + $thika_market_fees;
					$quarry = $juja_quarry;

					?>
						['Parking', 'revenues.php?report_start_date=<?php echo $start_date ?>&report_end_date=<?php echo $current_date ?>&revenue=parking&subcounty=juja&mode=drill2', <?php echo $parking?>, '<?php echo $parking?>'],
						['Quarry', 'revenues.php?report_start_date=<?php echo $start_date ?>&report_end_date=<?php echo $current_date ?>&revenue=quarry&subcounty=ruiru&mode=drill2', <?php echo $quarry?>, '<?php echo $quarry?>'],
						['Markets', 'revenues.php?report_start_date=<?php echo $start_date ?>&report_end_date=<?php echo $current_date ?>&revenue=market&subcounty=thika&mode=drill2', <?php echo $markets?>, '<?php echo $markets?>'],
					<?php
				}
			?>
        	]);
        
        var view = new google.visualization.DataView(data);
      	view.setColumns([0, 2]);

        var options = {
        	backgroundColor: '#F0F1F1',
    		chartArea:{left:100,top:10,width:'90%',height:'80%'},
    		hAxis: {title: 'Revenue',  titleTextStyle: {color: '#333'}},
		vAxis: {title: 'Revenue Stream',  titleTextStyle: {color: '#333'}},
		pieHole: 0.4,
        };
        	
        var chart = new google.visualization.ColumnChart(document.getElementById('streams'));
        chart.draw(view, options);
        	
        var selectHandler = function(e) {
        	window.location = data.getValue(chart.getSelection()[0]['row'], 1 );
       	}
       		
       	google.visualization.events.addListener(chart, 'select', selectHandler);
    }
</script>

<script type="text/javascript">
      	google.load("visualization", "1", {packages:["corechart"]});
      	google.setOnLoadCallback(drawChart);
      	function drawChart() {
        	var data = google.visualization.arrayToDataTable([
        		//['Month', 'Revenue'],
        		['Sub County', 'link', 'Revenue', { role: 'annotation' }],
        		<?php
				//$results = mysql_query("select distinct invoice_month, sum(invoice_amount)invoice_amount from juja_market_fees.statement_report group by invoice_month;");
				if($revenue == 'parking'){
					$result_tender = mysql_query("select sum(thika_parking.statements.invoice_amount)thika_parking, (select sum(ruiru_parking.statements.invoice_amount)ruiru_parking from ruiru_parking.statements where EXTRACT(YEAR FROM statements.paid_date) = '$filter_year' and EXTRACT(MONTH FROM paid_date) = '$filter_month' and status = 'Paid')ruiru_parking from thika_parking.statements where EXTRACT(YEAR FROM paid_date) = '$filter_year' and EXTRACT(MONTH FROM paid_date) = '$filter_month' and status = 'Paid'");
					while ($row = mysql_fetch_array($result_tender))
					{
						$ruiru_parking = $row['ruiru_parking'];
						$thika_parking = $row['thika_parking'];

						?>
							['Thika', 'revenues.php?report_start_date=<?php echo $start_date ?>&report_end_date=<?php echo $current_date ?>&revenue=parking&subcounty=thika&mode=drill3',<?php echo $thika_parking?>, '<?php echo $thika_parking?>'],
							['Ruiru', 'revenues.php?report_start_date=<?php echo $start_date ?>&report_end_date=<?php echo $current_date ?>&revenue=parking&subcounty=ruiru&mode=drill3',<?php echo $ruiru_parking?>, '<?php echo $thika_parking?>'],
						<?php
					}
				}
				else if($revenue == 'market'){
					$result_tender = mysql_query("select sum(juja_market_fees.statements.invoice_amount)juja_market_fees, (select sum(ruiru_market_fees.statements.invoice_amount)ruiru_market_fees from ruiru_market_fees.statements where EXTRACT(YEAR FROM ruiru_market_fees.statements.paid_date) = '$filter_year' and EXTRACT(MONTH FROM ruiru_market_fees.statements.paid_date) = '$filter_month' and ruiru_market_fees.statements.status = 'Paid')ruiru_market_fees, (select sum(thika_market_fees.statements.invoice_amount)thika_market_fees from thika_market_fees.statements where EXTRACT(YEAR FROM thika_market_fees.statements.paid_date) = '$filter_year' and EXTRACT(MONTH FROM thika_market_fees.statements.paid_date) = '$filter_month' and thika_market_fees.statements.status = 'Paid')thika_market_fees from juja_market_fees.statements where EXTRACT(YEAR FROM paid_date) = '$filter_year' and EXTRACT(MONTH FROM juja_market_fees.statements.paid_date) = '$filter_month' and status = 'Paid'");
					while ($row = mysql_fetch_array($result_tender))
					{
						$juja_market_fees = $row['juja_market_fees'];
						$ruiru_market_fees = $row['ruiru_market_fees'];
						$thika_market_fees = $row['thika_market_fees'];

						?>
							['Thika', 'revenues.php?report_start_date=<?php echo $start_date ?>&report_end_date=<?php echo $current_date ?>&revenue=market&subcounty=thika&mode=drill3',<?php echo $thika_market_fees ?>, '<?php echo $thika_market_fees ?>'],
							['Ruiru', 'revenues.php?report_start_date=<?php echo $start_date ?>&report_end_date=<?php echo $current_date ?>&revenue=market&subcounty=ruiru&mode=drill3',<?php echo $ruiru_market_fees ?>, '<?php echo $ruiru_market_fees ?>'],
							['Juja', 'revenues.php?report_start_date=<?php echo $start_date ?>&report_end_date=<?php echo $current_date ?>&revenue=market&subcounty=juja&mode=drill3',<?php echo $juja_market_fees ?>, '<?php echo $juja_market_fees ?>'],
						<?php
					}
				}
				else if($revenue == 'quarry'){
					$result_tender = mysql_query("select sum(juja_quarry.statements.invoice_amount)juja_quarry from juja_quarry.statements where EXTRACT(YEAR FROM paid_date) = '$filter_year' and EXTRACT(MONTH FROM paid_date) = '$filter_month' and status = 'Paid'");
					while ($row = mysql_fetch_array($result_tender))
					{
						$juja_quarry = $row['juja_quarry'];

						?>
							['Juja', 'revenues.php?report_start_date=<?php echo $start_date ?>&report_end_date=<?php echo $current_date ?>&revenue=quarry&subcounty=juja&mode=drill3',<?php echo $juja_quarry ?>, '<?php echo $juja_quarry ?>'],
						<?php
					}
				}
			?>
        	]);

       	
       	var view = new google.visualization.DataView(data);
      	view.setColumns([0, 2]);

        var options = {
        	legend: { position: 'top' },
    		backgroundColor: '#F0F1F1',
    		chartArea:{left:100,top:10,width:'90%',height:'75%'},
    		hAxis: {title: 'Revenue Stream',  titleTextStyle: {color: '#000A8B'}},
		vAxis: {title: 'Revenue',  titleTextStyle: {color: '#000A8B'}},
        };
        	
        var chart = new google.visualization.ColumnChart(document.getElementById('streams_countys'));
        chart.draw(view, options);
        	
        var selectHandler = function(e) {
        	window.location = data.getValue(chart.getSelection()[0]['row'], 1 );
       	}
       		
       	google.visualization.events.addListener(chart, 'select', selectHandler);
    }
</script>

<script type="text/javascript">
      	google.load("visualization", "1", {packages:["corechart"]});
      	google.setOnLoadCallback(drawChart);
      	function drawChart() {
        	var data = google.visualization.arrayToDataTable([
        		//['Month', 'Revenue'],
        		['Sub County', 'link', 'Revenue', { role: 'annotation' }],
        		<?php
				//$results = mysql_query("select distinct invoice_month, sum(invoice_amount)invoice_amount from juja_market_fees.statement_report group by invoice_month;");
				if($revenue == 'parking'){
					$result_tender = mysql_query("select distinct subcategories.name, subcategories.id, sum(invoice_amount)invoice from ".$subcounty."_".$revenue.".statements inner join ".$subcounty."_".$revenue.".subcategories on subcategories.id = statements.subcategory where EXTRACT(YEAR FROM ".$subcounty."_".$revenue.".statements.paid_date) = '$filter_year' and EXTRACT(MONTH FROM ".$subcounty."_".$revenue.".statements.paid_date) = '$filter_month' and ".$subcounty."_".$revenue.".statements.status = 'Paid' group by subcategory");
					while ($row = mysql_fetch_array($result_tender))
					{
						$subcategory = $row['name'];
						$cat = $row['id'];
						$invoice = $row['invoice'];

						?>
							['<?php echo $subcategory ?>', 'revenues.php?report_start_date=<?php echo $start_date ?>&report_end_date=<?php echo $current_date ?>&cat=<?php echo $cat ?>&revenue=<?php echo $revenue ?>&subcounty=<?php echo $subcounty ?>&mode=drill4',<?php echo $invoice ?>, '<?php echo $invoice ?>'],
						<?php
					}
				}
				else if($revenue == 'quarry'){
					$result_tender = mysql_query("select distinct subcategories.name, subcategories.id, sum(invoice_amount)invoice from ".$subcounty."_".$revenue.".statements inner join ".$subcounty."_".$revenue.".subcategories on subcategories.id = statements.subcategory where EXTRACT(YEAR FROM ".$subcounty."_".$revenue.".statements.paid_date) = '$filter_year' and EXTRACT(MONTH FROM ".$subcounty."_".$revenue.".statements.paid_date) = '$filter_month' and ".$subcounty."_".$revenue.".statements.status = 'Paid' group by subcategory");
					while ($row = mysql_fetch_array($result_tender))
					{
						$subcategory = $row['name'];
						$cat = $row['id'];
						$invoice = $row['invoice'];

						?>
							['<?php echo $subcategory ?>', 'revenues.php?report_start_date=<?php echo $start_date ?>&report_end_date=<?php echo $current_date ?>&cat=<?php echo $cat ?>&revenue=<?php echo $revenue ?>&subcounty=<?php echo $subcounty ?>&mode=drill4',<?php echo $invoice ?>, '<?php echo $invoice ?>'],
						<?php
					}
				}
				else if($revenue == 'market'){
					$result_tender = mysql_query("select distinct subcategory, sum(invoice_amount)invoice from ".$subcounty."_".$revenue."_fees.statements where EXTRACT(YEAR FROM paid_date) = '$filter_year' and EXTRACT(MONTH FROM paid_date) = '$filter_month' and status = 'Paid' group by subcategory");
					while ($row = mysql_fetch_array($result_tender))
					{
						$subcategory = $row['subcategory'];
						$invoice = $row['invoice'];

						?>
							['<?php echo $subcategory ?>', 'revenues.php?report_start_date=<?php echo $start_date ?>&report_end_date=<?php echo $current_date ?>&category=<?php echo $subcategory ?>&revenue=<?php echo $revenue ?>&subcounty=<?php echo $subcounty ?>&mode=drill4',<?php echo $invoice ?>, '<?php echo $invoice ?>'],
						<?php
					}
				}
				
			?>
        	]);

       	
       	var view = new google.visualization.DataView(data);
      	view.setColumns([0, 2]);

        var options = {
        	legend: { position: 'top' },
    		backgroundColor: '#F0F1F1',
    		chartArea:{left:100,top:10,width:'90%',height:'75%'},
    		hAxis: {title: 'Revenue Streams',  titleTextStyle: {color: '#000A8B'}},
		vAxis: {title: 'Revenue',  titleTextStyle: {color: '#000A8B'}},
        };
        	
        var chart = new google.visualization.ColumnChart(document.getElementById('streams_revenue_stream'));
        chart.draw(view, options);
        	
        var selectHandler = function(e) {
        	window.location = data.getValue(chart.getSelection()[0]['row'], 1 );
       	}
       		
       	google.visualization.events.addListener(chart, 'select', selectHandler);
    }
</script>

<script type="text/javascript">
      	google.load("visualization", "1", {packages:["corechart"]});
      	google.setOnLoadCallback(drawChart);
      	function drawChart() {
        	var data = google.visualization.arrayToDataTable([
        		//['Month', 'Revenue'],
        		['Sub County', 'link', 'Revenue', { role: 'annotation' }],
        		<?php
				//$results = mysql_query("select distinct invoice_month, sum(invoice_amount)invoice_amount from juja_market_fees.statement_report group by invoice_month;");
				if($revenue == 'parking'){
					$result_tender = mysql_query("select distinct paid_date, sum(invoice_amount)invoice from ".$subcounty."_".$revenue.".statements where subcategory = '$cat' and EXTRACT(YEAR FROM paid_date) = '$filter_year' and EXTRACT(MONTH FROM paid_date) = '$filter_month' group by paid_date");
					while ($row = mysql_fetch_array($result_tender))
					{
						$paid_date = $row['paid_date'];
						$invoice = $row['invoice'];
						$paid_date_name = date("d M, Y", strtotime($paid_date));

						?>
							['<?php echo $paid_date_name ?>', 'revenues.php?report_start_date=<?php echo $start_date ?>&report_end_date=<?php echo $current_date ?>&cat=<?php echo $cat ?>&revenue=<?php echo $revenue ?>&subcounty=<?php echo $subcounty ?>&paid_date=<?php echo $paid_date ?>&mode=drill5',<?php echo $invoice ?>, '<?php echo $invoice ?>'],
						<?php
					}
				}
				else if($revenue == 'quarry'){
					$result_tender = mysql_query("select distinct paid_date, sum(invoice_amount)invoice from ".$subcounty."_".$revenue.".statements where subcategory = '$cat' and EXTRACT(YEAR FROM ".$subcounty."_".$revenue.".statements.paid_date) = '$filter_year' and EXTRACT(MONTH FROM juja_quarry.statements.paid_date) = '$filter_month' group by paid_date");
					while ($row = mysql_fetch_array($result_tender))
					{
						$paid_date = $row['paid_date'];
						$invoice = $row['invoice'];
						$paid_date_name = date("d M, Y", strtotime($paid_date));

						?>
							['<?php echo $paid_date_name ?>', 'revenues.php?report_start_date=<?php echo $start_date ?>&report_end_date=<?php echo $current_date ?>&cat=<?php echo $cat ?>&revenue=<?php echo $revenue ?>&subcounty=<?php echo $subcounty ?>&paid_date=<?php echo $paid_date ?>&mode=drill5',<?php echo $invoice ?>, '<?php echo $invoice ?>'],
						<?php
					}
				}
				else if($revenue == 'market'){
					$result_tender = mysql_query("select distinct paid_date, sum(invoice_amount)invoice from ".$subcounty."_".$revenue."_fees.statements where subcategory = '$category' and EXTRACT(YEAR FROM ".$subcounty."_".$revenue."_fees.statements.paid_date) = '$filter_year' and EXTRACT(MONTH FROM ".$subcounty."_".$revenue."_fees.statements.paid_date) = '$filter_month' group by paid_date");
					while ($row = mysql_fetch_array($result_tender))
					{
						$paid_date = $row['paid_date'];
						$invoice = $row['invoice'];
						$paid_date_name = date("d M, Y", strtotime($paid_date));

						?>
							['<?php echo $paid_date_name ?>', 'revenues.php?report_start_date=<?php echo $start_date ?>&report_end_date=<?php echo $current_date ?>&category=<?php echo subcategory ?>&revenue=<?php echo $revenue ?>&subcounty=<?php echo $subcounty ?>&paid_date=<?php echo $paid_date ?>&mode=drill5',<?php echo $invoice ?>, '<?php echo $invoice ?>'],
						<?php
					}
				}
				
			?>
        	]);

       	
       	var view = new google.visualization.DataView(data);
      	view.setColumns([0, 2]);

        var options = {
        	legend: { position: 'top' },
    		backgroundColor: '#F0F1F1',
    		chartArea:{left:100,top:10,width:'90%',height:'75%'},
    		hAxis: {title: 'Invoice Date',  titleTextStyle: {color: '#000A8B'}},
		vAxis: {title: 'Revenue',  titleTextStyle: {color: '#000A8B'}},
        };
        	
        var chart = new google.visualization.ColumnChart(document.getElementById('revenue_stream_daily'));
        chart.draw(view, options);
        	
        var selectHandler = function(e) {
        	window.location = data.getValue(chart.getSelection()[0]['row'], 1 );
       	}
       		
       	google.visualization.events.addListener(chart, 'select', selectHandler);
    }
</script>

<script type="text/javascript">
      	google.load("visualization", "1", {packages:["corechart"]});
      	google.setOnLoadCallback(drawChart);
      	function drawChart() {
        	var data = google.visualization.arrayToDataTable([
        		//['Month', 'Revenue'],
        		['Sub County', 'link', 'Revenue', { role: 'annotation' }],
        		<?php
				//$results = mysql_query("select distinct invoice_month, sum(invoice_amount)invoice_amount from juja_market_fees.statement_report group by invoice_month;");
				if($revenue == 'parking'){
					$result_tender = mysql_query("select distinct clerk, sum(invoice_amount)invoice from ".$subcounty."_".$revenue.".statements where subcategory = '$cat' and p	aid_date = '$paid_date' and EXTRACT(YEAR FROM paid_date) = '$filter_year' and EXTRACT(MONTH FROM paid_date) = '$filter_month' group by paid_date");
					while ($row = mysql_fetch_array($result_tender))
					{
						$clerk = $row['clerk'];
						$invoice = $row['invoice'];

						?>
							['<?php echo $clerk ?>',<?php echo $invoice ?>, '<?php echo $invoice ?>'],
						<?php
					}
				}
				else if($revenue == 'quarry'){
					$result_tender = mysql_query("select distinct clerk, sum(invoice_amount)invoice from ".$subcounty."_".$revenue.".statements where subcategory = '$cat' and paid_date = '$paid_date' and EXTRACT(YEAR FROM paid_date) = '$filter_year' and EXTRACT(MONTH FROM paid_date) = '$filter_month' group by paid_date");
					while ($row = mysql_fetch_array($result_tender))
					{
						$clerk = $row['clerk'];
						$invoice = $row['invoice'];

						?>
							['<?php echo $clerk ?>',<?php echo $invoice ?>, '<?php echo $invoice ?>'],
						<?php
					}
				}
				else if($revenue == 'market'){
					$result_tender = mysql_query("select distinct clerk, sum(invoice_amount)invoice from ".$subcounty."_".$revenue."_fees.statements where subcategory = '$category' and paid_date = '$paid_date' and EXTRACT(YEAR FROM paid_date) = '$filter_year' and EXTRACT(MONTH FROM paid_date) = '$filter_month' group by paid_date");
					while ($row = mysql_fetch_array($result_tender))
					{
						$clerk = $row['clerk'];
						$invoice = $row['invoice'];

						?>
							['<?php echo $clerk ?>',<?php echo $invoice ?>, '<?php echo $invoice ?>'],
						<?php
					}
				}
				
			?>
        	]);
	
       	
       	var options = {
          	legend: { position: 'top' },
    		backgroundColor: '#F0F1F1',
    		chartArea:{left:100,top:10,width:'90%',height:'75%'},
    		hAxis: {title: 'Clerk Name',  titleTextStyle: {color: '#000A8B'}},
		vAxis: {title: 'Revenue',  titleTextStyle: {color: '#000A8B'}},
        };

        var chart = new google.visualization.ColumnChart(document.getElementById('revenue_stream_clerk'));
        chart.draw(data, options);
    }
</script>
