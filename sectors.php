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
		$page_title = "4G Capital Sector(s)";
		include_once('includes/header.php');
		include_once('includes/db_conn.php');
			
		?>
		<div id="page">
			<div id="content">
				<div class="post">
					<h2><?php echo $page_title ?></h2>
					<p>+ <a href="update_sectors.php">Add a new Sector</a></p>
					<table width="100%" border="0" cellspacing="2" class="display" cellpadding="2" id="example">
						<thead bgcolor="#E6EEEE">
							<tr bgcolor='#fff'>
								<th>#</th>
								<th>Sector</th>
								<th>Parent Branch</th>
								<th>Branch Manager</th>
								<th>Edit</th>
							</tr>
						</thead>
						<tbody>
						<?php
							$result = mysql_query("select id, sector, parent_branch, branch_manager from sectors order by sector asc");
							while ($row = mysql_fetch_array($result))
							{
								$intcount++;
								$id = $row['id'];
								$sector = $row['sector'];
								$parent_station_id = $row['parent_branch'];
								$branch_manager_id = $row['branch_manager'];
								
								// Get the name of the parent branch
								$parent_branch_name = "";
								$sql_parent_branch_name = mysql_query("select stations from stations where id = '$parent_station_id'");
								while ( $row = mysql_fetch_array($sql_parent_branch_name) )
								{
									$parent_branch_name = $row['stations'];
								}
	
								$sql_parent_branch_name = mysql_query("select concat(first_name, ' ', last_name)branch_manager_name from user_profiles where id = '$branch_manager_id'");
								while ($row = mysql_fetch_array($sql_parent_branch_name)) {
								    $branch_manager_name = $row['branch_manager_name'];
								}

								if ($intcount % 2 == 0) {
									$display= '<tr bgcolor = #F0F0F6>';
								}
								else {
									$display= '<tr>';
								}
								echo $display;
									echo "<td valign='top'>$intcount.</td>";
									echo "<td valign='top'>$sector</td>";
									echo "<td valign='top'>$parent_branch_name</td>";
									echo "<td valign='top'>$branch_manager_name</td>";
									echo "<td valign='top' align='center'><a title = 'Edit Detail' href='update_sectors.php?id=$id&action=edit'><img src='images/edit.png' width='25px'></a></td>";
								echo "</tr>";	
								}
							?>
						</tbody>
						<tfoot bgcolor="#E6EEEE">
							<tr bgcolor='#fff'>
								<th>#</th>
								<th>Sector</th>
								<th>Parent Branch</th>
								<th>Branch Manager</th>
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
