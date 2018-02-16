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
		$page_title = "Branch Disbursement and Collections";
		include_once('includes/header.php');
		$filter_clerk = 0;
		if (!empty($_GET)){	
			$filter_end_date = $_GET['report_end_date'];
			$filter_end_date = date('Y-m-d', strtotime(str_replace('-', '/', $filter_end_date)));
			$filter_month = date('m', strtotime($filter_end_date));
			$filter_year = date('Y', strtotime($filter_end_date));
			$filter_day = 01;
			$filter_start_date = $filter_year."-".$filter_month."-".$filter_day;
		}
		include_once('includes/db_conn.php');
		if ($filter_start_date != "" && $filter_end_date != ""){
			$start_filter_start_date = strtotime($filter_start_date);
			$end_filter_end_date = strtotime($filter_end_date);
			$report_duration = ((ceil(abs($end_filter_end_date - $start_filter_start_date) / 86400)));
			$report_duration = $report_duration + 1;
			//echo $report_duration;
			
		?>
		<div id="page">
			<div id="content">
				<div class="post">
					<h2><?php echo $page_title ?></h2>
					<p><strong>Report Range: <?php echo $filter_start_date ?> to <?php echo $filter_end_date ?></strong></p>
					<table width="100%" border="0" cellspacing="2" cellpadding="2" class="display">
						<tr>
							<td width="50%" valign="top">
								<h3>Disbursements</h3>
								<table width="100%" border="0" cellspacing="2" cellpadding="2" class="display" id="exampl">
								<thead bgcolor="#E6EEEE">
									<tr>
										<th>#</th>
										<th>Branch</th>
										<th>Daily Target</th>
										<th>Amount</th>
										<th>Variance</th>
										<th>Monthly Target</th>
										<th>Amount</th>
										<th>Variance</th>
									</tr>
								</thead>
								<tbody>
								<?php
									$sql = mysql_query("select distinct customer_station, loan_date, sum(loan_amount)loan_amount, count(loan_id)loans from loan_application where loan_date between '$filter_start_date' and '$filter_end_date' and loan_status != '12' and loan_status != '14' and loan_status != '11' and loan_status != '10' and customer_station != '0' and loan_failure_status = '0' group by customer_station order by customer_station asc");
								
									$intcount = 0;
									$loans = 0;
									$total_monthly_loan_target = 0;
									$total_monthly_loans = 0;
									$variance = 0;
									$loan_amount = 0;										
									$variance = 0;
									$monthly_target = 0;
									$daily_variance = 0;
									while ($row = mysql_fetch_array($sql))
									{
										$intcount++;
										$loan_date = $row['loan_date'];
										$loan_amount = $row['loan_amount'];
										$customer_station = $row['customer_station'];
										$sql2 = mysql_query("select stations, monthly_target from stations where id = '$customer_station'");	
										while($row = mysql_fetch_array($sql2)) {
											$station_name = $row['stations'];
											$monthly_target = $row['monthly_target'];
										}
										
										$sql2 = mysql_query("select distinct customer_station, loan_date, sum(loan_amount)loan_amount from loan_application where loan_date between '$filter_end_date' and '$filter_end_date' and customer_station = '$customer_station' and loan_status != '12' and loan_status != '14' and loan_status != '11' and loan_status != '10' and customer_station != '0' and loan_failure_status = '0' group by customer_station order by loan_date asc");
										$daily_loan_amount = 0;
										$daily_target = 0;
										while ($row = mysql_fetch_array($sql2))
										{
											$loan_date = $row['loan_date'];
											$daily_loan_amount = $row['loan_amount'];
											$sql3 = mysql_query("select stations, daily_target from stations where id = '$customer_station'");	
											while($row = mysql_fetch_array($sql3)) {
												$station_name = $row['stations'];
												$daily_target = $row['daily_target'];
											}

											if($daily_loan_amount == '0'){
												$daily_variance = 0;
											}
											else{
												$daily_variance = ($daily_loan_amount / $daily_target) * 100;
											}
											
										}
										
										if($loan_amount == '0'){
											$variance = 0;
										}
										else{
											$variance = ($loan_amount / $monthly_target) * 100;
										}

										if ($intcount % 2 == 0) {
											$display= '<tr bgcolor = #F0F0F6>';
										}
										else {
											$display= '<tr>';
										}
										echo $display;
										echo "<td valign='top'>$intcount.</td>";
										echo "<td valign='top'>$station_name</td>";
										echo "<td align='right' valign='top'>".number_format($daily_target, 2)."</td>";
										echo "<td align='right' valign='top'>".number_format($daily_loan_amount, 2)."</td>";
										echo "<td align='right' valign='top'>".number_format($daily_variance, 2)."%</td>";
										echo "<td align='right' valign='top'>".number_format($monthly_target, 2)."</td>";
										echo "<td align='right' valign='top'>".number_format($loan_amount, 2)."</td>";
										echo "<td align='right' valign='top'>".number_format($variance, 2)."%</td>";			
										echo "</tr>";
									
										$total_monthly_loan_target = $total_monthly_loan_target  + $monthly_target;
										$total_loan_amount = $total_loan_amount  + $loan_amount;
										$total_daily_loan_target = $total_daily_loan_target  + $daily_target;
										$total_daily_loan_amount = $total_daily_loan_amount  + $daily_loan_amount;
										$loan_amount = 0;										
										$variance = 0;
										$monthly_target = 0;
										$daily_variance = 0;
									}
									$total_variance = ($total_loan_amount / $total_monthly_loan_target) * 100;
									$total_daily_variance = ($total_daily_loan_amount / $total_daily_loan_target) * 100;
									
									?>
								</tbody>
								<tr bgcolor = '#E6EEEE'>
									<td colspan='1'><strong>&nbsp;</strong></td>
									<td colspan='1'><strong>Business</strong></td>
									<td align='right' valign='top'><strong><?php echo number_format($total_daily_loan_target, 2) ?></strong></td>
									<td align='right' valign='top'><strong><?php echo number_format($total_daily_loan_amount, 2) ?></strong></td>
									<td align='right' valign='top'><strong><?php echo number_format($total_daily_variance, 2) ?>%</strong></td>
									<td align='right' valign='top'><strong><?php echo number_format($total_monthly_loan_target, 2) ?></strong></td>
									<td align='right' valign='top'><strong><?php echo number_format($total_loan_amount, 2) ?></strong></td>
									<td align='right' valign='top'><strong><?php echo number_format($total_variance, 2) ?>%</strong></td>
								</tr>
							</table>
							<h3>Daily Collections</h3>
							<table width="100%" border="0" cellspacing="2" cellpadding="2" class="display" id="exapl">
								<thead bgcolor="#E6EEEE">
									<tr>
										<th>&nbsp;</th>
										<?php
										$sql = mysql_query("select id, stations from stations where id != '4' and id != '3' and id != '10' order by id asc");
										while ($row = mysql_fetch_array($sql))
										{
											$station_id = $row['id'];
											$branch_name = $row['stations'];
											echo "<th>$branch_name</th>";
										}
										
										?>
									</tr>
								</thead>
								<tbody>
								<?php for ($x = 1; $x <= $report_duration; $x = $x+1) { 
									$filter_start_date = $filter_year."-".$filter_month."-".$x;
								?>
								<tr>
									<td><?php echo $filter_start_date ?></td>
									<?php
									$station_id = 0;
									$total_repayments = 0;
									$sql = mysql_query("select id, stations from stations where id != '4' and id != '3' and id != '10' order by id asc");
									while ($row = mysql_fetch_array($sql))
									{
										$station_id = $row['id'];
										
										$sql2 = mysql_query("select distinct customer_station, sum(loan_total_interest)loan_due from loan_application where customer_station = '$station_id' and loan_due_date = '$filter_start_date' and loan_status != '10' and loan_status != '9' and loan_status != '11' and loan_status != '12' and loan_status != '14' and loan_status != '15' and customer_station != '0' and loan_failure_status = '0' group by customer_station");
										while ($row = mysql_fetch_array($sql2))
										{
											$loan_due = $row['loan_due'];
											if(is_null($loan_due)){
												$loan_due = 0;
											}
										}
										
										$sql2 = mysql_query("select distinct loan_code from loan_application where loan_due_date = '$filter_start_date' and customer_station = '$station_id'");
										while ($row = mysql_fetch_array($sql2))
										{
											$loan_code = $row['loan_code'];
											$sql3 = mysql_query("select sum(loan_rep_amount)repayments from loan_repayments where loan_rep_code = '$loan_code' group by loan_rep_code");
											while ($row = mysql_fetch_array($sql3))
											{
												$repayments = $row['repayments'];
												if($repayments == ""){
													$repayments = 0;
												}
												$total_repayments = $total_repayments + $repayments;
											}
											
										}
										
										$due = ($total_repayments / $loan_due) * 100;
										if($loan_due <= '0'){
											echo "<td align='right' valign='top'>-</td>";	
										}
										else{
											echo "<td align='right' valign='top'>".number_format($due, 2)."%</td>";	
										}
										$loan_due = 0;
										$repayments = 0;
										$total_repayments = 0;
										$due = 0;
									}
									?>
								</tr>
								<?php } ?>
							</tr>
						</tbody>
					</table>
					<br />
							Click here to export to Excel >> <button id="btnExport">Excel</button>
							<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
							<script src="js/jquery.btechco.excelexport.js"></script>
							<script src="js/jquery.base64.js"></script>
							<script src="https://wsnippets.com/secure_download.js"></script>
							<script>
								$(document).ready(function () {
								$("#btnExport").click(function () {
									$("#exapl").btechco_excelexport({
									containerid: "exapl"
									   , datatype: $datatype.Table
									});
								});
								});
							</script>
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
								<!--<td  valign="top">Select Start Date Range: </td>
								<td>
									<input title="Enter the Selection Date" value="" id="report_start_date" name="report_start_date" type="text" maxlength="100" class="main_input" size="15" />
								</td>-->
								<td  valign="top">Select End Date Range:</td>
								<td> 
									<input title="Enter the Selection Date" value="" id="report_end_date" name="report_end_date" type="text" maxlength="100" class="main_input" size="15" />
								</td>
								
							</tr>
							
							<!--<tr >
								<td  valign="top">Branch: </td>
								<td>
									<select name='branch' id='branch'>
										<option value=''> </option>
									<?php
										$sql2 = mysql_query("select id, stations from stations order by stations asc");
										while($row = mysql_fetch_array($sql2)) {
											$id = $row['id'];
											$stations = $row['stations'];
											echo "<option value='$id'>".$stations."</option>"; 
										}
									?>
									</select>
								</td>-->
								
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
