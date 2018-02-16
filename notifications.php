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
		$transactiontime = date("Y-m-d G:i:s");
		$page_title = "Notifications";
		include_once('includes/header.php');
		include_once('includes/notifications.php');
		$result_tender = mysql_query("select first_name, last_name, email_address from user_profiles where username = '$username'");
		while ($row = mysql_fetch_array($result_tender))
		{
			$first_name = $row['first_name'];
			$last_name = $row['last_name'];
			$email_address = $row['email_address'];
		}
		if($title == '3'){
			$result_tender = mysql_query("select count(loan_id)loan_count from loan_application where customer_station = '$station' and loan_status = '16'");
			while ($row = mysql_fetch_array($result_tender))
			{
				$total_loan_count = $row['loan_count'];
			}
		}
		else if($title == '8'){
			$total_loan_count = 0;
			$result_tender = mysql_query("select id from stations where parent_branch = '$station'");
			while ($row = mysql_fetch_array($result_tender))
			{
				$branches = $row['id'];
				$result_tender_2 = mysql_query("select count(loan_id)loan_count from loan_application where customer_station = '$branches' and loan_status = '10'");
				while ($row = mysql_fetch_array($result_tender_2))
				{
					$loan_count = $row['loan_count'];
				}
				$total_loan_count = $total_loan_count + $loan_count;	
			}
		}
		else{
			$total_loan_count = 0;
			$result_tender = mysql_query("select parent_branch from sectors where branch_manager = '$userid'");
			while ($row = mysql_fetch_array($result_tender))
			{
				$parent_branch = $row['parent_branch'];
				$result_tender_2 = mysql_query("select count(loan_id)loan_count from loan_application where customer_station = '$parent_branch' and loan_status = '16'");
				while ($row = mysql_fetch_array($result_tender_2))
				{
					$loan_count = $row['loan_count'];
				}
				$total_loan_count = $total_loan_count + $loan_count;	
			}
		}
		$result = mysql_query("select count(id)nugget from overdue_comments where mgt_UID != '' and UID = '$userid'");
		while ($row = mysql_fetch_array($result))
		{
			$nugget = $row['nugget'];
		}
		
		$notifications = $nugget + $issue_log;
		?>		
		<div id="page">
			<div id="content">
				<div class="post">
					<h2><font color="#000A8B">Notifications:</font> Dashboard</h2>
					<h3>Welcome: <?php echo $first_name.' '.$last_name; ?>! (You have <a href="notifications.php"><?php echo $total_loan_count ?> Unresolved Notifications</a>)</h3>
					<table width="100%" border="0" cellspacing="2" cellpadding="2" class="display" id="example2">
					<thead bgcolor="#E6EEEE">
						<tr>
							<th>#</th>
							<th>Loan Date</th>
							<th>Branch</th>
							<th>Ref</th>
							<th>Phone</th>
							<th>Status</th>
							<th>Amount</th>
							<th>Profile</th>
							<th>Business</th>
							<th>Map</th>
							<th>Accounts</th>
						</tr>
					</thead>
					<tbody>
					<?php
					if($title == '3'){
						 $result_tender = mysql_query("select id from stations where parent_branch = '$station'");
						while ($row = mysql_fetch_array($result_tender))
						{
							$parent_branch = $row['id'];
							$result_tender_2 = mysql_query("select loan_id, loan_date, customer_station, customer_id, loan_code, loan_mobile, loan_status, loan_amount from loan_application where loan_status = '16' and customer_station = '$parent_branch' order by loan_date desc");
							$interest = 0;
							 $total_loan_amount = 0;
							 $total_interest = 0;
							 $total_loan_total_interest = 0;
							 $intcount = 0;
							 while ($row = mysql_fetch_array($result_tender_2))
							 {
								$intcount++;
								$loan_id = $row['loan_id'];	
								$customer_id = $row['customer_id'];					
								$loan_date = $row['loan_date'];
								$loan_due_date = $row['loan_due_date'];
								$loan_mobile = $row['loan_mobile'];
								$loan_amount = $row['loan_amount'];
								$loan_status = $row['loan_status'];
								$loan_code = $row['loan_code'];
								$loan_status = $row['loan_status'];
								$customer_station = $row['customer_station'];
							
								$sql2 = mysql_query("select status from customer_status where id = '$loan_status'");
								while ($row = mysql_fetch_array($sql2))
								{
									$loan_status_name = $row['status'];
								}
	
								$sql2 = mysql_query("select stations from stations where id = '$customer_station'");
								while ($row = mysql_fetch_array($sql2))
								{
									$stations_name = $row['stations'];
								}
							
								if ($intcount % 2 == 0) {
									$display= '<tr bgcolor = #F0F0F6>';
								}
								else {
									$display= '<tr>';
								}
								echo $display;
								echo "<td valign='top'>$intcount.</td>";
								echo "<td valign='top'>$loan_date</td>";
								echo "<td valign='top'>$stations_name</td>";
								echo "<td valign='top'>$loan_code</td>";
								echo "<td valign='top'>$loan_mobile</td>";					
								echo "<td valign='top'>$loan_status_name</td>";
								echo "<td valign='top' align='right'>".number_format($loan_amount, 2)."</td>";
								echo "<td valign='top' align='center'><a href='customer_details.php?user_id=$customer_id&mode=edit'><img src='images/titles.png' width='35px'></a></td>";
								echo "<td valign='top' align='center'><a href='business_details.php?user_id=$customer_id&mode=edit'><img src='images/folder-horizontal.png' width='35px'></a></td>";
								echo "<td valign='top' align='center'><a href='map.php?user_id=$customer_id&mode=edit'><img src='images/markers.png' width='35px'></a></td>";
								echo "<td valign='top' align='center'><a href='customer_loans.php?user_id=$customer_id'><img src='images/active.png' width='35px'></a></td>";
								echo "</tr>";
							}
						}
					}
					else if($title == '8'){
						$result_tender = mysql_query("select id from stations where parent_branch = '$station'");
						while ($row = mysql_fetch_array($result_tender))
						{
							$parent_branch = $row['id'];
							$result_tender_2 = mysql_query("select loan_id, loan_date, customer_station, customer_id, loan_code, loan_mobile, loan_status, loan_amount from loan_application where loan_status = '10' and customer_station = '$parent_branch' order by loan_date desc");
							$interest = 0;
							 $total_loan_amount = 0;
							 $total_interest = 0;
							 $total_loan_total_interest = 0;
							 $intcount = 0;
							 while ($row = mysql_fetch_array($result_tender_2))
							 {
								$intcount++;
								$loan_id = $row['loan_id'];	
								$customer_id = $row['customer_id'];					
								$loan_date = $row['loan_date'];
								$loan_due_date = $row['loan_due_date'];
								$loan_mobile = $row['loan_mobile'];
								$loan_amount = $row['loan_amount'];
								$loan_status = $row['loan_status'];
								$loan_code = $row['loan_code'];
								$loan_status = $row['loan_status'];
								$customer_station = $row['customer_station'];
							
								$sql2 = mysql_query("select status from customer_status where id = '$loan_status'");
								while ($row = mysql_fetch_array($sql2))
								{
									$loan_status_name = $row['status'];
								}
	
								$sql2 = mysql_query("select stations from stations where id = '$customer_station'");
								while ($row = mysql_fetch_array($sql2))
								{
									$stations_name = $row['stations'];
								}
							
								if ($intcount % 2 == 0) {
									$display= '<tr bgcolor = #F0F0F6>';
								}
								else {
									$display= '<tr>';
								}
								echo $display;
								echo "<td valign='top'>$intcount.</td>";
								echo "<td valign='top'>$loan_date</td>";
								echo "<td valign='top'>$stations_name</td>";
								echo "<td valign='top'>$loan_code</td>";
								echo "<td valign='top'>$loan_mobile</td>";					
								echo "<td valign='top'>$loan_status_name</td>";
								echo "<td valign='top' align='right'>".number_format($loan_amount, 2)."</td>";
								echo "<td valign='top' align='center'><a href='customer_details.php?user_id=$customer_id&mode=edit'><img src='images/titles.png' width='35px'></a></td>";
								echo "<td valign='top' align='center'><a href='business_details.php?user_id=$customer_id&mode=edit'><img src='images/folder-horizontal.png' width='35px'></a></td>";
								echo "<td valign='top' align='center'><a href='map.php?user_id=$customer_id&mode=edit'><img src='images/markers.png' width='35px'></a></td>";
								echo "<td valign='top' align='center'><a href='customer_loans.php?user_id=$customer_id'><img src='images/active.png' width='35px'></a></td>";
								echo "</tr>";
							}
						}
					}
					else if($title== '11'){
						$result_tender = mysql_query("select parent_branch from sectors where branch_manager = '$userid'");
						while ($row = mysql_fetch_array($result_tender))
						{
							$parent_branch = $row['parent_branch'];
							$result_tender_2 = mysql_query("select loan_id, loan_date, customer_station, customer_id, loan_code, loan_mobile, loan_status, loan_amount from loan_application where loan_status = '16' and customer_station = '$parent_branch' order by loan_date desc");
							$interest = 0;
							 $total_loan_amount = 0;
							 $total_interest = 0;
							 $total_loan_total_interest = 0;
							 $intcount = 0;
							 while ($row = mysql_fetch_array($result_tender_2))
							 {
								$intcount++;
								$loan_id = $row['loan_id'];	
								$customer_id = $row['customer_id'];					
								$loan_date = $row['loan_date'];
								$loan_due_date = $row['loan_due_date'];
								$loan_mobile = $row['loan_mobile'];
								$loan_amount = $row['loan_amount'];
								$loan_status = $row['loan_status'];
								$loan_code = $row['loan_code'];
								$loan_status = $row['loan_status'];
								$customer_station = $row['customer_station'];
							
								$sql2 = mysql_query("select status from customer_status where id = '$loan_status'");
								while ($row = mysql_fetch_array($sql2))
								{
									$loan_status_name = $row['status'];
								}
	
								$sql2 = mysql_query("select stations from stations where id = '$customer_station'");
								while ($row = mysql_fetch_array($sql2))
								{
									$stations_name = $row['stations'];
								}
							
								if ($intcount % 2 == 0) {
									$display= '<tr bgcolor = #F0F0F6>';
								}
								else {
									$display= '<tr>';
								}
								echo $display;
								echo "<td valign='top'>$intcount.</td>";
								echo "<td valign='top'>$loan_date</td>";
								echo "<td valign='top'>$stations_name</td>";
								echo "<td valign='top'>$loan_code</td>";
								echo "<td valign='top'>$loan_mobile</td>";					
								echo "<td valign='top'>$loan_status_name</td>";
								echo "<td valign='top' align='right'>".number_format($loan_amount, 2)."</td>";
								echo "<td valign='top' align='center'><a href='customer_details.php?user_id=$customer_id&mode=edit'><img src='images/titles.png' width='35px'></a></td>";
								echo "<td valign='top' align='center'><a href='business_details.php?user_id=$customer_id&mode=edit'><img src='images/folder-horizontal.png' width='35px'></a></td>";
								echo "<td valign='top' align='center'><a href='map.php?user_id=$customer_id&mode=edit'><img src='images/markers.png' width='35px'></a></td>";
								echo "<td valign='top' align='center'><a href='customer_loans.php?user_id=$customer_id'><img src='images/active.png' width='35px'></a></td>";
								echo "</tr>";
							}
						}
						 
					}
						?>
					</tbody>
					<tfoot bgcolor="#E6EEEE">
						<tr>
							<th>#</th>
							<th>Loan Date</th>
							<th>Branch</th>
							<th>Ref</th>
							<th>Phone</th>
							<th>Status</th>
							<th>Amount</th>
							<th>Profile</th>
							<th>Business</th>
							<th>Map</th>
							<th>Accounts</th>
						</tr>
					</tfoot>
				</table>
				</div>
			</div>
			<br class="clearfix" />
			</div>
		</div>
<?php
	}
	include_once('includes/footer.php');
?>
