<?php
    $userid = "";
	$adminstatus = 4;
	$property_manager_id = "";
	session_start();
	if (!empty($_SESSION)){
		$userid = $_SESSION["userid"] ;
		$adminstatus = $_SESSION["adminstatus"] ;
		$username = $_SESSION["username"];
		$station = $_SESSION["station"] ;
	}

	//if($adminstatus != 1 || $adminstatus != 2 || $adminstatus != 4){
	if($adminstatus == 4){
		include_once('includes/header.php');
		?>
		<script type="text/javascript">
			document.location = "insufficient_permission.php";
		</script>
		<?php
	}
	else{
	$transactiontime = date("Y-m-d G:i:s");
	$page_title = "Finance Report: Recoveries Report";
	include_once('includes/db_conn.php');
	include_once('includes/header.php');
	$filter_month = date("m");
	$filter_year = date("Y");
	$filter_day = date("d");
	$current_date = $filter_year.'-'.$filter_month.'-'.$filter_day;
	
	if (!empty($_GET)){	
        $loan_officer = $_GET['loan_officer'];
        $filter_start_date = $_GET['report_start_date'];
        $filter_start_date = date('Y-m-d', strtotime(str_replace('-', '/', $filter_start_date)));
        $filter_end_date = $_GET['report_end_date'];
        $filter_end_date = date('Y-m-d', strtotime(str_replace('-', '/', $filter_end_date)));
    }
    if ($filter_start_date != "" && $filter_end_date != ""){
	
	//$filter_start_date = '2016-03-01';
	//$filter_end_date = '2016-05-31';
?>
	<div id="page">
	<div id="content">
	<div class="post">
	<h2><?php echo $page_title ?></h2>
					<h3>Report Date: <?php echo $filter_start_date ?> and <?php echo $filter_end_date ?></h3>
	<table width="100%" border="0" cellspacing="2" cellpadding="2" class="display" id="exampl">
		<thead bgcolor="#E6EEEE">
			<tr>
				<th>Date</th>
				<th>Branch</th>
				<th>Mobile</th>
				<th>Loan Code</th>
				<th>Payment Code</th>
				<th>Amount</th>
			</tr>
		</thead>
		<tbody>
	<?php

	$sql = mysql_query("select loan_code, write_off_date from write_off_loans order by loan_code");
	while ($row = mysql_fetch_array($sql))
	{
		$loan_code = $row['loan_code'];
		$write_off_date = $row['write_off_date'];
		
		if($filter_start_date >= $write_off_date){
            $sql2 = mysql_query("select loan_rep_date, loan_rep_mobile, loan_rep_amount, loan_rep_mpesa_code, loan_rep_code, customer_station from loan_repayments where loan_rep_code = '$loan_code' and loan_rep_date between '$filter_start_date' and '$filter_end_date' order by loan_rep_date asc");
        }
		
		while ($row = mysql_fetch_array($sql2))
		{
		    $intcount++;
			$loan_rep_date = $row['loan_rep_date'];
			$loan_rep_mobile = $row['loan_rep_mobile'];
			$loan_rep_amount = $row['loan_rep_amount'];
			$loan_rep_mpesa_code = $row['loan_rep_mpesa_code'];
			$loan_rep_code = $row['loan_rep_code'];
			$customer_station = $row['customer_station'];
			
			$sql3 = mysql_query("select id, stations from stations where id = '$customer_station'");
            while($row = mysql_fetch_array($sql3)) {
                $stations = $row['stations'];
                $stations = ucwords(strtolower($stations));
            }
			
			if ($intcount % 2 == 0) {
                $display= '<tr bgcolor = #F0F0F6>';
            }
            else {
                $display= '<tr>';
            }
            echo $display;
            echo "<td valign='top'>$loan_rep_date</td>";
            echo "<td valign='top'>$stations</td>";
            echo "<td valign='top'>$loan_rep_mobile</td>";
            echo "<td valign='top'>$loan_rep_code</td>";
            echo "<td valign='top'>$loan_rep_mpesa_code</td>";
            echo "<td valign='top'>$loan_rep_amount</td>";
            echo "</tr>";
	
		}
		$loan_rep_date = "";
        $stations = "";
        $loan_rep_mobile = "";
        $loan_rep_amount = 0;
        $loan_rep_mpesa_code = "";
        $loan_rep_code = "";
		
	}
	?>
	                </tbody>
				</table>
            </div>
			<br class="clearfix" />
			</div>
		</div>
        <?php
		}
		else{
		?>		
		<div id="page">
			<div id="content">
				<div class="post">
				
					<h2><?php echo $page_title ?></h2>
					<form id="frmCreateTenant" name="frmCreateTenant" method="GET" action="<?php echo $_SERVER['PHP_SELF'];?>">
					<table border="0" width="100%" cellspacing="2" cellpadding="2">
							<tr >
								<td  valign="top">Select Start Date Range: </td>
								<td>
									<input title="Enter the Selection Date" value="" id="report_start_date" name="report_start_date" type="text" maxlength="100" class="main_input" size="15" />
								</td>
								<td  valign="top">Select End Date Range:</td>
								<td> 
									<input title="Enter the Selection Date" value="" id="report_end_date" name="report_end_date" type="text" maxlength="100" class="main_input" size="15" />
								</td>
								
							</tr>
							<tr>
								<td><button name="btnNewCard" id="button">Search</button></td>
							</tr>
						</table>
					</form>
				</div>
			</div>
			<br class="clearfix" />
			</div>
		</div>
<?php
		}
	}
	include_once('includes/footer.php');
?>

