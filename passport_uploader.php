 <?php
 	include_once('includes/db_conn.php');
	$transactiontime = date("Y-m-d G:i:s");

	session_start();
	if (!empty($_SESSION)) {
    		$userid = $_SESSION["userid"];
	}
	if (!empty($_GET)){	
		$user_id = $_GET['user_id'];
	}
	
	$target_dir = "uploads/";
	$target_file = $target_dir . basename($_FILES["passportfileupload"]["name"]);
	$uploadOk = 1;
	$imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
	
	// Check if image file is a actual image or fake image
	if(isset($_POST["submit"])) {
		$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
		if($check !== false) {
			echo "File is an image - " . $check["mime"] . ".";
			$uploadOk = 1;
		} else {
			echo "File is not an image.";
			$uploadOk = 0;
		}
	}

	// Check file size
	if ($_FILES["passportfileupload"]["size"] > 50000000) {
		echo "Sorry, your file is too large.";
		$uploadOk = 0;
	}
	
	// Allow certain file formats
	if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
	&& $imageFileType != "gif" ) {
		echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
		$uploadOk = 0;
	}
	
	// Check if $uploadOk is set to 0 by an error
	if ($uploadOk == 0) {
		echo "Sorry, your file was not uploaded.";
	// if everything is ok, try to upload file
	} else {
		if (move_uploaded_file($_FILES["passportfileupload"]["tmp_name"], $target_file)) {
			$target_path = $target_path . basename( $_FILES['passportfileupload']['name']); 
			$fileupload = basename( $_FILES['passportfileupload']['name']);
			session_start();
			$target_path = "uploads/".$target_path;
			$_SESSION["passportfileupload"] = $target_path;
			//$_SESSION["username"] = $username;
			
			//write the file upload path in the db at this point
			
			
			
			//echo $sql3;
	 
			if($user_id != ""){
				$sql3 = "update users set passportfileupload = '$target_path' WHERE id  = '$user_id'";
				$result = mysql_query($sql3);
				$query = "customer_details.php?user_id=$user_id&mode=edit";
				$sql4 = "insert into change_log(UID, table_name, customer_id, variable, old_value, new_value, transactiontime)values('$userid', 'users', '$user_id', 'passport_front', 'nill', '$target_path', '$transactiontime')";
				//echo $sql4."<br />";
				$result = mysql_query($sql4);
			}
			else{
				$query = "customer_details.php";
			}
			//echo $target_path;
			//echo $query;
			
			?>
			<script type="text/javascript">
					<!--
						document.location = "<?php echo $query ?>";
					//-->
				</script>
			<?php
			exit;
		} else {
			echo "Sorry, there was an error uploading your file.";
		}
	}
?>
