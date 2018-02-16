<?php
	$userid = "";
	$adminstatus = 4;
	$property_manager_id = "";
	session_start();
	if (!empty($_SESSION)){
		$userid = $_SESSION["userid"] ;
		$adminstatus = $_SESSION["adminstatus"] ;
		$property_manager_id = $_SESSION["property_manager_id"] ;
	}

	//if($adminstatus != 1 || $adminstatus != 2 || $adminstatus != 4){
	if($adminstatus == 3){
		include_once('includes/header.php');
		?>
		<script type="text/javascript">
			document.location = "insufficient_permission.php";
		</script>
		<?php
	}
	else{	
		include_once('includes/db_conn.php');
		$transactiontime = date("Y-m-d G:i:s");
		$page_title = "Admin Panel";
		include_once('includes/header.php');
		//$station = 4;
		
		?>		
		<div id="page">
			<div id="content">
				<div class="post">
					<h2><?php echo $page_title ?></h2>
					Which feature would you like to view?
					<hr>
					<?php if($station == '3'){ ?>
					<table width="100%" border="0" cellspacing="2" cellpadding="2" >
						<tr>
							<td width="50%" valign="top">
								<table width="100%" border="0" cellspacing="2" cellpadding="2" >
									<tr>
										<td valign='top'><a href='station.php'><img src="images/stations.jpg" width="65px"></a></td>
										<td valign='top'><strong>Create Stations</strong><br />
										Create/ Activate & Edit station locations in the system.</td>
									</tr>
<tr>
                                        <td valign='top'><a href='sectors.php'><img src="images/stations.jpg" width="65px"></a></td>
                                        <td valign='top'><strong>Create Sectors</strong><br />
                                            Create/ Activate & Edit Sectors in the system</td>
                                    </tr>
									<tr>
										<td valign='top'><a href='upload_statements.php'><img src="images/mpesa.jpeg" width="65px"></a></td>
										<td valign='top'><strong>Upload Mpesa Statement</strong><br />
										This feature is used to upload the mpesa statements into the system, C2B mpesa statements.</td>
									</tr>
									<tr>
										<td valign='top'><a href='upload_airtel_statements.php'><img src="images/airtel.jpg" width="65px"></a></td>
										<td valign='top'><strong>Upload Airtel Statement</strong><br />
										This feature is used to upload the mpesa statements into the system, C2B mpesa statements.</td>
									</tr>
									<tr>
										<td valign='top'><a href='user_details.php'><img src="images/users.jpg" width="65px"></a></td>
										<td valign='top'><strong>User Profiles</strong><br />
										This feature lets you view all the Users and right levels in the system.</td>
									</tr>
									<tr>
										<td valign='top'><a href='arrears_assign_FA.php'><img src="images/arrears.jpg" width="65px"></a></td>
										<td valign='top'><strong>Bulk Assign Accounts to Field Agents</strong><br />
										This feature enables you to bulk assign overdue accounts to field agents.</td>
									</tr>
									<tr>
										<td valign='top'><a href='user_logs.php'><img src="images/sys_logs.png" width="65px"></a></td>
										<td valign='top'><strong>User Logs</strong><br />
										This features enable you to view logs of the system, logins done by who and time.</td>
									</tr>
									<tr>

										<td valign='top'><a href='backupdb.php'><img src="images/db.jpeg" width="75px"></a></td>
										<td valign='top'><strong>Database Backups</strong><br />
										Backup the database, ensuring you never loose any data from the system.</td>
									</tr>
									<tr>
										<td valign='top'><a href='change_log.php'><img src="images/sys_logs.png" width="65px"></a></td>
										<td valign='top'><strong>Users Change Log Report</strong><br />
										Users Change Log Summary Report.</td>

									</tr>
								</table>
							</td>
							<td width="50%" valign="top">
								<table width="100%" border="0" cellspacing="2" cellpadding="2" >
									<?php if($userid == '1' || $userid == '12' || $userid == '16' || $userid == '111'){ ?>
										<tr>
											<td valign='top'><a href='bulk_messaging.php'><img src="images/message.png" width="60px"></a></td>
											<td valign='top'><strong>Create/ Send Bulk Messages</strong><br />
											Create and send bulk messages to customers in the branches.</td>
										</tr>
									<?php } ?>
									<tr>
										<td valign='top'><a href='title.php'><img src="images/titles.png" width="65px"></a></td>
										<td valign='top'><strong>Create User Titles</strong><br />
										Create/ Activate & Edit user titles in the system.</td>
									</tr>
									<tr>
										<td valign='top'><a href='extract_loco_pairs.php'><img src="images/loco_pair.png" width="65px"></a></td>
										<td valign='top'><strong>Extract LO/ CO Pairs</strong><br />
										This feature is used to extract all the LO/ CO pairs for ammendement should there be any.</td>
									</tr>
									<tr>
										<td valign='top'><a href='update_suspense_account.php'><img src="images/airtel.jpg" width="65px"></a></td>
										<td valign='top'><strong>Reconcile Afb Airtel Suspense Account</strong><br />
										This feature is used to upload the mpesa statements into the system, C2B mpesa statements.</td>
									</tr>
									<tr>
										<td valign='top'><a href='arrears_assign.php'><img src="images/arrears.jpg" width="65px"></a></td>
										<td valign='top'><strong>Bulk Assign Accounts to EDC</strong><br />
										This feature enables you to bulk assign overdue accounts to the EDCs.</td>
									</tr>
									<tr>
										<td valign='top'><a href='business.php'><img src="images/task_checklist.jpg" width="65px"></a></td>
										<td valign='top'><strong>Create Business Types</strong><br />
										Create/ Activate & Edit business types in the system.</td>
									</tr>
									<tr>
										<td valign='top'><a href='user_failed_logs.php'><img src="images/sys_logs.png" width="65px"></a></td>
										<td valign='top'><strong>User Failed Logs</strong><br />
										This features enable you to view failed logs of the system</td>
									</tr>
									<tr>
										<td valign='top'><a href='optimizedb.php'><img src="images/database.jpg" width="65px"></a></td>
										<td valign='top'><strong>Optimize Database</strong><br />
										This feature enables you to optimize the database, makes it faster.</td>
									</tr>
									<tr>
										<td valign='top'><a href='holidays.php'><img src="images/task_checklist.jpg" width="65px"></a></td>
										<td valign='top'><strong>Create Public Holidays</strong><br />
										Create/ Activate & Edit public holidays in the system.</td>
									</tr>
								</table>
							</td>
						</tr>
					<?php }
					else{ ?>
						<tr>
								<td width="50%" valign="top">
									<table width="100%" border="0" cellspacing="2" cellpadding="2" >
										<tr>
											<td valign='top'><a href='user_details.php'><img src="images/users.jpg" width="65px"></a></td>
											<td valign='top'><strong>User Profiles</strong><br />
											This feature lets you view all the Users and right levels in the system.</td>
										</tr>
									</table>
								</td>
							</tr>
						<?php } ?>
						</table>
					<hr>
				</div>
			</div>
			<br class="clearfix" />
			</div>
		</div>
<?php
	}
	include_once('includes/footer.php');
?>
