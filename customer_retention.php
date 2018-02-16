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
		$filter_month = date("m");
		$filter_year = date("Y");
		$filter_day = date("d");
		$current_date = $filter_year.'-'.$filter_month.'-'.$filter_day;
	
		$transactiontime = date("Y-m-d G:i:s");
		$page_title = "Customer Retention Report";
		include_once('includes/header.php');
		$filter_clerk = 0;
		if (!empty($_GET)){	
			$filter_clerk = $_GET['clerk'];
			$filter_select_year = $_GET['year'];
			$filter_start_date = $_GET['report_start_date'];
			$filter_start_date = date('Y-m-d', strtotime(str_replace('-', '/', $filter_start_date)));
			$filter_end_date = $_GET['report_end_date'];
			$filter_end_date = date('Y-m-d', strtotime(str_replace('-', '/', $filter_end_date)));
		}
		include_once('includes/db_conn.php');	
		?>
		<div id="page">
			<div id="content">
				<div class="post">
					<h2><?php echo $page_title ?></h2>
					<h3>Report Range: 
					<?php
						$sql2 = mysql_query("select distinct EXTRACT(year FROM loan_date)year from loan_application group by EXTRACT(year FROM loan_date)");
						while($row = mysql_fetch_array($sql2)) {
							$year = $row['year'];
							if($year != '0'){
								echo "<a href='customer_retention.php?year=$year'>$year</a> | ";
							}
						}
					?>
					</h3>
					<h3>New Customers</h3>
					<table width="100%" border="0" cellspacing="2" cellpadding="2" class="display" id="example">
					<thead bgcolor="#E6EEEE">
						<tr>
							<th>Name</th>
							<th>Mobile</th>
							<th>Branch</th>
							<th>Date</th>
							<th>Amount</th>
							<th>Affordability</th>
							<th>Business</th>
						</tr>
					</thead>
					<tbody>
					<?php
						if($station == '3'){
							$sql = mysql_query("select distinct customer_id, concat(first_name, ' ', last_name)customer_name, loan_date, loan_amount, mobile_no, count(loan_id)counts, alt_phone, affordability, customer_station from loan_application inner join users on users.id = loan_application.customer_id where EXTRACT(year FROM loan_date) = '$filter_select_year' group by customer_id order by loan_date desc");
						}
						else{
							 $sql = mysql_query("select distinct customer_id, concat(first_name, ' ', last_name)customer_name, loan_date, loan_amount, mobile_no, national_id, count(loan_id)counts, alt_phone, affordability, customer_station from loan_application inner join users on users.id = loan_application.customer_id where loan_date between '$filter_start_date' and '$filter_end_date' and customer_station = '$station' group by customer_id order by loan_date desc");
						}
						
						while ($row = mysql_fetch_array($sql))
						{
							$intcount++;
							$customer_id = $row['customer_id'];
							$customer_name = $row['customer_name'];
							$customer_name = ucwords(strtolower($customer_name));
							$mobile_no = $row['mobile_no'];
							$national_id = $row['national_id'];
							$loan_date = $row['loan_date'];
							$loan_amount = $row['loan_amount'];
							$counts = $row['counts'];
							$alt_phone = $row['alt_phone'];
							$affordability = $row['affordability'];
							$customer_station = $row['customer_station'];
							$sql2 = mysql_query("select id, status from loan_declined_status_codes where id = '$status'");
							while($row = mysql_fetch_array($sql2)) {
								$status_name = $row['status'];
								if($status_name == ""){
									$status_name = 'Active';
								}
							}
							$sql2 = mysql_query("select id, stations from stations where id = '$customer_station'");
							while($row = mysql_fetch_array($sql2)) {
								$stations = $row['stations'];
								$stations = ucwords(strtolower($stations));
							}
							if($counts == 1){
								if ($intcount % 2 == 0) {
									$display= '<tr bgcolor = #F0F0F6>';
								}
								else {
									$display= '<tr>';
								}
								echo $display;
							
								echo "<td valign='top'>$customer_name</td>";
								echo "<td valign='top'>$mobile_no</td>";	
								echo "<td valign='top'>$stations</td>";	
								echo "<td valign='top'>$loan_date</td>";	
								echo "<td valign='top' align='right'>".number_format($loan_amount, 2)."</td>";	
								echo "<td valign='top' align='right'>".number_format($affordability, 2)."</td>";	
								echo "<td valign='top'><a href='business_details.php?user_id=$id&mode=edit'><img src='images/folder-horizontal.png' width='35px'></a></td>";
								echo "</tr>";
							}
						}
						?>
					</tbody>
					<tfoot bgcolor="#E6EEEE">
						<tr>
							<th>Name</th>
							<th>Mobile</th>
							<th>Branch</th>
							<th>Date</th>
							<th>Amount</th>
							<th>Affordability</th>
							<th>Business</th>
						</tr>
					</tfoot>
				</table>
				<br /><br />
				<h3>Active Customers</h3>
					<table width="100%" border="0" cellspacing="2" cellpadding="2" class="display" id="example2">
					<thead bgcolor="#E6EEEE">
						<tr>
							<th>Name</th>
							<th>Mobile</th>
							<th>Branch</th>
							<th>Date</th>
							<th>Amount</th>
							<th>Affordability</th>
							<th>Business</th>
						</tr>
					</thead>
					<tbody>
					<?php
						if($station == '3'){
							$sql = mysql_query("select distinct customer_id, concat(first_name, ' ', last_name)customer_name, loan_date, loan_amount, mobile_no, count(loan_id)counts, alt_phone, affordability, customer_station from loan_application inner join users on users.id = loan_application.customer_id where EXTRACT(year FROM loan_date) = '$filter_select_year' group by customer_id order by loan_date desc");
						}
						else{
							 $sql = mysql_query("select distinct customer_id, concat(first_name, ' ', last_name)customer_name, loan_date, loan_amount, mobile_no, national_id, count(loan_id)counts, alt_phone, affordability, customer_station from loan_application inner join users on users.id = loan_application.customer_id where loan_date between '$filter_start_date' and '$filter_end_date' and customer_station = '$station' group by customer_id order by loan_date desc");
						}
						
						while ($row = mysql_fetch_array($sql))
						{
							$intcount++;
							$customer_id = $row['customer_id'];
							$customer_name = $row['customer_name'];
							$customer_name = ucwords(strtolower($customer_name));
							$mobile_no = $row['mobile_no'];
							$national_id = $row['national_id'];
							$loan_date = $row['loan_date'];
							$loan_amount = $row['loan_amount'];
							$counts = $row['counts'];
							$alt_phone = $row['alt_phone'];
							$affordability = $row['affordability'];
							$customer_station = $row['customer_station'];
							$sql2 = mysql_query("select id, status from loan_declined_status_codes where id = '$status'");
							while($row = mysql_fetch_array($sql2)) {
								$status_name = $row['status'];
								if($status_name == ""){
									$status_name = 'Active';
								}
							}
							$sql2 = mysql_query("select id, stations from stations where id = '$customer_station'");
							while($row = mysql_fetch_array($sql2)) {
								$stations = $row['stations'];
								$stations = ucwords(strtolower($stations));
							}
							if($counts > 1){
								if ($intcount % 2 == 0) {
									$display= '<tr bgcolor = #F0F0F6>';
								}
								else {
									$display= '<tr>';
								}
								echo $display;
							
								echo "<td valign='top'>$customer_name</td>";
								echo "<td valign='top'>$mobile_no</td>";	
								echo "<td valign='top'>$stations</td>";	
								echo "<td valign='top'>$loan_date</td>";	
								echo "<td valign='top' align='right'>".number_format($loan_amount, 2)."</td>";	
								echo "<td valign='top' align='right'>".number_format($affordability, 2)."</td>";	
								echo "<td valign='top'><a href='business_details.php?user_id=$id&mode=edit'><img src='images/folder-horizontal.png' width='35px'></a></td>";
								echo "</tr>";
							}
						}
						?>
					</tbody>
					<tfoot bgcolor="#E6EEEE">
						<tr>
							<th>Name</th>
							<th>Mobile</th>
							<th>Branch</th>
							<th>Date</th>
							<th>Amount</th>
							<th>Affordability</th>
							<th>Business</th>
						</tr>
					</tfoot>
				</table>
				<br /><br />
				<h3>Dormant Customers</h3>
				<table width="100%" border="0" cellspacing="2" cellpadding="2" class="display" id="example3">
					<thead bgcolor="#E6EEEE">
						<tr>
							<th>Name</th>
							<th>Mobile</th>
							<th>National ID</th>
							<th>Alt Phone</th>
							<th>Affordability</th>
							<th>LO</th>
							<th>CO</th>
							<th>Business</th>
							<th>Loan Date</th>
						</tr>
					</thead>
					<tbody>
					<?php
						if($station == '3'){
							$sql = mysql_query("select distinct customer_id, loan_date, concat(first_name, ' ', last_name)customer_name, mobile_no, count(loan_id)counts, alt_phone, affordability, loan_application.loan_officer, loan_application.collections_officer, national_id from loan_application inner join users on users.id = loan_application.customer_id where loan_status = '13' and EXTRACT(year FROM loan_date) = '$filter_select_year' group by customer_id order by loan_date desc");
						}
						else{
							 $sql = mysql_query("select distinct customer_id, loan_date, concat(first_name, ' ', last_name)customer_name, mobile_no, national_id, count(loan_id)counts, alt_phone, affordability, loan_application.loan_officer, loan_application.collections_officer from loan_application inner join users on users.id = loan_application.customer_id where customer_station = '$station' and loan_status = '13' group by customer_id order by loan_date desc");
						}
						
						while ($row = mysql_fetch_array($sql))
						{
							$intcount++;
							$customer_id = $row['customer_id'];
							$loan_date = $row['loan_date'];
							$customer_name = $row['customer_name'];
							$customer_name = ucwords(strtolower($customer_name));
							$mobile_no = $row['mobile_no'];
							$national_id = $row['national_id'];
							$counts = $row['counts'];
							$alt_phone = $row['alt_phone'];
							$affordability = $row['affordability'];
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
							
							$date1 = strtotime($loan_date);
							$date2 = strtotime($current_date);
							$dateDiff = $date2 - $date1;
							$days = floor($dateDiff/(60*60*24));
							
							if($days > 120){
								if ($intcount % 2 == 0) {
									$display= '<tr bgcolor = #F0F0F6>';
								}
								else {
									$display= '<tr>';
								}
								echo $display;
							
								echo "<td valign='top'>$customer_name</td>";
								echo "<td valign='top'>$mobile_no</td>";
								echo "<td valign='top'>$national_id</td>";	
								echo "<td valign='top'>$alt_phone</td>";	
								echo "<td valign='top' align='right'>".number_format($affordability, 2)."</td>";	
								echo "<td valign='top'>$loan_officer_name</td>";
								echo "<td valign='top'>$collect_officer_name</td>";	
								echo "<td valign='top'><a href='business_details.php?user_id=$id&mode=edit'><img src='images/folder-horizontal.png' width='35px'></a></td>";
								echo "<td valign='top'>$loan_date</td>";	
								echo "</tr>";
							}
						}
						?>
					</tbody>
					<tfoot bgcolor="#E6EEEE">
						<tr>
							<th>Name</th>
							<th>Mobile</th>
							<th>National ID</th>
							<th>Alt Phone</th>
							<th>Affordability</th>
							<th>LO</th>
							<th>CO</th>
							<th>Business</th>
							<th>Loan Date</th>
						</tr>
					</tfoot>
				</table>
			</div>
			<br class="clearfix" />
			</div>
		</div>
<?php
	}
	include_once('includes/footer.php');
?>
