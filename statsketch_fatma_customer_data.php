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
		$page_title = "Loans Disbursed Report";
		include_once('includes/db_conn.php');
		
		$filter_month = date("m");
		$filter_year = date("Y");
		$filter_day = date("d");
		$current_date = $filter_year.'-'.$filter_month.'-'.$filter_day;
		$current_date_full = date("d M, Y", strtotime($current_date));
		
		$report_term = 7;
		$start_report_date = date('Y-m-d',strtotime($current_date) - (24 * 3600 * $report_term));

		if (!empty($_GET)){	
			$loan_officer = $_GET['loan_officer'];
			$filter_start_date = $_GET['report_start_date'];
			$filter_start_date = date('Y-m-d', strtotime(str_replace('-', '/', $filter_start_date)));
			$filter_end_date = $_GET['report_end_date'];
			$filter_end_date = date('Y-m-d', strtotime(str_replace('-', '/', $filter_end_date)));
		}
		$filter_start_date = '2015-10-17';
		$filter_end_date = '2015-11-30';
		$filter_repayments_date = '2016-02-29';
			
		?>
					<table width="100%" border="0" cellspacing="2" cellpadding="2" class="display" id="example2">
					<thead bgcolor="#E6EEEE">
						<tr>
							<th>#</th>
							<th>Customer_id</th>
							<th>Sales</th>
							<th>Expenses</th>
							<th>Affordability</th>
							<th>Gender</th>
							<th>Branch</th>
						</tr>
					</thead>
					<tbody>
					<?php
						
						$sql = mysql_query("select id, affordability, gender, stations from users where national_id != '' order by id asc");
						while ($row = mysql_fetch_array($sql))
						{
							$intcount++;				
							$stations = $row['stations'];
							$affordability = $row['affordability'];
							$customer_id = $row['id'];
							$gender = $row['gender'];
							
							$sql2 = mysql_query("select business_category, business_address, business_rent, business_utilities, employees, licensing, storage, transport, weekly_sales, spend_stock from business_details where user_id = '$customer_id' order by id desc limit 1");
							while ($row = mysql_fetch_array($sql2)) {
								$business_category = $row['business_category'];
								$business_address = $row['business_address'];
								$business_rent = $row['business_rent'];
								$business_utilities = $row['business_utilities'];
								$employees = $row['employees'];
								$licensing = $row['licensing'];
								$storage = $row['storage'];
								$transport = $row['transport'];
								$weekly_sales = $row['weekly_sales'];
								$spend_stock = $row['spend_stock'];
								$business_expenses = $business_rent + $employess + $business_utilities + $licensing + $storage + $transport;
							}
							
							$sql2 = mysql_query("select stations from stations where id = '$stations'");
							while ($row = mysql_fetch_array($sql2)) {
								$stations = $row['stations'];
							}
							
							$sql2 = mysql_query("select gender from gender where id = '$gender'");
							while ($row = mysql_fetch_array($sql2)) {
								$gender = $row['gender'];
							}
							
							
							if ($intcount % 2 == 0) {
								$display= '<tr bgcolor = #F0F0F6>';
							}
							else {
								$display= '<tr>';
							}
							
							echo $display;
							echo "<td valign='top'>$intcount.</td>";
							echo "<td valign='top'>$customer_id</td>";	
							echo "<td valign='top' align='right'>".number_format($weekly_sales, 0)."</td>";
							echo "<td valign='top' align='right'>".number_format($business_expenses, 0)."</td>";
							echo "<td valign='top' align='right'>".number_format($affordability, 0)."</td>";
							echo "<td valign='top'>$gender</td>";
							echo "<td valign='top'>$stations</td>";
							echo "</tr>";
							
							$customer_id = "";
							$first_name = "";
							$last_name = "";
							$national_id = "";
							$preffered_language = "";
							$nickname = "";
							$date_of_birth = "";
                            $marital = "";
                            $dependants = "";
                            $home_address = "";
                            $owns = "";
                            $home_occupy = "";
                        
						}
						?>
					</tbody>
				</table>

<?php
	}
	include_once('includes/footer.php');
?>
