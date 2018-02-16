<?php
	$userid = "";
	$adminstatus = 4;
	$property_manager_id = "";
	session_start();
	if (!empty($_SESSION)){
		$userid = $_SESSION["userid"] ;
		$adminstatus = $_SESSION["adminstatus"] ;
		$station = $_SESSION["station"] ;
		$username = $_SESSION["username"];
	}

	//if($adminstatus != 1 || $adminstatus != 2 || $adminstatus != 4){
	if($adminstatus == 4){
		include_once('includes/header.php');
		?>
		<script type="text/javascript">
			document.location = "login.php";
		</script>
		<?php
	}
	else{
		include_once('includes/db_conn.php');
		$transactiontime = date("Y-m-d G:i:s");
		$page_title = "Loan Reversals";
		include_once('includes/header.php');
		?>		
		<div id="page">
			<div id="content">
				<div class="post">
				<h2><font color="#000A8B"><?php echo $page_title; ?></font></h2>
				<?php if($adminstatus == 1 || $adminstatus == 2 || $adminstatus == 3){ ?>
					<p>+ <a href="loan_reversal_details.php">Add a new Loan Reversal</a></p>
				<?php } ?>
				<table width="100%" border="0" cellspacing="2" cellpadding="2" class="display" id="example">
					<thead bgcolor="#E6EEEE">
						<tr bgcolor='#fff'>
							<th>#</th>
							<th>Date</th>
							<th>MPESA</th>
							<th>Mobile</th>
							<th>Reversal #</th>
							<th>Amount</th>
							<th>R. Date</th>
							<th>Authorisation</th>
							<th>Finalized</th>
							<th>MPESA</th>
							<th>Paybill</th>
						</tr>
						</tr>
					</thead>
					<tbody>
					<?php
						 $sql = mysql_query("select id, loan_mpesa_code, loan_date, loan_mobile, agent_mobile, loan_amount, reversal_date, finalized, authorization, mpesa_id, system_id, paybill from loan_reversal_table order by transactiontime desc");
						 while ($row = mysql_fetch_array($sql))
						 {
							$intcount++;
							$id = $row['id'];	
							$loan_mpesa_code = $row['loan_mpesa_code'];				
							$loan_date = $row['loan_date'];
							$loan_mobile = $row['loan_mobile'];
							$agent_mobile = $row['agent_mobile'];
							$loan_amount = $row['loan_amount'];
							$reversal_date = $row['reversal_date'];
							$authorization = $row['authorization'];
							$finalized = $row['finalized'];
							$mpesa_id = $row['mpesa_id'];
							$paybill = $row['paybill'];
							if ($intcount % 2 == 0) {
								$display= '<tr bgcolor = #F0F0F6>';
							}
							else {
								$display= '<tr>';
							}
							echo $display;
							echo "<td valign='top'>$intcount.</td>";
							echo "<td valign='top'>$loan_date</td>";
							echo "<td valign='top'>$loan_mpesa_code</td>";
							echo "<td valign='top'>$loan_mobile</td>";
							echo "<td valign='top'>$agent_mobile</td>";
							echo "<td valign='top' align='right'>".number_format($loan_amount, 2)."</td>";
							echo "<td valign='top'>$reversal_date</td>";
							echo "<td valign='top'>$authorization</td>";
							echo "<td valign='top'>$finalized</td>";
							echo "<td valign='top'>$mpesa_id</td>";
							echo "<td valign='top'>$paybill</td>";
							echo "</tr>";
						}
						?>
					</tbody>
					<tfoot bgcolor="#E6EEEE">
						<tr bgcolor='#fff'>
							<th>#</th>
							<th>Date</th>
							<th>MPESA</th>
							<th>Mobile</th>
							<th>Reversal #</th>
							<th>Amount</th>
							<th>R. Date</th>
							<th>Authorisation</th>
							<th>Finalized</th>
							<th>MPESA</th>
							<th>Paybill</th>
						</tr>
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
