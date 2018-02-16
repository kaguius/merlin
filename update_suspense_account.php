<?php
	$userid = "";
	$adminstatus = 4;
	$property_manager_id = "";
	session_start();
	if (!empty($_SESSION)){
		$userid = $_SESSION["userid"] ;
		$adminstatus = $_SESSION["adminstatus"] ;
		$station = $_SESSION["station"] ;
		$username = $_SESSION["username"];
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
		include_once('includes/header.php');
		
		$sql1="DROP TABLE suspence_accounts;";
		//echo $sql1.'<br />';
		//$result = mysql_query($sql1);
		$sql2="CREATE TABLE `suspence_accounts` (                  
                             `id` int(10) NOT NULL AUTO_INCREMENT,                     
                             `receipt` varchar(10) DEFAULT NULL,                       
                             `date` datetime DEFAULT NULL,                             
                             `details` longtext,                                       
                             `status` varchar(10) DEFAULT NULL,                        
                             `withdrawn` float DEFAULT NULL,                           
                             `paid_in` float DEFAULT NULL,                             
                             `balance` float DEFAULT NULL,                             
                             `balance_confirmed` varchar(10) DEFAULT NULL,             
                             `trans_type` varchar(250) DEFAULT NULL,                   
                             `other_party_info` longtext,                              
                             `trans_party_details` varchar(50) DEFAULT NULL, 
			                 `resolved` int(5) DEFAULT '0', 
                             PRIMARY KEY (`id`));";
        //echo $sql2.'<br />';     
        //$result = mysql_query($sql2);
    	//Check if the payment is in the payments table
    	$sql = mysql_query("select receipt from mpesa_519606_transactions where status = 'Completed';");
		while ($row = mysql_fetch_array($sql))
		{
			$receipt = $row['receipt'];
			$sql2 = mysql_query("select loan_rep_mpesa_code from loan_repayments where loan_rep_mpesa_code = '$receipt'");
			while ($row = mysql_fetch_array($sql2))
			{
				$loan_rep_mpesa_code = $row['loan_rep_mpesa_code'];
			}
			if($loan_rep_mpesa_code != $receipt){
				$sql3="INSERT INTO suspence_accounts (receipt, date, details, status, withdrawn, paid_in, balance, balance_confirmed, trans_type, other_party_info, trans_party_details) 
		select receipt, date, details, status, withdrawn, paid_in, balance, balance_confirmed, trans_type, other_party_info, trans_party_details from mpesa_519606_transactions where status = 'Completed' and paid_in != '0' and receipt = '$receipt';";
				//echo $sql3.'<br />';
				$result = mysql_query($sql3);
			}
		}
		
		
		
		$query = "suspence_account.php";
		?>
		<script type="text/javascript">
			<!--
			/*alert("Either the Email Address or the Password do not match the records in the database or you have been disabled from the system, please contact the system admin at www.e-kodi.com/contact.php");*/
			document.location = "<?php echo $query ?>";
			//-->
		</script>
		<?php	
}
	include_once('includes/footer.php');
?>
