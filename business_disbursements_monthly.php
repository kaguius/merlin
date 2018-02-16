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
		$page_title = "Branch Disbursement and Collections";
		include_once('includes/header.php');
		$filter_clerk = 0;
		if (!empty($_GET)){	
			$filter_end_date = $_GET['report_end_date'];
			$filter_end_date = date('Y-m-d', strtotime(str_replace('-', '/', $filter_end_date)));
			$filter_month = date('m', strtotime($filter_end_date));
			$filter_year = date('Y', strtotime($filter_end_date));
			$filter_day = 01;
			$filter_start_date = $filter_year."-".$filter_month."-".$filter_day;
		}
		include_once('includes/db_conn.php');
		if ($filter_start_date != "" && $filter_end_date != ""){
			$start_filter_start_date = strtotime($filter_start_date);
			$end_filter_end_date = strtotime($filter_end_date);
			$report_duration = ((ceil(abs($end_filter_end_date - $start_filter_start_date) / 86400)));
			$report_duration = $report_duration + 1;
			//echo $report_duration;
			
		?>
		<div id="page">
			<div id="content">
				<div class="post">
					<h2><?php echo $page_title ?></h2>
					<p><strong>Report Range: <?php echo $filter_start_date ?> to <?php echo $filter_end_date ?></strong></p>
					
							<h3>Monthly Collections</h3>
							<table width="100%" border="0" cellspacing="2" cellpadding="2" class="display" id="exampl">
								<thead bgcolor="#E6EEEE">
									<tr>
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
									<td><?php echo $filter_start_date ?></td>
									<?php
									$sql = mysql_query("select distinct extract(month from loan_date)report_month from loan_application where extract(year from loan_date) !='0' group by  extract(month from loan_date) order by report_month desc limit 4");
										while ($row = mysql_fetch_array($sql))
										{
											$report_month = $row['report_month'];
											
											$sql2 = mysql_query("select sum(loan_total_interest)loan_due from loan_application where extract(month from loan_due_date) = '$report_month' and extract(year from loan_due_date) = '$year_report'");
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
											echo "<td align='right' valign='top'>".number_format($due, 2)."%</td>";	
											$loan_due = 0;
											$repayments = 0;
											$total_repayments = 0;
											
										}
									?>
								</tr>
							</tr>
						</tbody>
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
								<!--<td  valign="top">Select Start Date Range: </td>
								<td>
									<input title="Enter the Selection Date" value="" id="report_start_date" name="report_start_date" type="text" maxlength="100" class="main_input" size="15" />
								</td>-->
								<td  valign="top">Select End Date Range:</td>
								<td> 
									<input title="Enter the Selection Date" value="" id="report_end_date" name="report_end_date" type="text" maxlength="100" class="main_input" size="15" />
								</td>
								
							</tr>
							
							<!--<tr >
								<td  valign="top">Branch: </td>
								<td>
									<select name='branch' id='branch'>
										<option value=''> </option>
									<?php
										$sql2 = mysql_query("select id, stations from stations order by stations asc");
										while($row = mysql_fetch_array($sql2)) {
											$id = $row['id'];
											$stations = $row['stations'];
											echo "<option value='$id'>".$stations."</option>"; 
										}
									?>
									</select>
								</td>-->
								
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
