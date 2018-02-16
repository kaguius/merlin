<?php
	$userid = "";
	$adminstatus = 3;
	$property_manager_id = "";
	session_start();
	if (!empty($_SESSION)){
		$userid = $_SESSION["userid"] ;
		$adminstatus = $_SESSION["adminstatus"] ;
		$station = $_SESSION["station"] ;
	}
	if($adminstatus == 3){
		include_once('includes/header.php');
		?>
		<script type="text/javascript">
			document.location = "insufficient_permission.php";
		</script>
		<?php
	}
	else{
		$page_title = "User(s) Listing";
		include_once('includes/header.php');
		include_once('includes/db_conn.php');
		
		if (!empty($_GET)){	
			$action = $_GET['action'];
			$user_id = $_GET['id'];
		}

		if ($action=='disable'){
			$sql2="update user_profiles set user_status = '0' where id = '$user_id'";
			$result = mysql_query($sql2);
			?>
				<script type="text/javascript">
				<!--
					document.location = "user_details.php";
				//-->
				</script>
			<?php
		}
		if ($action=='enable'){
			$sql2="update user_profiles set user_status = '1' where id = '$user_id'";
			$result = mysql_query($sql2);
			?>
				<script type="text/javascript">
				<!--
					document.location = "user_details.php";
				//-->
				</script>
			<?php
		}
		//$station = 4;
		?>		
		<div id="page">
			<div id="content">
				<div class="post">
					<h2><font color="#FA9828">Admin Function:</font> <?php echo $page_title ?></h2>
					<?php if($station == 3){ ?>
						<p>+<a href="user_profiles.php">Add a new User</a></p>
					<?php } ?>
					<table width="100%" border="0" cellspacing="2" cellpadding="2" class="display" id="example">
						<thead bgcolor="#E6EEEE">
							<tr>
								<th>#</th>
								<th>Username</th>
								<th>First</th>
								<th>Last</th>
								<th>Station</th>
								<th>Title</th>
								<th>Email</th>
								<th>User</th>
								<th>Status</th>
								<th>Disable/ Enable</th>
							</tr>
						</thead>
						<tbody>
						<?php
						if($station == '3'){
							$sql = mysql_query("select user_profiles.id, first_name, last_name, username, title, email_address,  admin_status.admin_status, user_status, station from user_profiles inner join admin_status on admin_status.id = user_profiles.admin_status order by user_status desc");
						}
						else{
							$sql = mysql_query("select user_profiles.id, first_name, last_name, username, title, email_address,  admin_status.admin_status, user_status, station from user_profiles inner join admin_status on admin_status.id = user_profiles.admin_status where user_profiles.id = '$userid' order by user_status desc");
						}
						$station = "";
						while($row = mysql_fetch_array($sql)) {
							$intcount++;
							$id = $row['id'];
							$first_name = $row['first_name'];
							$first_name = ucwords(strtolower($first_name));
							$last_name = $row['last_name'];
							$last_name = ucwords(strtolower($last_name));
							$username = $row['username'];
							$title = $row['title'];
							$email_address = $row['email_address'];
							$admin_status = $row['admin_status'];
							$user_status = $row['user_status'];
							$station = $row['station'];
							$sql2 = mysql_query("select id, stations from stations where id = '$station'");
							while($row = mysql_fetch_array($sql2)) {
								$station_id = $row['id'];
								$stations = $row['stations'];
							}
							$sql2 = mysql_query("select id, title from title where id = '$title'");
							while($row = mysql_fetch_array($sql2)) {
								$title_id = $row['id'];
								$title = $row['title'];
							}
									
							if ($intcount % 2 == 0) {
								$display= '<tr bgcolor = #F8F2F2>';
							}
							else {
								$display= '<tr>';
							}
							echo $display;
								echo "<td valign='top'>$intcount</td>";
								echo "<td valign='top'><a href='user_profiles.php?id=$id&action=edit' title='User Details'>$username</a></td>";
								echo "<td valign='top'>$first_name</td>";
								echo "<td valign='top'>$last_name</td>";
								echo "<td valign='top'>$stations</td>";
								echo "<td valign='top'>$title</td>";
								echo "<td valign='top'>$email_address</td>";
								echo "<td valign='top'>$admin_status</td>";
								if($user_status == '1'){
									echo "<td valign='top'>Active</td>";
									echo "<td valign='top' align='center'><a title = 'Disable User' href='user_details.php?id=$id&action=disable'><img src='images/delete.png' width='20px'></a></td>";
								}
								else{
									echo "<td valign='top'>Disabled</td>";
									if($user_status == 'Administrator' && $user_status == 'Management'){
										echo "<td valign='top' align='center'><a title = 'Enable User' href='user_details.php?id=$id&action=enable'><img src='images/active.png' width='20px'></a></td>";
									}
									else{
										echo "<td valign='top' align='center'><a title = 'Enable User' href='user_details.php?id=$id&action=enable'><img src='images/active.png'  width='20px'></a></td>";
									}
								}
										
							echo "</tr>";
							$stations = "";	
						}
							?>
						</tbody>
						<tfoot bgcolor="#E6EEEE">
							<tr>
								<th>#</th>
								<th>Username</th>
								<th>First</th>
								<th>Last</th>
								<th>Station</th>
								<th>Title</th>
								<th>Email</th>
								<th>User</th>
								<th>Status</th>
								<th>Disable/ Enable</th>
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
