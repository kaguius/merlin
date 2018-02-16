 <?php
$target_path = "uploads/";
$target_path = $target_path . basename( $_FILES['uploadedfile']['name']); 
if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)) {
	$fileupload = basename( $_FILES['uploadedfile']['name']);
	session_start();
	$_SESSION["fileupload"]=$target_path;
	 
	//approach 1
	//$redir="Location: https://".$_SERVER['https_HOST'].dirname($_SERVER['PHP_SELF'])."/apply.php";
	 
	//Approach 2: specify the path explicitly. If you move servers it may need changing compared with apprach 1
	//$redir="Location: https://domain/directory/filename.php";
	?>
	<script type="text/javascript">
			<!--
				document.location = "upload_loans.php";
			//-->
		</script>
	<?php
	exit;
} 
else{
     	echo "There was an error uploading the file, please try again!";
}
?>
