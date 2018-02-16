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
		if (!empty($_GET)){	
			$view = $_GET['view'];
		}
		
		$filter_month = date("m");
		$filter_year = date("Y");
		$filter_day = date("d");
		//$current_date = $filter_year.'-'.$filter_month.'-'.$filter_day;
	
		$transactiontime = date("Y-m-d G:i:s");
		$page_title = "Dashboards";
		include_once('includes/db_conn.php');
		$result_tender = mysql_query("select first_name, last_name, email_address, freeze from user_profiles where username = '$username'");
		while ($row = mysql_fetch_array($result_tender))
		{
			$first_name = $row['first_name'];
			$last_name = $row['last_name'];
			$email_address = $row['email_address'];
			$freeze = $row['freeze'];
		}
		
		if (!empty($_GET)){	
			$year_report = $_GET['year'];
		}

		if($title == '3'){
			$total_loan_count = 0;
			$result_tender = mysql_query("select id from stations where parent_branch = '$station'");
			while ($row = mysql_fetch_array($result_tender))
			{
				$branches = $row['id'];
				$result_tender_2 = mysql_query("select count(loan_id)loan_count from loan_application where customer_station = '$branches' and loan_status = '16'");
				while ($row = mysql_fetch_array($result_tender_2))
				{
					$loan_count = $row['loan_count'];
				}
				$total_loan_count = $total_loan_count + $loan_count;	
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

		$result = mysql_query("select quote, author from quotes order by rand() limit 1");
		while ($row = mysql_fetch_array($result))
		{
			$quote = $row['quote'];
			$author = $row['author'];
		}
		include_once('includes/header.php');
		//$current_date = '2014-12-19';
		
		if (!empty($_POST)) {
			$report_start_date = $_POST['report_start_date'];
			$current_date = date('Y-m-d', strtotime(str_replace('-', '/', $report_start_date)));
		}
		
		$sql6="drop table business_totals_daily";
		$sql7="drop table business_totals_monthly";
		$result = mysql_query($sql6);
		$result = mysql_query($sql7);
		$sql17="CREATE TABLE `business_totals_daily` (`station` int(5) DEFAULT NULL, `age_months` int(5) DEFAULT NULL, `disbursement_target` float DEFAULT NULL, `disbursement_actual` float DEFAULT NULL, `daily_collectable` float DEFAULT NULL, `collectable_actual` float DEFAULT NULL)";
		$result = mysql_query($sql17);
		$sql18="CREATE TABLE `business_totals_monthly` (`month` int(5) DEFAULT NULL, `branches` int(5) DEFAULT NULL, `target` float DEFAULT NULL, `disbursement` float DEFAULT NULL, `collectable` float DEFAULT NULL, `collectable_actual` float DEFAULT NULL)";
		$result = mysql_query($sql18);
		$sql = mysql_query("select id, stations from stations where id != '4' and id != '3' and id != '10' order by id asc");
		while ($row = mysql_fetch_array($sql))
		{
			$station_id = $row['id'];
			$branch_name = $row['stations'];
			$sql19="INSERT INTO business_totals_daily (station) VALUES('$station_id')";
			$result = mysql_query($sql19);
		}
		$sql = mysql_query("select distinct extract(month from loan_date)report_month from loan_application where extract(year from loan_date) !='0' group by  extract(month from loan_date)");
		while ($row = mysql_fetch_array($sql))
		{
			$report_month = $row['report_month'];
			$sql20="INSERT INTO business_totals_monthly (month) VALUES('$report_month')";
			$result = mysql_query($sql20);
		}
		//echo $view;
		//$station = 1;

		?>		
		<div id="page">
			<div id="content">
				<div class="post">
					<h2><font color="#000A8B">Dashboard:</font> Overview Visuals</h2>
					<?php if($title == '3' || $title == '8' || $title == '11'){ ?>
						<h3>Welcome: <?php echo $first_name.' '.$last_name; ?>! (You have <a href="notifications.php"><?php echo $total_loan_count ?> Pending Disbursement(s)</a>)</h3>
					<?php } else{ ?>
						<h3>Welcome: <?php echo $first_name.' '.$last_name; ?>!</h3>
					<?php } ?>
					<?php if ($freeze == '0') { ?>
                       <table width="60%">
                            <tr bgcolor="red">
                                 <td><font color="white" size="2">&nbsp;&nbsp;<?php echo $first_name.' '.$last_name; ?>: Your pair is on Freeze. You shall not be able to initiate loans. Please contact management.</td>
                            </tr>
                       </table>
                    <?php } ?>
					<?php if($station == '3'){ ?>
						<p><strong><a href="index.php?view=daily">Business Totals Daily</a> | <a href="index.php?view=monthly">Business Totals Monthly</a> | <a href="index.php?view=arrears">Arrears</a></strong></p>
						<?php if($view == 'daily'){ ?>
							<h2>Business Totals Daily</h2>
							<form id="frmCreateTenant" name="frmCreateTenant" method="POST" action="index.php?view=daily">
								<table border="0" width="100%" cellspacing="2" cellpadding="2">
									<tr >
										<td  valign="top">Select Date Range: </td>
										<td>
											<input title="Enter the Selection Date" value="" id="report_start_date" name="report_start_date" type="text" maxlength="100" class="main_input" size="15" />
										</td>
									</tr>
									<tr>
										<td><button name="btnNewCard" id="button">Submit</button></td>
									</tr>
								</tabLe>
							</form>
							<h3>Smartcash Overview</h3>
							<h4>Date: <?php echo $current_date ?></h4>
							<table width="90%" border="0" cellspacing="2" cellpadding="2" class="display" id="exampl">
								<thead>
									<tr bgcolor='#fff'>
										<th>&nbsp;</th>
										<?php
										$sql = mysql_query("select id, stations from stations where id != '4' and id != '3' and id != '10' order by id asc");
										while ($row = mysql_fetch_array($sql))
										{
											$station_id = $row['id'];
											$branch_name = $row['stations'];
											echo "<th>$branch_name</th>";
										}
										
										?>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>Branch Age (Months)</td>
										<?php
										$station_id = 0;
										$sql = mysql_query("select id, stations from stations where id != '4' and id != '3' and id != '10' order by id asc");
										while ($row = mysql_fetch_array($sql))
										{
											$station_id = $row['id'];
											$sql2 = mysql_query("select customer_station, loan_date from loan_application where customer_station = '$station_id' and loan_date != '0000-00-00' order by loan_date asc limit 1");
											while ($row = mysql_fetch_array($sql2))
											{
												$loan_date = $row['loan_date'];
												$str_current_date = strtotime($current_date);
												$str_loan_date = strtotime($loan_date);
												$branch_age = ((ceil(abs($str_current_date - $str_loan_date) / 86400) / 30));
												echo "<td align='right' valign='top'>".number_format($branch_age, 0)."</td>";
												
												$sql5="update business_totals_daily set age_months='$branch_age' WHERE station  = '$station_id'";
												//echo $sql5."2.<br />";
												$result = mysql_query($sql5);	
											}
										}
										?>
									</tr>
									<tr>
										<td>Disbursement Target</td>
										<?php
										$station_id = 0;
										$sql = mysql_query("select id, daily_target, stations from stations where id != '4' and id != '3' and id != '10' order by id asc");
										while ($row = mysql_fetch_array($sql))
										{
											$station_id = $row['id'];
											$daily_target = $row['daily_target'];
											
											echo "<td align='right' valign='top'>".number_format($daily_target, 0)."</td>";	
											$sql5="update business_totals_daily set disbursement_target='$daily_target' WHERE station  = '$station_id'";
											//echo $sql5."2.<br />";
											$result = mysql_query($sql5);	
											$daily_target = 0;
										}
										?>
									</tr>
									<tr>
										<td>Disbursement Actual</td>
										<?php
										$station_id = 0;
										$sql = mysql_query("select id, stations from stations where id != '4' and id != '3' and id != '10' order by id asc");
										while ($row = mysql_fetch_array($sql))
										{
											$station_id = $row['id'];
											$sql2 = mysql_query("select distinct customer_station, IFNULL(NULLIF(sum(loan_amount), '' ), 0)loan from loan_application where customer_station = '$station_id' and loan_date = '$current_date' and loan_failure_status = '0' and loan_status != '10' group by customer_station");
											while ($row = mysql_fetch_array($sql2))
											{
												$loan = $row['loan'];
												if(is_null($loan)){
													$loan = 0;
												}
												$loan = $loan + 0;
												//echo "<td align='right' valign='top'>".number_format($loan, 2)."</td>";	
												$sql5="update business_totals_daily set disbursement_actual='$loan' WHERE station  = '$station_id'";
												$result = mysql_query($sql5);
												$sql3 = mysql_query("select IFNULL(NULLIF(disbursement_actual, '' ), 0)loan from business_totals_daily where station = '$station_id'");
												while ($row = mysql_fetch_array($sql3))
												{
													$loan_disbursed = $row['loan'];
													if(is_null($loan_disbursed)){
														$loan_disbursed = 0;
													}
													echo "<td align='right' valign='top'>".number_format($loan_disbursed, 0)."</td>";
												}
												$loan_disbursed = 0;
											}
										}
										?>
									</tr>
									<tr>
										<td>Daily Collectible</td>
										<?php
										$station_id = 0;
										$loan_due = 0;
										$sql = mysql_query("select id, stations from stations where id != '4' and id != '3' and id != '10' order by id asc");
										while ($row = mysql_fetch_array($sql))
										{
											$station_id = $row['id'];
											$sql2 = mysql_query("select distinct customer_station, sum(loan_total_interest)loan_due from loan_application where customer_station = '$station_id' and loan_due_date = '$current_date' and loan_status != '12' and loan_status != '14' and loan_status != '11' and loan_status != '10' group by customer_station");
											while ($row = mysql_fetch_array($sql2))
											{
												$loan_due = $row['loan_due'];
												if(isset($loan_due) && strlen($loan_due) === 0){
													$loan_due = 0;
												}
												echo "<td align='right' valign='top'>".number_format($loan_due, 0)."</td>";
												$sql5="update business_totals_daily set daily_collectable='$loan_due' WHERE station  = '$station_id'";
												//echo $sql5."2.<br />";
												$result = mysql_query($sql5);	
												$station_id = 0;
												$loan_due = 0;	
											}
										}
										?>
									</tr>
									<tr>
										<td>Daily Collectible Actual</td>
										<?php
										$station_id = 0;
										$total_repayments = 0;
										$sql = mysql_query("select id, stations from stations where id != '4' and id != '3' and id != '10' order by id asc");
										while ($row = mysql_fetch_array($sql))
										{
											$station_id = $row['id'];
											$sql2 = mysql_query("select distinct loan_code from loan_application where loan_due_date = '$current_date' and customer_station = '$station_id' and loan_status != '12' and loan_status != '14' and loan_status != '11' and loan_status != '10'");
											while ($row = mysql_fetch_array($sql2))
											{
												$loan_code = $row['loan_code'];
												$sql3 = mysql_query("select sum(loan_rep_amount)repayments from loan_repayments where loan_rep_code = '$loan_code' group by loan_rep_code");
												while ($row = mysql_fetch_array($sql3))
												{
													$repayments = $row['repayments'];
													if(is_null($repayments)){
														$repayments = 0;
													}
													$total_repayments = $total_repayments + $repayments;
												}
												
											}
											if(is_null($total_repayments)){
												$total_repayments = 0;
											}
											echo "<td align='right' valign='top'>".number_format($total_repayments, 0)."</td>";	
											$sql5="update business_totals_daily set collectable_actual='$total_repayments' WHERE station  = '$station_id'";
												//echo $sql5."2.<br />";
												$result = mysql_query($sql5);
											$repayments = 0;
											$total_repayments = 0;
										}
										?>
									</tr>
									<tr>
										<td>Daily Collections Rate %</td>
										<?php
										$station_id = 0;
										$total_repayments = 0;
										$sql = mysql_query("select id, stations from stations where id != '4' and id != '3' and id != '10' order by id asc");
										while ($row = mysql_fetch_array($sql))
										{
											$station_id = $row['id'];
											
											$sql2 = mysql_query("select distinct customer_station, sum(loan_total_interest)loan_due from loan_application where customer_station = '$station_id' and loan_due_date = '$current_date' and loan_status != '12' and loan_status != '14' and loan_status != '11' and loan_status != '10' group by customer_station");
											while ($row = mysql_fetch_array($sql2))
											{
												$loan_due = $row['loan_due'];
												if(is_null($loan_due)){
													$loan_due = 0;
												}
											}
											
											$sql2 = mysql_query("select distinct loan_code from loan_application where loan_due_date = '$current_date' and customer_station = '$station_id'");
											while ($row = mysql_fetch_array($sql2))
											{
												$loan_code = $row['loan_code'];
												$sql3 = mysql_query("select sum(loan_rep_amount)repayments from loan_repayments where loan_rep_code = '$loan_code' group by loan_rep_code");
												while ($row = mysql_fetch_array($sql3))
												{
													$repayments = $row['repayments'];
													if(is_null($repayments)){
														$repayments = 0;
													}
													$total_repayments = $total_repayments + $repayments;
												}
												
											}
											
											$due = ($total_repayments / $loan_due) * 100;
											echo "<td align='right' valign='top'>".number_format($due, 2)."%</td>";	
											$loan_due = 0;
											$repayments = 0;
											$total_repayments = 0;
											
										}
										?>
									</tr>
									<tr>
										<td>Collections</td>
										<?php
										$station_id = 0;
										$loan_due = 0;
										$sql = mysql_query("select id, stations from stations where id != '4' and id != '3' and id != '10' order by id asc");
										while ($row = mysql_fetch_array($sql))
										{
											$station_id = $row['id'];
											$sql2 = mysql_query("select distinct customer_station, sum(loan_rep_amount)repayments_done from loan_repayments where customer_station = '$station_id' and loan_rep_date = '$current_date' group by customer_station");
											while ($row = mysql_fetch_array($sql2))
											{
												$repayments_done = $row['repayments_done'];
												if(is_null($repayments_done)){
													$repayments_done = 0;
												}
												echo "<td align='right' valign='top'>".number_format($repayments_done, 2)."</td>";
												$sql5="update business_totals_daily set daily_collectable='$loan_due' WHERE station  = '$station_id'";
												//echo $sql5."2.<br />";
												//$result = mysql_query($sql5);	
												$station_id = 0;
												$repayments_done = 0;	
											}
										}
										?>
									</tr>
									<tr>
										<td>New Leads</td>
										<?php
										$station_id = 0;
										$sql = mysql_query("select id, stations from stations where id != '4' and id != '3' and id != '10' order by id asc");
										$start_date_leads = $current_date.' 00:00:00';
										$end_date_leads = $current_date.' 23:59:59';
										while ($row = mysql_fetch_array($sql))
										{
											$station_id = $row['id'];
											$sql2 = mysql_query("select count(id)leads from users where national_id = '' and transactiontime between '$start_date_leads' and '$end_date_leads' and stations = '$station_id' group by stations");
											$num_rows = mysql_num_rows($sql2);
											//while ($row = mysql_fetch_array($sql2))
											//{
											//	$leads = $row['leads'];
												echo "<td align='right' valign='top'>".number_format($num_rows, 0)."</td>";	
											//}
										}
										?>
									</tr>
									<tr>
										<td>New Customers</td>
										<?php
										$station_id = 0;
										$num_rows = 0;
										$sql = mysql_query("select id, stations from stations where id != '4' and id != '3' and id != '10' order by id asc");
										while ($row = mysql_fetch_array($sql))
										{
											$station_id = $row['id'];
											$sql2 = mysql_query("select customer_station, loan_date from loan_application where customer_station = '$station_id' and loan_date != '0000-00-00' order by loan_date asc limit 1");
											while ($row = mysql_fetch_array($sql2))
											{
												$loan_date = $row['loan_date'];
											}
											$sql2 = mysql_query("select count(loan_id)loan_count_rep from loan_application where loan_date = '$current_date' and customer_station = '$station_id' and initiation_fee != '0' group by customer_id");
											$num_rows = mysql_num_rows($sql2);
											echo "<td align='right' valign='top'>".number_format($num_rows, 0)."</td>";
											$total_loan_count_rep =  0;
										}
										?>
									</tr>
									<tr>
										<td>Repeat Customers</td>
										<?php
										$station_id = 0;
										$num_rows = 0;
										$sql = mysql_query("select id, stations from stations where id != '4' and id != '3' and id != '10' order by id asc");
										while ($row = mysql_fetch_array($sql))
										{
											$station_id = $row['id'];
											$sql2 = mysql_query("select customer_station, loan_date from loan_application where customer_station = '$station_id' and loan_date != '0000-00-00' order by loan_date asc limit 1");
											while ($row = mysql_fetch_array($sql2))
											{
												$loan_date = $row['loan_date'];
											}
											//$sql2 = mysql_query("select count(loan_id)loan_count_rep from loan_application where loan_date between '$loan_date' and '$current_date' and customer_station = '$station_id' and initiation_fee = '0' group by customer_id");
											$sql2 = mysql_query("select count(loan_id)loan_count_rep from loan_application where loan_date = '$current_date' and customer_station = '$station_id' and initiation_fee = '0' group by customer_id");
											$num_rows = mysql_num_rows($sql2);
											echo "<td align='right' valign='top'>".number_format($num_rows, 0)."</td>";
											$total_loan_count_rep =  0;
										}
										?>
									</tr>
									<tr>
										<td>Average Loan Value (New Loans)</td>
										<?php
										$station_id = 0;
										$num_rows = 0;
										$sql = mysql_query("select id, stations from stations where id != '4' and id != '3' and id != '10' order by id asc");
										while ($row = mysql_fetch_array($sql))
										{
											$station_id = $row['id'];
											$sql2 = mysql_query("select avg(loan_amount)avg_loan_size_new from loan_application where initiation_fee != '0' and loan_date = '$current_date' and customer_station = '$station_id'");
											while ($row = mysql_fetch_array($sql2))
											{
												$avg_loan_size_new = $row['avg_loan_size_new'];
												if(is_null($avg_loan_size_new)){
													$avg_loan_size_new = 0;
												}
											}
											echo "<td align='right' valign='top'>".number_format($avg_loan_size_new, 0)."</td>";
											$avg_loan_size_new =  0;
										}
										?>
									</tr>
									<tr>
										<td>Average Loan Value (Repeat Loans)</td>
										<?php
										$station_id = 0;
										$num_rows = 0;
										$sql = mysql_query("select id, stations from stations where id != '4' and id != '3' and id != '10' order by id asc");
										while ($row = mysql_fetch_array($sql))
										{
											$station_id = $row['id'];
											$sql2 = mysql_query("select avg(loan_amount)avg_loan_size_repeat from loan_application where initiation_fee = '0' and loan_date = '$current_date' and customer_station = '$station_id'");
											while ($row = mysql_fetch_array($sql2))
											{
												$avg_loan_size_repeat = $row['avg_loan_size_repeat'];
												if(is_null($avg_loan_size_repeat)){
													$avg_loan_size_repeat = 0;
												}
											}
											echo "<td align='right' valign='top'>".number_format($avg_loan_size_repeat, 0)."</td>";
											$avg_loan_size_repeat =  0;
										}
										?>
									</tr>
								</tbody>
							</table>
							<?php 
								include_once('includes/dash_graphs.php');
							?>
							<table width="100%" cellpadding="20px" cellspacing="10px">
								<tr>
									<td width="50%" style="border: 1px dotted #F8F2F2; padding-left:2px;">
										<div class='example awesome'>
											<h3>Business Totals Daily</h3>
											<div id="chart_div" style="width: 1070px; height: 600px;"></div>
										</div>
									</td>
								</tr>
							</table>
						<?php } else if($view == 'monthly'){ ?>
							<h2>Business Totals Monthly</h2>
							<h3>Select Year:
							<?php
								$result = mysql_query("select distinct extract(year from loan_date)year from loan_application where extract(year from loan_date) !='0' group by  extract(year from loan_date)");
								while ($row = mysql_fetch_array($result))
								{
									$report_year = $row['year'];
									echo "<a href='index.php?view=monthly&year=$report_year'>$report_year</a> | ";
								}
							?>
							</h3>
							<h3>Smartcash Overview</h3>
							<h4>Year: <?php echo $year_report ?></h4>
							<table width="100%" border="0" cellspacing="2" cellpadding="2" class="display" id="exampl">
								<thead bgcolor="#E6EEEE">
									<tr bgcolor='#fff'>
										<th>&nbsp;</th>
										<?php
										$result = mysql_query("select distinct extract(month from loan_date)report_month from loan_application where extract(year from loan_date) !='0' group by  extract(month from loan_date)");
										while ($row = mysql_fetch_array($result))
										{
											$report_month = $row['report_month'];
											$result_tender = mysql_query("select month from calender where id = '$report_month'");
											while ($row = mysql_fetch_array($result_tender))
											{
												$month_name = $row['month'];
											}
											echo "<th>$month_name</th>";
										}
										?>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>Branches</td>
										<?php
										$sql = mysql_query("select distinct extract(month from loan_date)report_month from loan_application where extract(year from loan_date) !='0' group by  extract(month from loan_date)");
										while ($row = mysql_fetch_array($sql))
										{
											$report_month = $row['report_month'];
											$sql2 = mysql_query("select count(distinct customer_station)station from loan_application where extract(month from loan_date) = '$report_month' and extract(year from loan_date) = '$year_report'");
											while ($row = mysql_fetch_array($sql2))
											{
												$station = $row['station'];
												echo "<td align='right' valign='top'>".number_format($station, 0)."</td>";	
												$sql5="update business_totals_monthly set branches='$station' WHERE month  = '$report_month'";
												//echo $sql5."2.<br />";
												$result = mysql_query($sql5);
											}
										}
										?>
									</tr>
									<tr>
										<td>Target</td>
										<?php
										$sql = mysql_query("select distinct extract(month from loan_date)report_month from loan_application where extract(year from loan_date) !='0' group by  extract(month from loan_date)");
										while ($row = mysql_fetch_array($sql))
										{
											$report_month = $row['report_month'];
											$sql2 = mysql_query("select count(distinct customer_station)station from loan_application where extract(month from loan_date) = '$report_month' and extract(year from loan_date) = '$year_report'");
											while ($row = mysql_fetch_array($sql2))
											{
												$station = $row['station'];
												$sql2 = mysql_query("select sum(monthly_target)target from stations");
												while ($row = mysql_fetch_array($sql2))
												{
													$target = $row['target'];
													if(is_null($target)){
														$target = 0;
													}
													if($station == '0'){
														$target = 0;
														echo "<td align='right' valign='top'>".number_format($target)."</td>";
													}
													else{
														echo "<td align='right' valign='top'>".number_format($target)."</td>";
													}
													$sql5="update business_totals_monthly set target='$target' WHERE month  = '$report_month'";
													//echo $sql5."2.<br />";
													$result = mysql_query($sql5);
												}
											}
										}
										?>
									</tr>
									<tr>
										<td>Disbursement</td>
										<?php
										$sql = mysql_query("select distinct extract(month from loan_date)report_month from loan_application where extract(year from loan_date) !='0' group by  extract(month from loan_date)");
										while ($row = mysql_fetch_array($sql))
										{
											$report_month = $row['report_month'];
											$sql2 = mysql_query("select sum(loan_amount)loan from loan_application where extract(month from loan_date) = '$report_month' and extract(year from loan_date) = '$year_report'");
											while ($row = mysql_fetch_array($sql2))
											{
												$loan = $row['loan'];
												if(is_null($loan)){
													$loan = 0;
												}
												echo "<td align='right' valign='top'>".number_format($loan)."</td>";	
												$sql5="update business_totals_monthly set disbursement='$loan' WHERE month  = '$report_month'";
												//echo $sql5."2.<br />";
												$result = mysql_query($sql5);
											}
										}
										?>
									</tr>
									<tr>
										<td>Collectible</td>
										<?php
										$station_id = 0;
										$loan_due = 0;
										$sql = mysql_query("select distinct extract(month from loan_date)report_month from loan_application where extract(year from loan_date) !='0' group by  extract(month from loan_date)");
										while ($row = mysql_fetch_array($sql))
										{
											$report_month = $row['report_month'];
											$sql2 = mysql_query("select sum(loan_total_interest)loan_due from loan_application where extract(month from loan_due_date) = '$report_month' and extract(year from loan_due_date) = '$year_report' and loan_status != '12' and loan_status != '14' and loan_status != '11' and loan_status != '10'");
											while ($row = mysql_fetch_array($sql2))
											{
												$loan_due = $row['loan_due'];
												if(is_null($loan_due)){
													$loan_due = 0;
												}
												echo "<td align='right' valign='top'>".number_format($loan_due)."</td>";
												$sql5="update business_totals_monthly set collectable='$loan_due' WHERE month  = '$report_month'";
												//echo $sql5."2.<br />";
												$result = mysql_query($sql5);	
											}
										}
										?>
									</tr>
									<tr>
										<td>Collectible Actual</td>
										<?php
										$station_id = 0;
										$repayments = 0;
										$total_repayments = 0;
										$sql = mysql_query("select distinct extract(month from loan_date)report_month from loan_application where extract(year from loan_date) !='0' group by  extract(month from loan_date)");
										while ($row = mysql_fetch_array($sql))
										{
											$report_month = $row['report_month'];
											$sql2 = mysql_query("select distinct loan_code from loan_application where extract(month from loan_due_date) = '$report_month' and extract(year from loan_due_date) = '$year_report' and loan_status != '12' and loan_status != '14' and loan_status != '11' and loan_status != '10'");
											while ($row = mysql_fetch_array($sql2))
											{
												$loan_code = $row['loan_code'];
												$sql3 = mysql_query("select sum(loan_rep_amount)repayments from loan_repayments where loan_rep_code = '$loan_code' group by loan_rep_code");
												while ($row = mysql_fetch_array($sql3))
												{
													$repayments = $row['repayments'];
													if($repayments == ""){
														$repayments = 0;
													}
													$total_repayments = $total_repayments + $repayments;
													
												}
												
											}
											echo "<td align='right' valign='top'>".number_format($total_repayments)."</td>";
											$sql5="update business_totals_monthly set collectable_actual='$total_repayments' WHERE month  = '$report_month'";
											//echo $sql5."2.<br />";
											$result = mysql_query($sql5);	
											$repayments = 0;
											$total_repayments = 0;
										}
										?>
									</tr>
									<tr>
										<td>Collections</td>
										<?php
										$sql = mysql_query("select distinct extract(month from loan_date)report_month from loan_application where extract(year from loan_date) !='0' group by  extract(month from loan_date)");
										while ($row = mysql_fetch_array($sql))
										{
											$report_month = $row['report_month'];
											$sql2 = mysql_query("select sum(loan_rep_amount)repaymnet from loan_repayments where extract(month from loan_rep_date) = '$report_month' and extract(year from loan_rep_date) = '$year_report'");
											while ($row = mysql_fetch_array($sql2))
											{
												$repaymnet = $row['repaymnet'];
												if(is_null($loan)){
													$repaymnet = 0;
												}
												echo "<td align='right' valign='top'>".number_format($repaymnet)."</td>";	
											}
										}
										?>
									</tr>
									<tr>
										<td>% Rate</td>
										<?php
										$station_id = 0;
										$total_repayments = 0;
										$sql = mysql_query("select distinct extract(month from loan_date)report_month from loan_application where extract(year from loan_date) !='0' group by  extract(month from loan_date)");
										while ($row = mysql_fetch_array($sql))
										{
											$report_month = $row['report_month'];
											
											$sql2 = mysql_query("select sum(loan_total_interest)loan_due from loan_application where extract(month from loan_due_date) = '$report_month' and extract(year from loan_due_date) = '$year_report' and loan_status != '12' and loan_status != '14' and loan_status != '11'");
											while ($row = mysql_fetch_array($sql2))
											{
												$loan_due = $row['loan_due'];
												if(is_null($loan_due)){
													$loan_due = 0;
												}	
											}
											
											$sql2 = mysql_query("select distinct loan_code from loan_application where extract(month from loan_due_date) = '$report_month' and extract(year from loan_due_date) = '$year_report'");
											while ($row = mysql_fetch_array($sql2))
											{
												$loan_code = $row['loan_code'];
												$sql3 = mysql_query("select sum(loan_rep_amount)repayments from loan_repayments where loan_rep_code = '$loan_code' group by loan_rep_code");
												while ($row = mysql_fetch_array($sql3))
												{
													$repayments = $row['repayments'];
													if($repayments == ""){
														$repayments = 0;
													}
													$total_repayments = $total_repayments + $repayments;
												}
												
											}
											
											$due = ($total_repayments / $loan_due) * 100;
											echo "<td align='right' valign='top'>".number_format($due, 0)."%</td>";	
											$loan_due = 0;
											$repayments = 0;
											$total_repayments = 0;
											
										}
										?>
									</tr>
									<tr>
										<td>Leads</td>
										<?php
										$station_id = 0;
										$result = mysql_query("select distinct extract(month from loan_date)report_month from loan_application where extract(year from loan_date) !='0' group by  extract(month from loan_date)");
										while ($row = mysql_fetch_array($result))
										{
											$report_month = $row['report_month'];
											$sql2 = mysql_query("select count(id)leads from users where national_id = '' and extract(month from transactiontime) = '$report_month' and extract(year from transactiontime) = '$year_report'");
											while ($row = mysql_fetch_array($sql2))
											{
												$leads = $row['leads'];
												echo "<td align='right' valign='top'>".number_format($leads, 0)."</td>";	
											}
										}
										?>
									</tr>
									<tr>
										<td>New Customers</td>
										<?php
										$station_id = 0;
										$num_rows = 0;
										$result = mysql_query("select distinct extract(month from loan_date)report_month from loan_application where extract(year from loan_date) !='0' group by  extract(month from loan_date)");
										while ($row = mysql_fetch_array($result))
										{
											$report_month = $row['report_month'];
											$sql2 = mysql_query("select count(loan_id)loan_count_rep from loan_application where extract(month from loan_due_date) = '$report_month' and extract(year from loan_due_date) = '$year_report' and initiation_fee != '0' group by customer_id order by loan_count_rep desc");
											$num_rows = mysql_num_rows($sql2);
											echo "<td align='right' valign='top'>".number_format($num_rows, 0)."</td>";
											$total_loan_count_rep =  0;
										}
										?>
									</tr>
									<tr>
										<td>Repeat</td>
										<?php
										$station_id = 0;
										$num_rows = 0;
										$result = mysql_query("select distinct extract(month from loan_date)report_month from loan_application where extract(year from loan_date) !='0' group by  extract(month from loan_date)");
										while ($row = mysql_fetch_array($result))
										{
											$report_month = $row['report_month'];
											$sql2 = mysql_query("select count(loan_id)loan_count_rep from loan_application where extract(month from loan_due_date) = '$report_month' and extract(year from loan_due_date) = '$year_report' and initiation_fee = '0' group by customer_id order by loan_count_rep desc");
											$num_rows = mysql_num_rows($sql2);
											echo "<td align='right' valign='top'>".number_format($num_rows, 0)."</td>";
											$total_loan_count_rep =  0;
										}
										?>
									</tr>
									<tr>
										<td>Average Loan Value (New Loans)</td>
										<?php
										$station_id = 0;
										$num_rows = 0;
										$result = mysql_query("select distinct extract(month from loan_date)report_month from loan_application where extract(year from loan_date) !='0' group by  extract(month from loan_date)");
										while ($row = mysql_fetch_array($result))
										{
											$report_month = $row['report_month'];
											$sql2 = mysql_query("select avg(loan_amount)avg_loan_size_new from loan_application where extract(month from loan_due_date) = '$report_month' and extract(year from loan_due_date) = '$year_report' and initiation_fee != '0'");
											while ($row = mysql_fetch_array($sql2))
											{
												$avg_loan_size_new = $row['avg_loan_size_new'];
												if(is_null($avg_loan_size_new)){
													$avg_loan_size_new = 0;
												}
											}
											echo "<td align='right' valign='top'>".number_format($avg_loan_size_new, 0)."</td>";
											$avg_loan_size_new =  0;
										}
										?>
									</tr>
									<tr>
										<td>Average Loan Value (Repeat Loans)</td>
										<?php
										$station_id = 0;
										$num_rows = 0;
										$result = mysql_query("select distinct extract(month from loan_date)report_month from loan_application where extract(year from loan_date) !='0' group by  extract(month from loan_date)");
										while ($row = mysql_fetch_array($result))
										{
											$report_month = $row['report_month'];
											$sql2 = mysql_query("select avg(loan_amount)avg_loan_size_repeat from loan_application where extract(month from loan_due_date) = '$report_month' and extract(year from loan_due_date) = '$year_report' and initiation_fee = '0'");
											while ($row = mysql_fetch_array($sql2))
											{
												$avg_loan_size_repeat = $row['avg_loan_size_repeat'];
												if(is_null($avg_loan_size_repeat)){
													$avg_loan_size_repeat = 0;
												}
											}
											echo "<td align='right' valign='top'>".number_format($avg_loan_size_repeat, 0)."</td>";
											$avg_loan_size_repeat =  0;
										}
										?>
									</tr>
								</tbody>
							</table>
							<?php 
								include_once('includes/dash_graphs.php');
							?>
							<table width="100%" cellpadding="20px" cellspacing="10px">
								<tr>
									<td width="50%" style="border: 1px dotted #F8F2F2; padding-left:2px;">
										<div class='example awesome'>
											<h3>Business Totals Monthly</h3>
											<div id="monthly_chart_div" style="width: 1070px; height: 600px;"></div>
										</div>
									</td>
								</tr>
							</table>
						<?php } else if($view == 'arrears'){ ?>
							<h2>Arrears</h2>
							<h3>Smartcash Arrears</h3>
							<form id="frmCreateTenant" name="frmCreateTenant" method="POST" action="index.php?view=arrears">
								<table border="0" width="100%" cellspacing="2" cellpadding="2">
									<tr >
										<td  valign="top">Select Date Range: </td>
										<td>
											<input title="Enter the Selection Date" value="" id="report_start_date" name="report_start_date" type="text" maxlength="100" class="main_input" size="15" />
										</td>
									</tr>
									<tr>
										<td><button name="btnNewCard" id="button">Submit</button></td>
									</tr>
								</tabLe>
							</form>
							<h4>Date: <?php echo $current_date ?></h4>
							<?php
								$sql2 = mysql_query("select loan_date from loan_application where loan_date != '0000-00-00' order by loan_date asc limit 1");
								while ($row = mysql_fetch_array($sql2))
								{
									$filter_start_date = $row['loan_date'];
								}
							?>
							<table width="100%" border="0" cellspacing="2" cellpadding="2" class="display" id="exampl">
								<thead bgcolor="#E6EEEE">
									<tr bgcolor='#fff'>
										<th>Vintage</th>
										<th>Amount</th>
										<th>BLC</th>
										<th>BFC</th>
										<th>% BLC</th>
										<th>% BFC</th>
										<th>Collected</th>
										<th>% Vintage</th>
									</tr>
								</thead>
								<tbody>
								<?php
									$sql = mysql_query("select distinct vintage, sum(loan_total_interest)arrears from loan_application where loan_due_date between '$filter_start_date' and '$current_date' and vintage != '' group by vintage order by vintage asc");
									while ($row = mysql_fetch_array($sql))
									{
										$vintage = $row['vintage'];
										$arrears = $row['arrears'];
										
										$sql2 = mysql_query("select sum(loan_total_interest)arrears from loan_application where loan_due_date between '$filter_start_date' and '$current_date' and vintage = '$vintage' and customer_state = 'BLC' group by vintage");
										while ($row = mysql_fetch_array($sql2))
										{
											$BLC_arrears = $row['arrears'];
											if($BLC_arrears == ''){
												$BLC_arrears = 0;
											}
										}
										
										$sql2 = mysql_query("select sum(loan_total_interest)arrears from loan_application where loan_due_date between '$filter_start_date' and '$current_date' and vintage = '$vintage' and customer_state = 'BFC' group by vintage");
										while ($row = mysql_fetch_array($sql2))
										{
											$BFC_arrears = $row['arrears'];
											if($BFC_arrears == ''){
												$BFC_arrears = 0;
											}
										}
										
										$sql2 = mysql_query("select loan_code from loan_application where vintage = '$vintage' and loan_due_date between '$filter_start_date' and '$current_date' and vintage != ''");
										while ($row = mysql_fetch_array($sql2))
										{
											$loan_code = $row['loan_code'];
											$sql3 = mysql_query("select sum(loan_rep_amount)repayment from loan_repayments where loan_rep_code = '$loan_code'");
											while ($row = mysql_fetch_array($sql3))
											{
												$repayment = $row['repayment'];
												if($repayment == ''){
													$repayment = 0;
												}
												$total_repayment = $total_repayment + $repayment;
											}	
										}
										
										$BLC_rate = ($BLC_arrears / $arrears) * 100;
										$BFC_rate = ($BFC_arrears / $arrears) * 100;
										$vintage_rate = ($total_repayment / $arrears) * 100;
										
							
										if ($intcount % 2 == 0) {
											$display= '<tr bgcolor = #F5F5F5>';
										}
										else {
											$display= '<tr>';
										}
										echo $display;
										echo "<td valign='top'>$vintage</td>";
										echo "<td align='right' valign='top'>".number_format($arrears, 2)."</td>";
										echo "<td align='right' valign='top'>".number_format($BLC_arrears, 2)."</td>";
										echo "<td align='right' valign='top'>".number_format($BFC_arrears, 2)."</td>";
										echo "<td align='right' valign='top'>".number_format($BLC_rate, 2)."%</td>";
										echo "<td align='right' valign='top'>".number_format($BFC_rate, 2)."%</td>";
										echo "<td align='right' valign='top'>".number_format($total_repayment, 2)."</td>";
										echo "<td align='right' valign='top'>".number_format($vintage_rate, 2)."%</td>";
										$total_repayment = 0;
										$BLC_arrears = 0;
										$BFC_arrears = 0;
										$repayment = 0;
										$arrears = 0;
										$total_repayment = 0;
									}
								?>
							</table>
							<?php } ?>
					<?php } else if($station == '4'){ ?>
						<?php 
							include_once('includes/dash_graphs.php');
						?>
						<table width="100%" cellpadding="20px" cellspacing="10px">
							<tr>
								<td width="50%" bgcolor="#F5F5F5" style="border: 1px dotted #F5F5F5; padding-left:2px;">
									<div class='example awesome'>
										<h3><font color="#000A8B">Collections (Month to Date)</font></h3>
										<div id="collections" style="width: 470px; height: 300px;"></div>
									</div>
								</td>
								<td width="50%" bgcolor="#F5F5F5" style="border: 1px dotted #F5F5F5; padding-left:2px;">
									<div class='example awesome'>
										<h3><font color="#000A8B">Call Outcomes (Year to Date)</font></h3>
										<div id="call_outcomes" style="width: 470px; height: 300px;"></div>
									</div>
								</td>
							</tr>
						</table>
					<?php } else{ ?>
						<?php 
							include_once('includes/dash_graphs.php');
						?>
						<table width="100%" cellpadding="20px" cellspacing="10px">
							<tr>
								<td width="50%" bgcolor="#F5F5F5" style="border: 1px dotted #F5F5F5; padding-left:2px;">
									<div class='example awesome'>
										<h3><font color="#000A8B">Loan Disbursements (Year to Date)</font></h3>
										<div id="branches_breakdown" style="width: 470px; height: 300px;"></div>
									</div>
								</td>
								<td width="50%" bgcolor="#F5F5F5" style="border: 1px dotted #F5F5F5; padding-left:2px;">
									<div class='example awesome'>
										<h3><font color="#000A8B">Loan Status (Year to Date)</font></h3>
										<div id="loan_status" style="width: 470px; height: 300px;"></div>
									</div>
								</td>
							</tr>
						</table>
					<?php } ?>
					
				</div>
			</div>
			<br class="clearfix" />
			</div>
		</div>
<?php
	}
	include_once('includes/footer.php');
?>
