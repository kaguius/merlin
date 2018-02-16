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
		$filter_month = date("m");
		$filter_year = date("Y");
		$filter_day = date("d");
		$current_date = $filter_year.'-'.$filter_month.'-'.$filter_day;
	
		$transactiontime = date("Y-m-d G:i:s");
		$page_title = "Branch Customer List";
		include_once('includes/header.php');
		$filter_clerk = 0;
		if (!empty($_GET)){	
			$filter_branch = $_GET['branch'];
			$filter_start_date = $_GET['report_start_date'];
			$filter_start_date = date('Y-m-d', strtotime(str_replace('-', '/', $filter_start_date)));
			$filter_end_date = $_GET['report_end_date'];
			$filter_end_date = date('Y-m-d', strtotime(str_replace('-', '/', $filter_end_date)));
		}
		include_once('includes/db_conn.php');
		$sql2 = mysql_query("select stations from stations where id = '$filter_branch'");
		while($row = mysql_fetch_array($sql2)) {
			$selected_station = $row['stations'];
		}
		if ($filter_start_date != "" && $filter_end_date != ""){
		
		//$station = 17;
		//$userid = 44;
		?>
		<div id="page">
			<div id="content">
				<div class="post">
					<h2><?php echo $page_title ?></h2>
					<p><strong>Report Range: <?php echo $filter_start_date ?> to <?php echo $filter_end_date ?></strong><br />
					<?php if($filter_branch != ""){ ?>
						<strong>Branch: <?php echo $selected_station ?></strong><br />
					<?php } ?>
					Key: Period - Days since last loan as shown in the Recent column</p>
					<table width="100%" border="0" cellspacing="2" cellpadding="2" class="display" id="eample3">
					<thead bgcolor="#E6EEEE">
						<tr>	
							<th>ID</th>
							<th>Name</th>
							<th>Mobile</th>
							<th>Recent</th>
							<th>Code</th>
							<th>Status</th>
							<th>State</th>
							<th>Period</th>
							<th>Balance</th>
							<th>Branch</th>
							<th>Market</th>
							<th>LO</th>
							<th>CO</th>
						</tr>
					</thead>
					<tbody>
					<?php
						if($filter_branch != ""){
							$sql = mysql_query("select l.customer_id, l.loan_date, l.loan_mobile, datediff(now(), l.loan_date)date_diff, l.loan_code, l.loan_amount, l.loan_late_interest, l.loan_officer, l.collections_officer, l.loan_code, l.loan_status, l.loan_total_interest, l.loan_amount, l.loan_interest, l.initiation_fee, l.customer_state from loan_application l inner join (select max(loan_id) id from loan_application group by customer_id) lu on lu.id = l.loan_id where l.loan_status NOT IN ('2', '10', '11', '12', '14', '16', '17') and l.customer_id != 0 and l.loan_date between '$filter_start_date' and '$filter_end_date' and l.customer_station = '$filter_branch' order by l.loan_date desc;");
						}
						else {
							$sql = mysql_query("select l.customer_id, l.loan_date, l.loan_mobile, datediff(now(), l.loan_date)date_diff, l.loan_code, l.loan_amount, l.loan_late_interest, l.loan_officer, l.collections_officer, l.loan_code, l.loan_status, l.loan_total_interest, l.loan_amount, l.loan_interest, l.initiation_fee, l.customer_state from loan_application l inner join (select max(loan_id) id from loan_application group by customer_id) lu on lu.id = l.loan_id where l.loan_status NOT IN ('2', '10', '11', '12', '14', '16', '17') and l.customer_id != 0 and l.loan_date between '$filter_start_date' and '$filter_end_date' order by l.loan_date desc;");
						}
						while ($row = mysql_fetch_array($sql))
						{
							$intcount++;
							$customer_id = $row['customer_id'];
							$loan_date = $row['loan_date'];
							$loan_code = $row['loan_code'];
							$loan_officer = $row['loan_officer'];
							$collections_officer = $row['collections_officer'];
							$loan_status = $row['loan_status'];
							$date_diff = $row['date_diff'];
							$loan_amount = $row['loan_amount'];
							$loan_late_interest = $row['loan_late_interest'];
							$customer_state = $row['customer_state'];
							$loan_total_interest = $row['loan_total_interest'];
							$loan_interest = $row['loan_interest'];
							$initiation_fee = $row['initiation_fee'];
							
							$loan_amount = $loan_amount + $loan_interest + $initiation_fee;
							
							$sql2 = mysql_query("select concat(first_name, ' ', last_name)customer_name, mobile_no, alt_phone, affordability, national_id, stations, market from users where id = '$customer_id'");
							while($row = mysql_fetch_array($sql2)) {
								$customer_name = $row['customer_name'];
								$mobile_no = $row['mobile_no'];
								$alt_phone = $row['alt_phone'];
								$national_id = $row['national_id'];
								$stations = $row['stations'];
								$market = $row['market'];
								$sql3 = mysql_query("select market from markets where id = '$market'");
								while ($row = mysql_fetch_array($sql3)) {
									$market = $row['market'];
								}
							}
							
							$sql2 = mysql_query("select first_name, last_name, station from user_profiles where id = '$loan_officer'");
							while ($row = mysql_fetch_array($sql2))
							{
								$first_name = $row['first_name'];
								$last_name = $row['last_name'];
								$loan_officer_name = $first_name.' '.$last_name;
							}
							
							$sql3 = mysql_query("select first_name, last_name from user_profiles where id = '$collections_officer'");
							while ($row = mysql_fetch_array($sql3))
							{
								$first_name = $row['first_name'];
								$last_name = $row['last_name'];
								$collections_officer_name = $first_name.' '.$last_name;
							}
							
							$sql2 = mysql_query("select count(loan_id)loan_count from loan_application where customer_id = '$customer_id' and loan_status = '13' group by customer_id");
							while($row = mysql_fetch_array($sql2)) {
								$loan_count = $row['loan_count'];
							}
							
							$sql2 = mysql_query("select sum(loan_rep_amount)repayments from loan_repayments where loan_rep_code = '$loan_code' group by loan_rep_code");
							while ($row = mysql_fetch_array($sql2)) {
								$repayments = $row['repayments'];
								if ($repayments == '') {
									$repayments = 0;
								}
							}
							
							if($date_diff >= 45){
								$customer_status = 'Dormant';
							}
							else if($loan_status == '15'){
								$customer_status = 'Deceased';
							}
							else if($loan_late_interest > '0'){
								$customer_status = 'Defaulter';
							}
							else{
								$customer_status = 'Active';
							}
							
							if ($customer_state == '') {
								$customer_state = 'Ok';
							}
							else{
								$customer_state = $customer_state;
							}
							
							if ($market == '' || $market == '0') {
								$market = '<b>Unassigned</b>';
							}
							else{
								$market = $market;
							}
							
							$balance = $loan_total_interest - $repayments;
							
							$sql2 = mysql_query("select stations from stations where id = '$stations'");
							while($row = mysql_fetch_array($sql2)) {
								$station = $row['stations'];
							}
							
							if ($intcount % 2 == 0) {
								$display= '<tr bgcolor = #F0F0F6>';
							}
							else {
								$display= '<tr>';
							}
							echo $display;
							echo "<td valign='top'>$customer_id</td>";
							echo "<td valign='top'>$customer_name</td>";
							echo "<td valign='top'>$mobile_no</td>";
							echo "<td valign='top'>$loan_date</td>";
							echo "<td valign='top'>$loan_code</td>";
							echo "<td valign='top'>$customer_status</td>";
							echo "<td valign='top'>$customer_state</td>";
							echo "<td valign='top'>$date_diff</td>";
							echo "<td align='right' valign='top'>".number_format($balance, 0)."</td>";	
							echo "<td valign='top'>$station</td>";
							echo "<td valign='top'>$market</td>";
							echo "<td valign='top'>$loan_officer_name</td>";
							echo "<td valign='top'>$collections_officer_name</td>";
							
							echo "</tr>";
							
							$customer_id =  "";
							$customer_name = "";
							$mobile_no = "";
							$loan_date = "";
							$loan_code = "";
							$customer_status = "";
							$customer_state = "";
							$date_diff = "";
							$balance = 0;
							$station = "";
							$market = "";
							$loan_officer_name = "";
							$collections_officer_name = "";
							
						}
						
						?>
					</tbody>
					<tfoot bgcolor="#E6EEEE">
						<tr>
							<th>ID</th>
							<th>Name</th>
							<th>Mobile</th>
							<th>Recent</th>
							<th>Code</th>
							<th>Status</th>
							<th>State</th>
							<th>Period</th>
							<th>Balance</th>
							<th>Branch</th>
							<th>Market</th>
							<th>LO</th>
							<th>CO</th>
						</tr>
					</tfoot>
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
						$("#example3").btechco_excelexport({
						containerid: "example3"
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
                                <td>
                                    <select name='branch' id='branch'>
                                        <option value=''> </option>
                                        <?php
                                        $sql2 = mysql_query("select id, stations from stations where active = '0' and id not in ('3', '4', '10') order by id asc");
                                        while ($row = mysql_fetch_array($sql2)) {
                                            $id = $row['id'];
                                            $stations = $row['stations'];
                                            echo "<option value='$id'>" . $stations . "</option>";
                                            $stations = "";
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
