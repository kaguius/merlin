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
                <th>First Name</th>
                <th>Last Name</th>
                <th>Mobile No</th>
                <th>National ID</th>
                <th>Branch</th>
                <th>LO</th>
                <th>CO</th>
                <th>Market</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $sql = mysql_query("select id, first_name, last_name, mobile_no, market, national_id, stations, loan_officer, collections_officer from users where stations = '9'");
        while ($row = mysql_fetch_array($sql))
        {
            $id = $row['id'];
            $first_name = $row['first_name'];
            $last_name = $row['last_name'];
            $mobile_no = $row['mobile_no'];
            $national_id = $row['national_id'];
            $loan_officer = $row['loan_officer'];
            $collections_officer = $row['collections_officer'];
            $stations = $row['stations'];
            $market = $row['market'];
        
            $sql2 = mysql_query("select id, first_name, last_name from user_profiles where id = '$loan_officer'");
            while($row = mysql_fetch_array($sql2)) {
                $loan_first_name = $row['first_name'];
                $loan_last_name = $row['last_name'];
                $loan_officer_name = $loan_first_name." ".$loan_last_name;
            }
            $sql2 = mysql_query("select id, first_name, last_name from user_profiles where id = '$collections_officer'");
            while($row = mysql_fetch_array($sql2)) {
                $collect_first_name = $row['first_name'];
                $collect_last_name = $row['last_name'];
                $collect_officer_name = $collect_first_name." ".$collect_last_name;
            }
            $sql2 = mysql_query("select stations from stations where id = '$stations'");
            while($row = mysql_fetch_array($sql2)) {
                $stations_name = $row['stations'];
            }
            
            $sql2 = mysql_query("select market from markets where id = '$market'");
            while($row = mysql_fetch_array($sql2)) {
                $market_name = $row['market'];
            }
        
            if ($intcount % 2 == 0) {
                $display= '<tr bgcolor = #F0F0F6>';
            }
            else {
                $display= '<tr>';
            }
            echo $display;
    
            echo "<td valign='top'>$id</td>";
            echo "<td valign='top'>$first_name</td>";
            echo "<td valign='top'>$last_name</td>";	
            echo "<td valign='top'>$mobile_no</td>";	
            echo "<td valign='top'>$national_id</td>";
            echo "<td valign='top'>$stations_name</td>";
            echo "<td valign='top'>$loan_officer_name</td>";	
            echo "<td valign='top'>$collect_officer_name</td>";	
            echo "<td valign='top'>$market_name</td>";	
            echo "</tr>";
            
            $id = '';
            $first_name = '';
            $last_name = '';
            $mobile_no = '';
            $national_id = '';
            $stations_name = '';
            $loan_officer_name = '';
            $collect_officer_name = '';
            $market = '';
            $market_name = '';
        }
?>
