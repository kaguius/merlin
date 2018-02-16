<?php
	//include_once('includes/header.php');
	include_once('includes/db_conn.php');
	
	$filter_month = date("m");
	$filter_year = date("Y");
	$filter_day = date("d");
	$current_date = $filter_year.'-'.$filter_month.'-'.$filter_day;
	
	//$sql = mysql_query("select loan_code, customer_id, loan_amount from loan_application where loan_date between '2015-01-01' and '2015-11-05' and customer_station != '1' and customer_station != '2' and customer_station != '3' and customer_station != '4' and customer_station != '5' and customer_station != '6' and customer_station != '7' and customer_station != '8' and customer_station != '9'");	
	$sql = mysql_query("select loan_code, customer_id, loan_amount from loan_application where loan_date between '2015-01-01' and '2015-11-05' and customer_station != '11' and customer_station != '12' and customer_station != '13' and customer_station != '14' and customer_station != '16' and customer_station != '17'");	
	while($row = mysql_fetch_array($sql)) {
		$customer_id = $row['customer_id'];
		$loan_amount = $row['loan_amount'];
		$sql2 = mysql_query("select distinct loan_amount from loan_application where loan_amount > '$loan_amount' and customer_id = '$customer_id'");	
		while($row = mysql_fetch_array($sql2)) {
			$loan_amount_increase = $row['loan_amount'];
		
			echo $customer_id." - ".$loan_amount_increase;
			echo "<br />";
		}
	}
?>
