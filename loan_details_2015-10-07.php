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
		include_once('includes/db_conn.php');
		
		include_once('includes/db_conn_dialer.php');
		if (!empty($_GET)){	
			$loan_id = $_GET['loan_id'];
			$mode = $_GET['mode'];
			$user_id = $_GET['user_id'];
			$id_status = $_GET['status'];
		}
		include_once('cron_limit_loans.php');
		$filter_month = date("m");
		$filter_year = date("Y");
		$filter_day = date("d");
		$current_date = $filter_year.'-'.$filter_month.'-'.$filter_day;
		
		//echo $station."<br />";
		
		$sql = mysql_query("select first_name, last_name, mobile_no, dis_phone, affordability, loan_officer, collections_officer, stations, limit_loan_amount, override_consq from users where id = '$user_id'", $dbh1);
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
			$limit_loan_amount = $row['limit_loan_amount'];
			$override_consq = $row['override_consq'];
		}
		
		$dis_phone_prefix = substr($dis_phone ,0,5);
		if($dis_phone_prefix == 25470 || $dis_phone_prefix == 25471 || $dis_phone_prefix == 25472 || $dis_phone_prefix == 25479){
			$mobile_carrier = 'Safaricom';
			$img = "<img src='images/safaricom.jpg' width='150px'";
		}
		else if($dis_phone_prefix == 25473 || $dis_phone_prefix == 25478){
			$mobile_carrier = 'Airtel';
			$img = "<img src='images/airtel.jpg' width='150px'";
		}
		else if($dis_phone_prefix == 25477){
			$mobile_carrier = 'Orange Mobile';
		}
		else if($dis_phone_prefix == 25475){
			$mobile_carrier = 'Essar Yu';
		}
		else if($dis_phone_prefix == 25476){
			$mobile_carrier = 'Equitel';
		}
		
		//$sql = mysql_query("select loan_amount, loan_due_date from loan_application where loan_code = '$loan_rep_code' and customer_id = '$user_id'", $dbh1);
		$sql = mysql_query("select loan_amount, loan_due_date, loan_code from loan_application where customer_id = '$user_id' order by loan_due_date desc limit 1", $dbh1);
		while ($row = mysql_fetch_array($sql))
		{
			$loan_due_date = $row['loan_due_date'];
			$current_loan_amount = $row['loan_amount'];
			//$previous_loan_amount = $row['loan_amount'];
			$loan_code = $row['loan_code'];
		}
		
		$sql = mysql_query("select loan_rep_date, loan_rep_code from loan_repayments where customer_id = '$user_id' and loan_rep_code = '$loan_code' order by loan_rep_date desc limit 1", $dbh1);
		while ($row = mysql_fetch_array($sql))
		{
			$loan_rep_date = $row['loan_rep_date'];
			$loan_rep_code = $row['loan_rep_code'];
		}
		
		$date1 = strtotime($loan_due_date);
		$date2 = strtotime($loan_rep_date);
		$dateDiff = $date2 - $date1;
		$regression_days = floor($dateDiff/(60*60*24));
		
		$date3 = strtotime($current_date);
		$dateDiff = $date2 - $date1;
		$days_diff = floor($dateDiff/(60*60*24));
		
		//echo $regression_days."<br />";
		//echo $days_diff."<br />";
		
		//if($override_consq == '1'){
			$regression_days = 0;
			$days_diff = 0;
		//}
		//else{
		//	$regression_days = $regression_days;
		//	$days_diff = $days_diff;
		//}
		
		$transactiontime = date("Y-m-d G:i:s");
		if ($mode=='edit'){
			$sql = mysql_query("select loan_id, loan_date, loan_term, loan_due_date, customer_id, loan_mobile, loan_amount, loan_total_interest, loan_status, loan_code, loan_mpesa_code, waiver, loan_disbursed, loan_failure_status, customer_state, loan_officer, collections_officer, comment, UID, late_status, collections_agent, admin_fee, appointment_fee, early_settlement, early_settlement_surplus, fix, joining_fee, loan_late_interest, initiation_fee, loan_interest, loan_failure_status from loan_application where loan_id = '$loan_id'", $dbh1);
			while ($row = mysql_fetch_array($sql))
			{					
				$loan_id = $row['loan_id'];
				$loan_date = $row['loan_date'];
				$loan_due_date = $row['loan_due_date'];
				$dis_phone = $row['loan_mobile'];
				$loan_amount = $row['loan_amount'];
				$previous_loan_amount = $row['loan_amount'];
				$loan_total_interest = $row['loan_total_interest'];
				$comment = $row['comment'];
				$loan_status = $row['loan_status'];
				$late_status = $row['late_status'];
				$loan_code = $row['loan_code'];
				$customer_state = $row['customer_state'];
				//$loan_officer = $row['loan_officer'];
				//$collections_officer = $row['collections_officer'];
				$collections_agent = $row['collections_agent'];
				$loan_mpesa_code = $row['loan_mpesa_code'];
				
				$initiation_fee = $row['initiation_fee'];
				$loan_late_interest = $row['loan_late_interest'];
				$loan_interest = $row['loan_interest'];
				$waiver = $row['waiver'];
				
				$admin_fee = $row['admin_fee'];
				$appointment_fee = $row['appointment_fee'];
				$early_settlement = $row['early_settlement'];
				$early_settlement_surplus = $row['early_settlement_surplus'];
				$fix = $row['fix'];
				$joining_fee = $row['joining_fee'];
				
				$loan_failure_status = $row['loan_failure_status'];
				
				$allocation_fees = $admin_fee + $appointment_fee + $early_settlement + $early_settlement_surplus + $fix + $joining_fee;
						
				$sql2 = mysql_query("select id, first_name, last_name from user_profiles where id = '$collections_agent'", $dbh1);
				while ($row = mysql_fetch_array($sql2))
				{
					$collections_agent_id = $row['id'];
					$first_name = $row['first_name'];
					$last_name = $row['last_name'];
					$collections_agent = $first_name." ".$last_name;
				}
				$sql2 = mysql_query("select id, status from customer_status where id = '$loan_status'", $dbh1);
				while ($row = mysql_fetch_array($sql2))
				{
					$status_id = $row['id'];
					$status_name = $row['status'];
				}
			}
			$page_title = "Update Loan Application Detail(s)";
		}
		else{
			if($regression_days <= 0){
				$loan_amount = $affordability;
				if($loan_amount > 50000){
					$loan_amount = 50000;
				}
			}
			else if($regression_days <= 7){
				$loan_amount = $current_loan_amount;
			}
			else if($regression_days <= 15){
				if($days_diff < 15){
					$loan_amount = 0;
				}
				else{
					$loan_amount = $current_loan_amount;
				}
			}
			else if($regression_days <= 50){
				if($days_diff < 30){
					$loan_amount = 0;
				}
				else{
					$loan_amount = $current_loan_amount - 5000;
				}
			}
			else if($regression_days <= 90){
				if($days_diff < 60){
					$loan_amount = 0;
				}
				else{
					$loan_amount = $current_loan_amount - 10000;
				}
			}
			else if($regression_days > 90){
				$loan_amount = 0;
				$sql3="update users set status='13' WHERE id  = '$user_id'";
				//echo $sql3."<br />";
				$result = mysql_query($sql3);
			}
			
			$sql2 = mysql_query("select count(loan_id)loan_count from loan_application where customer_id = '$user_id'", $dbh1);
			while ($row = mysql_fetch_array($sql2))
			{
				$loan_count = $row['loan_count'];
			}
			//echo $loan_amount."<br />";
			$page_title = "Create new Loan Application Detail(s)";
		}
		
		$sql2 = mysql_query("select count(loan_id)loan_count from loan_application where customer_id = '$user_id' and loan_status != '12' and loan_status != '11' and loan_status != '14'", $dbh1);
		while ($row = mysql_fetch_array($sql2))
		{
			$loan_count = $row['loan_count'];
		}
		
		//echo $loan_mpesa_code;
		//$affordability = 15000;
		//echo $affordability;
		
		include_once('includes/header.php');
		?>		
		<div id="page">
			<div id="content">
				<div class="post">
				<h2><font color="#000A8B"><?php echo $page_title; ?></font></h2>
				<h3>Customer Name: <?php echo $customer_name ?>, Disbursement #: <?php echo $dis_phone ?></h3>
				<br />
				<?php if($id_status == 'missing_mpesa_code' ){ ?>
					<table width="60%">
						<tr bgcolor="red">
							<td><font color="white" size="2">&nbsp;&nbsp;Yikes! Something's gone wrong.</td>
						</tr>
					</table>
					<font color="red">* The loan cannot be disbursed without the Mobile Money Reference Code.</font></a>	
				<?php } ?>
				<?php if($id_status == 'exists_loan_mpesa_code'){ ?>
					<table width="60%">
						<tr bgcolor="red">
							<td><font color="white" size="2">&nbsp;&nbsp;Yikes! Something's gone wrong.</td>
						</tr>
					</table>
					<font color="red">* The Mobile Money Reference Code used already exists in the system.</font></a>	
				<?php } ?>	
				<?php if($station == '3'){ ?>
					<p align="right"><img src="images/delete.png"> - <a href="loan_reversal_details.php?loan_id=<?php echo $loan_id ?>">Reverse this loan</a></p>
				<?php } ?>
				<form id="frmOrder" name="frmOrder" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
				<table border="0" width="100%" cellspacing="2" cellpadding="2">	
				<input type="hidden" name="loan_id" id="loan_id" value="<?php echo $loan_id ?>" />
				<input type="hidden" name="user_id" id="user_id" value="<?php echo $user_id ?>" />			
				<input type="hidden" name="mobile_no" id="mobile_no" value="<?php echo $mobile_no ?>" />
				<input type="hidden" name="page_status" id="page_status" value="<?php echo $mode ?>" />
				<input type="hidden" name="mobile_carrier" id="mobile_carrier" value="<?php echo $mobile_carrier ?>" />
				<input type="hidden" name="loan_officer" id="loan_officer" value="<?php echo $loan_officer_id ?>" />
				<input type="hidden" name="collections_officer" id="collections_officer" value="<?php echo $collections_officer_id ?>" />
				<input type="hidden" name="customer_station" id="customer_station" value="<?php echo $customer_station ?>" />
				<input type="hidden" name="blank_mpesa_code" id="blank_mpesa_code" value="<?php echo $blank_mpesa_code ?>" />
				<input type="hidden" name="loan_failure_status" id="loan_failure_status" value="<?php echo $loan_failure_status ?>" />
				
				
					<tr bgcolor = #F0F0F6>
						<td valign='top' width="15%">Loan Date </td>
							<td valign='top' width="35%" colspan="3">
								<input title="Enter Loan Date" value="<?php echo $loan_date ?>" id="loan_date" name="loan_date" type="text" maxlength="100" class="main_input" readonly size="35" />
							</td>
							
					</tr>
				<?php if($mode == 'edit'){ ?>
					<tr>
						<td valign='top' width="15%">Due Date </td>
						<td valign='top' width="35%" colspan="3">
							<input title="Enter Due Date" value="<?php echo $loan_due_date ?>" id="loan_due_date" name="loan_due_date" type="text" maxlength="100" class="main_input" size="35" />
						</td>
					</tr>
					<tr bgcolor = #F0F0F6>
						<td valign='top' >Mobile Money Ref Code: *</td>
						<td valign='top'>
							<?php if($station == '4'){ ?>
								<input title="Enter Gender" value="<?php echo $loan_mpesa_code ?>" id="loan_mpesa_code" name="loan_mpesa_code" type="text" maxlength="100" class="main_input" readonly size="35" />
							<?php }else if($station == '5' || $station == '3'){ ?>
								<?php if($loan_mpesa_code == ""){ ?>
									<input title="Enter Gender" value="<?php echo $loan_mpesa_code ?>" id="loan_mpesa_code" name="loan_mpesa_code" type="text" maxlength="100" class="main_input" size="35" />
								<?php } else { ?>
									<input title="Enter Gender" value="<?php echo $loan_mpesa_code ?>" id="loan_mpesa_code" name="loan_mpesa_code" type="text" maxlength="100" class="main_input" readonly size="35" />
									<input value="1" id="mpesa_code_no_edit" name="mpesa_code_no_edit" type="hidden" />
								<?php } ?>
							<?php }else { ?>
								<?php if($loan_mpesa_code == ""){ ?>
									<input title="Enter Gender" value="<?php echo $loan_mpesa_code ?>" id="loan_mpesa_code" name="loan_mpesa_code" type="text" maxlength="100" class="main_input" readonly size="35" />
								<?php } else { ?>
									<input title="Enter Gender" value="<?php echo $loan_mpesa_code ?>" id="loan_mpesa_code" name="loan_mpesa_code" type="text" maxlength="100" class="main_input" readonly size="35" />
									<input value="1" id="mpesa_code_no_edit" name="mpesa_code_no_edit" type="hidden" />
								<?php } ?>
							<?php } ?>
							
							<input value="<?php echo $loan_mpesa_code ?>" id="old_loan_mpesa_code" name="old_loan_mpesa_code" type="hidden" />
						</td>
						<td valign='top' >Loan Code </td>
							<td valign='top' >
								<input title="Enter Loan Date" value="<?php echo $loan_code ?>" id="loan_code" name="loan_code" type="text" maxlength="100" class="main_input" readonly size="35" />
							</td>
					</tr>
					<?php } ?>
					<tr>
						<td valign="top" width="15%">Disbursement Mobile *</td>
						<td valign="top" width="35%">
							<input title="Enter Disbursement Phone Number" value="<?php echo $dis_phone ?>" id="dis_phone" name="dis_phone" type="text" maxlength="100" class="main_input" readonly size="35" />
						</td>
						<td valign='top' >Loan Amount *</td>
						<?php
						// Define the minimum loan amount based on station and satellite status
						if ($customer_station == 16) { // This is to identify a satellite: 15 is id for South B. Satellite's minimum loan is 2500 /=                                                
							$minimum = 2500;
						} else {
							$minimum = 5000;
						}
						?>					
						
						<?php if ($regression_days >= '0') { ?>
                                <td valign='top' colspan="3">
                                    <select name='loan_amount' id='loan_amount'>
                                        <option value="<?php echo $previous_loan_amount ?>"><?php echo number_format($previous_loan_amount, 2) ?></option>
                                        <option value=''> </option>	
                                        <?php
                                        for ($x = $minimum; $x <= $loan_amount; $x = $x + 2500) {
                                            echo "<option value='$x'>" . number_format($x, 2) . "</option>";
                                        }
                                        ?>
                                    </select>
                                    <!--<input title="Enter Gender" value="<?php echo $loan_amount ?>" id="loan_amount" name="loan_amount" type="text" maxlength="100" class="main_input" size="35" />-->
                                    <input value="<?php echo $loan_amount ?>" id="old_loan_amount" name="old_loan_amount" type="hidden" />
                                </td>
                            <?php } else {
                                ?>
                                <td valign='top' colspan="3">
                                    <select name='loan_amount' id='loan_amount'>
                                        <?php
                                        if ($mode == 'edit') {
                                            ?>
                                            <option value="<?php echo $loan_amount ?>"><?php echo number_format($loan_amount, 2) ?></option>
                                            <option value=''> </option>	
                                            <?php
                                        } else {
                                            ?>
                                            <option value=''> </option>
                                            <?php
                                        }

                                        if ($affordability >= 5000) {
                                            if ($loan_count == 0 && $affordability <= 7500) {
                                                for ($x = $minimum; $x <= 5000; $x = $x + 2500) {
                                                    echo "<option value='$x'>" . number_format($x, 2) . "</option>";
                                                }
                                            } else if ($loan_count == 0 && $affordability <= 10000) {
                                                for ($x = $minimum; $x <= 7500; $x = $x + 2500) {
                                                    echo "<option value='$x'>" . number_format($x, 2) . "</option>";
                                                }
                                            } else if ($loan_count == 0 && $affordability <= 12500) {
                                                for ($x = $minimum; $x <= 10000; $x = $x + 2500) {
                                                    echo "<option value='$x'>" . number_format($x, 2) . "</option>";
                                                }
                                            } else if ($loan_count == 0 && $affordability <= 15000) {
                                                for ($x = $minimum; $x <= 12500; $x = $x + 2500) {
                                                    echo "<option value='$x'>" . number_format($x, 2) . "</option>";
                                                }
					    } else if ($loan_count == 0 && $affordability <= 17500) {
                                                for ($x = $minimum; $x <= 15000; $x = $x + 2500) {
                                                    echo "<option value='$x'>" . number_format($x, 2) . "</option>";
                                                }
					    } else if ($loan_count == 0 && $affordability <= 20000) {
                                                for ($x = $minimum; $x <= 17500; $x = $x + 2500) {
                                                    echo "<option value='$x'>" . number_format($x, 2) . "</option>";
                                                }
                                            } else {
                                                if ($affordability > 50000) {
                                                    $affordability = 50000;
                                                }
                                                //for ($x = 5000; $x <= $affordability; $x = $x+5000) {
                                                //	echo "<option value='$x'>".number_format($x, 2)."</option>"; 
                                                //} 
                                                if ($limit_loan_amount > '0') {
                                                    for ($x = $minimum; $x <= $limit_loan_amount; $x = $x + 2500) {
                                                        echo "<option value='$x'>" . number_format($x, 2) . "</option>";
                                                    }
                                                } else {
                                                    if($customer_station == 6){
                                                    	for ($x = $minimum; $x <= $current_loan_amount; $x = $x + 2500) {
								echo "<option value='$x'>" . number_format($x, 2) . "</option>";
                                                    	}
						    }
						    else{
							for ($x = $minimum; $x <= $affordability; $x = $x + 2500) {
								echo "<option value='$x'>" . number_format($x, 2) . "</option>";
                                                    	}
						    }
                                                }
                                            }
                                        } else {
                                            for ($x = 0; $x <= 0; $x++) {
                                                echo "<option value='$x'>" . $x . "</option>";
                                            }
                                        }
                                        ?>
                                    </select>
							<input value="<?php echo $loan_amount ?>" id="old_loan_amount" name="old_loan_amount" type="hidden" />
						</td>
						<?php } ?>
					</tr>
					<?php if($regression_days > 90){ ?>
					<tr>
						<td valign='top' colspan="4">
							Note: The loan amount is zero since the client took over 90 days to pay back their loan.
						</td>
					</tr>
					<?php } ?>
					<?php if($mode == 'edit'){ ?>
					<tr >
						<td valign='top' >Loan Status *</td>
						<td valign='top' width="35%">
							<select name='loan_status' id='loan_status'>
								<?php
									if($mode == 'edit'){
								?>
									<option value="<?php echo $status_id ?>"><?php echo $status_name ?></option>
									<option value=''> </option>	
								<?php
									}
									else{
									?>
									<option value=''> </option>
									<?php
									}
									//echo "<option value=''>" "</option>";
									if($title == '3'){
										$sql2 = mysql_query("select id, status from customer_status where id = '10' or id = '2' order by status asc", $dbh1);
									}
									else{
										$sql2 = mysql_query("select id, status from customer_status order by status asc", $dbh1);
									}
									while($row = mysql_fetch_array($sql2)) {
										$id = $row['id'];
										$status_name = $row['status'];
										echo "<option value='$id'>".$status_name."</option>"; 
									}
									?>
								</select>
								<input value="<?php echo $loan_status ?>" id="old_loan_status" name="old_loan_status" type="hidden" />
						</td>
						<?php if($station == '3'){ ?>
						<td valign='top' width="15%">Collections Agent: *</td>
						<td valign='top' width="35%">
							<select name='collections_agent' id='collections_agent'>
								<?php
									if($mode == 'edit'){
								?>
									<option value="<?php echo $collections_agent_id ?>"><?php echo $collections_agent ?></option>
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
										$sql2 = mysql_query("select user_profiles.id, first_name, last_name, stations.stations from user_profiles inner join stations on stations.id = user_profiles.station where (title = '7' or title = '10') and user_status = '1'", $dbh1);
									}
									//else{										
									//	$sql2 = mysql_query("select id, first_name, last_name from user_profiles where station = '$station' and title = '7'", $dbh1);
									//}
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
								<input value="<?php echo $collections_agent_id ?>" id="old_collections_agent" name="old_collections_agent" type="hidden" />
						</td>
						<?php } ?>
					</tr>
					<tr>
						<td valign='top' width="15%">Customer State (BFC/ BLC)</td>
						<td valign='top' width="35%" colspan="3">
							<?php if($customer_state == 'BFC'){ ?>
								<select name='customer_state' id='customer_state'>
								<?php
									if($mode == 'edit'){
								?>
									<option value="<?php echo $customer_state ?>"><?php echo $customer_state ?></option>
								<?php
								}
								?>
								</select>
							<?php
							}
							else{
							?>
								<select name='customer_state' id='customer_state'>
								<?php
									if($mode == 'edit'){
								?>
									<option value="<?php echo $customer_state ?>"><?php echo $customer_state ?></option>
									<option value=''> </option>
								<?php
								}
								else{
								?>
									<option value=''> </option>
								<?php
								}
									echo "<option value='BLC'>BLC</option>"; 
									echo "<option value='BFC'>BFC</option>"; 
								?>
							</select>
						<?php
						}
						?>
						<input value="<?php echo $customer_state ?>" id="old_customer_state" name="old_customer_state" type="hidden" />
						</td>
					</tr>
					<tr bgcolor = #F0F0F6>			
						<td valign='top' >Initiation Fee:</td>
						<td valign='top'>
							<?php if($station == '3'){ ?>
								<input title="Enter Admin Fee" value="<?php echo $initiation_fee ?>" id="initiation_fee" name="initiation_fee" type="text" maxlength="100" class="main_input" size="35" />
							<?php }else{ ?>
								<input title="Enter Admin Fee" value="<?php echo $initiation_fee ?>" id="initiation_fee" name="initiation_fee" type="text" maxlength="100" readonly class="main_input" size="35" />
							<?php } ?>
							<input value="<?php echo $initiation_fee ?>" id="old_initiation_fee" name="old_initiation_fee" type="hidden" />
						</td>
						<td valign='top' >Late Interest Fee:</td>
							<td valign='top' >
							<?php if($station == '3'){ ?>
								<input title="Enter Appointment Fee" value="<?php echo $loan_late_interest ?>" id="loan_late_interest" name="loan_late_interest" type="text" maxlength="100" class="main_input" size="35" />
							<?php }else { ?>
								<input title="Enter Appointment Fee" value="<?php echo $loan_late_interest ?>" id="loan_late_interest" name="loan_late_interest" type="text" readonly maxlength="100" class="main_input" size="35" />
							<?php } ?>
							<input value="<?php echo $loan_late_interest ?>" id="old_loan_late_interest" name="old_loan_late_interest" type="hidden" />
						</td>
					</tr>
					<tr>			
						<td valign='top' >Interest Fee:</td>
						<td valign='top' >
							<?php if($station == '3'){ ?>
								<input title="Enter Appointment Fee" value="<?php echo $loan_interest ?>" id="loan_interest" name="loan_interest" type="text"  maxlength="100" class="main_input" size="35" />
							<?php }else{ ?>
								<input title="Enter Appointment Fee" value="<?php echo $loan_interest ?>" id="loan_interest" name="loan_interest" type="text" readonly maxlength="100" class="main_input" size="35" />
							<?php } ?>
							<input value="<?php echo $loan_interest ?>" id="old_loan_interest" name="old_loan_interest" type="hidden" />
						</td>
						<td valign='top' >Waiver:</td>
						<td valign='top' >
							<?php if($station == '3'){ ?>
								<input title="Enter Waiver Fee" value="<?php echo $waiver ?>" id="waiver" name="waiver" type="text"  maxlength="100" readonly class="main_input" size="35" />
							<?php }else{ ?>
								<input title="Enter Appointment Fee" value="<?php echo $waiver ?>" id="waiver" name="waiver" type="text" readonly maxlength="100" class="main_input" size="35" />
							<?php } ?>
							
						</td>
					</tr>
					<?php } ?>
					<?php //if($allocation_fees != 0){ ?>
					<?php if($mode == 'edit'){ ?>
					<tr>
						<td colspan="4">
							<h3>Allocations</h3>
						</td>
					</tr>
					<tr bgcolor = #F0F0F6>			
						<td valign='top' >Admin Fee:</td>
						<td valign='top'>
							<?php if($station == '3'){ ?>
								<input title="Enter Admin Fee" value="<?php echo $admin_fee ?>" id="admin_fee" name="admin_fee" type="text" maxlength="100" class="main_input" size="35" />
							<?php }else{ ?>
								<input title="Enter Admin Fee" value="<?php echo $admin_fee ?>" id="admin_fee" name="admin_fee" type="text" maxlength="100" class="main_input" readonly size="35" />
							<?php } ?>
							<input value="<?php echo $admin_fee ?>" id="old_admin_fee" name="old_admin_fee" type="hidden" />
						</td>
						<td valign='top' >Appointment Fee:</td>
							<td valign='top' >
								<?php if($station == '3'){ ?>
									<input title="Enter Appointment Fee" value="<?php echo $appointment_fee ?>" id="appointment_fee" name="appointment_fee" type="text" maxlength="100" class="main_input" size="35" />
								<?php }else{ ?>
									<input title="Enter Appointment Fee" value="<?php echo $appointment_fee ?>" id="appointment_fee" name="appointment_fee" type="text" maxlength="100" class="main_input" readonly size="35" />
								<?php } ?>
								<input value="<?php echo $appointment_fee ?>" id="old_appointment_fee" name="old_appointment_fee" type="hidden" />
						</td>
					</tr>
					<tr>			
						<td valign='top' >Early Settlement:</td>
						<td valign='top'>
							<?php if($station == '3'){ ?>
								<input title="Enter Early Settlement" value="<?php echo $early_settlement ?>" id="early_settlement" name="early_settlement" type="text" maxlength="100" class="main_input"  size="35" />
							<?php }else{ ?>
								<input title="Enter Early Settlement" value="<?php echo $early_settlement ?>" id="early_settlement" name="early_settlement" type="text" maxlength="100" class="main_input"  readonly size="35" />
							<?php } ?>
							<input value="<?php echo $early_settlement ?>" id="old_early_settlement" name="old_early_settlement" type="hidden" />
						</td>
						<td valign='top' >Early Settlement Surplus:</td>
							<td valign='top' >
							<?php if($station == '3'){ ?>
								<input title="Enter Early Settlement Surplus" value="<?php echo $early_settlement_surplus ?>" id="early_settlement_surplus" name="early_settlement_surplus" type="text" maxlength="100" class="main_input"  size="35" />
							<?php }else{ ?>
								<input title="Enter Early Settlement Surplus" value="<?php echo $early_settlement_surplus ?>" id="early_settlement_surplus" name="early_settlement_surplus" type="text" readonly maxlength="100" class="main_input"  size="35" />
							<?php } ?>	
							<input value="<?php echo $early_settlement_surplus ?>" id="old_early_settlement_surplus" name="old_early_settlement_surplus" type="hidden" />
						</td>
					</tr>
					<tr bgcolor = #F0F0F6>			
						<td valign='top' >Fix:</td>
						<td valign='top'>
							<?php if($station == '3'){ ?>
								<input title="Enter Fix" value="<?php echo $fix ?>" id="fix" name="fix" type="text" maxlength="100" class="main_input" size="35" />
							<?php }else{ ?>
								<input title="Enter Fix" value="<?php echo $fix ?>" id="fix" name="fix" type="text" maxlength="100" class="main_input" readonly size="35" />
							<?php } ?>	
							<input value="<?php echo $fix ?>" id="old_fix" name="old_fix" type="hidden" />
						</td>
						<td valign='top' >Joining Fee:</td>
						<td valign='top' >
							<?php if($station == '3'){ ?>
								<input title="Enter Joining Fee" value="<?php echo $joining_fee ?>" id="joining_fee" name="joining_fee" type="text" maxlength="100" class="main_input" size="35" />
							<?php }else{ ?>
								<input title="Enter Joining Fee" value="<?php echo $joining_fee ?>" id="joining_fee" name="joining_fee" type="text" maxlength="100" class="main_input" readonly size="35" />
							<?php } ?>	
							<input value="<?php echo $joining_fee ?>" id="old_joining_fee" name="old_joining_fee" type="hidden" />
						</td>
					</tr>
					<tr>
						<td valign='top' >Payable Amount </td>
						<td valign='top' colspan="3">
							<?php if($station == '3'){ ?>
								<input title="Enter Gender" value="<?php echo $loan_total_interest ?>" id="loan_total_interest" name="loan_total_interest" type="text" maxlength="100" class="main_input" readonly size="35" />
								<input value="<?php echo $loan_total_interest ?>" id="old_loan_total_interest" name="old_loan_total_interest" type="hidden" />
							<?php }else{ ?>
								<input title="Enter Gender" value="<?php echo $loan_total_interest ?>" id="loan_total_interest" name="loan_total_interest" type="text" maxlength="100" class="main_input" readonly size="35" />
								<input value="<?php echo $loan_total_interest ?>" id="old_loan_total_interest" name="old_loan_total_interest" type="hidden" />
							<?php } ?>
						</td>
					</tr>
					<?php } ?>
					<tr bgcolor = #F0F0F6>
						<td valign='top' >Comment *</td>
						<td valign='top' colspan="3">
							<textarea title="Enter Comment" name="comment" id="comment" cols="45" rows="3" class="textfield"><?php echo $comment ?></textarea>
						</td>
					</tr>
					<tr>
						<td colspan="4">
							Mobile Carrier: <strong><?php echo $mobile_carrier; ?></strong><br />
							<?php echo $img ?>
						</td>
					</tr>
				</table>
				<table border="0" width="100%">
					<tr>
						<td valign="top">
							<button name="btnNewCard" id="button">Submit</button>
						</td>
						<td align="right">
							<button name="reset" id="button2" type="reset">Reset</button>
						</td>		
					</tr>
				</table>
				<script  type="text/javascript">
					var frmvalidator = new Validator("frmOrder");
					frmvalidator.addValidation("comment","req","Please enter the Comment");
					//frmvalidator.addValidation("loan_mpesa_code","req","Please enter Mobile Money Ref Code");
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
		$loan_date = $_POST['loan_date'];
		$loan_date = date('Y-m-d', strtotime(str_replace('-', '/', $loan_date)));
		$old_loan_date = $_POST['old_loan_date'];
		$old_loan_date = date('Y-m-d', strtotime(str_replace('-', '/', $old_loan_date)));
		$loan_due_date = $_POST['loan_due_date'];
		$loan_due_date = date('Y-m-d', strtotime(str_replace('-', '/', $loan_due_date)));
		$loan_due_date_days = date("l". $loan_due_date);
		$dis_phone = $_POST['dis_phone'];
		$loan_mpesa_code = $_POST['loan_mpesa_code'];
		$loan_mpesa_code = trim($loan_mpesa_code);
		$old_loan_mpesa_code = $_POST['old_loan_mpesa_code'];
		$old_loan_mpesa_code = trim($old_loan_mpesa_code);
		$mpesa_code_no_edit = $_POST['mpesa_code_no_edit'];
		$loan_amount = $_POST['loan_amount'];
		$loan_amount_from_form = $_POST['loan_amount'];
		$old_loan_amount = $_POST['old_loan_amount'];
		$loan_total_interest = $_POST['loan_total_interest'];
		$old_loan_total_interest = $_POST['old_loan_total_interest'];
		$comment = $_POST['comment'];
		$loan_status_name = $_POST['loan_status'];
		$old_loan_status_name = $_POST['old_loan_status'];
		$loan_officer = $_POST['loan_officer'];
		$collections_officer = $_POST['collections_officer'];
		$collections_agent = $_POST['collections_agent'];
		$old_collections_agent = $_POST['old_collections_agent'];
		$mobile_no = $_POST['mobile_no'];
		$loan_code = $_POST['loan_code'];
		$customer_state = $_POST['customer_state'];
		$old_customer_state = $_POST['old_customer_state'];
		
		$initiation_fee_from_form = $_POST['initiation_fee'];
		$old_initiation_fee = $_POST['old_initiation_fee'];
		$loan_late_interest_from_form = $_POST['loan_late_interest'];
		$old_loan_late_interest = $_POST['old_loan_late_interest'];
		$loan_total_interest_from_form = $_POST['loan_total_interest'];
		$old_loan_total_interest = $_POST['old_loan_total_interest'];
		$admin_fee_from_form = $_POST['admin_fee'];
		$old_admin_fee = $_POST['old_admin_fee'];
		$appointment_fee_from_form = $_POST['appointment_fee'];
		$old_appointment_fee = $_POST['old_appointment_fee'];
		$early_settlement_from_form = $_POST['early_settlement'];
		$old_early_settlement = $_POST['old_early_settlement'];
		$early_settlement_surplus_from_form = $_POST['early_settlement_surplus'];
		$old_early_settlement_surplus = $_POST['old_early_settlement_surplus'];
		$fix_from_form = $_POST['fix'];
		$old_fix = $_POST['old_fix'];
		$joining_fee_from_form = $_POST['joining_fee'];
		$old_joining_fee = $_POST['old_joining_fee'];
		$waiver_from_form = $_POST['waiver'];
		$loan_interest_from_form = $_POST['loan_interest'];
		$old_loan_interest = $_POST['old_loan_interest'];
		
		$page_status = $_POST['page_status'];
		$user_id = $_POST['user_id'];
		$loan_id = $_POST['loan_id'];
		$customer_station = $_POST['customer_station'];
		$mobile_carrier = $_POST['mobile_carrier'];
		$blank_mpesa_code = $_POST['blank_mpesa_code'];
		$loan_failure_status_form = $_POST['loan_failure_status'];
		
		
		$sql = mysql_query("select count(loan_id)loan_count from loan_application where customer_id = '$user_id' and loan_status != '12' and loan_status != '11' and loan_status != '14'", $dbh1);
		while ($row = mysql_fetch_array($sql))
		{
			$loan_count = $row['loan_count'];
			if($loan_count == ""){
				$loan_count = 0;
			}
		}
		
		//$date1 = strtotime($loan_date);
		//$date2 = strtotime($loan_due_date);
		//$dateDiff = $date2 - $date1;
		//$days = floor($dateDiff/(60*60*24));
		
		$days = 30;
		
		$loan_interest = $loan_amount * ($days/100);
		$loan_total_interest = $loan_interest + $loan_amount;
		
		//echo $loan_total_interest;
		
		if($loan_count == 0){
			$initiation_fee = 0;
			$sql = mysql_query("select fee from feez where category = 'initiation_fee'", $dbh1);
			while ($row = mysql_fetch_array($sql))
			{
				$initiation_fee = $row['fee'];
			}
			$loan_total_interest = $loan_total_interest + $initiation_fee;
		}
	
		$sql = mysql_query("select holiday_name from holiday_names where holiday_date = '$current_date'");
		while ($row = mysql_fetch_array($sql))
		{
			$holiday_name = $row['holiday_name'];
			if($holiday_name !=""){
				$comments = 'holiday_exists';
			}
		}
		
		$loan_term = $days;
		//$loan_due_date = '2015-01-18';
		$loan_due_date = date('Y-m-d',strtotime($loan_date) + (24 * 3600 * $loan_term));
		$loan_due_date_day = date("l", strtotime($loan_due_date));
		//echo $loan_due_date."<br />";
		//echo $loan_due_date_day."<br />";
		
		if($loan_due_date_day == 'Saturday'){
			$days = 2;
			$loan_term = $days;
			$loan_due_date = date('Y-m-d',strtotime($loan_due_date) + (24 * 3600 * $loan_term));
			//echo $loan_due_date."<br />";
		}
		else if($loan_due_date_day == 'Sunday'){
			$days = 1;
			$loan_term = $days;
			$loan_due_date = date('Y-m-d',strtotime($loan_due_date) + (24 * 3600 * $loan_term));
			//echo $loan_due_date."<br />";
		}
		else if($comments == 'holiday_exists'){
			$days = 1;
			$loan_term = $days;
			$loan_due_date = date('Y-m-d',strtotime($loan_due_date) + (24 * 3600 * $loan_term));
			//echo $loan_due_date."<br />";
		}
		else{
			$loan_term = $days;
			$loan_due_date = date('Y-m-d',strtotime($loan_date) + (24 * 3600 * $loan_term));
			//echo $loan_due_date."<br />";
		}
		
		//if($loan_mpesa_code == ""){
		//	$loan_status = 10;
		//	$loan_failure_status = "Pending";
		//}
		//else{
		//	$loan_status = 2;
		//	$loan_failure_status = "Success";
		//}
		//if($loan_status_name != 2){
			$loan_status = $loan_status_name;
		//}
		
		//echo $loan_amount_from_form."<br />";
		//echo $initiation_fee_from_form."<br />";
		//echo $loan_late_interest_from_form."<br />";
		//echo $loan_interest_from_form."<br />";
		//echo $admin_fee_from_form."<br />";
		//echo $appointment_fee_from_form."<br />";
		//echo $early_settlement_from_form."<br />";
		//echo $early_settlement_surplus_from_form."<br />";
		//echo $fix_from_form."<br />";
		//echo $joining_fee_from_form."<br />";


		if($page_status == 'edit'){
			if($loan_status == '5'){
				$loan_total_interest = $loan_amount_from_form + $initiation_fee_from_form + $loan_late_interest_from_form + $loan_interest_from_form + $admin_fee_from_form + $appointment_fee_from_form + $early_settlement_from_form + $early_settlement_surplus_from_form + $fix_from_form + $joining_fee_from_form + $waiver_from_form;
				
				//echo $loan_total_interest."<br />";
				
				$sql3="
				update loan_application set loan_mpesa_code='$loan_mpesa_code', customer_state = '$customer_state', loan_officer='$loan_officer', collections_officer='$collections_officer', comment ='$comment', loan_status='$loan_status', loan_failure_status='$loan_failure_status', admin_fee = '$admin_fee_from_form', loan_status = '$loan_status', UID='$userid', loan_interest = '$loan_interest_from_form', early_settlement='$early_settlement_from_form',  early_settlement_surplus = '$early_settlement_surplus_from_form', appointment_fee = '$appointment_fee_from_form', loan_late_interest = '$loan_late_interest_from_form', joining_fee = '$joining_fee_from_form', fix = '$fix_from_form', loan_total_interest = '$loan_total_interest', collections_agent='$collections_agent', current_collector='$collections_agent' WHERE loan_id  = '$loan_id'";
				
				$sql10="
				update users set customer_state ='$customer_state', collections_agent='$collections_agent' WHERE id  = '$user_id'";
				//echo $sql10."<br />";
				$result = mysql_query($sql10, $dbh1);
				
				if($customer_state == 'BFC'){
					$sql11 = "update loan_application set loan_status = '5' WHERE loan_id = '$loan_id'";
				}
	
			}
			else{
				//if($loan_status == '2' && $loan_mpesa_code == "" && $loan_status != '11' && $loan_status != '12' && $loan_status != '14'){
					$missing_mpesa_code = MD5(missing_mpesa_code);
					$query="loan_details.php?status=missing_mpesa_code&missing_mpesa_code=$missing_mpesa_code&user_id=$user_id&loan_id=$loan_id&mode=edit";
					?>
						<script type="text/javascript">
						<!--
				//			document.location = "<?php echo $query ?>";
						//-->
						</script>
					<?php
				//}
				//else{
					if($mpesa_code_no_edit == ""){
						$sql = mysql_query("select distinct loan_mpesa_code from loan_application where loan_mpesa_code = '$loan_mpesa_code'", $dbh1);
						while ($row = mysql_fetch_array($sql))
						{
							$exists_loan_mpesa_code = $row['loan_mpesa_code'];
						}
					}
					//if($exists_loan_mpesa_code == $loan_mpesa_code && $loan_status != '11' && $loan_status != '12' && $loan_status != '14'){
						$exists_loan_mpesa_code = MD5(exists_loan_mpesa_code);	$query="loan_details.php?status=exists_loan_mpesa_code&exists_loan_mpesa_code=$exists_loan_mpesa_code&user_id=$user_id&loan_id=$loan_id&mode=edit";
						?>
							<script type="text/javascript">
							<!--
					//			document.location = "<?php echo $query ?>";
							//-->
							</script>
						<?php
					//}
					//else{
						$loan_total_interest = $loan_amount_from_form + $initiation_fee_from_form + $loan_late_interest_from_form + $loan_interest_from_form + $admin_fee_from_form + $appointment_fee_from_form + $early_settlement_from_form + $early_settlement_surplus_from_form + $fix_from_form + $joining_fee_from_form + $waiver_from_form;
					
						$sql3="update loan_application set initiation_fee = '$initiation_fee_from_form', loan_amount = '$loan_amount_from_form', loan_interest = '$loan_interest_from_form', loan_late_interest = '$loan_late_interest_from_form', admin_fee = '$admin_fee_from_form', appointment_fee = '$appointment_fee_from_form', early_settlement = '$early_settlement_from_form', early_settlement_surplus = '$early_settlement_surplus_from_form', fix = '$fix_from_form', joining_fee = '$joining_fee_from_form', appointment_fee = '$appointment_fee_from_form', fix = '$fix_from_form', loan_total_interest = '$loan_total_interest', loan_status = '$loan_status', loan_mpesa_code = '$loan_mpesa_code', loan_officer = '$loan_officer', collections_officer = '$collections_officer', collections_agent='$collections_agent', current_collector='$collections_agent', customer_state = '$customer_state', comment = '$comment', UID = '$UID' WHERE loan_id  = '$loan_id'";
				
						$sql10="
						update users set customer_state ='$customer_state', collections_agent='$collections_agent' WHERE id  = '$user_id'";
						//echo $sql10."<br />";
						$result = mysql_query($sql10, $dbh1);
						
						if($customer_state == 'BFC'){
							$sql11 = "update loan_application set loan_status ='5' WHERE loan_code  = '$loan_code'";
						}
				
						$sql = mysql_query("select distinct customer_id from loan_application where loan_code = '$loan_code'", $dbh1);
						while ($row = mysql_fetch_array($sql))
						{
							$customer_id = $row['customer_id'];
						}
					
						$sql = mysql_query("select distinct loan_code from overpayments_schedule where loan_code = '$loan_code' and customer_id = '$customer_id'", $dbh1);
						while ($row = mysql_fetch_array($sql))
						{
							$exists_customer_id = $row['loan_code'];
						}
				
						if($exists_customer_id != $loan_code){
							$sql14="insert into overpayments_schedule(customer_id, loan_code, loan_amount, transactiontime)values('$customer_id', '$loan_code', '$loan_total_interest', '$transactiontime')";
							//echo $sql14."<br />";
							$result = mysql_query($sql14, $dbh1);
						}
					
						if($old_loan_mpesa_code != $loan_mpesa_code){
							$sql50="insert into change_log(UID, table_name, customer_id, variable, loan_code, old_value, new_value, transactiontime)values('$userid', 'loan_application', '$user_id', 'loan_mpesa_code', '$loan_code', '$old_loan_mpesa_code', '$loan_mpesa_code', '$transactiontime')";
							//echo $sql4."<br />";
							$result = mysql_query($sql50, $dbh1);
						}
						if($old_loan_status_name != $loan_status_name){
							$sql2 = mysql_query("select status from customer_status where id = '$loan_status_name'");
							while ($row = mysql_fetch_array($sql2))
							{
								$status_name = $row['status'];
							}
							$sql2 = mysql_query("select status from customer_status where id = '$old_loan_status_name'");
							while ($row = mysql_fetch_array($sql2))
							{

								$old_status_name = $row['status'];
							}
							$sql50="insert into change_log(UID, table_name, customer_id, variable, loan_code, old_value, new_value, transactiontime)values('$userid', 'loan_application', '$user_id', 'loan_status', '$loan_code', '$old_status_name', '$status_name', '$transactiontime')";
							//echo $sql4."<br />";
							$result = mysql_query($sql50, $dbh1);
						}
					//}
				//}
			}
			
				$sql = mysql_query("select distinct loan_code, customer_station, msg from loan_application where loan_code = '$loan_code' and loan_status = '10' order by loan_code desc limit 1", $dbh1);
				while ($row = mysql_fetch_array($sql))
				{
					$loan_code_latest = $row['loan_code'];
					$msg = $row['msg'];
					$customer_station = $row['customer_station'];
				}
				
				if($loan_failure_status_form == '1' && $loan_status == '2' && $station != '16'){
					if($mobile_carrier == 'Safaricom'){
						$sql8="INSERT INTO mobile_money_requests (loan_code, msisdn, amount, carrier, new, customer_station, transactiontime) 
						VALUES('$loan_code_latest', '$dis_phone', '$loan_amount_from_form', '1', '1', '$customer_station', '$transactiontime')";	
					}
					else if($mobile_carrier == 'Airtel'){
						$sql8="INSERT INTO mobile_money_requests (loan_code, msisdn, amount, carrier, new, customer_station, transactiontime) 
						VALUES('$loan_code_latest', '$dis_phone', '$loan_amount_from_form', '2', '1', '$customer_station', '$transactiontime')";	
					}
				}
				
				echo $customer_state;
				$result = mysql_query($sql3, $dbh1);
				$result = mysql_query($sql5, $dbh1);
				$result = mysql_query($sql6, $dbh1);
				$result = mysql_query($sql7, $dbh1);
				$result = mysql_query($sql8, $dbh1);
				$result = mysql_query($sql11, $dbh1);
		}
		else{
			//$loan_code = 111111;
			
			$sql = mysql_query("select id from loan_code", $dbh1);

			while ($row = mysql_fetch_array($sql))
			{
				$loan_code_latest = $row['id'];	
			}
			
			//echo $loan_code_latest;
			//$sql = mysql_query("select distinct loan_code from loan_application order by loan_code desc limit 1", $dbh1);
			//while ($row = mysql_fetch_array($sql))
			//{
			//	$loan_code_latest = $row['loan_code'];
			//	
			//}
			
			//$loan_total_interest = $loan_amount + $initiation_fee + $loan_late_interest + $loan_interest + $admin_fee + $appointment_fee + $early_settlement + $early_settlement_surplus + $fix + $joining_fee;
			
			$sql3="
			INSERT INTO loan_application (loan_date, loan_term, loan_due_date, customer_id, loan_mobile, initiation_fee, loan_amount, loan_interest, loan_total_interest, loan_status, loan_code, loan_mpesa_code, loan_disbursed, loan_failure_status, loan_officer, collections_officer, comment, UID, customer_station, loan_creation, customer_state)
			VALUES('$loan_date', '$loan_term', '$loan_due_date', '$user_id', '$dis_phone', '$initiation_fee', '$loan_amount', '$loan_interest', '$loan_total_interest', '10', '$loan_code_latest', '$loan_mpesa_code', '$loan_disbursed', '1', '$loan_officer', '$collections_officer', '$comment', '$userid', '$customer_station', '1', '$customer_state')";
			
			$sql13="
			INSERT INTO call_center (loan_date, loan_term, loan_due_date, customer_id, loan_mobile, initiation_fee, loan_amount, loan_interest, loan_total_interest, loan_status, loan_code, loan_mpesa_code, loan_disbursed, loan_failure_status, loan_officer, collections_officer, comment, UID, customer_station, loan_creation, customer_state)
			VALUES('$loan_date', '$loan_term', '$loan_due_date', '$user_id', '$dis_phone', '$initiation_fee', '$loan_amount', '$loan_interest', '$loan_total_interest', '10', '$loan_code_latest', '$loan_mpesa_code', '$loan_disbursed', '1', '$loan_officer', '$collections_officer', '$comment', '$userid', '$customer_station', '1', '$customer_state')";
			
			
			$sql = mysql_query("select distinct loan_id from loan_application order by loan_id desc limit 1", $dbh1);
			while ($row = mysql_fetch_array($sql))
			{
				$loan_id_latest = $row['loan_id'];	
			}
			$sql4="insert into change_log(UID, table_name, customer_id, variable, old_value, new_value, transactiontime, comment)values('$userid', 'loan_application', '$user_id', 'loan', '0', '$loan_amount', '$transactiontime', '$comment')";
			
			$sql = mysql_query("select loan_balance from overpayments_schedule where customer_id = '$user_id' order by id desc limit 1", $dbh1);
			while ($row = mysql_fetch_array($sql))
			{
				$loan_balance = $row['loan_balance'];	
			}
			//echo $loan_balance."<br />";
			if($loan_balance > 0){
				$loan_balance = -$loan_balance;
				$loan_total_interest = $loan_total_interest + $loan_balance;
				$sql20="update loan_application set early_settlement_surplus='$loan_balance', loan_total_interest='$loan_total_interest' where loan_code = '$loan_code_latest'";
			       $sql21 = "update overpayments_schedule set loan_balance= null where customer_id = '$user_id' and loan_code =  '$loan_code'";
				}
			
			$loan_code_latest = $loan_code_latest + 1;
			
			$sql15="update loan_code set id='$loan_code_latest'";
			
		}
		
		//echo $loan_term."<br />";
		//echo $sql3."<br />";
		//echo $sql4."<br />";
		//echo $sql5."<br />";
		//echo $sql6."<br />";
		//echo $sql20."<br />";
		
		$result = mysql_query($sql3, $dbh1);
		$result = mysql_query($sql13, $dbh1);
		$result = mysql_query($sql4, $dbh1);
		$result = mysql_query($sql20, $dbh1);
		$result = mysql_query($sql15, $dbh1);
		
		$query = "customer_loans.php?user_id=$user_id";
		
		if($collections_agent != 0 || $collections_agent != ''){
			$dis_phone = substr($dis_phone, 3);
			$transactiontime = date("Y-m-d G:i:s");
			$sql = mysql_query("select campaign_id, list_id from user_profiles where id = '$collections_agent'", $dbh1);
			while ($row = mysql_fetch_array($sql))
			{
				$campaign_id = $row['campaign_id'];	
				$list_id = $row['list_id'];	
			}
			$sql = mysql_query("select first_name, last_name from users where id = '$user_id'", $dbh1);
			while ($row = mysql_fetch_array($sql))
			{
				$first_name = $row['first_name'];	
				$last_name = $row['last_name'];	
			}
			
			$sql15="insert into vicidial_list Set entry_date = '$transactiontime', modify_date =  '$transactiontime', status = 'NEW', list_id = '$list_id', gmt_offset_now = '-5.00', called_since_last_reset = 'N', phone_code = '254', phone_number = '$dis_phone', first_name = '$first_name', last_name = '$last_name', called_count = '0', rank = '0', comments = '$first_name $last_name put under Management'";
           
			
			$sql16="INSERT INTO dial_table (customer_id, dialed_number, status, transactiontime)
			VALUES('$user_id', '$dis_phone', '1', '$transactiontime')";
           
            //echo $sql15."<br />";   
            //echo $sql16."<br />";    
			$result = mysql_query($sql15, $dbh2);  
			$result = mysql_query($sql16, $dbh1);
			
		}
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
