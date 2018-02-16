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
		$title = $_SESSION["title"];
	}
	if (!empty($_GET)) {
		$user_id = $_GET['user_id'];
		$complaint_id = $_GET['complaint_id'];
	}
	//if($adminstatus != 1 || $adminstatus != 2 || $adminstatus != 4){
	if($adminstatus == 4 && $userid == ''){
		include_once('includes/header.php');
		?>
		<script type="text/javascript">
			document.location = "login.php";
		</script>
		<?php
	}
	else{
		include_once('includes/db_conn.php');
		$log_transactiontime = date("Y-m-d G:i:s");
		$page_title = "Complaints Escalation View";
		include_once('includes/header.php');
		$sql2 = mysql_query("select first_name, last_name, mobile_no from users where id = '$user_id'");
		while ($row = mysql_fetch_array($sql2)) {
			$first_name = $row['first_name'];
			$last_name = $row['last_name'];
			$mobile_no = $row['mobile_no'];
			$customer_name = $first_name." ".$last_name;
		}
		$result_tender = mysql_query("select customer_id, complaint_nature, complaint, resolution, created_time, UID from complaints_customer where id = '$complaint_id'");
		while ($row = mysql_fetch_array($result_tender))
		{
			$customer_id = $row['customer_id'];
			$complaint_nature = $row['complaint_nature'];
			$complaint = $row['complaint'];
			$resolution = $row['resolution'];
			$created_time = $row['created_time'];
			$UID = $row['UID'];
			$sql2 = mysql_query("select first_name, last_name, mobile_no from users where id = '$customer_id'");
			while ($row = mysql_fetch_array($sql2)) {
				$first_name = $row['first_name'];
				$last_name = $row['last_name'];
				$mobile_no = $row['mobile_no'];
				$customer_name = $first_name." ".$last_name;
			}
			$sql2 = mysql_query("select complaint_nature from complaint_nature where id = '$complaint_nature'");
			while ($row = mysql_fetch_array($sql2)) {
				$complaint_nature_name = $row['complaint_nature'];
			}
			$sql2 = mysql_query("select first_name, last_name from user_profiles where id = '$UID'");
			while ($row = mysql_fetch_array($sql2))
			{
				$first_name_escalatee = $row['first_name'];
				$last_name_escalatee = $row['last_name'];
			}
		}
		//echo $title;
		?>		
		<div id="page">
			<div id="content">
				<div class="post">
				<h2><font color="#000A8B"><?php echo $page_title; ?></font></h2>
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
							<input title="Enter Mobile Number" value="<?php echo $complaint_nature_name ?>" id="mobile_no" name="mobile_no" type="text" maxlength="250" readonly class="main_input" size="75" />
						</td>
					</tr>
					<tr bgcolor = #F0F0F6>
						<td valign='top' >Details of the Complaint *</td>
						<td valign='top' colspan="3">
							<textarea title="Enter Complaint Detail" readonly name="complaint" id="complaint" cols="125" rows="5" class="textfield"><?php echo $complaint ?></textarea>
						</td>
					</tr>
					
				</table>
				
				<br />
				<h2>Customer Complaint Escalation Log Comments</h2>
				<?php
					$sql2 = mysql_query("select id, escalation_comment, UID, transactiontime from complaint_escalation where complaint_id = '$complaint_id' order by transactiontime asc");
					while($row = mysql_fetch_array($sql2)) {
						$id = $row['id'];
						$escalation_comment = $row['escalation_comment'];
						$UID = $row['UID'];
						$transactiontime = $row['transactiontime'];
						$sql = mysql_query("select first_name, last_name from user_profiles where id = '$UID'");
						while ($row = mysql_fetch_array($sql))
						{
							$first_name = $row['first_name'];
							$last_name = $row['last_name'];
							$staff_name = $first_name." ".$last_name;
						}
						echo "<font size='2'>- <b>$staff_name</b>, $transactiontime <br />$escalation_comment</font><br />"; 						
					}
				?>
				</ul>
				<?php if($resolution == '4'){ ?>
				<br />
				<table border="0" width="100%" cellspacing="2" cellpadding="2">	
					<tr bgcolor = #F0F0F6>
						<td valign='top' colspan="3">Enter Escalation Comment *</td>
					</tr>
					<tr>
						<td valign='top' colspan="3">
							<textarea title="Enter Escalation Comment" name="enter_escalation_comment" id="enter_escalation_comment" cols="125" rows="5" class="textfield"><?php echo $enter_escalation_comment ?></textarea>
						</td>
					</tr>
					<?php if($complaint_nature == '4'){ ?>
					<tr >
						<td valign='top' width="15%">Escalate to *</td>
						<td valign='top' width="35%" colspan="3">
							<select name='escalate_to' id='escalate_to'>
								<?php
									$sql2 = mysql_query("select id, first_name, last_name from user_profiles where id = '1' or id = '82' or id = '111' or id = '107' order by first_name asc");
									while($row = mysql_fetch_array($sql2)) {
										$id = $row['id'];
										$first_name = $row['first_name'];
										$last_name = $row['last_name'];
										$staff_name_esclate = $first_name." ".$last_name;
										echo "<option value='$id'>".$staff_name_esclate."</option>"; 										}
								?>
							</select>
						</td>
					</tr>
					<?php } ?>
					<tr bgcolor = #F0F0F6>
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
				<?php } ?>
                    <script  type="text/javascript">
                        var frmvalidator = new Validator("frmOrder");
                            frmvalidator.addValidation("enter_escalation_comment","req","Please specify the escalation comment");
                    </script>
				</form>
			</div>
			</div>
			<br class="clearfix" />
			</div>
		</div>
<?php
	if (!empty($_POST)) {
		$enter_escalation_comment = $_POST['enter_escalation_comment'];
		$enter_escalation_comment = mysql_real_escape_string($enter_escalation_comment	);
		$resolution = $_POST['resolution'];

		$page_status = $_POST['page_status'];
		$users_id = $_POST['users_id'];
		$complaint_id = $_POST['complaint_id'];

		$sql3 = "INSERT INTO complaint_escalation (customer_id, complaint_id, escalation_comment, UID, transactiontime)
		VALUES('$users_id', '$complaint_id', '$enter_escalation_comment', '$userid', '$log_transactiontime')";
		$result = mysql_query($sql3);
		
		if($resolution != ''){
			$sql3="update complaints_customer set resolution = '$resolution', resolved_time = '$log_transactiontime', UID = '$userid', resolution_comment = '$enter_escalation_comment' WHERE id  = '$complaint_id'";
			$result = mysql_query($sql3);
		}
		
		$query = "complaints_escalations.php?complaint_id=$complaint_id&user_id=$users_id&mode=edit";

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
