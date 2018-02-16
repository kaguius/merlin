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
		$page_title = "Collection Rates per Sector Report";
		include_once('includes/header.php');
		$filter_clerk = 0;
		if (!empty($_GET)){	
			$filter_clerk = $_GET['clerk'];
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
					<table width="100%" border="0" cellspacing="2" cellpadding="2" class="display" id="example3">
					<thead bgcolor="#E6EEEE">
						<tr>
							<th>#</th>
							<th>Sector</th>
							<th>Disbursements</th>
							<th>Collections</th>
							<th>Ratio</th>
						</tr>
					</thead>
					<tbody>
					<?php
						 if($branch == ''){
							 $sql = mysql_query("select distinct business_details.business_category, sum(loan_total_interest)amount from loan_application inner join business_details on business_details.user_id = loan_application.customer_id where loan_application.loan_date between '$filter_start_date' and '$filter_end_date' group by business_details.business_category");
						 }
						 else{
							 $sql = mysql_query("select distinct business_details.business_category, sum(loan_total_interest)amount from loan_application inner join business_details on business_details.user_id = loan_application.customer_id where loan_application.loan_date between '$filter_start_date' and '$filter_end_date' and loan_application.customer_station = '$branch' group by business_details.business_category");
						 }
						 $intcount = 0;
						 $total_loan_rep_amount = 0;
						 while ($row = mysql_fetch_array($sql))
						 {
							$intcount++;
							$business_category = $row['business_category'];
							$amount = $row['amount'];
							
							$sql2 = mysql_query("select distinct loan_code from loan_application inner join business_details on business_details.user_id = loan_application.customer_id where business_details.business_category = '$business_category' and loan_due_date between '$filter_start_date' and '$filter_end_date' group by loan_code");	
							while($row = mysql_fetch_array($sql2)) {
								$loan_code = $row['loan_code'];
								$sql3 = mysql_query("select sum(loan_rep_amount)repayments from loan_repayments where loan_rep_code = '$loan_code' group by loan_rep_code");	
								while($row = mysql_fetch_array($sql3)) {
									$repayments = $row['repayments'];
									if($repayments == ""){
										$repayments = 0;
									}
									$total_repayments = $total_repayments + $repayments;
								}
							}
							
							$sql2 = mysql_query("select business from business where id = '$business_category'");	
							while($row = mysql_fetch_array($sql2)) {
								$business_name = $row['business'];
							}

							$rate = ($total_repayments / $amount) * 100;
							
							if ($intcount % 2 == 0) {
								$display= '<tr bgcolor = #F0F0F6>';
							}
							else {
								$display= '<tr>';
							}
							echo $display;
							echo "<td valign='top'>$intcount.</td>";
							echo "<td valign='top'>$business_name</td>";
							echo "<td align='right' valign='top'>".number_format($amount, 2)."</td>";
							echo "<td align='right' valign='top'>".number_format($total_repayments, 2)."</td>";
							echo "<td align='right' valign='top'>".number_format($rate, 2)."%</td>";			
							echo "</tr>";
							
							$total_disbursed = $total_disbursed + $amount;
							$total_known_repayments = $total_known_repayments + $total_repayments;
							
							$amount = 0;
							$repayments = 0;
							$total_repayments = 0;
							$business_category = "";
						}
						$total_rate = ($total_known_repayments / $total_disbursed) * 100;
						?>
					</tbody>
					<tr bgcolor = '#E6EEEE'>
						<td ><strong>&nbsp;</strong></td>
						<td ><strong>&nbsp;</strong></td>
						<td align='right' valign='top'><strong>KES <?php echo number_format($total_disbursed, 2) ?></strong></td>
						<td align='right' valign='top'><strong>KES <?php echo number_format($total_known_repayments, 2) ?></strong></td>
						<td align='right' valign='top'><strong><?php echo number_format($total_rate, 2) ?>%</strong></td>
					</tr>
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
