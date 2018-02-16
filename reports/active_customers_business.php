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
		$page_title = "Active & New Customers Report";
		include_once('includes/header.php');
		$filter_clerk = 0;
		if (!empty($_GET)){	
			$branch = $_GET['branch'];
			$filter_start_date = $_GET['report_start_date'];
			$filter_start_date = date('Y-m-d', strtotime(str_replace('-', '/', $filter_start_date)));
			$filter_end_date = $_GET['report_end_date'];
			$filter_end_date = date('Y-m-d', strtotime(str_replace('-', '/', $filter_end_date)));
		}
		include_once('includes/db_conn.php');
		$sql2 = mysql_query("select loan_date from loan_application where loan_date != '0000-00-00' order by loan_date asc limit 1");
		while ($row = mysql_fetch_array($sql2))
		{
			$filter_start_date_report = $row['loan_date'];
		}
		if ($filter_start_date != "" && $filter_end_date != ""){
			
		?>
		<div id="page">
			<div id="content">
				<div class="post">
					<h2><?php echo $page_title ?></h2>
					<p><strong>Report Range: <?php echo $filter_start_date ?> to <?php echo $filter_end_date ?></strong></p>
					<table width="100%" border="0" cellspacing="2" cellpadding="2" class="display">
						<tr>
							<td width="50%" valign="top">
								<h3>New Customers</h3>
								<table width="100%" border="0" cellspacing="2" cellpadding="2" class="display" id="exampl2">
								<thead bgcolor="#E6EEEE">
									<tr>
										<th>Branch</th>
										<th>Counts</th>
									</tr>
								</thead>
								<tbody>
								<?php
									if($branch == ''){
										//$sql = mysql_query("select distinct customer_station, count(loan_id)new_customers from loan_application where initiation_fee > '0' and customer_id != '4' and customer_id != '3' and customer_id != '10' and loan_date between '$filter_start_date' and '$filter_end_date' group by customer_station order by customer_station");
										$sql = mysql_query("select distinct customer_station, count(loan_id)new_customers from loan_application where initiation_fee != '0' and loan_date between '$filter_start_date' and '$filter_end_date' and loan_status != '4' and loan_status != '3' and loan_status != '10' and loan_status != '11' and loan_status != '12' group by customer_station order by customer_station");
									}
									else{
										 $sql = mysql_query("select distinct customer_station, count(loan_id)new_customers from loan_application where initiation_fee != '0' and loan_date between '$filter_start_date' and '$filter_end_date' and loan_status != '4' and loan_status != '3' and loan_status != '10' and loan_status != '11' and loan_status != '12' group by customer_station order by customer_station");
									}
									$intcount = 0;
									$total_customers = 0;
									while ($row = mysql_fetch_array($sql))
									{
										$intcount++;
										$new_customers = $row['new_customers'];
										$customer_station = $row['customer_station'];
										$sql2 = mysql_query("select stations from stations where id = '$customer_station'");	
										while($row = mysql_fetch_array($sql2)) {
											$station_name = $row['stations'];
										}
										
										if ($intcount % 2 == 0) {
											$display= '<tr bgcolor = #F0F0F6>';
										}
										else {
											$display= '<tr>';
										}
										echo $display;
										echo "<td valign='top'>$station_name</td>";
										echo "<td valign='top' align='right'>".number_format($new_customers, 0)."</td>";		
										echo "</tr>";
										$total_new_customers = $total_new_customers + $new_customers;
									}
									?>
								</tbody>
								<tr bgcolor = '#E6EEEE'>
									<td ><strong>&nbsp;</strong></td>
									<td align='right' valign='top'><strong><?php echo number_format($total_new_customers, 0) ?></strong></td>
								</tr>
								<tfoot bgcolor="#E6EEEE">
									<tr>
										<th>Branch</th>
										<th>Counts</th>
									</tr>
								</tfoot>
							</table>
						</td>
						<td width="50%" valign="top">
							<h3>Active Customers</h3>
								<table width="100%" border="0" cellspacing="2" cellpadding="2" class="display" id="exampl3">
								<thead bgcolor="#E6EEEE">
									<tr>
										<th>Branch</th>
										<th>Counts</th>
									</tr>
								</thead>
								<tbody>
								<?php
									if($branch == ''){
										$sql = mysql_query("select distinct customer_station, count(distinct customer_id)active_customers from loan_application where loan_status != '4' and loan_status != '3' and loan_status != '10' and loan_status != '11' and loan_status != '12' and loan_date between '$filter_start_date' and '$filter_end_date' group by customer_station order by customer_station");
									}
									else{
										 $sql = mysql_query("select distinct customer_station, count(distinct customer_id)active_customers from loan_application where loan_status != '4' and loan_status != '3' and loan_status != '10' and loan_status != '11' and loan_status != '12' and loan_date between '$filter_start_date' and '$filter_end_date' group by customer_station order by customer_station");
									}
									$intcount = 0;
									$total_customers = 0;
									while ($row = mysql_fetch_array($sql))
									{
										$intcount++;
										$active_customers = $row['active_customers'];
										$customer_station = $row['customer_station'];
										$sql2 = mysql_query("select stations from stations where id = '$customer_station'");	
										while($row = mysql_fetch_array($sql2)) {
											$station_name = $row['stations'];
										}
										
										if ($intcount % 2 == 0) {
											$display= '<tr bgcolor = #F0F0F6>';
										}
										else {
											$display= '<tr>';
										}
										echo $display;
										echo "<td valign='top'>$station_name</td>";
										echo "<td valign='top' align='right'>".number_format($active_customers, 0)."</td>";		
										echo "</tr>";
										$total_active_customers = $total_active_customers + $active_customers;
									}
									?>
								</tbody>
								<tr bgcolor = '#E6EEEE'>
									<td ><strong>&nbsp;</strong></td>
									<td align='right' valign='top'><strong><?php echo number_format($total_active_customers, 0) ?></strong></td>
								</tr>
								<tfoot bgcolor="#E6EEEE">
									<tr>
										<th>Branch</th>
										<th>Counts</th>
									</tr>
								</tfoot>
							</table>
						</td>
					</tr>
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
