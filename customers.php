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
		$page_title = "Customer Listing";
		include_once('includes/header.php');
		$sql = mysql_query("select count(users_id)user_count from users where name != ''");
		while ($row = mysql_fetch_array($sql))
		{
			$user_count = $row['user_count'];		
		}
		$sql = mysql_query("select count(distinct loan_mobile)clients from loan_application where EXTRACT(Year FROM loan_date) = '2014' group by EXTRACT(Month FROM loan_date)");
		$active_user_count = 0;
		$total_active_user_count = 0;
		while ($row = mysql_fetch_array($sql))
		{
			$active_user_count = $row['clients'];	
			$total_active_user_count = $total_active_user_count + $active_user_count;	
		}
		?>		
		<div id="page">
			<div id="content">
				<div class="post">
				<h2><font color="#000A8B"><?php echo $page_title; ?>:</font> Overview</h2>
				
				<?php if($title == 1 || $title == 2 || $title == 4){ ?>
					<p>+ <a href="customer_details.php">Add a new Customer</a></p>
				<?php } ?>
				<table width="100%" border="0" cellspacing="2" cellpadding="2" class="display" id="example">
					<thead bgcolor="#E6EEEE">
						<tr bgcolor='#fff'>
							<th>#</th>
							<th>Name</th>
							<th>ID</th>
							<th>Branch</th>
							<th>Mobile No</th>
							<th>Marital</th>
							<th>DOB</th>
							<th>Status</th>
							<th>Business</th>
							<th>Map</th>
							<th>Issues</th>
							<?php if($title == 1 || $title == 2 || $title == 3 || $title == 4 || $title == 8){ ?>
							<th>Edit</th>
							<?php } ?>
						</tr>
					</thead>
					<tbody>
					<?php
					if($station == '3'){
				    	$sql = mysql_query("select id, title, first_name, last_name, national_id, mobile_no, marital, date_of_birth, alt_phone, stations, status, passportfileupload, resumefileupload from users where national_id != '' order by id desc limit 200");
				    }
				    else if($station == '4'){
						$sql = mysql_query("select id, title, first_name, last_name, national_id, mobile_no, marital, date_of_birth, alt_phone, stations, status, passportfileupload, resumefileupload from users where collections_agent = '$userid' and national_id != '' order by id desc limit 200");
					}
					//else if($title == '1'){
					//	$sql = mysql_query("select id, title, first_name, last_name, national_id, mobile_no, marital, date_of_birth, alt_phone, stations, status, passportfileupload, resumefileupload from users where collections_agent = '$userid' and national_id != '' and loan_officer = '$userid' order by id desc limit 200");
					//}
					//else if($title == '2'){
					//$sql = mysql_query("select id, title, first_name, last_name, national_id, mobile_no, marital, date_of_birth, alt_phone, stations, status, passportfileupload, resumefileupload from users where collections_agent = '$userid' and national_id != '' and collections_officer = '$userid' order by id desc limit 200");
					//}
				    else{
				    	$sql = mysql_query("select id, title, first_name, last_name, national_id, mobile_no, marital, date_of_birth, alt_phone, stations, status, passportfileupload, resumefileupload from users where stations = '$station' and national_id != '' order by id desc limit 200");
				    }
						 while ($row = mysql_fetch_array($sql))
						 {
							$intcount++;
							$id = $row['id'];					
							$first_name = $row['first_name'];
							$first_name = ucwords(strtolower($first_name));
							$last_name = $row['last_name'];
							$last_name = ucwords(strtolower($last_name));
							$trans_date = $row['trans_date'];
							$mobile_no = $row['mobile_no'];
							$national_id = $row['national_id'];
							$marital = $row['marital'];
							$date_of_birth = $row['date_of_birth'];
							$alt_phone = $row['alt_phone'];
							$stations = $row['stations'];
							$status = $row['status'];
							$passportfileupload = $row['passportfileupload'];
							$resumefileupload = $row['resumefileupload'];
							if($status == '0'){
								$status_name = 'Active';
							}
							$sql2 = mysql_query("select id, marital from marital where id = '$marital'");
							while($row = mysql_fetch_array($sql2)) {
								$marital = $row['marital'];
							}
							$sql2 = mysql_query("select id, status from loan_declined_status_codes where id = '$status'");
							while($row = mysql_fetch_array($sql2)) {
								$status_name = $row['status'];
								if($status_name == ""){
									$status_name = 'Active';
								}
							}
							$sql2 = mysql_query("select id, stations from stations where id = '$stations'");
							while($row = mysql_fetch_array($sql2)) {
								$stations = $row['stations'];
								$stations = ucwords(strtolower($stations));
							}
							
							if($passportfileupload == "" || $resumefileupload == ""){
								$issues = '<font color="red">Photo and ID missing</font>';
							}
							else{
								$issues = '<font color="green">None, Good to go</font>';
							}
							
							if ($intcount % 2 == 0) {
								$display= '<tr bgcolor = #F0F0F6>';
							}
							else {
								$display= '<tr>';
							}
							echo $display;
							echo "<td valign='top'>$intcount.</td>";
							echo "<td valign='top'>$first_name $last_name</td>";
							echo "<td valign='top'>$national_id</td>";
							echo "<td valign='top'>$stations</td>";
							echo "<td valign='top'>$mobile_no</td>";
							echo "<td valign='top'>$marital</td>";
							echo "<td valign='top'>$date_of_birth</td>";
							echo "<td valign='top'>$status_name</td>";
							if($status == '0'){
								echo "<td valign='top'><a href='business_details.php?user_id=$id&mode=edit'><img src='images/folder-horizontal.png' width='35px'></a></td>";
							}
							else{
								echo "<td valign='top'><img src='images/folder-horizontal.png' width='35px'></td>";
							}
							echo "<td valign='top'><a href='map.php?user_id=$id&mode=edit'><img src='images/markers.png' width='35px'></a></td>";
							echo "<td valign='top'><a href='issues.php?user_id=$id&mode=edit'><img src='images/warning.png' width='35px'></a></td>";
							if($title == 1 || $title == 2 || $title == 3 || $title == 4 || $title == 8){
								echo "<td valign='top'><a href='customer_details.php?user_id=$id&mode=edit'><img src='images/edit.png' width='35px'></a></td>";
							}
							echo "</tr>";
						}
						?>
					</tbody>
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
