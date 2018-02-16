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
	if($adminstatus == 4 && $userid == ''){
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
			$complaint_id = $_GET['complaint_id'];
			$id_status = $_GET['status'];
		}
		$transactiontime = date("Y-m-d G:i:s");
		if ($mode=='edit'){
			$sql = mysql_query("select id, customer_id, complaint_nature, complaint, resolution, resolution_comment from complaints_customer where id = '$complaint_id'");
			while ($row = mysql_fetch_array($sql))
			{
				$complaint_nature = $row['complaint_nature'];					
				$complaint = $row['complaint'];
				$resolution = $row['resolution'];
				$resolution_comment = $row['resolution_comment'];

				$sql2 = mysql_query("select complaint_nature from complaint_nature where id = '$complaint_nature'");
				while ($row = mysql_fetch_array($sql2)) {
					$complaint_nature_name = $row['complaint_nature'];
				}
				$sql2 = mysql_query("select resolution from complaint_resolution where id = '$resolution'");
				while ($row = mysql_fetch_array($sql2)) {
					$resolution_name = $row['resolution'];
				}
			}
			$page_title = "Update Complaint Detail(s)";
		}
		else{
			$page_title = "Create new Complaint Detail(s)";
		}
		$sql2 = mysql_query("select first_name, last_name, mobile_no from users where id = '$user_id'");
		while ($row = mysql_fetch_array($sql2)) {
			$first_name = $row['first_name'];
			$last_name = $row['last_name'];
			$mobile_no = $row['mobile_no'];
			$customer_name = $first_name." ".$last_name;
		}
		
		include_once('includes/header.php');
		
		?>		
		<div id="page">
			<div id="content">
				<div class="post">
				<h2><font color="#000A8B"><?php echo $page_title; ?></font></h2>
				<h3>Customer Name: <?php echo $customer_name ?></h3>
				<?php if($id_status == 'phone_number_length'){ ?>
					<table width="60%">
						<tr bgcolor="red">
							<td><font color="white" size="2">&nbsp;&nbsp;Yikes! Something's gone wrong.</td>
						</tr>
					</table>
					<font color="red">
					* Either the phone number entered is not the required format<br />
				</font>
				<?php } ?>	
				<form id="frmOrder" name="frmOrder" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
				<table border="0" width="100%" cellspacing="2" cellpadding="2">	
				<input type="hidden" name="users_id" id="users_id" value="<?php echo $user_id ?>" />		
				<input type="hidden" name="page_status" id="page_status" value="<?php echo $mode ?>" />
				<input type="hidden" name="customer_station" id="customer_station" value="<?php echo $station ?>" />
				<input type="hidden" name="id_status" id="id_status" value="<?php echo $id_status ?>" />
				<input type="hidden" name="complaint_id" id="complaint_id" value="<?php echo $complaint_id ?>" />	
					<tr>
						<td valign="top" width="15%">Primary Mobile Number </td>
						<td valign="top" width="35%" colspan="3">
							<input title="Enter Mobile Number" value="<?php echo $mobile_no ?>" id="mobile_no" name="mobile_no" type="text" maxlength="100" readonly class="main_input" size="35" />
							<input value="<?php echo $mobile_no ?>" id="old_mobile_no" name="old_mobile_no" type="hidden" />
						</td>
					</tr>
				    <tr bgcolor = #F0F0F6>
						<td valign='top' width="15%">First Name </td>
						<td valign='top' width="35%">
							<input title="Enter First Name" value="<?php echo $first_name ?>" id="first_name" name="first_name" type="text" maxlength="100" readonly class="main_input" size="35" />
						</td>
						<td valign='top' width="15%">Last Name </td>
						<td valign='top' width="35%">
							<input title="Enter Last Name" value="<?php echo $last_name ?>" id="last_name" name="last_name" type="text" maxlength="100" readonly class="main_input" size="35" />
						</td>
					</tr>
					<tr >
						<td valign='top' width="15%">Nature of Complaint *</td>
						<td valign='top' width="35%" colspan="3">
							<select name='complaint_nature' id='complaint_nature'>
								<?php
								if($mode == 'edit'){
								?>
								<option value="<?php echo $complaint_nature ?>"><?php echo $complaint_nature_name; ?></option>	
								<?php
								}
								else{
								?>
								<option value=''> </option>
								<?php
								}
								?>
								<?php
									$sql2 = mysql_query("select id, complaint_nature from complaint_nature order by id asc");
									while($row = mysql_fetch_array($sql2)) {
										$complaint_id = $row['id'];
										$complaint_nature_name = $row['complaint_nature'];
										echo "<option value='$complaint_id'>".$complaint_nature_name."</option>"; 										}
								?>
							</select>
							<input value="<?php echo $complaint_nature_name ?>" id="old_complaint_nature" name="old_complaint_nature" type="hidden" />
						</td>
					</tr>
					<tr bgcolor = #F0F0F6>
						<td valign='top' >Details of the Complaint *</td>
						<td valign='top' colspan="3">
							<textarea title="Enter Complaint Detail" name="complaint" id="complaint" cols="125" rows="5" class="textfield"><?php echo $complaint ?></textarea>
						</td>
					</tr>
					<tr >
						<td valign='top' width="15%">Resolution *</td>
						<td valign='top' width="35%" colspan="3">
							<select name='resolution' id='resolution'>
								<?php
								if($mode == 'edit'){
								?>
								<option value="<?php echo $resolution ?>"><?php echo $resolution_name; ?></option>	
								<?php
								}
								else{
								?>
								<option value=''> </option>
								<?php
								}
								?>
								<?php
									$sql2 = mysql_query("select id, resolution from complaint_resolution order by id asc");
									while($row = mysql_fetch_array($sql2)) {
										$resolution_id = $row['id'];
										$resolution = $row['resolution'];
										echo "<option value='$resolution_id'>".$resolution."</option>"; 										}
								?>
							</select>
							<input value="<?php echo $resolution_name ?>" id="old_resolution" name="old_resolution" type="hidden" />
						</td>
					</tr>
					<tr bgcolor = #F0F0F6>
						<td valign='top' >Resolution Details *</td>
						<td valign='top' colspan="3">
							<textarea title="Enter Complaint Detail" name="resolution_comment" id="resolution_comment" cols="125" rows="5" class="textfield"><?php echo $resolution_comment ?></textarea>
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
						frmvalidator.addValidation("complaint_nature","req","Please specify the nature of the complaint");
                                                frmvalidator.addValidation("complaint","req","Please specify the exact detail of the complaint");
						frmvalidator.addValidation("resolution","req","Please specify the complaint resolution");
                                        </script>

				</form>
			</div>
			</div>
			<br class="clearfix" />
			</div>
		</div>
