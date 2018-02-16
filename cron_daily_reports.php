<?php
	$userid = "";
	$adminstatus = 4;
	$property_manager_id = "";
	session_start();
	if (!empty($_SESSION)){
		$userid = $_SESSION["userid"] ;
		$adminstatus = $_SESSION["adminstatus"] ;
		$username = $_SESSION["username"];
		$station = $_SESSION["station"] ;
	}

	//if($adminstatus != 1 || $adminstatus != 2 || $adminstatus != 4){
	if($adminstatus == 4){
		include_once('includes/header.php');
		?>
		<script type="text/javascript">
			document.location = "insufficient_permission.php";
		</script>
		<?php
	}
	else{
		$transactiontime = date("Y-m-d G:i:s");
		$page_title = "Business Daily Reports";
		include_once('includes/header.php');
		$filter_clerk = 0;
		if (!empty($_GET)){	
			$filter_clerk = $_GET['clerk'];
			$filter_start_date = $_GET['report_start_date'];
			$filter_start_date = date('Y-m-d', strtotime(str_replace('-', '/', $filter_start_date)));
			$filter_end_date = $_GET['report_end_date'];
			$filter_end_date = date('Y-m-d', strtotime(str_replace('-', '/', $filter_end_date)));
		}
		include_once('includes/db_conn.php');
		if ($filter_start_date != "" && $filter_end_date != ""){
			
		?>
		<div id="page">
			<div id="content">
				<div class="post">
					<h2><?php echo $page_title ?></h2>
					<p><strong>Report Range: <?php echo $filter_start_date ?> to <?php echo $filter_end_date ?></strong></p>
					<h3>Disbursements</h3>
					<table width="108%" border="0" cellspacing="2" cellpadding="2" class="display">
						<thead bgcolor="#FF8000">
							<tr>
								<!--<th>#</th>-->
								<th>Date</th>
								<th>Branch</th>
								<th>Daily Target</th>
								<th>Actual</th>
								<th>Variance</th>
								<th>Monthly Target</th>
								<th>Actual</th>
								<th>Variance</th>
							</tr>
							</thead>
							<tbody>
								<?php
									$sql = mysql_query("select report_date, branch, daily_target, daily_actual, daily_variance, monthly_target, monthly_actual, moanthly_variance from daily_report_disbursements where report_date between '$filter_start_date' and '$filter_end_date'");
									while ($row = mysql_fetch_array($sql))
									{
										$intcount++;
										$report_date = $row['report_date'];
										$branch = $row['branch'];
										$daily_target = $row['daily_target'];
										$daily_actual = $row['daily_actual'];
										$daily_variance = $row['daily_variance'];
										$monthly_target = $row['monthly_target'];
										$monthly_actual = $row['monthly_actual'];
										$moanthly_variance = $row['moanthly_variance'];
							
										if ($intcount % 2 == 0) {
											$display= '<tr bgcolor = #F7BE81>';
										}
										else {
											$display= '<tr>';
										}
										echo $display;
										//echo "<td valign='top'>$intcount.</td>";
										echo "<td valign='top'>$report_date</td>";
										echo "<td valign='top'>$branch</td>";
										echo "<td align='right' valign='top'>".number_format($daily_target, 2)."</td>";
										echo "<td align='right' valign='top'>".number_format($daily_actual, 2)."</td>";	
										echo "<td align='right' valign='top'>".number_format($daily_variance, 2)."%</td>";
										echo "<td align='right' valign='top'>".number_format($monthly_target, 2)."</td>";
										echo "<td align='right' valign='top'>".number_format($monthly_actual, 2)."</td>";	
										echo "<td align='right' valign='top'>".number_format($moanthly_variance, 2)."%</td>";					
										echo "</tr>";
									}
									?>
								</tbody>
					</table>
					<h3>New Customers</h3>
					<table width="108%" border="0" cellspacing="2" cellpadding="2" class="display">
						<thead bgcolor="#FF8000">
							<tr>
								<!--<th>#</th>-->
								<th>Date</th>
								<th>Branch</th>
								<th>Target</th>
								<th>Actual</th>
								<th>Variance</th>
							</tr>
							</thead>
							<tbody>
								<?php
									$sql = mysql_query("select report_date, branch, target_customers, actual_customers, daily_variance from daily_report_customers where report_date between '$filter_start_date' and '$filter_end_date'");
									while ($row = mysql_fetch_array($sql))
									{
										$intcount++;
										$report_date = $row['report_date'];
										$branch = $row['branch'];
										$target_customers = $row['target_customers'];
										$actual_customers = $row['actual_customers'];
										$daily_variance = $row['daily_variance'];
							
										if ($intcount % 2 == 0) {
											$display= '<tr bgcolor = #F7BE81>';
										}
										else {
											$display= '<tr>';
										}
										echo $display;
										//echo "<td valign='top'>$intcount.</td>";
										echo "<td valign='top'>$report_date</td>";
										echo "<td valign='top'>$branch</td>";
										echo "<td align='right' valign='top'>".number_format($target_customers, 0)."</td>";
										echo "<td align='right' valign='top'>".number_format($actual_customers, 0)."</td>";	
										echo "<td align='right' valign='top'>".number_format($daily_variance, 0)."%</td>";					
										echo "</tr>";
									}
									?>
								</tbody>
					</table>
					<h3>Amount Due</h3>
					<table width="108%" border="0" cellspacing="2" cellpadding="2" class="display">
						<thead bgcolor="#FF8000">
							<tr>
								<!--<th>#</th>-->
								<th>Date</th>
								<th>Branch</th>
								<th>Due</th>
								<th>Collected</th>
								<th>Rate</th>
								<th>Variance</th>
							</tr>
							</thead>
							<tbody>
								<?php
									$sql = mysql_query("select report_date, branch, amount_due, amount_collected, amount_rate, amount_variance from daily_report_amount_due where report_date between '$filter_start_date' and '$filter_end_date'");
									while ($row = mysql_fetch_array($sql))
									{
										$intcount++;
										$report_date = $row['report_date'];
										$branch = $row['branch'];
										$amount_due = $row['amount_due'];
										$amount_collected = $row['amount_collected'];
										$amount_rate = $row['amount_rate'];
										$amount_variance = $row['amount_variance'];
							
										if ($intcount % 2 == 0) {
											$display= '<tr bgcolor = #F7BE81>';
										}
										else {
											$display= '<tr>';
										}
										echo $display;
										//echo "<td valign='top'>$intcount.</td>";
										echo "<td valign='top'>$report_date</td>";
										echo "<td valign='top'>$branch</td>";
										echo "<td align='right' valign='top'>".number_format($amount_due, 2)."</td>";
										echo "<td align='right' valign='top'>".number_format($amount_collected, 2)."</td>";	
										echo "<td align='right' valign='top'>".number_format($amount_rate, 2)."%</td>";	
										echo "<td align='right' valign='top'>".number_format($amount_variance, 2)."</td>";					
										echo "</tr>";
									}
									?>
								</tbody>
					</table>
			</div>
			<br class="clearfix" />
			</div>
		</div>
		<?php
		}
		else{
		?>		
		<div id="page">
			<div id="content">
				<div class="post">
				
					<h2><?php echo $page_title ?></h2>
					<form id="frmCreateTenant" name="frmCreateTenant" method="GET" action="<?php echo $_SERVER['PHP_SELF'];?>">
					<table border="0" width="100%" cellspacing="2" cellpadding="2">
							<tr >
								<td  valign="top">Select Start Date Range: </td>
								<td>
									<input title="Enter the Selection Date" value="" id="report_start_date" name="report_start_date" type="text" maxlength="100" class="main_input" size="15" />
								</td>
								<td  valign="top">Select End Date Range:</td>
								<td> 
									<input title="Enter the Selection Date" value="" id="report_end_date" name="report_end_date" type="text" maxlength="100" class="main_input" size="15" />
								</td>
								
							</tr>
							<tr>
								<td><button name="btnNewCard" id="button">Search</button></td>
							</tr>
						</table>
					</form>
				</div>
			</div>
			<br class="clearfix" />
			</div>
		</div>
<?php
		}
	}
	include_once('includes/footer.php');
?>
