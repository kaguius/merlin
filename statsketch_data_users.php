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
					<h2><?php echo $page_title ?></h2>
					<table width="100%" border="0" cellspacing="2" cellpadding="2" class="display" id="example2">
					<thead bgcolor="#E6EEEE">
						<tr>
							<th>#</th>
							<th>Customer_id</th>
							<th>First Name</th>
							<th>Last Name</th>
							<th>National ID</th>
							<th>Preffered Language</th>
							<th>Known As</th>
							<th>DOB</th>
							<th>Marital</th>
							<th>Dependents </th>
							<th>Home Address</th>
							<th>Rents/Owns</th>
							<th>Lived there Since</th>
							<th>Customer State</th>
						</tr>
					</thead>
					<tbody>
					<?php
						
						$sql = mysql_query("select id, first_name, last_name, national_id, preffered_language, nickname, date_of_birth, marital, dependants, home_address, owns, home_occupy from users where stations IN ('9', '13', '17') order by first_name asc");
						while ($row = mysql_fetch_array($sql))
						{
							$intcount++;
							$customer_id = $row['id'];					
							$first_name = $row['first_name'];
							$last_name = $row['last_name'];
							$national_id = $row['national_id'];
							$preffered_language = $row['preffered_language'];
							$nickname = $row['nickname'];
							$date_of_birth = $row['date_of_birth'];
							$marital = $row['marital'];
							$dependants = $row['dependants'];
							$home_address = $row['home_address'];
							$owns = $row['owns'];
							$home_occupy = $row['home_occupy'];
							
							$sql2 = mysql_query("select marital from marital where id = '$marital'");
                            while($row = mysql_fetch_array($sql2)) {
                                $marital = $row['marital'];
                            }
							
							if($national_id == ""){
							    $customer_state = "Lead";
							}
							else{
							    $customer_state = "Customer";
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
							echo "<td valign='top'>$first_name</td>";	
							echo "<td valign='top'>$last_name</td>";
							echo "<td valign='top'>$national_id</td>";					
							echo "<td valign='top'>$preffered_language</td>";
							echo "<td valign='top'>$nickname</td>";
							echo "<td valign='top'>$date_of_birth</td>";	
							echo "<td valign='top'>$marital</td>";
							echo "<td valign='top'>$dependants</td>";					
							echo "<td valign='top'>$home_address</td>";
							echo "<td valign='top'>$owns</td>";
							echo "<td valign='top'>$home_occupy</td>";
							echo "<td valign='top'>$customer_state</td>";
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
