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
		include_once('includes/db_conn.php');
		$transactiontime = date("Y-m-d G:i:s");
		$page_title = "Repayments Suspense Accounts Reconciliation";
		include_once('includes/header.php');
		$filter_clerk = 0;
		
		if (!empty($_GET)){	
			$filter_start_date = $_GET['report_start_date'];
			$filter_start_date = date('Y-m-d', strtotime(str_replace('-', '/', $filter_start_date)));
			$filter_end_date = $_GET['report_end_date'];
			$filter_end_date = date('Y-m-d', strtotime(str_replace('-', '/', $filter_end_date)));
		}

		if ($filter_start_date != "" && $filter_end_date != ""){
		$sql = mysql_query("select sum(paid_in)susp_acc_amount from suspence_accounts where resolved = '0' and date between '$filter_start_date' and '$filter_end_date'");
		while ($row = mysql_fetch_array($sql))
		{
			$susp_acc_amount = $row['susp_acc_amount'];		
		}
			
		?>
		<div id="page">
			<div id="content">
				<div class="post">
					<h2><?php echo $page_title ?></h2>
					<h3>Unllocated Suspense Account Amounts: KES <?php echo number_format($susp_acc_amount, 2) ?></h3>
					<p><strong>Report Range: <?php echo $filter_start_date ?> to <?php echo $filter_end_date ?></strong></p>
					<table width="100%" border="0" cellspacing="2" cellpadding="2" class="display" id="example3">
					<thead bgcolor="#E6EEEE">
						<tr bgcolor='#fff'>
							<th>#</th>
							<th>Date</th>
							<th>Receipt</th>
							<th>Details</th>
							<th>Status</th>
							<th>Other Party</th>
							<th>Trans Party Details</th>
							<th>Paid In</th>
							<th>Resolved</th>
							<?php if($station == '3'){ ?>
							<th>Edit</th>
							<?php } ?>
						</tr>
					</thead>
					<tbody>
					<?php
						 $sql = mysql_query("select id, receipt, date, details, status, paid_in, balance, balance_confirmed, trans_type, other_party_info, trans_party_details, resolved from suspence_accounts where resolved = '0' and date between '$filter_start_date' and '$filter_end_date' order by date desc");
						 $intcount = 0;
						 $total_trans_amount = 0;
						 while ($row = mysql_fetch_array($sql))
						 {
							$intcount++;
							$id = $row['id'];
							$receipt = $row['receipt'];
							$date = $row['date'];
							$details = $row['details'];
							$status = $row['status'];
							$paid_in = $row['paid_in'];
							$balance = $row['balance'];
							$balance_confirmed = $row['balance_confirmed'];
							$trans_type = $row['trans_type'];
							$other_party_info = $row['other_party_info'];
							$trans_party_details = $row['trans_party_details'];
							$resolved = $row['resolved'];
							if($resolved == '0'){
								$resolution = "Unresolved";
							}
							else{
								$resolution = "Resolved";
							}

							if ($intcount % 2 == 0) {
								$display= '<tr bgcolor = #F0F0F6>';
							}
							else {
								$display= '<tr>';
							}
							echo $display;
							echo "<td valign='top'>$intcount.</td>";
							echo "<td valign='top'>$date</a></td>";
							echo "<td valign='top'>$receipt</a></td>";
							echo "<td valign='top'>$details</a></td>";
							echo "<td valign='top'>$status</a></td>";
							echo "<td valign='top'>$other_party_info</a></td>";
							echo "<td valign='top'>$trans_party_details</a></td>";
							echo "<td valign='top' align='right'>".number_format($paid_in, 2)."</td>";
							echo "<td valign='top' align='right'>$resolution</td>";
							if($station == '3'){ 
								if($resolved == 1){
									echo "<td valign='top'><img src='images/edit.png' width='35px'></td>";
								}
								else{
									echo "<td valign='top'><a href='suspence_details.php?id=$id&mode=edit'><img src='images/edit.png' width='35px'></a></td>";
								}
							}
							echo "</tr>";
							$total_trans_amount = $total_trans_amount + $withdrawn;
						}
						?>
					</tbody>
					<tfoot bgcolor="#E6EEEE">
						<tr bgcolor='#fff'>
							<th>#</th>
							<th>Date</th>
							<th>Receipt</th>
							<th>Details</th>
							<th>Status</th>
							<th>Other Party</th>
							<th>Trans Party Details</th>
							<th>Paid In</th>
							<th>Resolved</th>
							<?php if($station == '3'){ ?>
							<th>Edit</th>
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
