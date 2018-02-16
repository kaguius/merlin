<?php
	$userid = "";
	$adminstatus = "";
	session_start();
	if (!empty($_SESSION)){
		$userid = $_SESSION["userid"] ;
		$adminstatus = $_SESSION["adminstatus"] ;
		$station = $_SESSION["station"] ;
		$title = $_SESSION["title"];
	}
	$filter_month = date("m");
	$filter_year = date("Y");
	$filter_day = date("d");
	$day_one = 01;
	
	$start_date = $filter_year.'-'.$filter_month.'-'.$day_one;
	$current_date = $filter_year.'-'.$filter_month.'-'.$filter_day;
?>
<div id="menu">
	<ul>
		<?php if($station == '4'){ ?>
			<li><a href="index.php?view=daily">Home</a></li>
			<li><a href="payments.php">Payments</a></li>
			<li><a href="#">Search</a>
                <ul>
                    <li><a href="search.php">Search</a></li>
                    <li><a href="id_search.php">ID Customer Search</a></li>
                    <li><a href="ref_search.php">Reference Search</a></li>
                </ul>
            </li>
			<li><a href="#">Followup</a>
                <ul>
                    <li><a href="ptps.php">Promise To Pay (PTP)</a></li>
                    <li><a href="interactions.php">Customer Interactions</a></li>
                </ul>
            </li>
			<li><a href="arrears.php">Arrears</a></li>
			<li><a href="reports.php">Reports</a></li>
			<li><a href="admin.php" title="Admin">Admin</a></li>
			<li><a href="logout.php" title="Log Out">Log Out</a></li>
		<? }
		else if($station == '3'){
		    if($title == '12'){ ?>
		        <li><a href="index.php?view=daily">Home</a></li>
		        <li><a href="reports.php">Reports</a></li>
		        <li><a href="logout.php" title="Log Out">Log Out</a></li>
		    <?php } else if($title == '13'){ ?>
		        <li><a href="index.php?view=daily">Home</a></li>
		        <li><a href="#">Search</a>
                    <ul>
                        <li><a href="search.php">Search</a></li>
                        <li><a href="id_search.php">ID Customer Search</a></li>
                        <li><a href="ref_search.php">Reference Search</a></li>
                    </ul>
                </li>
		        <li><a href="payments.php">Payments</a></li>
		        <li><a href="logout.php" title="Log Out">Log Out</a></li>
		    <?php } else { ?>
                <li><a href="index.php?view=daily">Home</a></li>
                <li><a href="leads.php">Leads</a></li>
                <!--<li><a href="customers.php">Customers</a></li>-->
                <li><a href="#">Search</a>
                    <ul>
                        <li><a href="search.php">Search</a></li>
                        <li><a href="id_search.php">ID Customer Search</a></li>
                        <li><a href="ref_search.php">Reference Search</a></li>
                    </ul>
                </li>
                <li><a href="payments.php">Payments</a></li>
                <li><a href="#">Followup</a>
                    <ul>
                        <li><a href="ptps.php">Promise To Pay (PTP)</a></li>
                        <li><a href="interactions.php">Customer Interactions</a></li>
                    </ul>
                </li>
                <li><a href="arrears.php">Arrears</a></li>
                <li><a href="loan_suspense.php">Suspense</a>
                    <ul>
                        <li><a href="loan_suspense.php">Loan Suspense</a></li>
                        <li><a href="suspence_account.php">Payment Suspense</a></li>
                    </ul>
                </li>
                <!--<li><a href="#">Quality</a>
                    <ul>
                        <li><a href="field_qa.php">Field QA Form</a></li>
                        <li><a href="call_monitoring.php">Call Monitoring</a></li>
                    </ul>
                </li>-->
                <li><a href="reports.php">Reports</a></li>
                <li><a href="loan_reversals.php">Reversals</a>
                    <ul>
                        <li><a href="loan_reversals.php">Loan Reversals</a></li>
                        <li><a href="payment_reversals.php">Payment Reversals</a></li>
                    </ul>
                </li>
                <li><a href="admin.php" title="Admin">Admin</a></li>
                <li><a href="logout.php" title="Log Out">Log Out</a></li>
			<?php } ?>
		<? }
		else{ ?>
			<li><a href="index.php?view=daily">Home</a></li>
			<li><a href="leads.php">Leads</a></li>
			<li><a href="customers.php">Customers</a></li>
			<li><a href="#">Search</a>
                    <ul>
                        <li><a href="search.php">Search</a></li>
                        <li><a href="id_search.php">ID Customer Search</a></li>
                        <li><a href="ref_search.php">Reference Search</a></li>
                    </ul>
                </li>
			<li><a href="payments.php">Payments</a></li>
			<li><a href="loan_suspense.php">Suspense</a>
				<ul>
					<li><a href="loan_suspense.php">Loan Suspense</a></li>
					<li><a href="suspence_account.php">Payment Suspense</a></li>
				</ul>
			</li>
			<li><a href="#">Followup</a>
				<ul>
					<li><a href="ptps.php">Promise To Pay (PTP)</a></li>
					<li><a href="interactions.php">Customer Interactions</a></li>
				</ul>
			</li>
			<li><a href="reports.php">Reports</a></li>
			<li><a href="admin.php" title="Admin">Admin</a></li>
			<li><a href="logout.php" title="Log Out">Log Out</a></li>
		<? } ?>
	</ul>
	<br class="clearfix" />
</div>
