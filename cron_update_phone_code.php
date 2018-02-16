<?php
	//Updates the customer_id and customer_station on the db once loan_repayments are done

	$host_server='localhost';
	$db_server='asterisk';
	$user_server='root';
	$pwd_server='vicidialnow';

	$sql = mysql_query("SELECT phone_code, phone_number FROM vicidial_list WHERE phone_code = '1'");
	while ($row = mysql_fetch_array($sql))
	{
		$phone_code = $row['phone_code'];
		
		$sql3="update vicidial_list set phone_code='254' WHERE phone_code  = '1'";
		$result = mysql_query($sql3);
	}
?>
