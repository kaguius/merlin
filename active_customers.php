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
		$page_title = "Active Customers Report";
		include_once('includes/header.php');
		$filter_clerk = 0;
		if (!empty($_GET)){	
			$loan_officer = $_GET['loan_officer'];
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
							<th>Date</th>
							<th>Name</th>
							<th>Mobile</th>
							<th>Disbursement</th>
							<th>Alternate</th>
							<th>Station</th>
							<th>LO</th>
							<th>CO</th>
							<th>Amount</th>
							<th>Affordability</th>
						</tr>
					</thead>
					<tbody>
					<?php
						if($station == '3'){
							$sql = mysql_query("select concat(first_name, ' ', last_name)customer_name, mobile_no, dis_phone, alt_phone, loan_date, loan_amount, affordability, customer_station, loan_application.loan_officer, loan_application.collections_officer from loan_application inner join users on users.id = loan_application.customer_id where loan_date between '$filter_start_date' and '$filter_end_date' and initiation_fee = '0' order by loan_date, customer_name asc");
						}
						else{
							$sql = mysql_query("select concat(first_name, ' ', last_name)customer_name, mobile_no, dis_phone, alt_phone, loan_date, loan_amount, affordability, customer_station, loan_application.loan_officer, loan_application.collections_officer from loan_application inner join users on users.id = loan_application.customer_id where loan_date between '$filter_start_date' and '$filter_end_date' and initiation_fee = '0' and customer_station = '$station' order by loan_date, customer_name asc");
						}
						
						while ($row = mysql_fetch_array($sql))
						{
							$intcount++;
							$customer_id = $row['customer_id'];
							$customer_name = $row['customer_name'];
							$customer_name = ucwords(strtolower($customer_name));
							$mobile_no = $row['mobile_no'];
							$dis_phone = $row['dis_phone'];
							$alt_phone = $row['alt_phone'];
							$loan_date = $row['loan_date'];
							$loan_amount = $row['loan_amount'];
							$affordability = $row['affordability'];
							$alt_phone = $row['alt_phone'];
							$affordability = $row['affordability'];
							$customer_station = $row['customer_station'];
							$loan_officer = $row['loan_officer'];
							$collections_officer = $row['collections_officer'];

							$sql2 = mysql_query("select id, stations from stations where id = '$customer_station'");
							while($row = mysql_fetch_array($sql2)) {
								$stations = $row['stations'];
								$stations = ucwords(strtolower($stations));
							}
							
							$sql2 = mysql_query("select id, first_name, last_name from user_profiles where id = '$loan_officer'");
							while($row = mysql_fetch_array($sql2)) {
								$loan_first_name = $row['first_name'];
								$loan_last_name = $row['last_name'];
								$loan_officer_name = $loan_first_name." ".$loan_last_name;
							}
							$sql2 = mysql_query("select id, first_name, last_name from user_profiles where id = '$collections_officer'");
							while($row = mysql_fetch_array($sql2)) {
								$collect_first_name = $row['first_name'];
								$collect_last_name = $row['last_name'];
								$collect_officer_name = $collect_first_name." ".$collect_last_name;
							}
							
							if ($intcount % 2 == 0) {
								$display= '<tr bgcolor = #F0F0F6>';
							}
							else {
								$display= '<tr>';
							}
							echo $display;
							echo "<td valign='top'>$loan_date</td>";	
							echo "<td valign='top'>$customer_name</td>";
							echo "<td valign='top'>$mobile_no</td>";	
							echo "<td valign='top'>$dis_phone</td>";	
							echo "<td valign='top'>$alt_phone</td>";
							echo "<td valign='top'>$stations</td>";
							echo "<td valign='top'>$loan_officer_name</td>";		
							echo "<td valign='top'>$collect_officer_name</td>";				
							echo "<td valign='top' align='right'>".number_format($loan_amount, 2)."</td>";	
							echo "<td valign='top' align='right'>".number_format($affordability, 2)."</td>";	
							echo "</tr>";
						}
						?>
					</tbody>
					<tfoot bgcolor="#E6EEEE">
						<tr>
							<th>Date</th>
							<th>Name</th>
							<th>Mobile</th>
							<th>Disbursement</th>
							<th>Alternate</th>
							<th>Station</th>
							<th>LO</th>
							<th>CO</th>
							<th>Amount</th>
							<th>Affordability</th>
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
