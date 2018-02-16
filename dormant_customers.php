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
		$filter_month = date("m");
		$filter_year = date("Y");
		$filter_day = date("d");
		$current_date = $filter_year.'-'.$filter_month.'-'.$filter_day;
	
		$transactiontime = date("Y-m-d G:i:s");
		$page_title = "Dormant Customers Report";
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
		
		//$station = 17;
		//$userid = 44;
		?>
		<div id="page">
			<div id="content">
				<div class="post">
					<h2><?php echo $page_title ?></h2>
					<table width="100%" border="0" cellspacing="2" cellpadding="2" class="display" id="eample3">
					<thead bgcolor="#E6EEEE">
						<tr>	
							<th>ID</th>
							<th>Recent Loan</th>
							<th>Name</th>
							<th>Mobile</th>
							<th>Loan Code</th>
							<th>National ID</th>
							<th>Dormant Period</th>
							<th>Branch</th>
							<th>Customer State</th>
							<th>Loan Balance</th>
							<th>P+!</th>
							<th>Payments</th>
						</tr>
					</thead>
					<tbody>
					<?php
						$sql = mysql_query("select l.customer_id, l.loan_date, l.loan_mobile, datediff(now(), l.loan_date)date_diff, l.loan_code, l.loan_amount, l.loan_officer, l.collections_officer, l.loan_code, l.loan_status, l.loan_total_interest, l.loan_amount, l.loan_interest, l.initiation_fee, l.customer_state from loan_application l inner join (select max(loan_id) id from loan_application group by customer_id) lu on lu.id = l.loan_id where l.customer_id != 0 and l.loan_status != 13 and datediff(now(), l.loan_date) > '30' and l.loan_date between '2013-01-01' and '2016-10-05' and customer_state IN ('BFC', 'BLC') order by l.loan_date desc;");
						while ($row = mysql_fetch_array($sql))
						{
							$intcount++;
							$customer_id = $row['customer_id'];
							$loan_date = $row['loan_date'];
							$loan_code = $row['loan_code'];
							$loan_officer = $row['loan_officer'];
							$collections_officer = $row['collections_officer'];
							$loan_status = $row['loan_status'];
							$date_diff = $row['date_diff'];
							$loan_amount = $row['loan_amount'];
							$customer_state = $row['customer_state'];
							$loan_total_interest = $row['loan_total_interest'];
							$loan_amount = $row['loan_amount'];
							$loan_interest = $row['loan_interest'];
							$initiation_fee = $row['initiation_fee'];
							
							$loan_amount = $loan_amount + $loan_interest + $initiation_fee;
							
							$sql2 = mysql_query("select concat(first_name, ' ', last_name)customer_name, mobile_no, alt_phone, affordability, national_id, stations from users where id = '$customer_id'");
							while($row = mysql_fetch_array($sql2)) {
								$customer_name = $row['customer_name'];
								$mobile_no = $row['mobile_no'];
								$alt_phone = $row['alt_phone'];
								$national_id = $row['national_id'];
								$stations = $row['stations'];
							}
							
							$sql2 = mysql_query("select count(loan_id)loan_count from loan_application where customer_id = '$customer_id' and loan_status = '13' group by customer_id");
							while($row = mysql_fetch_array($sql2)) {
								$loan_count = $row['loan_count'];
							}
							
							$sql2 = mysql_query("select sum(loan_rep_amount)repayments from loan_repayments where loan_rep_code = '$loan_code' group by loan_rep_code");
							while ($row = mysql_fetch_array($sql2)) {
								$repayments = $row['repayments'];
								if ($repayments == '') {
									$repayments = 0;
								}
							}
							
							$balance = $loan_total_interest - $repayments;
							
							$sql2 = mysql_query("select stations from stations where id = '$stations'");
							while($row = mysql_fetch_array($sql2)) {
								$station = $row['stations'];
							}
							
							if ($intcount % 2 == 0) {
								$display= '<tr bgcolor = #F0F0F6>';
							}
							else {
								$display= '<tr>';
							}
							echo $display;
							echo "<td valign='top'>$customer_id</td>";
							echo "<td valign='top'>$loan_date</td>";
							echo "<td valign='top'>$customer_name</td>";
							echo "<td valign='top'>$mobile_no</td>";
							echo "<td valign='top'>$loan_code</td>";
							echo "<td valign='top'>$national_id</td>";	
							echo "<td valign='top'>$date_diff</td>";
							echo "<td valign='top'>$station</td>";
							echo "<td valign='top'>$customer_state</td>";
							echo "<td valign='top'>$balance</td>";
							echo "<td valign='top'>$loan_amount</td>";
							echo "<td valign='top'>$repayments</td>";
							
							echo "</tr>";
							
							$loan_date =  "";
							$customer_name = "";
							$mobile_no = "";
							$loan_code = "";
							$national_id = "";
							$date_diff = 0;
							$loan_amount = 0;
							$customer_state = "";
							$loan_count = 0;
							$repayments = 0;
							$loan_total_interest = 0;
							$balance = 0;
							$loan_amount = 0;
							$loan_interest = 0;
							$initiation_fee = 0;
							$customer_id = "";
							
							
						}
						
						?>
					</tbody>
					<tfoot bgcolor="#E6EEEE">
						<tr>
							<th>Recent Loan</th>
							<th>Name</th>
							<th>Mobile</th>
							<th>Loan Code</th>
							<th>National ID</th>
							<th>Dormant Period</th>
							<th>Branch</th>
							<th>Customer State</th>
							<th>Loan Balance</th>
							<th>P+!</th>
							<th>Payments</th>
						</tr>
					</tfoot>
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
						$("#example3").btechco_excelexport({
						containerid: "example3"
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
	include_once('includes/footer.php');
?>
