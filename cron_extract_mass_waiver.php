<?php
	//Extract Loan Officer and Collections Officer from selected Branches
	include_once('includes/db_conn.php');
	
	$filter_month = date("m");
	$filter_year = date("Y");
	$filter_day = date("d");
	$current_date = $filter_year.'-'.$filter_month.'-'.$filter_day;
	?>
	
	<table width="100%" border="0" cellspacing="2" cellpadding="2" class="display" id="example3">
        <thead bgcolor="#E6EEEE">
            <tr>
            	<th>ID</th>
                <th>Phone Numbere</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>National ID</th>
                <th>Loan Date</th>
                <th>Due Date</th>
                <th>Loan</th>
                <th>Payments</th>
                <th>Balance</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $sql = mysql_query("select mobile_number from mass_waiver_table order by mobile_number asc");
        while ($row = mysql_fetch_array($sql))
        {
            $mobile_number = $row['mobile_number'];
            
            $sql2 = mysql_query("select id, first_name, last_name, national_id from users where dis_phone = '$mobile_number'");
            while($row = mysql_fetch_array($sql2)) {
                $id = $row['id'];
                $first_name = $row['first_name'];
                $last_name = $row['last_name'];
                $national_id = $row['national_id'];
            }
            
            $sql2 = mysql_query("select loan_code, loan_total_interest, loan_date, loan_due_date from loan_application where customer_id = '$id' order by loan_date desc limit 1");
            while($row = mysql_fetch_array($sql2)) {
                $loan_code = $row['loan_code'];
                $loan_total_interest = $row['loan_total_interest'];
                $loan_date = $row['loan_date'];
                $loan_due_date = $row['loan_due_date'];
            }
            
            $sql2 = mysql_query("select sum(loan_rep_amount)repayments from loan_repayments where loan_rep_code = '$loan_code' group by loan_rep_code");
			while ($row = mysql_fetch_array($sql2)) {
				$repayments = $row['repayments'];
				if ($repayments == '') {
					$repayments = 0;
				}
			}
            
        	$balance = $loan_total_interest - $repayments;
        	
            if ($intcount % 2 == 0) {
                $display= '<tr bgcolor = #F0F0F6>';
            }
            else {
                $display= '<tr>';
            }
            echo $display;
    
    		echo "<td valign='top'>$id</td>";	
            echo "<td valign='top'>$mobile_number</td>";	
            echo "<td valign='top'>$first_name</td>";
            echo "<td valign='top'>$last_name</td>";
            echo "<td valign='top'>$national_id</td>";
            echo "<td valign='top'>$loan_date</td>";
            echo "<td valign='top'>$loan_due_date</td>";
            echo "<td valign='top'>$loan_total_interest</td>";
            echo "<td valign='top'>$repayments</td>";
            echo "<td valign='top'>$balance</td>";
            echo "</tr>";
            
            $mobile_number = '';
            $first_name = '';
            $last_name = '';
            $id = '';
            $national_id = '';
            $loan_total_interest = '';
            $repayments = '';
            $balance = '';
            $loan_due_date = '';
            $loan_date = '';
            
        }
?>
