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
			$extension_id = $_GET['extension_id'];
			$mode = $_GET['mode'];
			$user_id = $_GET['user_id'];
		}
		include_once('includes/db_conn.php');
		$sql = mysql_query("select first_name, last_name, mobile_no, dis_phone, affordability from users where id = '$user_id'");
		while ($row = mysql_fetch_array($sql))
		{
			$first_name = $row['first_name'];
			$last_name = $row['last_name'];
			$first_name = ucwords(strtolower($first_name));	
			$last_name = ucwords(strtolower($last_name));
			$name = $first_name.' '.$last_name;		
			$mobile_no = $row['mobile_no'];
			$dis_phone = $row['dis_phone'];
			$affordability = $row['affordability'];
		}
		$sql = mysql_query("select loan_total_interest, loan_code, loan_due_date from loan_application where customer_id = '$user_id' and loan_status = '2' order by loan_id desc limit 1");
		while ($row = mysql_fetch_array($sql))
		{
			$latest_loan = $row['loan_total_interest'];
			$loan_code = $row['loan_code'];
			$loan_due_date = $row['loan_due_date'];
		}
		$transactiontime = date("Y-m-d G:i:s");
		if ($mode=='edit'){
			$sql = mysql_query("select customer_id, loan_code, days, reason, comment, transactiontime from loan_extensions where id = '$extension_id'");
			while ($row = mysql_fetch_array($sql))
			{					
				$loan_id = $row['loan_id'];
				$extension_date = $row['transactiontime'];
				$extension_days = $row['days'];
				$loan_code = $row['loan_code'];
				$reason = $row['reason'];
				$comment = $row['comment'];
				$sql2 = mysql_query("select id, extension from extension_reason where id = '$reason'");
				while ($row = mysql_fetch_array($sql2))
				{
					$extension_reason_id = $row['id'];
					$extension_reason = $row['extension'];
				}
			}
			$page_title = "Update Loan Repayment Extension Request(s)";
		}
		else{
			$page_title = "Create new Repayment Extension Request(s)";
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
				<input type="hidden" name="extension_id" id="extension_id" value="<?php echo $extension_id ?>" />
				<input type="hidden" name="user_id" id="user_id" value="<?php echo $user_id ?>" />			
				<input type="hidden" name="page_status" id="page_status" value="<?php echo $mode ?>" />
				<input type="hidden" name="loan_code" id="loan_code" value="<?php echo $loan_code ?>" />
				<input type="hidden" name="loan_due_date" id="loan_due_date" value="<?php echo $loan_due_date ?>" />
				<?php if($mode == 'edit'){ ?>
					<tr bgcolor = #F0F0F6>
						<td valign='top' >Extension Date </td>
						<td valign='top'>
							<input title="Enter Loan Date" value="<?php echo $extension_date ?>" id="extension_date" name="extension_date" type="text" maxlength="100" class="main_input" readonly size="35" />
						</td>
						<td valign='top' >Loan Code </td>
						<td valign='top' colspan= '3'>
							<input title="Enter Loan Date" value="<?php echo $loan_code ?>" id="loan_code" name="loan_code" type="text" maxlength="100" class="main_input" readonly size="35" />
						</td>
					</tr>
				<?php } ?>
					<tr bgcolor = #F0F0F6>
						<td valign="top" width="15%">Extension Days *</td>
						<td valign="top" width="35%">
							<select name='extension_days' id='extension_days'>
								<?php
								if($mode == 'edit'){
								?>
									<option value="<?php echo $extension_days ?>"><?php echo $extension_days ?></option>
									<option value=''> </option>	
								<?php
								}
								else{
								?>
									<option value=''> </option>
								<?php
								}
									for ($x=0; $x<=7; $x++) {
									  echo "<option value='$x'>".$x."</option>"; 
									} 
								?>
							</select>
							<input value="<?php echo $extension_days ?>" id="old_extension_days" name="old_extension_days" type="hidden" />
						</td>
						<td valign='top' width="15%">Reason Code: *</td>
						<td valign='top' width="35%">
							<select name='extension_reason' id='extension_reason'>
								<?php
									if($mode == 'edit'){
								?>
									<option value="<?php echo $extension_reason_id ?>"><?php echo $extension_reason ?></option>
									<option value=''> </option>	
								<?php
									}
									else{
									?>
									<option value=''> </option>
									<?php
									}
									//echo "<option value=''>" "</option>"; 																				
									$sql2 = mysql_query("select id, extension from extension_reason order by extension asc");
									while($row = mysql_fetch_array($sql2)) {
										$extension_id = $row['id'];
										$extension = $row['extension'];
										echo "<option value='$extension_id'>".$extension."</option>"; 
									}
									?>
								</select>
						</td>
					</tr>
					
					<tr>
						<td valign='top' >Comment *</td>
						<td valign='top' colspan="3">
							<textarea title="Enter Comment" name="comment" id="comment" cols="45" rows="5" class="textfield"><?php echo $comment ?></textarea>
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
				<script  type="text/javascript">
							var frmvalidator = new Validator("frmOrder");
							frmvalidator.addValidation("loan_amount","req","Please enter the Loan Amount");
							frmvalidator.addValidation("Loan_Total_Interest","req","Please enter the Loan + Interest Amount");
							frmvalidator.addValidation("comment","req","Please enter the Comment");
							//frmvalidator.addValidation("tenant_status","req","Please enter the Tenant Status");					
						</script>
				</form>
				</form>
			</div>
			</div>
			<br class="clearfix" />
			</div>
		</div>
<?php
	if (!empty($_POST)) {
		$extension_days = $_POST['extension_days'];
		$old_extension_days = $_POST['old_extension_days'];
		$extension_reason = $_POST['extension_reason'];
		$comment = $_POST['comment'];
		$loan_due_date = $_POST['loan_due_date'];

		$page_status = $_POST['page_status'];
		$user_id = $_POST['user_id'];
		$extension_id = $_POST['extension_id'];
		$loan_code = $_POST['loan_code'];
		
		echo $loan_due_date."<br />";
		
		$new_loan_due_date = date('Y-m-d',strtotime($loan_due_date) + (24 * 3600 * $extension_days));
		
		if($page_status == 'edit'){
			$sql = mysql_query("select distinct loan_id, loan_total_interest from loan_application where loan_code = '$loan_code'");
			while ($row = mysql_fetch_array($sql))
			{
				$loan_id_latest = $row['loan_id'];	
				$loan_total_interest = $row['loan_total_interest'];	
			}
			
			$extension_fee = 0;
			$sql = mysql_query("select fee from feez where category = 'extension_fee'");
			while ($row = mysql_fetch_array($sql))
			{
				$extension_fee = $row['fee'];
			}
			$loan_total_interest = $loan_total_interest + $extension_fee;
			
			$sql3="
			update loan_extensions set days='$extension_days', reason='$extension_reason' WHERE id  = '$extension_id'";
			
			if($old_extension_days != $extension_days){
				$sql4="
				update loan_application set loan_due_date='$new_loan_due_date', loan_extension = '300', loan_total_interest = '$loan_total_interest' WHERE loan_code  = '$loan_code'";
				
				$sql5="insert into change_log(UID, table_name, table_id, transactiontime, comment)values('$userid', 'loan_applications', '$loan_id_latest', '$transactiontime', '$comment')";
			}
			
		}
		else{
			$sql = mysql_query("select distinct loan_id, loan_total_interest from loan_application where loan_code = '$loan_code'");
			while ($row = mysql_fetch_array($sql))
			{
				$loan_id_latest = $row['loan_id'];	
				$loan_total_interest = $row['loan_total_interest'];	
			}
			
			echo $loan_total_interest."<br />";
			$loan_total_interest = $loan_total_interest + 300;
			
			$sql3="
			INSERT INTO loan_extensions (customer_id, loan_code, days, reason, comment, UID, transactiontime)
			VALUES('$user_id', '$loan_code', '$extension_days', '$extension_reason', '$comment', '$userid', '$transactiontime')";
			
			$sql4="
			update loan_application set loan_due_date='$new_loan_due_date', loan_extension = '300', loan_total_interest = '$loan_total_interest' WHERE loan_code  = '$loan_code'";
			
			$sql5="insert into change_log(UID, table_name, customer_id, variable, old_value, new_value, transactiontime, comment)values('$userid', 'extension_request', '$user_id', 'extension_request', '0', '$extension_days', '$transactiontime', '$comment')";
			
			$sql = mysql_query("select affordability from users where id = '$user_id'");
			while ($row = mysql_fetch_array($sql))
			{
				$affordability = $row['affordability'];	
			}
			
			$affordability = $affordability - 5000;
			
			$sql6="update users set affordability='$affordability' WHERE id  = '$user_id'";
		}
		
		//echo $sql3."<br />";
		//echo $sql4."<br />";
		//echo $sql5."<br />";
		//echo $sql6."<br />";
		$result = mysql_query($sql3);
		$result = mysql_query($sql4);
		$result = mysql_query($sql5);
		$result = mysql_query($sql6);
		$query = "customer_loans.php?user_id=$user_id";
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
