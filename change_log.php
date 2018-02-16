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
		$page_title = "Change Log Summary Report";
		include_once('includes/header.php');
		$filter_clerk = 0;
		if (!empty($_GET)){	
			$customer_name = $_GET['customer_name'];
			$filter_start_date = $_GET['report_start_date'];
			$filter_start_date = date('Y-m-d', strtotime(str_replace('-', '/', $filter_start_date)));
			$filter_end_date = $_GET['report_end_date'];
			$filter_end_date = date('Y-m-d', strtotime(str_replace('-', '/', $filter_end_date)));
		}
		include_once('includes/db_conn.php');
		if ($filter_start_date != "" && $filter_end_date != ""){
			$filter_start_date = $filter_start_date.' 00:00:00';
			$filter_end_date = $filter_end_date.' 23:59:59';
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
								<th>Date</th>
								<th>Name</th>
								<th>Table</th>
								<th>Customer</th>
								<th>Loan</th>
								<th>Variable</th>
								<th>Old Value</th>
								<th>New Value</th>
								<th>Comment</th>
							</tr>
						</thead>
						<tbody>
						<?php
						if($customer_name == ""){
						 	$sql = mysql_query("select change_log.UID, concat(first_name,' ',last_name)user_name, loan_code, table_name, customer_id, change_log.transactiontime, comment, variable, old_value, new_value from change_log inner join user_profiles on user_profiles.id = change_log.UID where change_log.transactiontime between '$filter_start_date' and '$filter_end_date' order by change_log.transactiontime desc");
						 }
						 else{
						 	$sql = mysql_query("select change_log.UID, concat(first_name,' ',last_name)user_name, loan_code, table_name, customer_id, change_log.transactiontime, comment, variable, old_value, new_value from change_log inner join user_profiles on user_profiles.id = change_log.UID where change_log.transactiontime between '$filter_start_date' and '$filter_end_date' and customer_id = '$customer_name' order by change_log.transactiontime desc");
						 }
						 while ($row = mysql_fetch_array($sql))
						 {
							$intcount++;
							$UID = $row['UID'];
							$user_name = $row['user_name'];
							$table_name = $row['table_name'];
							$customer_id = $row['customer_id'];
							$transactiontime = $row['transactiontime'];
							$loan_code = $row['loan_code'];
							$comment = $row['comment'];
							$variable = $row['variable'];
							$old_value = $row['old_value'];
							$new_value = $row['new_value'];
							$user_name = ucwords(strtolower($user_name));
							
							$sql2 = mysql_query("select first_name, last_name from users where id = '$customer_id'");
							while ($row = mysql_fetch_array($sql2))
							{
								$first_name = $row['first_name'];
								$last_name = $row['last_name'];
								$first_name = ucwords(strtolower($first_name));
								$last_name = ucwords(strtolower($last_name));
								$customer_name = $first_name.' '.$last_name;
							}
							
							if ($intcount % 2 == 0) {
								$display= '<tr bgcolor = #F0F0F6>';
							}
							else {
								$display= '<tr>';
							}
							echo $display;
							echo "<td valign='top'>$intcount.</td>";
							echo "<td valign='top'>$transactiontime</td>";
							echo "<td valign='top'>$user_name</td>";
							echo "<td valign='top'>$table_name</td>";
							echo "<td valign='top'>$customer_name</td>";
							echo "<td valign='top'>$loan_code</td>";
							echo "<td valign='top'>$variable</td>";
							echo "<td valign='top'>$old_value</td>";
							echo "<td valign='top'>$new_value</td>";
							echo "<td valign='top'>$comment</td>";
							echo "</tr>";
						}
						?>
						</tbody>
						<tfoot bgcolor="#E6EEEE">
							<tr>
								<th>#</th>
								<th>Date</th>
								<th>Name</th>
								<th>Table</th>
								<th>Customer</th>
								<th>Loan</th>
								<th>Variable</th>
								<th>Old Value</th>
								<th>New Value</th>
								<th>Comment</th>
							</tr>
						</tfoot>
					</table>
					<br />
				</div>
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
								<td>Customer Name *</td>
								<td>
									<select name='customer_name' id='customer_name'>
										<option value=''> </option>
									<?php
										$sql2 = mysql_query("select distinct customer_id, concat(users.first_name, ' ', users.last_name)customer_name from change_log inner join users on users.id = change_log.customer_id group by customer_id order by customer_name asc");
										while($row = mysql_fetch_array($sql2)) {
											$customer_id = $row['customer_id'];
											$customer_name = $row['customer_name'];
											$customer_name = ucwords(strtolower($customer_name));
											echo "<option value='$customer_id'>".$customer_name."</option>"; 
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
