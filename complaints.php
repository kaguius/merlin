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
	if (!empty($_GET)) {
		$user_id = $_GET['user_id'];
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
		$page_title = "Complaints Listing";
		include_once('includes/header.php');
		//echo $title;
		?>		
		<div id="page">
			<div id="content">
				<div class="post">
				<h2><font color="#000A8B"><?php echo $page_title; ?>:</font> Overview</h2>
				
				<?php if($station == '3' || $userid == '31' || $userid == '32'){ ?>
					<p>+ <a href="complaints_details.php?user_id=<?php echo $user_id ?>">Add a new Complaint</a></p>
				<?php } ?>
				<table width="100%" border="0" cellspacing="2" cellpadding="2" class="display" id="example">
					<thead bgcolor="#E6EEEE">
						<tr bgcolor='#fff'>
							<th>#</th>
							<th>Name</th>
							<th>Mobile No</th>
							<th>Complaint</th>
							<th>Resolution</th>
							<th>Created</th>
							<th>Resolved</th>
							<th>View</th>
							<th>Escalations</th>
						</tr>
					</thead>
					<tbody>
					<?php
						if($station == '3'){
							$sql = mysql_query("select id, customer_id, complaint_nature, resolution, created_time, resolved_time, UID from complaints_customer where customer_id = '$user_id' order by created_time desc");
						}
						else{
							$sql = mysql_query("select id, customer_id, complaint_nature, resolution, created_time, resolved_time, UID from complaints_customer where customer_id = '$user_id' and UID = '$userid' order by created_time desc");
						}
						while ($row = mysql_fetch_array($sql))
						{
							$intcount++;
							$id = $row['id'];					
							$customer_id = $row['customer_id'];
							$complaint_nature = $row['complaint_nature'];
							$resolution = $row['resolution'];
							$created_time = $row['created_time'];
							$resolved_time = $row['resolved_time'];

							$sql2 = mysql_query("select first_name, last_name, mobile_no from users where id = '$customer_id'");
							while ($row = mysql_fetch_array($sql2)) {
							    	$first_name = $row['first_name'];
							    	$last_name = $row['last_name'];
								$mobile_no = $row['mobile_no'];
							}
							$sql2 = mysql_query("select complaint_nature from complaint_nature where id = '$complaint_nature'");
							while ($row = mysql_fetch_array($sql2)) {
							    	$complaint_nature_name = $row['complaint_nature'];
							}
							$sql2 = mysql_query("select resolution from complaint_resolution where id = '$resolution'");
							while ($row = mysql_fetch_array($sql2)) {
							    	$resolution_name = $row['resolution'];
							}
							
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
							echo "<td valign='top'>$complaint_nature_name</td>";
							echo "<td valign='top'>$resolution_name</td>";
							echo "<td valign='top'>$created_time</td>";
							echo "<td valign='top'>$resolved_time</td>";
							echo "<td valign='top' align='center'><a href='complaints_details.php?complaint_id=$id&user_id=$user_id&mode=edit'><img src='images/edit.png' width='35px'></a></td>";
							echo "<td valign='top' align='center'><a href='complaints_escalations.php?complaint_id=$id&user_id=$user_id&mode=edit'><img src='images/escalations.png' width='35px'></a></td>";
							echo "</tr>";
						}
						?>
					</tbody>
					<tfoot bgcolor="#E6EEEE">
						<tr>
							<th>#</th>
							<th>Name</th>
							<th>Mobile No</th>
							<th>Complaint</th>
							<th>Resolution</th>
							<th>Created</th>
							<th>Resolved</th>
							<th>View</th>
							<th>Escalations</th>
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
