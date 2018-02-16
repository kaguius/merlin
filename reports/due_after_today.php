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
		$page_title = "Accounts: Loans DD+1 to DD+14";
		include_once('includes/header.php');
		include_once('includes/db_conn.php');
		
		$filter_month = date("m");
		$filter_year = date("Y");
		$filter_day = date("d");
		$current_date = $filter_year.'-'.$filter_month.'-'.$filter_day;
		
		$report_term = 15;
		$end_report_date = date('Y-m-d',strtotime($current_date) - (24 * 3600 * $report_term));
			
		?>
		<div id="page">
			<div id="content">
				<div class="post">
					<h2><?php echo $page_title ?></h2>
					<table width="100%" border="0" cellspacing="2" cellpadding="2" class="display" id="example3">
					<thead bgcolor="#E6EEEE">
						<tr>
							<th>#</th>
							<th>Customer</th>
							<th>ID</th>
							<th>Dis Phone</th>
							<th>Mobile</th>
							<th>Disbursed</th>
							<th>Due</th>
							<th>LO</th>
							<th>CO</th>
							<th>Days</th>
							<th>Code</th>
							<th>Branch</th>
							<th>Amount</th>
							<th>Payment</th>
							<th>Balance</th>
						</tr>
					</thead>
					<tbody>
					<?php
						if($station == '3'){
							 $sql = mysql_query("select loan_date, loan_due_date, customer_id, customer_station, loan_mobile, loan_code, loan_total_interest, loan_officer, collections_officer from loan_application where loan_due_date between '$end_report_date' and '$current_date' and loan_status != '13' and loan_status != '9' and loan_status != '10' and loan_status != '11' and loan_status != '12' and loan_status != '14' and loan_status != '15' order by loan_due_date asc");
						}
						else{
							  $sql = mysql_query("select loan_date, loan_due_date, customer_id, customer_station, loan_mobile, loan_code, loan_total_interest, loan_officer, collections_officer from loan_application where loan_due_date between '$end_report_date' and '$current_date' and customer_station = '$station' and loan_status != '13' and loan_status != '9' and loan_status != '10' and loan_status != '11' and loan_status != '12' and loan_status != '14' and loan_status != '15' order by loan_due_date asc");
						}
						
						 $intcount = 0;
						 $total_loan_total_interest = 0;
						 $total_payments = 0;
						 $total_balance = 0;
						 while ($row = mysql_fetch_array($sql))
						 {
							$intcount++;
							$loan_date = $row['loan_date'];
							$loan_due_date = $row['loan_due_date'];
							$customer_id = $row['customer_id'];
							$customer_station = $row['customer_station'];
							$loan_mobile = $row['loan_mobile'];
							$loan_code = $row['loan_code'];
							$loan_total_interest = $row['loan_total_interest'];
							$loan_officer = $row['loan_officer'];
							$collections_officer = $row['collections_officer'];
							
							$date1 = strtotime($loan_due_date);
							$date2 = strtotime($current_date);
							$dateDiff = $date1 - $date2;
							$days = floor($dateDiff/(60*60*24));
							
							$days = $days * -1;
							
							$sql2 = mysql_query("select first_name, last_name, national_id, mobile_no from users where id = '$customer_id'");
							while ($row = mysql_fetch_array($sql2))
							{
								$first_name = $row['first_name'];
								$first_name = ucwords(strtolower($first_name));
								$last_name = $row['last_name'];
								$last_name = ucwords(strtolower($last_name));
								$national_id = $row['national_id'];
								$mobile_no = $row['mobile_no'];
								$customer_name = $first_name." ".$last_name;
							}
							
							$sql2 = mysql_query("select first_name, last_name from user_profiles where id = '$loan_officer'");
							while ($row = mysql_fetch_array($sql2))
							{
								$first_name = $row['first_name'];
								$last_name = $row['last_name'];
								$loan_officer_name = $first_name." ".$last_name;
							}
							
							$sql2 = mysql_query("select first_name, last_name from user_profiles where id = '$collections_officer'");
							while ($row = mysql_fetch_array($sql2))
							{
								$first_name = $row['first_name'];
								$last_name = $row['last_name'];
								$collections_officer_name = $first_name." ".$last_name;
							}
							
							$sql2 = mysql_query("select stations from stations where id = '$customer_station'");
							while ($row = mysql_fetch_array($sql2))
							{
								$stations = $row['stations'];
							}
							
							$sql2 = mysql_query("select sum(loan_rep_amount)payments from loan_repayments where loan_rep_code = '$loan_code' group by loan_rep_code");
							while ($row = mysql_fetch_array($sql2))
							{
								$payments = $row['payments'];
							}
							
							$balance = $loan_total_interest - $payments;
							
							if ($intcount % 2 == 0) {
								$display= '<tr bgcolor = #F0F0F6>';
							}
							else {
								$display= '<tr>';
							}
							echo $display;
							echo "<td valign='top'>$intcount.</td>";
							echo "<td valign='top'>$customer_name</td>";
							echo "<td valign='top'>$national_id </td>";
							echo "<td valign='top'>$loan_mobile</td>";	
							echo "<td valign='top'>$mobile_no</td>";	
								
							echo "<td valign='top'>$loan_date</td>";	
							echo "<td valign='top'>$loan_due_date</td>";
							echo "<td valign='top'>$loan_officer_name</td>";	
							echo "<td valign='top'>$collections_officer_name</td>";		
							echo "<td valign='top'>$days</td>";	
							echo "<td valign='top'>$loan_code</td>";	
							echo "<td valign='top'>$stations</td>";	
							echo "<td valign='top' align='right'>".number_format($loan_total_interest, 2)."</td>";		
							echo "<td valign='top' align='right'>".number_format($payments, 2)."</td>";		
							echo "<td valign='top' align='right'>".number_format($balance, 2)."</td>";			
							echo "</tr>";
							
							$total_loan_total_interest = $total_loan_total_interest + $loan_total_interest;
							$total_payments = $total_payments + $payments;
							$total_balance = $total_balance + $balance;
							$collections_officer_name = "";
							$loan_officer_name = "";
							$payments = 0;
						}
						?>
					</tbody>
					<tr bgcolor = '#E6EEEE'>
						<td colspan='12'><strong>&nbsp;</strong></td>
						<td align='right' valign='top'><strong><?php echo number_format($total_loan_total_interest, 2) ?></strong></td>
						<td align='right' valign='top'><strong><?php echo number_format($total_payments, 2) ?></strong></td>
						<td align='right' valign='top'><strong><?php echo number_format($total_balance, 2) ?></strong></td>
					</tr>
					<tfoot bgcolor="#E6EEEE">
						<tr>
							<th>#</th>
							<th>Customer</th>
							<th>ID</th>
							<th>Dis Phone</th>
							<th>Mobile</th>
							<th>Disbursed</th>
							<th>Due</th>
							<th>LO</th>
							<th>CO</th>
							<th>Days</th>
							<th>Code</th>
							<th>Branch</th>
							<th>Amount</th>
							<th>Payment</th>
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
						$("#example3").btechco_excelexport({
						containerid: "example3"
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
	include_once('includes/footer.php');
?>
