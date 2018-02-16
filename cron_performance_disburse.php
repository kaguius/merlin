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
				<th>LO</th>
				<th>CO</th>
				<th>Branch</th>
				<th>Target</th>
				<th>Disbursed</th>
				<th>Rate</th>
			</tr>
		</thead>
		<tbody>
	<?php

	$sql = mysql_query("select distinct loan_officer, sum(loan_amount)disbursed, collections_officer from loan_application where loan_date between '2014-12-01' and '2014-12-31' group by loan_officer");
	while ($row = mysql_fetch_array($sql))
	{
		$loan_officer = $row['loan_officer'];
		$disbursed = $row['disbursed'];
		$collections_officer = $row['collections_officer'];
		
		$sql2 = mysql_query("select first_name, last_name, station from user_profiles where id = '$loan_officer'");
		while ($row = mysql_fetch_array($sql2))
		{
			$first_name = $row['first_name'];
			$last_name = $row['last_name'];
			$station = $row['station'];
			$first_name = ucwords(strtolower($first_name));	
			$last_name = ucwords(strtolower($last_name));
			$loan_officer_name = $first_name.' '.$last_name;		
		}
		
		$sql2 = mysql_query("select first_name, last_name, station from user_profiles where id = '$collections_officer'");
		while ($row = mysql_fetch_array($sql2))
		{
			$first_name = $row['first_name'];
			$last_name = $row['last_name'];
			$first_name = ucwords(strtolower($first_name));	
			$last_name = ucwords(strtolower($last_name));
			$collections_officer_name = $first_name.' '.$last_name;		
		}
		
		$sql2 = mysql_query("select stations, monthly_target from stations where id = '$station'");
		while ($row = mysql_fetch_array($sql2))
		{
			$stations = $row['stations'];
			$monthly_target = $row['monthly_target'];	
		}
		$ind_target = $monthly_target / 2;
		$rate = ($disbursed / $ind_target) * 100;

		if ($intcount % 2 == 0) {
			$display= '<tr bgcolor = #F0F0F6>';
		}
		else {
			$display= '<tr>';
		}
		echo $display;
		echo "<td valign='top'>$loan_officer_name</td>";
		echo "<td valign='top'>$collections_officer_name</td>";
		echo "<td valign='top'>$stations</td>";
		echo "<td align='right' valign='top'>".number_format($ind_target, 2)."</td>";	
		echo "<td align='right' valign='top'>".number_format($disbursed, 2)."</td>";
		echo "<td align='right' valign='top'>".number_format($rate, 2)."%</td>";		
		echo "</tr>";
		$disbursed = 0;
		$collections_officer = '';
		$loan_officer_name = '';
	}
?>