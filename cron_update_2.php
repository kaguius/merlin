<?php
	//update customer_station and customer_id on loan_application
	include_once('includes/db_conn.php');
	
	$filter_month = date("m");
	$filter_year = date("Y");
	$filter_day = date("d");
	$current_date = $filter_year.'-'.$filter_month.'-'.$filter_day;
	
	$sql = mysql_query("select loan_code, loan_mobile from loan_application where customer_id = '0'");
	while ($row = mysql_fetch_array($sql))
	{
		$loan_mobile = $row['loan_mobile'];
		$loan_code = $row['loan_code'];
		
		$sql2 = mysql_query("select id, stations from users where mobile_no = '$loan_mobile' or dis_phone = '$loan_mobile'");
		while ($row = mysql_fetch_array($sql2))
		{
			$stations = $row['stations'];
			$id = $row['id'];
		
			$sql3="update loan_application set customer_id='$id', customer_station='$stations' WHERE loan_code  = '$loan_code'";
			echo $sql3."<br />";
			$result = mysql_query($sql3);
		}
	}
?>