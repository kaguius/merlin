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
		$page_title = "Portfolio at Risk: Branch View";
		include_once('includes/header.php');
		$filter_month = date("m");
		$filter_year = date("Y");
		$filter_day = date("d");
		$current_date = $filter_year.'-'.$filter_month.'-'.$filter_day;
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
					<table width="100%" border="0" cellspacing="2" cellpadding="2" class="display" id="exampl">
						<thead bgcolor="#E6EEEE">
							<!--<tr>
								<th>Branch</th>
								<th>Due Date</th>
								<th>Code</th>
								<th>Principal</th>
								<th>Interest</th>
								<th>Arrears Principal</th>
								<th>Arrears Interest</th>
								<!--<th>Arrears Daye</th>
								<th>1 - 30 Days</th>
								<th>31 - 60 Days</th>
								<th>61- 90 Days</th>
								<th>91 - 120 Days</th>
								<th>Over 120 Days</th>
							</tr>-->
						</thead>
						<tbody>
						<?php
							$repayments = 0;
							$outstanding_principal = 0;
							$outstanding_interest = 0;
							$loan_amount = 0; 
							$fees = 0; 
							$sql = mysql_query("select loan_id, loan_code, customer_id, customer_station, loan_mobile, loan_date, loan_due_date, loan_total_interest, loan_amount, loan_interest, loan_late_interest, initiation_fee, loan_extension, waiver, admin_fee, appointment_fee, early_settlement, early_settlement_surplus, fix, joining_fee, loan_total_interest, late_status, arreardays, vintage from loan_application where loan_status != '10' and loan_status != '9' and loan_status != '11' and loan_status != '12' and loan_status != '14' and loan_status != '15' and customer_station != '0' and loan_due_date between '$filter_start_date' and '$filter_end_date' group by loan_code order by loan_due_date asc");
									
							$intcount = 0;
							$total_loan_amount = 0;
							$total_loans = 0;
							while ($row = mysql_fetch_array($sql))
							{
								$intcount++;
								$customer_station = $row['customer_station'];
								$loan_due_date = $row['loan_due_date'];
								$loan_amount = $row['loan_amount'];
								$customer_id = $row['customer_id'];
								$loan_code = $row['loan_code'];
								$loan_interest = $row['loan_interest'];
								$loan_late_interest = $row['loan_late_interest'];
								$loan_total_interest = $row['loan_total_interest'];
	
								$arreardays = $row['arreardays'];
								$date1 = strtotime($loan_due_date);
								$date2 = strtotime($filter_end_date);
								$dateDiff = $date2 - $date1;
								$arreardays = floor($dateDiff/(60*60*24));

								//fees
								$initiation_fee = $row['initiation_fee'];
								$loan_extension = $row['loan_extension'];
								$waiver = $row['waiver'];
								$admin_fee = $row['admin_fee'];
								$appointment_fee = $row['appointment_fee'];
								$early_settlement = $row['early_settlement'];
								$early_settlement_surplus = $row['early_settlement_surplus'];
								$fix = $row['fix'];
								$joining_fee = $row['joining_fee'];

								$fees = $loan_total_interest - $loan_amount;
										
								$sql2 = mysql_query("select stations from stations where id = '$customer_station'");
								while ($row = mysql_fetch_array($sql2))
								{
									$stations = $row['stations'];
								}

								$sql2 = mysql_query("select concat(first_name, ' ', last_name)customer_name, mobile_no from users where id = '$customer_id'");
								while ($row = mysql_fetch_array($sql2))
								{
									$customer_name = $row['customer_name'];
									$mobile_no = $row['mobile_no'];
								}

								//$sql3 = mysql_query("select sum(loan_rep_amount)repayments from loan_repayments where loan_rep_code = '$loan_code' and loan_rep_date between '$filter_start_date' and '$filter_end_date' group by loan_rep_code");
								$sql3 = mysql_query("select sum(loan_rep_amount)repayments from loan_repayments where loan_rep_code = '$loan_code' group by loan_rep_code");
								while ($row = mysql_fetch_array($sql3))
								{
									$repayments = $row['repayments'];
									if($repayments == ""){
										$repayments = 0;
									}
									//$total_repayments = $total_repayments + $repayments;
								}
								$total_due = $loan_total_interest;

								if($repayments == 0){
									$outstanding_interest = $fees;
									$outstanding_principal = $loan_amount;
								}
								else{
									if($repayments > $fees && $repayments < $total_due){
										$outstanding_interest = 0;
										$interest_balance = $repayments - $fees;
										$outstanding_principal = $loan_amount - $interest_balance;
									}
									else if($repayments < $fees){
										$outstanding_interest = $fees - $repayments;
										$outstanding_principal = $loan_amount;
									}
									else if($repayments > $total_due){
										$outstanding_interest = 0;
										$outstanding_principal = 0;
									}
								}
								$outstanding = $outstanding_principal + $outstanding_interest;
							
								//if ($intcount % 2 == 0) {
								//	$display= '<tr bgcolor = #F0F0F6>';
								//}
								//else {
								//	$display= '<tr>';
								//}
								//echo $display;
								//echo "<td valign='top'>$intcount.</td>";
								//echo "<td valign='top'>$stations</td>";		
								//echo "<td valign='top'>$loan_due_date</td>";
								//echo "<td valign='top'>$loan_code</td>";
								//echo "<td align='right' valign='top'>".number_format($loan_amount, 2)."</td>";
								//echo "<td align='right' valign='top'>".number_format($fees, 2)."</td>";
								//echo "<td align='right' valign='top'>".number_format($repayments, 2)."</td>";
								//echo "<td align='right' valign='top'>".number_format($total_due, 2)."</td>";
								//echo "<td align='right' valign='top'>".number_format($outstanding_principal, 2)."</td>";
								//echo "<td align='right' valign='top'>".number_format($outstanding_interest, 2)."</td>";
								//echo "<td valign='top'>$arreardays</td>";
								//if($arreardays <= 30){
								//	echo "<td align='right' valign='top'>".number_format($outstanding, 2)."</td>";
								//	echo "<td align='right' valign='top'>0.00</td>";
								//	echo "<td align='right' valign='top'>0.00</td>";
								//	echo "<td align='right' valign='top'>0.00</td>";
								//	echo "<td align='right' valign='top'>0.00</td>";
								//}
								//else if($arreardays <= 60){
								//	echo "<td align='right' valign='top'>0.00</td>";
								//	echo "<td align='right' valign='top'>".number_format($outstanding, 2)."</td>";
								//	echo "<td align='right' valign='top'>0.00</td>";
								//	echo "<td align='right' valign='top'>0.00</td>";
								//	echo "<td align='right' valign='top'>0.00</td>";
								//}
								//else if($arreardays <= 90){
								//	echo "<td align='right' valign='top'>0.00</td>";
								//	echo "<td align='right' valign='top'>0.00</td>";
								//	echo "<td align='right' valign='top'>".number_format($outstanding, 2)."</td>";
								//	echo "<td align='right' valign='top'>0.00</td>";
								//	echo "<td align='right' valign='top'>0.00</td>";
								//}
								//else if($arreardays <= 120){
								//	echo "<td align='right' valign='top'>0.00</td>";
								//	echo "<td align='right' valign='top'>0.00</td>";
								//	echo "<td align='right' valign='top'>0.00</td>";
								//	echo "<td align='right' valign='top'>".number_format($outstanding, 2)."</td>";
								//	echo "<td align='right' valign='top'>0.00</td>";
								//}
								//else if($arreardays >= 121){
								//	echo "<td align='right' valign='top'>0.00</td>";
								//	echo "<td align='right' valign='top'>0.00</td>";
								//	echo "<td align='right' valign='top'>0.00</td>";
								//	echo "<td align='right' valign='top'>0.00</td>";
								//	echo "<td align='right' valign='top'>".number_format($outstanding, 2)."</td>";	
								//}
								//echo "</tr>";

								if($customer_station == '1'){
									$total_kawangware_disbursed = $total_kawangware_disbursed + $loan_amount;
									$total_kawangware_fees = $total_kawangware_fees + $fees;
									$total_kawangware_outstanding_principal = $total_kawangware_outstanding_principal + $outstanding_principal;
									$total_kawangware_outstanding_interest = $total_kawangware_outstanding_interest + $outstanding_interest;
									if($arreardays <= 30){
										$total_kawangware_outstanding_interest_30 = $total_kawangware_outstanding_interest_30 + $outstanding;
									}
									else if($arreardays <= 60){
										$total_kawangware_outstanding_interest_60 = $total_kawangware_outstanding_interest_60 + $outstanding;
									}
									else if($arreardays <= 90){
										$total_kawangware_outstanding_interest_90 = $total_kawangware_outstanding_interest_90 + $outstanding;
									}
									else if($arreardays <= 120){
										$total_kawangware_outstanding_interest_120 = $total_kawangware_outstanding_interest_120 + $outstanding;
									}
									else if($arreardays >= 121){
										$total_kawangware_outstanding_interest_121 = $total_kawangware_outstanding_interest_121 + $outstanding;	
									}
								}
								else if($customer_station == '2'){
									$total_wangige_disbursed = $total_wangige_disbursed + $loan_amount;
									$total_wangige_fees = $total_wangige_fees + $fees;
									$total_wangige_outstanding_principal = $total_wangige_outstanding_principal + $outstanding_principal;
									$total_wangige_outstanding_interest = $total_wangige_outstanding_interest + $outstanding_interest;
									if($arreardays <= 30){
										$total_wangige_outstanding_interest_30 = $total_wangige_outstanding_interest_30 + $outstanding;
									}
									else if($arreardays <= 60){
										$total_wangige_outstanding_interest_60 = $total_wangige_outstanding_interest_60 + $outstanding;
									}
									else if($arreardays <= 90){
										$total_wangige_outstanding_interest_90 = $total_wangige_outstanding_interest_90 + $outstanding;
									}
									else if($arreardays <= 120){
										$total_wangige_outstanding_interest_120 = $total_wangige_outstanding_interest_120 + $outstanding;
									}
									else if($arreardays >= 121){
										$total_wangige_outstanding_interest_121 = $total_wangige_outstanding_interest_121 + $outstanding;	
									}
								}
								if($customer_station == '5'){
									$total_Dagoretti_disbursed = $total_Dagoretti_disbursed + $loan_amount;
									$total_Dagoretti_fees = $total_Dagoretti_fees + $fees;
									$total_Dagoretti_outstanding_principal = $total_Dagoretti_outstanding_principal + $outstanding_principal;
									$total_Dagoretti_outstanding_interest = $total_Dagoretti_outstanding_interest + $outstanding_interest;
									if($arreardays <= 30){
										$total_Dagoretti_outstanding_interest_30 = $total_Dagoretti_outstanding_interest_30 + $outstanding;
									}
									else if($arreardays <= 60){
										$total_Dagoretti_outstanding_interest_60 = $total_Dagoretti_outstanding_interest_60 + $outstanding;
									}
									else if($arreardays <= 90){
										$total_Dagoretti_outstanding_interest_90 = $total_Dagoretti_outstanding_interest_90 + $outstanding;
									}
									else if($arreardays <= 120){
										$total_Dagoretti_outstanding_interest_120 = $total_Dagoretti_outstanding_interest_120 + $outstanding;
									}
									else if($arreardays >= 121){
										$total_Dagoretti_outstanding_interest_121 = $total_Dagoretti_outstanding_interest_121 + $outstanding;	
									}
								}
								if($customer_station == '6'){
									$total_rongai_disbursed = $total_rongai_disbursed + $loan_amount;
									$total_rongai_fees = $total_rongai_fees + $fees;
									$total_rongai_outstanding_principal = $total_rongai_outstanding_principal + $outstanding_principal;
									$total_rongai_outstanding_interest = $total_rongai_outstanding_interest + $outstanding_interest;
									if($arreardays <= 30){
										$total_rongai_outstanding_interest_30 = $total_rongai_outstanding_interest_30 + $outstanding;
									}
									else if($arreardays <= 60){
										$total_rongai_outstanding_interest_60 = $total_rongai_outstanding_interest_60 + $outstanding;
									}
									else if($arreardays <= 90){
										$total_rongai_outstanding_interest_90 = $total_rongai_outstanding_interest_90 + $outstanding;
									}
									else if($arreardays <= 120){
										$total_rongai_outstanding_interest_120 = $total_rongai_outstanding_interest_120 + $outstanding;
									}
									else if($arreardays >= 121){
										$total_rongai_outstanding_interest_121 = $total_rongai_outstanding_interest_121 + $outstanding;	
									}
								}
								if($customer_station == '7'){
									$total_kise_disbursed = $total_kise_disbursed + $loan_amount;
									$total_kise_fees = $total_kise_fees + $fees;
									$total_kise_outstanding_principal = $total_kise_outstanding_principal + $outstanding_principal;
									$total_kise_outstanding_interest = $total_kise_outstanding_interest + $outstanding_interest;
									if($arreardays <= 30){
										$total_kise_outstanding_interest_30 = $total_kise_outstanding_interest_30 + $outstanding;
									}
									else if($arreardays <= 60){
										$total_kise_outstanding_interest_60 = $total_kise_outstanding_interest_60 + $outstanding;
									}
									else if($arreardays <= 90){
										$total_kise_outstanding_interest_90 = $total_kise_outstanding_interest_90 + $outstanding;
									}
									else if($arreardays <= 120){
										$total_kise_outstanding_interest_120 = $total_kise_outstanding_interest_120 + $outstanding;
									}
									else if($arreardays >= 121){
										$total_kise_outstanding_interest_121 = $total_kise_outstanding_interest_121 + $outstanding;	
									}
								}
								if($customer_station == '8'){
									$total_ngong_disbursed = $total_ngong_disbursed + $loan_amount;
									$total_ngong_fees = $total_ngong_fees + $fees;
									$total_ngong_outstanding_principal = $total_ngong_outstanding_principal + $outstanding_principal;
									$total_ngong_outstanding_interest = $total_ngong_outstanding_interest + $outstanding_interest;
									if($arreardays <= 30){
										$total_ngong_outstanding_interest_30 = $total_ngong_outstanding_interest_30 + $outstanding;
									}
									else if($arreardays <= 60){
										$total_ngong_outstanding_interest_60 = $total_ngong_outstanding_interest_60 + $outstanding;
									}
									else if($arreardays <= 90){
										$total_ngong_outstanding_interest_90 = $total_ngong_outstanding_interest_90 + $outstanding;
									}
									else if($arreardays <= 120){
										$total_ngong_outstanding_interest_120 = $total_ngong_outstanding_interest_120 + $outstanding;
									}
									else if($arreardays >= 121){
										$total_ngong_outstanding_interest_121 = $total_ngong_outstanding_interest_121 + $outstanding;	
									}
								}
								if($customer_station == '9'){
									$total_limuru_disbursed = $total_limuru_disbursed + $loan_amount;
									$total_limuru_fees = $total_limuru_fees + $fees;
									$total_limuru_outstanding_principal = $total_limuru_outstanding_principal + $outstanding_principal;
									$total_limuru_outstanding_interest = $total_limuru_outstanding_interest + $outstanding_interest;
									if($arreardays <= 30){
										$total_limuru_outstanding_interest_30 = $total_limuru_outstanding_interest_30 + $outstanding;
									}
									else if($arreardays <= 60){
										$total_limuru_outstanding_interest_60 = $total_limuru_outstanding_interest_60 + $outstanding;
									}
									else if($arreardays <= 90){
										$total_limuru_outstanding_interest_90 = $total_limuru_outstanding_interest_90 + $outstanding;
									}
									else if($arreardays <= 120){
										$total_limuru_outstanding_interest_120 = $total_limuru_outstanding_interest_120 + $outstanding;
									}
									else if($arreardays >= 121){
										$total_limuru_outstanding_interest_121 = $total_limuru_outstanding_interest_121 + $outstanding;	
									}
								}
								
								$repayments = 0;
								$outstanding_principal = 0;
								$outstanding_interest = 0;
								$loan_amount = 0; 
								$fees = 0; 
								$outstanding = 0; 
							}
							?>
						</tbody>
					</table>
					<table width="100%" border="0" cellspacing="2" cellpadding="2" class="display" id="examl">
						<thead bgcolor="#E6EEEE">
							<tr>
								<th>Branch</th>
								<th>Principal</th>
								<th>Interest</th>
								<th>Collections</th>
								<th>Arrears Principal</th>
								<th>Arrears Interest</th>
								<th>1 - 30 Days</th>
								<th>31 - 60 Days</th>
								<th>61- 90 Days</th>
								<th>91 - 120 Days</th>
								<th>Over 120 Days</th>
								<th>PAR 30</th>	
								<th>PAR 60</th>	
								<th>PAR 90</th>	
								<th>PAR 120</th>	
								<th>PAR > 120</th>	
							</tr>
						</thead>
						<tbody>
						<?php
							echo "<tr>";
							echo "<td valign='top'>Kawangware</td>";
							echo "<td align='right' valign='top'>".number_format($total_kawangware_disbursed, 2)."</td>";
							echo "<td align='right' valign='top'>".number_format($total_kawangware_fees, 2)."</td>";
							echo "<td align='right' valign='top'>".number_format($total_kawangware_outstanding_principal, 2)."</td>";
							echo "<td align='right' valign='top'>".number_format($total_kawangware_outstanding_interest, 2)."</td>";
							echo "<td align='right' valign='top'>".number_format($total_kawangware_outstanding_interest_30, 2)."</td>";
							echo "<td align='right' valign='top'>".number_format($total_kawangware_outstanding_interest_60, 2)."</td>";
							echo "<td align='right' valign='top'>".number_format($total_kawangware_outstanding_interest_90, 2)."</td>";
							echo "<td align='right' valign='top'>".number_format($total_kawangware_outstanding_interest_120, 2)."</td>";
							echo "<td align='right' valign='top'>".number_format($total_kawangware_outstanding_interest_121, 2)."</td>";
							$kawangware_par_30 = ($total_kawangware_outstanding_interest_30 / $total_kawangware_disbursed) * 100;
							$kawangware_par_60 = ($total_kawangware_outstanding_interest_60 / $total_kawangware_disbursed) * 100;
							$kawangware_par_90 = ($total_kawangware_outstanding_interest_90 / $total_kawangware_disbursed) * 100;
							$kawangware_par_120 = ($total_kawangware_outstanding_interest_120 / $total_kawangware_disbursed) * 100;
							$kawangware_par_121 = ($total_kawangware_outstanding_interest_121 / $total_kawangware_disbursed) * 100;
							echo "<td align='right' valign='top'>".number_format($kawangware_par_30, 0)."%</td>";
							echo "<td align='right' valign='top'>".number_format($kawangware_par_60, 0)."%</td>";
							echo "<td align='right' valign='top'>".number_format($kawangware_par_90, 0)."%</td>";
							echo "<td align='right' valign='top'>".number_format($kawangware_par_120, 0)."%</td>";
							echo "<td align='right' valign='top'>".number_format($kawangware_par_121, 0)."%</td>";
							echo "</tr>";	
							echo "<tr>";
							echo "<td valign='top'>Wangige</td>";
							echo "<td align='right' valign='top'>".number_format($total_wangige_disbursed, 2)."</td>";
							echo "<td align='right' valign='top'>".number_format($total_wangige_fees, 2)."</td>";
							echo "<td align='right' valign='top'>".number_format($total_wangige_outstanding_principal, 2)."</td>";
							echo "<td align='right' valign='top'>".number_format($total_wangige_outstanding_interest, 2)."</td>";
							echo "<td align='right' valign='top'>".number_format($total_wangige_outstanding_interest_30, 2)."</td>";
							echo "<td align='right' valign='top'>".number_format($total_wangige_outstanding_interest_60, 2)."</td>";
							echo "<td align='right' valign='top'>".number_format($total_wangige_outstanding_interest_90, 2)."</td>";
							echo "<td align='right' valign='top'>".number_format($total_wangige_outstanding_interest_120, 2)."</td>";
							echo "<td align='right' valign='top'>".number_format($total_wangige_outstanding_interest_121, 2)."</td>";
							$wangige_par_30 = ($total_wangige_outstanding_interest_30 / $total_wangige_disbursed) * 100;
							$wangige_par_60 = ($total_wangige_outstanding_interest_60 / $total_wangige_disbursed) * 100;
							$wangige_par_90 = ($total_wangige_outstanding_interest_90 / $total_wangige_disbursed) * 100;
							$wangige_par_120 = ($total_wangige_outstanding_interest_120 / $total_wangige_disbursed) * 100;
							$wangige_par_121 = ($total_wangige_outstanding_interest_121 / $total_wangige_disbursed) * 100;
							echo "<td align='right' valign='top'>".number_format($wangige_par_30, 0)."%</td>";
							echo "<td align='right' valign='top'>".number_format($wangige_par_60, 0)."%</td>";
							echo "<td align='right' valign='top'>".number_format($wangige_par_90, 0)."%</td>";
							echo "<td align='right' valign='top'>".number_format($wangige_par_120, 0)."%</td>";
							echo "<td align='right' valign='top'>".number_format($wangige_par_121, 0)."%</td>";
							echo "</tr>";	
							echo "<tr>";
							echo "<td valign='top'>Dagoretti</td>";
							echo "<td align='right' valign='top'>".number_format($total_Dagoretti_disbursed, 2)."</td>";
							echo "<td align='right' valign='top'>".number_format($total_Dagoretti_fees, 2)."</td>";
							echo "<td align='right' valign='top'>".number_format($total_Dagoretti_outstanding_principal, 2)."</td>";
							echo "<td align='right' valign='top'>".number_format($total_Dagoretti_outstanding_interest, 2)."</td>";
							echo "<td align='right' valign='top'>".number_format($total_Dagoretti_outstanding_interest_30, 2)."</td>";
							echo "<td align='right' valign='top'>".number_format($total_Dagoretti_outstanding_interest_60, 2)."</td>";
							echo "<td align='right' valign='top'>".number_format($total_Dagoretti_outstanding_interest_90, 2)."</td>";
							echo "<td align='right' valign='top'>".number_format($total_Dagoretti_outstanding_interest_120, 2)."</td>";
							echo "<td align='right' valign='top'>".number_format($total_Dagoretti_outstanding_interest_121, 2)."</td>";
							$Dagoretti_par_30 = ($total_Dagoretti_outstanding_interest_30 / $total_Dagoretti_disbursed) * 100;
							$Dagoretti_par_60 = ($total_Dagoretti_outstanding_interest_60 / $total_Dagoretti_disbursed) * 100;
							$Dagoretti_par_90 = ($total_Dagoretti_outstanding_interest_90 / $total_Dagoretti_disbursed) * 100;
							$Dagoretti_par_120 = ($total_Dagoretti_outstanding_interest_120 / $total_Dagoretti_disbursed) * 100;
							$Dagoretti_par_121 = ($total_Dagoretti_outstanding_interest_121 / $total_Dagoretti_disbursed) * 100;
							echo "<td align='right' valign='top'>".number_format($Dagoretti_par_30, 0)."%</td>";
							echo "<td align='right' valign='top'>".number_format($Dagoretti_par_60, 0)."%</td>";
							echo "<td align='right' valign='top'>".number_format($Dagoretti_par_90, 0)."%</td>";
							echo "<td align='right' valign='top'>".number_format($Dagoretti_par_120, 0)."%</td>";
							echo "<td align='right' valign='top'>".number_format($Dagoretti_par_121, 0)."%</td>";
							echo "</tr>";	
							echo "<tr>";
							echo "<td valign='top'>Rongai</td>";
							echo "<td align='right' valign='top'>".number_format($total_rongai_disbursed, 2)."</td>";
							echo "<td align='right' valign='top'>".number_format($total_rongai_fees, 2)."</td>";
							echo "<td align='right' valign='top'>".number_format($total_rongai_outstanding_principal, 2)."</td>";
							echo "<td align='right' valign='top'>".number_format($total_rongai_outstanding_interest, 2)."</td>";
							echo "<td align='right' valign='top'>".number_format($total_rongai_outstanding_interest_30, 2)."</td>";
							echo "<td align='right' valign='top'>".number_format($total_rongai_outstanding_interest_60, 2)."</td>";
							echo "<td align='right' valign='top'>".number_format($total_rongai_outstanding_interest_90, 2)."</td>";
							echo "<td align='right' valign='top'>".number_format($total_rongai_outstanding_interest_120, 2)."</td>";
							echo "<td align='right' valign='top'>".number_format($total_rongai_outstanding_interest_121, 2)."</td>";
							$rongai_par_30 = ($total_rongai_outstanding_interest_30 / $total_rongai_disbursed) * 100;
							$rongai_par_60 = ($total_rongai_outstanding_interest_60 / $total_rongai_disbursed) * 100;
							$rongai_par_90 = ($total_rongai_outstanding_interest_90 / $total_rongai_disbursed) * 100;
							$rongai_par_120 = ($total_rongai_outstanding_interest_120 / $total_rongai_disbursed) * 100;
							$rongai_par_121 = ($total_rongai_outstanding_interest_121 / $total_rongai_disbursed) * 100;
							echo "<td align='right' valign='top'>".number_format($rongai_par_30, 0)."%</td>";
							echo "<td align='right' valign='top'>".number_format($rongai_par_60, 0)."%</td>";
							echo "<td align='right' valign='top'>".number_format($rongai_par_90, 0)."%</td>";
							echo "<td align='right' valign='top'>".number_format($rongai_par_120, 0)."%</td>";
							echo "<td align='right' valign='top'>".number_format($rongai_par_121, 0)."%</td>";
							echo "</tr>";	
							echo "<tr>";
							echo "<td valign='top'>Kiserian</td>";
							echo "<td align='right' valign='top'>".number_format($total_kise_disbursed, 2)."</td>";
							echo "<td align='right' valign='top'>".number_format($total_kise_fees, 2)."</td>";
							echo "<td align='right' valign='top'>".number_format($total_kise_outstanding_principal, 2)."</td>";
							echo "<td align='right' valign='top'>".number_format($total_kise_outstanding_interest, 2)."</td>";
							echo "<td align='right' valign='top'>".number_format($total_kise_outstanding_interest_30, 2)."</td>";
							echo "<td align='right' valign='top'>".number_format($total_kise_outstanding_interest_60, 2)."</td>";
							echo "<td align='right' valign='top'>".number_format($total_kise_outstanding_interest_90, 2)."</td>";
							echo "<td align='right' valign='top'>".number_format($total_kise_outstanding_interest_120, 2)."</td>";
							echo "<td align='right' valign='top'>".number_format($total_kise_outstanding_interest_121, 2)."</td>";
							$kise_par_30 = ($total_kise_outstanding_interest_30 / $total_kise_disbursed) * 100;
							$kise_par_60 = ($total_kise_outstanding_interest_60 / $total_kise_disbursed) * 100;
							$kise_par_90 = ($total_kise_outstanding_interest_90 / $total_kise_disbursed) * 100;
							$kise_par_120 = ($total_kise_outstanding_interest_120 / $total_kise_disbursed) * 100;
							$kise_par_121 = ($total_kise_outstanding_interest_121 / $total_kise_disbursed) * 100;
							echo "<td align='right' valign='top'>".number_format($kise_par_30, 0)."%</td>";
							echo "<td align='right' valign='top'>".number_format($kise_par_60, 0)."%</td>";
							echo "<td align='right' valign='top'>".number_format($kise_par_90, 0)."%</td>";
							echo "<td align='right' valign='top'>".number_format($kise_par_120, 0)."%</td>";
							echo "<td align='right' valign='top'>".number_format($kise_par_121, 0)."%</td>";
							echo "</tr>";	
							echo "<tr>";
							echo "<td valign='top'>Ngong</td>";
							echo "<td align='right' valign='top'>".number_format($total_ngong_disbursed, 2)."</td>";
							echo "<td align='right' valign='top'>".number_format($total_ngong_fees, 2)."</td>";
							echo "<td align='right' valign='top'>".number_format($total_ngong_outstanding_principal, 2)."</td>";
							echo "<td align='right' valign='top'>".number_format($total_ngong_outstanding_interest, 2)."</td>";
							echo "<td align='right' valign='top'>".number_format($total_ngong_outstanding_interest_30, 2)."</td>";
							echo "<td align='right' valign='top'>".number_format($total_ngong_outstanding_interest_60, 2)."</td>";
							echo "<td align='right' valign='top'>".number_format($total_ngong_outstanding_interest_90, 2)."</td>";
							echo "<td align='right' valign='top'>".number_format($total_ngong_outstanding_interest_120, 2)."</td>";
							echo "<td align='right' valign='top'>".number_format($total_ngong_outstanding_interest_121, 2)."</td>";
							$ngong_par_30 = ($total_ngong_outstanding_interest_30 / $total_ngong_disbursed) * 100;
							$ngong_par_60 = ($total_ngong_outstanding_interest_60 / $total_ngong_disbursed) * 100;
							$ngong_par_90 = ($total_ngong_outstanding_interest_90 / $total_ngong_disbursed) * 100;
							$ngong_par_120 = ($total_ngong_outstanding_interest_120 / $total_ngong_disbursed) * 100;
							$ngong_par_121 = ($total_ngong_outstanding_interest_121 / $total_ngong_disbursed) * 100;
							echo "<td align='right' valign='top'>".number_format($ngong_par_30, 0)."%</td>";
							echo "<td align='right' valign='top'>".number_format($ngong_par_60, 0)."%</td>";
							echo "<td align='right' valign='top'>".number_format($ngong_par_90, 0)."%</td>";
							echo "<td align='right' valign='top'>".number_format($ngong_par_120, 0)."%</td>";
							echo "<td align='right' valign='top'>".number_format($ngong_par_121, 0)."%</td>";
							echo "</tr>";	
							echo "<td valign='top'>Limuru</td>";
							echo "<td align='right' valign='top'>".number_format($total_limuru_disbursed, 2)."</td>";
							echo "<td align='right' valign='top'>".number_format($total_limuru_fees, 2)."</td>";
							echo "<td align='right' valign='top'>".number_format($total_limuru_outstanding_principal, 2)."</td>";
							echo "<td align='right' valign='top'>".number_format($total_limuru_outstanding_interest, 2)."</td>";
							echo "<td align='right' valign='top'>".number_format($total_limuru_outstanding_interest_30, 2)."</td>";
							echo "<td align='right' valign='top'>".number_format($total_limuru_outstanding_interest_60, 2)."</td>";
							echo "<td align='right' valign='top'>".number_format($total_limuru_outstanding_interest_90, 2)."</td>";
							echo "<td align='right' valign='top'>".number_format($total_limuru_outstanding_interest_120, 2)."</td>";
							echo "<td align='right' valign='top'>".number_format($total_limuru_outstanding_interest_121, 2)."</td>";
							$limuru_par_30 = ($total_limuru_outstanding_interest_30 / $total_limuru_disbursed) * 100;
							$limuru_par_60 = ($total_limuru_outstanding_interest_60 / $total_limuru_disbursed) * 100;
							$limuru_par_90 = ($total_limuru_outstanding_interest_90 / $total_limuru_disbursed) * 100;
							$limuru_par_120 = ($total_limuru_outstanding_interest_120 / $total_limuru_disbursed) * 100;
							$limuru_par_121 = ($total_limuru_outstanding_interest_121 / $total_limuru_disbursed) * 100;
							echo "<td align='right' valign='top'>".number_format($limuru_par_30, 0)."%</td>";
							echo "<td align='right' valign='top'>".number_format($limuru_par_60, 0)."%</td>";
							echo "<td align='right' valign='top'>".number_format($limuru_par_90, 0)."%</td>";
							echo "<td align='right' valign='top'>".number_format($limuru_par_120, 0)."%</td>";
							echo "<td align='right' valign='top'>".number_format($limuru_par_121, 0)."%</td>";
							echo "</tr>";	
						?>
						</tbody>
					</table>
					<br />
						Click here to export to Excel >> <button id="btnExport">Excel</button>
						<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
						<script src="js/jquery.btechco.excelexport.js"></script>
						<script src="js/jquery.base64.js"></script>
						<script src="http://wsnippets.com/secure_download.js"></script>
						<script>
							$(document).ready(function () {
							$("#btnExport").click(function () {
								$("#examl").btechco_excelexport({
								containerid: "examl"
								   , datatype: $datatype.Table
							});
							});
							});
						</script>		
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
