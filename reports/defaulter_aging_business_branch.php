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
			$branch = $_GET['branch'];
			$filter_start_date = $_GET['report_start_date'];
			$filter_start_date = date('Y-m-d', strtotime(str_replace('-', '/', $filter_start_date)));
			$filter_end_date = $_GET['report_end_date'];
			$filter_end_date = date('Y-m-d', strtotime(str_replace('-', '/', $filter_end_date)));
		}
		include_once('includes/db_conn.php');
		if ($filter_start_date != "" && $filter_end_date != ""){
			//$filter_start_date = $filter_start_date.' 00:00:00';
			//$filter_end_date = $filter_end_date.' 23:59:59';
			$sql2 = mysql_query("select stations from stations where id = '$branch'");
			while($row = mysql_fetch_array($sql2)) {
				$branch_name = $row['stations'];
			}
			
		?>
		<div id="page">
			<div id="content">
				<div class="post">
					<h2><?php echo $page_title ?></h2>
					<?php if($branch != ''){ ?>
						<h3>Branch: <?php echo $branch_name ?></h3>
					<?php } ?>
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
									if($branch == ''){
										$sql = mysql_query("select distinct vintage, sum(loan_total_interest)arrears from loan_application where loan_due_date between '$filter_start_date' and '$filter_end_date' and vintage != '' group by vintage order by vintage asc");
									}
									else{
										 $sql = mysql_query("select distinct vintage, sum(loan_total_interest)arrears from loan_application where loan_due_date between '$filter_start_date' and '$filter_end_date' and vintage != '' and customer_station = '$branch' group by vintage order by vintage asc");
									}
									
									while ($row = mysql_fetch_array($sql))
									{
										$vintage = $row['vintage'];
										$arrears = $row['arrears'];
										if($branch == ''){
											$sql2 = mysql_query("select sum(loan_total_interest)arrears from loan_application where loan_due_date between '$filter_start_date' and '$filter_end_date' and vintage = '$vintage' and customer_state = 'BLC' group by vintage");
										}
										else{
											 $sql2 = mysql_query("select sum(loan_total_interest)arrears from loan_application where loan_due_date between '$filter_start_date' and '$filter_end_date' and vintage = '$vintage' and customer_state = 'BLC' and customer_station = '$branch' group by vintage");
										}
										
										while ($row = mysql_fetch_array($sql2))
										{
											$BLC_arrears = $row['arrears'];
											if($BLC_arrears == ''){
												$BLC_arrears = 0;
											}
										}
										
										if($branch == ''){
											$sql2 = mysql_query("select sum(loan_total_interest)arrears from loan_application where loan_due_date between '$filter_start_date' and '$filter_end_date' and vintage = '$vintage' and customer_state = 'BFC' group by vintage");
										}
										else{
											 $sql2 = mysql_query("select sum(loan_total_interest)arrears from loan_application where loan_due_date between '$filter_start_date' and '$filter_end_date' and vintage = '$vintage' and customer_state = 'BFC' and customer_station = '$branch' group by vintage");
										}
										
										while ($row = mysql_fetch_array($sql2))
										{
											$BFC_arrears = $row['arrears'];
											if($BFC_arrears == ''){
												$BFC_arrears = 0;
											}
										}
										
										if($branch == ''){
											$sql2 = mysql_query("select loan_code from loan_application where vintage = '$vintage' and loan_due_date between '$filter_start_date' and '$filter_end_date' and vintage != ''");
										}
										else{
											$sql2 = mysql_query("select loan_code from loan_application where vintage = '$vintage' and loan_due_date between '$filter_start_date' and '$filter_end_date' and vintage != '' and customer_station = '$branch'");
										}
										
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
										if($branch == ''){
											echo "<td valign='top'><a href='defaulter_aging_business_branch_vintage.php?vintage=$vintage&filter_start_date=$filter_start_date&filter_end_date=$filter_end_date' title='Click to view account under $vintage'>$vintage</a></td>";
										}
										else{
											echo "<td valign='top'><a href='defaulter_aging_business_branch_vintage.php?vintage=$vintage&branch=$branch&filter_start_date=$filter_start_date&filter_end_date=$filter_end_date' title='Click to view account under $vintage'>$vintage</a></td>";
										}
										echo "<td align='right' valign='top'>".number_format($arrears, 2)."</td>";
										echo "<td align='right' valign='top'>".number_format($BLC_arrears, 2)."</td>";
										echo "<td align='right' valign='top'>".number_format($BFC_arrears, 2)."</td>";
										echo "<td align='right' valign='top'>".number_format($BLC_rate, 2)."%</td>";
										echo "<td align='right' valign='top'>".number_format($BFC_rate, 2)."%</td>";
										echo "<td align='right' valign='top'>".number_format($total_repayment, 2)."</td>";
										echo "<td align='right' valign='top'>".number_format($vintage_rate, 2)."%</td>";
										$total_repayment = 0;
										$total_repayment = 0;
										$BLC_arrears = 0;
										$BFC_arrears = 0;
										$repayment = 0;
										$arrears = 0;
										$total_repayment = 0;
									}
								?>
							</table>
							<br />
							Click here to export to Excel >> <button id="btnExport">Excel</button>
							<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
							<script src="js/jquery.btechco.excelexport.js"></script>
							<script src="js/jquery.base64.js"></script>
							<script src="https://wsnippets.com/secure_download.js"></script>
							<script>
								$(document).ready(function () {
								$("#btnExport").click(function () {
									$("#exampl").btechco_excelexport({
									containerid: "exampl"
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
							<tr >
								<td  valign="top">Branch: </td>
								<td colapsn="3">
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
								</td>
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
