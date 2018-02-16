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
		$page_title = "Average loan Size Report";
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
					<h3>Average Loan Size per Branch</h3>
					<table width="100%" border="0" cellspacing="2" cellpadding="2" class="display" id="example2">
					<thead bgcolor="#E6EEEE">
						<tr>
							<th>#</th>
							<th>Avg Loan Size</th>
							<th>Disbursed</th>
							<th>Collections</th>
							<th>Rate</th>
						</tr>
					</thead>
					<tbody>
					<?php
						if($branch == ''){
							$sql = mysql_query("select distinct loan_amount from loan_application where loan_date between '$filter_start_date' and '$filter_end_date' group by loan_amount order by loan_amount asc");
						}
						else{
							 $sql = mysql_query("select distinct loan_amount from loan_application where loan_date between '$filter_start_date' and '$filter_end_date' and customer_station = '$branch' group by loan_amount order by loan_amount asc");
						}
						
						$intcount = 0;
						$total_disbursed = 0;
						$total_repayments = 0;
						while ($row = mysql_fetch_array($sql))
						{
							$intcount++;
							$loan_amount = $row['loan_amount'];
							$sql2 = mysql_query("select sum(loan_total_interest)disbursed from loan_application where loan_amount = '$loan_amount' and loan_date between '$filter_start_date' and '$filter_end_date'");	
							while($row = mysql_fetch_array($sql2)) {
								$disbursed = $row['disbursed'];
							}
							
							$sql2 = mysql_query("select loan_code from loan_application where loan_amount = '$loan_amount' and loan_date between '$filter_start_date' and '$filter_end_date'");
							while($row = mysql_fetch_array($sql2)) {
								$loan_code = $row['loan_code'];
								$sql3 = mysql_query("select sum(loan_rep_amount)repayments from loan_repayments where loan_rep_code = '$loan_code'");	
								while($row = mysql_fetch_array($sql3)) {
									$repayments = $row['repayments'];
									if($repayments == ""){
										$repayments = 0;
									}
									$total_repayments = $total_repayments + $repayments;
								}
							}
							if($total_known_repayments == ""){
								$total_known_repayments = 0;
							}
							
							$rate = ($total_repayments / $disbursed) * 100;
				
							if ($intcount % 2 == 0) {
								$display= '<tr bgcolor = #F0F0F6>';
							}
							else {
								$display= '<tr>';
							}
							echo $display;
							echo "<td valign='top'>$intcount.</td>";
							echo "<td align='right' valign='top'>".number_format($loan_amount, 0)."</td>";	
							echo "<td align='right' valign='top'>".number_format($disbursed, 2)."</td>";	
							echo "<td align='right' valign='top'>".number_format($total_repayments, 2)."</td>";	
							echo "<td align='right' valign='top'>".number_format($rate, 2)."%</td>";			
							echo "</tr>";
							
							$total_disbursed = $total_disbursed + $disbursed;
							$total_known_repayments = $total_known_repayments + $total_repayments;
							
							$disbursed = 0;
							$total_repayments = 0;
							$rate = 0;
							
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
					<tfoot bgcolor="#E6EEEE">
						<tr>
							<th>#</th>
							<th>Avg Loan Size</th>
							<th>Disbursed</th>
							<th>Collections</th>
							<th>Rate</th>
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
