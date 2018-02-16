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
		include_once('includes/db_conn.php');
		$transactiontime = date("Y-m-d G:i:s");
		$page_title = "Lead Listing";
		include_once('includes/header.php');
		//echo $title;
		?>		
		<div id="page">
			<div id="content">
				<div class="post">
				<h2><font color="#000A8B"><?php echo $page_title; ?>:</font> Overview</h2>
				<?php if($title != 3){ ?>
					+ <a href="lead_details.php">Add a new Lead</a><br />
				<?php } ?>
				Select a Report: <a href="leads_report.php?report_title=due_today">Leads for today</a> | <a href="leads_report.php?report_title=due_tomorrow">Leads for tomorrow</a> | <a href="reports/leads.php">Leads Report</a>
				
				<table width="100%" border="0" cellspacing="2" cellpadding="2" class="display" id="example">
					<thead bgcolor="#E6EEEE">
						<tr bgcolor='#fff'>
							<th>#</th>
							<th>Name</th>
							<th>Mobile No</th>
							<th>Next Visit</th>
							<th>Date Created</th>
							<th>Edit</th>
							<th>Convert</th>
						</tr>
					</thead>
					<tbody>
					<?php
						if($station == '3'){
							$sql = mysql_query("select id, title, first_name, last_name, next_visit, mobile_no, marital, date_of_birth, alt_phone, stations, status, transactiontime from users where national_id = '' order by next_visit desc");
						}
						else{
							if($title == '3'){
								$sql = mysql_query("select id, title, first_name, last_name, next_visit, mobile_no, marital, date_of_birth, alt_phone, stations, status, transactiontime from users where stations = '$station' and national_id = '' order by next_visit desc");
							}
							if($title == '1'){
								$sql = mysql_query("select id, title, first_name, last_name, next_visit, mobile_no, marital, date_of_birth, alt_phone, stations, status, transactiontime from users where stations = '$station' and national_id = '' and loan_officer = '$userid' order by next_visit desc");
							}
							else{
								$sql = mysql_query("select id, title, first_name, last_name, next_visit, mobile_no, marital, date_of_birth, alt_phone, stations, status, transactiontime from users where stations = '$station' and national_id = '' and collections_officer = '$userid' order by next_visit desc");
							}
						}
						while ($row = mysql_fetch_array($sql))
						{
							$intcount++;
							$id = $row['id'];					
							$first_name = $row['first_name'];
							$first_name = ucwords(strtolower($first_name));
							$last_name = $row['last_name'];
							$last_name = ucwords(strtolower($last_name));
							$mobile_no = $row['mobile_no'];
							$next_visit = $row['next_visit'];
							$transactiontime = $row['transactiontime'];
							
							if ($intcount % 2 == 0) {
								//$display= '<tr bgcolor = #F0F0F6>';
								$display= '<tr>';
							}
							else {
								$display= '<tr>';
							}
							echo $display;
							echo "<td valign='top'>$intcount.</td>";
							echo "<td valign='top'>$first_name $last_name</td>";
							echo "<td valign='top'>$mobile_no</td>";
							echo "<td valign='top'>$next_visit</td>";
							echo "<td valign='top'>$transactiontime</td>";
							echo "<td valign='top' align='center'><a href='lead_details.php?user_id=$id&mode=edit'><img src='images/edit.png' width='35px'></a></td>";
							echo "<td valign='top' align='center'><a href='customer_details.php?user_id=$id&mode=edit&status=new'><img src='images/active.png' width='35px'></a></td>";
							echo "</tr>";
						}
						?>
					</tbody>
					<tfoot bgcolor="#E6EEEE">
						<tr>
							<th>#</th>
							<th>Name</th>
							<th>Mobile No</th>
							<th>Next Visit</th>
							<th>Date Created</th>
							<th>Edit</th>
							<th>Convert</th>
						</tr>
					</tfoot>
				</table>
			</div>
			</div>
			<br class="clearfix" />
			</div>
		</div>
<?php
	}
	include_once('includes/footer.php');
?>
