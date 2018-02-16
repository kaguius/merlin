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
		$page_title = "Upload Airtel Paybill C2B Statement(s)";
		include_once('includes/header.php');
		?>		
		<div id="page">
			<div id="content">
				<div class="post">
					<h2><font color="#000A8B"><?php echo $page_title ?></h2>
					<form id="frmApply" name="frmApply" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
					<table class="dataTable" width="90%" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td valign="top"><strong>Upload Airtel Statement: *</strong></td>
							<td valign="top"><a href="uploadform_airtel.php">Click here to Upload the copy of the Airtel Paybill C2B Statement</a><br />
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
		//$result = mysql_query($sql3);
		
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
			$insert_csv['Date'] = $csv_array[0];
			$insert_csv['Reference'] = $csv_array[1];
			$insert_csv['Source'] = $csv_array[2];
			$insert_csv['Destination'] = $csv_array[3];
			$insert_csv['Amount'] = $csv_array[4];
			$insert_csv['Details'] = $csv_array[5];
			$insert_csv['Source Info'] = $csv_array[6];

			$query = "INSERT INTO airtel_payments_transactions(date, reference, source, destination, amount, details, source_info)
			VALUES('".$insert_csv['Date']."','".$insert_csv['Reference']."','".$insert_csv['Source']."', '".$insert_csv['Destination']."', '".$insert_csv['Amount']."', '".$insert_csv['Details']."', '".$insert_csv['Source Info']."')";
			echo $query."<br />";
			//$result = mysql_query($query);
			
			$loan_code = substr($csv_array[2], 0, 10);
			//echo $csv_array[2]."<br />";
			$status = $csv_array[3];
			//echo $loan_code."<br />";
			
			$sql = mysql_query("select customer_id, customer_station, loan_status from loan_application where loan_code = '$loan_code'");
			while ($row = mysql_fetch_array($sql))
			{					
				$customer_id = $row['customer_id'];
				$customer_station = $row['customer_station'];
				$loan_status = $row['loan_status'];
			}
			//echo $customer_id."<br />";
			$mobile = substr($insert_csv['Other Party Info'], 1, 10);
			$mobile = "254".$mobile;
			
			$i++;
			$customer_id = "";
			$loan_code = "";
			
			echo "File data successfully imported to database!!";
		}
		fclose($csvfile);

		
	}
}
	include_once('includes/footer.php');
?>
