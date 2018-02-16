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
		$page_title = "Mobile Wallets Report";
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
							<th>#</th>
							<th>Loan Ref</th>
							<th>Disbursements</th>
						</tr>
					</thead>
					<tbody>
					<?php
						 $sql = mysql_query("select distinct length(loan_mpesa_code)length_code, sum(loan_amount)loans from loan_application where loan_date between '$filter_start_date' and '$filter_end_date' group by length(loan_mpesa_code)");
						 $intcount = 0;
						 while ($row = mysql_fetch_array($sql))
						 {
							$intcount++;
							$length_code = $row['length_code'];
							$loans = $row['loans'];
							
							if($length_code == '0' || $length_code == '1'){
								$code_name = 'blank';
							}
							else if($length_code == '9'){
								$code_name = 'MPESA';
							}
							else if($length_code == '10' || $length_code == '12'){
								$code_name = 'Airtel Money';
							}
							
							if ($intcount % 2 == 0) {
								$display= '<tr bgcolor = #F0F0F6>';
							}
							else {
								$display= '<tr>';
							}
							echo $display;
							echo "<td valign='top'>$intcount.</td>";
							echo "<td valign='top'>$code_name</td>";
							echo "<td align='right' valign='top'>".number_format($loans, 2)."</td>";
							//echo "<td align='right' valign='top'>".number_format($loans, 2)."</td>";
							echo "</tr>";
					
							$total_loan_rep_amount = $total_loan_rep_amount  + $loans;
						}
						$loan_rep_date = "";
						?>
					</tbody>
					<tr bgcolor = '#E6EEEE'>
						<td colspan='2'><strong>&nbsp;</strong></td>
						<td align='right' valign='top'><strong><?php echo number_format($total_loan_rep_amount, 2) ?></strong></td>
					</tr>
					<tfoot bgcolor="#E6EEEE">
						<tr>
							<th>#</th>
							<th>Loan Ref</th>
							<th>Disbursements</th>
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
