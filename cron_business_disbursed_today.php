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
		$filter_start_date = '2015-02-01';
		$filter_end_date = '2015-10-31';
			
		?>
					<h2><?php echo $page_title ?></h2>
					<h3>Report Date: <?php echo $filter_start_date_full ?> and <?php echo $filter_end_date_full ?></h3>
					<table width="100%" border="0" cellspacing="2" cellpadding="2" class="display" id="example2">
					<thead bgcolor="#E6EEEE">
						<tr>
							<th>#</th>
							<th>Customer</th>
							<th>Branch</th>
							<th>Loan Code</th>
							<th>Loan Date</th>
							<th>Due Date</th>
							<th>Mobile</th>
							<th>Amount</th>
							<th>Interest </th>
							<th>Initiation Fee</th>
							<th>Extensions</th>
							<th>Waiver</th>
							<th>Early</th>
							<th>Surplus</th>
							<th>Penalty </th>
							<th>Status </th>
							<th>Repayments </th>
							<th>Balance </th>
							<th>Last Payment Date</th>
						</tr>
					</thead>
					<tbody>
					<?php
						
						//$sql10 = mysql_query("select loan_code from afB_loans_confirm order by id asc");
						//while ($row = mysql_fetch_array($sql10))
						//{
						//    $confirm_loan_code = $row['loan_code'];	
						    
                            $sql = mysql_query("select loan_id, customer_id, customer_station, loan_date, loan_due_date, loan_mobile, initiation_fee, loan_amount, loan_extension, loan_interest, loan_late_interest, waiver, admin_fee, appointment_fee, early_settlement, early_settlement_surplus, fix, joining_fee, loan_total_interest, loan_code, loan_mpesa_code, loan_failure_status, loan_status from loan_application where loan_date between '2016-11-14' and '2016-12-05' order by loan_date asc");
                         
                             $interest = 0;
                             $total_loan_amount = 0;
                             $total_interest = 0;
                             $total_loan_total_interest = 0;
                             $intcount = 0;
                             $total_allocation_fees = 0;
                             while ($row = mysql_fetch_array($sql))
                             {
                                $intcount++;
                                $loan_id = $row['loan_id'];					
                                $loan_date = $row['loan_date'];
                                $loan_due_date = $row['loan_due_date'];
                                $loan_mobile = $row['loan_mobile'];
                                $customer_id = $row['customer_id'];
                                $customer_station = $row['customer_station'];
                                $initiation_fee = $row['initiation_fee'];
                                $loan_amount = $row['loan_amount'];
                                $loan_extension = $row['loan_extension'];
                                $loan_interest = $row['loan_interest'];
                                $loan_total_interest = $row['loan_total_interest'];
                                $loan_status = $row['loan_status'];
                                $loan_code = $row['loan_code'];
                                $loan_status = $row['loan_status'];
                                $loan_late_interest = $row['loan_late_interest'];
                                $loan_mpesa_code = $row['loan_mpesa_code'];
                                $loan_failure_status = $row['loan_failure_status'];
                            
                                $admin_fee = $row['admin_fee'];
                                $appointment_fee = $row['appointment_fee'];
                                $early_settlement = $row['early_settlement'];
                                $early_settlement_surplus = $row['early_settlement_surplus'];
                                $fix = $row['fix'];
                                $joining_fee = $row['joining_fee'];
                                $waiver = $row['waiver'];
                            
                                $allocation_fees = $waiver + $admin_fee + $appointment_fee + $early_settlement + $early_settlement_surplus + $fix + $joining_fee;

                                $fees = $loan_extension + $initiation_fee + $allocation_fees;
                            
                                $sql2 = mysql_query("select id, stations from stations where id = '$customer_station'");
                                while($row = mysql_fetch_array($sql2)) {
                                    $stations = $row['stations'];
                                    $stations = ucwords(strtolower($stations));
                                }
                            
                                $sql2 = mysql_query("select status from customer_status where id = '$loan_status'");
                                while ($row = mysql_fetch_array($sql2)) {
                                    $loan_status_name = $row['status'];
                                }

                                $sql2 = mysql_query("select first_name, last_name from users where id = '$customer_id'");
                                while($row = mysql_fetch_array($sql2)) {
                                    $first_name = $row['first_name'];
                                    $first_name = ucwords(strtolower($first_name));
                                    $last_name = $row['last_name'];
                                    $last_name = ucwords(strtolower($last_name));
                                    $customer_name = $first_name.' '.$last_name;
                                }
                            
                                $sql2 = mysql_query("select sum(loan_rep_amount)repayments from loan_repayments where loan_rep_code = '$loan_code' group by loan_rep_code");
                                while ($row = mysql_fetch_array($sql2)) {
                                    $repayments = $row['repayments'];
                                    if ($repayments == '') {
                                        $repayments = 0;
                                    }
                                }
                            
                                $loan_balance = $loan_total_interest - $repayments;
                            
                                $sql2 = mysql_query("select loan_rep_date from loan_repayments where loan_rep_code = '$loan_code' order by loan_rep_date desc limit 1");
                                while ($row = mysql_fetch_array($sql2)) {
                                    $loan_rep_date = $row['loan_rep_date'];
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
                                echo "<td valign='top'>$stations</td>";	
                                echo "<td valign='top'>$loan_code</td>";
                                echo "<td valign='top'>$loan_date</td>";					
                                echo "<td valign='top'>$loan_due_date</td>";
                                echo "<td valign='top'>$loan_mobile</td>";
                                echo "<td valign='top' align='right'>".number_format($loan_amount, 2)."</td>";
                                echo "<td valign='top' align='right'>".number_format($loan_interest, 2)."</td>";
                                echo "<td valign='top' align='right'>".number_format($initiation_fee, 2)."</td>";
                                echo "<td valign='top' align='right'>".number_format($loan_extension, 2)."</td>";
                                echo "<td valign='top' align='right'>".number_format($waiver, 2)."</td>";
                                echo "<td valign='top' align='right'>".number_format($early_settlement, 2)."</td>";
                                echo "<td valign='top' align='right'>".number_format($early_settlement_surplus, 2)."</td>";
                                echo "<td valign='top' align='right'>".number_format($loan_late_interest, 2)."</td>";
                                echo "<td valign='top'>$loan_status_name</td>";
                                echo "<td valign='top' align='right'>".number_format($repayments, 2)."</td>";
                                echo "<td valign='top' align='right'>".number_format($loan_balance, 2)."</td>";
                                echo "<td valign='top'>$loan_rep_date</td>";
                                echo "</tr>";
                                $sql3 = mysql_query("select loan_rep_date, loan_rep_amount from loan_repayments where loan_rep_code = '$loan_code'");
                                while ($row = mysql_fetch_array($sql3)) {
                                    $loan_rep_date = $row['loan_rep_date'];
                                    $loan_rep_amount = $row['loan_rep_amount'];
                                    echo '<tr>';
                                    echo "<td valign='top'>Repayments:</td>";
                                    echo "<td valign='top'>&nbsp;</td>";
                                    echo "<td valign='top'>&nbsp;</td>";
                                    echo "<td valign='top'>$loan_code</td>";
                                    echo "<td valign='top'>$loan_rep_date</td>";
                                    echo "<td valign='top' align='right'>".number_format($loan_rep_amount, 2)."</td>";
                                    $loan_rep_amount = 0;
                                    $loan_rep_date = "";
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
                            }
                        //}
						?>
					</tbody>
				</table>

<?php
	}
	include_once('includes/footer.php');
?>
