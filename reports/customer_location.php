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
		$page_title = "Branch Customer Information";
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
			
		?>
		<div id="page">
			<div id="content">
				<div class="post">
					<h2><?php echo $page_title ?></h2>
					<p><strong>Report Range: <?php echo $filter_start_date ?> to <?php echo $filter_end_date ?></strong></p>
					<table width="100%" border="0" cellspacing="2" cellpadding="2" class="display" id="example">
						<thead bgcolor="#E6EEEE">
							<tr>
								<th>#</th>
								<th>Customer</th>
								<th>Phone</th>
								<th>Alt Phone</th>
								<th>Branch</th>
								<th>Home Address</th>
								<th>Business Address</th>
							</tr>
						</thead>
						<tbody>
						<?php
						if($station == '3'){
							$sql = mysql_query("select users.id, first_name, last_name, mobile_no, alt_phone, home_address, business_details.business_address, stations from users inner join business_details on business_details.user_id = users.id where users.transactiontime between '$filter_start_date' and '$filter_end_date' order by first_name asc");
						}
						else{
							$sql = mysql_query("select users.id, first_name, last_name, mobile_no, alt_phone, home_address, business_details.business_address, stations from users inner join business_details on business_details.user_id = users.id where users.transactiontime between '$filter_start_date' and '$filter_end_date' and stations = '$station' order by first_name asc");
						}
						$intcount = 0;
							 while ($row = mysql_fetch_array($sql))
							 {
								$intcount++;
								$id = $row['id'];					
								$first_name = $row['first_name'];
								$last_name = $row['last_name'];
								$first_name = ucwords(strtolower($first_name));
								$last_name = ucwords(strtolower($last_name));
								$customer_name = $first_name.' '.$last_name;
								$mobile_no = $row['mobile_no'];
								$alt_phone = $row['alt_phone'];
								$home_address = $row['home_address'];
								$business_address = $row['business_address'];
								$stations = $row['stations'];
								
								$sql2 = mysql_query("select stations from stations where id = '$stations'");
								while ($row = mysql_fetch_array($sql2))
								{
									$customer_stations = $row['stations'];
								}

								if ($intcount % 2 == 0) {
									$display= '<tr bgcolor = #F0F0F6>';
								}
								else {
									$display= '<tr>';
								}
								echo $display;
								echo "<td valign='top'>$intcount.</td>";
								echo "<td valign='top'>$customer_name</td>";
								echo "<td valign='top'>$mobile_no</td>";
								echo "<td valign='top'>$alt_phone</td>";
								echo "<td valign='top'>$customer_stations</td>";
								echo "<td valign='top'>$home_address</td>";
								echo "<td valign='top'>$business_address</td>";
								echo "</tr>";
							}
							?>
						</tbody>
						<tfoot bgcolor="#E6EEEE">
							<tr>
								<th>#</th>
								<th>Customer</th>
								<th>Phone</th>
								<th>Alt Phone</th>
								<th>Branch</th>
								<th>Home Address</th>
								<th>Business Address</th>
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
