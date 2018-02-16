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
		$page_title = "EDC Loans Listing";
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
								<th colspan="4">&nbsp;</th>
								<th colspan="2">Business Details</th>
								<th>Residential</th>
								<th colspan="3">Reference 1</th>
								<th colspan="3">Reference 2</th>
								<th colspan="9">Loan Details</th>
							</tr>
							<tr>
								<th>ID</th>
								<th>Customer</th>
								<th>Phone</th>
								<th>Branch</th>
								<th>Business</th>
								<th>Type</th>
								<th>Residence</th>
								<th>Rship</th>
								<th>Name</th>
								<th>Number</th>
								<th>Rship</th>
								<th>Name</th>
								<th>Number</th>
								<th>EDC</th>
								<th>Code</th>
								<th>Date</th>
								<th>Due</th>
								<th>Amount</th>
								<th>Fees</th>
								<th>Total</th>
								<th>Repayments</th>
								<th>Balance</th>
							</tr>
						</thead>
						<tbody>
						<?php

						$sql = mysql_query("select loan_id, loan_date, loan_due_date, loan_mobile, customer_id, customer_station, loan_status, loan_mpesa_code, loan_code, initiation_fee, loan_amount, loan_extension, loan_interest, loan_late_interest, admin_fee, appointment_fee, early_settlement, early_settlement_surplus, fix, early_settlement_surplus, loan_total_interest, waiver, edc from loan_application where loan_date between '$filter_start_date' and '$filter_end_date' and edc != '0' and Loan_status != '13' order by loan_date asc");
						while ($row = mysql_fetch_array($sql))
						{
							$intcount++;
							$loan_id = $row['loan_id'];
							$loan_date = $row['loan_date'];
							$loan_due_date = $row['loan_due_date'];
							$loan_mobile = $row['loan_mobile'];
							$customer_id = $row['customer_id'];
							$customer_station = $row['customer_station'];
							$loan_status = $row['loan_status'];
							$loan_mpesa_code = $row['loan_mpesa_code'];
							$loan_code = $row['loan_code'];
							$initiation_fee = $row['initiation_fee'];
							$loan_amount = $row['loan_amount'];
							$loan_extension = $row['loan_extension'];
							$loan_interest = $row['loan_interest'];
							$loan_late_interest = $row['loan_late_interest'];
							$admin_fee = $row['admin_fee'];
							$appointment_fee = $row['appointment_fee'];
							$early_settlement = $row['early_settlement'];
							$early_settlement_surplus = $row['early_settlement_surplus'];
							$fix = $row['fix'];
							$early_settlement_surplus = $row['early_settlement_surplus'];
							$loan_total_interest = $row['loan_total_interest'];
							$edc = $row['edc'];
							$waiver = $row['waiver'];
	
							$loan_total_interest_calculated = $loan_amount + $initiation_fee + $loan_late_interest + $loan_interest + $admin_fee + $appointment_fee + $early_settlement + $early_settlement_surplus + $fix + $joining_fee + $waiver;
	
							$sql2 = mysql_query("select sum(loan_rep_amount)repayments from loan_repayments where loan_rep_code = '$loan_code' group by loan_rep_code");
							while ($row = mysql_fetch_array($sql2))
							{
								$repayments = $row['repayments'];
								if($repayments == ''){
									$repayments = 0;
								}
							}
		
							$sql2 = mysql_query("select first_name, last_name, national_id, home_address, ref_first_name, ref_last_name, ref_phone_number, ref_relationship, ref_landlord_first_name, ref_landlord_last_name, ref_landlord_relationship, ref_landlord_phone from users where id = '$customer_id'");
							while ($row = mysql_fetch_array($sql2))
							{
								$first_name = $row['first_name'];
								$last_name = $row['last_name'];
								$first_name = ucwords(strtolower($first_name));	
								$last_name = ucwords(strtolower($last_name));
								$customer_name = $first_name.' '.$last_name;	
								$national_id = $row['national_id'];	
								$home_address = $row['home_address'];	
								$ref_first_name = $row['ref_first_name'];	
								$ref_last_name = $row['ref_last_name'];	
								$ref_phone_number = $row['ref_phone_number'];	
								$ref_relationship = $row['ref_relationship'];	
								$ref_one_name = $ref_first_name.' '.$ref_last_name;	
								$ref_landlord_first_name = $row['ref_landlord_first_name'];	
								$ref_landlord_last_name = $row['ref_landlord_last_name'];	
								$ref_landlord_relationship = $row['ref_landlord_relationship'];	
								$ref_landlord_phone = $row['ref_landlord_phone'];	
								$ref_two_name = $ref_landlord_first_name.' '.$ref_landlord_last_name;	
							}
							$sql2 = mysql_query("select first_name, last_name from user_profiles where id = '$edc'");
							while ($row = mysql_fetch_array($sql2))
							{
								$first_name = $row['first_name'];
								$last_name = $row['last_name'];
								$first_name = ucwords(strtolower($first_name));	
								$last_name = ucwords(strtolower($last_name));
								$edc_name = $first_name.' '.$last_name;		
							}
		
							$sql2 = mysql_query("select stations from stations where id = '$customer_station'");
							while ($row = mysql_fetch_array($sql2))
							{
								$stations_name = $row['stations'];	
							}
		
							$sql2 = mysql_query("select status from customer_status where id = '$loan_status'");
							while ($row = mysql_fetch_array($sql2))
							{
								$status_name = $row['status'];	
							}
						
							$sql2 = mysql_query("select business.business, business_address from business_details inner join business on business.id = business_details.business_category where user_id = '$customer_id'");
							while ($row = mysql_fetch_array($sql2))
							{
								$business = $row['business'];	
								$business_address = $row['business_address'];	
							}
							
							$allocation_fees = $waiver + $admin_fee + $appointment_fee + $early_settlement + $early_settlement_surplus + $fix + $joining_fee;
                            $fees = $loan_extension + $initiation_fee + $loan_late_interest + $allocation_fees + $loan_interest;
							$balance = $loan_total_interest_calculated - $repayments;
		
							if ($intcount % 2 == 0) {
								$display= '<tr bgcolor = #F0F0F6>';
							}
							else {
								$display= '<tr>';
							}
							echo $display;
							echo "<td valign='top'>$national_id</td>";
							echo "<td valign='top'>$customer_name</td>";
							echo "<td valign='top'>$loan_mobile</td>";
							echo "<td valign='top'>$stations_name</td>";
							echo "<td valign='top'>$business</td>";
							echo "<td valign='top'>$business_address</td>";
							echo "<td valign='top'>$home_address</td>";
							echo "<td valign='top'>$ref_relationship</td>";
							echo "<td valign='top'>$ref_one_name</td>";
							echo "<td valign='top'>$ref_phone_number</td>";
							echo "<td valign='top'>$ref_landlord_relationship</td>";
							echo "<td valign='top'>$ref_two_name</td>";
							echo "<td valign='top'>$ref_landlord_phone</td>";
							echo "<td valign='top'>$edc_name</td>";
							echo "<td valign='top'>$loan_code</td>";
							echo "<td valign='top'>$loan_date</td>";
							echo "<td valign='top'>$loan_due_date</td>";
							echo "<td align='right' valign='top'>".number_format($loan_amount, 2)."</td>";
							echo "<td align='right' valign='top'>".number_format($fees, 2)."</td>";
							echo "<td align='right' valign='top'>".number_format($loan_total_interest, 2)."</td>";	
							echo "<td align='right' valign='top'>".number_format($repayments, 2)."</td>";		
							echo "<td align='right' valign='top'>".number_format($balance, 2)."</td>";				
							echo "</tr>";
							$loan_amount = 0;
							$fees = 0;
							$loan_total_interest = 0;
							$repayments = 0;
							$balance = 0;
						}
							?>
						</tbody>
						<tfoot bgcolor="#E6EEEE">
						<tr>
							<th>ID</th>
							<th>Customer</th>
							<th>Phone</th>
							<th>Branch</th>
							<th>Business</th>
							<th>Type</th>
							<th>Residence</th>
							<th>Rship</th>
							<th>Name</th>
							<th>Number</th>
							<th>Rship</th>
							<th>Name</th>
							<th>Number</th>
							<th>EDC</th>
							<th>Code</th>
							<th>Date</th>
							<th>Due</th>
							<th>Amount</th>
							<th>Fees</th>
							<th>Total</th>
							<th>Repayments</th>
							<th>Balance</th>
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
