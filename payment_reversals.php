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
		$page_title = "Payment Reversals";
		include_once('includes/header.php');
		?>		
		<div id="page">
			<div id="content">
				<div class="post">
				<h2><font color="#000A8B"><?php echo $page_title; ?></font></h2>
				<?php if($adminstatus == 1 || $adminstatus == 2 || $adminstatus == 3){ ?>
					<p>+ <a href="payment_reversal_details.php">Add a new Payment Reversal</a></p>
				<?php } ?>
				<table width="100%" border="0" cellspacing="2" cellpadding="2" class="display" id="example">
					<thead bgcolor="#E6EEEE">
						<tr bgcolor='#fff'>
							<th>#</th>
							<th>MPESA ID</th>
							<th>Mobile</th>
							<th>Amount</th>
							<th>Payment Date</th>
							<th>Paybill</th>
						</tr>
						</tr>
					</thead>
					<tbody>
					<?php
						 $sql = mysql_query("select id, mpesa_id, mobile_number, payment_amount, payment_date, paybill from payment_reversal_table order by transactiontime desc");
						 while ($row = mysql_fetch_array($sql))
						 {
							$intcount++;
							$id = $row['id'];					
							$mpesa_id = $row['mpesa_id'];
							$mobile_number = $row['mobile_number'];
							$payment_amount = $row['payment_amount'];
							$payment_date = $row['payment_date'];
							$loan_reversal_id = $row['loan_reversal_id'];
							$paybill = $row['paybill'];

							if ($intcount % 2 == 0) {
								$display= '<tr bgcolor = #F0F0F6>';
							}
							else {
								$display= '<tr>';
							}
							echo $display;
							echo "<td valign='top'>$intcount.</td>";
							echo "<td valign='top'>$mpesa_id</td>";
							echo "<td valign='top'>$mobile_number</td>";
							echo "<td valign='top' align='right'>".number_format($payment_amount, 2)."</td>";
							echo "<td valign='top'>$payment_date</td>";
							echo "<td valign='top'>$paybill</td>";
							echo "</tr>";
						}
						?>
					</tbody>
					<tfoot bgcolor="#E6EEEE">
						<tr bgcolor='#fff'>
							<th>#</th>
							<th>MPESA ID</th>
							<th>Mobile</th>
							<th>Amount</th>
							<th>Payment Date</th>
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
