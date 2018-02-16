<?php
	//Updates the customer_id and customer_station on the db once loan_repayments are done

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
                <th>Affordability</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $sql = mysql_query("select user_id from statsketch_user_ids");
        while ($row = mysql_fetch_array($sql))
        {
            $user_id = $row['user_id'];
        
            $sql2 = mysql_query("select id, affordability from users where id = '$user_id'");
            while ($row = mysql_fetch_array($sql2))
            {
                $user_id = $row['id'];
                $affordability = $row['affordability'];
        
                if ($intcount % 2 == 0) {
                $display= '<tr bgcolor = #F0F0F6>';
                }
                else {
                    $display= '<tr>';
                }
                echo $display;
    
                echo "<td valign='top'>$user_id</td>";
                echo "<td valign='top'>$affordability</td>";
                echo "</tr>";
            
            }
        }
?>