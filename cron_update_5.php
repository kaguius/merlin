<?php
	//update customer_id and customer_station for loan_repayments
	include_once('includes/db_conn.php');
	
	$filter_month = date("m");
	$filter_year = date("Y");
	$filter_day = date("d");
	$current_date = $filter_year.'-'.$filter_month.'-'.$filter_day;
	
	$sql = mysql_query("select loan_code from pkf_data order by id asc");
	while ($row = mysql_fetch_array($sql))
	{
		$loan_code = $row['loan_code'];
		
		$sql2 = mysql_query("select distinct loan_mobile, customer_id from loan_application where loan_code = '$loan_code'");
		while ($row = mysql_fetch_array($sql2))
		{
			$loan_mobile = $row['loan_mobile'];
			$customer_id = $row['customer_id'];
		
			echo "<td valign='top'>$loan_code</td>";
			echo "&nbsp;";
        	echo "<td valign='top'>$loan_mobile</td>";
        	echo "&nbsp;";
        	echo "<td valign='top'>$customer_id</td>";
        	echo "<br />";
		}
	}
?>