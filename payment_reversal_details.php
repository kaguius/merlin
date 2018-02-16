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
			$loan_rep_id = $_GET['loan_rep_id'];
			$loan_rep_sus_id = $_GET['loan_rep_sus_id'];
		}
		include_once('includes/db_conn.php');
		$transactiontime = date("Y-m-d G:i:s");
		if($loan_rep_id != ""){
			$sql = mysql_query("select loan_rep_id, loan_rep_mobile, loan_rep_amount from loan_repayments where loan_rep_id = '$loan_rep_id'");
			while ($row = mysql_fetch_array($sql))
			{
				$loan_rep_mobile = $row['loan_rep_mobile'];					
				$loan_rep_amount = $row['loan_rep_amount'];
			}
		}
		else{
			$sql = mysql_query("select paid_in, other_party_info from suspence_accounts where id = '$loan_rep_sus_id'");
			while ($row = mysql_fetch_array($sql))
			{
				$other_party_info = $row['other_party_info'];					
				$loan_rep_amount = $row['paid_in'];
				$loan_rep_mobile = substr($other_party_info, 1, 10);
				$loan_rep_mobile = "254".$loan_rep_mobile;
			}
		}
		$page_title = "Create new Payment Reversal Detail(s)";
		include_once('includes/header.php');
		?>		
		<div id="page">
			<div id="content">
				<div class="post">
				<h2><font color="#000A8B"><?php echo $page_title; ?></font></h2>
				<br />
				<form id="frmOrder" name="frmOrder" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
				<table border="0" width="100%" cellspacing="2" cellpadding="2">	
				<input type="hidden" name="agent_id" id="agent_id" value="<?php echo $agent_id ?>" />		
				<input type="hidden" name="page_status" id="page_status" value="<?php echo $mode ?>" />
				<input type="hidden" name="loan_rep_id" id="loan_rep_id" value="<?php echo $loan_rep_id ?>" />	
				<input type="hidden" name="loan_rep_sus_id" id="loan_rep_sus_id" value="<?php echo $loan_rep_sus_id ?>" />		
				    	<tr bgcolor = #F0F0F6>
						<td valign='top' width="15%">MPESA ID *</td>
						<td valign='top' width="35%">
							<input title="Enter MPESA ID" value="<?php echo $mpesa_id ?>" id="mpesa_id" name="mpesa_id" type="text" maxlength="100" class="main_input" size="35" />
							<input value="<?php echo $mpesa_id ?>" id="old_mpesa_id" name="old_mpesa_id" type="hidden" />
						</td>
						<td valign="top" width="15%">Mobile Number *</td>
						<td valign="top" width="35%">
							<input title="Enter Mobile Number" value="<?php echo $loan_rep_mobile ?>" id="mobile_number" name="mobile_number" type="text" maxlength="100" class="main_input" size="35" />
							<input value="<?php echo $mobile_number ?>" id="old_mobile_number" name="old_mobile_number" type="hidden" />
						</td>
					</tr>
					<tr>
						<td valign="top" width="15%">Payment Amount *</td>
						<td valign="top" width="35%">
							<input title="Enter Payment Amount" value="<?php echo $loan_rep_amount ?>" id="payment_amount" name="payment_amount" type="text" maxlength="100" class="main_input" size="35" />
							<input value="<?php echo $payment_amount ?>" id="old_payment_amount" name="old_payment_amount" type="hidden" />
						</td>
						<td valign='top' >Reversal Date *</td>
						<td valign='top'>
							<input title="Enter Loan Date" value="<?php echo $loan_date ?>" id="loan_date" name="loan_date" type="text" maxlength="100" class="main_input" readonly size="35" />
							<input value="<?php echo $payment_date ?>" id="old_payment_date" name="old_payment_date" type="hidden" />
						</td>
					</tr>
					<tr bgcolor = #F0F0F6>
						<td valign='top' width="15%">Paybill *</td>
						<td valign='top' width="35%" colspan="3">
							<input title="Enter Paybill Number" value="<?php echo $paybill ?>" id="paybill" name="paybill" type="text" maxlength="100" class="main_input" size="35" />
							<input value="<?php echo $paybill ?>" id="old_paybill" name="old_paybill" type="hidden" />
						</td>
					</tr>
				</table>
				<table border="0" width="100%">
					<tr>
						<td valign="top">
							<button name="btnNewCard" id="button">Save</button>
						</td>
						<td align="right">
							<button name="reset" id="button2" type="reset">Reset</button>
						</td>		
					</tr>
				</table>
				</form>
			</div>
			</div>
			<br class="clearfix" />
			</div>
		</div>
<?php
	if (!empty($_POST)) {
		$mpesa_id = $_POST['mpesa_id'];
		$mobile_number = $_POST['mobile_number'];
		$payment_amount = $_POST['payment_amount'];
		$payment_date = $_POST['loan_date'];
		$payment_date = date('Y-m-d', strtotime(str_replace('-', '/', $payment_date)));
		$loan_reversal_id = $_POST['loan_reversal_id'];
		$paybill = $_POST['paybill'];

		$page_status = $_POST['page_status'];
		$loan_rep_id = $_POST['loan_rep_id'];
		$loan_rep_sus_id = $_POST['loan_rep_sus_id'];
		
		if($page_status == 'edit'){
			$sql3="
			update agent set agent_name='$agent_name', agent_gender='$agent_gender', agent_level_amount='$agent_level_amount', agent_location='$agent_location' WHERE agent_id  = '$agent_id'";
			
			$sql5="insert into refferals(ref_date, ref_mobile, ref_referee_mobile, ref_type)values('$transactiontime', '$agent_referrer', '$agent_mobile_no', '1')";
			
			$sql4="insert into change_log(UID, table_name, table_id, transactiontime)values('$userid', 'agent', '$agent_id', '$transactiontime')";
		}
		else{
			$sql3="
			INSERT INTO payment_reversal_table (mpesa_id, mobile_number, payment_amount, payment_date, paybill, transactiontime, UID)
			VALUES('$mpesa_id', '$mobile_number', '$payment_amount', '$payment_date', '$paybill', '$transactiontime', '$userid')";
			
			$sql = mysql_query("select distinct id from payment_reversal_table order by id desc limit 1");
			while ($row = mysql_fetch_array($sql))
			{
				$id_latest = $row['id'];	
			}
				
			$sql4="insert into change_log(UID, table_name, table_id, transactiontime)values('$userid', 'payment_reversal_table', '$id_latest', '$transactiontime')";
		}
		if($loan_rep_id != ""){
			$sql5="delete from loan_repayments where loan_rep_id = '$loan_rep_id'";
		}
		else{
			$sql5="delete from suspence_accounts where id = '$loan_rep_sus_id'";
		}
		
		//echo $sql3.'<br />';
		//echo $sql4.'<br />';
		//echo $sql5.'<br />';
		$result = mysql_query($sql3);
		$result = mysql_query($sql4);
		$result = mysql_query($sql5);
		$query = "payment_reversals.php";
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
