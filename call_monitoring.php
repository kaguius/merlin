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
		$id_status = $_GET['status'];
	}
	include_once('includes/db_conn.php');
	$sql2 = mysql_query("select stations from users where id = '$user_id'");
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
		if (!empty($_GET)){	
			$mode = $_GET['mode'];
			$user_id = $_GET['user_id'];
			$id_status = $_GET['status'];
		}
		$transactiontime = date("Y-m-d G:i:s");
		if ($mode=='edit'){
			$sql = mysql_query("select passportfileupload, resumefileupload, mobile_no, title, first_name, last_name, national_id, preffered_language, nickname, next_visit, marital, dependants, alt_phone, dis_phone, lead_outcome, owns, home_occupy, stations, status, loan_officer, collections_officer, next_visit from users where id = '$user_id'");
			while ($row = mysql_fetch_array($sql))
			{
				$passportfileupload = $row['passportfileupload'];					
				$resumefileupload = $row['resumefileupload'];
				$mobile_no = $row['mobile_no'];
				$title = $row['title'];
				$first_name = $row['first_name'];
				$first_name = ucwords(strtolower($first_name));
				$last_name = $row['last_name'];
				$last_name = ucwords(strtolower($last_name));
				$national_id = $row['national_id'];
				$preffered_language = $row['preffered_language'];
				$nickname = $row['nickname'];
				$date_of_birth = $row['next_visit'];
				$marital = $row['marital'];
				$dependants = $row['dependants'];
				$alt_phone = $row['alt_phone'];
				$dis_phone = $row['dis_phone'];
				$lead_outcome = $row['lead_outcome'];
				$loan_officer = $row['loan_officer'];
				$collections_officer = $row['collections_officer'];
				$owns = $row['owns'];
				$owns = ucwords(strtolower($owns));
				$home_occupy = $row['home_occupy'];
				$stations = $row['stations'];
				$status = $row['status'];
				$loan_officer = $row['loan_officer'];
				$collections_officer = $row['collections_officer'];
				$next_visit_date = $row['next_visit'];

				$sql2 = mysql_query("select id, first_name, last_name from user_profiles where id = '$loan_officer'");
				while ($row = mysql_fetch_array($sql2))
				{
					$loan_officer_id = $row['id'];
					$loan_first_name = $row['first_name'];
					$loan_last_name = $row['last_name'];
					$loan_officer = $loan_first_name." ".$loan_last_name;
				}
				$sql2 = mysql_query("select id, first_name, last_name from user_profiles where id = '$collections_officer'");
				while ($row = mysql_fetch_array($sql2))
				{
					$collections_officer_id = $row['id'];
					$col_first_name = $row['first_name'];
					$col_last_name = $row['last_name'];
					$collections_officer = $col_first_name." ".$col_last_name;
				}
			}
			$page_title = "Update Lead Detail(s)";
		}
		else{
			$page_title = "Call Monitoring Form";
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
				<input type="hidden" name="users_id" id="users_id" value="<?php echo $user_id	 ?>" />		
					<tr >
						<td valign='top' width="75%">Collections Officer: *</td>
						<td valign='top' width="25%">
							<select name='loan_officer' id='loan_officer'>
								<?php
									if($mode == 'edit'){
								?>
									<option value="<?php echo $loan_officer_id ?>"><?php echo $loan_officer ?></option>
									<option value=''> </option>	
								<?php
									}
									else{
									?>
									<option value=''> </option>
									<?php
									}
									//echo "<option value=''>" "</option>"; 										
									if($station == '3'){ 
										$sql2 = mysql_query("select user_profiles.id, first_name, last_name, stations.stations from user_profiles inner join stations on stations.id = user_profiles.station where title = '7' and user_status = '1'");
									}
									else if($title == '3' || $title == '8'){ 
										$sql2 = mysql_query("select user_profiles.id, first_name, last_name, stations.stations from user_profiles inner join stations on stations.id = user_profiles.station where title = '7' and user_status = '1' and station = '$station'");
									}
									else{										
										$sql2 = mysql_query("select id, first_name, last_name from user_profiles where station = '$station' and id = '$userid'");
									}
									while($row = mysql_fetch_array($sql2)) {
										$loan = $row['id'];
										$first_name = $row['first_name'];
										$last_name = $row['last_name'];
										if($station == '3'){ 
											$stations = $row['stations'];
											echo "<option value='$loan'>".$stations.": ".$first_name." ".$last_name."</option>"; 
										}
										else{
											echo "<option value='$loan'>".$first_name." ".$last_name."</option>"; 
										}
									}
									?>
								</select>
								<input value="<?php echo $loan_officer_id ?>" id="old_loan_officer" name="old_loan_officer" type="hidden" />
						</td>
					</tr>
					<tr>
						<td valign="top" width="15%">Follow script guidelines?</td>
						<td valign="top" width="35%">
							<select name='script_guidelines' id='script_guidelines'>
								<option value=''></option>
								<option value='4'>Excellent</option>
								<option value='3.2'>Good</option>
								<option value='2.4'>Fair</option>
								<option value='2'>Poor</option>
							</select>
						</td>
					</tr>
					<tr>
						<td valign="top" width="15%">Proffessional and courteous behaviour extended during the call?</td>
						<td valign="top" width="35%">
							<select name='national_id_back' id='national_id_back'>
								<option value=''></option>
								<option value='4'>Excellent</option>
								<option value='3.2'>Good</option>
								<option value='2.4'>Fair</option>
								<option value='2'>Poor</option>
							</select>
						</td>
					</tr>
					<tr>
						<td valign="top" width="15%">Communication clear, positive and convey confidence?</td>
						<td valign="top" width="35%">
							<select name='script_guidelines' id='script_guidelines'>
								<option value=''></option>
								<option value='4'>Excellent</option>
								<option value='3.2'>Good</option>
								<option value='2.4'>Fair</option>
								<option value='2'>Poor</option>
							</select>
						</td>
					</tr>
					<tr>
						<td valign="top" width="15%">Did the agent confirm the customer's contact (ID/phone number)</td>
						<td valign="top" width="35%">
							<select name='national_id_back' id='national_id_back'>
								<option value=''></option>
								<option value='4'>Excellent</option>
								<option value='3.2'>Good</option>
								<option value='2.4'>Fair</option>
								<option value='2'>Poor</option>
							</select>
						</td>
					</tr>
					<tr>
						<td valign="top" width="15%">Did the agent confirm the customer's disbursed amount</td>
						<td valign="top" width="35%">
							<select name='script_guidelines' id='script_guidelines'>
								<option value=''></option>
								<option value='4'>Excellent</option>
								<option value='3.2'>Good</option>
								<option value='2.4'>Fair</option>
								<option value='2'>Poor</option>
							</select>
						</td>
					</tr>
					<tr>
						<td valign="top" width="15%">Did the agent notify the customer of the accrued penalties?</td>
						<td valign="top" width="35%">
							<select name='script_guidelines' id='script_guidelines'>
								<option value=''></option>
								<option value='4'>Excellent</option>
								<option value='3.2'>Good</option>
								<option value='2.4'>Fair</option>
								<option value='2'>Poor</option>
							</select>
						</td>
					</tr>
					<tr>
						<td valign="top" width="15%">Did the agent inform the customer of the consequenses of default clearly?</td>
						<td valign="top" width="35%">
							<select name='script_guidelines' id='script_guidelines'>
								<option value=''></option>
								<option value='4'>Excellent</option>
								<option value='3.2'>Good</option>
								<option value='2.4'>Fair</option>
								<option value='2'>Poor</option>
							</select>
						</td>
					</tr>
					<tr>
						<td valign="top" width="15%">Did the agent confirm the  pay bill number/payment modes?</td>
						<td valign="top" width="35%">
							<select name='script_guidelines' id='script_guidelines'>
								<option value=''></option>
								<option value='4'>Excellent</option>
								<option value='3.2'>Good</option>
								<option value='2.4'>Fair</option>
								<option value='2'>Poor</option>
							</select>
						</td>
					</tr>
					<tr>
						<td valign="top" width="15%">Did the agent offer the most appropriate repayment plan?</td>
						<td valign="top" width="35%">
							<select name='script_guidelines' id='script_guidelines'>
								<option value=''></option>
								<option value='4'>Excellent</option>
								<option value='3.2'>Good</option>
								<option value='2.4'>Fair</option>
								<option value='2'>Poor</option>
							</select>
						</td>
					</tr>
					<tr>
						<td valign="top" width="15%">Did the agent answer customer questions correctly?</td>
						<td valign="top" width="35%">
							<select name='script_guidelines' id='script_guidelines'>
								<option value=''></option>
								<option value='4'>Excellent</option>
								<option value='3.2'>Good</option>
								<option value='2.4'>Fair</option>
								<option value='2'>Poor</option>
							</select>
						</td>
					</tr>
					<tr>
						<td valign="top" width="15%">Demonstrate active listening and acknowledgement?</td>
						<td valign="top" width="35%">
							<select name='script_guidelines' id='script_guidelines'>
								<option value=''></option>
								<option value='4'>Excellent</option>
								<option value='3.2'>Good</option>
								<option value='2.4'>Fair</option>
								<option value='2'>Poor</option>
							</select>
						</td>
					</tr>
					<tr>
						<td valign="top" width="15%">Allow customer to present their concerns?</td>
						<td valign="top" width="35%">
							<select name='script_guidelines' id='script_guidelines'>
								<option value=''></option>
								<option value='4'>Excellent</option>
								<option value='3.2'>Good</option>
								<option value='2.4'>Fair</option>
								<option value='2'>Poor</option>
							</select>
						</td>
					</tr>
					<tr>
						<td valign="top" width="15%">Manage resistance/ tactfully counter customers' excuses?</td>
						<td valign="top" width="35%">
							<select name='script_guidelines' id='script_guidelines'>
								<option value=''></option>
								<option value='4'>Excellent</option>
								<option value='3.2'>Good</option>
								<option value='2.4'>Fair</option>
								<option value='2'>Poor</option>
							</select>
						</td>
					</tr>
					<tr>
						<td valign="top" width="15%">Maintain Call control?</td>
						<td valign="top" width="35%">
							<select name='script_guidelines' id='script_guidelines'>
								<option value=''></option>
								<option value='4'>Excellent</option>
								<option value='3.2'>Good</option>
								<option value='2.4'>Fair</option>
								<option value='2'>Poor</option>
							</select>
						</td>
					</tr>
					<tr>
						<td valign="top" width="15%">Did the agent avoid long silences during the call?</td>
						<td valign="top" width="35%">
							<select name='script_guidelines' id='script_guidelines'>
								<option value=''></option>
								<option value='4'>Excellent</option>
								<option value='3.2'>Good</option>
								<option value='2.4'>Fair</option>
								<option value='2'>Poor</option>
							</select>
						</td>
					</tr>
					<tr>
						<td valign="top" width="15%">Did the agent interrupt or talk over the customer?</td>
						<td valign="top" width="35%">
							<select name='script_guidelines' id='script_guidelines'>
								<option value=''></option>
								<option value='4'>Excellent</option>
								<option value='3.2'>Good</option>
								<option value='2.4'>Fair</option>
								<option value='2'>Poor</option>
							</select>
						</td>
					</tr>
					<tr>
						<td valign="top" width="15%">Did the agent display a professional manner throughout the call?</td>
						<td valign="top" width="35%">
							<select name='script_guidelines' id='script_guidelines'>
								<option value=''></option>
								<option value='4'>Excellent</option>
								<option value='3.2'>Good</option>
								<option value='2.4'>Fair</option>
								<option value='2'>Poor</option>
							</select>
						</td>
					</tr>
					<tr>
						<td valign="top" width="15%">Did the agent pro-actively add value throughout the call?</td>
						<td valign="top" width="35%">
							<select name='script_guidelines' id='script_guidelines'>
								<option value=''></option>
								<option value='4'>Excellent</option>
								<option value='3.2'>Good</option>
								<option value='2.4'>Fair</option>
								<option value='2'>Poor</option>
							</select>
						</td>
					</tr>
					<tr>
						<td valign="top" width="15%">Did the agent sound clear and confident throughout the call?</td>
						<td valign="top" width="35%">
							<select name='script_guidelines' id='script_guidelines'>
								<option value=''></option>
								<option value='4'>Excellent</option>
								<option value='3.2'>Good</option>
								<option value='2.4'>Fair</option>
								<option value='2'>Poor</option>
							</select>
						</td>
					</tr>
					<tr>
						<td valign="top" width="15%">Did the agent refrain from using jargon throughout the call?</td>
						<td valign="top" width="35%">
							<select name='script_guidelines' id='script_guidelines'>
								<option value=''></option>
								<option value='4'>Excellent</option>
								<option value='3.2'>Good</option>
								<option value='2.4'>Fair</option>
								<option value='2'>Poor</option>
							</select>
						</td>
					</tr>
					<tr>
						<td valign="top" width="15%">Did the agent sound friendly, polite but firm?</td>
						<td valign="top" width="35%">
							<select name='script_guidelines' id='script_guidelines'>
								<option value=''></option>
								<option value='4'>Excellent</option>
								<option value='3.2'>Good</option>
								<option value='2.4'>Fair</option>
								<option value='2'>Poor</option>
							</select>
						</td>
					</tr>
					<tr>
						<td valign="top" width="15%">Did the agent sound friendly, polite but firm?</td>
						<td valign="top" width="35%">
							<select name='script_guidelines' id='script_guidelines'>
								<option value=''></option>
								<option value='4'>Excellent</option>
								<option value='3.2'>Good</option>
								<option value='2.4'>Fair</option>
								<option value='2'>Poor</option>
							</select>
						</td>
					</tr>
					<tr>
						<td valign="top" width="15%">Did the agent use effective questioning skills?</td>
						<td valign="top" width="35%">
							<select name='script_guidelines' id='script_guidelines'>
								<option value=''></option>
								<option value='4'>Excellent</option>
								<option value='3.2'>Good</option>
								<option value='2.4'>Fair</option>
								<option value='2'>Poor</option>
							</select>
						</td>
					</tr>
					<tr>
						<td valign="top" width="15%">Did the agent demonstrate active listening?</td>
						<td valign="top" width="35%">
							<select name='script_guidelines' id='script_guidelines'>
								<option value=''></option>
								<option value='4'>Excellent</option>
								<option value='3.2'>Good</option>
								<option value='2.4'>Fair</option>
								<option value='2'>Poor</option>
							</select>
						</td>
					</tr>
					<tr>
						<td valign="top" width="15%">Did the agent adapt to the customer?</td>
						<td valign="top" width="35%">
							<select name='script_guidelines' id='script_guidelines'>
								<option value=''></option>
								<option value='4'>Excellent</option>
								<option value='3.2'>Good</option>
								<option value='2.4'>Fair</option>
								<option value='2'>Poor</option>
							</select>
						</td>
					</tr>
					<tr>
						<td valign="top" width="15%">Is the hold time more than 1 min?</td>
						<td valign="top" width="35%">
							<select name='script_guidelines' id='script_guidelines'>
								<option value=''></option>
								<option value='4'>Excellent</option>
								<option value='3.2'>Good</option>
								<option value='2.4'>Fair</option>
								<option value='2'>Poor</option>
							</select>
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
		$mobile_no = $_POST['mobile_no'];
		$old_mobile_no = $_POST['old_mobile_no'];
		$first_name = $_POST['first_name'];
		$last_name = $_POST['last_name'];
		$date_of_birth = $_POST['next_visit_date'];
		$date_of_birth = date('Y-m-d', strtotime(str_replace('-', '/', $date_of_birth)));
		$lead_outcome = $_POST['lead_outcome'];
		$customer_station = $_POST['customer_station'];
		$loan_officer = $_POST['loan_officer'];
		$collections_officer = $_POST['collections_officer'];
		
		$length_mobile_no = strlen($mobile_no);

		$page_status = $_POST['page_status'];
		$users_id = $_POST['users_id'];
		
		$sql = mysql_query("select distinct mobile_no from users where mobile_no = '$mobile_no'");
		while ($row = mysql_fetch_array($sql))
		{
			$exists_mobile_no = $row['mobile_no'];	
		}
		
		if($length_mobile_no == '12'){
			if($page_status == 'edit' && $old_mobile_no == $mobile_no){
				$sql3="update users set mobile_no = '$mobile_no', first_name = '$first_name', last_name = '$last_name', next_visit = '$date_of_birth',  lead_outcome = '$lead_outcome', transactiontime = '$transactiontime', UID = '$userid', loan_officer = '$loan_officer', collections_officer = '$collections_officer' WHERE id  = '$users_id'";
				//echo $sql3."<br />";
				$result = mysql_query($sql3);
				$query = "leads.php";
			}
			else if($exists_mobile_no != $mobile_no){
				$sql = mysql_query("select id from user_id");
				while ($row = mysql_fetch_array($sql))
				{
					$user_id_latest = $row['id'];	
				}
					
				$sql3="
				INSERT INTO users (id, mobile_no, first_name, last_name, next_visit, lead_outcome, stations, loan_officer, collections_officer, transactiontime, UID)
				VALUES('$user_id_latest', '$mobile_no', '$first_name', '$last_name', '$date_of_birth', '$lead_outcome', '$customer_station', '$loan_officer', '$collections_officer', '$transactiontime', '$userid')";
			
				$user_id_latest = $user_id_latest + 1;
				$sql15="update user_id set id='$user_id_latest'";
				$result = mysql_query($sql15);
				
				//echo $sql3."<br />";
				$result = mysql_query($sql3);
				$query = "leads.php";
			}
			else{
				$phone_number_length = MD5(phone_number_length);
				$query="lead_details.php?status=phone_number_length&phone_number_length=$phone_number_length";
			}
		}
		
	
		?>
		<script type="text/javascript">
			<!--
				document.location = "<?php echo $query ?>";
			//-->
		</script>
		<?php
	}
}
	include_once('includes/footer.php');
?>
