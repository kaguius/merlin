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
		$page_title = "Reverse Payments Report";
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
			$page_title = "Reverse Payments Report";
			
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
							<th>Date</th>
							<th>Mobile</th>
							<th>Agent</th>
							<th>Amount</th>
							<th>Reverse Date</th>
							<th>Logged</th>
							<th>MPESA</th>
							<th>Loan Code</th>
							<?php if($adminstatus == 1 || $adminstatus == 3){ ?>
							<th>MPESA</th>
							<th>Mobile</th>
							<th>Date</th>
							<th>Paybill</th>
							<th>Amount</th>
							<th>Reverse Details</th>
							<?php } ?>
						</tr>
					</thead>
					<tbody>
					<?php
						 $sql = mysql_query("select id, loan_date, loan_mobile, agent_mobile, loan_amount, reversal_date, UID, mpesa_code, loan_code, transactiontime, reverse_mpesa_code, reverse_mobile, reverse_date, paybill_number, reverse_amount from loan_reversal where reversal_date between '$filter_start_date' and '$filter_end_date'");
						 $intcount = 0;
						 $total_loan_rep_amount = 0;
						 while ($row = mysql_fetch_array($sql))
						 {
							$intcount++;
							$reverse_id = $row['id'];
							$loan_date = $row['loan_date'];
							$loan_mobile = $row['loan_mobile'];
							$agent_mobile = $row['agent_mobile'];
							$loan_amount = $row['loan_amount'];
							$reversal_date = $row['reversal_date'];
							$UID = $row['UID'];
							$mpesa_code = $row['mpesa_code'];
							$loan_code = $row['loan_code'];
							
							$reverse_mpesa_code = $row['reverse_mpesa_code'];
							$reverse_mobile = $row['reverse_mobile'];
							$reverse_date = $row['reverse_date'];
							$paybill_number = $row['paybill_number'];
							$reverse_amount = $row['reverse_amount'];
							
							$sql2 = mysql_query("select concat(first_name, ' ', last_name)staff_name from user_profiles where id = '$UID'");
							while ($row = mysql_fetch_array($sql2))
							{
								$staff_name = $row['staff_name'];	
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
							echo "<td valign='top'>$loan_mobile</td>";
							echo "<td valign='top'>$agent_mobile</td>";
							echo "<td valign='top' align='right'>".number_format($loan_amount, 2)."</td>";
							echo "<td valign='top'>$reversal_date</td>";
							echo "<td valign='top'>$staff_name</td>";
							echo "<td valign='top'>$mpesa_code</td>";
							echo "<td valign='top'>$loan_code</td>";
							if($adminstatus == 1){
								if($reverse_amount == ""){
									echo "<td valign='top'>&nbsp;</td>";
									echo "<td valign='top'>&nbsp;</td>";
									echo "<td valign='top'>&nbsp;</td>";
									echo "<td valign='top'>&nbsp;</td>";
									echo "<td valign='top'>&nbsp;</td>";
									echo "<td valign='top'><a href='reverse_details.php?reverse_id=$reverse_id&mode=edit'><img src='images/reverse.jpg' width='35px'></a></td>";
								}
								else{
									echo "<td valign='top'>$reverse_mpesa_code</td>";
									echo "<td valign='top'>$reverse_mobile</td>";
									echo "<td valign='top'>$reverse_date</td>";
									echo "<td valign='top'>$paybill_number</td>";
									echo "<td valign='top'>$reverse_amount</td>";
									echo "<td valign='top'><img src='images/reverse.jpg' width='35px'></td>";
								}
							}		
							echo "</tr>";
						}
						?>
					</tbody>
					<tfoot bgcolor="#E6EEEE">
						<tr>
							<th>#</th>
							<th>Date</th>
							<th>Mobile</th>
							<th>Agent</th>
							<th>Amount</th>
							<th>Reverse Date</th>
							<th>Logged</th>
							<th>MPESA</th>
							<th>Loan Code</th>
							<?php if($adminstatus == 1 || $adminstatus == 3){ ?>
							<th>MPESA</th>
							<th>Mobile</th>
							<th>Date</th>
							<th>Paybill</th>
							<th>Amount</th>
							<th>Reverse Details</th>
							<?php } ?>
						</tr>
					</tfoot>
				</table>
				</div>
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
