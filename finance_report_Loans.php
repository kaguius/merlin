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
	$page_title = "Finance Report: Loans Report - Pair and Branch";
	include_once('includes/db_conn.php');
	include_once('includes/header.php');
	$filter_month = date("m");
	$filter_year = date("Y");
	$filter_day = date("d");
	$current_date = $filter_year.'-'.$filter_month.'-'.$filter_day;
	
	if (!empty($_GET)){	
        $loan_officer = $_GET['loan_officer'];
        $filter_start_date = $_GET['report_start_date'];
        $filter_start_date = date('Y-m-d', strtotime(str_replace('-', '/', $filter_start_date)));
        $filter_end_date = $_GET['report_end_date'];
        $filter_end_date = date('Y-m-d', strtotime(str_replace('-', '/', $filter_end_date)));
    }
    if ($filter_start_date != "" && $filter_end_date != ""){
	
	//$filter_start_date = '2016-03-01';
	//$filter_end_date = '2016-05-31';
?>
	<div id="page">
	    <div id="content">
	        <div class="post">
	            <h2><?php echo $page_title ?></h2>
					<h3>Report Date: <?php echo $filter_start_date ?> and <?php echo $filter_end_date ?></h3>
	                <table width="100%" border="0" cellspacing="2" cellpadding="2" class="display">
						<tr>
							<td width="50%" valign="top">
								<h3>Disbursements/ Loans/ ALV: Staff</h3>
								<table width="100%" border="0" cellspacing="2" cellpadding="2" class="display" id="exampl">
								<thead bgcolor="#E6EEEE">
									<tr>
										<!--<th>#</th>-->
										<th>Branch</th>
										<th>Amount</th>
										<th>Loans</th>
										<th>ALV</th>
									</tr>
								</thead>
								<tbody>
								<?php
									$sql = mysql_query("select distinct loan_officer, sum(loan_amount)loan_amount, count(loan_id)loans, customer_station from loan_application where loan_date between '$filter_start_date' and '$filter_end_date' and customer_station != '0' and loan_failure_status = '0' and loan_status != '10' and loan_status != '9' and loan_status != '11' and loan_status != '12' and loan_status != '14' group by loan_officer order by loan_officer asc");
									
									$intcount = 0;
									$total_loan_amount = 0;
									$total_loans = 0;
									$total_manual_loans = 0;
									$total_repeater_loans = 0;
									while ($row = mysql_fetch_array($sql))
									{
										$intcount++;
										$loan_officer = $row['loan_officer'];
										$loan_amount = $row['loan_amount'];
										$loans = $row['loans'];
										$customer_station = $row['customer_station'];
										
										$sql2 = mysql_query("select concat(first_name, ' ', last_name)loan_officer, collections from user_profiles where id = '$loan_officer'");	
										while($row = mysql_fetch_array($sql2)) {
											$loan_officer = $row['loan_officer'];
										}

										$alv = $loan_amount / $loans;
							
										if ($intcount % 2 == 0) {
											$display= '<tr bgcolor = #F0F0F6>';
										}
										else {
											$display= '<tr>';
										}
										echo $display;
										//echo "<td valign='top'>$intcount.</td>";
										echo "<td valign='top'>$loan_officer</td>";
										echo "<td align='right' valign='top'>".number_format($loan_amount, 0)."</td>";
										echo "<td align='right' valign='top'>".number_format($loans, 0)."</td>";
										echo "<td align='right' valign='top'>".number_format($alv, 0)."</td>";
										echo "</tr>";

										
										
										$total_loan_amount = $total_loan_amount  + $loan_amount;
										$total_loans = $total_loans + $loans;
										$total_manual_loans = $total_manual_loans + $manual_loans;
										$total_repeater_loans = $total_repeater_loans + $repeater_loans;
										$repeater_loans = 0;
										$manual_loans = 0;
										$loans = 0;
										$total_alv = $total_loan_amount / $total_loans;
									}
									?>
								</tbody>
								<tr bgcolor = '#E6EEEE'>
									<td ><strong>&nbsp;</strong></td>
									<td align='right' valign='top'><strong><?php echo number_format($total_loan_amount, 0) ?></strong></td>
									<td align='right' valign='top'><strong><?php echo number_format($total_loans, 0) ?></strong></td>
									<td align='right' valign='top'><strong><?php echo number_format($total_alv, 0) ?></strong></td>
								</tr>
							</table>
						</td>
						<td width="50%" valign="top">
							<h3>Disbursements/ Loans/ ALV: Branch</h3>
								<table width="100%" border="0" cellspacing="2" cellpadding="2" class="display" id="exampl">
								<thead bgcolor="#E6EEEE">
									<tr>
										<!--<th>#</th>-->
										<th>Branch</th>
										<th>Amount</th>
										<th>Loans</th>
										<th>ALV</th>
									</tr>
								</thead>
								<tbody>
								<?php
									$sql = mysql_query("select distinct loan_date, sum(loan_amount)loan_amount, count(loan_id)loans, customer_station from loan_application where loan_date between '$filter_start_date' and '$filter_end_date' and customer_station != '0' and loan_failure_status = '0' and loan_status != '10' and loan_status != '9' and loan_status != '11' and loan_status != '12' and loan_status != '14' group by customer_station order by customer_station asc");
									
									$intcount = 0;
									$total_loan_amount = 0;
									$total_loans = 0;
									$total_manual_loans = 0;
									$total_repeater_loans = 0;
									while ($row = mysql_fetch_array($sql))
									{
										$intcount++;
										$loan_date = $row['loan_date'];
										$loan_amount = $row['loan_amount'];
										$loans = $row['loans'];
										$customer_station = $row['customer_station'];
										
										$sql2 = mysql_query("select stations from stations where id = '$customer_station'");
										while ($row = mysql_fetch_array($sql2))
										{
											$stations = $row['stations'];
										}

										$alv = $loan_amount / $loans;
							
										if ($intcount % 2 == 0) {
											$display= '<tr bgcolor = #F0F0F6>';
										}
										else {
											$display= '<tr>';
										}
										echo $display;
										//echo "<td valign='top'>$intcount.</td>";
										echo "<td valign='top'>$stations</td>";
										echo "<td align='right' valign='top'>".number_format($loan_amount, 0)."</td>";
										echo "<td align='right' valign='top'>".number_format($loans, 0)."</td>";
										echo "<td align='right' valign='top'>".number_format($alv, 0)."</td>";
										echo "</tr>";

										
										
										$total_loan_amount = $total_loan_amount  + $loan_amount;
										$total_loans = $total_loans + $loans;
										$total_manual_loans = $total_manual_loans + $manual_loans;
										$total_repeater_loans = $total_repeater_loans + $repeater_loans;
										$repeater_loans = 0;
										$manual_loans = 0;
										$loans = 0;
										$total_alv = $total_loan_amount / $total_loans;
									}
									?>
								</tbody>
								<tr bgcolor = '#E6EEEE'>
									<td ><strong>&nbsp;</strong></td>
									<td align='right' valign='top'><strong><?php echo number_format($total_loan_amount, 0) ?></strong></td>
									<td align='right' valign='top'><strong><?php echo number_format($total_loans, 0) ?></strong></td>
									<td align='right' valign='top'><strong><?php echo number_format($total_alv, 0) ?></strong></td>
								</tr>
							</table>
						</td>
					</tr>
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

