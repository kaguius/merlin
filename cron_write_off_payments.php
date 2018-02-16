<?php
	//include_once('includes/header.php');
	include_once('includes/db_conn.php');
	
	$filter_month = date("m");
	$filter_year = date("Y");
	$filter_day = date("d");
	$current_date = $filter_year.'-'.$filter_month.'-'.$filter_day;
	
	$filter_start_date = '2016-03-01';
	$filter_end_date = '2016-05-31';
?>
	
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