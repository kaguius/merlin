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
		$page_title = "Extract LO/ CO Pairs";
		include_once('includes/header.php');
		include_once('includes/db_conn.php');
		
		$filter_month = date("m");
		$filter_year = date("Y");
		$filter_day = date("d");
		$current_date = $filter_year.'-'.$filter_month.'-'.$filter_day;
		
		$report_term = 7;
		$start_report_date = date('Y-m-d',strtotime($current_date) - (24 * 3600 * $report_term));
			
		?>
		<div id="page">
			<div id="content">
				<div class="post">
					<h2><?php echo $page_title ?></h2>
					<p><strong>Report Range: <?php echo $current_date ?></strong></p>
					<table width="100%" border="0" cellspacing="2" cellpadding="2" class="display" id="example3">
					<thead bgcolor="#E6EEEE">
						<tr>
							<th>ID</th>
							<th>First Name</th>
							<th>Last Name</th>
							<th>Mobile No</th>
							<th>National ID</th>
							<th>Branch</th>
							<th>Market</th>
							<th>LO</th>
							<th>CO</th>
						</tr>
					</thead>
					<tbody>
					<?php
						if($station == '3'){
							$sql = mysql_query("select id, first_name, last_name, mobile_no, national_id, stations, market, loan_officer, collections_officer from users order by stations asc");
						}
						else{
							$sql = mysql_query("select id, first_name, last_name, mobile_no, national_id, stations, market, loan_officer, collections_officer from users where stations = '$station' order by stations asc");
						}
						$intcount = 0;
						while ($row = mysql_fetch_array($sql))
						{
							$intcount++;
							$id = $row['id'];
							$first_name = $row['first_name'];
							$last_name = $row['last_name'];
							$mobile_no = $row['mobile_no'];
							$national_id = $row['national_id'];
							$loan_officer = $row['loan_officer'];
							$collections_officer = $row['collections_officer'];
							$stations = $row['stations'];
							$market = $row['market'];
		
							$sql2 = mysql_query("select id, first_name, last_name from user_profiles where id = '$loan_officer'");
							while($row = mysql_fetch_array($sql2)) {
								$loan_first_name = $row['first_name'];
								$loan_last_name = $row['last_name'];
								$loan_officer_name = $loan_first_name." ".$loan_last_name;
							}
							$sql2 = mysql_query("select market from markets where id = '$market'");
							while($row = mysql_fetch_array($sql2)) {
								$market_name = $row['market'];
							}
							$sql2 = mysql_query("select id, first_name, last_name from user_profiles where id = '$collections_officer'");
							while($row = mysql_fetch_array($sql2)) {
								$collect_first_name = $row['first_name'];
								$collect_last_name = $row['last_name'];
								$collect_officer_name = $collect_first_name." ".$collect_last_name;
							}
							$sql2 = mysql_query("select stations from stations where id = '$stations'");
							while($row = mysql_fetch_array($sql2)) {
								$stations_name = $row['stations'];
							}
		
							if ($intcount % 2 == 0) {
								$display= '<tr bgcolor = #F0F0F6>';
							}
							else {
								$display= '<tr>';
							}
							echo $display;
	
							echo "<td valign='top'>$id</td>";
							echo "<td valign='top'>$first_name</td>";
							echo "<td valign='top'>$last_name</td>";	
							echo "<td valign='top'>$mobile_no</td>";	
							echo "<td valign='top'>$national_id</td>";
							echo "<td valign='top'>$stations_name</td>";
							echo "<td valign='top'>$market_name</td>";
							echo "<td valign='top'>$loan_officer_name</td>";	
							echo "<td valign='top'>$collect_officer_name</td>";	
							echo "</tr>";
			
							$id = '';
							$first_name = '';
							$last_name = '';
							$mobile_no = '';
							$national_id = '';
							$stations_name = '';
							$market_name = '';
							$loan_officer_name = '';
							$collect_officer_name = '';
						}
						?>
					</tbody>
					<tfoot bgcolor="#E6EEEE">
						<tr>
							<th>ID</th>
							<th>First Name</th>
							<th>Last Name</th>
							<th>Mobile No</th>
							<th>National ID</th>
							<th>Branch</th>
							<th>Market</th>
							<th>LO</th>
							<th>CO</th>
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
			</div>
			<br class="clearfix" />
			</div>
		</div>
		</div>
<?php
	}
	include_once('includes/footer.php');
?>
