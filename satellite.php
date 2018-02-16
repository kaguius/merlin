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
		$page_title = "4G Capital Satellites(s)";
		include_once('includes/header.php');
		include_once('includes/db_conn.php');
			
		?>
		<div id="page">
			<div id="content">
				<div class="post">
					<h2><?php echo $page_title ?></h2>
					<p>+ <a href="update_satellite.php">Add a new Satellite</a></p>
					<table width="100%" border="0" cellspacing="2" class="display" cellpadding="2" id="example">
						<thead bgcolor="#E6EEEE">
							<tr bgcolor='#fff'>
								<th>#</th>
								<th>Satellite</th>
								<th>Parent Branch</th>
								<th>Daily Target</th>
								<th>Weekly Target</th>
								<th>Monthly Target</th>
								<th>Paybill</th>
								<th>Active</th>
								<th>Edit</th>
							</tr>
						</thead>
						<tbody>
						<?php
							$result = mysql_query("select id, parent_station_id, stations, daily_target, weekly_target, monthly_target, paybill, active, transactiontime from stations "
								."WHERE parent_station_id IS NOT NULL and parent_station_id != '0' order by stations asc");
							while ($row = mysql_fetch_array($result))
							{
								$intcount++;
								$id = $row['id'];
								$stations = $row['stations'];
								$daily_target = $row['daily_target'];
								$weekly_target = $row['weekly_target'];
								$monthly_target = $row['monthly_target'];
								$paybill = $row['paybill'];
								$active = $row['active'];
								$transactiontime = $row['transactiontime'];
								$parent_station_id = $row['parent_station_id'];
								
								// Get the name of the parent branch
								$parent_branch_name = "";
								$sql_parent_branch_name = mysql_query("select stations from stations where id = '$parent_station_id'");
								while ( $row = mysql_fetch_array($sql_parent_branch_name) )
								{
									$parent_branch_name = $row['stations'];
								}

								if ($intcount % 2 == 0) {
									$display= '<tr bgcolor = #F0F0F6>';
								}
								else {
									$display= '<tr>';
								}
								echo $display;
									echo "<td valign='top'>$intcount.</td>";
									echo "<td valign='top'>$stations</td>";
									echo "<td valign='top'>$parent_branch_name</td>";
									echo "<td valign='top' align='right'>".number_format($daily_target, 2)."</td>";
									echo "<td valign='top' align='right'>".number_format($weekly_target, 2)."</td>";
									echo "<td valign='top' align='right'>".number_format($monthly_target, 2)."</td>";
									echo "<td valign='top'>$paybill</td>";
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
							<tr bgcolor='#fff'>
								<th>#</th>
								<th>Satellite</th>
								<th>Parent Branch</th>
								<th>Daily Target</th>
								<th>Weekly Target</th>
								<th>Monthly Target</th>
								<th>Paybill</th>
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
