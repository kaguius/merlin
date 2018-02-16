<?php
	$userid = "";
	$adminstatus = 4;
	$property_manager_id = "";
	session_start();
	if (!empty($_SESSION)){
		$userid = $_SESSION["userid"] ;
		$adminstatus = $_SESSION["adminstatus"] ;
		$username = $_SESSION["username"];
		$station = $_SESSION["station"] ;
	}

	//if($adminstatus != 1 || $adminstatus != 2 || $adminstatus != 4){
	if($adminstatus == 4){
		include_once('includes/header.php');
		?>
		<script type="text/javascript">
			document.location = "insufficient_permission.php";
		</script>
		<?php
	}
	else{
		$transactiontime = date("Y-m-d G:i:s");
		$page_title = "Loans Disbursed Report";
		include_once('includes/db_conn.php');
		
		$filter_month = date("m");
		$filter_year = date("Y");
		$filter_day = date("d");
		$current_date = $filter_year.'-'.$filter_month.'-'.$filter_day;
		$current_date_full = date("d M, Y", strtotime($current_date));
		
		$report_term = 7;
		$start_report_date = date('Y-m-d',strtotime($current_date) - (24 * 3600 * $report_term));

		if (!empty($_GET)){	
			$loan_officer = $_GET['loan_officer'];
			$filter_start_date = $_GET['report_start_date'];
			$filter_start_date = date('Y-m-d', strtotime(str_replace('-', '/', $filter_start_date)));
			$filter_end_date = $_GET['report_end_date'];
			$filter_end_date = date('Y-m-d', strtotime(str_replace('-', '/', $filter_end_date)));
		}
		$filter_start_date = '2015-10-17';
		$filter_end_date = '2015-11-30';
		$filter_repayments_date = '2016-02-29';
			
		?>
					<h2><?php echo $page_title ?></h2>
					<table width="100%" border="0" cellspacing="2" cellpadding="2" class="display" id="example2">
					<thead bgcolor="#E6EEEE">
						<tr>
							<th>#</th>
							<th>Customer_id</th>
							<th>Business Category</th>
							<th>Business Type </th>
							<th>Date started the business</th>
							<th>Date started in the location</th>
							<th>Business address</th>
							<th>Current Stock Value</th>
							<th>Weekly Sales</th>
							<th>Weekly Spend on Stock</th>
							<th>Business Rent</th>
							<th>Business Utilities</th>
							<th>Employees</th>
							<th>No. of Employees</th>
							
							<th>Licensing</th>
							<th>Storage</th>
							<th>Transport</th>
							<th>Personal Rent</th>
                            <th>Personal Utility Cost</th>
                            <th>School Fees Cost</th>
                            <th>Chama – do they belong in chama</th>
                            <th>How much do they pay weekly</th>
                            <th>How many members are in the chama</th>
                            <th>What is the chama payout</th>
                            <th>What is the frequency of payout</th>
                            <th>Stock neat</th>
                            <th>Accurate ledger book</th>
                            <th>Evidence of sales activity</th>
                            <th>Evidence of permanent operation</th>
                            <th>Proof of ownership</th>
                            <th>Forthcoming and transparent</th>
                            <th>Known to market authorities</th>
                            <th>Sound reputation</th>
                            <th>Would I lend</th>
                            <th>If yes, how much</th>
                            <th>Do you have a bank account</th>
                            <th>With what type of organization</th>
                            <th>Do you have other access to credit</th>
                            <th>With what organization</th>
                            <th>How many loans do you have</th>
                            <th>On average, how many customers do you serve</th>
                            <th>Timestamp</th>

						</tr>
					</thead>
					<tbody>
					<?php
						$sql = mysql_query("select id from users where stations IN ('9', '13', '17')");
						//$sql = mysql_query("select id from users where id = '11963'");
                        while($row = mysql_fetch_array($sql)) {
                            $customer_id = $row['id'];
                            
                            $sql2 = mysql_query("select user_id, business_category, business_type, trading_product, trading_location, business_address, stock_value, weekly_sales, spend_stock, business_rent, business_utilities, employees, licensing, storage, transport, house_rent, house_utilities, food_expense, school_fees, weekly_cont, chama_members, chama_payout, payout_freq, stock_neat, ledger_book, sales_activity, permanent_operation, proof_ownership, forthcoming, market_authorities, sound_reputation, lend, lend_amount, bank_account, bank_account_holder, credit, loan_account, loan_number, daily_customers, no_of_employees, transactiontime from business_details where user_id = '$customer_id'");
                            while ($row = mysql_fetch_array($sql2))
                            {
                                $intcount++;
                                $user_id = $row['user_id'];					
                                $business_category = $row['business_category'];
                                $business_type = $row['business_type'];
                                $trading_product = $row['trading_product'];
                                $trading_location = $row['trading_location'];
                                $business_address = $row['business_address'];
                                $stock_value = $row['stock_value'];
                                $weekly_sales = $row['weekly_sales'];
                                $spend_stock = $row['spend_stock'];
                                $business_rent = $row['business_rent'];
                                $business_utilities = $row['business_utilities'];
                                $employees = $row['employees'];
                                $no_of_employees = $row['no_of_employees'];
                                $licensing = $row['licensing'];
                                $storage = $row['storage'];
                                $transport = $row['transport'];
                                $house_rent = $row['house_rent'];
                                $house_utilities = $row['house_utilities'];
                                $food_expense = $row['food_expense'];
                                $school_fees = $row['school_fees'];
                                $weekly_cont = $row['weekly_cont'];
                                $chama_members = $row['chama_members'];
                                $chama_payout = $row['chama_payout'];
                                $payout_freq = $row['payout_freq'];
                                $stock_neat = $row['stock_neat'];
                                $ledger_book = $row['ledger_book'];
                                $sales_activity = $row['sales_activity'];
                                $permanent_operation = $row['permanent_operation'];
                                $proof_ownership = $row['proof_ownership'];
                                $forthcoming = $row['forthcoming'];
                                $market_authorities = $row['market_authorities'];
                                $sound_reputation = $row['sound_reputation'];
                                $lend = $row['lend'];
                                $lend_amount = $row['lend_amount'];
                                $bank_account = $row['bank_account'];
                                $bank_account_holder = $row['bank_account_holder'];
                                $credit = $row['credit'];
                                $transactiontime = $row['transactiontime'];
                                
                                $loan_account = $row['loan_account'];
                                $loan_number = $row['loan_number'];
                                $daily_customers = $row['daily_customers'];
                            
                                $sql3 = mysql_query("select business from business where id = '$business_category'");
                                while($row = mysql_fetch_array($sql3)) {
                                    $business = $row['business'];
                                }
                            
                                if ($intcount % 2 == 0) {
                                    $display= '<tr bgcolor = #F0F0F6>';
                                }
                                else {
                                    $display= '<tr>';
                                }
                            
                                echo $display;
                                echo "<td valign='top'>$intcount.</td>";
                                echo "<td valign='top'>$user_id</td>";	
                                echo "<td valign='top'>$business</td>";	
                                echo "<td valign='top'>$business_type</td>";
                                echo "<td valign='top'>$trading_product</td>";					
                                echo "<td valign='top'>$trading_location</td>";
                                echo "<td valign='top'>$business_address</td>";
                                echo "<td valign='top'>$stock_value</td>";	
                                echo "<td valign='top'>$weekly_sales</td>";
                                echo "<td valign='top'>$spend_stock</td>";					
                                echo "<td valign='top'>$business_rent</td>";
                                echo "<td valign='top'>$business_utilities</td>";
                                echo "<td valign='top'>$employees</td>";
                                echo "<td valign='top'>$no_of_employees</td>";
                                echo "<td valign='top'>$licensing</td>";
                                echo "<td valign='top'>$storage</td>";
                                echo "<td valign='top'>$transport</td>";
                                echo "<td valign='top'>$house_rent</td>";
                                echo "<td valign='top'>$house_utilities</td>";
                                echo "<td valign='top'>$food_expense</td>";
                                echo "<td valign='top'>$school_fees</td>";
                                echo "<td valign='top'>$chama_members</td>";
                                echo "<td valign='top'>$weekly_cont</td>";
                                echo "<td valign='top'>$chama_members</td>";
                                echo "<td valign='top'>$chama_payout</td>";
                                echo "<td valign='top'>$payout_freq</td>";
                                echo "<td valign='top'>$stock_neat</td>";
                                echo "<td valign='top'>$ledger_book</td>";
                                echo "<td valign='top'>$sales_activity</td>";
                                echo "<td valign='top'>$permanent_operation</td>";
                                echo "<td valign='top'>$proof_ownership</td>";
                                
                                echo "<td valign='top'>$forthcoming</td>";
                                echo "<td valign='top'>$market_authorities</td>";
                                echo "<td valign='top'>$sound_reputation</td>";
                                
                                echo "<td valign='top'>$lend_amount</td>";
                                echo "<td valign='top'>$bank_account</td>";
                                echo "<td valign='top'>$bank_account_holder</td>";
                                echo "<td valign='top'>$credit</td>";
                                echo "<td valign='top'>$loan_account</td>";
                                
                                echo "<td valign='top'>$loan_number</td>";
                                echo "<td valign='top'>$daily_customers</td>";
                                
                                echo "<td valign='top'>$transactiontime</td>";
                                echo "</tr>";
                            }
                        }
						?>
					</tbody>
				</table>

<?php
	}
	include_once('includes/footer.php');
?>
