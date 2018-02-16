<?php
	$userid = "";
	$adminstatus = 4;
	$property_manager_id = "";
	session_start();
	if (!empty($_SESSION)){
		$userid = $_SESSION["userid"] ;
		$adminstatus = $_SESSION["adminstatus"] ;
		$username = $_SESSION["username"];
		$station = $_SESSION["station"] ;
		$title = $_SESSION["title"] ;
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
		include_once('includes/db_conn.php');
		$transactiontime = date("Y-m-d G:i:s");
		$page_title = "Customer Search Filter";
		include_once('includes/header.php');
		$filter_clerk = 0;
		if (!empty($_GET)){	
			$mobile_no = $_GET['mobile_no'];
			$id_number = $_GET['id_number'];
			$first_name = $_GET['first_name'];
			$last_name = $_GET['last_name'];
			$dis_phone = $_GET['dis_phone'];
			$alt_phone = $_GET['alt_phone'];
			$phone_number = $_GET['dialed_number'];
			if($phone_number != ""){
				$phone_number = "254".$phone_number;
			}
		}
		
		$sql2 = mysql_query("select parent_branch from stations where id = '$station'");
        while($row = mysql_fetch_array($sql2)) {
            $parent_branch = $row['parent_branch'];
        }
		
		if ($mobile_no != "" || $first_name != "" || $last_name != "" || $id_number != "" || $phone_number != "" || $dis_phone != "" || $alt_phone != ""){
			
		?>
		<div id="page">
			<div id="content">
				<div class="post">
					<h2><?php echo $page_title ?></h2>
					<h3>Customer Details in the System</h3>
					<table width="100%" border="0" cellspacing="2" cellpadding="2" class="display" id="example">
						<thead bgcolor="#E6EEEE">
							<tr bgcolor='#fff'>
								<th>#</th>
								<th>Name</th>
								<th>ID</th>
								<th>Branch</th>
								<th>Mobile No</th>
								<th>Marital</th>
								<th>DOB</th>
							</tr>
						</thead>
						<tbody>
						<?php
							if($mobile_no != ""){
								if($station == '3'){
									$sql = mysql_query("select id, title, first_name, last_name, national_id, mobile_no, marital, date_of_birth, alt_phone, stations, status, passportfileupload, resumefileupload, lat, lng from users where mobile_no = '$mobile_no' and national_id != '' order by first_name asc");
								}
								else if($station == '4'){
									$sql = mysql_query("select id, title, first_name, last_name, national_id, mobile_no, marital, date_of_birth, alt_phone, stations, status, passportfileupload, resumefileupload, lat, lng from users where mobile_no = '$mobile_no' and national_id != '' order by first_name asc");
								}
								else{
								    if($title == '3' || $title == '8' || $title == '11'){  
								        $sql = mysql_query("select users.id, title, first_name, last_name, national_id, mobile_no, marital, date_of_birth, alt_phone, users.stations, status, passportfileupload, resumefileupload, lat, lng from users inner join stations on stations.id = users.stations where mobile_no = '$mobile_no' and national_id != '' order by first_name asc");
								    }
								    else{
								        $sql = mysql_query("select id, title, first_name, last_name, national_id, mobile_no, marital, date_of_birth, alt_phone, stations, status, passportfileupload, resumefileupload, lat, lng from users where mobile_no = '$mobile_no' and national_id != '' order by first_name asc");
								    }
								}	
							}
							else if($id_number != ""){
								if($station == '3'){
									$sql = mysql_query("select id, title, first_name, last_name, national_id, mobile_no, marital, date_of_birth, alt_phone, stations, status, passportfileupload, resumefileupload, lat, lng from users where national_id = '$id_number' and national_id != '' order by first_name asc");
								}
								else if($station == '4'){
									$sql = mysql_query("select id, title, first_name, last_name, national_id, mobile_no, marital, date_of_birth, alt_phone, stations, status, passportfileupload, resumefileupload, lat, lng from users where national_id = '$id_number' and national_id != '' order by first_name asc");
								}
								else{
									if($title == '3' || $title == '8' || $title == '11'){  
								        $sql = mysql_query("select users.id, title, first_name, last_name, national_id, mobile_no, marital, date_of_birth, alt_phone, users.stations, status, passportfileupload, resumefileupload, lat, lng from users inner join stations on stations.id = users.stations where national_id = '$id_number' and national_id != '' order by first_name asc");
								    }
								    else{
								        $sql = mysql_query("select id, title, first_name, last_name, national_id, mobile_no, marital, date_of_birth, alt_phone, stations, status, passportfileupload, resumefileupload, lat, lng from users where national_id = '$id_number' and national_id != '' order by first_name asc");
								    }
								}
							}
							else if($phone_number != ""){
								if($station == '3'){
									$sql = mysql_query("select id, title, first_name, last_name, national_id, mobile_no, marital, date_of_birth, alt_phone, stations, status, passportfileupload, resumefileupload, lat, lng from users where mobile_no = '$phone_number' and national_id != '' order by first_name asc");
								}
								else if($station == '4'){
									$sql = mysql_query("select id, title, first_name, last_name, national_id, mobile_no, marital, date_of_birth, alt_phone, stations, status, passportfileupload, resumefileupload, lat, lng from users where mobile_no = '$phone_number' or dis_phone = '$phone_number' and collections_agent = '$userid' and national_id != '' order by first_name asc");
								}
								else{
									if($title == '3' || $title == '8' || $title == '11'){  
								        $sql = mysql_query("select users.id, title, first_name, last_name, national_id, mobile_no, marital, date_of_birth, alt_phone, users.stations, status, passportfileupload, resumefileupload, lat, lng from users inner join stations on stations.id = users.stations where mobile_no = '$phone_number' and national_id != '' and parent_branch = '$parent_branch' order by first_name asc");
								    }
								    else{
								        $sql = mysql_query("select id, title, first_name, last_name, national_id, mobile_no, marital, date_of_birth, alt_phone, stations, status, passportfileupload, resumefileupload, lat, lng from users where mobile_no = '$phone_number' and national_id != '' and stations = '$station' order by first_name asc");
								    }
								}
							}
							else if($dis_phone != ""){
								if($station == '3'){
									$sql = mysql_query("select id, title, first_name, last_name, national_id, mobile_no, marital, date_of_birth, alt_phone, stations, status, passportfileupload, resumefileupload, lat, lng from users where dis_phone = '$dis_phone' and national_id != '' order by first_name asc");
								}
								else if($station == '4'){
									$sql = mysql_query("select id, title, first_name, last_name, national_id, mobile_no, marital, date_of_birth, alt_phone, stations, status, passportfileupload, resumefileupload, lat, lng from users where dis_phone = '$dis_phone' or dis_phone = '$phone_number' and collections_agent = '$userid' and national_id != '' order by first_name asc");
								}
								else{
									if($title == '3' || $title == '8' || $title == '11'){  
								        $sql = mysql_query("select users.id, title, first_name, last_name, national_id, mobile_no, marital, date_of_birth, alt_phone, users.stations, status, passportfileupload, resumefileupload, lat, lng from users inner join stations on stations.id = users.stations where dis_phone = '$dis_phone' and national_id != '' and parent_branch = '$parent_branch' order by first_name asc");
								    }
								    else{
								        $sql = mysql_query("select id, title, first_name, last_name, national_id, mobile_no, marital, date_of_birth, alt_phone, stations, status, passportfileupload, resumefileupload, lat, lng from users where dis_phone = '$dis_phone' and national_id != '' and stations = '$station' order by first_name asc");
								    }
								}
							}
							else if($alt_phone != ""){
								if($station == '3'){
									$sql = mysql_query("select id, title, first_name, last_name, national_id, mobile_no, marital, date_of_birth, alt_phone, stations, status, passportfileupload, resumefileupload, lat, lng from users where alt_phone = '$alt_phone' and national_id != '' order by first_name asc");
								}
								else if($station == '4'){
									$sql = mysql_query("select id, title, first_name, last_name, national_id, mobile_no, marital, date_of_birth, alt_phone, stations, status, passportfileupload, resumefileupload, lat, lng from users where alt_phone = '$alt_phone' or dis_phone = '$phone_number' and collections_agent = '$userid' and national_id != '' order by first_name asc");
								}
								else{
									if($title == '3' || $title == '8' || $title == '11'){  
								        $sql = mysql_query("select users.id, title, first_name, last_name, national_id, mobile_no, marital, date_of_birth, alt_phone, users.stations, status, passportfileupload, resumefileupload, lat, lng from users inner join stations on stations.id = users.stations where alt_phone = '$alt_phone' and national_id != '' and parent_branch = '$parent_branch' order by first_name asc");
								    }
								    else{
								        $sql = mysql_query("select id, title, first_name, last_name, national_id, mobile_no, marital, date_of_birth, alt_phone, stations, status, passportfileupload, resumefileupload, lat, lng from users where alt_phone = '$alt_phone' and national_id != '' and stations = '$station' order by first_name asc");
								    }
								}
							}
							else{
								if($station == '3'){
									if($first_name != ""){
										$sql = mysql_query("select id, title, first_name, last_name, national_id, mobile_no, marital, date_of_birth, alt_phone, stations, status, passportfileupload, resumefileupload, lat, lng from users where first_name like '%$first_name%' and national_id != '' order by first_name asc");
									}
									else if($last_name != ""){
										$sql = mysql_query("select id, title, first_name, last_name, national_id, mobile_no, marital, date_of_birth, alt_phone, stations, status, passportfileupload, resumefileupload, lat, lng from users where last_name like '%$last_name%' and national_id != '' order by first_name asc");
									}
								}
								else if($station == '4'){
									if($first_name != ""){
										$sql = mysql_query("select id, title, first_name, last_name, national_id, mobile_no, marital, date_of_birth, alt_phone, stations, status, passportfileupload, resumefileupload, lat, lng from users where first_name like '%$first_name%' and collections_agent = '$userid' and national_id != '' order by first_name asc");
									}
									else if($last_name != ""){
										$sql = mysql_query("select id, title, first_name, last_name, national_id, mobile_no, marital, date_of_birth, alt_phone, stations, status, passportfileupload, resumefileupload, lat, lng from users where last_name like '%$last_name%' and collections_agent = '$userid' and national_id != '' order by first_name asc");
									}
								}
								else{
									if($first_name != ""){
									    if($title == '3' || $title == '8' || $title == '11'){  
                                            $sql = mysql_query("select users.id, title, first_name, last_name, national_id, mobile_no, marital, date_of_birth, alt_phone, users.stations, status, passportfileupload, resumefileupload, lat, lng from users inner join stations on stations.id = users.stations where first_name like '%$first_name%' and national_id != '' and parent_branch = '$parent_branch' order by first_name asc");
                                        }
                                        else{
                                            $sql = mysql_query("select id, title, first_name, last_name, national_id, mobile_no, marital, date_of_birth, alt_phone, stations, status, passportfileupload, resumefileupload, lat, lng from users where first_name like '%$first_name%' and national_id != '' and stations = '$station' order by first_name asc");
                                        }
									}
									else if($last_name != ""){
									    if($title == '3' || $title == '8' || $title == '11'){  
                                            $sql = mysql_query("select users.id, title, first_name, last_name, national_id, mobile_no, marital, date_of_birth, alt_phone, users.stations, status, passportfileupload, resumefileupload, lat, lng from users inner join stations on stations.id = users.stations where last_name like '%$last_name%' and national_id != '' and parent_branch = '$parent_branch' order by first_name asc");
                                        }
                                        else{
                                            $sql = mysql_query("select id, title, first_name, last_name, national_id, mobile_no, marital, date_of_birth, alt_phone, stations, status, passportfileupload, resumefileupload, lat, lng from users where last_name like '%$last_name%' and national_id != '' and stations = '$station' order by first_name asc");
                                        }
									}
									
								}
							}
							while ($row = mysql_fetch_array($sql))
							 {
								$intcount++;
								$id = $row['id'];					
								$first_name = $row['first_name'];
								$first_name = ucwords(strtolower($first_name));
								$last_name = $row['last_name'];
								$last_name = ucwords(strtolower($last_name));
								$trans_date = $row['trans_date'];
								$mobile_no = $row['mobile_no'];
								$national_id = $row['national_id'];
								$marital = $row['marital'];
								$date_of_birth = $row['date_of_birth'];
								$alt_phone = $row['alt_phone'];
								$stations = $row['stations'];
								$status = $row['status'];
								$passportfileupload = $row['passportfileupload'];
								$resumefileupload = $row['resumefileupload'];
								$lat = $row['lat'];
								$lng = $row['lng'];
								$sql2 = mysql_query("select id, marital from marital where id = '$marital'");
								while($row = mysql_fetch_array($sql2)) {
									$marital = $row['marital'];
								}
								$sql2 = mysql_query("select id, stations from stations where id = '$stations'");
								while($row = mysql_fetch_array($sql2)) {
									$stations = $row['stations'];
									$stations = ucwords(strtolower($stations));
								}
								
								if($passportfileupload == "" || $resumefileupload == "" || $lat == "" || $lng == ""){
									$issues = '<font color="red">Photo, ID or Geo tagging missing</font>';
								}
								else{
									$issues = '<font color="green">None, Good to go</font>';
								}
							
								if ($intcount % 2 == 0) {
									$display= '<tr bgcolor = #F0F0F6>';
								}
								else {
									$display= '<tr>';
								}
								echo $display;
								echo "<td valign='top'>$intcount.</td>";
								echo "<td valign='top'>$first_name $last_name</td>";
								echo "<td valign='top'>$national_id</td>";
								echo "<td valign='top'>$stations</td>";
								echo "<td valign='top'>$mobile_no</td>";
								echo "<td valign='top'>$marital</td>";
								echo "<td valign='top'>$date_of_birth</td>";
								echo "</tr>";
							}
							?>
						</tbody>
						<tfoot bgcolor="#E6EEEE">
							<tr bgcolor='#fff'>
								<th>#</th>
								<th>Name</th>
								<th>ID</th>
								<th>Branch</th>
								<th>Mobile No</th>
								<th>Marital</th>
								<th>DOB</th>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
			<br class="clearfix" />
			</div>
		</div>
		<?php
		}
		else{
		?>		
		<div id="page">
			<div id="content">
				<div class="post">
				
					<h2><?php echo $page_title ?></h2>
					<form id="frmCreateTenant" name="frmCreateTenant" method="GET" action="<?php echo $_SERVER['PHP_SELF'];?>">
					<table border="0" width="100%" cellspacing="2" cellpadding="2">
							<tr >
								<td  valign="top">Search by Phone Number: </td>
								<td>
									<input title="Enter the Phone Number" value="" id="mobile_no" name="mobile_no" type="text" maxlength="100" class="main_input" size="15" />
								</td>
								<td  valign="top">Search by ID Number: </td>
								<td>
									<input title="Enter the ID Number" value="" id="id_number" name="id_number" type="text" maxlength="100" class="main_input" size="15" />
								</td>
							</tr>
							<!--<tr>
								<td  valign="top">Search by Customer First Name: </td>
								<td>
									<input title="Enter the Name" value="" id="autocomplete" name="first_name" type="text" maxlength="100" class="main_input" size="15" />
								</td>
								<td  valign="top">Search by Customer Last Name: </td>
								<td>
									<input title="Enter the Name" value="" id="autocomplete" name="last_name" type="text" maxlength="100" class="main_input" size="15" />
								</td>
								
							</tr>
							<tr>
								<td  valign="top">Search by Customer Disbursement Phone #: </td>
								<td>
									<input title="Enter the Customer Disbursement Phone" value="" id="autocomplete" name="dis_phone" type="text" maxlength="100" class="main_input" size="15" />
								</td>
								<td  valign="top">Search by Customer Alternate Phone #: </td>
								<td>
									<input title="Enter the Customer Alternate Phone" value="" id="autocomplete" name="alt_phone" type="text" maxlength="100" class="main_input" size="15" />
								</td>
								
							</tr>-->
							<tr>
								<td><button name="btnNewCard" id="button">Search</button></td>
							</tr>
						</table>
					</form>
				</div>
			</div>
			<br class="clearfix" />
			</div>
		</div>
<?php
		}
	}
	include_once('includes/footer.php');
?>
