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
		$sql = mysql_query("select first_name, last_name, mobile_no, dis_phone, affordability, loan_officer, collections_officer, stations from users where id = '$user_id'");
		while ($row = mysql_fetch_array($sql))
		{
			$first_name = $row['first_name'];
			$last_name = $row['last_name'];
			$first_name = ucwords(strtolower($first_name));	
			$last_name = ucwords(strtolower($last_name));
			$customer_name = $first_name.' '.$last_name;		
			$mobile_no = $row['mobile_no'];
			$dis_phone = $row['dis_phone'];
			$affordability = $row['affordability'];
			$loan_officer_id = $row['loan_officer'];
			$collections_officer_id = $row['collections_officer'];
			$customer_station = $row['stations'];
		}
		$transactiontime = date("Y-m-d G:i:s");
		if ($mode=='edit'){
			$page_title = "Update Loan Repayment Extension Request(s)";
		}
		else{
			$page_title = "Create new Customer Waiver(s)";
		}
		include_once('includes/header.php');
		?>		
		<div id="page">
			<div id="content">
				<div class="post">
				<h2><font color="#000A8B"><?php echo $page_title; ?></font></h2>
				<h3>Customer Name: <?php echo $customer_name ?>, Disbursement #: <?php echo $dis_phone ?></h3>
				<br />
				<form id="frmOrder" name="frmOrder" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
				<table border="0" width="100%" cellspacing="2" cellpadding="2">	
				<input type="hidden" name="user_id" id="user_id" value="<?php echo $user_id ?>" />			
				<input type="hidden" name="page_status" id="page_status" value="<?php echo $mode ?>" />
					<tr bgcolor = #F0F0F6>
						<td valign='top' >Waive Date </td>
						<td valign='top'>
							<input title="Enter Loan Date" value="<?php echo $waiver_date ?>" id="waiver_date" name="waiver_date" type="text" maxlength="100" class="main_input"  size="35" />
						</td>
					</tr>
					<tr>
						<td valign='top' width="15%">Loan Code: *</td>
						<td valign='top' width="35%">
							<select name='loan_code' id='loan_code'>
									<option value=''> </option>
									<?php
									//echo "<option value=''>" "</option>"; 																				
									$sql2 = mysql_query("select distinct loan_code, loan_amount from loan_application where customer_id = '$user_id' and loan_status != '13' and loan_status != '12' and loan_status != '11' and loan_status != '14' order by loan_code asc");
									while($row = mysql_fetch_array($sql2)) {
										$loan_code = $row['loan_code'];
										$loan_amount = $row['loan_amount'];
										echo "<option value='$loan_code'>".$loan_code." - ".number_format($loan_amount, 2)."</option>"; 
									}
									?>
								</select>
						</td>
					</tr>
					<tr bgcolor = #F0F0F6>
						<td valign='top' >Waive Amount *</td>
						<td valign='top'>
							<input title="Enter Gender" value="<?php echo $waive_amount ?>" id="waive_amount" name="waive_amount" type="text" maxlength="100" class="main_input" size="35" />
						</td>
					</tr>
					<tr>
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
						<td valign='top'>
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
		$waiver_date = $_POST['waiver_date'];
		$waiver_date = date('Y-m-d', strtotime(str_replace('-', '/', $waiver_date)));
		$loan_code = $_POST['loan_code'];
		$waive_amount = $_POST['waive_amount'];
		$comment = $_POST['comment'];
		$extension_reason = $_POST['extension_reason'];

		$page_status = $_POST['page_status'];
		$user_id = $_POST['user_id'];
		
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
			$sql3="
			INSERT INTO waiver_table (waiver_date, loan_code, waive_amount, customer_id, UID, transactiontime, extension_reason) 
			VALUES('$waiver_date', '$loan_code', '$waive_amount', '$user_id', '$userid', '$transactiontime', '$extension_reason')";
			
			
			$sql = mysql_query("select loan_total_interest from loan_application where loan_code = '$loan_code'");
			while ($row = mysql_fetch_array($sql))
			{
				$loan_total_interest = $row['loan_total_interest'];
			}
			
			$sql = mysql_query("select waiver from loan_application where loan_code = '$loan_code'");
			while ($row = mysql_fetch_array($sql))
			{
				$existing_waiver = $row['waiver'];
			}
			
			$loan_total_interest = $loan_total_interest - $waive_amount;
			$waive_amount_figure = -$waive_amount;
			//$waive_amount_figure = $waive_amount_figure + $existing_waiver;
			
			$sql4="update loan_application set waiver='$waive_amount_figure', loan_total_interest = '$loan_total_interest' WHERE loan_code  = '$loan_code'";
			
			$sql5="insert into change_log(UID, table_name, customer_id, variable, old_value, new_value, transactiontime, comment)values('$userid', 'waiver_table', '$user_id', 'waiver', '0', '$waive_amount_figure', '$transactiontime', '$comment')";
			
		}
		
		//echo $sql3."<br />";
		//echo $sql4."<br />";
		$result = mysql_query($sql3);
		$result = mysql_query($sql4);
		$result = mysql_query($sql5);
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
