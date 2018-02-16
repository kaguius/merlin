<?php
	$userid = "";
	$adminstatus = 3;
	$property_manager_id = "";
	session_start();
	if (!empty($_SESSION)){
		$userid = $_SESSION["userid"] ;
		$adminstatus = $_SESSION["adminstatus"] ;
		$username = $_SESSION["username"];
	}

	//if($adminstatus != 1 || $adminstatus != 2 || $adminstatus != 4){
	if($adminstatus == 3){
		include_once('includes/header.php');
		?>
		<script type="text/javascript">
			document.location = "insufficient_permission.php";
		</script>
		<?php
	}
	else{
		$transactiontime = date("Y-m-d G:i:s");
		$page_title = "Afb Station(s)";
		include_once('includes/header.php');
		include_once('includes/db_conn.php');
			
		?>
		<div id="page">
			<div id="content">
				<div class="post">
					<h2><?php echo $page_title ?></h2>
					<p>+ <a href="update_station.php">Add a new Station</a></p>
					<table width="100%" border="0" cellspacing="2" class="display" cellpadding="2" id="example">
						<thead bgcolor="#E6EEEE">
							<tr>
								<th>#</th>
								<th>Station</th>
								<th>Daily Target</th>
								<th>Weekly Target</th>
								<th>Monthly Target</th>
								<th>Transactiontime</th>
								<th>Active</th>
								<th>Edit</th>
							</tr>
						</thead>
						<tbody>
						<?php
							$result = mysql_query("select id, stations, daily_target, weekly_target, monthly_target, active, transactiontime from stations order by stations asc");
							while ($row = mysql_fetch_array($result))
							{
								$intcount++;
								$id = $row['id'];
								$stations = $row['stations'];
								$daily_target = $row['daily_target'];
								$weekly_target = $row['weekly_target'];
								$monthly_target = $row['monthly_target'];
								$active = $row['active'];
								$transactiontime = $row['transactiontime'];

								if ($intcount % 2 == 0) {
									$display= '<tr bgcolor = #F0F0F6>';
								}
								else {
									$display= '<tr>';
								}
								echo $display;
									echo "<td valign='top'>$intcount.</td>";
									echo "<td valign='top'>$stations</td>";
									echo "<td valign='top'>$daily_target</td>";
									echo "<td valign='top'>$weekly_target</td>";
									echo "<td valign='top'>$monthly_target</td>";
									echo "<td valign='top'>$transactiontime</td>";
									if($active == '1'){
										echo "<td valign='top' align='center'><img src='images/delete.png' width='20px'></td>";
									}
									else{
										echo "<td valign='top' align='center'><img src='images/active.png'  width='20px'></td>";
									}
									echo "<td valign='top' align='center'><a title = 'Edit Detail' href='update_station.php?id=$id&action=edit'><img src='images/edit.png' width='25px'></a></td>";
								echo "</tr>";	
								}
							?>
						</tbody>
						<tfoot bgcolor="#E6EEEE">
							<tr>
								<th>#</th>
								<th>Station</th>
								<th>Daily Target</th>
								<th>Weekly Target</th>
								<th>Monthly Target</th>
								<th>Transactiontime</th>
								<th>Active</th>
								<th>Edit</th>
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
