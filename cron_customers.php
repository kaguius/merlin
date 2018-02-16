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
				<th>First Name</th>
				<th>Last Name</th>
				<th>Mobile</th>
				<th>National ID</th>
				<th>Date of Birth</th>
				<th>Alt Phone</th>
				<th>Dis Phone</th>
			    <th>Home Address</th>
				<th>Branch</th>
				<th>Loan Officer</th>
				<th>Collections Officer</th>
			</tr>
		</thead>
		<tbody>
	<?php

	$sql = mysql_query("select users.id, first_name, last_name, mobile_no, national_id, date_of_birth, alt_phone, dis_phone, home_address, stations.stations, loan_officer, collections_officer from users inner join stations on stations.id = users.stations order by ID asc");
	while ($row = mysql_fetch_array($sql))
	{
		$id = $row['id'];
		$customer_first_name = $row['first_name'];
		$customer_last_name = $row['last_name'];
		$customer_first_name = ucwords(strtolower($customer_first_name));	
		$customer_last_name = ucwords(strtolower($customer_last_name));
		$mobile_no = $row['mobile_no'];
		$national_id = $row['national_id'];
		$date_of_birth = $row['date_of_birth'];
		
		$alt_phone = $row['alt_phone'];
		$dis_phone = $row['dis_phone'];
		$home_address = $row['home_address'];
		$stations = $row['stations'];
		$loan_officer = $row['loan_officer'];
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
		if ($intcount % 2 == 0) {
			$display= '<tr bgcolor = #F0F0F6>';
		}
		else {
			$display= '<tr>';
		}
		echo $display;
		echo "<td valign='top'>$id</td>";
		echo "<td valign='top'>$customer_first_name</td>";
		echo "<td valign='top'>$customer_last_name</td>";
		echo "<td valign='top'>$mobile_no</td>";
		echo "<td valign='top'>$national_id</td>";
		echo "<td valign='top'>$date_of_birth</td>";	
		echo "<td valign='top'>$alt_phone</td>";
		echo "<td valign='top'>$dis_phone</td>";
		echo "<td valign='top'>$home_address</td>";
		echo "<td valign='top'>$stations</td>";
		echo "<td valign='top'>$loan_officer_name</td>";	
		echo "<td valign='top'>$collections_officer_name</td>";	
		echo "</tr>";
		
		$id = "";
		$customer_first_name = "";
		$customer_last_name = "";
		$mobile_no = "";
		$national_id = "";
		$date_of_birth = "";
		
		$alt_phone = "";
		$dis_phone = "";
		$home_address = "";
		$stations = "";
		$loan_officer = "";
		$collections_officer = "";
	}
?>