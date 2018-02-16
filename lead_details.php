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
	$transactiontime = date("Y-m-d G:i:s");
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
		//$transactiontime = date("Y-m-d G:i:s");
		if ($mode=='edit'){
			$sql = mysql_query("select passportfileupload, resumefileupload, mobile_no, title, first_name, last_name, national_id, preffered_language, nickname, next_visit, marital, dependants, alt_phone, dis_phone, lead_outcome, owns, home_occupy, stations, status, loan_officer, collections_officer, next_visit, market, outcome, other_sources from users where id = '$user_id'");
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
				$market = $row['market'];
				$outcome = $row['outcome'];
				$other_sources = $row['other_sources'];

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
				$sql2 = mysql_query("select market from markets where id = '$market'");
                while ($row = mysql_fetch_array($sql2)) {
                    $market = $row['market'];
                }
                $sql2 = mysql_query("select id, outcome from outcome where id = '$outcome'");
                while ($row = mysql_fetch_array($sql2)) {
                    $outcome_id = $row['id'];
                    $outcome = $row['outcome'];
                }
			}
			$page_title = "Update Lead Detail(s)";
		}
		else{
			$page_title = "Create new Lead Detail(s)";
		}
		
		include_once('includes/header.php');
		
		?>		
		<div id="page">
			<div id="content">
				<div class="post">
				<h2><font color="#000A8B"><?php echo $page_title; ?></font></h2>
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
				<input type="hidden" name="users_id" id="users_id" value="<?php echo $user_id	 ?>" />		
				<input type="hidden" name="page_status" id="page_status" value="<?php echo $mode ?>" />
				<input type="hidden" name="customer_station" id="customer_station" value="<?php echo $station ?>" />
				<input type="hidden" name="id_status" id="id_status" value="<?php echo $id_status ?>" />
					<tr>
						<td valign="top" width="15%">Primary Mobile Number *</td>
						<td valign="top" width="35%" colspan="3">
							<input title="Enter Mobile Number" value="<?php echo $mobile_no ?>" id="mobile_no" name="mobile_no" type="text" maxlength="100" class="main_input" size="35" />
							<input value="<?php echo $mobile_no ?>" id="old_mobile_no" name="old_mobile_no" type="hidden" />
						</td>
					</tr>
				    <tr bgcolor = #F0F0F6>
						<td valign='top' width="15%">First Name *</td>
						<td valign='top' width="35%">
							<input title="Enter First Name" value="<?php echo $first_name ?>" id="first_name" name="first_name" type="text" maxlength="100" class="main_input" size="35" />
						</td>
						<td valign='top' width="15%">Last Name *</td>
						<td valign='top' width="35%">
							<input title="Enter Last Name" value="<?php echo $last_name ?>" id="last_name" name="last_name" type="text" maxlength="100" class="main_input" size="35" />
						</td>
					</tr>
					<tr >
						<td valign='top' width="15%">Next Visit Date</td>
						<td valign='top' width="35%">
							<input title="Enter Date of Next Visit" value="<?php echo $next_visit_date ?>" id="next_visit_date" name="next_visit_date" type="text" maxlength="100" class="main_input" size="35" />
						</td>
						<td valign='top' width="15%">Customer Market</td>
                            <td valign='top' width="35%">
                                <select name='market' id='market'>
                                    <?php
                                    if ($mode == 'edit') {
                                        ?>
                                        <option value="<?php echo $market ?>"><?php echo $market ?></option>
                                        <?php
                                    } else {
                                        ?>
                                        <option value=''> </option>
                                        <?php
                                    }
                                    if ($station == '3') {
                                    	$sql2 = mysql_query("select id, market from markets order by market asc");
                                    } else {
                                        $sql2 = mysql_query("select id, market from markets where station = '$station' order by market asc");
                                    }
                                    while ($row = mysql_fetch_array($sql2)) {
                                            $id = $row['id'];
                                            $market = $row['market'];
                                            echo "<option value='$id'>" . $market . "</option>";
                                    }
                                    ?>
                                </select>
                                <input value="<?php echo $market ?>" id="old_market" name="old_market" type="hidden" />
                            </td>
					</tr>
					 <tr bgcolor = #F0F0F6>
						<td valign='top' width="15%">Loan Officer: *</td>
						<td valign='top' width="35%">
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
										$sql2 = mysql_query("select user_profiles.id, first_name, last_name, stations.stations from user_profiles inner join stations on stations.id = user_profiles.station where title = '1' and user_status = '1'");
									}
									else if($title == '3' || $title == '8'){ 
										$sql2 = mysql_query("select user_profiles.id, first_name, last_name, stations.stations from user_profiles inner join stations on stations.id = user_profiles.station where title = '1' and user_status = '1' and station = '$station'");
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
						<td valign='top' width="15%">Collections Officer: *</td>
						<td valign='top' width="35%">
							<select name='collections_officer' id='collections_officer'>
								<?php
									if($mode == 'edit'){
								?>
									<option value="<?php echo $collections_officer_id ?>"><?php echo $collections_officer ?></option>
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
										$sql2 = mysql_query("select user_profiles.id, first_name, last_name, stations.stations from user_profiles inner join stations on stations.id = user_profiles.station where title = '2' and user_status = '1'");
									}
									else if($title == '3' || $title == '8'){ 
										$sql2 = mysql_query("select user_profiles.id, first_name, last_name, stations.stations from user_profiles inner join stations on stations.id = user_profiles.station where title = '2' and user_status = '1' and station = '$station'");
									}
									else{										
										$sql2 = mysql_query("select id, first_name, last_name from user_profiles where station = '$station' and title = '2' and user_status = '1'");
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
								<input value="<?php echo $collections_officer_id ?>" id="old_collections_officer" name="old_collections_officer" type="hidden" />
						</td>
					</tr>
					<tr>
						<td valign='top' >Outcome*</td>
						<td valign='top' colspan="3">
							<select name='outcome' id='outcome'>
								<?php
									if($mode == 'edit'){
								?>
									<option value="<?php echo $outcome_id ?>"><?php echo $outcome ?></option>
									<option value=''> </option>	
								<?php
									}
									else{
									?>
									<option value=''> </option>
									<?php
									}
									//echo "<option value=''>" "</option>"; 																				
									$sql2 = mysql_query("select id, outcome from outcome order by outcome asc");
									while($row = mysql_fetch_array($sql2)) {
										$outcome_id = $row['id'];
										$outcome = $row['outcome'];
										echo "<option value='$outcome_id'>".$outcome."</option>"; 
									}
									?>
								</select>
						</td>
					</tr>
					<tr>
						<td valign='top' >Outcome Explanation*</td>
						<td valign='top' colspan="3">
							<textarea title="Enter Lead Outcome" name="lead_outcome" id="lead_outcome" cols="95" rows="5" class="textfield"><?php echo $lead_outcome ?></textarea>
						</td>
					</tr>

			        <tr bgcolor = #F0F0F6>
                            <td valign='top' width="50%">How did you hear about us?</td>
                            <td valign='top' width="35%" colspan = "3">
                                <select name='marketing_drive' id='marketing_drive'>   
                                    <option value=''> </option>
                                    echo "<option value='Branch Ambassadors'>Branch Ambassadors</option>";
                                    echo "<option value='Fliers'>Fliers</option>";
				                    echo "<option value='Existing Customer'>Existing Customer</option>";
                                    echo "<option value='Newspaper'>Newspaper</option>";
                                    echo "<option value='Marketing Drive'>Marketing Drive</option>";
                                    echo "<option value='Loan Officer'> Loan Officer</option>";
				                    echo "<option value='Access Afya'>Access Afya</option>";
                                    echo "<option value='Others'> Others</option>";

                                </select>
                            </td>
                        </tr>

                        <tr>
                            <td valign='top' width="50%">If others/ Existing Customer, please specify</td>
                            <td valign='top' width="35%" colspan="3">
                                <textarea title="other_sources" name="other_sources" id="other_sources" cols="95" rows="5" class="textfield"><?php echo $other_sources ?></textarea>
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
				<br />
				<h2>Leads Log Comments</h2>
				<?php
					$sql2 = mysql_query("select id, user_id, lead_comment, UID, transactiontime from lead_comments where user_id = '$user_id' order by transactiontime asc");
					while($row = mysql_fetch_array($sql2)) {
						$id = $row['id'];
						$lead_comment = $row['lead_comment'];
						$UID = $row['UID'];
						$lead_time = $row['transactiontime'];
						$sql = mysql_query("select first_name, last_name from user_profiles where id = '$UID'");
						while ($row = mysql_fetch_array($sql))
						{
							$first_name = $row['first_name'];
							$last_name = $row['last_name'];
							$staff_name = $first_name." ".$last_name;
						}
						echo "<font size='2'>- <b>$staff_name</b>, $lead_time <br />$lead_comment</font><br />"; 						
					}
				?>
				 <script  type="text/javascript">
                    var frmvalidator = new Validator("frmOrder");
                    frmvalidator.addValidation("mobile_no","req","Please specify the leads mobile number");
                    frmvalidator.addValidation("outcome","req","Please specify the outcome");
                    frmvalidator.addValidation("market","req","Please specify the Customer's market");
                    frmvalidator.addValidation("marketing_drive","req","Please specify how the lead came to know about us");
                </script>

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
		$market = $_POST['market'];
		$outcome = $_POST['outcome'];

		$marketing_drive = $_POST['marketing_drive'];
        $other_sources = $_POST['other_sources'];

		
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
				$sql3="update users set mobile_no = '$mobile_no', first_name = '$first_name', last_name = '$last_name', next_visit = '$date_of_birth',  lead_outcome = '$lead_outcome', transactiontime = '$transactiontime', UID = '$userid', loan_officer = '$loan_officer', collections_officer = '$collections_officer', market = '$market', outcome = '$outcome' WHERE id  = '$users_id'";
				//echo $sql3."<br />";
	
				$sql6 = "insert into change_log(UID, table_name, customer_id, variable, old_value, new_value, transactiontime)values('$userid', 'leads', '$users_id', 'leads', '$old_mobile_no', '$mobile_no', '$transactiontime')";
				
				$sql7 = "insert into lead_comments(user_id, lead_comment, UID, transactiontime) values ('$users_id', '$lead_outcome', '$userid', '$transactiontime')";

				//echo $sql6."<br />";

				$result = mysql_query($sql3);
				$result = mysql_query($sql6);
				$result = mysql_query($sql7);
				$query = "leads.php";
				
			}
			else if($exists_mobile_no != $mobile_no){
				$sql = mysql_query("select id from user_id");
				while ($row = mysql_fetch_array($sql))
				{
					$user_id_latest = $row['id'];	
				}

				$sql3 = "INSERT INTO users (id, mobile_no, first_name, last_name, next_visit, lead_outcome, stations, loan_officer, collections_officer, transactiontime, UID, marketing_drive, other_sources, market, outcome)
				VALUES('$user_id_latest', '$mobile_no', '$first_name', '$last_name', '$date_of_birth', '$lead_outcome', '$customer_station', '$loan_officer', '$collections_officer', '$transactiontime', '$userid','$marketing_drive','$other_sources', '$market', '$outcome')";

				$sql6 = "insert into change_log(UID, table_name, customer_id, variable, old_value, new_value, transactiontime)values('$userid', 'leads', '$user_id_latest', 'leads', '$old_mobile_no', '$mobile_no', '$transactiontime')";
				
				$sql7 = "insert into lead_comments(user_id, lead_comment, UID, transactiontime) values ('$user_id_latest', '$lead_outcome', '$userid', '$transactiontime')";
			
				$user_id_latest = $user_id_latest + 1;
				$sql15="update user_id set id='$user_id_latest'";
				$result = mysql_query($sql15);

				$result = mysql_query($sql3);
                $result = mysql_query($sql6);
                $result = mysql_query($sql7);
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
