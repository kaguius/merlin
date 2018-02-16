<?php
	$userid = "";
	$adminstatus = 3;
	$property_manager_id = "";
	session_start();
	if (!empty($_SESSION)){
		$userid = $_SESSION["userid"] ;
		$adminstatus = $_SESSION["adminstatus"] ;
		$property_manager_id = $_SESSION["property_manager_id"] ;
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
		$page_title = "Optimize & Analyze Database";
		include_once('includes/db_conn.php');
		include_once('includes/header.php');
		
		?>
		<div id="page">
			<div id="content">
				<div class="post">
					<h2><?php echo $page_title ?></h2>
					<?php
						$connection = mysql_connect($host_server, $user_server, $pwd_server);
						$sql = "SHOW TABLES FROM $db_server";
						$result = mysql_query($sql);
						//$result = mysql_list_tables($db);
						echo '<h4>Optimize tables in <b>'.$db_server.'</b> database.</h4>';
						while ($row = mysql_fetch_row($result))
						{
							if ( $db_server == "information_schema" )
							continue;

							echo $db_server . ".`" . $row[0] . "`";

							$sql = "OPTIMIZE TABLE `".$row[0]."`";
							$erg = mysql_query($sql, $connection) or die(mysql_error());
							$data= mysql_fetch_array($erg, MYSQL_ASSOC);

							if($data)
							{
								echo " - " . $data['Msg_text'] . "<br>";
							}
						}
						echo "<br />";
						$connection = mysql_connect($host_server, $user_server, $pwd_server);
						$sql = "SHOW TABLES FROM $db_server";
						$result = mysql_query($sql);
						//$result = mysql_list_tables($db);
						echo '<h4>Analyze tables in <b>'.$db_server.'</b> database.</h4>';
						while ($row = mysql_fetch_row($result))
						{
							if ( $db_server == "information_schema" )
							continue;

							echo $db_server . ".`" . $row[0] . "`";

							//$sql = "OPTIMIZE TABLE `".$row[0]."`";
							$sql = "ANALYZE TABLE `".$row[0]."`";
							$erg = mysql_query($sql, $connection) or die(mysql_error());
							$data= mysql_fetch_array($erg, MYSQL_ASSOC);

							if($data)
							{
								echo " - " . $data['Msg_text'] . "<br>";
							}
						}
					?>
				</div>
			</div>
			<br class="clearfix" />
			</div>
		</div>
<?php
	}
	include_once('includes/footer.php');
?>
<?php
	
