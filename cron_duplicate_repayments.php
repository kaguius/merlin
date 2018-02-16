<?php
	//include_once('includes/header.php');
	include_once('includes/db_conn.php');
	
	$filter_month = date("m");
	$filter_year = date("Y");
	$filter_day = date("d");
	$current_date = $filter_year.'-'.$filter_month.'-'.$filter_day;
	
	?>
	<table width="100%" border="0" cellspacing="2" cellpadding="2" class="display" id="exampl">
		<thead bgcolor="#E6EEEE">
			<tr>
				<th>Loan rep ID</th>
				<th>Customer</th>
				<th>Phone</th>
				<th>Date</th>
				<th>Loan Code</th>
				<th>MPESA Code</th>
				<th>Amount</th>
			</tr>
		</thead>
		<tbody>
	<?php

	$sql = mysql_query("select distinct loan_rep_mpesa_code, count(loan_rep_id)loans from loan_repayments group by loan_rep_mpesa_code order by loans desc");
	while ($row = mysql_fetch_array($sql))
	{
		$loan_rep_mpesa_code = $row['loan_rep_mpesa_code'];
		$loans = $row['loans'];
		
		if($loans > 1){
			$sql2 = mysql_query("select loan_rep_id, loan_rep_date, customer_id, customer_station, loan_rep_mobile, loan_rep_amount, loan_rep_mpesa_code, loan_rep_code from loan_repayments where loan_rep_mpesa_code = '$loan_rep_mpesa_code'");
			while ($row = mysql_fetch_array($sql2))
			{
				$loan_rep_id = $row['loan_rep_id'];
				$loan_rep_date = $row['loan_rep_date'];
				$customer_id = $row['customer_id'];
				$customer_station = $row['customer_station'];
				$loan_rep_mobile = $row['loan_rep_mobile'];
				$loan_rep_amount = $row['loan_rep_amount'];
				$loan_rep_mpesa_code = $row['loan_rep_mpesa_code'];
				$loan_rep_code = $row['loan_rep_code'];
		
				$sql3 = mysql_query("select first_name, last_name, national_id from users where id = '$customer_id'");
				while ($row = mysql_fetch_array($sql3))
				{
					$first_name = $row['first_name'];
					$last_name = $row['last_name'];
					$first_name = ucwords(strtolower($first_name));	
					$last_name = ucwords(strtolower($last_name));
					$customer_name = $first_name.' '.$last_name;		
					$national_id = $row['national_id'];
				}
				$sql4 = mysql_query("select stations from stations where id = '$customer_station'");
				while ($row = mysql_fetch_array($sql3))
				{
					$customer_stations = $row['stations'];
				}
	
				
				if ($intcount % 2 == 0) {
					$display= '<tr bgcolor = #F0F0F6>';
				}
				else {
					$display= '<tr>';
				}
				echo $display;
				echo "<td valign='top'>$loan_rep_id</td>";
				echo "<td valign='top'>$customer_name</td>";
				echo "<td valign='top'>$loan_rep_mobile</td>";
				echo "<td valign='top'>$loan_rep_date</td>";
				echo "<td valign='top'>$loan_rep_code</td>";
				echo "<td valign='top'>$loan_rep_mpesa_code</td>";
				echo "<td align='right' valign='top'>".number_format($loan_rep_amount, 2)."</td>";	
				echo "</tr>";
			}
			$loan_mpesa_code = "";
			$loans = 0;
		}
	}
?>