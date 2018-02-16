<?php
	$userid = "";
	$adminstatus = 4;
	$property_manager_id = "";
	$passportfileupload = "";
	$resumefileupload = "";
	session_start();
	if (!empty($_SESSION)){
		$userid = $_SESSION["userid"] ;
		$adminstatus = $_SESSION["adminstatus"] ;
		$station = $_SESSION["station"] ;
		$username = $_SESSION["username"];
		$passportfileupload = $_SESSION["passportfileupload"];
		$resumefileupload = $_SESSION["resumefileupload"];
	}
	
	if (!empty($_GET)){	
		$mode = $_GET['mode'];
		$user_id = $_GET['user_id'];
		$status = $_GET['status'];
		$loan_balance = $_GET['loan_balance'];
	}
	
	$filter_month = date("m");
	$filter_year = date("Y");
	$filter_day = date("d");
	$current_date = $filter_year.'-'.$filter_month.'-'.$filter_day;
	
	include_once('includes/db_conn.php');
	include_once('includes/db_conn_dialer.php');
	$sql2 = mysql_query("select stations from users where id = '$user_id'", $dbh1);
	while($row = mysql_fetch_array($sql2)) {
		$stations = $row['stations'];		
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
		if ($mode=='edit'){
			$page_title = "Update Promise to Pay Detail(s)";
		}
		else{
			$page_title = "Create new Promise to Pay Detail(s)";
		}
		
		include_once('includes/header.php');
		
		?>		
		<div id="page">
			<div id="content">
				<div class="post">
				<h2><font color="#000A8B"><?php echo $page_title; ?></font></h2>
				<br />
				<?php if($mode == 'edit'){ ?>
					<p><img src="<?php echo $passportfileupload ?>" width="150px"></p>
				<?php } ?>
				<form id="frmOrder" name="frmOrder" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
				<table border="0" width="100%" cellspacing="2" cellpadding="2">	
				<input type="hidden" name="users_id" id="users_id" value="<?php echo $user_id	 ?>" />		
				<input type="hidden" name="page_status" id="page_status" value="<?php echo $mode ?>" />
				<input type="hidden" name="loan_balance" id="loan_balance" value="<?php echo $loan_balance ?>" />
					<tr bgcolor = #F0F0F6>
						<td valign='top' width="15%">Date to Pay/ Next Interaction</td>
						<td valign='top' width="35%" colspan="3">
							<input title="Enter Promise Date to Pay" value="<?php echo $ptp_date ?>" id="ptp_date" name="ptp_date" type="text" maxlength="100" class="main_input" size="35" />
						</td>
					</tr>
					
					<tr >
						<td valign='top' width="15%">Interaction Category *</td>
						<td valign='top' width="35%" colspan="3">
							<select name='category' id='category'>
								<option value=""> </option>
								<option value="Customer Followup">Customer Followup</option>
								<option value="Promise to Pay">Promise to Pay</option>
								<option value="Training Followup">Training Followup</option>
								<option value="Spoof Calling">Spoof Calling</option>
								<option value="Surprise Visit">Surprise Visit</option>
								<option value="Asset List Confirmation">Asset List Confirmation</option>
							</select>
						</td>
						
					</tr>
					<tr>
						<td valign='top' width="15%">Loan Code: *</td>
						<td valign='top' width="35%">
							<select name='loan_code' id='loan_code'>
									<option value=''> </option>
									<?php
									//echo "<option value=''>" "</option>"; 																				
									$sql2 = mysql_query("select distinct loan_code, loan_amount from loan_application where customer_id = '$user_id' and loan_status != '13' order by loan_code asc");
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
						<td valign='top' width="15%">Call Outcome </td>
						<td valign='top' width="35%" colspan="3">
							<select name='call_outcome' id='call_outcome'>
								<?php
									if($mode == 'edit'){
								?>
									<option value="<?php echo $call_outcome ?>"><?php echo $call_outcome_name ?></option>
								<?php
								}
								else{
								?>
									<option value=''> </option>
								<?php
								}
								//echo "<option value=''>" "</option>"; 
								$sql2 = mysql_query("select id, reason_code from call_outcome order by reason_code asc", $dbh1);
								while($row = mysql_fetch_array($sql2)) {
									$id = $row['id'];
									$reason_code = $row['reason_code'];
									echo "<option value='$id'>".$reason_code."</option>"; 
								}
								?>
							
							</select>
						</td>
						
					</tr>
					<tr>
						<td valign='top'>If Call Back, enter Time (HH:MM, 24Hr Format))</td>
						<td valign='top' colspan="3">
							<select name='hour' id='hour'>
							<?php
								for ($x = 0; $x <= 24; $x++) {
									echo "<option value='$x'>".$x."</option>"; 
								} 
							?>
							</select>
							: 
							<select name='minute' id='minute'>
							<?php
								for ($x = 0; $x <= 60; $x++) {
									echo "<option value='$x'>".$x."</option>"; 
								} 
							?>
							</select>
						</td>
					</tr>
					<tr bgcolor = #F0F0F6>
						<td valign="top" width="15%">Promised Amount </td>
						<td valign="top" width="35%" colspan="3">
							<input title="Enter Promised Amount" value="<?php echo $promise_amount ?>" id="promise_amount" name="promise_amount" type="text" maxlength="100" class="main_input" size="35" />
						</td>
						
					</tr>
					<tr>
						<td valign='top'>Comments on the Call/ Visit *</td>
						<td valign='top' colspan="3">
							<textarea title="Enter Comments" name="comments" id="comments" cols="95" rows="5" class="textfield"><?php echo $comments ?></textarea>
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
				        frmvalidator.addValidation("category", "req", "Please enter the Interaction category");
				        frmvalidator.addValidation("comments", "req", "Please enter the comments on the call/ visit");				
				    </script>
				</form>
			</div>
			</div>
			<br class="clearfix" />
			</div>
		</div>
