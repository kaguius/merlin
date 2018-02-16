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
		if (!empty($_GET)){	
			$mode = $_GET['mode'];
			$id = $_GET['id'];
		}
		include_once('includes/db_conn.php');
		$transactiontime = date("Y-m-d G:i:s");
		if ($mode=='edit'){
			$sql = mysql_query("select id, receipt, loan_date, mpesa_name, phone_number, loan_amount, resolved from loan_suspece_account where id = '$id'");
			while ($row = mysql_fetch_array($sql))
			{
				$id = $row['id'];
				$receipt = $row['receipt'];
				$loan_date = $row['loan_date'];
				$mpesa_name = $row['mpesa_name'];
				$phone_number = $row['phone_number'];
				$loan_amount = $row['loan_amount'];
				$resolved = $row['resolved'];
			}
			$page_title = "Update Loan Suspense Transaction Detail(s)";
		}
		else{
			$page_title = "Create new Customer Detail(s)";
		}
		$mobile = substr($other_party_info, 1, 10);
		$mobile = "254".$mobile;
		include_once('includes/header.php');
		?>		
		<div id="page">
			<div id="content">
				<div class="post">
				<h2><font color="#000A8B"><?php echo $page_title; ?></font></h2>
				<br />
				<?php if($station == '3'){ ?>
					<p align="right"><img src="images/delete.png"> - <a href="loan_reversal_details.php?loan_sus_id=<?php echo $id ?>">Reverse this loan</a></p>
				<?php } ?>
				<form id="frmOrder" name="frmOrder" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
				<table border="0" width="100%" cellspacing="2" cellpadding="2">	
				<input type="hidden" name="id" id="id" value="<?php echo $id ?>" />		
				<input type="hidden" name="page_status" id="page_status" value="<?php echo $mode ?>" />
				    	<tr bgcolor = #F0F0F6>
						<td valign="top" width="15%">Receipt *</td>
						<td valign="top" width="35%">
							<input title="Enter Receipt" value="<?php echo $receipt ?>" id="receipt" name="receipt" type="text" maxlength="100" class="main_input" readonly size="35" />
						</td>
						<td valign="top" width="15%">Date *</td>
						<td valign="top" width="35%">
							<input title="Enter Date" value="<?php echo $loan_date ?>" id="susp_loan_date" name="susp_loan_date" type="text" maxlength="100" class="main_input" readonly size="35" />
						</td>
					</tr>
					<tr>
						<td valign='top' width="15%">Details *</td>
						<td valign='top' width="35%">
							<input title="Enter Gender" value="<?php echo $mpesa_name ?>" id="mpesa_name" name="mpesa_name" type="text" maxlength="100" class="main_input" readonly size="35" />
						</td>
						
					</tr>
					<tr bgcolor = #F0F0F6>
						<td valign='top' >Phone Number *</td>
						<td valign='top'>
							<input title="Enter Gender" value="<?php echo $phone_number ?>" id="phone_number" name="phone_number" type="text" maxlength="100" class="main_input" readonly size="35" />
						</td>
						<td valign='top' >Amount *</td>
						<td valign='top'>
							<input title="Enter Gender" value="<?php echo $loan_amount ?>" id="loan_amount" name="loan_amount" type="text" maxlength="100" class="main_input" readonly size="35" />
						</td>
					</tr>
					<tr>
						<td valign='top' width="15%">Console Loan Code *</td>
						<td valign='top' width="35%">
							<input title="Enter Gender" value="<?php echo $console_loan_code ?>" id="console_loan_code" name="console_loan_code" type="text" maxlength="100" class="main_input" size="35" />
						</td>
						<td valign='top' >Loan Status *</td>
						<td valign='top' width="35%">
							<select name='loan_status' id='loan_status'>
								<?php
									if($mode == 'edit'){
								?>
									<option value="<?php echo $status_id ?>"><?php echo $status_name ?></option>
									<option value=''> </option>	
								<?php
									}
									else{
									?>
									<option value=''> </option>
									<?php
									}
									//echo "<option value=''>" "</option>";
									if($title == '3'){
										$sql2 = mysql_query("select id, status from customer_status where id = '10' or id = '2' or id= '11' or id = '14' order by status asc", $dbh1);
									}
									else{
										$sql2 = mysql_query("select id, status from customer_status order by status asc", $dbh1);
									}
									while($row = mysql_fetch_array($sql2)) {
										$id = $row['id'];
										$status_name = $row['status'];
										echo "<option value='$id'>".$status_name."</option>"; 
									}
									?>
								</select>
						</td>
					</tr>
				</table>
				<table border="0" width="100%">
					<tr>
						<td valign="top">
							<button name="btnNewCard" id="button">Update Records</button>
						</td>
					</tr>
				</table>
				</form><br />
				<h3>Phone Number Exist</h3>
				<?php
					$sql = mysql_query("select mobile_no from users where mobile_no = '$phone_number'");
					while ($row = mysql_fetch_array($sql))
					{
						$mobile_no = $row['mobile_no'];
						similar_text($mobile_no, $phone_number, $percent);
						if($percent >= 80){
							echo "<strong>Primary Number</strong>: $phone_number; <strong>Similarity</strong>: ".number_format($percent, 2)."%";
							echo "<br />";
						}
					}
				?>
				<?php
					$sql = mysql_query("select dis_phone from users where dis_phone = '$phone_number'");
					while ($row = mysql_fetch_array($sql))
					{
						$dis_phone = $row['dis_phone'];
						similar_text($dis_phone, $phone_number, $percent);
						if($percent >= 80){
							echo "<strong>Disbursement Number</strong>: $dis_phone; <strong>Similarity</strong>: ".number_format($percent, 2)."%";
							echo "<br />";
						}
					}
				?>
				<?php
					$sql = mysql_query("select alt_phone from users where alt_phone = '$phone_number'");
					while ($row = mysql_fetch_array($sql))
					{
						$alt_phone = $row['alt_phone'];
						similar_text($alt_phone, $phone_number, $percent);
						if($percent >= 80){
							echo "<strong>Alternate Number</strong>: $alt_phone; <strong>Similarity</strong>: ".number_format($percent, 2)."%";
							echo "<br />";
						}
					}
				?>
				<br />
				<h3>Loans with the same Loan Mobile</h3>
				<?php
					$sql = mysql_query("select loan_date, loan_mobile, loan_amount, loan_total_interest, loan_code from loan_application inner join users on users.Mobile_no = loan_application.Loan_mobile where loan_mobile = '$phone_number ' order by loan_date desc");
						 $loan_amount = 0;
						 $intcount = 0;
						 $repayment = 0;
						 $overdue = 0;
						 $total_loan_amount = 0;
						 $total_repayment = 0;
						 $total_overdue = 0;
						 $intcount = 0;
						 while ($row = mysql_fetch_array($sql))
						 {
							$intcount++;
							$name = $row['loan_name'];
							$name = ucwords(strtolower($name));
							$agent_name = $row['Agent_Name'];
							$agent_name = ucwords(strtolower($agent_name));
							$loan_date = $row['loan_date'];
							$loan_mobile = $row['loan_mobile'];
							$loan_amount = $row['loan_amount'];
							$loan_total_interest = $row['loan_total_interest'];
							$loan_code = $row['loan_code'];
							$agent_mobile_no = $row['loan_agent_mobile'];
							
							$sql3 = mysql_query("select sum(loan_rep_amount)repayment from loan_repayments where loan_rep_code = '$loan_code' group by loan_rep_code");
							while ($row = mysql_fetch_array($sql3))
							{
								$repayment = $row['repayment'];
							}

							$balance = $loan_amount - $repayment;

							echo "<strong>Loan Date</strong>: $loan_date, <strong>Loan Reference</strong>: $loan_code, <strong>Loan Amount</strong>: ".number_format($loan_amount, 2)."";
							echo "<br />";
						}
				?>
			</div>
			</div>
			<br class="clearfix" />
			</div>
		</div>
