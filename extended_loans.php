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
		$page_title = "Extended/ Restructured Loans Report";
		include_once('includes/header.php');
		$filter_clerk = 0;
		if (!empty($_GET)){	
			$filter_clerk = $_GET['clerk'];
			$filter_start_date = $_GET['report_start_date'];
			$filter_start_date = date('Y-m-d', strtotime(str_replace('-', '/', $filter_start_date)));
			$filter_end_date = $_GET['report_end_date'];
			$filter_end_date = date('Y-m-d', strtotime(str_replace('-', '/', $filter_end_date)));
		}
		include_once('includes/db_conn.php');
		if ($filter_start_date != "" && $filter_end_date != ""){
			$filter_start_date_full = $filter_start_date.' 00:00:00';
			$filter_end_date_full = $filter_end_date.' 23:59:59';
			
		?>
		<div id="page">
			<div id="content">
				<div class="post">
					<h2><?php echo $page_title ?></h2>
					<p><strong>Report Range: <?php echo $filter_start_date ?> to <?php echo $filter_end_date ?></strong></p>
					<table width="100%" border="0" cellspacing="2" cellpadding="2" class="display" id="example3">
					<thead bgcolor="#E6EEEE">
						<tr>
							<th>#</th>
							<th>Branch</th>
							<th>Loan Officer</th>
							<th>Collections Officer</th>
							<th>Count</th>
						</tr>
					</thead>
					<tbody>
					<?php
						 $sql = mysql_query("select distinct loan_application.customer_station, loan_application.loan_officer, loan_application.collections_officer, count(id)loan_extensions from loan_extensions inner join loan_application on loan_application.loan_code = loan_extensions.loan_code where loan_extensions.transactiontime between '$filter_start_date_full' and '$filter_end_date_full' group by loan_application.customer_station, loan_application.loan_officer ");
						 $intcount = 0;
						 $total_loan_rep_amount = 0;
						 while ($row = mysql_fetch_array($sql))
						 {
							$intcount++;
							$customer_station = $row['customer_station'];
							$loan_officer = $row['loan_officer'];
							$collections_officer = $row['collections_officer'];
							$loan_extensions = $row['loan_extensions'];
							
							$sql2 = mysql_query("select stations from stations where id = '$customer_station'");	
							while($row = mysql_fetch_array($sql2)) {
								$station_name = $row['stations'];
							}
							
							$sql2 = mysql_query("select first_name, last_name from user_profiles where id = '$loan_officer'");
							while ($row = mysql_fetch_array($sql2))
							{
								$first_name = $row['first_name'];
								$last_name = $row['last_name'];
								$first_name = ucwords(strtolower($first_name));	
								$last_name = ucwords(strtolower($last_name));
								$loan_officer_name = $first_name.' '.$last_name;		
							}
							
							$sql2 = mysql_query("select first_name, last_name from user_profiles where id = '$collections_officer'");
							while ($row = mysql_fetch_array($sql2))
							{
								$first_name = $row['first_name'];
								$last_name = $row['last_name'];
								$first_name = ucwords(strtolower($first_name));	
								$last_name = ucwords(strtolower($last_name));
								$collections_officer_name = $first_name.' '.$last_name;		
							}

							
							if ($intcount % 2 == 0) {
								$display= '<tr bgcolor = #F0F0F6>';
							}
							else {
								$display= '<tr>';
							}
							echo $display;
							echo "<td valign='top'>$intcount.</td>";
							echo "<td valign='top'>$station_name</td>";
							echo "<td valign='top'>$loan_officer_name</td>";
							echo "<td valign='top'>$collections_officer_name</td>";
							echo "<td valign='top'>".number_format($loan_extensions, 0)."</td>";			
							echo "</tr>";
							$total_loan_extensions = $total_loan_extensions + $loan_extensions;
						}
						?>
					</tbody>
					<tr bgcolor = '#E6EEEE'>
						<td colspan='4'><strong>&nbsp;</strong></td>
						<td align='right' valign='top'><strong><?php echo number_format($total_loan_extensions, 0) ?></strong></td>
					</tr>
					<tfoot bgcolor="#E6EEEE">
						<tr>
							<th>#</th>
							<th>Branch</th>
							<th>Loan Officer</th>
							<th>Collections Officer</th>
							<th>Count</th>
						</tr>
					</tfoot>
				</table>
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
