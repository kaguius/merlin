<?php
	//include_once('includes/header.php');
	include_once('includes/db_conn.php');
	
	$filter_month = date("m");
	$filter_year = date("Y");
	$filter_day = date("d");
	$current_date = $filter_year.'-'.$filter_month.'-'.$filter_day;
	$audit_date = '2014-12-31';
?>
	
	<table width="100%" border="0" cellspacing="2" cellpadding="2" class="display" id="exampl">
		<thead bgcolor="#E6EEEE">
			<tr>
				<th>ID</th>
				<th>Transactiontime</th>
				<th>Affordability</th>
			</tr>
		</thead>
		<tbody>
	<?php
	$sql20 = mysql_query("select loan_code from pkf_data order by id asc");
	while ($row = mysql_fetch_array($sql20)){
		$loan_code_extract = $row['loan_code'];
		
		$sql = mysql_query("select id, transactiontime, affordability from users where dis_phone = '$loan_code_extract'");
		while ($row = mysql_fetch_array($sql)){
			$id = $row['id'];
			$transactiontime = $row['transactiontime'];
			$affordability = $row['affordability'];
			
			if ($intcount % 2 == 0) {
				$display= '<tr bgcolor = #F0F0F6>';
			}
			else {
				$display= '<tr>';
			}
		
			echo $display;
			echo "<td valign='top'>$id</td>";
			echo "<td valign='top'>$transactiontime</td>";
			echo "<td valign='top'>$affordability</td>";
			echo "</tr>";
		
			$id = "";
			$transactiontime = "";
		}
	}
?>