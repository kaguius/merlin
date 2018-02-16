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
		$page_title = "Deceased Status Report";
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
							<th>Name</th>
							<th>Mobile</th>
							<th>Branch</th>
							<th>Date</th>
							<th>Amount</th>
						</tr>
					</thead>
					<tbody>
					<?php
						if($station == '3'){
							$sql = mysql_query("select distinct customer_id, concat(first_name, ' ', last_name)customer_name, loan_date, loan_amount, mobile_no, count(loan_id)counts, alt_phone, affordability, customer_station from loan_application inner join users on users.id = loan_application.customer_id where loan_date between '$filter_start_date' and '$filter_end_date' and loan_status = '15' group by customer_id order by loan_date desc");
						}
						else{
							 $sql = mysql_query("select distinct customer_id, concat(first_name, ' ', last_name)customer_name, loan_date, loan_amount, mobile_no, national_id, count(loan_id)counts, alt_phone, affordability, customer_station from loan_application inner join users on users.id = loan_application.customer_id where loan_date between '$filter_start_date' and '$filter_end_date' and customer_station = '$station' and loan_status = '15' group by customer_id order by loan_date desc");
						}
						
						while ($row = mysql_fetch_array($sql))
						{
							$intcount++;
							$customer_id = $row['customer_id'];
							$customer_name = $row['customer_name'];
							$customer_name = ucwords(strtolower($customer_name));
							$mobile_no = $row['mobile_no'];
							$national_id = $row['national_id'];
							$loan_date = $row['loan_date'];
							$loan_amount = $row['loan_amount'];
							$counts = $row['counts'];
							$alt_phone = $row['alt_phone'];
							$affordability = $row['affordability'];
							$customer_station = $row['customer_station'];
							$sql2 = mysql_query("select id, status from loan_declined_status_codes where id = '$status'");
							while($row = mysql_fetch_array($sql2)) {
								$status_name = $row['status'];
								if($status_name == ""){
									$status_name = 'Active';
								}
							}
							$sql2 = mysql_query("select id, stations from stations where id = '$customer_station'");
							while($row = mysql_fetch_array($sql2)) {
								$stations = $row['stations'];
								$stations = ucwords(strtolower($stations));
							}
							//if($counts == 1){
								if ($intcount % 2 == 0) {
									$display= '<tr bgcolor = #F0F0F6>';
								}
								else {
									$display= '<tr>';
								}
								echo $display;
								echo "<td valign='top'>$intcount.</td>";
								echo "<td valign='top'>$customer_name</td>";
								echo "<td valign='top'>$mobile_no</td>";	
								echo "<td valign='top'>$stations</td>";	
								echo "<td valign='top'>$loan_date</td>";	
								echo "<td valign='top' align='right'>".number_format($loan_amount, 2)."</td>";	
								echo "</tr>";
								$total_Loan_amount = $total_Loan_amount + $loan_amount;
							}
						//}
						?>
					</tbody>
					<tr bgcolor = '#E6EEEE'>
						<td colspan='5'><strong>&nbsp;</strong></td>
						<td align='right' valign='top'><strong><?php echo number_format($total_Loan_amount, 2) ?></strong></td>
					</tr>
					<tfoot bgcolor="#E6EEEE">
						<tr>
							<th>#</th>
							<th>Name</th>
							<th>Mobile</th>
							<th>Branch</th>
							<th>Date</th>
							<th>Amount</th>
						</tr>
					</tfoot>
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
