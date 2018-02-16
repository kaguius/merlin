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
		$filter_start_date = '2016-09-16';
		$filter_end_date = '2016-10-15';
		$filter_repayments_date = '2016-05-31';
			
		?>
					<h2><?php echo $page_title ?></h2>
					<h3>Report Date: <?php echo $filter_start_date_full ?> and <?php echo $filter_end_date_full ?></h3>
					<table width="100%" border="0" cellspacing="2" cellpadding="2" class="display" id="example2">
					<thead bgcolor="#E6EEEE">
						<tr>
							<th>#</th>
							<th>loan Code</th>
							<th>Loan Date</th>
							<th>Loan Due Date</th>
							<th>Days</th>
							<th>Loan Status</th>
							<th>Current Collector</th>
							<th>Branch</th>
							<th>Repayments</th>
						</tr>
					</thead>
					<tbody>
					<?php
						
						$sql = mysql_query("select customer_station, loan_code, loan_date, loan_due_date, loan_status, loan_late_interest, current_collector from loan_application where loan_due_date between '$filter_start_date' and '$filter_end_date' and loan_status != '2' and loan_late_interest != '0' and current_collector != '0'");
						 
						 $interest = 0;
						 $total_loan_amount = 0;
						 $total_interest = 0;
						 $total_loan_total_interest = 0;
						 $intcount = 0;
						 $total_allocation_fees = 0;
						 while ($row = mysql_fetch_array($sql))
						 {
							$intcount++;
							$loan_code = $row['loan_code'];					
							$loan_date = $row['loan_date'];
							$loan_due_date = $row['loan_due_date'];
							$loan_status = $row['loan_status'];
							$loan_late_interest = $row['loan_late_interest'];
							$current_collector = $row['current_collector'];
							$customer_station = $row['customer_station'];
							
							$sql2 = mysql_query("select id, stations from stations where id = '$customer_station'");
							while($row = mysql_fetch_array($sql2)) {
								$stations = $row['stations'];
								$stations = ucwords(strtolower($stations));
							}
							
							$sql2 = mysql_query("select status from customer_status where id = '$loan_status'");
                        	while ($row = mysql_fetch_array($sql2)) {
								$loan_status_name = $row['status'];
                            }

							$sql2 = mysql_query("select first_name, last_name from user_profiles where id = '$current_collector'");
							while($row = mysql_fetch_array($sql2)) {
								$first_name = $row['first_name'];
								$first_name = ucwords(strtolower($first_name));
								$last_name = $row['last_name'];
								$last_name = ucwords(strtolower($last_name));
								$staff_name = $first_name.' '.$last_name;
							}
							
							$sql2 = mysql_query("select sum(loan_rep_amount)repayments from loan_repayments where loan_rep_code = '$loan_code' and loan_rep_date between '$filter_start_date' and '$filter_end_date' group by loan_rep_code");
							while ($row = mysql_fetch_array($sql2)) {
								$repayments = $row['repayments'];
								if ($repayments == '') {
									$repayments = 0;
								}
							}
							
							echo "<tr>";
							echo "<td valign='top'>$intcount.</td>";
							echo "<td valign='top'>$loan_code</td>";
							echo "<td valign='top'>$loan_date</td>";	
							echo "<td valign='top'>$loan_due_date</td>";
							echo "<td valign='top'>&nbsp;</td>";	
							echo "<td valign='top'>$loan_status_name</td>";					
							echo "<td valign='top'>$staff_name</td>";
							echo "<td valign='top'>$stations</td>";
							echo "<td valign='top' align='right'>".number_format($repayments, 2)."</td>";
							echo "</tr>";
							$staff_name = "";
							$sql3 = mysql_query("select loan_rep_date, loan_rep_amount, current_collector from loan_repayments where loan_rep_date between '$filter_start_date' and '$filter_end_date' and loan_rep_code = '$loan_code' order by loan_rep_date asc");
                            while ($row = mysql_fetch_array($sql3)) {
                                $loan_rep_date = $row['loan_rep_date'];
                                $loan_rep_amount = $row['loan_rep_amount'];
                                $current_collector = $row['current_collector'];
                                
                                $datetime1 = strtotime($loan_due_date);
								$datetime2 = strtotime($loan_rep_date);
								$secs = $datetime2 - $datetime1;
								$days = $secs / 86400;

                                $sql4 = mysql_query("select first_name, last_name from user_profiles where id = '$current_collector'");
								while($row = mysql_fetch_array($sql4)) {
									$first_name = $row['first_name'];
									$first_name = ucwords(strtolower($first_name));
									$last_name = $row['last_name'];
									$last_name = ucwords(strtolower($last_name));
									$staff_name = $first_name.' '.$last_name;
								}
                                
                                echo '<tr>';
                                echo "<td valign='top'>Repayments:</td>";
                                echo "<td valign='top'>$loan_code</td>";
                                echo "<td valign='top'>&nbsp;</td>";
                                echo "<td valign='top'>$loan_rep_date</td>";
                                echo "<td valign='top'>$days</td>";
                                echo "<td valign='top'>&nbsp;</td>";
                                echo "<td valign='top'>$staff_name</td>";
                                echo "<td valign='top'>$stations</td>";
                                echo "<td valign='top' align='right'>".number_format($loan_rep_amount, 2)."</td>";
                                
                                $loan_rep_amount = 0;
                                $loan_rep_date = "";
                                $days = "";
                            }
							
							$initiation_fee = 0;
							$loan_amount = 0;
							$loan_extension = 0;
							$loan_late_interest = 0;
							$loan_interest = 0;
							$initiation_fee = 0;
							$waiver = 0;
							$early_settlement = 0;
							$early_settlement_surplus = 0;
							$loan_total_interest = 0;
							$allocation_fees = 0;
							$loan_mpesa_code = "";
							$repayments = 0;
							$loan_balance = 0;
							$loan_rep_date = "";
							$staff_name = "";
						}
						?>
					</tbody>
				</table>

<?php
	}
	include_once('includes/footer.php');
?>
