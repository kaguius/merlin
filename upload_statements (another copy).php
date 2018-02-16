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
		$page_title = "Upload Mpesa Paybill C2B Statement(s)";
		include_once('includes/header.php');
		?>		
		<div id="page">
			<div id="content">
				<div class="post">
					<h2><font color="#000A8B"><?php echo $page_title ?></h2>
					<form id="frmApply" name="frmApply" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
					<table class="dataTable" width="90%" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td valign="top"><strong>Upload Mpesa Statement: *</strong></td>
							<td valign="top"><a href="uploadform.php">Click here to Upload the copy of the MPESA Paybill C2B Statement</a><br />
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
		
		$sql3="INSERT INTO mpesa_file_uploads (transactiontime, filename, UID)
		VALUES('$transactiontime', '$fileupload', '$userid')";
		
		//echo $sql3;
		$result = mysql_query($sql3);
		
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
			$insert_csv['Receipt'] = $csv_array[0];
			$insert_csv['Date'] = $csv_array[1];
			$insert_csv['Details'] = $csv_array[2];
			$insert_csv['Status'] = $csv_array[3];
			$insert_csv['Withdrawn'] = $csv_array[4];
			$insert_csv['Paid In'] = $csv_array[5];
			$insert_csv['Balance '] = $csv_array[6];
			$insert_csv['Balance Confirmed'] = $csv_array[7];
			$insert_csv['Transaction Type'] = $csv_array[8];
			$insert_csv['Other Party Info'] = $csv_array[9];
			$insert_csv['Transaction Party Details'] = $csv_array[10];
			$insert_csv['Transaction Party Details'] = trim($insert_csv['Transaction Party Details']);
			
			$query = "INSERT INTO mpesa_payments_transactions(receipt, date, details, status, withdrawn, paid_in, balance, balance_confirmed, trans_type, other_party_info, trans_party_details)
			VALUES('".$insert_csv['Receipt']."','".$insert_csv['Date']."','".$insert_csv['Details']."', '".$insert_csv['Status']."', '".$insert_csv['Withdrawn']."', '".$insert_csv['Paid In']."', '".$insert_csv['Balance']."', '".$insert_csv['Balance Confirmed']."', '".$insert_csv['Transaction Type']."', '".$insert_csv['Other Party Info']."', '".$insert_csv['Transaction Party Details']."')";
			//echo $query."<br />";
			$result = mysql_query($query);
			
			$loan_code = trim($csv_array[10]);
			$status = $csv_array[3];
			$receipt = $csv_array[0];

			//echo $loan_code."<br />";
			//echo $status."<br />";
			//echo $receipt."<br />";
			
			$sql = mysql_query("select customer_id, customer_station, loan_status from loan_application where loan_code = '$loan_code'");
			while ($row = mysql_fetch_array($sql))
			{					
				$customer_id = $row['customer_id'];
				$customer_station = $row['customer_station'];
				$loan_status = $row['loan_status'];
			}
			//echo $customer_id."<br />";
			$mobile = substr($insert_csv['Other Party Info'], 1, 9);
			$mobile = "254".$mobile;
			//echo $mobile."<br />";
			//echo $customer_id."<br />";
			//echo "select customer_id, customer_station, loan_status from loan_application where loan_code = '$loan_code'";
			
			if($status == 'Completed' && $customer_id != ""){
				$sql2 = "INSERT INTO loan_repayments(loan_rep_date, customer_id, customer_station, loan_rep_mobile, loan_rep_amount, loan_rep_mpesa_code, loan_rep_code, UID, loan_status)
				VALUES('".$insert_csv['Date']."', '$customer_id', '$customer_station', '$mobile', '".$insert_csv['Paid In']."', '".$insert_csv['Receipt']."', '$loan_code', '$userid', '$loan_status')";
				//echo "Loan Repayment<br />";
				//echo $sql2."<br />";
				$result = mysql_query($sql2);
			}
			else{
				$sql2 = "INSERT INTO suspence_accounts(receipt, date, details, status, withdrawn, paid_in, balance, balance_confirmed, trans_type, other_party_info, trans_party_details)
			VALUES('".$insert_csv['Receipt']."','".$insert_csv['Date']."','".$insert_csv['Details']."', '".$insert_csv['Status']."', '".$insert_csv['Withdrawn']."', '".$insert_csv['Paid In']."', '".$insert_csv['Balance']."', '".$insert_csv['Balance Confirmed']."', '".$insert_csv['Transaction Type']."', '".$insert_csv['Other Party Info']."', '".$insert_csv['Transaction Party Details']."')";
				//echo "Suspense Details<br />";
				//echo $sql2."<br />";
				$result = mysql_query($sql2);
			}
			
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
