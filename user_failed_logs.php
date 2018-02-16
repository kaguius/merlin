<?php
	$userid = "";
	$adminstatus = 4;
	session_start();
	if (!empty($_SESSION)){
		$userid = $_SESSION["userid"] ;
		$adminstatus = $_SESSION["adminstatus"] ;
	}

	//if($adminstatus != 1 || $adminstatus != 2 || $adminstatus != 4){
	if($adminstatus == 3){
		include_once('includes/header.php');
		?>
		<script type="text/javascript">
			document.location = "login.php";
		</script>
		<?php
	}
	else{
		$transactiontime = date("Y-m-d G:i:s");
		$page_title = "User Failed Logs";
		include_once('includes/db_conn.php');
		include_once('includes/header.php');
		?>		
		<div id="page">
			<div id="content">
				<div class="post">
				<h2><font color="#FA9828">Admin Function:</font> <?php echo $page_title ?></h2>
					<br />
					<table width="100%" border="0" cellspacing="2" class="display" cellpadding="2" id="example">
						<thead bgcolor="#E6EEEE">
							<tr bgcolor='#fff'>
								<th width="5%">#</th>
								<th width="15%">Username</th>
								<th width="20%">IP Address</th>
								<th width="20%">Transactiontime</th>
							</tr>
						</thead>
						<tbody>
						<?php
							$result_suppliers = mysql_query("select id, username, password, ipaddress, tranasctiontime from users_failed_logs order by tranasctiontime desc limit 200");
							$intcount = 0;
							while ($row = mysql_fetch_array($result_suppliers))
							{
								$intcount++;
								$username = $row['username'];
								$password = $row['password'];
								$ipaddress = $row['ipaddress'];
								$tranasctiontime = $row['tranasctiontime'];
								if ($intcount % 2 == 0) {
									$display= '<tr bgcolor = #F8F2F2>';
								}
								else {
									$display= '<tr>';
								}
								echo $display;
									echo "<td valign='top'>$intcount</td>";
									echo "<td valign='top'>$username</td>";
									echo "<td valign='top'>$ipaddress</td>";	
									echo "<td valign='top'>$tranasctiontime</td>";		
								echo "</tr>";	
								}
							?>
						</tbody>
						<tfoot bgcolor="#E6EEEE">
							<tr bgcolor='#fff'>
								<th width="5%">#</th>
								<th width="15%">Username</th>
								<th width="20%">IP Address</th>
								<th width="20%">Transactiontime</th>
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
