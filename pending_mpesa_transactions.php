<?php
	$userid = "";
	$adminstatus = 4;
	$property_manager_id = "";
	session_start();
	if (!empty($_SESSION)){
		$userid = $_SESSION["userid"] ;
		$adminstatus = $_SESSION["adminstatus"] ;
		$username = $_SESSION["username"];
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
		$page_title = "Pending MPESA Transactions";
		include_once('includes/header.php');
		$filter_clerk = 0;
		if (!empty($_GET)){	
			$agent = $_GET['agent'];
			$filter_start_date = $_GET['report_start_date'];
			$filter_start_date = date('Y-m-d', strtotime(str_replace('-', '/', $filter_start_date)));
			$filter_end_date = $_GET['report_end_date'];
			$filter_end_date = date('Y-m-d', strtotime(str_replace('-', '/', $filter_end_date)));
		}
		include_once('includes/db_conn.php');
			
		?>
		<div id="page">
			<div id="content">
				<div class="post">
					<h2><?php echo $page_title ?></h2>
					<table width="100%" border="0" cellspacing="2" cellpadding="2" class="display" id="example3">
					<thead bgcolor="#E6EEEE">
						<tr>
							<th>#</th>
							<th>Loan Code</th>
							<th>Phone</th>
							<th>Amount</th>
							<th>Time</th>
						</tr>
					</thead>
					<tbody>
					<?php
						 $sql = mysql_query("select id, loan_code, msisdn, amount, carrier, transactiontime from mobile_money_requests where new = '0' and carrier = '1' and customer_station != '5' order by transactiontime desc");
						 $intcount = 0;
						 $total_counts = 0;
						 while ($row = mysql_fetch_array($sql))
						 {
							$intcount++;
							$loan_code = $row['loan_code'];
							$msisdn = $row['msisdn'];
							$amount = $row['amount'];
							$transactiontime = $row['transactiontime'];
							
							if ($intcount % 2 == 0) {
								$display= '<tr bgcolor = #F0F0F6>';
							}
							else {
								$display= '<tr>';
							}
							echo $display;
							echo "<td valign='top'>$intcount.</td>";
							echo "<td valign='top'>$loan_code</td>";
							echo "<td valign='top'>$msisdn</td>";
							echo "<td align='right' valign='top'>".number_format($amount, 2)."</td>";
							echo "<td valign='top'>$transactiontime</td>";
							echo "</tr>";
						}
						?>
					</tbody>
					<tfoot bgcolor="#E6EEEE">
						<tr>
							<th>#</th>
							<th>Loan Code</th>
							<th>Phone</th>
							<th>Amount</th>
							<th>Time</th>
						</tr>
					</tfoot>
				</table>
			</div>
			<br class="clearfix" />
			</div>
		</div>
<?php
	}
	include_once('includes/footer.php');
?>
