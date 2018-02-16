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
		$page_title = "Loans Suspense Accounts Reconciliation";
		include_once('includes/header.php');
		$filter_clerk = 0;
		$sql = mysql_query("select sum(loan_amount)susp_acc_amount from loan_suspece_account where resolved = '0'");
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
					<br />
					<table width="100%" border="0" cellspacing="2" cellpadding="2" class="display" id="example3">
					<thead bgcolor="#E6EEEE">
						<tr bgcolor='#fff'>
							<th>#</th>
							<th>Receipt</th>
							<th>Date</th>
							<th>Details</th>
							<th>Phone</th>
							<th>Amount</th>
							<th>Resolved</th>
							<?php if($station == '3'){ ?>
							<th>Edit</th>
							<?php } ?>
						</tr>
					</thead>
					<tbody>
					<?php
						 $sql = mysql_query("select id, receipt, loan_date, mpesa_name, phone_number, loan_amount, resolved from loan_suspece_account order by loan_date desc");
						 $intcount = 0;
						 $total_trans_amount = 0;
						 while ($row = mysql_fetch_array($sql))
						 {
							$intcount++;
							$id = $row['id'];
							$receipt = $row['receipt'];
							$loan_date = $row['loan_date'];
							$mpesa_name = $row['mpesa_name'];
							$phone_number = $row['phone_number'];
							$loan_amount = $row['loan_amount'];
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
							echo "<td valign='top'>$receipt</a></td>";
							echo "<td valign='top'>$loan_date</a></td>";
							echo "<td valign='top'>$mpesa_name</a></td>";
							echo "<td valign='top'>$phone_number</a></td>";
							echo "<td valign='top' align='right'>".number_format($loan_amount, 2)."</td>";
							echo "<td valign='top' align='right'>$resolution</td>";
							if($station == '3'){ 
								if($resolved == 1){
									echo "<td valign='top'><a href='loan_suspence_details.php?id=$id&mode=edit'><img src='images/edit.png' width='35px'></a></td>";
								}
								else{
									echo "<td valign='top'><a href='loan_suspence_details.php?id=$id&mode=edit'><img src='images/edit.png' width='35px'></a></td>";
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
							<th>Receipt</th>
							<th>Date</th>
							<th>Details</th>
							<th>Phone</th>
							<th>Amount</th>
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
	include_once('includes/footer.php');
?>
