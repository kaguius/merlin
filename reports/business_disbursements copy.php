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
			$branch = $_GET['branch'];
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
					<table width="100%" border="0" cellspacing="2" cellpadding="2" class="display">
						<tr>
							<td width="50%" valign="top">
								<h3>Disbursements</h3>
								<table width="100%" border="0" cellspacing="2" cellpadding="2" class="display" id="exampl">
								<thead bgcolor="#E6EEEE">
									<tr>
										<th>#</th>
										<th>Date</th>
										<?php if($branch != ''){ ?>
											<th>Branch</th>
										<?php } ?>
										<th>Target</th>
										<th>Amount</th>
										<th>Variance</th>
										<th>Count</th>
									</tr>
								</thead>
								<tbody>
								<?php
									if($branch == ''){
										$sql = mysql_query("select distinct customer_station, loan_date, sum(loan_amount)loan_amount, count(loan_id)loans from loan_application where loan_date between '$filter_start_date' and '$filter_end_date' group by customer_station, loan_date order by loan_date asc");
									}
									else{
										 $sql = mysql_query("select distinct customer_station, loan_date, sum(loan_amount)loan_amount, count(loan_id)loans from loan_application where loan_date between '$filter_start_date' and '$filter_end_date' and customer_station = '$branch' group by customer_station, loan_date order by loan_date asc");
									}
									
									$intcount = 0;
									$total_loan_amount = 0;
									$total_loans = 0;
									while ($row = mysql_fetch_array($sql))
									{
										$intcount++;
										$loan_date = $row['loan_date'];
										$loan_amount = $row['loan_amount'];
										$loans = $row['loans'];
										$customer_station = $row['customer_station'];
										$sql2 = mysql_query("select stations, daily_target from stations where id = '$customer_station'");	
										while($row = mysql_fetch_array($sql2)) {
											$station_name = $row['stations'];
											$daily_target = $row['daily_target'];
										}
							
										$variance = ($loan_amount / $daily_target) * 100;
										if ($intcount % 2 == 0) {
											$display= '<tr bgcolor = #F0F0F6>';
										}
										else {
											$display= '<tr>';
										}
										echo $display;
										echo "<td valign='top'>$intcount.</td>";
										echo "<td valign='top'>$loan_date</td>";
										if($branch != ''){
											echo "<td valign='top'>$station_name</td>";
										}
										echo "<td align='right' valign='top'>".number_format($daily_target, 2)."</td>";
										echo "<td align='right' valign='top'>".number_format($loan_amount, 2)."</td>";
										echo "<td align='right' valign='top'>".number_format($variance, 2)."%</td>";
										echo "<td align='right' valign='top'>".number_format($loans, 2)."</td>";			
										echo "</tr>";
										
										$total_loan_target = $total_loan_target  + $daily_target;
										$total_loan_amount = $total_loan_amount  + $loan_amount;
										$total_loans = $total_loans + $loans;
									}
									$total_variance = ($total_loan_amount / $total_loan_target) * 100;
									?>
								</tbody>
								<tr bgcolor = '#E6EEEE'>
									<?php if($branch != ''){ ?>
										<td colspan='3'><strong>&nbsp;</strong></td>
									<?php } else { ?>
										<td colspan='2'><strong>&nbsp;</strong></td>
									<?php } ?>
									<td align='right' valign='top'><strong><?php echo number_format($total_loan_target, 2) ?></strong></td>
									<td align='right' valign='top'><strong><?php echo number_format($total_loan_amount, 2) ?></strong></td>
									<td align='right' valign='top'><strong><?php echo number_format($total_variance, 2) ?>%</strong></td>
									<td align='right' valign='top'><strong><?php echo number_format($total_loans, 2) ?></strong></td>
								</tr>
							</table>
						</td>
						<td width="50%" valign="top">
							<h3>Collections</h3>
							<table width="100%" border="0" cellspacing="2" cellpadding="2" class="display" id="exampl">
								<thead bgcolor="#E6EEEE">
									<tr>
										<th>#</th>
										<th>Date</th>
										<?php if($branch != ''){ ?>
										<th>Branch</th>
										<?php } ?>
										<th>Due</th>
										<th>Collected</th>
										<th>%</th>
									</tr>
								</thead>
								<tbody>
								<?php
									//if($branch == ''){
									//	$sql = mysql_query("select distinct customer_station, loan_due_date, sum(loan_total_interest)due from loan_application where loan_date between '$filter_start_date' and '$filter_end_date' group by customer_station, loan_due_date order by loan_due_date");
									//}
									//else{
									//	 $sql = mysql_query("select distinct customer_station, loan_due_date, sum(loan_total_interest)due from loan_application where loan_date between '$filter_start_date' and '$filter_end_date' and customer_station = '$branch' group by customer_station, loan_due_date order by loan_due_date");
									//}
									
									if($branch == ''){
										$sql = mysql_query("select distinct customer_station, loan_due_date, sum(loan_total_interest)due from loan_application where loan_due_date between '$filter_start_date' and '$filter_end_date' group by loan_due_date order by loan_due_date asc");
									}
									else{
										 $sql = mysql_query("select distinct customer_station, loan_due_date, sum(loan_total_interest)due from loan_application where loan_due_date between '$filter_start_date' and '$filter_end_date' and customer_station = '$branch' group by loan_due_date order by loan_due_date asc");
									}
									
									$intcount = 0;
									$total_due = 0;
									$total_payments = 0;
									$total_ratio = 0;
									while ($row = mysql_fetch_array($sql))
									{
										$intcount++;
										$loan_due_date = $row['loan_due_date'];
										$due = $row['due'];
										$customer_station = $row['customer_station'];
										
										$sql2 = mysql_query("select distinct loan_code from loan_application where loan_due_date = '$loan_due_date'");
										while ($row = mysql_fetch_array($sql2))
										{
											$loan_code = $row['loan_code'];
											$sql3 = mysql_query("select sum(loan_rep_amount)repayments from loan_repayments where loan_rep_code = '$loan_code' and customer_station = '$customer_station' group by loan_rep_code");
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
										
										$sql2 = mysql_query("select stations from stations where id = '$customer_station'");	
										while($row = mysql_fetch_array($sql2)) {
											$station_name = $row['stations'];
										}
										
										if ($intcount % 2 == 0) {
											$display= '<tr bgcolor = #F0F0F6>';
										}
										else {
											$display= '<tr>';
										}
										echo $display;
										echo "<td valign='top'>$intcount.</td>";
										echo "<td valign='top'>$loan_due_date</td>";
										if($branch != ''){
											echo "<td valign='top'>$station_name</td>";
										}
										echo "<td align='right' valign='top'>".number_format($due, 2)."</td>";
										echo "<td align='right' valign='top'>".number_format($total_repayments, 2)."</td>";
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
									<?php if($branch != ''){ ?>
										<td colspan='3'><strong>&nbsp;</strong></td>
									<?php } else { ?>
										<td colspan='2'><strong>&nbsp;</strong></td>
									<?php } ?>
									<td align='right' valign='top'><strong><?php echo number_format($total_due, 2) ?></strong></td>
									<td align='right' valign='top'><strong><?php echo number_format($total_payments, 2) ?></strong></td>
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
							
							<tr >
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