<?php
	if (!empty($_POST)) {
		$complaint_nature = $_POST['complaint_nature'];
		$old_complaint_nature = $_POST['old_complaint_nature'];
		$complaint = $_POST['complaint'];
		$complaint = mysql_real_escape_string($complaint);
		$resolution = $_POST['resolution'];
		$resolution_comment = $_POST['resolution_comment'];
		$resolution_comment = mysql_real_escape_string($resolution_comment);

		$page_status = $_POST['page_status'];
		$users_id = $_POST['users_id'];
		$complaint_id = $_POST['complaint_id'];

		//Select the user to escalate the issue to
		$result_tender = mysql_query("select escalate_to from complaint_nature where id = '$complaint_nature'");
		while ($row = mysql_fetch_array($result_tender))
		{
			$escalate_to = $row['escalate_to'];
		}

		if($page_status == 'edit'){
			if($resolution == '2'){
				$sql3="update complaints_customer set complaint_nature = '$complaint_nature', complaint = '$complaint', resolution = '$resolution', resolved_time = '$transactiontime', UID = '$userid', resolution_comment = '$resolution_comment' WHERE id  = '$complaint_id'";
			}
			else if($resolution == '4'){
				$sql3="update complaints_customer set complaint_nature = '$complaint_nature', complaint = '$complaint', resolution = '$resolution',  UID = '$userid', escalated = '$escalate_to', resolution_comment = '$resolution_comment' WHERE id  = '$complaint_id'";
			}
			else{
				$sql3="update complaints_customer set complaint_nature = '$complaint_nature', complaint = '$complaint', resolution = '$resolution', UID = '$userid', resolution_comment = '$resolution_comment' WHERE id  = '$complaint_id'";
			}
			//echo $sql3."<br />";
			$result = mysql_query($sql3);

			$result_tender = mysql_query("select email from complaints_customer WHERE id  = '$complaint_id'");
			while ($row = mysql_fetch_array($result_tender))
			{
				$email_sent = $row['email'];
			}
			
			if($resolution == '4' && $email_sent == '0'){
				$query = "escalate_email.php?user_id=$users_id&escalate_to=$escalate_to&id=$complaint_id&mode=edit";
			}
			else{	
				$query = "complaints.php?user_id=$users_id&mode=edit";
			}
		}
		else{
			if($resolution == '2'){
				$sql3 = "INSERT INTO complaints_customer (customer_id, complaint_nature, complaint, resolution, created_time, resolved_time, UID, resolution_comment)
				VALUES('$users_id', '$complaint_nature', '$complaint', '$resolution', '$transactiontime', '$transactiontime', '$userid', '$resolution_comment')";
			}
			else if($resolution == '4'){
				$sql3 = "INSERT INTO complaints_customer (customer_id, complaint_nature, complaint, resolution, created_time, escalated, UID, resolution_comment)
				VALUES('$users_id', '$complaint_nature', '$complaint', '$resolution', '$transactiontime', '$escalate_to', '$userid', '$resolution_comment')";
			}
			else{
				$sql3 = "INSERT INTO complaints_customer (customer_id, complaint_nature, complaint, resolution, created_time, UID, resolution_comment)
				VALUES('$users_id', '$complaint_nature', '$complaint', '$resolution', '$transactiontime', '$userid', '$resolution_comment')";
			}
				
			//echo $sql3."<br />";
			$result = mysql_query($sql3);
			$result_tender = mysql_query("select id, email from complaints_customer order by id desc limit 1");
			while ($row = mysql_fetch_array($result_tender))
			{
				$record_id = $row['id'];
				$email_sent = $row['email'];
			}
			
			//$query = "leads.php";
			if($resolution == '4'  && $email_sent == '0'){
				$query = "escalate_email.php?user_id=$users_id&escalate_to=$escalate_to&complaint_id=$record_id&mode=edit";
			}
			else{	
				$query = "complaints.php?user_id=$users_id&mode=edit";
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