<?php
	if (!empty($_POST)) {
		$customer = $_POST['customer'];
		$category = $_POST['category'];
		if($_POST['ptp_date'] != ""){
			$pay_date = $_POST['ptp_date'];
			$pay_date = date('Y-m-d', strtotime(str_replace('-', '/', $pay_date)));
		}
		if($pay_date == '0000-00-00'){
			$pay_date = $current_date;
		}
		
		$loan_code = $_POST['loan_code'];
		$hour = $_POST['hour'];
		$minute = $_POST['minute'];
		$comments = $_POST['comments'];
		$comments = mysql_real_escape_string($comments);
		$call_outcome = $_POST['call_outcome'];
		$promise_amount = $_POST['promise_amount'];
		
		$loan_balance = $_POST['loan_balance'];
		$page_status = $_POST['page_status'];
		$users_id = $_POST['users_id'];
		
		$sql = mysql_query("select loan_code, loan_due_date from loan_application where loan_code = '$loan_code'", $dbh1);
		while ($row = mysql_fetch_array($sql))
		{
			$loan_code = $row['loan_code'];	
			$loan_due_date = $row['loan_due_date'];	
		}
		
		$date1 = strtotime($loan_due_date);
		$date2 = strtotime($current_date);
		$dateDiff = $date2 - $date1;
		$days = floor($dateDiff/(60*60*24));
	
		$dateArrears = $date2 - $date1;
		$Arrearsdays = floor($dateArrears/(60*60*24));
		
		if($Arrearsdays <= 7){
			$vintage = "CD 1";
		}
		else if($Arrearsdays <= 14){
			$vintage = "CD 2";
		}
		else if($Arrearsdays <= 22){
			$vintage = "CD 3";
		}
		else if($Arrearsdays <= 30){
			$vintage = "CD 4";
		}
		else if($Arrearsdays <= 37){
			$vintage = "CD 5";
		}
		else if($Arrearsdays <= 44){
			$vintage = "CD 6";
		}
		else if($Arrearsdays <= 51){
			$vintage = "CD 7";
		}
		else if($Arrearsdays <= 58){
			$vintage = "CD 8";
		}
		else if($Arrearsdays <= 65){
			$vintage = "CD 9";
		}
		else if($Arrearsdays <= 72){
			$vintage = "CD 10";
		}
		else if($Arrearsdays <= 79){
			$vintage = "CD 11";
		}
		else if($Arrearsdays <= 86){
			$vintage = "CD 12";
		}
		else if($Arrearsdays <= 93){
			$vintage = "CD 13";
		}
		else if($Arrearsdays <= 100){
			$vintage = "CD 14";
		}
		else if($Arrearsdays <= 106){
			$vintage = "CD 15";
		}
		else if($Arrearsdays <= 113){
			$vintage = "CD 16";
		}
		else if($Arrearsdays <= 120){
			$vintage = "CD 17";
		}
		else{
			$vintage = "CD 17";
		}
		
		if($category == 'Promise to Pay'){
			$sql3="
			INSERT INTO promise_to_pay (customer_id, category, loan_code, loan_balance, pay_date, comments, overdue_days, vintage, call_outcome, transactiontime, UID)
			VALUES('$users_id', '$category', '$loan_code', '$promise_amount', '$pay_date', '$comments', '$Arrearsdays', '$vintage', '$call_outcome',  '$transactiontime', '$userid')";
			$result = mysql_query($sql3, $dbh1);
			//echo $sql3."<br />";  
			
		}
		else{
			$sql3="
			INSERT INTO promise_to_pay (customer_id, category, loan_code, loan_balance, pay_date, comments, call_outcome, transactiontime, UID)
			VALUES('$users_id', '$category', '$loan_code', '$promise_amount', '$pay_date', '$comments', '$call_outcome',  '$transactiontime', '$userid')";
			$result = mysql_query($sql3, $dbh1);
			//echo $sql3."<br />";  
		}
		
		
		
		if($call_outcome == '1' || $call_outcome == '2' || $call_outcome == '6' || $call_outcome == '7'){
			$sql2 = mysql_query("select first_name, last_name, mobile_no, alt_phone, ref_phone_number, ref_first_name, ref_last_name, ref_landlord_first_name, ref_landlord_last_name, ref_relationship, ref_landlord_phone, ref_landlord_relationship, collections_agent from users where id = '$users_id'", $dbh1);
			while($row = mysql_fetch_array($sql2)) {
				$collections_agent = $row['collections_agent'];		
				$mobile_no = $row['mobile_no'];
				$first_name = $row['first_name'];	
				$last_name = $row['last_name'];	
				$alt_phone = $row['alt_phone'];	
				$ref_first_name = $row['ref_first_name'];	
				$ref_last_name = $row['ref_last_name'];	
				$ref_phone_number = $row['ref_phone_number'];	
				$ref_relationship = $row['ref_relationship'];	
				$ref_landlord_first_name = $row['ref_landlord_first_name'];	
				$ref_landlord_last_name = $row['ref_landlord_last_name'];	
				$ref_landlord_phone = $row['ref_landlord_phone'];	
				$ref_landlord_relationship = $row['ref_landlord_relationship'];	
			}
			
			$sql = mysql_query("select campaign_id, list_id from user_profiles where id = '$collections_agent'", $dbh1);
			while ($row = mysql_fetch_array($sql))
			{
				$campaign_id = $row['campaign_id'];	
				$list_id = $row['list_id'];	
			}
			
			$sql = mysql_query("select status from dial_table where customer_id = '$users_id'", $dbh1);
			while ($row = mysql_fetch_array($sql))
			{
				$call_status = $row['status'];	
			}

			if($collections_agent != 0 || $collections_agent != ''){
				$mobile_no = substr($mobile_no, 3);
				$alt_phone = substr($alt_phone, 3);
				$ref_phone_number = substr($ref_phone_number, 3);
				$ref_landlord_phone = substr($ref_landlord_phone, 3);
				if($call_status == '1' || $mobile_no != ""){
					$transactiontime = date("Y-m-d G:i:s");
					if($mobile_no != ""){
						$sql15="insert into vicidial_list Set entry_date = '$transactiontime', modify_date =  '$transactiontime', status = 'NEW',list_id = '$list_id', gmt_offset_now = '-5.00', called_since_last_reset = 'N', phone_code = '254', phone_number = '$mobile_no', first_name = '$first_name', last_name = '$last_name', called_count = '0', rank = '0', comments = '$first_name $last_name main phone number', loan_code = '$loan_code'";
						
						//echo $sql15."<br />";  
						$result = mysql_query($sql15, $dbh2);    
					}
					
					$sql16="update dial_table set status='2', dialed_number = '$mobile_no' WHERE customer_id  = '$users_id'";
		
					//echo $sql16."<br />";  
					$result = mysql_query($sql16, $dbh1);
				}
				else if($call_status == '2' || $alt_phone != ""){
					$transactiontime = date("Y-m-d G:i:s");
					if($alt_phone != ""){
						$sql15="insert into vicidial_list Set entry_date = '$transactiontime', modify_date =  '$transactiontime', status = 'NEW',list_id = '$list_id', gmt_offset_now = '-5.00', called_since_last_reset = 'N', phone_code = '254', phone_number = '$alt_phone', first_name = '$first_name', last_name = '$last_name', called_count = '0', rank = '0', comments = '$first_name $last_name alternate phone number', loan_code = '$loan_code'";
						
						//echo $sql15."<br />"; 
						$result = mysql_query($sql15, $dbh2);  
					}
					
					$sql16="update dial_table set status='3', dialed_number = '$alt_phone' WHERE customer_id  = '$users_id'";
					//echo $sql16."<br />";  
					$result = mysql_query($sql16, $dbh1);
				}
				else if($call_status == '3' || $ref_phone_number != ""){
					$transactiontime = date("Y-m-d G:i:s");
					if($ref_phone_number != ""){
						$sql15="insert into vicidial_list Set entry_date = '$transactiontime', modify_date =  '$transactiontime', status = 'NEW',list_id = '$list_id', gmt_offset_now = '-5.00', called_since_last_reset = 'N', phone_code = '254', phone_number = '$ref_phone_number', first_name = '$ref_first_name', last_name = '$ref_last_name', called_count = '0', rank = '0', comments = '$first_name $last_name first Refence Number: $ref_relationship', loan_code = '$loan_code'";
					
						//echo $sql15."<br />"; 
						$result = mysql_query($sql15, $dbh2);  
					}
					
					$sql16="update dial_table set status='4', dialed_number = '$ref_phone_number' WHERE customer_id  = '$users_id'";
					
					//echo $sql16."<br />";  
					$result = mysql_query($sql16, $dbh1);
				}
				else if($call_status == '4' || $ref_landlord_phone != ""){
					$transactiontime = date("Y-m-d G:i:s");
					if($ref_landlord_phone != ""){
						$sql15="insert into vicidial_list Set entry_date = '$transactiontime', modify_date =  '$transactiontime', status = 'NEW',list_id = '$list_id', gmt_offset_now = '-5.00', called_since_last_reset = 'N', phone_code = '254', phone_number = '$ref_landlord_phone', first_name = '$ref_landlord_first_name', last_name = '$ref_landlord_last_name', called_count = '0', rank = '0', comments = '$first_name $last_name second Refence Number: $ref_landlord_relationship', loan_code = '$loan_code'";
					
						//echo $sql15."<br />";  
						$result = mysql_query($sql15, $dbh2);    
					}
					
					$sql16="update dial_table set status='5', dialed_number = '$ref_phone_number' WHERE customer_id  = '$users_id'";
					
					//echo $sql16."<br />";  
					$result = mysql_query($sql16, $dbh1);
				}
			}
		}
		else if($call_outcome == '5'){
			$sql2 = mysql_query("select campaign_id, list_id from user_profiles where id = '$userid'", $dbh1);
			while($row = mysql_fetch_array($sql2)) {
				$campaign_id = $row['campaign_id'];		
				$list_id = $row['list_id'];
			}
			$sql2 = mysql_query("select lead_id from vicidial_list where loan_code = '$loan_code'", $dbh2);
			while($row = mysql_fetch_array($sql2)) {
				$lead_id = $row['lead_id'];		
			}
			if($hour != "" || $minute != ""){
				$pay_date = $pay_date.' '.$hour.':'.$minute.':00';
			}
			else{
				$pay_date = $pay_date.' 10:00:00';
			}
			$transactiontime = date("Y-m-d G:i:s");
			$sql15="insert into vicidial_callbacks Set lead_id = '$lead_id', list_id = '$list_id', campaign_id = '$campaign_id',status = 'LIVE', entry_time = '$pay_date', callback_time = '$pay_date', recipient = 'USERONLY', user_group = 'AGENTS', lead_status = 'CALLBK'";
					
			//echo $sql15."<br />";  
			$result = mysql_query($sql15, $dbh2);    
		}
		
		$query = "customer_loans.php?user_id=".$users_id;
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
