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
		$page_title = "Branch Write-Offs";
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
						<?php if($filter_start_date > '2015-02-31'){ ?>
							<table width="100%" border="0" cellspacing="2" cellpadding="2" class="display" id="exampl">
								<thead bgcolor="#E6EEEE">
									<tr>
										<!--<th>#</th>-->
										<th>Branch</th>
										<th>Loan Amount</th>
										<th>Fees</th>
										<th>Collected</th>
										<th>Lost Fees</th>	
										<th>Write Off</th>
									</tr>
								</thead>
								<tbody>
								<?php
								
								//10000		3500		6000		(-(6000-3500)-loan_amount)
									//if($station == '3'){
										$sql = mysql_query("select distinct customer_station, sum(loan_amount)due, sum(initiation_fee)init, sum(loan_interest)interest, sum(loan_late_interest)penalty, sum(loan_extension)extension from loan_application where loan_date between '$filter_start_date' and '$filter_end_date' and loan_status != '10' and loan_status != '9' and loan_status != '11' and loan_status != '12' and loan_status != '14' and loan_status != '15' and customer_station != '0' and loan_failure_status = '0' and loan_status = '7' group by customer_station order by customer_station asc");
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
										$init = $row['init'];
										$interest = $row['interest'];
										$penalty = $row['penalty'];
										$extension = $row['extension'];
										$fees = $init + $interest + $penalty + $extension;
										$customer_station = $row['customer_station'];
										
										$sql2 = mysql_query("select stations from stations where id = '$customer_station'");
										while ($row = mysql_fetch_array($sql2))
										{
											$stations = $row['stations'];
										}
										
										//$sql2 = mysql_query("select distinct loan_code from loan_application where loan_date between '$filter_start_date' and '$filter_end_date' and customer_station = '$customer_station' and loan_status != '12' and loan_status != '14' and loan_status != '11'");
										$sql2 = mysql_query("select distinct loan_code from loan_application where loan_date between '$filter_start_date' and '$filter_end_date' and customer_station = '$customer_station' and loan_status != '10' and loan_status != '9' and loan_status != '11' and loan_status != '12' and loan_status != '14' and loan_status != '15' and customer_station != '0' and loan_failure_status = '0' and loan_status = '7'");
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
										//Loan amount	interest+fees	Collected	((collected -Interest)-loan_amount)
										$ratio = ($total_repayments / $due)*100;
										$write_off = (($total_repayments - $fees) - $due);
										if($write_off < 0){
											$write_off = (abs($write_off));
										}
										else{
											$write_off = $write_off;
										}

										if($write_off > $due) { 
											$write_off = $due; 
											$lost_fees = $fees - $total_repayments;	
										} 
										else { 
											$write_off = $write_off; 
											$lost_fees = 0;	
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
										echo "<td align='right' valign='top'>".number_format($due, 2)."</td>";
										echo "<td align='right' valign='top'>".number_format($fees, 2)."</td>";
										echo "<td align='right' valign='top'>".number_format($total_repayments, 2)."</td>";
										echo "<td align='right' valign='top'>".number_format($lost_fees, 2)."</td>";
										echo "<td align='right' valign='top'>".number_format($write_off, 2)."</td>";
										//echo "<td align='right' valign='top'>".number_format($ratio, 2)."%</td>";						
										echo "</tr>";
										
										$total_due = $total_due  + $due;
										$total_fees = $total_fees  + $fees;
										$total_payments = $total_payments + $total_repayments;
										$total_write_off = $total_write_off  + $write_off;
										$total_lost_fees = $total_lost_fees  + $lost_fees;
										
										$amount = 0;
										$repayments = 0;
										$total_repayments = 0;
										$write_off = 0;
										$fees = 0;
										$fees_lost = 0;
									}
									$total_ratio = ($total_payments / $total_due) * 100;
									?>
								</tbody>
								<tr bgcolor = '#E6EEEE'>
									<td colspan='1'><strong>&nbsp;</strong></td>
									<td align='right' valign='top'><strong><?php echo number_format($total_due, 2) ?></strong></td>
									<td align='right' valign='top'><strong><?php echo number_format($total_fees, 2) ?></strong></td>
									<td align='right' valign='top'><strong><?php echo number_format($total_payments, 2) ?></strong></td>
									<td align='right' valign='top'><strong><?php echo number_format($total_lost_fees, 2) ?></strong></td>
									<td align='right' valign='top'><strong><?php echo number_format($total_write_off, 2) ?></strong></td>
								</tr>
							</table>
						<?php } else { ?>
							<table width="100%" border="0" cellspacing="2" cellpadding="2" class="display" id="exampl">
								<thead bgcolor="#E6EEEE">
									<tr>
										<!--<th>#</th>-->
										<th>Branch</th>
										<th>Loan Total</th>
										<th>Fees</th>
										<th>Collected</th>	
										<th>Write Off</th>
									</tr>
								</thead>
								<tbody>
								<?php
								
								//Loan_total	Collected	(collected-loan_total)
								//13500		6000		7500
								//if($station == '3'){
									$sql = mysql_query("select distinct customer_station, sum(loan_total_interest)due, sum(initiation_fee)init, sum(loan_interest)interest, sum(loan_late_interest)penalty, sum(loan_extension)extension from loan_application where loan_date between '$filter_start_date' and '$filter_end_date' and loan_status != '10' and loan_status != '9' and loan_status != '11' and loan_status != '12' and loan_status != '14' and loan_status != '15' and customer_station != '0' and loan_failure_status = '0' and loan_status = '7' group by customer_station order by customer_station asc");
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
										$init = $row['init'];
										$interest = $row['interest'];
										$penalty = $row['penalty'];
										$extension = $row['extension'];
										$fees = $init + $interest + $penalty + $extension;
										$customer_station = $row['customer_station'];
										
										$sql2 = mysql_query("select stations from stations where id = '$customer_station'");
										while ($row = mysql_fetch_array($sql2))
										{
											$stations = $row['stations'];
										}
										
										//$sql2 = mysql_query("select distinct loan_code from loan_application where loan_date between '$filter_start_date' and '$filter_end_date' and customer_station = '$customer_station' and loan_status != '12' and loan_status != '14' and loan_status != '11'");
										$sql2 = mysql_query("select distinct loan_code from loan_application where loan_date between '$filter_start_date' and '$filter_end_date' and customer_station = '$customer_station' and loan_status != '10' and loan_status != '9' and loan_status != '11' and loan_status != '12' and loan_status != '14' and loan_status != '15' and customer_station != '0' and loan_failure_status = '0' and loan_status = '7'");
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
										//Loan amount	interest+fees	Collected	((collected -Interest)-loan_amount)
										$ratio = ($total_repayments / $due)*100;
										$write_off = ($total_repayments - $due);
										if($write_off < 0){
											$write_off = (abs($write_off));
										}
										else{
											$write_off = $write_off;
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
										echo "<td align='right' valign='top'>".number_format($due, 2)."</td>";
										echo "<td align='right' valign='top'>".number_format($fees, 2)."</td>";
										echo "<td align='right' valign='top'>".number_format($total_repayments, 2)."</td>";
										echo "<td align='right' valign='top'>".number_format($write_off, 2)."</td>";
										//echo "<td align='right' valign='top'>".number_format($ratio, 2)."%</td>";						
										echo "</tr>";
										
										$total_due = $total_due  + $due;
										$total_fees = $total_fees  + $fees;
										$total_payments = $total_payments + $total_repayments;
										$total_write_off = $total_write_off  + $write_off;
										
										$amount = 0;
										$repayments = 0;
										$total_repayments = 0;
										$write_off = 0;
										$fees = 0;
									}
									$total_ratio = ($total_payments / $total_due) * 100;
									?>
								</tbody>
								<tr bgcolor = '#E6EEEE'>
									<td colspan='1'><strong>&nbsp;</strong></td>
									<td align='right' valign='top'><strong><?php echo number_format($total_due, 2) ?></strong></td>
									<td align='right' valign='top'><strong><?php echo number_format($total_fees, 2) ?></strong></td>
									<td align='right' valign='top'><strong><?php echo number_format($total_payments, 2) ?></strong></td>
									<td align='right' valign='top'><strong><?php echo number_format($total_write_off, 2) ?></strong></td>
								</tr>
							</table>
						<?php } ?>
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
