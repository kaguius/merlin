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
		$page_title = "Dormant Customers Report";
		include_once('includes/header.php');
		$filter_clerk = 0;
		if (!empty($_GET)){	
			$filter_clerk = $_GET['clerk'];
			$filter_start_date = $_GET['report_start_date'];
			$filter_start_date = date('Y-m-d', strtotime(str_replace('-', '/', $filter_start_date)));
			$filter_end_date = $_GET['report_end_date'];
			$filter_end_date = date('Y-m-d', strtotime(str_replace('-', '/', $filter_end_date)));
		}
		include_once('includes/db_conn.php');	
		
		//$station = 17;
		//$userid = 44;
		?>
		<div id="page">
			<div id="content">
				<div class="post">
					<h2><?php echo $page_title ?></h2>
					<table width="100%" border="0" cellspacing="2" cellpadding="2" class="display" id="example3">
					<thead bgcolor="#E6EEEE">
						<tr>
							<th>Name</th>
							<th>Mobile</th>
							<th>National ID</th>
							<th>Alt Phone</th>
							<th>Affordability</th>
							<th>LO</th>
							<th>CO</th>
							<th>Business</th>
							<th>Loan Date</th>
						</tr>
					</thead>
					<tbody>
					<?php
						if($station == '3'){
							//$sql = mysql_query("select distinct customer_id, loan_date, concat(first_name, ' ', last_name)customer_name, mobile_no, count(loan_id)counts, alt_phone, affordability, loan_application.loan_officer, loan_application.collections_officer, national_id, loan_status from loan_application inner join users on users.id = loan_application.customer_id group by customer_id order by loan_date desc");
							$sql = mysql_query("select l.customer_id, l.loan_date, datediff(now(), l.loan_date)date_diff, l.loan_code, l.loan_officer, l.collections_officer, l.loan_status from loan_application l inner join (select max(loan_id) id from loan_application group by customer_id) lu on lu.id = l.loan_id where l.customer_id !=0 and l.loan_status = 13 order by l.loan_date desc;");
						}
						else{
							 $sql = mysql_query("select l.customer_id, l.loan_date, datediff(now(), l.loan_date)date_diff, l.loan_code, l.loan_officer, l.collections_officer, l.loan_status from loan_application l inner join (select max(loan_id) id from loan_application group by customer_id) lu on lu.id = l.loan_id where l.customer_id !=0 and l.loan_status = 13 and l.customer_station = '$station' order by l.loan_date desc;");
						}
						
						while ($row = mysql_fetch_array($sql))
						{
							$intcount++;
							$customer_id = $row['customer_id'];
							$loan_date = $row['loan_date'];
							$loan_officer = $row['loan_officer'];
							$collections_officer = $row['collections_officer'];
							$loan_status = $row['loan_status'];
							$date_diff = $row['date_diff'];
							
							$sql2 = mysql_query("select concat(first_name, ' ', last_name)customer_name, mobile_no, alt_phone, affordability, national_id from users where id = '$customer_id'");
							while($row = mysql_fetch_array($sql2)) {
								$customer_name = $row['customer_name'];
								$mobile_no = $row['mobile_no'];
								$alt_phone = $row['alt_phone'];
								$affordability = $row['affordability'];
								$national_id = $row['national_id'];
							}
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
							
							if($date_diff > 30){
								if ($intcount % 2 == 0) {
									$display= '<tr bgcolor = #F0F0F6>';
								}
								else {
									$display= '<tr>';
								}
								echo $display;
							
								echo "<td valign='top'>$customer_name</td>";
								echo "<td valign='top'>$mobile_no</td>";
								echo "<td valign='top'>$national_id</td>";	
								echo "<td valign='top'>$alt_phone</td>";	
								echo "<td valign='top' align='right'>".number_format($affordability, 2)."</td>";	
								echo "<td valign='top'>$loan_officer_name</td>";
								echo "<td valign='top'>$collect_officer_name</td>";	
								echo "<td valign='top'><a href='business_details.php?user_id=$id&mode=edit'><img src='images/folder-horizontal.png' width='35px'></a></td>";
								echo "<td valign='top'>$loan_date</td>";	
								echo "</tr>";
							}
						}
						?>
					</tbody>
					<tfoot bgcolor="#E6EEEE">
						<tr>
							<th>Name</th>
							<th>Mobile</th>
							<th>National ID</th>
							<th>Alt Phone</th>
							<th>Affordability</th>
							<th>LO</th>
							<th>CO</th>
							<th>Business</th>
							<th>Loan Date</th>
						</tr>
					</tfoot>
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
	include_once('includes/footer.php');
?>
