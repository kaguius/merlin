<?php
	$userid = "";
	$adminstatus = 3;
	$property_manager_id = "";
	if (!empty($_SESSION)){
		$userid = $_SESSION["userid"] ;
		$adminstatus = $_SESSION["adminstatus"] ;
		$station = $_SESSION["station"] ;
	}
	include_once('includes/db_conn.php');
	$expense_month = date("m");
	$expense_year = date("Y");
	$filter_month = date("m");
	$filter_year = date("Y");
	$filter_day = date("d");
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
          		['Sub County', 'link', 'Revenue', { role: 'annotation' }],
          		<?php
          			if($subcounty == 'thika'){
					$result = mysql_query("select sum(".$subcounty."_market_fees.statements.invoice_amount)thika_market_fees, (select sum(".$subcounty."_parking.statements.invoice_amount)thika_parking from ".$subcounty."_parking.statements where ".$subcounty."_parking.statements.paid_date between '$filter_start_date' and '$filter_end_date' and ".$subcounty."_parking.statements.status = 'Paid')".$subcounty."_parking from ".$subcounty."_market_fees.statements where ".$subcounty."_market_fees.statements.paid_date between '$filter_start_date' and '$filter_end_date' and ".$subcounty."_market_fees.statements.status = 'Paid';");
					while ($row = mysql_fetch_array($result))
					{
						$thika_market_fees = $row['thika_market_fees'];
						$thika_parking = $row['thika_parking'];					
						?>
							['Thika Markets', 'financial_summary.php?report_start_date=<?php echo $filter_start_date ?>&report_end_date=<?php echo $filter_end_date ?>&subcounty=thika&revenue=market&mode=drill2', <?php echo $thika_market_fees?>, '<?php echo $thika_market_fees?>'],
							['Thika Parking', 'financial_summary.php?report_start_date=<?php echo $filter_start_date ?>&report_end_date=<?php echo $filter_end_date ?>&subcounty=thika&revenue=parking&mode=drill2', <?php echo $thika_parking?>, '<?php echo $thika_parking?>'],
						<?php
					}
				}
				else if($subcounty == 'ruiru'){
					$result = mysql_query("select sum(".$subcounty."_market_fees.statements.invoice_amount)ruiru_market_fees, (select sum(".$subcounty."_parking.statements.invoice_amount)ruiru_parking from ".$subcounty."_parking.statements where ".$subcounty."_parking.statements.paid_date between '$filter_start_date' and '$filter_end_date' and ".$subcounty."_parking.statements.status = 'Paid')".$subcounty."_parking from ".$subcounty."_market_fees.statements where ".$subcounty."_market_fees.statements.paid_date between '$filter_start_date' and '$filter_end_date' and ".$subcounty."_market_fees.statements.status = 'Paid';");
					while ($row = mysql_fetch_array($result))
					{
						$ruiru_market_fees = $row['ruiru_market_fees'];
						$ruiru_parking = $row['ruiru_parking'];					
						?>
							['Ruiru Markets', 'financial_summary.php?report_start_date=<?php echo $filter_start_date ?>&report_end_date=<?php echo $filter_end_date ?>&subcounty=ruiru&revenue=market&mode=drill2', <?php echo $ruiru_market_fees?>, '<?php echo $ruiru_market_fees?>'],
							['Ruiru Parking', 'financial_summary.php?report_start_date=<?php echo $filter_start_date ?>&report_end_date=<?php echo $filter_end_date ?>&subcounty=ruiru&revenue=parking&mode=drill2', <?php echo $ruiru_parking?>, '<?php echo $ruiru_parking?>'],
						<?php
					}
				}
				else if($subcounty == 'juja'){
					$result = mysql_query("select sum(".$subcounty."_market_fees.statements.invoice_amount)juja_market_fees, (select sum(".$subcounty."_quarry.statements.invoice_amount)juja_quarry from ".$subcounty."_quarry.statements where ".$subcounty."_quarry.statements.paid_date between '$filter_start_date' and '$filter_end_date' and ".$subcounty."_quarry.statements.status = 'Paid')".$subcounty."_quarry from ".$subcounty."_market_fees.statements where ".$subcounty."_market_fees.statements.paid_date between '$filter_start_date' and '$filter_end_date' and ".$subcounty."_market_fees.statements.status = 'Paid';");
					while ($row = mysql_fetch_array($result))
					{
						$juja_market_fees = $row['juja_market_fees'];
						$juja_quarry = $row['juja_quarry'];					
						?>
							['Juja Markets', 'financial_summary.php?report_start_date=<?php echo $filter_start_date ?>&report_end_date=<?php echo $filter_end_date ?>&subcounty=juja&revenue=market&mode=drill2', <?php echo $juja_market_fees?>, '<?php echo $juja_market_fees?>'],
							['Juja Quarry', 'financial_summary.php?report_start_date=<?php echo $filter_start_date ?>&report_end_date=<?php echo $filter_end_date ?>&subcounty=juja&revenue=quarry&mode=drill2', <?php echo $juja_quarry?>, '<?php echo $juja_quarry?>'],
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
        	
        	var chart = new google.visualization.ColumnChart(document.getElementById('subcounty_revenue'));
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
          		['Sub County', 'link', 'Revenue', { role: 'annotation' }],
          		<?php
				$result_tender = mysql_query("select sum(juja_quarry.statements.invoice_amount)juja_quarry, (select sum(juja_market_fees.statements.invoice_amount)juja_market_fees from juja_market_fees.statements where juja_market_fees.statements.paid_date between '$filter_start_date' and '$filter_end_date' and juja_market_fees.statements.status = 'Paid')juja_market_fees, (select sum(ruiru_parking.statements.invoice_amount)ruiru_parking from ruiru_parking.statements where ruiru_parking.statements.paid_date between '$filter_start_date' and '$filter_end_date' and ruiru_parking.statements.status = 'Paid')ruiru_parking, (select sum(kiambu_parking.statements.invoice_amount)kiambu_parking from kiambu_parking.statements where kiambu_parking.statements.paid_date between '$filter_start_date' and '$filter_end_date' and kiambu_parking.statements.status = 'Paid')kiambu_parking, (select sum(kiambu_market_fees.statements.invoice_amount)kiambu_market_fees from kiambu_market_fees.statements where kiambu_market_fees.statements.paid_date between '$filter_start_date' and '$filter_end_date' and kiambu_market_fees.statements.status = 'Paid')kiambu_market_fees, (select sum(ruiru_market_fees.statements.invoice_amount)ruiru_market_fees from ruiru_market_fees.statements where ruiru_market_fees.statements.paid_date between '$filter_start_date' and '$filter_end_date' and ruiru_market_fees.statements.status = 'Paid')ruiru_market_fees, (select sum(thika_parking.statements.invoice_amount)thika_parking from thika_parking.statements where thika_parking.statements.paid_date between '$filter_start_date' and '$filter_end_date' and thika_parking.statements.status = 'Paid')thika_parking, (select sum(thika_market_fees.statements.invoice_amount)thika_market_fees from thika_market_fees.statements where thika_market_fees.statements.paid_date between '$filter_start_date' and '$filter_end_date' and thika_market_fees.statements.status = 'Paid')thika_market_fees from juja_quarry.statements where juja_quarry.statements.paid_date between '$filter_start_date' and '$filter_end_date' and status = 'Paid';");
				while ($row = mysql_fetch_array($result_tender))
				{
					$juja_quarry = $row['juja_quarry'];
					$juja_market_fees = $row['juja_market_fees'];
					//$juja_parking = $row['juja_parking'];
					$juja = $juja_quarry + $juja_market_fees + $juja_parking;
					$kiambu_market_fees = $row['kiambu_market_fees'];
					$kiambu_parking = $row['kiambu_parking'];
					//$kiambu = $kiambu_market_fees + $kiambu_parking;
					$ruiru_market_fees = $row['ruiru_market_fees'];
					$ruiru_parking = $row['ruiru_parking'];
					$ruiru = $ruiru_market_fees + $ruiru_parking;
					$thika_market_fees = $row['thika_market_fees'];
					$thika_parking = $row['thika_parking'];
					$thika = $thika_market_fees + $thika_parking;
					
					?>
						['Juja', 'financial_summary.php?report_start_date=<?php echo $filter_start_date ?>&report_end_date=<?php echo $filter_end_date ?>&subcounty=juja&mode=drill1', <?php echo $juja?>, '<?php echo $juja?>'],
						['Ruiru', 'financial_summary.php?report_start_date=<?php echo $filter_start_date ?>&report_end_date=<?php echo $filter_end_date ?>&subcounty=ruiru&mode=drill1', <?php echo $ruiru?>, '<?php echo $ruiru?>'],
						['Thika', 'financial_summary.php?report_start_date=<?php echo $filter_start_date ?>&report_end_date=<?php echo $filter_end_date ?>&subcounty=thika&mode=drill1', <?php echo $thika?>, '<?php echo $thika?>'],
					<?php
				}
			?>
        	]);
        	
        	var view = new google.visualization.DataView(data);
      		view.setColumns([0, 2]);

        	var options = {
          		legend: { position: 'top' },
    			backgroundColor: '#F0F1F1',
    			chartArea:{left:100,top:10,width:'90%',height:'75%'},
    			hAxis: {title: 'Sub County',  titleTextStyle: {color: '#000A8B'}},
			vAxis: {title: 'Revenue',  titleTextStyle: {color: '#000A8B'}},
        	};
        	
        	var chart = new google.visualization.ColumnChart(document.getElementById('summary'));
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
          		['Sub County', 'link', 'Revenue', { role: 'annotation' }],
          		<?php
          			if($revenue == 'market'){
					$result = mysql_query("select distinct subcategory, sum(invoice_amount)market from ".$subcounty."_".$revenue."_fees.statements where paid_date between '$filter_start_date' and '$filter_end_date' and status = 'paid' group by subcategory");
					while ($row = mysql_fetch_array($result))
					{
						$subcategory = $row['subcategory'];
						$market = $row['market'];					
						?>
							['<?php echo $subcategory ?>', 'financial_summary.php?report_start_date=<?php echo $filter_start_date ?>&report_end_date=<?php echo $filter_end_date ?>&subcounty=<?php echo $subcounty ?>&revenue=<?php echo $revenue ?>&category=<?php echo $subcategory ?>&mode=drill3&drillcat=byproduct', <?php echo $market ?>, '<?php echo $market?>'],
						<?php
					}
				}
				else if($revenue == 'parking'){
					$result = mysql_query("select distinct subcategories.name, subcategories.id, sum(invoice_amount)parking from ".$subcounty."_".$revenue.".statements inner join ".$subcounty."_".$revenue.".subcategories on subcategories.id = statements.subcategory where paid_date between '$filter_start_date' and '$filter_end_date' and status = 'paid' group by subcategories.name");
					while ($row = mysql_fetch_array($result))
					{
						$subcategory = $row['name'];
						$cat_id = $row['id'];
						$parking = $row['parking'];					
						?>
							['<?php echo $subcategory ?>', 'financial_summary.php?report_start_date=<?php echo $filter_start_date ?>&report_end_date=<?php echo $filter_end_date ?>&subcounty=<?php echo $subcounty ?>&revenue=<?php echo $revenue ?>&category=<?php echo $subcategory ?>&mode=drill3&cat=<?php echo $cat_id ?>&drillcat=byproduct', <?php echo $parking ?>, '<?php echo $parking?>'],
						<?php
					}
				}
				else if($revenue == 'quarry'){
					$result = mysql_query("select distinct subcategories.name, subcategories.id, sum(invoice_amount)parking from ".$subcounty."_".$revenue.".statements inner join ".$subcounty."_".$revenue.".subcategories on subcategories.id = statements.subcategory where paid_date between '$filter_start_date' and '$filter_end_date' and status = 'paid' group by subcategories.name");
					while ($row = mysql_fetch_array($result))
					{
						$subcategory = $row['name'];
						$cat_id = $row['id'];
						$parking = $row['parking'];					
						?>
							['<?php echo $subcategory ?>', 'financial_summary.php?report_start_date=<?php echo $filter_start_date ?>&report_end_date=<?php echo $filter_end_date ?>&subcounty=<?php echo $subcounty ?>&revenue=<?php echo $revenue ?>&category=<?php echo $subcategory ?>&mode=drill3&cat=<?php echo $cat_id ?>&drillcat=byproduct', <?php echo $parking ?>, '<?php echo $parking?>'],
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
        	
        	var chart = new google.visualization.ColumnChart(document.getElementById('revenue_stream'));
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
          		['Sub County', 'link', 'Revenue', { role: 'annotation' }],
          		<?php
          			if($revenue == 'market'){
					$result = mysql_query("select distinct product, sum(invoice_amount)market from ".$subcounty."_".$revenue."_fees.statements where paid_date between '$filter_start_date' and '$filter_end_date' and status = 'Paid' and subcategory = '$category' group by product");
					while ($row = mysql_fetch_array($result))
					{
						$product = $row['product'];
						$market = $row['market'];					
						?>
							['<?php echo $product ?>', 'financial_summary.php?report_start_date=<?php echo $filter_start_date ?>&report_end_date=<?php echo $filter_end_date ?>&subcounty=<?php echo $subcounty ?>&revenue=<?php echo $revenue ?>&product=<?php echo $product ?>&mode=drill4&drillcat=bydatenew&category=<?php echo $category ?>', <?php echo $market ?>, '<?php echo $market?>'],
						<?php
					}
				}
				else if($revenue == 'parking'){
					$result = mysql_query("select distinct product, sum(invoice_amount)parking from ".$subcounty."_".$revenue.".statements where paid_date between '$filter_start_date' and '$filter_end_date' and status = 'Paid' and subcategory = '$cat' group by product");
					while ($row = mysql_fetch_array($result))
					{
						$product = $row['product'];
						$parking = $row['parking'];					
						?>
							['<?php echo $product ?>', 'financial_summary.php?report_start_date=<?php echo $filter_start_date ?>&report_end_date=<?php echo $filter_end_date ?>&subcounty=<?php echo $subcounty ?>&revenue=<?php echo $revenue ?>&product=<?php echo $product ?>&mode=drill4&drillcat=bydatenew&cat=<?php echo $cat ?>', <?php echo $parking ?>, '<?php echo $parking?>'],
						<?php
					}
				}
				else if($revenue == 'quarry'){
					$result = mysql_query("select distinct product, sum(invoice_amount)quarry from ".$subcounty."_".$revenue.".statements where paid_date between '$filter_start_date' and '$filter_end_date' and status = 'Paid' and subcategory = '$cat' group by product");
					while ($row = mysql_fetch_array($result))
					{
						$product = $row['product'];
						$quarry = $row['quarry'];					
						?>
							['<?php echo $product ?>', 'financial_summary.php?report_start_date=<?php echo $filter_start_date ?>&report_end_date=<?php echo $filter_end_date ?>&subcounty=<?php echo $subcounty ?>&revenue=<?php echo $revenue ?>&product=<?php echo $product ?>&mode=drill4&drillcat=bydatenew&cat=<?php echo $cat ?>', <?php echo $quarry ?>, '<?php echo $quarry?>'],
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
        	
        	var chart = new google.visualization.ColumnChart(document.getElementById('product'));
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
          		['Sub County', 'link', 'Revenue', { role: 'annotation' }],
          		<?php
          			if($revenue == 'market'){
					$result = mysql_query("select distinct clerk, sum(invoice_amount)invoice from ".$subcounty."_".$revenue."_fees.statements where subcategory = '$category' and paid_date between '$filter_start_date' and '$filter_end_date' and status = 'Paid' group by clerk");
					while ($row = mysql_fetch_array($result))
					{
						$clerk = $row['clerk'];
						$invoice = $row['invoice'];					
						?>
							['<?php echo $clerk ?>', 'financial_summary.php?report_start_date=<?php echo $filter_start_date ?>&report_end_date=<?php echo $filter_end_date ?>&subcounty=<?php echo $subcounty ?>&revenue=<?php echo $revenue ?>&product=<?php echo $product ?>&category=<?php echo $category ?>&mode=drill4&drillcat=bydatetwo&filter=<?php echo $clerk ?>', <?php echo $invoice ?>, '<?php echo $invoice?>'],
						<?php
					}
				}
				else if($revenue == 'parking'){
					$result = mysql_query("select distinct clerk, sum(invoice_amount)invoice from ".$subcounty."_".$revenue.".statements where subcategory = '$cat' and paid_date between '$filter_start_date' and '$filter_end_date' and status = 'Paid' group by clerk");
					while ($row = mysql_fetch_array($result))
					{
						$clerk = $row['clerk'];
						$invoice = $row['invoice'];					
						?>
							['<?php echo $clerk ?>', 'financial_summary.php?report_start_date=<?php echo $filter_start_date ?>&report_end_date=<?php echo $filter_end_date ?>&subcounty=<?php echo $subcounty ?>&revenue=<?php echo $revenue ?>&product=<?php echo $product ?>&cat=<?php echo $cat ?>&mode=drill4&drillcat=bydatetwo&filter=<?php echo $clerk ?>', <?php echo $invoice ?>, '<?php echo $invoice?>'],
						<?php
					}
				}
				else if($revenue == 'quarry'){
					$result = mysql_query("select distinct clerk, sum(invoice_amount)invoice from ".$subcounty."_".$revenue.".statements where subcategory = '$cat' and paid_date between '$filter_start_date' and '$filter_end_date' and status = 'Paid' group by clerk");
					while ($row = mysql_fetch_array($result))
					{
						$clerk = $row['clerk'];
						$invoice = $row['invoice'];					
						?>
							['<?php echo $clerk ?>', 'financial_summary.php?report_start_date=<?php echo $filter_start_date ?>&report_end_date=<?php echo $filter_end_date ?>&subcounty=<?php echo $subcounty ?>&revenue=<?php echo $revenue ?>&product=<?php echo $product ?>&cat=<?php echo $cat ?>&mode=drill4&drillcat=bydatetwo&filter=<?php echo $clerk ?>', <?php echo $invoice ?>, '<?php echo $invoice?>'],
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
    			hAxis: {title: 'Clerk Name',  titleTextStyle: {color: '#000A8B'}},
			vAxis: {title: 'Revenue',  titleTextStyle: {color: '#000A8B'}},
        	};
        	
        	var chart = new google.visualization.ColumnChart(document.getElementById('product_clerk'));
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
          		['Sub County', 'link', 'Revenue', { role: 'annotation' }],
          		<?php
          			if($revenue == 'market'){
					$result = mysql_query("select distinct paid_date, sum(invoice_amount)invoice from ".$subcounty."_".$revenue."_fees.statements where subcategory = '$category' and paid_date between '$filter_start_date' and '$filter_end_date' and status = 'Paid' group by paid_date");
					while ($row = mysql_fetch_array($result))
					{
						$paid_date = $row['paid_date'];
						$invoice = $row['invoice'];	
						$paid_date_name = date("d M, Y", strtotime($paid_date));					
						?>
							['<?php echo $paid_date_name ?>', 'financial_summary.php?report_start_date=<?php echo $filter_start_date ?>&report_end_date=<?php echo $filter_end_date ?>&subcounty=<?php echo $subcounty ?>&revenue=<?php echo $revenue ?>&paid_date=<?php echo $paid_date ?>&mode=drill4&drillcat=bydate&category=<?php echo $category ?>', <?php echo $invoice ?>, '<?php echo $invoice?>'],
						<?php
					}
				}
				else if($revenue == 'parking'){
					$result = mysql_query("select distinct paid_date, sum(invoice_amount)invoice from ".$subcounty."_".$revenue.".statements where subcategory = '$cat' and paid_date between '$filter_start_date' and '$filter_end_date' and status = 'Paid' group by paid_date");
					while ($row = mysql_fetch_array($result))
					{
						$paid_date = $row['paid_date'];
						$invoice = $row['invoice'];	
						$paid_date_name = date("d M, Y", strtotime($paid_date));					
						?>
							['<?php echo $paid_date_name ?>', 'financial_summary.php?report_start_date=<?php echo $filter_start_date ?>&report_end_date=<?php echo $filter_end_date ?>&subcounty=<?php echo $subcounty ?>&revenue=<?php echo $revenue ?>&paid_date=<?php echo $paid_date ?>&mode=drill4&drillcat=bydate&cat=<?php echo $cat ?>', <?php echo $invoice ?>, '<?php echo $invoice?>'],
						<?php
					}
				}
				else if($revenue == 'quarry'){
					$result = mysql_query("select distinct paid_date, sum(invoice_amount)invoice from ".$subcounty."_".$revenue.".statements where subcategory = '$cat' and paid_date between '$filter_start_date' and '$filter_end_date' and status = 'Paid' group by paid_date");
					while ($row = mysql_fetch_array($result))
					{
						$paid_date = $row['paid_date'];
						$invoice = $row['invoice'];
						$paid_date_name = date("d M, Y", strtotime($paid_date));	
						?>
							['<?php echo $paid_date_name ?>', 'financial_summary.php?report_start_date=<?php echo $filter_start_date ?>&report_end_date=<?php echo $filter_end_date ?>&subcounty=<?php echo $subcounty ?>&revenue=<?php echo $revenue ?>&paid_date=<?php echo $paid_date ?>&mode=drill4&drillcat=bydate&cat=<?php echo $cat ?>', <?php echo $invoice ?>, '<?php echo $invoice?>'],
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
        	
        	var chart = new google.visualization.ColumnChart(document.getElementById('product_date'));
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
          		['Sub County', 'link', 'Revenue', { role: 'annotation' }],
          		<?php
          			if($revenue == 'market'){
					$result = mysql_query("select distinct paid_date, sum(invoice_amount)invoice from ".$subcounty."_".$revenue."_fees.statements where subcategory = '$category' and paid_date between '$filter_start_date' and '$filter_end_date' and status = 'Paid' and clerk = '$filter' group by paid_date");
					while ($row = mysql_fetch_array($result))
					{
						$paid_date = $row['paid_date'];
						$invoice = $row['invoice'];
						$paid_date_name = date("d M, Y", strtotime($paid_date));					
						?>
							['<?php echo $paid_date_name ?>', 'financial_summary.php?report_start_date=<?php echo $filter_start_date ?>&report_end_date=<?php echo $filter_end_date ?>&subcounty=<?php echo $subcounty ?>&revenue=<?php echo $revenue ?>&product=<?php echo $product ?>&mode=drill5&drillcat=byproduct&filter=<?php echo $paid_date ?>&category=<?php echo $category ?>&clerk=<?php echo $filter ?>', <?php echo $invoice ?>, '<?php echo $invoice?>'],
						<?php
					}
				}
				else if($revenue == 'parking'){
					$result = mysql_query("select distinct paid_date, sum(invoice_amount)invoice from ".$subcounty."_".$revenue.".statements where subcategory = '$cat' and paid_date between '$filter_start_date' and '$filter_end_date' and status = 'Paid' and clerk = '$filter' group by paid_date");
					while ($row = mysql_fetch_array($result))
					{
						$paid_date = $row['paid_date'];
						$invoice = $row['invoice'];
						$paid_date_name = date("d M, Y", strtotime($paid_date));					
						?>
							['<?php echo $paid_date_name ?>', 'financial_summary.php?report_start_date=<?php echo $filter_start_date ?>&report_end_date=<?php echo $filter_end_date ?>&subcounty=<?php echo $subcounty ?>&revenue=<?php echo $revenue ?>&product=<?php echo $product ?>&mode=drill5&drillcat=byproduct&filter=<?php echo $paid_date ?>&cat=<?php echo $cat ?>&clerk=<?php echo $filter ?>', <?php echo $invoice ?>, '<?php echo $invoice?>'],
						<?php
					}
				}
				else if($revenue == 'quarry'){
					$result = mysql_query("select distinct paid_date, sum(invoice_amount)invoice from ".$subcounty."_".$revenue.".statements where subcategory = '$cat' and paid_date between '$filter_start_date' and '$filter_end_date' and status = 'Paid' and clerk = '$filter' group by paid_date");
					while ($row = mysql_fetch_array($result))
					{
						$paid_date = $row['paid_date'];
						$invoice = $row['invoice'];
						$paid_date_name = date("d M, Y", strtotime($paid_date));					
						?>
							['<?php echo $paid_date_name ?>', 'financial_summary.php?report_start_date=<?php echo $filter_start_date ?>&report_end_date=<?php echo $filter_end_date ?>&subcounty=<?php echo $subcounty ?>&revenue=<?php echo $revenue ?>&product=<?php echo $product ?>&mode=drill5&drillcat=byproduct&filter=<?php echo $paid_date ?>&cat=<?php echo $cat ?>&clerk=<?php echo $filter ?>', <?php echo $invoice ?>, '<?php echo $invoice?>'],
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
    			hAxis: {title: 'Date',  titleTextStyle: {color: '#000A8B'}},
			vAxis: {title: 'Revenue',  titleTextStyle: {color: '#000A8B'}},
        	};
        	
        	var chart = new google.visualization.ColumnChart(document.getElementById('clerk_daily'));
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
          		['Sub County', 'link', 'Revenue', { role: 'annotation' }],
          		<?php
          			if($revenue == 'market'){
					$result = mysql_query("select distinct clerk, sum(invoice_amount)invoice from ".$subcounty."_".$revenue."_fees.statements where subcategory = '$category' and paid_date = '$date_paid' and status = 'Paid' group by clerk");
					while ($row = mysql_fetch_array($result))
					{
						$clerk = $row['clerk'];
						$invoice = $row['invoice'];				
						?>
							['<?php echo $clerk ?>', 'financial_summary.php?report_start_date=<?php echo $filter_start_date ?>&report_end_date=<?php echo $filter_end_date ?>&subcounty=<?php echo $subcounty ?>&revenue=<?php echo $revenue ?>&product=<?php echo $product ?>&mode=drill4&drillcat=bydatetwo&filter=<?php echo $clerk ?>', <?php echo $invoice ?>, '<?php echo $invoice?>'],
						<?php
					}
				}
				else if($revenue == 'parking'){
					$result = mysql_query("select distinct clerk, sum(invoice_amount)invoice from ".$subcounty."_".$revenue.".statements where subcategory = '$cat' and paid_date = '$date_paid' and status = 'Paid' group by clerk");
					while ($row = mysql_fetch_array($result))
					{
						$clerk = $row['clerk'];
						$invoice = $row['invoice'];				
						?>
							['<?php echo $clerk ?>', 'financial_summary.php?report_start_date=<?php echo $filter_start_date ?>&report_end_date=<?php echo $filter_end_date ?>&subcounty=<?php echo $subcounty ?>&revenue=<?php echo $revenue ?>&product=<?php echo $product ?>&mode=drill4&drillcat=bydatetwo&filter=<?php echo $clerk ?>', <?php echo $invoice ?>, '<?php echo $invoice?>'],
						<?php
					}
				}
				else if($revenue == 'quarry'){
					$result = mysql_query("select distinct clerk, sum(invoice_amount)invoice from ".$subcounty."_".$revenue.".statements where subcategory = '$cat' and paid_date = '$date_paid' and status = 'Paid' group by clerk");
					while ($row = mysql_fetch_array($result))
					{
						$clerk = $row['clerk'];
						$invoice = $row['invoice'];				
						?>
							['<?php echo $clerk ?>', 'financial_summary.php?report_start_date=<?php echo $filter_start_date ?>&report_end_date=<?php echo $filter_end_date ?>&subcounty=<?php echo $subcounty ?>&revenue=<?php echo $revenue ?>&product=<?php echo $product ?>&mode=drill4&drillcat=bydatetwo&filter=<?php echo $clerk ?>', <?php echo $invoice ?>, '<?php echo $invoice?>'],
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
    			hAxis: {title: 'Date',  titleTextStyle: {color: '#000A8B'}},
			vAxis: {title: 'Revenue',  titleTextStyle: {color: '#000A8B'}},
        	};
        	
        	var chart = new google.visualization.ColumnChart(document.getElementById('product_daily'));
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
          		['Sub County', 'link', 'Revenue', { role: 'annotation' }],
          		<?php
          			if($revenue == 'market'){
					$result = mysql_query("select distinct clerk, sum(invoice_amount)invoice from ".$subcounty."_".$revenue."_fees.statements where subcategory = '$category' and paid_date = '$date_paid' and status = 'Paid' group by clerk");
					while ($row = mysql_fetch_array($result))
					{
						$clerk = $row['clerk'];
						$invoice = $row['invoice'];				
						?>
							['<?php echo $clerk ?>', 'financial_summary.php?paid_date=<?php echo $date_paid ?>&subcounty=<?php echo $subcounty ?>&revenue=<?php echo $revenue ?>&clerk=<?php echo $clerk ?>&report_start_date=<?php echo $filter_start_date ?>&report_end_date=<?php echo $filter_end_date ?>&category=<?php echo $category ?>&mode=drill5&drillcat=byproducttwo', <?php echo $invoice ?>, '<?php echo $invoice?>'],
						<?php
					}
				}
				else if($revenue == 'parking'){
					$result = mysql_query("select distinct clerk, sum(invoice_amount)invoice from ".$subcounty."_".$revenue.".statements where subcategory = '$cat' and paid_date = '$date_paid' and status = 'Paid' group by clerk");
					while ($row = mysql_fetch_array($result))
					{
						$clerk = $row['clerk'];
						$invoice = $row['invoice'];				
						?>
							['<?php echo $clerk ?>', 'financial_summary.php?paid_date=<?php echo $date_paid ?>&subcounty=<?php echo $subcounty ?>&revenue=<?php echo $revenue ?>&clerk=<?php echo $clerk ?>&report_start_date=<?php echo $filter_start_date ?>&report_end_date=<?php echo $filter_end_date ?>&cat=<?php echo $cat ?>&mode=drill5&drillcat=byproducttwo', <?php echo $invoice ?>, '<?php echo $invoice?>'],
						<?php
					}
				}
				else if($revenue == 'quarry'){
					$result = mysql_query("select distinct clerk, sum(invoice_amount)invoice from ".$subcounty."_".$revenue.".statements where subcategory = '$cat' and paid_date = '$date_paid' and status = 'Paid' group by clerk");
					while ($row = mysql_fetch_array($result))
					{
						$clerk = $row['clerk'];
						$invoice = $row['invoice'];				
						?>
							['<?php echo $clerk ?>', 'financial_summary.php?paid_date=<?php echo $date_paid ?>&subcounty=<?php echo $subcounty ?>&revenue=<?php echo $revenue ?>&clerk=<?php echo $clerk ?>&report_start_date=<?php echo $filter_start_date ?>&report_end_date=<?php echo $filter_end_date ?>&cat=<?php echo $cat ?>&mode=drill5&drillcat=byproducttwo', <?php echo $invoice ?>, '<?php echo $invoice?>'],
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
    			hAxis: {title: 'Date',  titleTextStyle: {color: '#000A8B'}},
			vAxis: {title: 'Revenue',  titleTextStyle: {color: '#000A8B'}},
        	};
        	
        	var chart = new google.visualization.ColumnChart(document.getElementById('dates_daily'));
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
          		['Sub County', 'link', 'Revenue', { role: 'annotation' }],
          		<?php
          			if($revenue == 'market'){
					$result = mysql_query("select distinct paid_date, sum(invoice_amount)invoice from ".$subcounty."_".$revenue."_fees.statements where subcategory = '$category' and paid_date between '$filter_start_date' and '$filter_end_date' and product = '$product' and status = 'Paid' group by paid_date");
					while ($row = mysql_fetch_array($result))
					{
						$paid_date = $row['paid_date'];
						$invoice = $row['invoice'];
						$paid_date_name = date("d M, Y", strtotime($paid_date));					
						?>
							['<?php echo $paid_date_name ?>', 'financial_summary.php?paid_date=<?php echo $date_paid ?>&subcounty=<?php echo $subcounty ?>&revenue=<?php echo $revenue ?>&paid_date=<?php echo $paid_date ?>&report_start_date=<?php echo $filter_start_date ?>&report_end_date=<?php echo $filter_end_date ?>&category=<?php echo $category ?>&product=<?php echo $product ?>&mode=drill5&drillcat=byclerk', <?php echo $invoice ?>, '<?php echo $invoice?>'],
						<?php
					}
				}
				else if($revenue == 'parking'){
					$result = mysql_query("select distinct paid_date, sum(invoice_amount)invoice from ".$subcounty."_".$revenue.".statements where subcategory = '$cat' and paid_date between '$filter_start_date' and '$filter_end_date' and product = '$product' and status = 'Paid' group by paid_date");
					while ($row = mysql_fetch_array($result))
					{
						$paid_date = $row['paid_date'];
						$invoice = $row['invoice'];
						$paid_date_name = date("d M, Y", strtotime($paid_date));					
						?>
							['<?php echo $paid_date_name ?>', 'financial_summary.php?paid_date=<?php echo $date_paid ?>&subcounty=<?php echo $subcounty ?>&revenue=<?php echo $revenue ?>&paid_date=<?php echo $paid_date ?>&report_start_date=<?php echo $filter_start_date ?>&report_end_date=<?php echo $filter_end_date ?>&cat=<?php echo $cat ?>&product=<?php echo $product ?>&mode=drill5&drillcat=byclerk', <?php echo $invoice ?>, '<?php echo $invoice?>'],
						<?php
					}
				}
				else if($revenue == 'quarry'){
					$result = mysql_query("select distinct paid_date, sum(invoice_amount)invoice from ".$subcounty."_".$revenue.".statements where subcategory = '$cat' and paid_date between '$filter_start_date' and '$filter_end_date' and product = '$product' and status = 'Paid' group by paid_date");
					while ($row = mysql_fetch_array($result))
					{
						$paid_date = $row['paid_date'];
						$invoice = $row['invoice'];
						$paid_date_name = date("d M, Y", strtotime($paid_date));					
						?>
							['<?php echo $paid_date_name ?>', 'financial_summary.php?paid_date=<?php echo $date_paid ?>&subcounty=<?php echo $subcounty ?>&revenue=<?php echo $revenue ?>&paid_date=<?php echo $paid_date ?>&report_start_date=<?php echo $filter_start_date ?>&report_end_date=<?php echo $filter_end_date ?>&cat=<?php echo $cat ?>&product=<?php echo $product ?>&mode=drill5&drillcat=byclerk', <?php echo $invoice ?>, '<?php echo $invoice?>'],
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
    			hAxis: {title: 'Date',  titleTextStyle: {color: '#000A8B'}},
			vAxis: {title: 'Revenue',  titleTextStyle: {color: '#000A8B'}},
        	};
        	
        	var chart = new google.visualization.ColumnChart(document.getElementById('bydatenew'));
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
          		['Sub County', 'Revenue', { role: 'annotation' }],
          		<?php
          			if($revenue == 'market'){
					$result = mysql_query("select distinct product, sum(invoice_amount)invoice from ".$subcounty."_".$revenue."_fees.statements where paid_date = '$filter' and clerk = '$clerk' and status = 'Paid' group by product");
					while ($row = mysql_fetch_array($result))
					{
						$product = $row['product'];
						$invoice = $row['invoice'];				
						?>
							['<?php echo $product ?>', <?php echo $invoice ?>, '<?php echo $invoice?>'],
						<?php
					}
				}
				else if($revenue == 'parking'){
					$result = mysql_query("select distinct product, sum(invoice_amount)invoice from ".$subcounty."_".$revenue.".statements where paid_date = '$filter' and clerk = '$clerk' and status = 'Paid' group by product");
					while ($row = mysql_fetch_array($result))
					{
						$product = $row['product'];
						$invoice = $row['invoice'];				
						?>
							['<?php echo $product ?>', <?php echo $invoice ?>, '<?php echo $invoice?>'],
						<?php
					}
				}
				else if($revenue == 'quarry'){
					$result = mysql_query("select distinct product, sum(invoice_amount)invoice from ".$subcounty."_".$revenue.".statements where paid_date = '$filter' and clerk = '$clerk' and status = 'Paid' group by product");
					while ($row = mysql_fetch_array($result))
					{
						$product = $row['product'];
						$invoice = $row['invoice'];				
						?>
							['<?php echo $product ?>', <?php echo $invoice ?>, '<?php echo $invoice?>'],
						<?php
					}
				}
				
			?>
        	]);
       		
       		var options = {
    			legend: { position: 'top' },
    			backgroundColor: '#F0F1F1',
    			chartArea:{left:100,top:10,width:'90%',height:'75%'},
    			hAxis: {title: 'Product',  titleTextStyle: {color: '#000A8B'}},
			vAxis: {title: 'Revenue',  titleTextStyle: {color: '#000A8B'}},
        	};

        	var chart = new google.visualization.ColumnChart(document.getElementById('byproduct_last'));
       	 	chart.draw(data, options);
      }
</script>

<script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        	var data = google.visualization.arrayToDataTable([
          		['Sub County', 'Revenue', { role: 'annotation' }],
          		<?php
          			if($revenue == 'market'){
					$result = mysql_query("select distinct product, sum(invoice_amount)invoice from ".$subcounty."_".$revenue."_fees.statements where subcategory = '$category' and paid_date = '$date_paid' and clerk = '$clerk' and status = 'Paid' group by product");
					while ($row = mysql_fetch_array($result))
					{
						$product = $row['product'];
						$invoice = $row['invoice'];				
						?>
							['<?php echo $product ?>', <?php echo $invoice ?>, '<?php echo $invoice?>'],
						<?php
					}
				}
				else if($revenue == 'parking'){
					$result = mysql_query("select distinct product, sum(invoice_amount)invoice from ".$subcounty."_".$revenue.".statements where subcategory = '$cat' and paid_date = '$date_paid' and clerk = '$clerk' and status = 'Paid' group by product");
					while ($row = mysql_fetch_array($result))
					{
						$product = $row['product'];
						$invoice = $row['invoice'];				
						?>
							['<?php echo $product ?>', <?php echo $invoice ?>, '<?php echo $invoice?>'],
						<?php
					}
				}
				else if($revenue == 'quarry'){
					$result = mysql_query("select distinct product, sum(invoice_amount)invoice from ".$subcounty."_".$revenue.".statements where subcategory = '$cat' and paid_date = '$date_paid' and clerk = '$clerk' and status = 'Paid' group by product");
					while ($row = mysql_fetch_array($result))
					{
						$product = $row['product'];
						$invoice = $row['invoice'];				
						?>
							['<?php echo $product ?>', <?php echo $invoice ?>, '<?php echo $invoice?>'],
						<?php
					}
				}
				
			?>
        	]);
       		
       		var options = {
    			legend: { position: 'top' },
    			backgroundColor: '#F0F1F1',
    			chartArea:{left:100,top:10,width:'90%',height:'75%'},
    			hAxis: {title: 'Product',  titleTextStyle: {color: '#000A8B'}},
			vAxis: {title: 'Revenue',  titleTextStyle: {color: '#000A8B'}},
        	};

        	var chart = new google.visualization.ColumnChart(document.getElementById('bydate_last'));
       	 	chart.draw(data, options);
      }
</script>

<script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        	var data = google.visualization.arrayToDataTable([
          		['Sub County', 'link', 'Revenue', { role: 'annotation' }],
          		<?php
          			if($revenue == 'market'){
					$result = mysql_query("select distinct paid_date, sum(invoice_amount)market from ".$subcounty."_".$revenue."_fees.statements where paid_date between '$filter_start_date' and '$filter_end_date' and status = 'Paid' and product = '$product' group by paid_date order by paid_date asc");
					while ($row = mysql_fetch_array($result))
					{
						$paid_date = $row['paid_date'];
						$market = $row['market'];
						$paid_date_name = date("d M, Y", strtotime($paid_date));					
						?>
							['<?php echo $paid_date_name ?>', 'financial_summary.php?report_start_date=<?php echo $filter_start_date ?>&report_end_date=<?php echo $filter_end_date ?>&subcounty=<?php echo $subcounty ?>&revenue=<?php echo $revenue ?>&product=<?php echo $product ?>&paid_date=<?php echo $paid_date ?>&mode=drill5&category=<?php echo $category ?>', <?php echo $market ?>, '<?php echo $market?>'],
						<?php
					}
				}
				else if($revenue == 'parking'){
					$result = mysql_query("select distinct paid_date, sum(invoice_amount)parking from ".$subcounty."_".$revenue.".statements where paid_date between '$filter_start_date' and '$filter_end_date' and status = 'Paid' and product = '$product' group by paid_date order by paid_date asc");
					while ($row = mysql_fetch_array($result))
					{
						$paid_date = $row['paid_date'];
						$parking = $row['parking'];
						$paid_date_name = date("d M, Y", strtotime($paid_date));					
						?>
							['<?php echo $paid_date_name ?>', 'financial_summary.php?report_start_date=<?php echo $filter_start_date ?>&report_end_date=<?php echo $filter_end_date ?>&subcounty=<?php echo $subcounty ?>&revenue=<?php echo $revenue ?>&product=<?php echo $product ?>&paid_date=<?php echo $paid_date ?>&mode=drill5&cat=<?php echo $cat ?>', <?php echo $parking ?>, '<?php echo $parking?>'],
						<?php
					}
				}
				else if($revenue == 'quarry'){
					$result = mysql_query("select distinct paid_date, sum(invoice_amount)quarry from ".$subcounty."_".$revenue.".statements where paid_date between '$filter_start_date' and '$filter_end_date' and status = 'Paid' and product = '$product' group by paid_date order by paid_date asc");
					while ($row = mysql_fetch_array($result))
					{
						$paid_date = $row['paid_date'];
						$quarry = $row['quarry'];
						$paid_date_name = date("d M, Y", strtotime($paid_date));					
						?>
							['<?php echo $paid_date_name ?>', 'financial_summary.php?report_start_date=<?php echo $filter_start_date ?>&report_end_date=<?php echo $filter_end_date ?>&subcounty=<?php echo $subcounty ?>&revenue=<?php echo $revenue ?>&product=<?php echo $product ?>&paid_date=<?php echo $paid_date ?>&mode=drill5', <?php echo $quarry ?>, '<?php echo $quarry?>'],
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
    			hAxis: {title: 'Daily Revenue',  titleTextStyle: {color: '#000A8B'}},
			vAxis: {title: 'Revenue',  titleTextStyle: {color: '#000A8B'}},
        	};
        	
        	var chart = new google.visualization.ColumnChart(document.getElementById('daily'));
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
				$result_tender = mysql_query("select sum(juja_quarry.statements.invoice_amount)juja_quarry, (select sum(juja_market_fees.statements.invoice_amount)juja_market_fees from juja_market_fees.statements where EXTRACT(YEAR FROM juja_market_fees.statements.paid_date) = '$filter_year' and juja_market_fees.statements.status = 'Paid')juja_market_fees, (select sum(ruiru_parking.statements.invoice_amount)ruiru_parking from ruiru_parking.statements where EXTRACT(YEAR FROM ruiru_parking.statements.paid_date) = '$filter_year' and ruiru_parking.statements.status = 'Paid')ruiru_parking, (select sum(kiambu_parking.statements.invoice_amount)kiambu_parking from kiambu_parking.statements where EXTRACT(YEAR FROM kiambu_parking.statements.paid_date) = '$filter_year' and kiambu_parking.statements.status = 'Paid')kiambu_parking, (select sum(kiambu_market_fees.statements.invoice_amount)kiambu_market_fees from kiambu_market_fees.statements where EXTRACT(YEAR FROM kiambu_market_fees.statements.paid_date) = '$filter_year' and kiambu_market_fees.statements.status = 'Paid')kiambu_market_fees, (select sum(ruiru_market_fees.statements.invoice_amount)ruiru_market_fees from ruiru_market_fees.statements where EXTRACT(YEAR FROM ruiru_market_fees.statements.paid_date) = '$filter_year' and ruiru_market_fees.statements.status = 'Paid')ruiru_market_fees, (select sum(thika_parking.statements.invoice_amount)thika_parking from thika_parking.statements where EXTRACT(YEAR FROM thika_parking.statements.paid_date) = '$filter_year' and thika_parking.statements.status = 'Paid')thika_parking, (select sum(thika_market_fees.statements.invoice_amount)thika_market_fees from thika_market_fees.statements where EXTRACT(YEAR FROM thika_market_fees.statements.paid_date) = '$filter_year' and thika_market_fees.statements.status = 'Paid')thika_market_fees from juja_quarry.statements where EXTRACT(YEAR FROM paid_date) = '$filter_year' and status = 'Paid';");
				while ($row = mysql_fetch_array($result_tender))
				{
					$juja_quarry = $row['juja_quarry'];
					$juja_market_fees = $row['juja_market_fees'];
					//$juja_parking = $row['juja_parking'];
					$juja = $juja_quarry + $juja_market_fees + $juja_parking;
					$kiambu_market_fees = $row['kiambu_market_fees'];
					$kiambu_parking = $row['kiambu_parking'];
					//$kiambu = $kiambu_market_fees + $kiambu_parking;
					$ruiru_market_fees = $row['ruiru_market_fees'];
					$ruiru_parking = $row['ruiru_parking'];
					$ruiru = $ruiru_market_fees + $ruiru_parking;
					$thika_market_fees = $row['thika_market_fees'];
					$thika_parking = $row['thika_parking'];
					$thika = $thika_market_fees + $thika_parking;
					
					?>
						['Juja', 'financial_summary.php?report_start_date=<?php echo $start_date ?>&report_end_date=<?php echo $current_date ?>&subcounty=juja&mode=drill1', <?php echo $juja?>, '<?php echo $juja?>'],
						['Ruiru', 'financial_summary.php?report_start_date=<?php echo $start_date ?>&report_end_date=<?php echo $current_date ?>&subcounty=ruiru&mode=drill1', <?php echo $ruiru?>, '<?php echo $ruiru?>'],
						['Thika', 'financial_summary.php?report_start_date=<?php echo $start_date ?>&report_end_date=<?php echo $current_date ?>&subcounty=thika&mode=drill1', <?php echo $thika?>, '<?php echo $thika?>'],
					<?php
				}
			?>
        	]);

        var view = new google.visualization.DataView(data);
      	view.setColumns([0, 2]);

        var options = {
        	backgroundColor: '#F0F1F1',
    		chartArea:{left:70,top:10,width:'90%',height:'80%'},
    		hAxis: {title: 'Revenue',  titleTextStyle: {color: '#000A8B'}},
		vAxis: {title: 'Sub County',  titleTextStyle: {color: '#000A8B'}},
		pieHole: 0.4,
        };
        	
        var chart = new google.visualization.PieChart(document.getElementById('juja_quarry'));
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
        		['Month', 'Revenue'],
        		<?php
				//$results = mysql_query("select distinct invoice_month, sum(invoice_amount)invoice_amount from juja_market_fees.statement_report group by invoice_month;");
				$results = mysql_query("select distinct juja_market_fees.statement_report.invoice_month, juja_market_fees.calender.month, sum(juja_market_fees.statement_report.invoice_amount)invoice_amount from juja_market_fees.statement_report inner join juja_market_fees.calender on juja_market_fees.calender.id = juja_market_fees.statement_report.invoice_month group by juja_market_fees.statement_report.invoice_month;");
				while ($row = mysql_fetch_array($results))
				{
					$invoice_month = $row['month'];
					$invoice_amount = $row['invoice_amount'];
					
					?>
						['<?php echo $invoice_month ?>',  <?php echo $invoice_amount ?>],
					<?php
				}
			?>
        	]);

        var options = {
    		//legend: { position: 'bottom' },
    		backgroundColor: '#F0F1F1',
    		chartArea:{left:70,top:10,width:'90%',height:'75%'},
    		hAxis: {title: 'Month',  titleTextStyle: {color: '#000A8B'}},
		vAxis: {title: 'Revenue',  titleTextStyle: {color: '#000A8B'}},
        };

        var chart = new google.visualization.LineChart(document.getElementById('revenue_collected'));
        chart.draw(data, options);
        
      }
</script>

<script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        	var data = google.visualization.arrayToDataTable([
          		['Sub County', 'Revenue', { role: 'annotation' }],
          		<?php
          			if($revenue == 'quarry'){
					$result = mysql_query("select distinct clerk, sum(invoice_amount)quarry from ".$subcounty."_".$revenue.".statements where paid_date = '$date_paid' and status = 'Paid' and product = '$product' and subcategory = '$cat' group by clerk order by clerk asc");
					while ($row = mysql_fetch_array($result))
					{
						$clerk = $row['clerk'];
						$quarry = $row['quarry'];					
						?>
							['<?php echo $clerk ?>', <?php echo $quarry ?>, '<?php echo $quarry?>'],
						<?php
					}
				}
				else if($revenue == 'market'){
					$result = mysql_query("select distinct clerk, sum(invoice_amount)quarry from ".$subcounty."_".$revenue."_fees.statements where paid_date = '$date_paid' and status = 'Paid' and product = '$product' and subcategory = '$category' group by clerk order by clerk asc");
					while ($row = mysql_fetch_array($result))
					{
						$clerk = $row['clerk'];
						$quarry = $row['quarry'];					
						?>
							['<?php echo $clerk ?>', <?php echo $quarry ?>, '<?php echo $quarry?>'],
						<?php
					}
				}
				else if($revenue == 'parking'){
					$result = mysql_query("select distinct clerk, sum(invoice_amount)quarry from ".$subcounty."_".$revenue.".statements where paid_date = '$date_paid' and status = 'Paid' and product = '$product' and subcategory = '$cat' group by clerk order by clerk asc");
					while ($row = mysql_fetch_array($result))
					{
						$clerk = $row['clerk'];
						$quarry = $row['quarry'];					
						?>
							['<?php echo $clerk ?>', <?php echo $quarry ?>, '<?php echo $quarry?>'],
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

		var chart = new google.visualization.ColumnChart(document.getElementById('clerk'));
		chart.draw(data, options);
      }
</script>

<script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        	var data = google.visualization.arrayToDataTable([
          		['Sub County', 'Revenue', { role: 'annotation' }],
          		<?php
          			if($revenue == 'quarry'){
					$result = mysql_query("select distinct product, sum(invoice_amount)invoice from ".$subcounty."_".$revenue.".statements where subcategory = '$cat' and paid_date = '$date_paid' and status = 'Paid' and clerk = '$clerk' group by product");
					while ($row = mysql_fetch_array($result))
					{
						$product = $row['product'];
						$invoice = $row['invoice'];					
						?>
							['<?php echo $product ?>', <?php echo $invoice ?>, '<?php echo $invoice?>'],
						<?php
					}
				}
				else if($revenue == 'market'){
					$result = mysql_query("select distinct product, sum(invoice_amount)invoice from ".$subcounty."_".$revenue."_fees.statements where subcategory = '$category' and paid_date = '$date_paid' and status = 'Paid' and clerk = '$clerk' group by product");
					while ($row = mysql_fetch_array($result))
					{
						$product = $row['product'];
						$invoice = $row['invoice'];					
						?>
							['<?php echo $product ?>', <?php echo $invoice ?>, '<?php echo $invoice?>'],
						<?php
					}
				}
				else if($revenue == 'parking'){
					$result = mysql_query("select distinct product, sum(invoice_amount)invoice from ".$subcounty."_".$revenue.".statements where subcategory = '$cat' and paid_date = '$filter' and status = 'Paid' and clerk = '$clerk' group by product");
					while ($row = mysql_fetch_array($result))
					{
						$product = $row['product'];
						$invoice = $row['invoice'];					
						?>
							['<?php echo $product ?>', <?php echo $invoice ?>, '<?php echo $invoice?>'],
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

		var chart = new google.visualization.ColumnChart(document.getElementById('byprodct_last'));
		chart.draw(data, options);
      }
</script>
