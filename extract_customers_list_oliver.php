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
				<th>Customer Name</th>
				<th>Mobile</th>
				<th>Disbursement</th>
				<th>Business Type</th>
				<th>Business Category</th>
				<th>Business Address</th>
				<th>Residence</th>
				<th>Ref Name</th>
			    <th>Ref Number</th>
				<th>Ref Relationship</th>
				<th>Ref Name</th>
			    <th>Ref Number</th>
				<th>Ref Relationship</th>
				<th>Loan Code</th>
				<th>Loan Due Date</th>
				<th>Loan Amount</th>
				<th>Repayment</th>
				<th>Balance</th>
			</tr>
		</thead>
		<tbody>
	<?php
	$sql20 = mysql_query("select distinct loan_mobile from loan_application where customer_station = '1' order by loan_id asc");
	while ($row = mysql_fetch_array($sql20)){
		$loan_code_extract = $row['loan_mobile'];
		
		$sql = mysql_query("select id, concat(first_name, ' ', last_name)customer_name, mobile_no, dis_phone, home_address, ref_first_name, ref_last_name, ref_phone_number, ref_relationship, ref_landlord_first_name, ref_landlord_last_name, ref_landlord_relationship, ref_landlord_phone from users where dis_phone = '$loan_code_extract' order by first_name asc");
		while ($row = mysql_fetch_array($sql)){
			$id = $row['id'];
			$customer_name = $row['customer_name'];
			$mobile_no = $row['mobile_no'];
			$dis_phone = $row['dis_phone'];
			$home_address = $row['home_address'];
		
			$ref_first_name = $row['ref_first_name'];
			$ref_last_name = $row['ref_last_name'];
			$ref_name = $ref_first_name.' '.$ref_last_name;
			$ref_phone_number = $row['ref_phone_number'];
			$ref_relationship = $row['ref_relationship'];
		
			$ref_landlord_first_name = $row['ref_landlord_first_name'];
			$ref_landlord_last_name = $row['ref_landlord_last_name'];
			$ref2_name = $ref_landlord_first_name.' '.$ref_landlord_last_name;
			$ref_phone_number = $row['ref_phone_number'];
			$ref_landlord_relationship = $row['ref_landlord_relationship'];
			$ref_landlord_phone = $row['ref_landlord_phone'];
	
			$sql2 = mysql_query("select distinct user_id, business.business, business_type, business_address from business_details inner join business on business.id = business_details.business_category where user_id = '$id' group by user_id");
			while ($row = mysql_fetch_array($sql2)){
				$business = $row['business'];
				$business_type = $row['business_type'];
				$business_address = $row['business_address'];
			}
		
			$sql2 = mysql_query("select loan_code, loan_due_date, loan_total_interest from loan_application where customer_id = '$id' and loan_status not in ('12', '13') order by loan_date desc limit 1");
			while ($row = mysql_fetch_array($sql2)){
				$loan_code = $row['loan_code'];	
				$loan_due_date = $row['loan_due_date'];		
				$loan_total_interest = $row['loan_total_interest'];		
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
			echo "<td valign='top'>$customer_name</td>";
			echo "<td valign='top'>$mobile_no</td>";
			echo "<td valign='top'>$dis_phone</td>";
			echo "<td valign='top'>$business_type</td>";
			echo "<td valign='top'>$business</td>";	
			echo "<td valign='top'>$business_address</td>";
			echo "<td valign='top'>$home_address</td>";
			echo "<td valign='top'>$ref_name</td>";
			echo "<td valign='top'>$ref_phone_number</td>";
			echo "<td valign='top'>$ref_relationship</td>";	
			echo "<td valign='top'>$ref2_name</td>";
			echo "<td valign='top'>$ref_landlord_phone</td>";
			echo "<td valign='top'>$ref_landlord_relationship</td>";	
			echo "<td valign='top'>$loan_due_date</td>";	
			echo "<td valign='top'>$loan_code</td>";		
			echo "<td valign='top' align='right'>".number_format($loan_total_interest, 2)."</td>";
			echo "<td valign='top' align='right'>".number_format($repayments, 2)."</td>";
			echo "<td valign='top' align='right'>".number_format($balance, 2)."</td>";
			echo "</tr>";
		
			$id = "";
			$customer_name = "";
			$mobile_no = "";
			$dis_phone = "";
			$business_type = "";
			$business = "";
		
			$business_address = "";
			$home_address = "";
			$ref_name = "";
			$ref_phone = "";
			$ref_relationship = "";
			$ref2_name = "";
			$ref_landlord_phone = "";
			$ref_landlord_relationship = "";
			$loan_due_date = "";
		
			$loan_code = "";
			$loan_total_interest = 0;
			$repayments = 0;
			$balance = 0;
		}
	}
?>