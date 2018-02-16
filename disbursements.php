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
					<table width="108%" border="0" cellspacing="2" cellpadding="2" class="display">
						<tr>
							<td width="36%" valign="top">
								<h3>Disbursements</h3>
								<table width="100%" border="0" cellspacing="2" cellpadding="2" class="display" id="exampl">
								<thead bgcolor="#E6EEEE">
									<tr>
										<!--<th>#</th>-->
										<th>Branch</th>
										<th>Amount</th>
										<th>Manual</th>
										<th>Repeater</th>
										<th>Loans</th>
									</tr>
								</thead>
								<tbody>
								<?php
									//if($station == '3'){
										$sql = mysql_query("select distinct loan_date, sum(loan_amount)loan_amount, count(loan_id)loans, customer_station from loan_application where loan_date between '$filter_start_date' and '$filter_end_date' and customer_station != '0' and loan_failure_status = '0' and loan_status != '10' and loan_status != '9' and loan_status != '11' and loan_status != '12' and loan_status != '14' group by customer_station order by customer_station asc");
									//}
									//else{
									//	 $sql = mysql_query("select distinct loan_date, sum(loan_amount)loan_amount, count(loan_id)loans from loan_application where loan_date between '$filter_start_date' and '$filter_end_date' and customer_station = '$station' and loan_failure_status = '0' and customer_station != '0' and loan_status != '10' and loan_status != '9' and loan_status != '11' and loan_status != '12' and loan_status != '14' group by loan_date, customer_station order by loan_date asc");
									//}
									
									$intcount = 0;
									$total_loan_amount = 0;
									$total_loans = 0;
									$total_manual_loans = 0;
									$total_repeater_loans = 0;
									while ($row = mysql_fetch_array($sql))
									{
										$intcount++;
										$loan_date = $row['loan_date'];
										$loan_amount = $row['loan_amount'];
										$loans = $row['loans'];
										$customer_station = $row['customer_station'];
										
										$sql2 = mysql_query("select stations from stations where id = '$customer_station'");
										while ($row = mysql_fetch_array($sql2))
										{
											$stations = $row['stations'];
										}

										$sql2 = mysql_query("select distinct loan_date, count(loan_id)repeater_loans from loan_application where loan_date between '$filter_start_date' and '$filter_end_date' and customer_station = '$customer_station' and UID = '94' and customer_station != '0' and loan_failure_status = '0' and loan_status != '10' and loan_status != '9' and loan_status != '11' and loan_status != '12' and loan_status != '14' group by customer_station order by customer_station asc");
										while ($row = mysql_fetch_array($sql2))
										{
											$repeater_loans = $row['repeater_loans'];
										}
	
										$manual_loans = $loans - $repeater_loans;
							
										if ($intcount % 2 == 0) {
											$display= '<tr bgcolor = #F0F0F6>';
										}
										else {
											$display= '<tr>';
										}
										echo $display;
										//echo "<td valign='top'>$intcount.</td>";
										echo "<td valign='top'>$stations</td>";
										echo "<td align='right' valign='top'>".number_format($loan_amount, 0)."</td>";
										echo "<td align='right' valign='top'>".number_format($manual_loans, 0)."</td>";	
										echo "<td align='right' valign='top'>".number_format($repeater_loans, 0)."</td>";	
										echo "<td align='right' valign='top'>".number_format($loans, 0)."</td>";				
										echo "</tr>";

										
										
										$total_loan_amount = $total_loan_amount  + $loan_amount;
										$total_loans = $total_loans + $loans;
										$total_manual_loans = $total_manual_loans + $manual_loans;
										$total_repeater_loans = $total_repeater_loans + $repeater_loans;
										$repeater_loans = 0;
										$manual_loans = 0;
										$loans = 0;
									}
									?>
								</tbody>
								<tr bgcolor = '#E6EEEE'>
									<td ><strong>&nbsp;</strong></td>
									<td align='right' valign='top'><strong><?php echo number_format($total_loan_amount, 0) ?></strong></td>
									<td align='right' valign='top'><strong><?php echo number_format($total_manual_loans, 0) ?></strong></td>
									<td align='right' valign='top'><strong><?php echo number_format($total_repeater_loans, 0) ?></strong></td>
									<td align='right' valign='top'><strong><?php echo number_format($total_loans, 0) ?></strong></td>
								</tr>
							</table>
						</td>
						<td width="36%" valign="top">
							<h3>Total Collections</h3>
							<table width="100%" border="0" cellspacing="2" cellpadding="2" class="display" id="exampl">
								<thead bgcolor="#E6EEEE">
									<tr>
										<!--<th>#</th>-->
										<th>Branch</th>
										<th>Amount</th>
										<th>Count</th>
									</tr>
								</thead>
								<tbody>
								<?php
									//if($station == '3'){
										$sql = mysql_query("select distinct loan_rep_date, sum(loan_rep_amount)repayments, count(loan_rep_id)repays, customer_station from loan_repayments where loan_rep_date between '$filter_start_date' and '$filter_end_date' and customer_station != '0' and loan_rep_mpesa_code not like '%4G%' group by customer_station order by customer_station asc");
									//}
									//else{
									//	 $sql = mysql_query("select distinct loan_rep_date, sum(loan_rep_amount)repayments, count(loan_rep_id)repays from loan_repayments where loan_rep_date between '$filter_start_date' and '$filter_end_date' and customer_station = '$station' and customer_station != '0' group by loan_rep_date order by loan_rep_date asc");
										 echo "";
									//}
									
									$intcount = 0;
									$total_repayments = 0;
									$total_repays = 0;
									while ($row = mysql_fetch_array($sql))
									{
										$intcount++;
										$loan_rep_date = $row['loan_rep_date'];
										$repayments = $row['repayments'];
										$repays = $row['repays'];
										$customer_station = $row['customer_station'];
										
										$sql2 = mysql_query("select stations from stations where id = '$customer_station'");
										while ($row = mysql_fetch_array($sql2))
										{
											$stations = $row['stations'];
										}
							
										if ($intcount % 2 == 0) {
											$display= '<tr bgcolor = #F0F0F6>';
										}
										else {
											$display= '<tr>';
										}
										echo $display;
										//echo "<td valign='top'>$intcount.</td>";
										echo "<td valign='top'>$stations</td>";
										echo "<td align='right' valign='top'>".number_format($repayments, 0)."</td>";
										echo "<td align='right' valign='top'>".number_format($repays, 0)."</td>";			
										echo "</tr>";
										
										$total_repayments = $total_repayments  + $repayments;
										$total_repays = $total_repays + $repays;
										$repayments = 0;
										$repays = 0;
									}
									?>
								</tbody>
								<tr bgcolor = '#E6EEEE'>
									<td colspan='1'><strong>&nbsp;</strong></td>
									<td align='right' valign='top'><strong><?php echo number_format($total_repayments, 0) ?></strong></td>
									<td align='right' valign='top'><strong><?php echo number_format($total_repays, 0) ?></strong></td>
								</tr>
							</table>
						</td>

						<td width="36%" valign="top">
							<h3>Collections</h3>
							<table width="100%" border="0" cellspacing="2" cellpadding="2" class="display" id="exampl">
								<thead bgcolor="#E6EEEE">
									<tr>
										<!--<th>#</th>-->
										<th>Branch</th>
										<th>Due</th>
										<th>Collected</th>
										<th>%</th>
									</tr>
								</thead>
								<tbody>
								<?php
									//if($station == '3'){
										$sql = mysql_query("select distinct customer_station, sum(loan_total_interest)due from loan_application where loan_due_date between '$filter_start_date' and '$filter_end_date' and loan_status != '10' and loan_status != '9' and loan_status != '11' and loan_status != '12' and loan_status != '14' and loan_status != '15' and customer_station != '0' and loan_failure_status = '0' group by customer_station order by customer_station asc");
										//$sql = mysql_query("select distinct customer_station, sum(loan_total_interest)due from loan_application where loan_date between '$filter_start_date' and '$filter_end_date' and loan_status != '12' and loan_status != '14' and loan_status != '11' and customer_station != '0' group by customer_station order by customer_station asc");
									//}
									//else{
									//	 $sql = mysql_query("select distinct loan_due_date, sum(loan_total_interest)due from loan_application where loan_due_date between '$filter_start_date' and '$filter_end_date' and customer_station = '$station' and loan_status != '10' and loan_status != '9' and loan_status != '11' and loan_status != '12' and loan_status != '14' and customer_station != '0' and loan_status != '15' and loan_failure_status = '0' group by loan_due_date order by loan_due_date asc");
									//}
									
									$intcount = 0;
									$total_due = 0;
									$total_payments = 0;
									$total_ratio = 0;
									$total_repayments = 0;
									while ($row = mysql_fetch_array($sql))
									{
										$intcount++;
										//$loan_due_date = $row['loan_due_date'];
										$due = $row['due'];
										$customer_station = $row['customer_station'];
										
										$sql2 = mysql_query("select stations from stations where id = '$customer_station'");
										while ($row = mysql_fetch_array($sql2))
										{
											$stations = $row['stations'];
										}
										
										//$sql2 = mysql_query("select distinct loan_code from loan_application where loan_date between '$filter_start_date' and '$filter_end_date' and customer_station = '$customer_station' and loan_status != '12' and loan_status != '14' and loan_status != '11'");
										$sql2 = mysql_query("select distinct loan_code from loan_application where loan_due_date between '$filter_start_date' and '$filter_end_date' and customer_station = '$customer_station' and loan_status != '10' and loan_status != '9' and loan_status != '11' and loan_status != '12' and loan_status != '14' and loan_status != '15' and loan_failure_status = '0'");
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
										
										$ratio = ($total_repayments / $due)*100;
										
										if ($intcount % 2 == 0) {
											$display= '<tr bgcolor = #F0F0F6>';
										}
										else {
											$display= '<tr>';
										}
										echo $display;
										//echo "<td valign='top'>$intcount.</td>";
										echo "<td valign='top'>$stations</td>";
										echo "<td align='right' valign='top'>".number_format($due, 0)."</td>";
										echo "<td align='right' valign='top'>".number_format($total_repayments, 0)."</td>";
										echo "<td align='right' valign='top'>".number_format($ratio, 2)."%</td>";						
										echo "</tr>";
										
										$total_due = $total_due  + $due;
										$total_payments = $total_payments + $total_repayments;
										
										$amount = 0;
										$repayments = 0;
										$total_repayments = 0;
									}
									$total_ratio = ($total_payments / $total_due) * 100;
									?>
								</tbody>
								<tr bgcolor = '#E6EEEE'>
									<td colspan='1'><strong>&nbsp;</strong></td>
									<td align='right' valign='top'><strong><?php echo number_format($total_due, 0) ?></strong></td>
									<td align='right' valign='top'><strong><?php echo number_format($total_payments, 0) ?></strong></td>
									<td align='right' valign='top'><strong><?php echo number_format($total_ratio, 2) ?>%</strong></td>
								</tr>
							</table>
						</td>
					</tr>
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
