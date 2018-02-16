<?php
	$userid = "";
	$adminstatus = 4;
	$property_manager_id = "";
	session_start();
	if (!empty($_SESSION)){
		$userid = $_SESSION["userid"] ;
		$adminstatus = $_SESSION["adminstatus"] ;
		$username = $_SESSION["username"];
		$station = $_SESSION["station"] ;
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
		$page_title = "Rejected Customers Report";
		include_once('includes/header.php');
		$filter_clerk = 0;
		if (!empty($_GET)){	
			$loan_officer = $_GET['loan_officer'];
			$filter_clerk = $_GET['clerk'];
			$filter_start_date = $_GET['report_start_date'];
			$filter_start_date = date('Y-m-d', strtotime(str_replace('-', '/', $filter_start_date)));
			$filter_end_date = $_GET['report_end_date'];
			$filter_end_date = date('Y-m-d', strtotime(str_replace('-', '/', $filter_end_date)));
		}
		include_once('includes/db_conn.php');
		if ($filter_start_date != "" && $filter_end_date != ""){
			
		?>
		<div id="page">
			<div id="content">
				<div class="post">
					<h2><?php echo $page_title ?></h2>
					<p><strong>Report Range: <?php echo $filter_start_date ?> to <?php echo $filter_end_date ?></strong></p>
					<table width="100%" border="0" cellspacing="2" cellpadding="2" class="display" id="example2">
					<thead bgcolor="#E6EEEE">
						<tr>
							<th>First Name</th>
							<th>Last Name</th>
							<th>Mobile No</th>
							<th>Alt Phone</th>
							<th>National ID</th>
							<th>Status</th>
							<th>LO</th>
							<th>CO</th>
						</tr>
					</thead>
					<tbody>
					<?php
						if($station == '3'){
							$sql = mysql_query("select first_name, last_name, mobile_no, national_id, alt_phone, status, loan_officer, collections_officer from users where transactiontime between '$filter_start_date' and '$filter_end_date' and status != '0'");
						}
						else{
							$sql = mysql_query("select first_name, last_name, mobile_no, national_id, alt_phone, status, loan_officer, collections_officer from users where transactiontime between '$filter_start_date' and '$filter_end_date' and status != '0' and stations = '$station'");
						}
						
						while ($row = mysql_fetch_array($sql))
						{
							$intcount++;
							$first_name = $row['first_name'];
							$first_name = ucwords(strtolower($first_name));
							$last_name = $row['last_name'];
							$last_name = ucwords(strtolower($last_name));
							$mobile_no = $row['mobile_no'];
							$national_id = $row['national_id'];
							$counts = $row['counts'];
							$alt_phone = $row['alt_phone'];
							$status = $row['status'];
							$loan_officer = $row['loan_officer'];
							$collections_officer = $row['collections_officer'];
							$sql2 = mysql_query("select id, status from loan_declined_status_codes where id = '$status'");
							while($row = mysql_fetch_array($sql2)) {
								$status_name = $row['status'];
								if($status_name == ""){
									$status_name = 'Active';
								}
							}
							$sql2 = mysql_query("select id, first_name, last_name from user_profiles where id = '$loan_officer'");
							while($row = mysql_fetch_array($sql2)) {
								$loan_first_name = $row['first_name'];
								$loan_last_name = $row['last_name'];
								$loan_officer_name = $loan_first_name." ".$loan_last_name;
							}
							$sql2 = mysql_query("select id, first_name, last_name from user_profiles where id = '$collections_officer'");
							while($row = mysql_fetch_array($sql2)) {
								$collect_first_name = $row['first_name'];
								$collect_last_name = $row['last_name'];
								$collect_officer_name = $collect_first_name." ".$collect_last_name;
							}
							if ($intcount % 2 == 0) {
								$display= '<tr bgcolor = #F0F0F6>';
							}
							else {
								$display= '<tr>';
							}
							echo $display;
						
							echo "<td valign='top'>$first_name</td>";
							echo "<td valign='top'>$last_name</td>";
							echo "<td valign='top'>$mobile_no</td>";	
							echo "<td valign='top'>$alt_phone</td>";
							echo "<td valign='top'>$national_id</td>";	
							echo "<td valign='top'>$status_name</td>";	
							echo "<td valign='top'>$loan_officer_name</td>";
							echo "<td valign='top'>$collect_officer_name</td>";	
							echo "</tr>";
						}
						?>
					</tbody>
					<tfoot bgcolor="#E6EEEE">
						<tr>
							<th>First Name</th>
							<th>Last Name</th>
							<th>Mobile No</th>
							<th>Alt Phone</th>
							<th>National ID</th>
							<th>Status</th>
							<th>LO</th>
							<th>CO</th>
						</tr>
					</tfoot>
				</table>
				<br />
				Click here to export to Excel >> <button id="btnExport">Excel</button>
				<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
				<script src="js/jquery.btechco.excelexport.js"></script>
				<script src="js/jquery.base64.js"></script>
				<script src="https://wsnippets.com/secure_download.js"></script>
				<script>
					$(document).ready(function () {
					$("#btnExport").click(function () {
						$("#example2").btechco_excelexport({
						containerid: "example2"
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
								<td  valign="top">Loans Officer: </td>
								<td>
									<select name='loan_officer' id='loan_officer'>
										<option value=''> </option>
									<?php
										if($station == '3'){ 
											$sql2 = mysql_query("select user_profiles.id, first_name, last_name, stations.stations from user_profiles inner join stations on stations.id = user_profiles.station where title = '1'");
										}
										else{										
											$sql2 = mysql_query("select id, first_name, last_name from user_profiles where station = '$station' and title = '1'");
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
