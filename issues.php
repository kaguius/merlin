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
		$title = $_SESSION["title"];
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
		if (!empty($_GET)){	
			$user_id = $_GET['user_id'];
			$action = $_GET['action'];
			$user_req_id = $_GET['user_req_id'];
		}
		include_once('includes/db_conn.php');
		$transactiontime = date("Y-m-d G:i:s");
		$page_title = "Customer Data Issue Log - Missing Records";
		include_once('includes/header.php');
		
		$sql = mysql_query("select first_name, last_name, mobile_no, dis_phone, affordability, loan_officer, collections_officer, stations from users where id = '$user_id'");
		while ($row = mysql_fetch_array($sql))
		{
			$first_name = $row['first_name'];
			$last_name = $row['last_name'];
			$first_name = ucwords(strtolower($first_name));	
			$last_name = ucwords(strtolower($last_name));
			$customer_name = $first_name.' '.$last_name;		
			$mobile_no = $row['mobile_no'];
			$dis_phone = $row['dis_phone'];
			$affordability = $row['affordability'];
			$loan_officer_id = $row['loan_officer'];
			$collections_officer_id = $row['collections_officer'];
			$customer_station = $row['stations'];
			$sql2 = mysql_query("select stations from stations where id = '$customer_station'");
			while ($row = mysql_fetch_array($sql2))
			{
				$stations_name = $row['stations'];
			}
		}
		
		$sql = mysql_query("select id, passportfileupload, resumefileupload, resumefileupload_back, mobile_no, title, first_name, last_name, national_id, preffered_language, nickname, date_of_birth, marital, dependants, alt_phone, dis_phone, home_address, owns, home_occupy, stations, affordability, status, loan_officer, collections_officer, collections_agent, next_visit, ref_first_name, ref_last_name, ref_known_as, ref_phone_number, ref_relationship, asset_list, gender, ref_landlord_title, ref_landlord_first_name, ref_landlord_last_name, ref_landlord_known_as, ref_landlord_relationship, ref_landlord_phone, lat, lng from users where id = '$user_id'");
		 while ($row = mysql_fetch_array($sql))
		 {
			$id = $row['id'];	
			$passportfileupload = $row['passportfileupload'];					
			$resumefileupload = $row['resumefileupload'];
			$resumefileupload_back = $row['resumefileupload_back'];
			$mobile_no = $row['mobile_no'];
			$title_name = $row['title'];
			$first_name = $row['first_name'];
			$last_name = $row['last_name'];
			$national_id = $row['national_id'];
			$preffered_language = $row['preffered_language'];
			$nickname = $row['nickname'];
			$date_of_birth = $row['date_of_birth'];
			$marital = $row['marital'];
			$dependants = $row['dependants'];
			$alt_phone = $row['alt_phone'];
			$dis_phone = $row['dis_phone'];
			$home_address = $row['home_address'];
			$owns = $row['owns'];
			$owns = ucwords(strtolower($owns));
			$home_occupy = $row['home_occupy'];
			$stations = $row['stations'];
			$status = $row['status'];
			$loan_officer = $row['loan_officer'];
			$collections_officer = $row['collections_officer'];
			$ref_first_name = $row['ref_first_name'];
			$ref_last_name = $row['ref_last_name'];
			$ref_known_as = $row['ref_known_as'];
			$ref_phone_number = $row['ref_phone_number'];
			$ref_relationship = $row['ref_relationship'];
			$asset_list = $row['asset_list'];
			$ref_landlord_title = $row['ref_landlord_title'];
			$ref_landlord_first_name = $row['ref_landlord_first_name'];
			$ref_landlord_last_name = $row['ref_landlord_last_name'];
			$ref_landlord_kown_as = $row['ref_landlord_kown_as'];
			$ref_landlord_relationship = $row['ref_landlord_relationship'];
			$ref_landlord_phone = $row['ref_landlord_phone'];
			$ref_landlord_known_as = $row['ref_landlord_known_as'];
			$lat = $row['lat'];
			$lng = $row['lng'];
			$affordability = $row['affordability'];
		}
		
		?>		
		<div id="page">
			<div id="content">
				<div class="post">
				<h2><font color="#000A8B"><?php echo $page_title; ?></font></h2>
				<h3>Customer Name: <?php echo $customer_name ?>, Disbursement #: <?php echo $dis_phone ?></h3>   
				<h3>Customer Branch: <?php echo $stations_name ?></h3>
				<?php //if($passportfileupload == "" || $resumefileupload == "" || $resumefileupload_back == "" || $mobile_no == "" || $title_name == "" || $title_name == '0' || $first_name == "" || $last_name == "" || $national_id == "" || $preffered_language == "" || $nickname == "" || $date_of_birth == "" || $date_of_birth == '0000-00-00' || $marital == "" || $marital == '0' || $dependants == "" || $alt_phone == "" || $dis_phone == "" || $home_address == "" || $owns == "" || $home_occupy == "" || $stations == "" || $affordability == "" || $affordability == '0' || $status == "" || $loan_officer == "" || $loan_officer == '0' || $collections_officer == "" || $collections_officer == '0' || $ref_first_name == "" || $ref_last_name == "" || $ref_known_as == "" || $ref_phone_number == "" || $ref_relationship == "" || $asset_list == "" || $gender == "" || $ref_landlord_title == "" || $ref_landlord_first_name == "" || $ref_landlord_last_name == || $ref_landlord_known_as == "" || $ref_landlord_relationship == "" || $ref_landlord_phone == "" || $lat == "" || $lng == ""){ ?>
					<?php if($passportfileupload == ""){ ?>
						Customer Photo<br />
					<?php } ?>
					<?php if($resumefileupload == ""){ ?>
						Customer ID Photo - Front<br />
					<?php } ?>
					<?php if($resumefileupload_back == ""){ ?>
						Customer ID Photo - Back<br />
					<?php } ?>
					<?php if($mobile_no == ""){ ?>
						Primary Phone Number<br />
					<?php } ?>
					<?php if($title_name == "" || $title_name == '0'){ ?>
						Customer Title<br />
					<?php } ?>
					<?php if($first_name == "" || $last_name == ""){ ?>
						Customer Name<br />
					<?php } ?>
					<?php if($national_id == ""){ ?>
						National ID<br />
					<?php } ?>
					<?php if($preffered_language == ""){ ?>
						Preferred Language<br />
					<?php } ?>
					<?php if($nickname == ""){ ?>
						Customer Nickname<br />
					<?php } ?>
					<?php if($date_of_birth == "" || $date_of_birth == '0000-00-00'){ ?>
						Date of Birth<br />
					<?php } ?>
					<?php if($marital == "" || $marital == '0'){ ?>
						Customer Nickname<br />
					<?php } ?>
					<?php if($dependants == ""){ ?>
						Customer Dependants<br />
					<?php } ?>
					<?php if($alt_phone == ""){ ?>
						Customer Alternate Phone Number<br />
					<?php } ?>
					<?php if($dis_phone == ""){ ?>
						Customer Disbursement Phone Number<br />
					<?php } ?>
					<?php if($home_address == ""){ ?>
						Customer Home Address<br />
					<?php } ?>
					<?php if($owns == ""){ ?>
						Customer Home Ownership<br />
					<?php } ?>
					<?php if($home_occupy == ""){ ?>
						How long the customer has lived in that location<br />
					<?php } ?>
					<?php if($stations == ""){ ?>
						Customer Branch<br />
					<?php } ?>
					<?php if($status == ""){ ?>
						Customer Status<br />
					<?php } ?>
					<?php if($loan_officer == ""){ ?>
						Loan Officer Attached to the Customer<br />
					<?php } ?>
					<?php if($collections_officer == ""){ ?>
						Collections Officer Attached to the Customer<br />
					<?php } ?>
					<?php if($ref_first_name == ""){ ?>
						Ref 1: First Name<br />
					<?php } ?>
					<?php if($ref_last_name == ""){ ?>
						Ref 1: Last Name<br />
					<?php } ?>
					<?php if($ref_known_as == ""){ ?>
						Ref 1: Also Know As<br />
					<?php } ?>
					<?php if($ref_phone_number == ""){ ?>
						Ref 1: Phone Number<br />
					<?php } ?>
					<?php if($ref_relationship == ""){ ?>
						Ref 1: Relationship<br />
					<?php } ?>
					<?php if($ref_landlord_first_name == ""){ ?>
						Ref 2: First Name<br />
					<?php } ?>
					<?php if($ref_landlord_last_name == ""){ ?>
						Ref 2: Last Name<br />
					<?php } ?>
					<?php if($ref_landlord_known_as == ""){ ?>
						Ref 2: Also Known As<br />
					<?php } ?>
					<?php if($ref_landlord_relationship == ""){ ?>
						Ref 2: Relationship<br />
					<?php } ?>
					<?php if($ref_landlord_phone == ""){ ?>
						Ref 2: Phone Number<br />
					<?php } ?>
					<?php if($ref_landlord_phone == ""){ ?>
						Ref 2: Phone Number<br />
					<?php } ?>
					<?php if($lat == "" || $lng == ""){ ?>
						Customer Geotagging<br />
					<?php } ?>
					<?php if($affordability == "" || $affordability == '0'){ ?>
						Ref 2: Phone Number<br />
					<?php } ?>
				<?php //} ?>
			</div>
			</div>
			<br class="clearfix" />
			</div>
		</div>
<?php
	}
	include_once('includes/footer.php');
?>
