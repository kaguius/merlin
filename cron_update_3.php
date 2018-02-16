<?php
	//include_once('includes/header.php');
	include_once('includes/db_conn.php');
	
	$filter_month = date("m");
	$filter_year = date("Y");
	$filter_day = date("d");
	$current_date = $filter_year.'-'.$filter_month.'-'.$filter_day;
	
	$total_loan_count_rep = 0;
	$sql2 = mysql_query("select count(loan_id)loan_count_rep from loan_application where extract(month from loan_due_date) = '01' and extract(year from loan_due_date) = '2015' and initiation_fee = '0' group by customer_id order by loan_count_rep desc");
	while ($row = mysql_fetch_array($sql2))
	{
		$loan_count_rep = $row['loan_count_rep'];
		if($loan_count_rep == 1){
			//$loan_count_rep = 1;
			$total_loan_count_rep = $total_loan_count_rep + $loan_count_rep;
		}
	}
	echo number_format($total_loan_count_rep, 0)."<br />";
	$total_loan_count_rep = 0;
	$sql2 = mysql_query("select count(loan_id)loan_count_rep from loan_application where extract(month from loan_due_date) = '02' and extract(year from loan_due_date) = '2015' and initiation_fee = '0' group by customer_id order by loan_count_rep desc");
	while ($row = mysql_fetch_array($sql2))
	{
		$loan_count_rep = $row['loan_count_rep'];
		if($loan_count_rep == 1){
			//$loan_count_rep = 1;
			$total_loan_count_rep = $total_loan_count_rep + $loan_count_rep;
		}
	}
	echo number_format($total_loan_count_rep, 0)."<br />";
	$total_loan_count_rep = 0;
	$sql2 = mysql_query("select count(loan_id)loan_count_rep from loan_application where extract(month from loan_due_date) = '03' and extract(year from loan_due_date) = '2015' and initiation_fee = '0' group by customer_id order by loan_count_rep desc");
	while ($row = mysql_fetch_array($sql2))
	{
		$loan_count_rep = $row['loan_count_rep'];
		if($loan_count_rep == 1){
			//$loan_count_rep = 1;
			$total_loan_count_rep = $total_loan_count_rep + $loan_count_rep;
		}
	}
	echo number_format($total_loan_count_rep, 0)."<br />";
	$total_loan_count_rep = 0;
	$sql2 = mysql_query("select count(loan_id)loan_count_rep from loan_application where extract(month from loan_due_date) = '04' and extract(year from loan_due_date) = '2015' and initiation_fee = '0' group by customer_id order by loan_count_rep desc");
	while ($row = mysql_fetch_array($sql2))
	{
		$loan_count_rep = $row['loan_count_rep'];
		if($loan_count_rep == 1){
			//$loan_count_rep = 1;
			$total_loan_count_rep = $total_loan_count_rep + $loan_count_rep;
		}
	}
	echo number_format($total_loan_count_rep, 0)."<br />";
	$total_loan_count_rep = 0;
	$sql2 = mysql_query("select count(loan_id)loan_count_rep from loan_application where extract(month from loan_due_date) = '05' and extract(year from loan_due_date) = '2015' and initiation_fee = '0' group by customer_id order by loan_count_rep desc");
	while ($row = mysql_fetch_array($sql2))
	{
		$loan_count_rep = $row['loan_count_rep'];
		if($loan_count_rep == 1){
			//$loan_count_rep = 1;
			$total_loan_count_rep = $total_loan_count_rep + $loan_count_rep;
		}
	}
	echo number_format($total_loan_count_rep, 0)."<br />";
	$total_loan_count_rep = 0;
	$sql2 = mysql_query("select count(loan_id)loan_count_rep from loan_application where extract(month from loan_due_date) = '06' and extract(year from loan_due_date) = '2015' and initiation_fee = '0' group by customer_id order by loan_count_rep desc");
	while ($row = mysql_fetch_array($sql2))
	{
		$loan_count_rep = $row['loan_count_rep'];
		if($loan_count_rep == 1){
			//$loan_count_rep = 1;
			$total_loan_count_rep = $total_loan_count_rep + $loan_count_rep;
		}
	}
	echo number_format($total_loan_count_rep, 0)."<br />";
	$total_loan_count_rep = 0;
	$sql2 = mysql_query("select count(loan_id)loan_count_rep from loan_application where extract(month from loan_due_date) = '07' and extract(year from loan_due_date) = '2015' and initiation_fee = '0' group by customer_id order by loan_count_rep desc");
	while ($row = mysql_fetch_array($sql2))
	{
		$loan_count_rep = $row['loan_count_rep'];
		if($loan_count_rep == 1){
			//$loan_count_rep = 1;
			$total_loan_count_rep = $total_loan_count_rep + $loan_count_rep;
		}
	}
	echo number_format($total_loan_count_rep, 0)."<br />";
	$total_loan_count_rep = 0;
	$sql2 = mysql_query("select count(loan_id)loan_count_rep from loan_application where extract(month from loan_due_date) = '08' and extract(year from loan_due_date) = '2015' and initiation_fee = '0' group by customer_id order by loan_count_rep desc");
	while ($row = mysql_fetch_array($sql2))
	{
		$loan_count_rep = $row['loan_count_rep'];
		if($loan_count_rep == 1){
			//$loan_count_rep = 1;
			$total_loan_count_rep = $total_loan_count_rep + $loan_count_rep;
		}
	}
	echo number_format($total_loan_count_rep, 0)."<br />";
	$total_loan_count_rep = 0;
	$sql2 = mysql_query("select count(loan_id)loan_count_rep from loan_application where extract(month from loan_due_date) = '09' and extract(year from loan_due_date) = '2015' and initiation_fee = '0' group by customer_id order by loan_count_rep desc");
	while ($row = mysql_fetch_array($sql2))
	{
		$loan_count_rep = $row['loan_count_rep'];
		if($loan_count_rep == 1){
			//$loan_count_rep = 1;
			$total_loan_count_rep = $total_loan_count_rep + $loan_count_rep;
		}
	}
	echo number_format($total_loan_count_rep, 0)."<br />";
	$total_loan_count_rep = 0;
	$sql2 = mysql_query("select count(loan_id)loan_count_rep from loan_application where extract(month from loan_due_date) = '10' and extract(year from loan_due_date) = '2015' and initiation_fee = '0' group by customer_id order by loan_count_rep desc");
	while ($row = mysql_fetch_array($sql2))
	{
		$loan_count_rep = $row['loan_count_rep'];
		if($loan_count_rep == 1){
			//$loan_count_rep = 1;
			$total_loan_count_rep = $total_loan_count_rep + $loan_count_rep;
		}
	}
	echo number_format($total_loan_count_rep, 0)."<br />";
	$total_loan_count_rep = 0;
	$sql2 = mysql_query("select count(loan_id)loan_count_rep from loan_application where extract(month from loan_due_date) = '11' and extract(year from loan_due_date) = '2015' and initiation_fee = '0' group by customer_id order by loan_count_rep desc");
	while ($row = mysql_fetch_array($sql2))
	{
		$loan_count_rep = $row['loan_count_rep'];
		if($loan_count_rep == 1){
			//$loan_count_rep = 1;
			$total_loan_count_rep = $total_loan_count_rep + $loan_count_rep;
		}
	}
	echo number_format($total_loan_count_rep, 0)."<br />";
	$total_loan_count_rep = 0;
	$sql2 = mysql_query("select count(loan_id)loan_count_rep from loan_application where extract(month from loan_due_date) = '12' and extract(year from loan_due_date) = '2015' and initiation_fee = '0' group by customer_id order by loan_count_rep desc");
	while ($row = mysql_fetch_array($sql2))
	{
		$loan_count_rep = $row['loan_count_rep'];
		if($loan_count_rep == 1){
			//$loan_count_rep = 1;
			$total_loan_count_rep = $total_loan_count_rep + $loan_count_rep;
		}
	}
	echo number_format($total_loan_count_rep, 0)."<br />";
?>