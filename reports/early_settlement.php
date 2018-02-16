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
		$page_title = "Early Settlement Report";
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
					<table width="100%" border="0" cellspacing="2" cellpadding="2" class="display" id="example3">
					<thead bgcolor="#E6EEEE">
						<tr>
							<th>Loan Ref</th>
							<th>Branch</th>
							<th>Business</th>
							<th>Date</th>
							<th>RDate</th>
							<th>Trunk</th>
							<th>Days</th>
							<th>Loan</th>
							<th>Repayments</th>
							<th>Diff</th>
						</tr>
					</thead>
					<tbody>
					<?php
						
						if($branch == ''){
							 $sql = mysql_query("select loan_due_date, loan_code, customer_id, loan_total_interest, customer_station from loan_application where loan_due_date between '$filter_start_date' and '$filter_end_date' and loan_status = '13' order by loan_due_date, customer_station asc");
						}
						else{
							 $sql = mysql_query("select loan_due_date, loan_code, customer_id, loan_total_interest, customer_station from loan_application where loan_due_date between '$filter_start_date' and '$filter_end_date' and customer_station = '$branch' and loan_status = '13' order by loan_due_date, customer_station asc");
						}
						 $intcount = 0;
						 while ($row = mysql_fetch_array($sql))
						 {
							$intcount++;
							$loan_due_date = $row['loan_due_date'];
							$loan_total_interest = $row['loan_total_interest'];
							$loan_code = $row['loan_code'];
							$customer_id = $row['customer_id'];
							$customer_station = $row['customer_station'];
							$sql3 = mysql_query("select loan_rep_date, loan_rep_amount from loan_repayments where loan_rep_code = '$loan_code' order by loan_rep_id desc limit 1");
							while ($row = mysql_fetch_array($sql3))
							{
								$loan_rep_date = $row['loan_rep_date'];
								if($loan_rep_date == ""){
									$loan_rep_date = "";
								}
							}
							$sql2 = mysql_query("select id, stations from stations where id = '$customer_station'");
							while($row = mysql_fetch_array($sql2)) {
								$stations = $row['stations'];
								$stations = ucwords(strtolower($stations));
							}
							
							$sql2 = mysql_query("select sum(loan_rep_amount)repayments from loan_repayments where loan_rep_code = '$loan_code' group by loan_rep_code");
							while($row = mysql_fetch_array($sql2)) {
								$repayments = $row['repayments'];
							}
							
							$sql2 = mysql_query("select business.business from business_details inner join business on business.id = business_details.business_category where user_id = '$customer_id'");
							while($row = mysql_fetch_array($sql2)) {
								$business = $row['business'];
								$business = ucwords(strtolower($business));
							}
							
							$date1 = strtotime($loan_due_date);
							$date2 = strtotime($loan_rep_date);
							$dateDiff = $date1 - $date2;
							$days = floor($dateDiff/(60*60*24));
							
							if($days <= 7){
								$trunck = '21-30 days';
							}
							else if($days <= 14){
								$trunck = '14-21 days';
							}
							else if($days <= 21){
								$trunck = '7-14 days';
							}
							else if($days <= 30){
								$trunck = '0-7 days';
							}
							else{
								$trunck = '21-30 days';
							}
							
							//$diff = (($loan_total_interest - $repayments)/ $loan_total_interest) * 100;
							$diff = ($loan_total_interest - $repayments);
							
							if($loan_rep_date != "" && $days > 0){
								if ($intcount % 2 == 0) {
									$display= '<tr bgcolor = #F0F0F6>';
								}
								else {
									$display= '<tr>';
								}
								echo $display;
								//echo "<td valign='top'>$intcount.</td>";
								echo "<td valign='top'>$loan_code</td>";
								echo "<td valign='top'>$stations</td>";
								echo "<td valign='top'>$business</td>";
								echo "<td valign='top'>$loan_due_date</td>";
								echo "<td valign='top'>$loan_rep_date</td>";
								echo "<td valign='top'>$trunck</td>";
								echo "<td valign='top'>$days</td>";
								echo "<td align='right' valign='top'>".number_format($loan_total_interest, 2)."</td>";
								echo "<td align='right' valign='top'>".number_format($repayments, 2)."</td>";
								echo "<td align='right' valign='top'>".number_format($diff, 2)."</td>";
								echo "</tr>";
							
								$total_loan_rep_amount = $total_loan_rep_amount  + $loan_total_interest;
								$total_repayments = $total_repayments  + $repayments;
							}
							$loan_rep_date = "";
							//$diff_rate = (($total_loan_rep_amount - $total_repayments)/ $total_loan_rep_amount) * 100;
							$diff_rate = ($total_loan_rep_amount - $total_repayments);
						}
						?>
					</tbody>
					<tr bgcolor = '#E6EEEE'>
						<td colspan='7'><strong>&nbsp;</strong></td>
						<td align='right' valign='top'><strong><?php echo number_format($total_loan_rep_amount, 2) ?></strong></td>
						<td align='right' valign='top'><strong><?php echo number_format($total_repayments, 2) ?></strong></td>
						<td align='right' valign='top'><strong><?php echo number_format($diff_rate, 2) ?></strong></td>
					</tr>
					<tfoot bgcolor="#E6EEEE">
						<tr>
							<th>Loan Ref</th>
							<th>Branch</th>
							<th>Business</th>
							<th>Date</th>
							<th>RDate</th>
							<th>Trunk</th>
							<th>Days</th>
							<th>Loan</th>
							<th>Repayments</th>
							<th>Diff</th>
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
