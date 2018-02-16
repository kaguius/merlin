<?php
	$userid = "";
	$adminstatus = 3;
	$property_manager_id = "";
	session_start();
	$page_title = "Password Confirmation";
	include_once('includes/db_conn.php');
	if (!empty($_GET)){	
		$email_address = $_GET['email_address'];
		$filter_start_date = $_GET['report_start_date'];
		$filter_start_date = date('Y-m-d', strtotime(str_replace('-', '/', $filter_start_date)));
		$filter_end_date = $_GET['report_end_date'];
		$filter_end_date = date('Y-m-d', strtotime(str_replace('-', '/', $filter_end_date)));
	}
	$result = mysql_query("select first_name, last_name from user_profiles where email_address = '$email_address'");
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
					<h3><?php echo $pagetitle ?> Confirmation: <?php echo $first_name.' '.$last_name ?></h3><br />
					<p>Hello <?php echo $first_name.' '.$last_name ?>,<br />
					We have received your request, and your new password has been sent to your email address, <strong><?php echo $email_address ?></strong>. If you experience any issues, please don't hesitate to contact us on e-mail, to <a href="mailto:support@afbkenya.freshdesk.com">support@afbkenya.freshdesk.com</a></p>
					<p>Best regards,<br />

					<strong>4G Capital Loan Management Portal</strong><br />
					Client Services Team</p>
				</div>
			</div>
			<br class="clearfix" />
			</div>
		</div>
<?php
	include_once('includes/footer.php');
?>
