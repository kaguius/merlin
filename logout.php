<?php
	session_start();
	if (!empty($_SESSION)){
		$userid = $_SESSION["userid"] ;
		$admin_status = $_SESSION["adminstatus"];		
		$station = $_SESSION["station"] ;
		$username = $_SESSION["username"];
	}
	// or this would remove all the variables in the session, but not the session itself 
	session_unset(); 
	// this would destroy the session variables 
	session_destroy();
?>
<script type="text/javascript">
<!--
	document.location = "insufficient_permission.php";
//-->
</script>
