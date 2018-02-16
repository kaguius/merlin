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
			$reverse_id = $_GET['reverse_id'];
			$mode = $_GET['mode'];
		}
		include_once('includes/db_conn.php');
		$transactiontime = date("Y-m-d G:i:s");
		
		if ($mode=='edit'){
			$sql = mysql_query("select id, loan_date, loan_mobile, agent_mobile, loan_amount, reversal_date, UID, mpesa_code, loan_code, transactiontime from loan_reversal where id = '$reverse_id'");
			while ($row = mysql_fetch_array($sql))
			{
				$reverse_id = $row['id'];
				$loan_date = $row['loan_date'];
				$loan_mobile = $row['loan_mobile'];
				$agent_mobile = $row['agent_mobile'];
				$loan_amount = $row['loan_amount'];
				$reversal_date = $row['reversal_date'];
				$UID = $row['UID'];
				$mpesa_code = $row['mpesa_code'];
				$loan_code = $row['loan_code'];
				
				$sql2 = mysql_query("select concat(first_name, ' ', last_name)staff_name from user_profiles where id = '$UID'");
				while ($row = mysql_fetch_array($sql2))
				{
					$staff_name = $row['staff_name'];	
				}
			}
			$page_title = "Update MPESA Reverse Detail(s)";
		}
		else{
			$page_title = "Create new Agent Detail(s)";
		}
		include_once('includes/header.php');
		?>		
		<div id="page">
			<div id="content">
				<div class="post">
				<h2><font color="#000A8B"><?php echo $page_title; ?></font></h2>
				<br />
				<form id="frmOrder" name="frmOrder" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
				<table border="0" width="100%" cellspacing="2" cellpadding="2">	
				<input type="hidden" name="reverse_id" id="reverse_id" value="<?php echo $reverse_id ?>" />		
				<input type="hidden" name="page_status" id="page_status" value="<?php echo $mode ?>" />
				    	<tr bgcolor = #F0F0F6>
						<td valign='top' width="15%">Loan Date *</td>
						<td valign='top' width="35%">
							<input title="Enter Agent Name" value="<?php echo $loan_date ?>" id="loan_date" name="loan_date" type="text" maxlength="100" class="main_input" size="35" />
						</td>
						<td valign="top" width="15%">Mobile Number *</td>
						<td valign="top" width="35%">
							<input title="Enter Mobile Number" value="<?php echo $loan_mobile ?>" id="loan_mobile" name="loan_mobile" readonly type="text" maxlength="100" class="main_input" size="35" />
						</td>
					</tr>
					<tr>
						<td valign='top' width="15%">Agent Mobile *</td>
						<td valign='top' width="35%">
							<input title="Enter Agent Name" value="<?php echo $agent_mobile ?>" id="agent_mobile" name="agent_mobile" type="text" maxlength="100" class="main_input" size="35" />
						</td>
						<td valign="top" width="15%">Loan Amount *</td>
						<td valign="top" width="35%">
							<input title="Enter Mobile Number" value="<?php echo $loan_amount ?>" id="loan_amount" name="loan_amount" readonly type="text" maxlength="100" class="main_input" size="35" />
						</td>
					</tr>
					<tr bgcolor = #F0F0F6>
						<td valign='top' >Staff Name*</td>
						<td valign='top'>
							<input title="Enter Gender" value="<?php echo $staff_name?>" id="staff_name" name="staff_name" type="text" maxlength="100" class="main_input" size="35" />
						</td>
						<td valign="top" width="15%">Reversal Date *</td>
						<td valign="top" width="35%">
							<input title="Enter Gender" value="<?php echo $reversal_date?>" id="reversal_date" name="reversal_date" type="text" maxlength="100" class="main_input" size="35" />
						</td>
					</tr>
					<tr>
						<td colspan = '4'>
							<strong>Effect the Reversal Details</strong>
						</td>
					</tr>
					<tr bgcolor = #F0F0F6>
						<td valign='top' >Reverse MPESA Code*</td>
						<td valign='top'>
							<input title="Enter Gender" value="<?php echo $reverse_mpesa_code?>" id="reverse_mpesa_code" name="reverse_mpesa_code" type="text" maxlength="100" class="main_input" size="35" />
						</td>
						<td valign="top" width="15%">Reverse Mobile *</td>
						<td valign="top" width="35%">
							<input title="Enter Gender" value="<?php echo $reversal_mobile?>" id="reversal_mobile" name="reversal_mobile" type="text" maxlength="100" class="main_input" size="35" />
						</td>
					</tr>
					<tr>
						<td valign='top' >Reverse Date*</td>
						<td valign='top'>
							<input title="Enter Agent Name" value="<?php echo $loan_rep_date ?>" id="loan_rep_date" name="loan_rep_date" type="text" maxlength="100" class="main_input" size="35" />
						</td>
						<td valign="top" width="15%">Paybill Number *</td>
						<td valign="top" width="35%">
							<input title="Enter Gender" value="<?php echo $paybill_number?>" id="paybill_number" name="paybill_number" type="text" maxlength="100" class="main_input" size="35" />
						</td>
					</tr>
					<tr bgcolor = #F0F0F6>
						<td valign='top' >Reverse Amount *</td>
						<td valign='top' colspan = '3'>
							<input title="Enter Gender" value="<?php echo $reverse_amount?>" id="reverse_amount" name="reverse_amount" type="text" maxlength="100" class="main_input" size="35" />
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
		$reverse_mpesa_code = $_POST['reverse_mpesa_code'];
		$reversal_mobile = $_POST['reversal_mobile'];
		$loan_rep_date = $_POST['loan_rep_date'];
		$loan_rep_date = date('Y-m-d', strtotime(str_replace('-', '/', $loan_rep_date)));
		$paybill_number = $_POST['paybill_number'];
		$reverse_amount = $_POST['reverse_amount'];
		
		$reverse_id = $_POST['reverse_id'];
		$page_status = $_POST['page_status'];
		
		if($page_status == 'edit'){
			$sql3="
			update loan_reversal set reverse_mpesa_code='$reverse_mpesa_code', reverse_mobile= '$reversal_mobile', reverse_date= '$loan_rep_date', paybill_number='$paybill_number', reverse_amount='$reverse_amount' WHERE id  = '$reverse_id'";
			
		}
		else{
			
		}
		
		
		
		//echo $sql3.'<br />';
		//echo $sql4.'<br />';
		//echo $sql5.'<br />';
		$result = mysql_query($sql3);
		//$result = mysql_query($sql4);
		//$result = mysql_query($sql5);
		$query = "reverse_details.php";
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
