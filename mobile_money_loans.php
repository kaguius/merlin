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
		$page_title = "Mobile Money Reconciliation: Loan Application";
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
					<table width="100%" border="0" cellspacing="2" cellpadding="2" class="display" id="example2">
					<thead bgcolor="#E6EEEE">
						<tr>
							<th>#</th>
							<th>Date</th>
							<th>Branch</th>
							<th>Customer ID</th>
							<th>Name</th>
							<th>Phone Number</th>
							<th>Loan Code</th>
							<th>Loan Amount</th>
							<th>MM transaction number</th>
						</tr>
					</thead>
					<tbody>
					<?php
						if($station == '3'){
							$sql = mysql_query("select loan_date, customer_station, loan_mobile, customer_id, loan_code, loan_amount, loan_mpesa_code from loan_application where loan_date between '$filter_start_date' and '$filter_end_date' and loan_status != '12' order by loan_date asc");
						}
						else{
							$sql = mysql_query("select loan_date, customer_station, loan_mobile, customer_id, loan_code, loan_amount, loan_mpesa_code from loan_application where loan_date between '$filter_start_date' and '$filter_end_date' and customer_station = '$stations' and loan_status != '12' order by loan_date asc");
						}
						
						while ($row = mysql_fetch_array($sql))
						{
							$intcount++;
							$loan_date = $row['loan_date'];
							$customer_station = $row['customer_station'];
							$customer_id = $row['customer_id'];
							$loan_code = $row['loan_code'];
							$loan_amount = $row['loan_amount'];
							$loan_mpesa_code = $row['loan_mpesa_code'];
							$loan_mobile = $row['loan_mobile'];
							
							$sql2 = mysql_query("select id, stations from stations where id = '$customer_station'");
							while($row = mysql_fetch_array($sql2)) {
								$stations = $row['stations'];
								$stations = ucwords(strtolower($stations));
							}
							$sql2 = mysql_query("select first_name, last_name, national_id from users where id = '$customer_id'");
							while($row = mysql_fetch_array($sql2)) {
								$first_name = $row['first_name'];
								$first_name = ucwords(strtolower($first_name));
								$last_name = $row['last_name'];
								$last_name = ucwords(strtolower($last_name));
								$customer_name = $first_name.' '.$last_name;
								$national_id = $row['national_id'];
							}

							if ($intcount % 2 == 0) {
								$display= '<tr bgcolor = #F0F0F6>';
							}
							else {
								$display= '<tr>';
							}
							echo $display;
							echo "<td valign='top'>$intcount.</td>";
							echo "<td valign='top'>$loan_date</td>";
							echo "<td valign='top'>$stations</td>";	
							echo "<td valign='top'>$national_id</td>";	
							echo "<td valign='top'>$customer_name</td>";	
							echo "<td valign='top'>$loan_mobile</td>";	
							echo "<td valign='top'>$loan_code</td>";		
							echo "<td valign='top' align='right'>".number_format($loan_amount, 2)."</td>";	
							echo "<td valign='top'>$loan_mpesa_code</td>";	
							echo "</tr>";
							$total_Loan_amount = $total_Loan_amount + $loan_amount;
						}
						?>
					</tbody>
					<tfoot bgcolor="#E6EEEE">
						<tr>
							<th>#</th>
							<th>Date</th>
							<th>Branch</th>
							<th>Customer ID</th>
							<th>Name</th>
							<th>Phone Number</th>
							<th>Loan Code</th>
							<th>Loan Amount</th>
							<th>MM transaction number</th>
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
						$("#example2").btechco_excelexport({
						containerid: "example2"
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
