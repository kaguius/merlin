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
		$page_title = "User Logs";
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
								<th width="10%">Username</th>
								<th width="20%">Timestamp</th>
								<th width="15%">IP Address</th>
								<th width="60%">Browser</th>
							</tr>
						</thead>
						<tbody>
						<?php
							$result = mysql_query("select username, log_time, log_ipaddress, log_browser from user_logs order by log_time desc limit 200");
							$intcount = 0;
							while ($row = mysql_fetch_array($result))
							{
								$intcount++;
								$username = $row['username'];
								$log_time = $row['log_time'];
								$log_ipaddress = $row['log_ipaddress'];
								$log_browser = $row['log_browser'];
								if ($intcount % 2 == 0) {
									$display= '<tr bgcolor = #F8F2F2>';
								}
								else {
									$display= '<tr>';
								}
								echo $display;
									echo "<td valign='top'>$intcount</td>";
									echo "<td valign='top'>$username</td>";
									echo "<td valign='top'>$log_time</td>";
									echo "<td valign='top'>$log_ipaddress</td>";								
									echo "<td valign='top'>$log_browser</td>";
								echo "</tr>";	
								}
							?>
						</tbody>
						<tfoot bgcolor="#E6EEEE">
							<tr bgcolor='#fff'>
								<th width="5%">#</th>
								<th width="10%">Username</th>
								<th width="20%">Timestamp</th>
								<th width="15%">IP Address</th>
								<th width="60%">Browser</th>
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
