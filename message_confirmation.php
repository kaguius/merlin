<?php
	$userid = "";
	$adminstatus = 3;
	$property_manager_id = "";
	session_start();
	$page_title = "Messaging Confirmation";
	include_once('includes/db_conn.php');
	if (!empty($_SESSION)){
		$userid = $_SESSION["userid"] ;
		$adminstatus = $_SESSION["adminstatus"] ;
		$station = $_SESSION["station"] ;
	}
	$result = mysql_query("select first_name, last_name from user_profiles where id = '$userid'");
	while ($row = mysql_fetch_array($result))
	{
		$first_name = $row['first_name'];
		$last_name = $row['last_name'];
	}
	include_once('includes/header.php');
	?>		
		<div id="page">
			<div id="content">
				<div class="post">
					<h2><?php echo $page_title ?></h2>
					<br />
					<p>Hello <?php echo $first_name.' '.$last_name ?>,<br />
					We have received your request, and the messages will be sent shortly.</p>
					<p>Best regards,<br />
					Technology Team</p>
				</div>
			</div>
			<br class="clearfix" />
			</div>
		</div>
<?php
	include_once('includes/footer.php');
?>
