<?php
	$userid = "";
	$adminstatus = 4;
	$property_manager_id = "";
	session_start();
	if (!empty($_SESSION)){
		$userid = $_SESSION["userid"] ;
		$adminstatus = $_SESSION["adminstatus"] ;
		$username = $_SESSION["username"];
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
		include_once('includes/db_conn.php');
		//include_once('includes/db_conn_dialer.php');
		
		$transactiontime = date("Y-m-d G:i:s");
		$page_title = "Arrears Management";
		include_once('includes/header.php');
		
		$filter_month = date("m");
		$filter_year = date("Y");
		$filter_day = date("d");
		$current_date = $filter_year.'-'.$filter_month.'-'.$filter_day;
		
		$assignment = "";
		$tenant_rent_paid = array();
		
		$filter_clerk = 0;
		if (!empty($_GET)){	
			$filter_clerk = $_GET['clerk'];
			$filter_start_date = $_GET['report_start_date'];
			$filter_start_date = date('Y-m-d', strtotime(str_replace('-', '/', $filter_start_date)));
		}
		//$station = 4;
		?>
		<div id="page">
			<div id="content">
				<div class="post">
					<h2><?php echo $page_title ?></h2>
					<form id="frmCreatePropertyItem" name="frmCreatePropertyItem" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
					<table width="100%" border="0" cellspacing="2" cellpadding="2" class="display" id="exampl">
					<thead bgcolor="#E6EEEE">
						<tr>
							<th>Code</th>
							<th>Name</th>
							<th>Branch</th>
							<th>Mobile</th>
							<th>Due</th>
							<th>Amount</th>
							<th>Repayments</th>
							<th>Balance</th>
							<th>Status</th>
							<th>Assigned</th>
							<th>Assign</th>
						</tr>
					</thead>
					<tbody>
					<?php
						//$sql = mysql_query("select loan_id, loan_code, customer_id, customer_station, loan_mobile, loan_date, loan_due_date, loan_total_interest, loan_status, late_status, collections_agent, field_agent, vintage, arrears_assigned from loan_application where field_agent = '0' and loan_due_date !='' and customer_state = 'BFC' group by loan_code order by loan_id asc", $dbh1);

						$sql = mysql_query("select loan_id, loan_code, customer_id, customer_station, loan_mobile, loan_date, loan_due_date, loan_total_interest, loan_status, late_status, collections_agent, field_agent, vintage, arrears_assigned, edc from loan_application where late_status != 0 and late_status != '1' and collections_agent = '0' and edc = '0' and field_agent = '0' and loan_due_date !='' and loan_status != '13' and loan_status != '2' and loan_status != '12' and loan_status != '14' and loan_status != '11' group by loan_code order by loan_id asc", $dbh1);
						 $intcount = 0;
						 $total_loan_rep_amount = 0;
						 while ($row = mysql_fetch_array($sql))
						 {
							$intcount++;
							$loan_id = $row['loan_id'];
							$loan_code = $row['loan_code'];
							$customer_id = $row['customer_id'];
							$arrears_assigned = $row['arrears_assigned'];
							$loan_mobile = $row['loan_mobile'];
							$loan_mobile = substr($loan_mobile, 3);
							$loan_date = $row['loan_date'];
							$loan_due_date = $row['loan_due_date'];
							$loan_due_date = date("d M, Y", strtotime($loan_due_date));
							$loan_total_interest = $row['loan_total_interest'];
							$late_status = $row['late_status'];
							$loan_status = $row['loan_status'];
							$customer_station = $row['customer_station'];
							$vintage = $row['vintage'];
							
							$sql2 = mysql_query("select id, stations from stations where id = '$customer_station'", $dbh1);
							while($row = mysql_fetch_array($sql2)) {
								$stations = $row['stations'];
								$stations = ucwords(strtolower($stations));
							}
							
							$sql2 = mysql_query("select transactiontime from promise_to_pay where loan_code = '$loan_code' order by id desc limit 1", $dbh1);
							while($row = mysql_fetch_array($sql2)) {
								$last_contacted = $row['transactiontime'];
							}
							
							$sql2 = mysql_query("select sum(loan_rep_amount)repayments from loan_repayments where loan_rep_code = '$loan_code' group by loan_rep_code", $dbh1);
							while ($row = mysql_fetch_array($sql2))
							{
								$repayments = $row['repayments'];
								//if($repayments == ""){
								if(is_null($repayments)){
									$repayments = 0;
								}
							}
							
							$balance = $loan_total_interest - $repayments;
							
							$date1 = strtotime($loan_due_date);
							$date2 = strtotime($filter_start_date);
							$dateDiff = $date2 - $date1;
							$days = floor($dateDiff/(60*60*24));
							
							$sql2 = mysql_query("select first_name, last_name from users where id = '$customer_id'", $dbh1);
							while ($row = mysql_fetch_array($sql2))
							{
								$first_name = $row['first_name'];
								$last_name = $row['last_name'];
								$first_name = ucwords(strtolower($first_name));	
								$last_name = ucwords(strtolower($last_name));
								$name = $first_name.' '.$last_name;		
							}
							
							$sql2 = mysql_query("select status from customer_status where id = '$loan_status'", $dbh1);
							while ($row = mysql_fetch_array($sql2))
							{
								$status = $row['status'];
							}
							
							if($late_status == '3'){
								$late_status_name = 'EDC';
							}
							else if($late_status == '2'){
								$late_status_name = 'CC';
							}
							else if($late_status == '4'){
								$late_status_name = 'Calls';
							}
							else if($late_status == '5'){
								$late_status_name = 'Field Visits';
							}
							else if($late_status == '6'){
								$late_status_name = 'Write Off';
							}
							else if($late_status == '1'){
								$late_status_name = 'Branch';
							}
							if($balance > 0){
								if ($intcount % 2 == 0) {
									$display= '<tr bgcolor = #F0F0F6>';
								}
								else {
									$display= '<tr>';
								}
								echo $display;
								echo "<td valign='top'>$loan_code</td>";
								echo "<td valign='top'>$name</td>";
								echo "<td valign='top'>$stations</td>";
								echo "<td valign='top'>$loan_mobile</td>";
								echo "<td valign='top'>$loan_due_date</td>";		
								echo "<td valign='top' align='right'>".number_format($loan_total_interest, 2)."</td>";
								echo "<td valign='top' align='right'>".number_format($repayments, 2)."</td>";
								echo "<td valign='top' align='right'>".number_format($balance, 2)."</td>";
								
								echo "<input type='hidden' id='loan_code[$loan_id]' name='loan_code[$loan_id]' value='$loan_code'>";
								echo "<td valign='top'>$status</td>";
								echo "<td valign='top'>$arrears_assigned</td>";	
								echo "<td valign='top'>";
								echo "<input type='checkbox' name='assign[$loan_id]' id='assign[$loan_id]' value='$loan_id'>";
								echo "</td>";
								echo "</tr>";
							}
							$loan_total_interest = 0;
							$repayments = 0;
							$balance = 0;
							$last_contacted = "";
							$loan_code = "";
							echo "<input type='hidden' id='loan' name='loan' value='$loan_id'>";
						}
						if($station == '3'){
							echo "<tr>";
								echo "Field Agent: <select name='edc' id='edc'>";
									echo "<option value=''> </option>";							
									$sql2 = mysql_query("select user_profiles.id, first_name, last_name, stations.stations from user_profiles inner join stations on stations.id = user_profiles.station where title = '10' and user_status = '1' order by first_name asc", $dbh1);
									while($row = mysql_fetch_array($sql2)) {
										$loan = $row['id'];
										$first_name = $row['first_name'];
										$last_name = $row['last_name'];
										echo "<option value='$loan'>".$first_name." ".$last_name."</option>"; 
									}
								echo "</select>";
							echo "</tr>";
						}
						?>
					</tbody>
					<tfoot bgcolor="#E6EEEE">
						<tr>
							<th>Code</th>
							<th>Name</th>
							<th>Branch</th>
							<th>Mobile</th>
							<th>Due</th>
							<th>Amount</th>
							<th>Repayments</th>
							<th>Balance</th>
							<th>Status</th>
							<th>Assigned</th>
							<th>Assign</th>
						</tr>
					</tfoot>
				</table>
				<table border="0" width="100%">
					<tr>
						<td valign="top">
							<button name="btnNewCard" id="button">Submit</button>
						</td>
						<td align="right">
							<button name="reset" id="button2" type="reset">Reset</button>
						</td>		
					</tr>
				</table>
				</form>
				</div>
			</div>
			<br class="clearfix" />
			</div>
		</div>
<?php
		if (!empty($_POST)) {
			$edc = $_POST['edc'];
			$loan_code = $_POST['loan_code'];
			$assign = $_POST['assign'];
			
			$loan = $_POST['loan'];
			
			for($assignment = 1 ;  $assignment <= $loan; $assignment++) {	
				if ($assign[$assignment] != 0) {
					$assign_unit = $assign[$assignment];
					
					if($edc != ""){
						$sql15="update loan_application set field_agent='$edc', assigned_field_agent = '$current_date' where loan_id = '$assign_unit'";
								
		   				//echo $sql15."<br />";
						$result = mysql_query($sql15, $dbh1);  
					}
				}
			}
			$query = "arrears_assign_FA.php";
			?>
			<script type="text/javascript">
				<!--
				/*alert("Either the Email Address or the Password do not match the records in the database or you have been disabled from the system, please contact the system admin at www.e-kodi.com/contact.php");*/
				document.location = "<?php echo $query ?>";
				//-->
			</script>
			<?php
		}
	}
	include_once('includes/footer.php');
?>