<?php
	if (!empty($_POST)) {
		
		$receipt = $_POST['receipt'];
		$loan_date = $_POST['susp_loan_date'];
		//$loan_date = date('Y-m-d', strtotime(str_replace('-', '/', $loan_date)));
		$phone_number = $_POST['phone_number'];
		$loan_amount = $_POST['loan_amount'];
		$console_loan_code = $_POST['console_loan_code'];
		$loan_status = $_POST['loan_status'];

		$page_status = $_POST['page_status'];
		$id = $_POST['id'];
		
		$days = 30;
		
		$loan_interest = $loan_amount * ($days/100);
		$loan_total_interest = $loan_interest + $loan_amount;
		
		$loan_term = $days;
		//$loan_due_date = '2015-01-18';
		$loan_due_date = date('Y-m-d',strtotime($loan_date) + (24 * 3600 * $loan_term));
		$loan_due_date_day = date("l", strtotime($loan_due_date));
		//echo $loan_due_date."<br />";
		//echo $loan_due_date_day."<br />";
		
		if($loan_due_date_day == 'Saturday'){
			$days = 2;
			$loan_term = $days;
			$loan_due_date = date('Y-m-d',strtotime($loan_due_date) + (24 * 3600 * $loan_term));
			//echo $loan_due_date."<br />";
		}
		else if($loan_due_date_day == 'Sunday'){
			$days = 1;
			$loan_term = $days;
			$loan_due_date = date('Y-m-d',strtotime($loan_due_date) + (24 * 3600 * $loan_term));
			//echo $loan_due_date."<br />";
		}
		else{
			$loan_term = $days;
			$loan_due_date = date('Y-m-d',strtotime($loan_date) + (24 * 3600 * $loan_term));
			//echo $loan_due_date."<br />";
		}
		
		$sql = mysql_query("select id, stations from users where mobile_no= '$phone_number' or dis_phone = '$phone_number' or alt_phone = '$phone_number'");
		//echo "select id, stations from users where mobile_no= '$phone_number'<br />";
		while ($row = mysql_fetch_array($sql))
		{
			$customer_id = $row['id'];	
			$customer_station = $row['stations'];	
		}
		
		$sql = mysql_query("select id from loan_code");
		while ($row = mysql_fetch_array($sql))
		{
			$loan_code_latest = $row['id'];	
		}
		
		$sql3="
		INSERT INTO loan_application (loan_date, loan_term, loan_due_date, customer_id, loan_mobile, initiation_fee, loan_amount, loan_interest, loan_total_interest, loan_status, loan_code, loan_mpesa_code, UID, customer_station, loan_creation)
		VALUES('$loan_date', '$loan_term', '$loan_due_date', '$customer_id', '$phone_number', '$initiation_fee', '$loan_amount', '$loan_interest', '$loan_total_interest', '$loan_status', '$loan_code_latest', '$receipt', '$userid', '$customer_station', '1')";
		
		$sql = mysql_query("select distinct loan_id from loan_application order by loan_id desc limit 1");
		while ($row = mysql_fetch_array($sql))
		{
			$loan_id_latest = $row['loan_id'];	
		}
		$sql4="insert into change_log(UID, table_name, table_id, transactiontime, comment)values('$userid', 'loan_applications', '$loan_id_latest', '$transactiontime', '$comment')";
		
		$sql = mysql_query("select loan_balance from overpayments_schedule where customer_id = '$user_id' order by id desc limit 1");
		while ($row = mysql_fetch_array($sql))
		{
			$loan_balance = $row['loan_balance'];	
		}
		//echo $loan_balance."<br />";
		if($loan_balance > 0){
			$loan_balance = -$loan_balance;
			$loan_total_interest = $loan_total_interest + $loan_balance;
			$sql20="update loan_application set early_settlement_surplus='$loan_balance', loan_total_interest='$loan_total_interest' where loan_code = '$loan_code_latest'";
		}
		
		$sql5="update loan_suspece_account set resolved = '1' WHERE id  = '$id'";
		
		$loan_code_latest = $loan_code_latest + 1;
		
		$sql15="update loan_code set id='$loan_code_latest'";
		
		//echo $sql3.'<br />';
		//echo $sql4.'<br />';
		//echo $sql5.'<br />';
		//echo $sql15.'<br />';
		//echo $sql20.'<br />';
		
		$result = mysql_query($sql3);
		$result = mysql_query($sql4);
		$result = mysql_query($sql5);
		$result = mysql_query($sql15);
		$result = mysql_query($sql20);
		
		$query = "loan_suspense.php";
		?>
		<script type="text/javascript">
			<!--
			/*alert("Either the Email Address or the Password do not match the records in the database or you have been disabled from the system, please contact the system admin at www.e-kodi.com/contact.php");*/
			document.location = "<?php echo $query ?>";
			//-->
		</script>
		<?php	
	}
}
	include_once('includes/footer.php');
?>
