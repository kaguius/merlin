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
		$page_title = "Income Report";
		include_once('includes/header.php');
		include_once('includes/db_conn.php');
		
		$filter_month = date("m");
		$filter_year = date("Y");
		$filter_day = date("d");
		$current_date = $filter_year.'-'.$filter_month.'-'.$filter_day;

		if (!empty($_GET)){	
			$filter_start_date = $_GET['report_start_date'];
			$filter_start_date = date('Y-m-d', strtotime(str_replace('-', '/', $filter_start_date)));
			$filter_end_date = $_GET['report_end_date'];
			$filter_end_date = date('Y-m-d', strtotime(str_replace('-', '/', $filter_end_date)));
		}
		if ($filter_start_date != "" && $filter_end_date != ""){
		
		//$report_term = 7;
		//$start_report_date = date('Y-m-d',strtotime($current_date) - (24 * 3600 * $report_term));
			
		?>
		<div id="page">
			<div id="content">
				<div class="post">
					<h2><?php echo $page_title ?></h2>
					<br />
					<table width="100%" border="0" cellspacing="2" cellpadding="2" class="display" id="exampl">
						<thead bgcolor="#E6EEEE">
							<tr>
								<th>Month</th>
								<th>Init</th>
								<th>Loan</th>
								<th>Extension</th>
								<th>Interest</th>
								<th>Late</th>
								<th>Waiver</th>
								<!--<th>Admin Fee</th>
								<th>Appointment</th>-->
								<th>Early</th>
								<th>Surplus</th>
								<!--<th>Fix</th>-->
								<th>Joining</th>
								<th>Total</th>
								<th>I. Earned</th> 
								<th>Repayment</th>
							</tr>
						</thead>
						<tbody>
					<?php

					//$sql = mysql_query("select distinct EXTRACT(month FROM loan_date)month, sum(initiation_fee)initiation_fee, sum(loan_amount)loan_amount, sum(loan_extension)loan_extension, sum(loan_interest)loan_interest, sum(loan_late_interest)loan_late_interest, sum(waiver)waiver, sum(admin_fee)admin_fee, sum(appointment_fee)appointment_fee, sum(early_settlement)early_settlement, sum(early_settlement_surplus)early_settlement_surplus, sum(fix)fix, sum(joining_fee)joining_fee, sum(loan_total_interest)loan_total_interest  from loan_application where loan_date between '$filter_start_date' and '$filter_end_date' and loan_status != '12' and loan_status != '14' and loan_status != '11' and loan_status != '10' group by EXTRACT(month FROM loan_date)");
					$sql = mysql_query("select distinct EXTRACT(month FROM loan_date)month, EXTRACT(year FROM loan_date)year, sum(initiation_fee)initiation_fee, sum(loan_amount)loan_amount, sum(loan_extension)loan_extension, sum(loan_interest)loan_interest, sum(loan_late_interest)loan_late_interest, sum(waiver)waiver, sum(admin_fee)admin_fee, sum(appointment_fee)appointment_fee, sum(early_settlement)early_settlement, sum(early_settlement_surplus)early_settlement_surplus, sum(fix)fix, sum(joining_fee)joining_fee, sum(loan_total_interest)loan_total_interest  from loan_application where loan_date between '$filter_start_date' and '$filter_end_date' and customer_station != '0' and loan_failure_status = '0' and loan_status != '10' and loan_status != '9' and loan_status != '11' and loan_status != '12' and loan_status != '14' group by EXTRACT(month FROM loan_date)");
					
					$total_repayments = 0;
					while ($row = mysql_fetch_array($sql))
					{
						$month = $row['month'];
						$year = $row['year'];
						$initiation_fee = $row['initiation_fee'];
						$loan_amount = $row['loan_amount'];
						$loan_extension = $row['loan_extension'];
						$loan_interest = $row['loan_interest'];
						$loan_late_interest = $row['loan_late_interest'];
						$waiver = $row['waiver'];
						$admin_fee = $row['admin_fee'];
						$appointment_fee = $row['appointment_fee'];
						$early_settlement = $row['early_settlement'];
						$early_settlement_surplus = $row['early_settlement_surplus'];
						$fix = $row['fix'];
						$joining_fee = $row['joining_fee'];
						$loan_total_interest = $row['loan_total_interest'];

						if($month == '10' && $year == '2015'){
							$loan_late_interest = 0;
						}						
						else if($loan_date >= '2015-10-01'){
							$result = mysql_query("select sum(penalty_amount)penalty from penallty_charged where extract(month from penalty_date) = '$month' and extract(year from penalty_date) = '$year'");
							while ($row = mysql_fetch_array($result))
							{
								$penalty = $row['penalty'];
							}
							$loan_late_interest = $penalty;
						}
						else{
							$loan_late_interest = $loan_late_interest;
						}

						$Calculated_total = $initiation_fee + $loan_amount + $loan_extension + $loan_interest + $loan_late_interest + $waiver + $admin_fee + $appointment_fee + $early_settlement + $early_settlement_surplus + $fix + $joining_fee;
		
						$result = mysql_query("select month from calender where id = '$month'");
						while ($row = mysql_fetch_array($result))
						{
							$month_name = $row['month'];
						}

						$result = mysql_query("select distinct loan_code from loan_application where extract(month from loan_date) = '$month' and extract(year from loan_date) = '$filter_year' and loan_status != '12' and loan_status != '14' and loan_status != '11' and loan_status != '10'");
						while ($row = mysql_fetch_array($result))
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

						$interest_earned = $loan_interest + $early_settlement;
						$percent = ($total_repayments / $loan_total_interest) * 100;
		
						if ($intcount % 2 == 0) {
							$display= '<tr bgcolor = #F0F0F6>';
						}
						else {
							$display= '<tr>';
						}
						echo $display;
						echo "<td align='right' valign='top'>$month_name</td>";
						echo "<td align='right' valign='top'>".number_format($initiation_fee, 0)."</td>";
						echo "<td align='right' valign='top'>".number_format($loan_amount, 0)."</td>";
						echo "<td align='right' valign='top'>".number_format($loan_extension, 0)."</td>";	
						echo "<td align='right' valign='top'>".number_format($loan_interest, 0)."</td>";		
						echo "<td align='right' valign='top'>".number_format($loan_late_interest, 0)."</td>";
						echo "<td align='right' valign='top'>".number_format($waiver, 0)."</td>";
						//echo "<td align='right' valign='top'>".number_format($admin_fee, 0)."</td>";
						//echo "<td align='right' valign='top'>".number_format($appointment_fee, 0)."</td>";
						echo "<td align='right' valign='top'>".number_format($early_settlement, 0)."</td>";
						echo "<td align='right' valign='top'>".number_format($early_settlement_surplus, 0)."</td>";
						//echo "<td align='right' valign='top'>".number_format($fix, 0)."</td>";
						echo "<td align='right' valign='top'>".number_format($joining_fee, 0)."</td>";
						//echo "<td align='right' valign='top'>".number_format($loan_total_interest, 0)."</td>";
						echo "<td align='right' valign='top'>".number_format($Calculated_total, 0)."</td>";
						echo "<td align='right' valign='top'>".number_format($interest_earned, 0)."</td>";
						echo "<td align='right' valign='top'>".number_format($total_repayments, 0)."</td>";
						//echo "<td align='right' valign='top'>".number_format($percent, 0)."%</td>";			
						echo "</tr>";
						$initiation_fee = 0;
						$loan_amount = 0;
						$loan_extension = 0;
						$loan_interest = 0;
						$loan_late_interest = 0;
						$waiver = 0;
						$early_settlement = 0;
						$early_settlement_surplus = 0;
						$joining_fee = 0;
						$loan_total_interest = 0;
						$interest_earned = 0;
						$total_repayments = 0;
					}
						?>
					</tbody>
					<tfoot bgcolor="#E6EEEE">
						<tr>
							<th>Month</th>
							<th>Init</th>
							<th>Loan</th>
							<th>Extension</th>
							<th>Interest</th>
							<th>Late</th>
							<th>Waiver</th>
							<!--<th>Admin Fee</th>
							<th>Appointment</th>-->
							<th>Early</th>
							<th>Surplus</th>
							<!--<th>Fix</th>-->
							<th>Joining</th>
							<th>Total</th>
							<th>I. Earned</th> 
							<th>Repayment</th>
						</tr>
					</tfoot>
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
						$("#exampl").btechco_excelexport({
						containerid: "exampl"
						   , datatype: $datatype.Table
						});
					});
					});
				</script>
				</div>
			</div>
			<br class="clearfix" />
			</div>
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
