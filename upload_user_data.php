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
		$page_title = "Upload User Data(s)";
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
							<td valign="top"><a href="uploadform2.php">Click here to Upload the copy of the MPESA Paybill C2B Statement</a><br />
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
			$insert_csv['id'] = $csv_array[0];
			$insert_csv['branch'] = $csv_array[1];
			$insert_csv['national_id'] = $csv_array[2];
			$insert_csv['title'] = $csv_array[3];
			$insert_csv['first_name'] = $csv_array[4];
			$insert_csv['last_name'] = $csv_array[5];
			$insert_csv['mobile_no'] = $csv_array[6];
			$insert_csv['dis_phone'] = $csv_array[7];
			$insert_csv['alt_phone'] = $csv_array[8];
			$insert_csv['ref_first_name'] = $csv_array[9];
			$insert_csv['ref_last_name'] = $csv_array[10];
			$insert_csv['ref_known_as'] = $csv_array[11];
			$insert_csv['ref_relationship'] = $csv_array[12];
			$insert_csv['ref_phone_number'] = $csv_array[13];
			
			$query = "INSERT INTO users_copy (id, stations, national_id, title, first_name, last_name, mobile_no, dis_phone, alt_phone, ref_first_name, ref_last_name, ref_known_as, ref_relationship, ref_phone_number)
			VALUES('".$insert_csv['id']."','".$insert_csv['branch']."','".$insert_csv['national_id']."', '".$insert_csv['title']."', '".$insert_csv['first_name']."', '".$insert_csv['last_name']."', '".$insert_csv['mobile_no']."', '".$insert_csv['dis_phone']."', '".$insert_csv['alt_phone']."', '".$insert_csv['ref_first_name']."', '".$insert_csv['ref_last_name']."', '".$insert_csv['ref_known_as']."', '".$insert_csv['ref_relationship']."', '".$insert_csv['ref_phone_number']."')";
			echo $query."<br />";
			//$result = mysql_query($query);
			
			$i++;
		}
		fclose($csvfile);

		echo "File data successfully imported to database!!";
	}
}
	include_once('includes/footer.php');
?>
