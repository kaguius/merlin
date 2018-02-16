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
		$page_title = "Daily PTPs against Collections Report";
		include_once('includes/header.php');
		$filter_clerk = 0;
		if (!empty($_GET)){	
			$filter_clerk = $_GET['clerk'];
			$loan_officer = $_GET['loan_officer'];
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
					<h3>Collections</h3>
					<table width="100%" border="0" cellspacing="2" cellpadding="2" class="display" id="example3">
					<thead bgcolor="#E6EEEE">
						<tr>
							<th>#</th>
							<th>Date</th>
							<th>Loan Ref</th>
							<th>Vintage</th>
							<th>Projection</th>
							<th>Amount</th>
							<th>Rate</th>
						</tr>
					</thead>
					<tbody>
					<?php
						 $sql = mysql_query("select distinct loan_application.loan_status, loan_rep_date, loan_rep_code, sum(loan_rep_amount)loan_rep_amount, collections_agent, loan_application.vintage from loan_repayments inner join loan_application on loan_application.loan_code = loan_repayments.loan_rep_code where loan_rep_date between '$filter_start_date' and '$filter_end_date' and vintage != '' group by loan_rep_code order by loan_rep_date asc");
						 $intcount = 0;
						 while ($row = mysql_fetch_array($sql))
						 {
							$intcount++;
							$loan_rep_date = $row['loan_rep_date'];
							$loan_rep_code = $row['loan_rep_code'];
							$loan_rep_amount = $row['loan_rep_amount'];
							$vintage = $row['vintage'];
							
							$sql2 = mysql_query("select sum(loan_balance)projection from promise_to_pay where loan_code = '$loan_rep_code' group by loan_code");	
							while($row = mysql_fetch_array($sql2)) {
								$projection = $row['projection'];
								if($projection == ""){
									$projection = 0;
								}
							}
							
							$rate = ($loan_rep_amount / $projection) * 100;
							
							if ($intcount % 2 == 0) {
								$display= '<tr bgcolor = #F0F0F6>';
							}
							else {
								$display= '<tr>';
							}
							echo $display;
							echo "<td valign='top'>$intcount.</td>";
							echo "<td valign='top'>$loan_rep_date</td>";
							echo "<td valign='top'>$loan_rep_code</td>";
							echo "<td valign='top'>$vintage</td>";
							echo "<td align='right' valign='top'>".number_format($projection, 2)."</td>";
							echo "<td align='right' valign='top'>".number_format($loan_rep_amount, 2)."</td>";
							echo "<td align='right' valign='top'>".number_format($rate, 2)."%</td>";
							echo "</tr>";
							
							$total_loan_rep_amount = $total_loan_rep_amount  + $loan_rep_amount;
							$total_projection = $total_projection  + $projection;
							
							$projection = 0;
						}
						$total_rate = ($total_loan_rep_amount / $total_projection) * 100;
						?>
					</tbody>
					<tr bgcolor = '#E6EEEE'>
						<td colspan='4'><strong>&nbsp;</strong></td>
						<td align='right' valign='top'><strong><?php echo number_format($total_projection, 2) ?></strong></td>
						<td align='right' valign='top'><strong><?php echo number_format($total_loan_rep_amount, 2) ?></strong></td>
						<td align='right' valign='top'><strong><?php echo number_format($total_rate, 2) ?>%</strong></td>
					</tr>
					<tfoot bgcolor="#E6EEEE">
						<tr>
							<th>#</th>
							<th>Date</th>
							<th>Loan Ref</th>
							<th>Vintage</th>
							<th>Projection</th>
							<th>Amount</th>
							<th>Rate</th>
						</tr>
					</tfoot>
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
								<td  valign="top">Select Start Date Range: </td>
								<td>
									<input title="Enter the Selection Date" value="" id="report_start_date" name="report_start_date" type="text" maxlength="100" class="main_input" size="15" />
								</td>
								<td  valign="top">Select End Date Range:</td>
								<td> 
									<input title="Enter the Selection Date" value="" id="report_end_date" name="report_end_date" type="text" maxlength="100" class="main_input" size="15" />
								</td>
								
							</tr>
							<!--<tr >
								<td  valign="top">Loans Officer: </td>
								<td>
									<select name='loan_officer' id='loan_officer'>
										<option value=''> </option>
									<?php
										if($station == '3'){ 
											$sql2 = mysql_query("select user_profiles.id, first_name, last_name, stations.stations from user_profiles inner join stations on stations.id = user_profiles.station where title = '1'");
										}
										else{										
											$sql2 = mysql_query("select id, first_name, last_name from user_profiles where station = '$station' and title = '1'");
										}
										while($row = mysql_fetch_array($sql2)) {
											$loan = $row['id'];
											$first_name = $row['first_name'];
											$last_name = $row['last_name'];
											if($station == '3'){ 
												$stations = $row['stations'];
												echo "<option value='$loan'>".$stations.": ".$first_name." ".$last_name."</option>"; 
											}
											else{
												echo "<option value='$loan'>".$first_name." ".$last_name."</option>"; 
											}
										}
									?>
									</select>
								</td>
								
							</tr>-->
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
