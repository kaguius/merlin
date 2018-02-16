<?php
	$userid = "";
	$adminstatus = 4;
	$property_manager_id = "";
	$passportfileupload = "";
	$resumefileupload = "";
	session_start();
	if (!empty($_SESSION)){
		$userid = $_SESSION["userid"] ;
		$adminstatus = $_SESSION["adminstatus"] ;
		$station = $_SESSION["station"] ;
		$username = $_SESSION["username"];
		$passportfileupload = $_SESSION["passportfileupload"];
		$resumefileupload = $_SESSION["resumefileupload"];
	}
	
	if (!empty($_GET)){	
		$mode = $_GET['mode'];
		$user_id = $_GET['user_id'];
		$status = $_GET['status'];
		$id_status = $_GET['status'];
	}
	include_once('includes/db_conn.php');
	$sql2 = mysql_query("select stations from users where id = '$user_id'");
	while($row = mysql_fetch_array($sql2)) {
		$stations = $row['stations'];		
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
		if (!empty($_GET)){	
			$mode = $_GET['mode'];
			$user_id = $_GET['user_id'];
			$id_status = $_GET['status'];
		}
		$transactiontime = date("Y-m-d G:i:s");
		if ($mode=='edit'){
			$sql = mysql_query("select passportfileupload, resumefileupload, mobile_no, title, first_name, last_name, national_id, preffered_language, nickname, next_visit, marital, dependants, alt_phone, dis_phone, lead_outcome, owns, home_occupy, stations, status, loan_officer, collections_officer, next_visit from users where id = '$user_id'");
			while ($row = mysql_fetch_array($sql))
			{
				$passportfileupload = $row['passportfileupload'];					
				$resumefileupload = $row['resumefileupload'];
				$mobile_no = $row['mobile_no'];
				$title = $row['title'];
				$first_name = $row['first_name'];
				$first_name = ucwords(strtolower($first_name));
				$last_name = $row['last_name'];
				$last_name = ucwords(strtolower($last_name));
				$national_id = $row['national_id'];
				$preffered_language = $row['preffered_language'];
				$nickname = $row['nickname'];
				$date_of_birth = $row['next_visit'];
				$marital = $row['marital'];
				$dependants = $row['dependants'];
				$alt_phone = $row['alt_phone'];
				$dis_phone = $row['dis_phone'];
				$lead_outcome = $row['lead_outcome'];
				$loan_officer = $row['loan_officer'];
				$collections_officer = $row['collections_officer'];
				$owns = $row['owns'];
				$owns = ucwords(strtolower($owns));
				$home_occupy = $row['home_occupy'];
				$stations = $row['stations'];
				$status = $row['status'];
				$loan_officer = $row['loan_officer'];
				$collections_officer = $row['collections_officer'];
				$next_visit_date = $row['next_visit'];

				$sql2 = mysql_query("select id, first_name, last_name from user_profiles where id = '$loan_officer'");
				while ($row = mysql_fetch_array($sql2))
				{
					$loan_officer_id = $row['id'];
					$loan_first_name = $row['first_name'];
					$loan_last_name = $row['last_name'];
					$loan_officer = $loan_first_name." ".$loan_last_name;
				}
				$sql2 = mysql_query("select id, first_name, last_name from user_profiles where id = '$collections_officer'");
				while ($row = mysql_fetch_array($sql2))
				{
					$collections_officer_id = $row['id'];
					$col_first_name = $row['first_name'];
					$col_last_name = $row['last_name'];
					$collections_officer = $col_first_name." ".$col_last_name;
				}
			}
			$page_title = "Update Lead Detail(s)";
		}
		else{
			$page_title = "Field QA Form";
		}
		
		include_once('includes/header.php');
		
		?>		
		<div id="page">
			<div id="content">
				<div class="post">
				<h2><font color="#000A8B"><?php echo $page_title; ?></font></h2>
				<br />
				<div id="tabs">
				<ul>
					<li><a href="#tabs-1">KYC</a></li>
					<li><a href="#tabs-2">Business</a></li>
				</ul>
				<div id="tabs-1">
				<form id="frmOrder" name="frmOrder" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
				<table border="0" width="100%" cellspacing="2" cellpadding="2">	
				<input type="hidden" name="users_id" id="users_id" value="<?php echo $user_id	 ?>" />		
					<tr>
						<td valign='top' width="15%">Branch: *</td>
						<td valign='top' width="35%" colspan="3">
							<select name='stations' id='stations'>
								<?php
									if($mode == 'edit'){
								?>
									<option value="<?php echo $stations_id ?>"><?php echo $stations ?></option>
								<?php
								}
								else{
								?>
									<option value=''> </option>
								<?php
								}
								//echo "<option value=''>" "</option>"; 
								$sql2 = mysql_query("select id, stations from stations where active = '0' order by stations asc");
								while($row = mysql_fetch_array($sql2)) {
									$id = $row['id'];
									$stations = $row['stations'];
									echo "<option value='$id'>".$stations."</option>"; 
								}
								?>
							</select>
							<input value="<?php echo $stations_id ?>" id="old_stations" name="old_stations" type="hidden" />
						</td>
					</tr>
					<tr >
						<td valign='top' width="15%">Loan Officer: *</td>
						<td valign='top' width="35%">
							<select name='loan_officer' id='loan_officer'>
								<?php
									if($mode == 'edit'){
								?>
									<option value="<?php echo $loan_officer_id ?>"><?php echo $loan_officer ?></option>
									<option value=''> </option>	
								<?php
									}
									else{
									?>
									<option value=''> </option>
									<?php
									}
									//echo "<option value=''>" "</option>"; 										
									if($station == '3'){ 
										$sql2 = mysql_query("select user_profiles.id, first_name, last_name, stations.stations from user_profiles inner join stations on stations.id = user_profiles.station where title = '1' and user_status = '1'");
									}
									else if($title == '3' || $title == '8'){ 
										$sql2 = mysql_query("select user_profiles.id, first_name, last_name, stations.stations from user_profiles inner join stations on stations.id = user_profiles.station where title = '1' and user_status = '1' and station = '$station'");
									}
									else{										
										$sql2 = mysql_query("select id, first_name, last_name from user_profiles where station = '$station' and id = '$userid'");
									}
									while($row = mysql_fetch_array($sql2)) {
										$loan = $row['id'];
										$first_name = $row['first_name'];
										$last_name = $row['last_name'];
										if($station == '3'){ 
											$stations = $row['stations'];
											echo "<option value='$loan'>".$stations.": ".$first_name." ".$last_name."</option>"; 
										}
										else{
											echo "<option value='$loan'>".$first_name." ".$last_name."</option>"; 
										}
									}
									?>
								</select>
								<input value="<?php echo $loan_officer_id ?>" id="old_loan_officer" name="old_loan_officer" type="hidden" />
						</td>
						<td valign='top' width="15%">Collections Officer: *</td>
						<td valign='top' width="35%">
							<select name='collections_officer' id='collections_officer'>
								<?php
									if($mode == 'edit'){
								?>
									<option value="<?php echo $collections_officer_id ?>"><?php echo $collections_officer ?></option>
									<option value=''> </option>	
								<?php
									}
									else{
									?>
									<option value=''> </option>
									<?php
									}
									//echo "<option value=''>" "</option>";
									if($station == '3'){ 
										$sql2 = mysql_query("select user_profiles.id, first_name, last_name, stations.stations from user_profiles inner join stations on stations.id = user_profiles.station where title = '2' and user_status = '1'");
									}
									else if($title == '3' || $title == '8'){ 
										$sql2 = mysql_query("select user_profiles.id, first_name, last_name, stations.stations from user_profiles inner join stations on stations.id = user_profiles.station where title = '2' and user_status = '1' and station = '$station'");
									}
									else{										
										$sql2 = mysql_query("select id, first_name, last_name from user_profiles where station = '$station' and title = '2' and user_status = '1'");
									}
									while($row = mysql_fetch_array($sql2)) {
										$loan = $row['id'];
										$first_name = $row['first_name'];
										$last_name = $row['last_name'];
										if($station == '3'){ 
											$stations = $row['stations'];
											echo "<option value='$loan'>".$stations.": ".$first_name." ".$last_name."</option>"; 
										}
										else{
											echo "<option value='$loan'>".$first_name." ".$last_name."</option>"; 
										}
									}
									?>
								</select>
								<input value="<?php echo $collections_officer_id ?>" id="old_collections_officer" name="old_collections_officer" type="hidden" />
						</td>
					</tr>
					<tr>
						<td valign="top" width="15%">National ID Copy - Front: *</td>
						<td valign="top" width="35%">
							<select name='national_id_front' id='national_id_front'>
								<option value=''></option>
								<option value='0'>Not done</option>
								<option value='1'>Poor</option>
								<option value='2'>Below Average</option>
								<option value='3'>Average</option>
								<option value='4'>Good</option>
								<option value='5'>Excellent</option>
							</select>
						</td>
						<td valign="top" width="15%">National ID Copy - Back: *</td>
						<td valign="top" width="35%">
							<select name='national_id_back' id='national_id_back'>
								<option value=''></option>
								<option value='0'>Not done</option>
								<option value='1'>Poor</option>
								<option value='2'>Below Average</option>
								<option value='3'>Average</option>
								<option value='4'>Good</option>
								<option value='5'>Excellent</option>
							</select>
						</td>
					</tr>
					<tr>
						<td valign="top" width="15%">Passport Photo: *</td>
						<td valign="top" width="35%">
							<select name='passport_photo' id='passport_photo'>
								<option value=''></option>
								<option value='0'>Not done</option>
								<option value='1'>Poor</option>
								<option value='2'>Below Average</option>
								<option value='3'>Average</option>
								<option value='4'>Good</option>
								<option value='5'>Excellent</option>
							</select>
						</td>
					</tr>
					<tr>
						<td valign="top" width="15%">Primary Phone #: *</td>
						<td valign="top" width="35%">
							<select name='primary_phone' id='primary_phone'>
								<option value=''></option>
								<option value='0'>Not done</option>
								<option value='1'>Poor</option>
								<option value='2'>Below Average</option>
								<option value='3'>Average</option>
								<option value='4'>Good</option>
								<option value='5'>Excellent</option>
							</select>
						</td>
						<td valign="top" width="15%">Title</td>
						<td valign="top" width="35%">
							<select name='title' id='title'>
								<option value=''></option>
								<option value='0'>Not done</option>
								<option value='1'>Poor</option>
								<option value='2'>Below Average</option>
								<option value='3'>Average</option>
								<option value='4'>Good</option>
								<option value='5'>Excellent</option>
							</select>
						</td>
					</tr>
					<tr>
						<td valign="top" width="15%">Preffered Language</td>
						<td valign="top" width="35%">
							<select name='pref_language' id='pref_language'>
								<option value=''></option>
								<option value='0'>Not done</option>
								<option value='1'>Poor</option>
								<option value='2'>Below Average</option>
								<option value='3'>Average</option>
								<option value='4'>Good</option>
								<option value='5'>Excellent</option>
							</select>
						</td>
					</tr>
					<tr>
						<td valign="top" width="15%">Last Name</td>
						<td valign="top" width="35%">
							<select name='last_name' id='last_name'>
								<option value=''></option>
								<option value='0'>Not done</option>
								<option value='1'>Poor</option>
								<option value='2'>Below Average</option>
								<option value='3'>Average</option>
								<option value='4'>Good</option>
								<option value='5'>Excellent</option>
							</select>
						</td>
						<td valign="top" width="15%">First Name</td>
						<td valign="top" width="35%">
							<select name='last_name' id='last_name'>
								<option value=''></option>
								<option value='0'>Not done</option>
								<option value='1'>Poor</option>
								<option value='2'>Below Average</option>
								<option value='3'>Average</option>
								<option value='4'>Good</option>
								<option value='5'>Excellent</option>
							</select>
						</td>
					</tr>
					<tr>
						<td valign="top" width="15%">National ID</td>
						<td valign="top" width="35%">
							<select name='national_id' id='national_id'>
								<option value=''></option>
								<option value='0'>Not done</option>
								<option value='1'>Poor</option>
								<option value='2'>Below Average</option>
								<option value='3'>Average</option>
								<option value='4'>Good</option>
								<option value='5'>Excellent</option>
							</select>
						</td>
						<td valign="top" width="15%">Disbursemnet Number</td>
						<td valign="top" width="35%">
							<select name='dis_phone' id='dis_phone'>
								<option value=''></option>
								<option value='0'>Not done</option>
								<option value='1'>Poor</option>
								<option value='2'>Below Average</option>
								<option value='3'>Average</option>
								<option value='4'>Good</option>
								<option value='5'>Excellent</option>
							</select>
						</td>
					</tr>
					<tr>
						<td valign="top" width="15%">Alternate Phone</td>
						<td valign="top" width="35%">
							<select name='alt_phone' id='alt_phone'>
								<option value=''></option>
								<option value='0'>Not done</option>
								<option value='1'>Poor</option>
								<option value='2'>Below Average</option>
								<option value='3'>Average</option>
								<option value='4'>Good</option>
								<option value='5'>Excellent</option>
							</select>
						</td>
						<td valign="top" width="15%">Also Knowns as</td>
						<td valign="top" width="35%">
							<select name='also_known_as' id='also_known_as'>
								<option value=''></option>
								<option value='0'>Not done</option>
								<option value='1'>Poor</option>
								<option value='2'>Below Average</option>
								<option value='3'>Average</option>
								<option value='4'>Good</option>
								<option value='5'>Excellent</option>
							</select>
						</td>
					</tr>
					<tr>
						<td valign="top" width="15%">Marital Status</td>
						<td valign="top" width="35%">
							<select name='marital' id='marital'>
								<option value=''></option>
								<option value='0'>Not done</option>
								<option value='1'>Poor</option>
								<option value='2'>Below Average</option>
								<option value='3'>Average</option>
								<option value='4'>Good</option>
								<option value='5'>Excellent</option>
							</select>
						</td>
						<td valign="top" width="15%">Home Ownership</td>
						<td valign="top" width="35%">
							<select name='home_ownership' id='home_ownership'>
								<option value=''></option>
								<option value='0'>Not done</option>
								<option value='1'>Poor</option>
								<option value='2'>Below Average</option>
								<option value='3'>Average</option>
								<option value='4'>Good</option>
								<option value='5'>Excellent</option>
							</select>
						</td>
					</tr>
					<tr>
						<td valign="top" width="15%">Lived there since</td>
						<td valign="top" width="35%">
							<select name='lived_there' id='lived_there'>
								<option value=''></option>
								<option value='0'>Not done</option>
								<option value='1'>Poor</option>
								<option value='2'>Below Average</option>
								<option value='3'>Average</option>
								<option value='4'>Good</option>
								<option value='5'>Excellent</option>
							</select>
						</td>
						<td valign="top" width="15%">Home Address</td>
						<td valign="top" width="35%">
							<select name='home_address' id='home_address'>
								<option value=''></option>
								<option value='0'>Not done</option>
								<option value='1'>Poor</option>
								<option value='2'>Below Average</option>
								<option value='3'>Average</option>
								<option value='4'>Good</option>
								<option value='5'>Excellent</option>
							</select>
						</td>
					</tr>
					<tr>
						<td valign="top" width="15%">No of Dependants</td>
						<td valign="top" width="35%">
							<select name='lived_there' id='lived_there'>
								<option value=''></option>
								<option value='0'>Not done</option>
								<option value='1'>Poor</option>
								<option value='2'>Below Average</option>
								<option value='3'>Average</option>
								<option value='4'>Good</option>
								<option value='5'>Excellent</option>
							</select>
						</td>
					</tr>
					<tr>
						<td valign="top" width="15%">Ref 1: First Name * </td>
						<td valign="top" width="35%">
							<select name='ref_1_first_name' id='ref_1_first_name'>
								<option value=''></option>
								<option value='0'>Not done</option>
								<option value='1'>Poor</option>
								<option value='2'>Below Average</option>
								<option value='3'>Average</option>
								<option value='4'>Good</option>
								<option value='5'>Excellent</option>
							</select>
						</td>
						<td valign="top" width="15%">Ref 1: Also Know As </td>
						<td valign="top" width="35%">
							<select name='ref_1_also_known_as' id='ref_1_also_known_as'>
								<option value=''></option>
								<option value='0'>Not done</option>
								<option value='1'>Poor</option>
								<option value='2'>Below Average</option>
								<option value='3'>Average</option>
								<option value='4'>Good</option>
								<option value='5'>Excellent</option>
							</select>
						</td>
					</tr>
					<tr>
						<td valign="top" width="15%">Ref 1: Last Name *</td>
						<td valign="top" width="35%">
							<select name='ref_1_last_name' id='ref_1_last_name'>
								<option value=''></option>
								<option value='0'>Not done</option>
								<option value='1'>Poor</option>
								<option value='2'>Below Average</option>
								<option value='3'>Average</option>
								<option value='4'>Good</option>
								<option value='5'>Excellent</option>
							</select>
						</td>
						<td valign="top" width="15%">Ref 1: Relationship </td>
						<td valign="top" width="35%">
							<select name='ref_1_relationship' id='ref_1_relationship'>
								<option value=''></option>
								<option value='0'>Not done</option>
								<option value='1'>Poor</option>
								<option value='2'>Below Average</option>
								<option value='3'>Average</option>
								<option value='4'>Good</option>
								<option value='5'>Excellent</option>
							</select>
						</td>
					</tr>
					<tr>
						<td valign="top" width="15%">Ref 1: Phone # *</td>
						<td valign="top" width="35%">
							<select name='ref_1_phone' id='ref_1_phone'>
								<option value=''></option>
								<option value='0'>Not done</option>
								<option value='1'>Poor</option>
								<option value='2'>Below Average</option>
								<option value='3'>Average</option>
								<option value='4'>Good</option>
								<option value='5'>Excellent</option>
							</select>
						</td>
					</tr>
					<tr>
						<td valign="top" width="15%">Ref 2: First Name * </td>
						<td valign="top" width="35%">
							<select name='ref_2_first_name' id='ref_2_first_name'>
								<option value=''></option>
								<option value='0'>Not done</option>
								<option value='1'>Poor</option>
								<option value='2'>Below Average</option>
								<option value='3'>Average</option>
								<option value='4'>Good</option>
								<option value='5'>Excellent</option>
							</select>
						</td>
						<td valign="top" width="15%">Ref 2: Also Know As </td>
						<td valign="top" width="35%">
							<select name='ref_2_also_known_as' id='ref_2_also_known_as'>
								<option value=''></option>
								<option value='0'>Not done</option>
								<option value='1'>Poor</option>
								<option value='2'>Below Average</option>
								<option value='3'>Average</option>
								<option value='4'>Good</option>
								<option value='5'>Excellent</option>
							</select>
						</td>
					</tr>
					<tr>
						<td valign="top" width="15%">Ref 2: Last Name *</td>
						<td valign="top" width="35%">
							<select name='ref_2_last_name' id='ref_2_last_name'>
								<option value=''></option>
								<option value='0'>Not done</option>
								<option value='1'>Poor</option>
								<option value='2'>Below Average</option>
								<option value='3'>Average</option>
								<option value='4'>Good</option>
								<option value='5'>Excellent</option>
							</select>
						</td>
						<td valign="top" width="15%">Ref 2: Relationship </td>
						<td valign="top" width="35%">
							<select name='ref_2_relationship' id='ref_2_relationship'>
								<option value=''></option>
								<option value='0'>Not done</option>
								<option value='1'>Poor</option>
								<option value='2'>Below Average</option>
								<option value='3'>Average</option>
								<option value='4'>Good</option>
								<option value='5'>Excellent</option>
							</select>
						</td>
					</tr>
					<tr>
						<td valign="top" width="15%">Ref 2: Phone # *</td>
						<td valign="top" width="35%">
							<select name='ref_2_phone' id='ref_2_phone'>
								<option value=''></option>
								<option value='0'>Not done</option>
								<option value='1'>Poor</option>
								<option value='2'>Below Average</option>
								<option value='3'>Average</option>
								<option value='4'>Good</option>
								<option value='5'>Excellent</option>
							</select>
						</td>
					</tr>
					<tr>
						<td valign="top" width="15%">Asset List</td>
						<td valign="top" width="35%">
							<select name='asset_list' id='asset_list'>
								<option value=''></option>
								<option value='0'>Not done</option>
								<option value='1'>Poor</option>
								<option value='2'>Below Average</option>
								<option value='3'>Average</option>
								<option value='4'>Good</option>
								<option value='5'>Excellent</option>
							</select>
						</td>
					</tr>
				</table>
				</div>
				<div id="tabs-1">
				<table border="0" width="100%" cellspacing="2" cellpadding="2">	
					<tr>
						<td valign="top" width="15%">Business Category</td>
						<td valign="top" width="35%">
							<select name='business_category' id='business_category'>
								<option value=''></option>
								<option value='0'>Not done</option>
								<option value='1'>Poor</option>
								<option value='2'>Below Average</option>
								<option value='3'>Average</option>
								<option value='4'>Good</option>
								<option value='5'>Excellent</option>
							</select>
						</td>
						<td valign="top" width="15%">Business Type</td>
						<td valign="top" width="35%">
							<select name='business_type' id='business_type'>
								<option value=''></option>
								<option value='0'>Not done</option>
								<option value='1'>Poor</option>
								<option value='2'>Below Average</option>
								<option value='3'>Average</option>
								<option value='4'>Good</option>
								<option value='5'>Excellent</option>
							</select>
						</td>
					</tr>
					<tr>
						<td valign="top" width="15%">Date Started trading the product</td>
						<td valign="top" width="35%">
							<select name='date_trading_product' id='date_trading_product'>
								<option value=''></option>
								<option value='0'>Not done</option>
								<option value='1'>Poor</option>
								<option value='2'>Below Average</option>
								<option value='3'>Average</option>
								<option value='4'>Good</option>
								<option value='5'>Excellent</option>
							</select>
						</td>
						<td valign="top" width="15%">Date started trading this location? </td>
						<td valign="top" width="35%">
							<select name='date_trading_location' id='date_trading_location'>
								<option value=''></option>
								<option value='0'>Not done</option>
								<option value='1'>Poor</option>
								<option value='2'>Below Average</option>
								<option value='3'>Average</option>
								<option value='4'>Good</option>
								<option value='5'>Excellent</option>
							</select>
						</td>
					</tr>
					<tr>
						<td valign="top" width="15%">Business Address</td>
						<td valign="top" width="35%">
							<select name='business_address' id='business_address'>
								<option value=''></option>
								<option value='0'>Not done</option>
								<option value='1'>Poor</option>
								<option value='2'>Below Average</option>
								<option value='3'>Average</option>
								<option value='4'>Good</option>
								<option value='5'>Excellent</option>
							</select>
						</td>
					</tr>
					<tr>
						<td valign="top" width="15%">Curresnt Stock Valus</td>
						<td valign="top" width="35%">
							<select name='current_stock_value' id='current_stock_value'>
								<option value=''></option>
								<option value='0'>Not done</option>
								<option value='1'>Poor</option>
								<option value='2'>Below Average</option>
								<option value='3'>Average</option>
								<option value='4'>Good</option>
								<option value='5'>Excellent</option>
							</select>
						</td>
						<td valign="top" width="15%">Weekly Sales </td>
						<td valign="top" width="35%">
							<select name='weekly_sales' id='weekly_sales'>
								<option value=''></option>
								<option value='0'>Not done</option>
								<option value='1'>Poor</option>
								<option value='2'>Below Average</option>
								<option value='3'>Average</option>
								<option value='4'>Good</option>
								<option value='5'>Excellent</option>
							</select>
						</td>
					</tr>
					<tr>
						<td valign="top" width="15%">Weekly Spend on Stock</td>
						<td valign="top" width="35%">
							<select name='spend_stock' id='spend_stock'>
								<option value=''></option>
								<option value='0'>Not done</option>
								<option value='1'>Poor</option>
								<option value='2'>Below Average</option>
								<option value='3'>Average</option>
								<option value='4'>Good</option>
								<option value='5'>Excellent</option>
							</select>
						</td>
					</tr>
					<tr>
						<td valign="top" width="15%">Business Rent</td>
						<td valign="top" width="35%">
							<select name='business_rent' id='business_rent'>
								<option value=''></option>
								<option value='0'>Not done</option>
								<option value='1'>Poor</option>
								<option value='2'>Below Average</option>
								<option value='3'>Average</option>
								<option value='4'>Good</option>
								<option value='5'>Excellent</option>
							</select>
						</td>
						<td valign="top" width="15%">Business Utilities</td>
						<td valign="top" width="35%">
							<select name='business_utilities' id='business_utilities'>
								<option value=''></option>
								<option value='0'>Not done</option>
								<option value='1'>Poor</option>
								<option value='2'>Below Average</option>
								<option value='3'>Average</option>
								<option value='4'>Good</option>
								<option value='5'>Excellent</option>
							</select>
						</td>
					</tr>
					<tr>
						<td valign="top" width="15%">Employees</td>
						<td valign="top" width="35%">
							<select name='employees' id='employees'>
								<option value=''></option>
								<option value='0'>Not done</option>
								<option value='1'>Poor</option>
								<option value='2'>Below Average</option>
								<option value='3'>Average</option>
								<option value='4'>Good</option>
								<option value='5'>Excellent</option>
							</select>
						</td>
						<td valign="top" width="15%">Licensing</td>
						<td valign="top" width="35%">
							<select name='licensing' id='licensing'>
								<option value=''></option>
								<option value='0'>Not done</option>
								<option value='1'>Poor</option>
								<option value='2'>Below Average</option>
								<option value='3'>Average</option>
								<option value='4'>Good</option>
								<option value='5'>Excellent</option>
							</select>
						</td>
					</tr>
					<tr>
						<td valign="top" width="15%">Storage</td>
						<td valign="top" width="35%">
							<select name='storage' id='storage'>
								<option value=''></option>
								<option value='0'>Not done</option>
								<option value='1'>Poor</option>
								<option value='2'>Below Average</option>
								<option value='3'>Average</option>
								<option value='4'>Good</option>
								<option value='5'>Excellent</option>
							</select>
						</td>
						<td valign="top" width="15%">Transport</td>
						<td valign="top" width="35%">
							<select name='transport' id='transport'>
								<option value=''></option>
								<option value='0'>Not done</option>
								<option value='1'>Poor</option>
								<option value='2'>Below Average</option>
								<option value='3'>Average</option>
								<option value='4'>Good</option>
								<option value='5'>Excellent</option>
							</select>
						</td>
					</tr>

					<tr>
						<td valign="top" width="15%">Rent</td>
						<td valign="top" width="35%">
							<select name='rent' id='rent'>
								<option value=''></option>
								<option value='0'>Not done</option>
								<option value='1'>Poor</option>
								<option value='2'>Below Average</option>
								<option value='3'>Average</option>
								<option value='4'>Good</option>
								<option value='5'>Excellent</option>
							</select>
						</td>
						<td valign="top" width="15%">House Utilities</td>
						<td valign="top" width="35%">
							<select name='house_utilities' id='house_utilities'>
								<option value=''></option>
								<option value='0'>Not done</option>
								<option value='1'>Poor</option>
								<option value='2'>Below Average</option>
								<option value='3'>Average</option>
								<option value='4'>Good</option>
								<option value='5'>Excellent</option>
							</select>
						</td>
					</tr>
					<tr>
						<td valign="top" width="15%">Food</td>
						<td valign="top" width="35%">
							<select name='food' id='food'>
								<option value=''></option>
								<option value='0'>Not done</option>
								<option value='1'>Poor</option>
								<option value='2'>Below Average</option>
								<option value='3'>Average</option>
								<option value='4'>Good</option>
								<option value='5'>Excellent</option>
							</select>
						</td>
						<td valign="top" width="15%">School Fees</td>
						<td valign="top" width="35%">
							<select name='school_fees' id='school_fees'>
								<option value=''></option>
								<option value='0'>Not done</option>
								<option value='1'>Poor</option>
								<option value='2'>Below Average</option>
								<option value='3'>Average</option>
								<option value='4'>Good</option>
								<option value='5'>Excellent</option>
							</select>
						</td>
					</tr>
					<tr>
						<td valign="top" width="15%">Weekly Chama Contribution </td>
						<td valign="top" width="35%">
							<select name='food' id='food'>
								<option value=''></option>
								<option value='0'>Not done</option>
								<option value='1'>Poor</option>
								<option value='2'>Below Average</option>
								<option value='3'>Average</option>
								<option value='4'>Good</option>
								<option value='5'>Excellent</option>
							</select>
						</td>
						<td valign="top" width="15%">Number of Members in the Chama </td>
						<td valign="top" width="35%">
							<select name='school_fees' id='school_fees'>
								<option value=''></option>
								<option value='0'>Not done</option>
								<option value='1'>Poor</option>
								<option value='2'>Below Average</option>
								<option value='3'>Average</option>
								<option value='4'>Good</option>
								<option value='5'>Excellent</option>
							</select>
						</td>
					</tr>
					<tr>
						<td valign="top" width="15%">Chama Payout </td>
						<td valign="top" width="35%">
							<select name='food' id='food'>
								<option value=''></option>
								<option value='0'>Not done</option>
								<option value='1'>Poor</option>
								<option value='2'>Below Average</option>
								<option value='3'>Average</option>
								<option value='4'>Good</option>
								<option value='5'>Excellent</option>
							</select>
						</td>
						<td valign="top" width="15%">Chama Payout Frequency </td>
						<td valign="top" width="35%">
							<select name='school_fees' id='school_fees'>
								<option value=''></option>
								<option value='0'>Not done</option>
								<option value='1'>Poor</option>
								<option value='2'>Below Average</option>
								<option value='3'>Average</option>
								<option value='4'>Good</option>
								<option value='5'>Excellent</option>
							</select>
						</td>
					</tr>
					<tr>
						<td valign="top" width="15%">Stock Neat </td>
						<td valign="top" width="35%">
							<select name='food' id='food'>
								<option value=''></option>
								<option value='0'>Not done</option>
								<option value='1'>Poor</option>
								<option value='2'>Below Average</option>
								<option value='3'>Average</option>
								<option value='4'>Good</option>
								<option value='5'>Excellent</option>
							</select>
						</td>
						<td valign="top" width="15%">Accurate Ledger Book </td>
						<td valign="top" width="35%">
							<select name='school_fees' id='school_fees'>
								<option value=''></option>
								<option value='0'>Not done</option>
								<option value='1'>Poor</option>
								<option value='2'>Below Average</option>
								<option value='3'>Average</option>
								<option value='4'>Good</option>
								<option value='5'>Excellent</option>
							</select>
						</td>
					</tr>
					<tr>
						<td valign="top" width="15%">Evidence of Sales Activity </td>
						<td valign="top" width="35%">
							<select name='food' id='food'>
								<option value=''></option>
								<option value='0'>Not done</option>
								<option value='1'>Poor</option>
								<option value='2'>Below Average</option>
								<option value='3'>Average</option>
								<option value='4'>Good</option>
								<option value='5'>Excellent</option>
							</select>
						</td>
						<td valign="top" width="15%">Evidence of Permanent Operation </td>
						<td valign="top" width="35%">
							<select name='school_fees' id='school_fees'>
								<option value=''></option>
								<option value='0'>Not done</option>
								<option value='1'>Poor</option>
								<option value='2'>Below Average</option>
								<option value='3'>Average</option>
								<option value='4'>Good</option>
								<option value='5'>Excellent</option>
							</select>
						</td>
					</tr>
					<tr>
						<td valign="top" width="15%">Proof of Ownership </td>
						<td valign="top" width="35%">
							<select name='food' id='food'>
								<option value=''></option>
								<option value='0'>Not done</option>
								<option value='1'>Poor</option>
								<option value='2'>Below Average</option>
								<option value='3'>Average</option>
								<option value='4'>Good</option>
								<option value='5'>Excellent</option>
							</select>
						</td>
						<td valign="top" width="15%">Forthcoming & Transparent </td>
						<td valign="top" width="35%">
							<select name='school_fees' id='school_fees'>
								<option value=''></option>
								<option value='0'>Not done</option>
								<option value='1'>Poor</option>
								<option value='2'>Below Average</option>
								<option value='3'>Average</option>
								<option value='4'>Good</option>
								<option value='5'>Excellent</option>
							</select>
						</td>
					</tr>
					<tr>
						<td valign="top" width="15%">Known to Market Authorities </td>
						<td valign="top" width="35%">
							<select name='food' id='food'>
								<option value=''></option>
								<option value='0'>Not done</option>
								<option value='1'>Poor</option>
								<option value='2'>Below Average</option>
								<option value='3'>Average</option>
								<option value='4'>Good</option>
								<option value='5'>Excellent</option>
							</select>
						</td>
						<td valign="top" width="15%">Sound Reputation </td>
						<td valign="top" width="35%">
							<select name='school_fees' id='school_fees'>
								<option value=''></option>
								<option value='0'>Not done</option>
								<option value='1'>Poor</option>
								<option value='2'>Below Average</option>
								<option value='3'>Average</option>
								<option value='4'>Good</option>
								<option value='5'>Excellent</option>
							</select>
						</td>
					</tr>
					<tr>
						<td valign="top" width="15%">Would I lend? </td>
						<td valign="top" width="35%">
							<select name='food' id='food'>
								<option value=''></option>
								<option value='0'>Not done</option>
								<option value='1'>Poor</option>
								<option value='2'>Below Average</option>
								<option value='3'>Average</option>
								<option value='4'>Good</option>
								<option value='5'>Excellent</option>
							</select>
						</td>
						<td valign="top" width="15%">If yes, how much?</td>
						<td valign="top" width="35%">
							<select name='school_fees' id='school_fees'>
								<option value=''></option>
								<option value='0'>Not done</option>
								<option value='1'>Poor</option>
								<option value='2'>Below Average</option>
								<option value='3'>Average</option>
								<option value='4'>Good</option>
								<option value='5'>Excellent</option>
							</select>
						</td>
					</tr>
					<tr>
						<td valign="top" width="15%">How many visits, calls have you received?</td>
						<td valign="top" width="35%">
							<select name='food' id='food'>
								<option value=''></option>
								<option value='0'>Not done</option>
								<option value='1'>Poor</option>
								<option value='2'>Below Average</option>
								<option value='3'>Average</option>
								<option value='4'>Good</option>
								<option value='5'>Excellent</option>
							</select>
						</td>
					</tr>
				</table>
				<table border="0" width="100%">
					<tr>
						<td valign="top">
							<button name="btnNewCard" id="button">Save</button>
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
		$mobile_no = $_POST['mobile_no'];
		$old_mobile_no = $_POST['old_mobile_no'];
		$first_name = $_POST['first_name'];
		$last_name = $_POST['last_name'];
		$date_of_birth = $_POST['next_visit_date'];
		$date_of_birth = date('Y-m-d', strtotime(str_replace('-', '/', $date_of_birth)));
		$lead_outcome = $_POST['lead_outcome'];
		$customer_station = $_POST['customer_station'];
		$loan_officer = $_POST['loan_officer'];
		$collections_officer = $_POST['collections_officer'];
		
		$length_mobile_no = strlen($mobile_no);

		$page_status = $_POST['page_status'];
		$users_id = $_POST['users_id'];
		
		$sql = mysql_query("select distinct mobile_no from users where mobile_no = '$mobile_no'");
		while ($row = mysql_fetch_array($sql))
		{
			$exists_mobile_no = $row['mobile_no'];	
		}
		
		if($length_mobile_no == '12'){
			if($page_status == 'edit' && $old_mobile_no == $mobile_no){
				$sql3="update users set mobile_no = '$mobile_no', first_name = '$first_name', last_name = '$last_name', next_visit = '$date_of_birth',  lead_outcome = '$lead_outcome', transactiontime = '$transactiontime', UID = '$userid', loan_officer = '$loan_officer', collections_officer = '$collections_officer' WHERE id  = '$users_id'";
				//echo $sql3."<br />";
				$result = mysql_query($sql3);
				$query = "leads.php";
			}
			else if($exists_mobile_no != $mobile_no){
				$sql = mysql_query("select id from user_id");
				while ($row = mysql_fetch_array($sql))
				{
					$user_id_latest = $row['id'];	
				}
					
				$sql3="
				INSERT INTO users (id, mobile_no, first_name, last_name, next_visit, lead_outcome, stations, loan_officer, collections_officer, transactiontime, UID)
				VALUES('$user_id_latest', '$mobile_no', '$first_name', '$last_name', '$date_of_birth', '$lead_outcome', '$customer_station', '$loan_officer', '$collections_officer', '$transactiontime', '$userid')";
			
				$user_id_latest = $user_id_latest + 1;
				$sql15="update user_id set id='$user_id_latest'";
				$result = mysql_query($sql15);
				
				//echo $sql3."<br />";
				$result = mysql_query($sql3);
				$query = "leads.php";
			}
			else{
				$phone_number_length = MD5(phone_number_length);
				$query="lead_details.php?status=phone_number_length&phone_number_length=$phone_number_length";
			}
		}
		
	
		?>
		<script type="text/javascript">
			<!--
				document.location = "<?php echo $query ?>";
			//-->
		</script>
		<?php
	}
}
	include_once('includes/footer.php');
?>
