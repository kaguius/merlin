<?php
	$userid = "";
	$adminstatus = "";
	session_start();
	if (!empty($_SESSION)){
		$userid = $_SESSION["userid"] ;
		$adminstatus = $_SESSION["adminstatus"] ;
	}
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
		if (!empty($_GET)){		
			$status = $_GET['status'];
		}
		$transactiontime = date("Y-m-d G:i:s");
		if (!empty($_GET)){	
			$action = $_GET['action'];
			$id = $_GET['id'];
			if ($action=='edit'){
				$page_title = "Edit station Detail";
				$result = mysql_query("select id, stations, daily_target, weekly_target, monthly_target, target_customers, paybill, active, freeze, repeater, transactiontime, parent_branch from stations where id = '$id'");
				while ($row = mysql_fetch_array($result))
				{
					$id = $row['id'];
					$stations = $row['stations'];
					$daily_target = $row['daily_target'];
					$weekly_target = $row['weekly_target'];
					$monthly_target = $row['monthly_target'];
					$target_customers = $row['target_customers'];
					$paybill = $row['paybill'];
					$active = $row['active'];
					$freeze = $row['freeze'];
					$repeater = $row['repeater'];
					$parent_station_id = $row['parent_branch'];
					
					// Get the name of the parent branch
					$parent_branch_name = "";
					$sql_parent_branch_name = mysql_query("select stations from stations where id = '$parent_station_id'");
					while ($row = mysql_fetch_array($sql_parent_branch_name)) {
						$parent_branch_name = $row['stations'];
					}
					if($active == '1'){
						$active_name = 'No';
					}
					else{
						$active_name = 'Yes';
					}
					if($freeze == '1'){
						$freeze_name = 'No';
					}
					else{
						$freeze_name = 'Yes';
					}
					
					if($repeater == '1'){
						$repeater_freeze = 'No';
					}
					else{
						$repeater_freeze = 'Yes';
					}
				}
			}
		}
		else{
			$page_title = "Create New Station";
		}
		include_once('includes/header.php');
	?>
	<div id="page">
			<div id="content">
				<div class="post">
				<h2><?php echo $page_title ?></h2>
					<form id="frmExecPosition" name="frmExecPosition" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">

						<input type="hidden" name="page_status" id="page_status" value="<?php echo $action ?>" />
						<input type="hidden" name="id" id="id" value="<?php echo $id ?>" />
						<table border="0" width="100%">
							<tr bgcolor = #F0F0F6>
								<td>Stations *</td>
								<td>
									<input title="Enter Station" value="<?php echo $stations ?>" id="stations" name="stations" type="text" maxlength="100" class="main_input" size="30" />
								</td>
							</tr>
							<tr>
								<td>Daily Target *</td>
								<td>
									<input title="Enter Daily Target" value="<?php echo $daily_target ?>" id="daily_target" name="daily_target" type="text" maxlength="100" class="main_input" size="30" />
								</td>
							</tr>
							<tr bgcolor = #F0F0F6>
								<td>Weekly Target *</td>
								<td>
									<input title="Enter Daily Target" value="<?php echo $weekly_target ?>" id="weekly_target" name="weekly_target" type="text" maxlength="100" class="main_input" size="30" />
								</td>
							</tr>
							<tr >
								<td>Monthly Target *</td>
								<td>
									<input title="Enter Monthly Target" value="<?php echo $monthly_target ?>" id="monthly_target" name="monthly_target" type="text" maxlength="100" class="main_input" size="30" />
								</td>
							</tr>
							<tr >
								<td>Monthly Customer Target *</td>
								<td>
									<input title="Enter Monthly Customer Target" value="<?php echo $target_customers ?>" id="target_customers" name="target_customers" type="text" maxlength="100" class="main_input" size="30" />
								</td>
							</tr>
							<tr bgcolor = #F0F0F6>
								<td >Paybill Number*</td>
								<td>
									<input title="Enter Paybill Number" value="<?php echo $paybill ?>" id="paybill" name="paybill" type="text" maxlength="100" class="main_input" size="30" />
								</td>
							</tr>
							<tr >
								<td valign='top' >Freeze Branch *</td>
								<td valign='top' >
									<select name='freeze' id='freeze'>
										<?php
										if($action == 'edit'){
										?>
										<option value="<?php echo $freeze ?>"><?php echo $freeze_name ?></option>	
										<?php
										}
										else{
										?>
										<option value=''> </option>
										<?php
										}
										?>
										<option value=''> </option>
										<option value='0'>Yes</option>
										<option value='1'>No</option>
									</select>
								</td>
							</tr>
							<tr >
								<td valign='top' >Repeater Freeze*</td>
								<td valign='top' >
									<select name='repeater' id='repeater'>
										<?php
										if($action == 'edit'){
										?>
										<option value="<?php echo $repeater ?>"><?php echo $repeater_freeze ?></option>	
										<?php
										}
										else{
										?>
										<option value=''> </option>
										<?php
										}
										?>
										<option value=''> </option>
										<option value='0'>Yes</option>
										<option value='1'>No</option>
									</select>
								</td>
							</tr>
							<tr bgcolor = #F0F0F6>
								<td valign='top' >Active *</td>
								<td valign='top' >
									<select name='active' id='active'>
										<?php
										if($action == 'edit'){
										?>
										<option value="<?php echo $active ?>"><?php echo $active_name ?></option>	
										<?php
										}
										else{
										?>
										<option value=''> </option>
										<?php
										}
										?>
										<option value=''> </option>
										<option value='0'>Yes</option>
										<option value='1'>No</option>
									</select>
								</td>
							</tr>
							<tr>
                            <td valign='top' >Parent Branch *</td>
                            <td valign='top' >
                                <select name='branch_id' id='parent_station_id'>
                                    <?php
                                    if ($action == 'edit') {
                                        ?>
                                        <option style="padding:5pxo 8px" value="<?php echo $parent_station_id; ?>"><?php echo $parent_branch_name; ?></option>	
                                        <?php
                                    } else {
                                        ?>
                                        <option style="padding:5px 8px" value=''>--- Select parent branch ---</option>
                                        <?php
                                    }

                                    // Get a list of possible parent branches
                                    $sql_parent_stations = mysql_query("select id, stations from stations WHERE id <> '3' AND id <> '4' AND id <> '10' AND parent_station_id = '0' order by stations asc", $dbh1);
                                    while ($row = mysql_fetch_array($sql_parent_stations)) {
                                        $parent_id = $row['id'];
                                        $candidate_parent_station = $row['stations'];
                                        echo "<option style='padding:5px 8px' value='$parent_id'>" . $candidate_parent_station . "</option>";
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
							
							<?php if($status == 'stations_exists'){ ?>
								<tr>
									<td colspan="2">
										<font color="red">The stations specified already exists, enter another one.</font></a>	
									</td>
								</tr>
							<?php } ?>		
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
							var frmvalidator = new Validator("frmExecPosition");
							frmvalidator.addValidation("stations","req","Please enter the stations Name");
							frmvalidator.addValidation("paybill","req","Please enter the paybill number for the station");
							frmvalidator.addValidation("active","req","Please enter Status of the Product Type");
						</script>
					</form>
			</div>
			</div>
			<br class="clearfix" />
			</div>
		</div>
		<?php
			if (!empty($_POST)) {
				$stations = $_POST['stations'];
				$daily_target = $_POST['daily_target'];
				$weekly_target = $_POST['weekly_target'];
				$monthly_target = $_POST['monthly_target'];
				$target_customers = $_POST['target_customers'];
				$paybill = $_POST['paybill'];
				$parent_station_id = $_POST["branch_id"];
				
				$active = $_POST['active'];
				$freeze = $_POST['freeze'];
				$repeater = $_POST['repeater'];
				
				$page_status = $_POST['page_status'];
				$id = $_POST['id'];
				
				if($page_status == 'edit'){
					$sql3="
					update stations set stations='$stations', paybill='$paybill', daily_target = '$daily_target', weekly_target = '$weekly_target', monthly_target = '$monthly_target', target_customers = '$target_customers', active= '$active', freeze = '$freeze', repeater = '$repeater', transactiontime = '$transactiontime', parent_branch = '$parent_station_id' WHERE ID  = '$id'";
					$result = mysql_query($sql3);
					//echo $sql3;
				}
				else{
					$result_tender = mysql_query("select distinct stations from stations where stations = '$stations'");
					while ($row = mysql_fetch_array($result_tender))
					{
						$stations_exists = $row['stations'];
					}
				
					$stations_exists = strtolower($stations_exists);
					$stations = strtolower($stations);

					if(($stations_exists != $stations)) {
						$stations = ucwords(strtolower($stations));
						$sql="
						INSERT INTO stations (stations, daily_target, weekly_target, monthly_target, target_customers, paybill, active, freeze, repeater, transactiontime, parent_branch)
						VALUES('$stations', '$daily_target', '$weekly_target', '$monthly_target', '$target_customers', '$paybill', '$active', '$freeze', '$repeater', '$transactiontime', '$parent_station_id')";

						//echo $sql;
						$result = mysql_query($sql);
					}				
					else{

						$stations_exists = MD5(stations_exists);
						$query = "update_station.php?status=stations_exists&stations_status=$stations_exists";
						//echo $query;
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

				?>
				<script type="text/javascript">
				<!--
					document.location = "station.php";
				//-->
				</script>
				<?php
			}				
	}
?>
<?php
	include_once('includes/footer.php');
?>
