<?php
	$userid = "";
	$adminstatus = 4;
	$property_manager_id = "";
	$fileupload = "";
	
	session_start();
	if (!empty($_SESSION)){
		$userid = $_SESSION["userid"] ;
		$adminstatus = $_SESSION["adminstatus"] ;
		$station = $_SESSION["station"] ;
		$username = $_SESSION["username"];
		$fileupload = $_SESSION["fileupload"];
	}
	
	$filter_month = date("m");
    $filter_year = date("Y");
    $filter_day = date("d");
    $current_date = $filter_year . '-' . $filter_month . '-' . $filter_day;

	//if($adminstatus != 1 || $adminstatus != 2 || $adminstatus != 4){
	if($adminstatus == 4){
		include_once('includes/header.php');
		?>
		<script type="text/javascript">
			document.location = "login.php";
		</script>
		<?php
	}
	else{
		include_once('includes/db_conn.php');
		$transactiontime = date("Y-m-d G:i:s");
		$page_title = "Manual Loan Upload";
		include_once('includes/header.php');
		?>		
		<div id="page">
			<div id="content">
				<div class="post">
					<h2><font color="#000A8B"><?php echo $page_title ?></h2>
					<form id="frmApply" name="frmApply" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
					<table class="dataTable" width="90%" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td valign="top"><strong>Upload Manual loans: *</strong></td>
							<td valign="top"><a href="upload_loanform.php">Click here to Upload the copy of the manual loans</a><br />
							<input id="fileupload" name="fileupload" value="<?php echo $fileupload ?>" type="text" readonly="true" size="30"/> (Format: csv file, File Size: 1MB)</td>
						</tr>
					</table>
					<table border="0" width="100%">
						<tr>
							<td valign="top">
								<button name="btnNewCard" id="button">Submit</button>
							</td>
							<td align="right">
								<button name="reset" id="button2" type="reset">Reset</button>
							</td>		
						</tr>
					</table>
					</form>
				</div>
			</div>
			<br class="clearfix" />
			</div>
		</div>
<?php
	if (!empty($_POST)) {
		$fileupload=$_POST['fileupload'];
		
		define('CSV_PATH','/var/www/afb/'); 
		// path where your CSV file is located

		$csv_file = CSV_PATH . $fileupload; // Name of your CSV file
		$csvfile = fopen($csv_file, 'r');
		$theData = fgets($csvfile);
		$i = 0;
		while (!feof($csvfile)) {
			$csv_data[] = fgets($csvfile, 1024);
			$csv_array = explode(",", $csv_data[$i]);
			
			$insert_csv = array();
			$insert_csv['Disbursement Number'] = $csv_array[0];
			$insert_csv['Amount'] = $csv_array[1];
			$insert_csv['MMID'] = $csv_array[2];
			$loan_mobile = trim($csv_array[0]);
			$loan_amount = trim($csv_array[1]);
			$loan_mpesa_code = trim($csv_array[2]);
			
			$dis_phone_prefix = substr($loan_mobile, 0, 5);
            if ($dis_phone_prefix == 25470 || $dis_phone_prefix == 25471 || $dis_phone_prefix == 25472 || $dis_phone_prefix == 25479) {
                $mobile_carrier = 'Safaricom';
            }
            else if ($dis_phone_prefix == 25473 || $dis_phone_prefix == 25475 || $dis_phone_prefix == 25478) {
                $mobile_carrier = 'Airtel';
            }
            else if ($dis_phone_prefix == 25477) {
                $mobile_carrier = 'Orange Mobile';
            }
            else if ($dis_phone_prefix == 25476) {
                $mobile_carrier = 'Equitel';
            }
			
			$sql = mysql_query("select id, mobile_no, dis_phone, stations, loan_officer, collections_officer from users where dis_phone = '$loan_mobile' or mobile_no = '$loan_mobile'");
			while ($row = mysql_fetch_array($sql))
			{					
				$customer_id = $row['id'];
				$mobile_no = $row['mobile_no'];
				$dis_phone = $row['dis_phone'];
				$customer_station = $row['stations'];
				$loan_officer = $row['loan_officer'];
				$collections_officer = $row['collections_officer'];
			}
			
			$sql = mysql_query("select count(loan_id)loan_count from loan_application where customer_id = '$customer_id' and loan_status != '12' and loan_status != '11' and loan_status != '14'");
            while ($row = mysql_fetch_array($sql)) {
                $loan_count = $row['loan_count'];
                if ($loan_count == "") {
                    $loan_count = 0;
                }
            }
			
			$loan_term = 30;
			$loan_date = $current_date;
			$loan_due_date = date('Y-m-d', strtotime($loan_date) + (24 * 3600 * $loan_term));
            $loan_due_date_day = date("l", strtotime($loan_due_date));
            $loan_interest = $loan_amount * ($loan_term / 100);
            $loan_total_interest = $loan_interest + $loan_amount;
			
			if ($loan_count == 0) {
                $initiation_fee = 0;
                $sql = mysql_query("select fee from feez where category = 'initiation_fee'");
                while ($row = mysql_fetch_array($sql)) {
                    $initiation_fee = $row['fee'];
                }
                $loan_total_interest = $loan_total_interest + $initiation_fee;
            }
            
            $sql = mysql_query("select holiday_name from holiday_names where holiday_date = '$current_date'");
            while ($row = mysql_fetch_array($sql)) {
                $holiday_name = $row['holiday_name'];
                if ($holiday_name != "") {
                    $comments = 'holiday_exists';
                }
            }

            if ($loan_due_date_day == 'Saturday') {
                $days = 2;
                $loan_term = $days;
                $loan_due_date = date('Y-m-d', strtotime($loan_due_date) + (24 * 3600 * $loan_term));
                //echo $loan_due_date."<br />";
            }
            else if ($loan_due_date_day == 'Sunday') {
                $days = 1;
                $loan_term = $days;
                $loan_due_date = date('Y-m-d', strtotime($loan_due_date) + (24 * 3600 * $loan_term));
                //echo $loan_due_date."<br />";
            }
            else if ($comments == 'holiday_exists') {
                $days = 1;
                $loan_term = $days;
                $loan_due_date = date('Y-m-d', strtotime($loan_due_date) + (24 * 3600 * $loan_term));
                //echo $loan_due_date."<br />";
            }
            else {
                $loan_term = $loan_term;
                $loan_due_date = date('Y-m-d', strtotime($loan_date) + (24 * 3600 * $loan_term));
                //echo $loan_due_date."<br />";
            }
            
            $sql = mysql_query("select id from loan_code");
            while ($row = mysql_fetch_array($sql)) {
                $loan_code_latest = $row['id'];
            }
            
            $comment = '20160510_ManualLoansUpload';
            
            if ($mobile_carrier == 'Safaricom') {
                $sql1 = "INSERT INTO loan_application (loan_date, loan_term, loan_due_date, customer_id, loan_mobile, initiation_fee, loan_amount, loan_interest, loan_total_interest, loan_status, loan_code, loan_mpesa_code, loan_disbursed, loan_failure_status, loan_officer, collections_officer, comment, UID, customer_station, loan_creation)
                VALUES('$loan_date', '$loan_term', '$loan_due_date', '$customer_id', '$dis_phone', '$initiation_fee', '$loan_amount', '$loan_interest', '$loan_total_interest', '2', '$loan_code_latest', '$loan_mpesa_code', '0', '0', '$loan_officer', '$collections_officer', '$comment', '$loan_officer', '$customer_station', '1')";

                $sql2 = "INSERT INTO call_center (loan_date, loan_term, loan_due_date, customer_id, loan_mobile, initiation_fee, loan_amount, loan_interest, loan_total_interest, loan_status, loan_code, loan_mpesa_code, loan_disbursed, loan_failure_status, loan_officer, collections_officer, comment, UID, customer_station, loan_creation)
                VALUES('$loan_date', '$loan_term', '$loan_due_date', '$customer_id', '$dis_phone', '$initiation_fee', '$loan_amount', '$loan_interest', '$loan_total_interest', '2', '$loan_code_latest', '$loan_mpesa_code', '0', '0', '$loan_officer', '$collections_officer', '$comment', '$loan_officer', '$customer_station', '1')";
            }
            else if ($mobile_carrier == 'Airtel') {
                $sq1 = "INSERT INTO loan_application (loan_date, loan_term, loan_due_date, customer_id, loan_mobile, initiation_fee, loan_amount, loan_interest, loan_total_interest, loan_status, loan_code, loan_mpesa_code, loan_disbursed, loan_failure_status, loan_officer, collections_officer, comment, UID, customer_station, loan_creation)
                VALUES('$loan_date', '$loan_term', '$loan_due_date', '$customer_id', '$dis_phone', '$initiation_fee', '$loan_amount', '$loan_interest', '$loan_total_interest', '2', '$loan_code_latest', '$loan_mpesa_code', '0', '0', '$loan_officer', '$collections_officer', '$comment', '$loan_officer', '$customer_station', '1')";

                $sql2 = "INSERT INTO call_center (loan_date, loan_term, loan_due_date, customer_id, loan_mobile, initiation_fee, loan_amount, loan_interest, loan_total_interest, loan_status, loan_code, loan_mpesa_code, loan_disbursed, loan_failure_status, loan_officer, collections_officer, comment, UID, customer_station, loan_creation)
                VALUES('$loan_date', '$loan_term', '$loan_due_date', '$customer_id', '$dis_phone', '$initiation_fee', '$loan_amount', '$loan_interest', '$loan_total_interest', '2', '$loan_code_latest', '$loan_mpesa_code', '0', '0', '$loan_officer', '$collections_officer', '$comment', '$loan_officer', '$customer_station', '1')";
            }

            $sql = mysql_query("select distinct loan_id from loan_application order by loan_id desc limit 1");
            while ($row = mysql_fetch_array($sql)) {
                $loan_id_latest = $row['loan_id'];
            }
            $sql3 = "insert into change_log(UID, table_name, customer_id, variable, old_value, new_value, transactiontime, comment)values('$userid', 'loan_application', '$customer_id', 'loan', '0', '$loan_amount', '$transactiontime', '$comment')";

            $sql = mysql_query("select loan_balance from overpayments_schedule where customer_id = '$customer_id' order by id desc limit 1");
            while ($row = mysql_fetch_array($sql)) {
                $loan_balance = $row['loan_balance'];
            }
            //echo $loan_balance."<br />";
            if ($loan_balance > 0) {
                $loan_balance = -$loan_balance;
                $loan_total_interest = $loan_total_interest + $loan_balance;
                $sql4 = "update loan_application set early_settlement_surplus='$loan_balance', loan_total_interest='$loan_total_interest' where loan_code = '$loan_code_latest'";
                $sql5 = "update overpayments_schedule set loan_balance= null where customer_id = '$customer_id' and loan_code =  '$loan_code'";
            }

            $loan_code_latest = $loan_code_latest + 1;

            $sql6 = "update loan_code set id='$loan_code_latest'";
            
            echo $sql1."<br />";
            $result = mysql_query($sql1);
            $result = mysql_query($sql2);
	 		$result = mysql_query($sql3);
	 		$result = mysql_query($sql4);
	 		$result = mysql_query($sql5);
            $result = mysql_query($sql6);
            
			$i++;
			$customer_id = "";
			$loan_code = "";
		}
		fclose($csvfile);

		echo "File data successfully imported to database!!";
	}
}
	include_once('includes/footer.php');
?>
