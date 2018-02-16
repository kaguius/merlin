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
		$page_title = "Defaulter Aging Report";
		include_once('includes/header.php');
		$filter_clerk = 0;
		if (!empty($_GET)){	
			$loan_officer = $_GET['loan_officer'];
			$filter_start_date = $_GET['report_start_date'];
			$filter_start_date = date('Y-m-d', strtotime(str_replace('-', '/', $filter_start_date)));
			$filter_end_date = $_GET['report_end_date'];
			$filter_end_date = date('Y-m-d', strtotime(str_replace('-', '/', $filter_end_date)));
		}
		$sql2 = mysql_query("select loan_date from loan_application where loan_date != '0000-00-00' order by loan_date asc limit 1");
		while ($row = mysql_fetch_array($sql2))
		{
			$filter_start_date = $row['loan_date'];
		}
		include_once('includes/db_conn.php');
		if ($filter_start_date != "" && $filter_end_date != ""){
			$filter_start_date = $filter_start_date.' 00:00:00';
			$filter_end_date = $filter_end_date.' 23:59:59';
			
		?>
		<div id="page">
			<div id="content">
				<div class="post">
					<h2><?php echo $page_title ?></h2>
					<p><strong>Report Range: <?php echo $filter_start_date ?> to <?php echo $filter_end_date ?></strong></p>
					<table width="100%" border="0" cellspacing="2" cellpadding="2" class="display" id="exampl">
								<thead bgcolor="#E6EEEE">
									<tr>
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
									$sql = mysql_query("select distinct vintage, sum(loan_total_interest)arrears from loan_application where loan_due_date between '$filter_start_date' and '$filter_end_date' and vintage != '' group by vintage order by vintage asc");
									while ($row = mysql_fetch_array($sql))
									{
										$vintage = $row['vintage'];
										$arrears = $row['arrears'];
										
										$sql2 = mysql_query("select sum(loan_total_interest)arrears from loan_application where loan_due_date between '$filter_start_date' and '$filter_end_date' and vintage = '$vintage' and customer_state = 'BLC' group by vintage");
										while ($row = mysql_fetch_array($sql2))
										{
											$BLC_arrears = $row['arrears'];
											if($BLC_arrears == ''){
												$BLC_arrears = 0;
											}
										}
										
										$sql2 = mysql_query("select sum(loan_total_interest)arrears from loan_application where loan_due_date between '$filter_start_date' and '$filter_end_date' and vintage = '$vintage' and customer_state = 'BFC' group by vintage");
										while ($row = mysql_fetch_array($sql2))
										{
											$BFC_arrears = $row['arrears'];
											if($BFC_arrears == ''){
												$BFC_arrears = 0;
											}
										}
										
										$sql2 = mysql_query("select loan_code from loan_application where vintage = '$vintage' and loan_due_date between '$filter_start_date' and '$filter_end_date' and vintage != ''");
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
											$display= '<tr bgcolor = #F0F0F6>';
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
					<!--<table width="100%" border="0" cellspacing="2" cellpadding="2" class="display" id="example3">
						<thead bgcolor="#E6EEEE">
							<tr>
								<th>#</th>
								<th>Vintage</th>
								<th>Balance</th>
								<th>Counts</th>
								<th>LO</th>
								<th>CO</th>
							</tr>
						</thead>
						<tbody>
						<?php
							if($station == '3'){
								$sql = mysql_query("select distinct vintage, sum(loan_balance)balance, count(promise_to_pay.id)counts, users.loan_officer, users.collections_officer from promise_to_pay inner join users on users.id = promise_to_pay.customer_id where promise_to_pay.transactiontime between '$filter_start_date' and '$filter_end_date' group by users.loan_officer, vintage");
							}
							else{
								if($loan_officer == ""){
									$sql = mysql_query("select distinct vintage, sum(loan_balance)balance, count(promise_to_pay.id)counts, users.loan_officer, users.collections_officer from promise_to_pay inner join users on users.id = promise_to_pay.customer_id where promise_to_pay.transactiontime between '$filter_start_date' and '$filter_end_date' and stations = '$station' group by users.loan_officer, vintage");
								}
								else{
									$sql = mysql_query("select distinct vintage, sum(loan_balance)balance, count(promise_to_pay.id)counts, users.loan_officer, users.collections_officer from promise_to_pay inner join users on users.id = promise_to_pay.customer_id where promise_to_pay.transactiontime between '$filter_start_date' and '$filter_end_date' and users.loan_officer = '$loan_officer' and stations = '$station' group by users.loan_officer, vintage");
								}
								 
							}
							
							$intcount = 0;
							$total_loan_balance = 0;
							$total_payments = 0;
							$total_ratio = 0;
							while ($row = mysql_fetch_array($sql))
							{
								$intcount++;
								$vintage = $row['vintage'];
								$balance = $row['balance'];
								$counts = $row['counts'];
								$loan_officer = $row['loan_officer'];
								$collections_officer = $row['collections_officer'];
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
								
								if ($intcount % 2 == 0) {
									$display= '<tr bgcolor = #F0F0F6>';
								}
								else {
									$display= '<tr>';
								}
								echo $display;
								echo "<td valign='top'>$intcount.</td>";
								echo "<td valign='top'>$vintage</td>";
								echo "<td align='right' valign='top'>".number_format($balance, 2)."</td>";	
								echo "<td align='right' valign='top'>".number_format($counts, 2)."</td>";
								echo "<td valign='top'>$loan_officer_name</td>";
								echo "<td valign='top'>$collect_officer_name</td>";					
								echo "</tr>";
							}
							?>
						</tbody>
						<tfoot bgcolor="#E6EEEE">
							<tr>
								<th>#</th>
								<th>Vintage</th>
								<th>Balance</th>
								<th>Counts</th>
								<th>LO</th>
								<th>CO</th>
							</tr>
						</tfoot>
					</table>-->
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
							<tr >
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
