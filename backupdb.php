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
		include_once('includes/db_conn.php');
		$page_title = "Database Backups";
		if (!empty($_GET)){	
			$action = $_GET['action'];
		}
		if ($action=='backup'){
			$backupFile='/var/www/afb/backup/'.date("Y-m-d-H-i-s").'_'.$db_server.'.sql';
			$command = "mysqldump -u$user_server -p$pwd_server -h$host_server $db_server > $backupFile";
			system($command, $result);
			echo $result;
			?>
			<script type="text/javascript">
				document.location = "backupdb.php";
			</script>
			<?php
		}
		include_once('includes/header.php');
	?>
		<div id="page">
			<div id="content">
				<div class="post">
					<h2><?php echo $page_title ?></h2>
					<p>Click on the icon below to create a backup:</p>
					<p><a href="backupdb.php?action=backup" title = "Create a system wide data backup"><img src="images/backups.jpg" width="75px"></a></p>
					<table width="100%" border="0" cellspacing="2" class="display" cellpadding="2" id="example">
						<thead bgcolor="#E6EEEE">
							<tr bgcolor='#fff'>
								<th>#</th>
								<th>File Name</th>
								<th>File Size</th>
								<th>Creation Time</th>
							</tr>
						</thead>
						<tbody>
					<?php
						//print_r($files);
						//path to directory to scan
						$directory = "backup/";
						 
						//get all image files with a .jpg extension.
						$files = glob($directory . "*.sql");
						 
						//print each file name
						
						$intcount = 0;
						foreach($files as $image)
						{
							$intcount++;
							if ($intcount % 2 == 0) {
								$display= '<tr bgcolor = #F0F0F6>';
							}
							else {
								$display= '<tr>';
							}
							echo $display;
								echo "<td valign='top'>$intcount.</td>";
								echo "<td valign='top'>$image</td>";
								echo "<td valign='top'>".number_format(filesize($image), 0) . " bytes</td>";
								echo "<td valign='top'>".date ("F d Y H:i:s.", filemtime($image)). "</td>";
						}
					?>
						</tbody>
						<tfoot bgcolor="#E6EEEE">
							<tr bgcolor='#fff'>
								<th>#</th>
								<th>File Name</th>
								<th>File Size</th>
								<th>Creation Time</th>
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
		

