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
			$loan_id = $_GET['loan_id'];
			$loan_sus_id = $_GET['loan_sus_id'];
		}
		include_once('includes/db_conn.php');
		$transactiontime = date("Y-m-d G:i:s");
		if($loan_id != ""){
			$sql = mysql_query("select loan_id, loan_mpesa_code, loan_date, loan_mobile, loan_amount, loan_code from loan_application where loan_id = '$loan_id'");
			while ($row = mysql_fetch_array($sql))
			{
				$loan_date = $row['loan_date'];					
				$loan_mobile = $row['loan_mobile'];
				$loan_amount = $row['loan_amount'];
				$loan_code = $row['loan_code'];
				$loan_mpesa_code = $row['loan_mpesa_code'];
			}
		}
		else{
			$sql = mysql_query("select loan_date, phone_number, loan_amount from loan_suspece_account where id = '$loan_sus_id'");
			while ($row = mysql_fetch_array($sql))
			{
				$loan_date = $row['loan_date'];					
				$loan_mobile = $row['phone_number'];
				$loan_amount = $row['loan_amount'];
				$loan_code = $row['loan_code'];
			}

		}
		$page_title = "Create new Loan Reversal Detail(s)";
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
				<input type="hidden" name="loan_id" id="loan_id" value="<?php echo $loan_id ?>" />	
				<input type="hidden" name="loan_sus_id" id="loan_sus_id" value="<?php echo $loan_sus_id ?>" />			
				<input type="hidden" name="page_status" id="page_status" value="<?php echo $mode ?>" />
				    	<tr bgcolor = #F0F0F6>
						<td valign='top' width="15%">Loan Date *</td>
						<td valign='top' width="35%">
							<input title="Enter Loan Date" value="<?php echo $loan_date ?>" id="loan_date" name="loan_date" type="text" maxlength="100" class="main_input" size="35" />
							<input value="<?php echo $loan_date ?>" id="old_loan_date" name="old_loan_date" type="hidden" />
						</td>
						<td valign="top" width="15%">Mobile Number *</td>
						<td valign="top" width="35%">
							<input title="Enter Mobile Number" value="<?php echo $loan_mobile ?>" id="loan_mobile" name="loan_mobile" type="text" maxlength="100" class="main_input" size="35" />
							<input value="<?php echo $loan_mobile ?>" id="old_loan_mobile" name="old_loan_mobile" type="hidden" />
						</td>
					</tr>
					<tr>
						<td valign='top' width="15%">Customer Mobile *</td>
						<td valign='top' width="35%">
							<input title="Enter Agent Mobile" value="<?php echo $loan_mobile ?>" id="agent_mobile" name="agent_mobile" type="text" maxlength="100" class="main_input" size="35" />
							<input value="<?php echo $agent_mobile ?>" id="old_agent_mobile" name="old_agent_mobile" type="hidden" />
						</td>
						<td valign="top" width="15%">Loan Amount *</td>
						<td valign="top" width="35%">
							<input title="Enter Loan Amount" value="<?php echo $loan_amount ?>" id="loan_amount" name="loan_amount" type="text" maxlength="100" class="main_input" size="35" />
							<input value="<?php echo $loan_amount ?>" id="old_loan_amount" name="old_loan_amount" type="hidden" />
						</td>
					</tr>
					<tr bgcolor = #F0F0F6>
						<td valign='top' >Loan MPESA ID *</td>
						<td valign='top'>
							<input title="Enter Loan MPESA ID" value="<?php echo $loan_mpesa_code ?>" id="loan_mpesa_code" name="loan_mpesa_code" type="text" maxlength="100" class="main_input" size="35" />
							<input value="<?php echo $reversal_date ?>" id="old_reversal_date" name="old_reversal_date" type="hidden" />
						</td>
						<td valign='top' >Reversal Date *</td>
						<td valign='top'>
							<input title="Enter Loan Reversal Date" value="<?php echo $reversal_date ?>" id="reversal_date" name="reversal_date" type="text" maxlength="100" class="main_input" size="35" />
							<input value="<?php echo $reversal_date ?>" id="old_reversal_date" name="old_reversal_date" type="hidden" />
						</td>
					</tr>
					<tr>
						<td valign='top' width="15%">Authorised By *</td>
						<td valign='top' width="35%">
							<input title="Enter MPESA ID" value="<?php echo $authorization ?>" id="authorization" name="authorization" type="text" maxlength="100" class="main_input" size="35" />
							<input value="<?php echo $authorization ?>" id="old_mpesa_id" name="old_mpesa_id" type="hidden" />
						</td>
						<td valign="top" width="15%">Finalised By *</td>
						<td valign="top" width="35%">
							<input title="Enter System ID" value="<?php echo $finalized ?>" id="finalized" name="finalized" type="text" maxlength="100" class="main_input" size="35" />
							<input value="<?php echo $system_id ?>" id="old_system_id" name="old_system_id" type="hidden" />
						</td>
					</tr>
					<tr bgcolor = #F0F0F6>
						<td valign='top' width="15%">Reversal MPESA ID *</td>
						<td valign='top' width="35%">
							<input title="Enter MPESA ID" value="<?php echo $mpesa_id ?>" id="mpesa_id" name="mpesa_id" type="text" maxlength="100" class="main_input" size="35" />
							<input value="<?php echo $mpesa_id ?>" id="old_mpesa_id" name="old_mpesa_id" type="hidden" />
						</td>
						<td valign="top" width="15%">Loan Code *</td>
						<td valign="top" width="35%">
							<input title="Enter System ID" value="<?php echo $loan_code ?>" id="system_id" name="system_id" type="text" maxlength="100" class="main_input" size="35" />
							<input value="<?php echo $system_id ?>" id="old_system_id" name="old_system_id" type="hidden" />
						</td>
					</tr>
					<tr >
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
		$loan_date = $_POST['loan_date'];
		$loan_date = date('Y-m-d', strtotime(str_replace('-', '/', $loan_date)));
		$loan_mobile = $_POST['loan_mobile'];
		$agent_mobile = $_POST['agent_mobile'];
		$loan_amount = $_POST['loan_amount'];
		$loan_mpesa_code = $_POST['loan_mpesa_code'];
		$reversal_date = $_POST['reversal_date'];
		$reversal_date = date('Y-m-d', strtotime(str_replace('-', '/', $reversal_date)));
		$authorization = $_POST['authorization'];
		$finalized = $_POST['finalized'];
		$mpesa_id = $_POST['mpesa_id'];
		$system_id = $_POST['system_id'];
		$paybill = $_POST['paybill'];

		$page_status = $_POST['page_status'];
		$loan_id = $_POST['loan_id'];
		$loan_sus_id = $_POST['loan_sus_id'];
		
		if($page_status == 'edit'){
			$sql3="
			update agent set agent_name='$agent_name', agent_gender='$agent_gender', agent_level_amount='$agent_level_amount', agent_location='$agent_location' WHERE agent_id  = '$agent_id'";
			
			$sql5="insert into refferals(ref_date, ref_mobile, ref_referee_mobile, ref_type)values('$transactiontime', '$agent_referrer', '$agent_mobile_no', '1')";
			
			$sql4="insert into change_log(UID, table_name, table_id, transactiontime)values('$userid', 'agent', '$agent_id', '$transactiontime')";
		}
		else{
			$sql3="
			INSERT INTO loan_reversal_table (loan_date, loan_mobile, agent_mobile, loan_amount, loan_mpesa_code, reversal_date, authorization, finalized, mpesa_id, system_id, paybill, transactiontime, UID)
			VALUES('$loan_date', '$loan_mobile', '$agent_mobile', '$loan_amount', '$loan_mpesa_code', '$reversal_date', '$authorization', '$finalized', '$mpesa_id', '$system_id', '$paybill', '$transactiontime', '$userid')";
			
			$sql = mysql_query("select distinct id from loan_reversal_table order by id desc limit 1");
			while ($row = mysql_fetch_array($sql))
			{
				$id_latest = $row['id'];	
			}
				
			$sql4="insert into change_log(UID, table_name, table_id, transactiontime)values('$userid', 'loan_reversal_table', '$id_latest', '$transactiontime')";
		}
		
		if($loan_id != ""){
			$sql5="delete from loan_application where loan_id = '$loan_id' and loan_code= '$system_id'";
		}
		else{
			$sql5="delete from loan_suspece_account where id = '$loan_sus_id'";
		}
		
		//echo $sql3.'<br />';
		//echo $sql4.'<br />';
		//echo $sql5.'<br />';
		$result = mysql_query($sql3);
		$result = mysql_query($sql4);
		$result = mysql_query($sql5);
		$query = "loan_reversals.php";
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
