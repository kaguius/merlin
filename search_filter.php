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
		$page_title = "Search Filter Results";
		include_once('includes/header.php');
		include_once('includes/db_conn.php');
		if (!empty($_GET)){	
			$client_mobile = $_GET['mobile'];
		}
		$sql3 = mysql_query("select client_name from users where client_mobile = '$client_mobile'");
		while ($row = mysql_fetch_array($sql3))
		{
			$client_name = $row['client_name'];
		}
		?>
		<div id="page">
			<div id="content">
				<div class="post">
					<h2><?php echo $page_title ?><br />Client: <?php echo $client_name ?></h2>
					<h3>Introductions</h3>
					<table width="100%" border="0" cellspacing="2" cellpadding="2" class="display" id="example">
						<thead bgcolor="#E6EEEE">
							<tr>
								<th>#</th>
								<th>Date</th>
								<th>Introducer</th>
								<th>Client Name</th>
								<th>Client Number</th>
								<th>Relationship</th>
							</tr>
						</thead>
						<tbody>
						<?php
						$total_invoice = 0;
							$total_invoice_sent = 0;
						 $sql = mysql_query("select Introduction_Introducer_Mobile, Introduction_Client_Mobile, Introduction_Date, Introduction_Relationship from introduction inner join loan_application on loan_application.Loan_mobile != introduction.Introduction_Client_Mobile where Introduction_Introducer_Mobile = '$client_mobile' group by Introduction_Client_Mobile order by Introduction_Date, Introduction_Introducer_Mobile asc ");
						 $total_invoice = 0;
						 $intcount = 0;
						 while ($row = mysql_fetch_array($sql))
						 {
							$intcount++;
							$introducer_mobile = $row['Introduction_Introducer_Mobile'];
							$client_mobile_number = $row['Introduction_Client_Mobile'];
							$introduction_date = $row['Introduction_Date'];
							$relationship = $row['Introduction_Relationship'];
							$sql2 = mysql_query("select client_name from users where client_mobile = '$introducer_mobile'");
							while ($row = mysql_fetch_array($sql2))
							{
								$introducer_name = $row['client_name'];
							}
							$sql3 = mysql_query("select client_name from users where client_mobile = '$client_mobile'");
							while ($row = mysql_fetch_array($sql3))
							{
								$client_name = $row['client_name'];
							}
							$sql4 = mysql_query("select Rel_Type_Name from relationships where Rel_Type_Id = '$relationship'");
							while ($row = mysql_fetch_array($sql4))
							{
								$relationship_name = $row['Rel_Type_Name'];
							}
							
							if ($intcount % 2 == 0) {
								$display= '<tr bgcolor = #F0F0F6>';
							}
							else {
								$display= '<tr>';
							}
							echo $display;
							echo "<td valign='top'>$intcount.</td>";
							echo "<td valign='top'>$introduction_date</td>";
							echo "<td valign='top'>$introducer_name</td>";
							echo "<td valign='top'>$client_name</td>";
							echo "<td valign='top'>$client_mobile_number</td>";
							echo "<td valign='top'>$relationship_name</td>";
							echo "</tr>";
						}
						?>
						</tbody>
						<tfoot bgcolor="#E6EEEE">
							<tr>
								<th>#</th>
								<th>Date</th>
								<th>Introducer</th>
								<th>Client Name</th>
								<th>Client Number</th>
								<th>Relationship</th>
							</tr>
						</tfoot>
					</table>
					<p>&nbsp;</p>
					<p>&nbsp;</p>
					<h3>Loans</h3>
					<table width="100%" border="0" cellspacing="2" cellpadding="2" class="display" id="example2">
							<thead bgcolor="#E6EEEE">
								<tr>
									<th>#</th>
									<th>Loan Date</th>
									<th>Loan Expiry</th>
									<th>Name</th>
									<th>Mobile</th>
									<th>Loan Amount</th>
								</tr>
							</thead>
							<tbody>
							<?php
							$total_invoice = 0;
							$total_invoice_sent = 0;
							 $sql = mysql_query("select loan_date, Loan_Expiry_Date, Loan_mobile, users.Client_Name, loan_amount, loan_status from loan_application inner join users on users.Client_Mobile = loan_application.Loan_mobile where Loan_mobile = '$client_mobile'");
							 $intcount = 0;
							 while ($row = mysql_fetch_array($sql))
							 {
								$intcount++;
								$loan_date = $row['loan_date'];
								$Loan_Expiry_Date = $row['Loan_Expiry_Date'];
								$Loan_mobile = $row['Loan_mobile'];
								$client_name = $row['Client_Name'];
								$loan_amount = $row['loan_amount'];
							
								if ($intcount % 2 == 0) {
									$display= '<tr bgcolor = #F0F0F6>';
								}
								else {
									$display= '<tr>';
								}
								echo $display;
								echo "<td valign='top'>$intcount.</td>";
								echo "<td valign='top'>$loan_date</td>";
								echo "<td valign='top'>$Loan_Expiry_Date</td>";
								echo "<td valign='top'>$client_name</td>";
								echo "<td valign='top'>$Loan_mobile</td>";
								echo "<td valign='top' align='right'>KES ".number_format($loan_amount, 2)."</td>";
								echo "</tr>";
								$total_invoice = $total_invoice + $loan_amount;
								$total_invoice_sent = $total_invoice_sent + $Loan_Amount_Sent;		
							}
							?>
							</tbody>
							<tr bgcolor = '#E6EEEE'>
								<td colspan='5'><strong>&nbsp;</strong></td>
								<!--<td align='right' valign='top'><strong>KES <?php echo number_format($total_invoice_sent, 2) ?></strong></td>-->
								<td align='right' valign='top'><strong>KES <?php echo number_format($total_invoice, 2) ?></strong></td>
							</tr>
							<tfoot bgcolor="#E6EEEE">
								<tr>
									<th>#</th>
									<th>Loan Date</th>
									<th>Loan Expiry</th>
									<th>Name</th>
									<th>Mobile</th>
									<th>Loan Amount</th>
								</tr>
							</tfoot>
						</table>
						<p>&nbsp;</p>
					<p>&nbsp;</p>
					<h3>Loan Repayments</h3>
					<table width="100%" border="0" cellspacing="2" cellpadding="2" class="display" id="example3">
							<thead bgcolor="#E6EEEE">
								<tr>
									<th>#</th>
									<th>Loan Date</th>
									<th>Loan Acc</th>
									<th>Mpesa Code</th>
									<th>Payment Type</th>
									<th>Loan Repayment</th>
								</tr>
							</thead>
							<tbody>
							<?php
							$total_invoice = 0;
							$total_invoice_sent = 0;
							 $sql = mysql_query("select loan_rep_date, loan_rep_acc_id, loan_rep_amount, loan_rep_mpesa_code, loan_schedule.Loan_Sched_Type from loan_repayments inner join loan_schedule on loan_repayments.Loan_Rep_Sched_ID = loan_schedule.Loan_Sched_ID where loan_rep_mobile = '$client_mobile' order by loan_rep_date asc");
							 $intcount = 0;
							 while ($row = mysql_fetch_array($sql))
							 {
								$intcount++;
								$loan_rep_date = $row['loan_rep_date'];
								$loan_rep_acc_id = $row['loan_rep_acc_id'];
								$loan_rep_amount = $row['loan_rep_amount'];
								$loan_rep_mpesa_code = $row['loan_rep_mpesa_code'];
								$Loan_Sched_Type = $row['Loan_Sched_Type'];
								
								$sql2 = mysql_query("select Sch_Type_Text from schedule_type where Sch_Type_ID = '$Loan_Sched_Type'");
								while ($row = mysql_fetch_array($sql2))
								{
									$Sch_Type_Text = $row['Sch_Type_Text'];
								}
							
								if ($intcount % 2 == 0) {
									$display= '<tr bgcolor = #F0F0F6>';
								}
								else {
									$display= '<tr>';
								}
								echo $display;
								echo "<td valign='top'>$intcount.</td>";
								echo "<td valign='top'>$loan_rep_date</td>";
								echo "<td valign='top'>$loan_rep_acc_id</td>";
								echo "<td valign='top'>$loan_rep_mpesa_code</td>";
								echo "<td valign='top'>$Sch_Type_Text</td>";
								echo "<td valign='top' align='right'>KES ".number_format($loan_rep_amount, 2)."</td>";
								echo "</tr>";
								$total_invoice = $total_invoice + $loan_rep_amount;
								$total_invoice_sent = $total_invoice_sent + $Loan_Amount_Sent;		
							}
							?>
							</tbody>
							<tr bgcolor = '#E6EEEE'>
								<td colspan='5'><strong>&nbsp;</strong></td>
								<!--<td align='right' valign='top'><strong>KES <?php echo number_format($total_invoice_sent, 2) ?></strong></td>-->
								<td align='right' valign='top'><strong>KES <?php echo number_format($total_invoice, 2) ?></strong></td>
							</tr>
							<tfoot bgcolor="#E6EEEE">
								<tr>
									<th>#</th>
									<th>Loan Date</th>
									<th>Loan Expiry</th>
									<th>Name</th>
									<th>Mobile</th>
									<th>Loan Amount</th>
								</tr>
							</tfoot>
						</table>
						<p>&nbsp;</p>
					<p>&nbsp;</p>
					<h3>Outstanding Dues</h3>
					<table width="100%" border="0" cellspacing="2" cellpadding="2" class="display" id="example4">
							<thead bgcolor="#E6EEEE">
								<tr>
									<th>#</th>
									<th>Loan Expiry Date</th>
									<th>Loan Age</th>
									<th>Schedule Date</th>
									<th>Client Name</th>
									<th>Mobile</th>
									<th>Due Amount Type</th>
									<!--<th>Loan Disbursed</th>-->
									<th>Due Amount</th>
								</tr>
							</thead>
							<tbody>
							<?php
							$total_invoice = 0;
							$total_invoice_sent = 0;
							 $sql = mysql_query("select loan_application.Loan_mobile, loan_application.Loan_Expiry_Date, loan_application.Loan_Amount_Sent, Loan_Sched_Loan_ID, loan_sched_id, Loan_Sched_Type, loan_sched_due_amount, Loan_Sched_Due_Date, Loan_Sched_Paid_Amount from loan_schedule inner join loan_application on loan_application.Loan_id = loan_schedule.Loan_Sched_Loan_ID where loan_application.Loan_mobile = '$client_mobile' and loan_sched_type = '3' and Loan_Sched_Paid_Amount = '0'");
							 $intcount = 0;
							 while ($row = mysql_fetch_array($sql))
							 {
								$intcount++;
								$loan_mobile = $row['Loan_mobile'];
								$Loan_Expiry_Date = $row['Loan_Expiry_Date'];
								$Loan_amount = $row['Loan_amount'];
								$Loan_Amount_Sent = $row['Loan_Amount_Sent'];
								$Loan_Sched_Due_Date = $row['Loan_Sched_Due_Date'];
								$loan_sched_due_amount = $row['loan_sched_due_amount'];
								$Loan_Sched_Type = $row['Loan_Sched_Type'];
							
								$start = strtotime($current_date);
								$end = strtotime($Loan_Expiry_Date);
								$loan_age = ((ceil(abs($end - $start) / 86400)));
														
								$sql3 = mysql_query("select client_name from users where client_mobile = '$loan_mobile'");
								while ($row = mysql_fetch_array($sql3))
								{
									$client_name = $row['client_name'];
								}
							
								$sql3 = mysql_query("select sch_type_text from schedule_type where sch_type_id = '$Loan_Sched_Type'");
								while ($row = mysql_fetch_array($sql3))
								{
									$shedule_name = $row['sch_type_text'];
								}
							
								if ($intcount % 2 == 0) {
									$display= '<tr bgcolor = #F0F0F6>';
								}
								else {
									$display= '<tr>';
								}
								echo $display;
								echo "<td valign='top'>$intcount.</td>";
								echo "<td valign='top'>$Loan_Expiry_Date</td>";
								echo "<td valign='top'>".number_format($loan_age, 0)."</td>";
								echo "<td valign='top'>$Loan_Sched_Due_Date</td>";
								echo "<td valign='top'>$client_name</td>";
								echo "<td valign='top'>$loan_mobile</td>";
								echo "<td valign='top'>$shedule_name</td>";
								//echo "<td valign='top' align='right'>KES ".number_format($Loan_Amount_Sent, 2)."</td>";
								echo "<td valign='top' align='right'>KES ".number_format($loan_sched_due_amount, 2)."</td>";
								echo "</tr>";
								$total_invoice = $total_invoice + $loan_sched_due_amount;
								$total_invoice_sent = $total_invoice_sent + $Loan_Amount_Sent;		
							}
							?>
							</tbody>
							<tr bgcolor = '#E6EEEE'>
								<td colspan='7'><strong>&nbsp;</strong></td>
								<!--<td align='right' valign='top'><strong>KES <?php echo number_format($total_invoice_sent, 2) ?></strong></td>-->
								<td align='right' valign='top'><strong>KES <?php echo number_format($total_invoice, 2) ?></strong></td>
							</tr>
							<tfoot bgcolor="#E6EEEE">
								<tr>
									<th>#</th>
									<th>Loan Expiry Date</th>
									<th>Loan Age</th>
									<th>Schedule Date</th>
									<th>Client Name</th>
									<th>Mobile</th>
									<th>Due Amount Type</th>
									<!--<th>Loan Disbursed</th>-->
									<th>Due Amount</th>
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
