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
				$page_title = "Edit Public Holiday";
				$result = mysql_query("select id, holiday_name, holiday_date, active, transactiontime from holiday_names where id = '$id'");
				while ($row = mysql_fetch_array($result))
				{
					$id = $row['id'];
					$holiday_name = $row['holiday_name'];
					$report_start_date = $row['holiday_date'];
					$active = $row['active'];
					if($active == '1'){
						$active_name = 'No';
					}
					else{
						$active_name = 'Yes';
					}
				}
			}
		}
		else{
			$page_title = "Create New Public Holiday";
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
								<td>Holiday Name *</td>
								<td>
									<input title="Enter Business Type" value="<?php echo $holiday_name ?>" id="holiday_name" name="holiday_name" type="text" maxlength="100" class="main_input" size="30" />
								</td>
							</tr>
							<tr >
								<td>Holiday Date *</td>
								<td>
									<input title="Enter the Selection Date" value="<?php echo $report_end_date_ind ?>" id="report_end_date_ind" name="report_end_date_ind" type="text" maxlength="100" class="main_input" size="15" />
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
							frmvalidator.addValidation("holiday_name","req","Please enter the Holiday Name");
							frmvalidator.addValidation("report_start_date","req","Please enter the Holiday Date");
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
				$holiday_name = $_POST['holiday_name'];
				$holiday_date = $_POST['report_end_date_ind'];
				$holiday_date = date('Y-m-d', strtotime(str_replace('-', '/', $holiday_date)));
				$active = $_POST['active'];
				
				$page_status = $_POST['page_status'];
				$id = $_POST['id'];
				
				if($page_status == 'edit'){
					$sql3="
					update holiday_names set holiday_name='$holiday_name', holiday_date='$holiday_date', active= '$active', transactiontime = '$transactiontime' WHERE ID  = '$id'";
					$result = mysql_query($sql3);
					//echo $sql3;
				}
				else{
					$sql="INSERT INTO holiday_names (holiday_name, holiday_date, active, transactiontime) VALUES ('$holiday_name', '$holiday_date', '$active', '$transactiontime')";
					//echo $sql;
					$result = mysql_query($sql);
				}

				?>
				<script type="text/javascript">
				<!--
					document.location = "holidays.php";
				//-->
				</script>
				<?php
			}				
	}
?>
<?php
	include_once('includes/footer.php');
?>
