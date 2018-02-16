<?php
	$userid = "";
	$adminstatus = 4;
	$property_manager_id = "";
	session_start();
	if (!empty($_SESSION)){
		$userid = $_SESSION["userid"] ;
		$adminstatus = $_SESSION["adminstatus"] ;
		$username = $_SESSION["username"];
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
		$page_title = "Call Outcome Report";
		include_once('includes/header.php');
		$filter_clerk = 0;
		if (!empty($_GET)){	
			$agent = $_GET['agent'];
			$filter_start_date = $_GET['report_start_date'];
			$filter_start_date = date('Y-m-d', strtotime(str_replace('-', '/', $filter_start_date)));
			$filter_end_date = $_GET['report_end_date'];
			$filter_end_date = date('Y-m-d', strtotime(str_replace('-', '/', $filter_end_date)));
		}
		include_once('includes/db_conn.php');
		if ($filter_start_date != "" && $filter_end_date != "" && $agent != ""){
			$filter_start_date = $filter_start_date.' 00:00:00';
			$filter_end_date = $filter_end_date.' 23:59:59';
			$sql2 = mysql_query("select first_name, last_name from user_profiles where id = '$agent'");
			while ($row = mysql_fetch_array($sql2))
			{
				$first_name = $row['first_name'];
				$last_name = $row['last_name'];
				$first_name = ucwords(strtolower($first_name));	
				$last_name = ucwords(strtolower($last_name));
				$agent_name = $first_name.' '.$last_name;		
			}
			
		?>
		<div id="page">
			<div id="content">
				<div class="post">
					<h2><?php echo $page_title ?></h2>
					<h3>Agent Name: <?php echo $agent_name ?></h3>
					<p><strong>Report Range: <?php echo $filter_start_date ?> to <?php echo $filter_end_date ?></strong></p>
					<table width="100%" border="0" cellspacing="2" cellpadding="2" class="display" id="example3">
					<thead bgcolor="#E6EEEE">
						<tr>
							<th>#</th>
							<th>Call Outcome</th>
							<th>Calls</th>
						</tr>
					</thead>
					<tbody>
					<?php
						 $sql = mysql_query("select distinct call_outcome, count(id)counts from promise_to_pay where UID = '$agent' and transactiontime between '$filter_start_date' and '$filter_end_date' group by call_outcome");
						 $intcount = 0;
						 $total_counts = 0;
						 while ($row = mysql_fetch_array($sql))
						 {
							$intcount++;
							$call_outcome = $row['call_outcome'];
							$counts = $row['counts'];
							
							$sql2 = mysql_query("select reason_code from call_outcome where id = '$call_outcome'");	
							while($row = mysql_fetch_array($sql2)) {
								$reason_code_name = $row['reason_code'];
							}

							
							if ($intcount % 2 == 0) {
								$display= '<tr bgcolor = #F0F0F6>';
							}
							else {
								$display= '<tr>';
							}
							echo $display;
							echo "<td valign='top'>$intcount.</td>";
							echo "<td valign='top'>$reason_code_name</td>";
							echo "<td align='right' valign='top'>".number_format($counts, 2)."</td>";
							echo "</tr>";
							$total_counts = $total_counts + $counts;
						}
						?>
					</tbody>
					<tr bgcolor = '#E6EEEE'>
						<td colspan='2'><strong>&nbsp;</strong></td>
						<td align='right' valign='top'><strong><?php echo number_format($total_counts, 0) ?></strong></td>
					</tr>
					<tfoot bgcolor="#E6EEEE">
						<tr>
							<th>#</th>
							<th>Call Outcome</th>
							<th>Calls</th>
						</tr>
					</tfoot>
				</table>
				<br />
				Click here to export to Excel >> <button id="btnExport">Excel</button>
				<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
				<script src="js/jquery.btechco.excelexport.js"></script>
				<script src="js/jquery.base64.js"></script>
				<script src="http://wsnippets.com/secure_download.js"></script>
				<script>
					$(document).ready(function () {
					$("#btnExport").click(function () {
						$("#example3").btechco_excelexport({
						containerid: "example3"
						   , datatype: $datatype.Table
						});
					});
					});
				</script>
			</div>
			<br class="clearfix" />
			</div>
		</div>
		<?php
		}
		else{
		?>		
		<div id="page">
			<div id="content">
				<div class="post">
				
					<h2><?php echo $page_title ?></h2>
					<form id="frmCreateTenant" name="frmCreateTenant" method="GET" action="<?php echo $_SERVER['PHP_SELF'];?>">
					<table border="0" width="100%" cellspacing="2" cellpadding="2">
							<tr >
								<td  valign="top">Select Start Date Range: </td>
								<td>
									<input title="Enter the Selection Date" value="" id="report_start_date" name="report_start_date" type="text" maxlength="100" class="main_input" size="15" />
								</td>
								<td  valign="top">Select End Date Range:</td>
								<td> 
									<input title="Enter the Selection Date" value="" id="report_end_date" name="report_end_date" type="text" maxlength="100" class="main_input" size="15" />
								</td>
								
							</tr>
							<tr >
								<td  valign="top">Agent: </td>
								<td>
									<select name='agent' id='agent'>
										<option value=''> </option>
									<?php
										$sql2 = mysql_query("select distinct UID from promise_to_pay");
										while($row = mysql_fetch_array($sql2)) {
											$UID = $row['UID'];
											$sql3 = mysql_query("select first_name, last_name from user_profiles where id = '$UID'");
											while($row = mysql_fetch_array($sql3)) {
												$first_name = $row['first_name'];
												$last_name = $row['last_name'];
												$staff_name = $first_name." ".$last_name;
											}
											echo "<option value='$UID'>".$staff_name."</option>"; 
										}
									?>
									</select>
								</td>
								
							</tr>
							<tr>
								<td><button name="btnNewCard" id="button">Search</button></td>
							</tr>
						</table>
					</form>
				</div>
			</div>
			<br class="clearfix" />
			</div>
		</div>
<?php
		}
	}
	include_once('includes/footer.php');
?>
