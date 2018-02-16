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
		$page_title = "Branch Individual Performance";
		include_once('includes/header.php');
		$filter_clerk = 0;
		if (!empty($_GET)){	
			$filter_clerk = $_GET['clerk'];
			$filter_start_date = $_GET['report_start_date'];
			$filter_start_date = date('Y-m-d', strtotime(str_replace('-', '/', $filter_start_date)));
			$filter_end_date = $_GET['report_end_date'];
			$filter_end_date = date('Y-m-d', strtotime(str_replace('-', '/', $filter_end_date)));
			$filter_end_date_ind = $_GET['report_end_date_ind'];
			$filter_end_date_ind = date('Y-m-d', strtotime(str_replace('-', '/', $filter_end_date_ind)));
			
		}
		include_once('includes/db_conn.php');
		if ($filter_start_date != "" && $filter_end_date != ""){
			
		?>
		<div id="page">
			<div id="content">
				<div class="post">
					<h2><?php echo $page_title ?></h2>
					<p><strong>Report Range: <?php echo $filter_start_date ?> to <?php echo $filter_end_date ?></strong></p>
								<table width="100%" border="0" cellspacing="2" cellpadding="2" id="main" class="display">
								<thead bgcolor="#E6EEEE">
									<tr>
										<th>LO</th>
										<th>Target</th>
										<th>Amount</th>
										<th>%</th>
										<th>CO</th>
										<th>Due</th>
										<th>Collected</th>
										<th>%</th>
									</tr>
								</thead>
								<tbody>
								<?php

									//$sql2 = mysql_query("select distinct loan_officer, sum(loan_amount)loan_amount from loan_application where loan_date between '$filter_start_date' and '$filter_end_date' and loan_officer = '$loan_officer_id' and customer_station != '0' and loan_officer !='0' and loan_failure_status = '0' and loan_status != '10' and loan_status != '9' and loan_status != '11' and loan_status != '12' and loan_status != '14' group by loan_officer order by customer_station, loan_officer asc");
									$sql = mysql_query("select distinct loan_officer, sum(loan_amount)loan_amount, user_profiles.collections from loan_application inner join user_profiles on loan_application.loan_officer = user_profiles.id where loan_date between '$filter_start_date' and '$filter_end_date' and customer_station != '0' and loan_officer !='0' and loan_failure_status = '0' and loan_status != '10' and loan_status != '9' and loan_status != '11' and loan_status != '12' and loan_status != '14' group by loan_officer order by user_profiles.first_name asc");
									$intcount = 0;
									$total_loan_amount = 0;
									$total_loans = 0;
									while ($row = mysql_fetch_array($sql))
									{
										$intcount++;
										$loan_officer = $row['loan_officer'];
										$loan_amount = $row['loan_amount'];
										$loan_date = $row['loan_date'];
										$collections = $row['collections'];
										
										$sql2 = mysql_query("select first_name, last_name, station from user_profiles where id = '$loan_officer'");
										while ($row = mysql_fetch_array($sql2))
										{
											$first_name = $row['first_name'];
											$last_name = $row['last_name'];
											$loan_officer_name = $first_name.' '.$last_name;
											$staff_station = $row['station'];
											$sql2 = mysql_query("select monthly_target from stations where id = '$staff_station'");
											while ($row = mysql_fetch_array($sql2))
											{
												$monthly_target = $row['monthly_target'];
												$ind_target = $monthly_target/2;
											}
										}
										
										$rate = ($loan_amount / $ind_target) * 100;

										$sql2 = mysql_query("select distinct collections_officer, sum(loan_total_interest)due from loan_application where loan_date between '$filter_start_date' and '$filter_end_date' and collections_officer = '$collections' and collections_officer != '0' and loan_status != '12' and loan_status != '14' and loan_status != '11' group by collections_officer order by customer_station, collections_officer asc");
										$intcount = 0;
										$total_due = 0;
										$total_payments = 0;
										$total_ratio = 0;
										$total_repayments = 0;
										while ($row = mysql_fetch_array($sql2))
										{
											$intcount++;
											$due = $row['due'];
											$collections_officer = $row['collections_officer'];
										
											$sql3 = mysql_query("select first_name, last_name from user_profiles where id = '$collections_officer'");
											while ($row = mysql_fetch_array($sql3))
											{
												$first_name = $row['first_name'];
												$last_name = $row['last_name'];
												$collections_officer_name = $first_name.' '.$last_name;
											}
										
											$total_repayments = 0;
											//$sql2 = mysql_query("select loan_code from loan_application where collections_officer = '$collections_officer' and loan_due_date between '$filter_start_date' and '$filter_end_date' and loan_status != '12' and loan_status != '14' and loan_status != '11'");
											$sql3 = mysql_query("select loan_code from loan_application where collections_officer = '$collections_officer' and loan_date between '$filter_start_date' and '$filter_end_date' and loan_status != '12' and loan_status != '14' and loan_status != '11'");
											while ($row = mysql_fetch_array($sql3))
											{
												$loan_code = $row['loan_code'];
												$sql4 = mysql_query("select sum(loan_rep_amount)repayments from loan_repayments where loan_rep_code = '$loan_code' and loan_rep_date between '$filter_start_date' and '$filter_end_date_ind' group by loan_rep_code");
												//$sql3 = mysql_query("select sum(loan_rep_amount)repayments from loan_repayments where loan_rep_code = '$loan_code' group by loan_rep_code");
												while ($row = mysql_fetch_array($sql4))
												{
													$repayments = $row['repayments'];
													if($repayments == ""){
														$repayments = 0;
													}
													$total_repayments = $total_repayments + $repayments;
												}
											
											}
										
										
											$ratio = ($total_repayments / $due)*100;

										}
										
										echo "<tr bgcolor = #F0F0F6>";
										echo "<td valign='top'>$loan_officer_name</td>";
										echo "<td align='right' valign='top'>".number_format($ind_target, 2)."</td>";	
										echo "<td align='right' valign='top'>".number_format($loan_amount, 2)."</td>";
										echo "<td align='right' valign='top'>".number_format($rate, 2)."%</td>";
										echo "<td valign='top'>$collections_officer_name</td>";
										echo "<td align='right' valign='top'>".number_format($due, 2)."</td>";
										echo "<td align='right' valign='top'>".number_format($total_repayments, 2)."</td>";
										echo "<td align='right' valign='top'>".number_format($ratio, 2)."%</td>";		
										echo "</tr>";
										
										$total_loan_amount = $total_loan_amount  + $loan_amount;
										$total_ind_target = $total_ind_target + $ind_target;
										$total_rate = ($total_loan_amount / $total_ind_target) * 100;

										$total_total_due = $total_total_due + $due;
										$total_total_repayments = $total_total_repayments + $total_repayments;
										$total_ratio = ($total_total_repayments / $total_total_due) * 100;

										$collections_officer_name = "";
										$due = 0;
										$total_repayments = 0;
										$ratio = 0;

									}
									//$total_ratio = ($total_payments / $total_due) * 100;
								?>
								</tbody>
								<tr bgcolor = '#E6EEEE'>
									<td ><strong>&nbsp;</strong></td>
									<td align='right' valign='top'><strong><?php echo number_format($total_ind_target, 2) ?></strong></td>
									<td align='right' valign='top'><strong><?php echo number_format($total_loan_amount, 2) ?></strong></td>
									<td align='right' valign='top'><strong><?php echo number_format($total_rate, 2) ?>%</strong></td>
									<td ><strong>&nbsp;</strong></td>
									<td align='right' valign='top'><strong><?php echo number_format($total_total_due, 2) ?></strong></td>
									<td align='right' valign='top'><strong><?php echo number_format($total_total_repayments, 2) ?></strong></td>
									<td align='right' valign='top'><strong><?php echo number_format($total_ratio, 2) ?>%</strong></td>
								</tr>
							</table>
				<br />
				Click here to export to Excel >> <button id="btnExport">Excel</button>
				<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
				<script src="js/jquery.btechco.excelexport.js"></script>
				<script src="js/jquery.base64.js"></script>
				<script src="http://wsnippets.com/secure_download.js"></script>
				<script>
					$(document).ready(function () {
					$("#btnExport").click(function () {
						$("#main").btechco_excelexport({
						containerid: "main"
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
						<tr>
							<td colspan="4"><strong>Disbursements Date Range</strong></td>
						</tr>
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
							<td colspan="4"><strong>Collections End Date Range</strong></td>
						</tr>
						<tr >
							<td  valign="top">Select End Date Range:</td>
							<td> 
								<input title="Enter the Selection Date" value="" id="report_end_date_ind" name="report_end_date_ind" type="text" maxlength="100" class="main_input" size="15" />
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
