<?php
	//Updates the customer_id and customer_station on the db once loan_repayments are done

	include_once('includes/db_conn.php');
	
	$filter_month = date("m");
	$filter_year = date("Y");
	$filter_day = date("d");
	$current_date = $filter_year.'-'.$filter_month.'-'.$filter_day;

	$sql = mysql_query("select customer_id from loan_application order by customer_id asc");
	while ($row = mysql_fetch_array($sql))
	{
		$customer_id = $row['customer_id'];
		
		$sql2 = mysql_query("select loan_officer, collections_officer from users where id = '$customer_id'");
		while ($row = mysql_fetch_array($sql2))
		{
			$loan_officer = $row['loan_officer'];
			$collections_officer = $row['collections_officer'];
		
			$sql3="update loan_application set loan_officer='$loan_officer', collections_officer='$collections_officer' WHERE customer_id  = '$customer_id';";
			echo $sql3."<br />";
			//$result = mysql_query($sql3);
		}
	}
?>
