<?php
$userid = "";
$adminstatus = 4;
$property_manager_id = "";
$passportfileupload = "";
$resumefileupload = "";
session_start();
if (!empty($_SESSION)) {
    $userid = $_SESSION["userid"];
    $adminstatus = $_SESSION["adminstatus"];
    $station = $_SESSION["station"];
    $username = $_SESSION["username"];
    $title = $_SESSION["title"];
    $passportfileupload = $_SESSION["passportfileupload"];
    $resumefileupload = $_SESSION["resumefileupload"];
    $resumefileupload_back = $_SESSION["resumefileupload_back"];
}

if (!empty($_GET)) {
    $mode = $_GET['mode'];
    $user_id = $_GET['user_id'];
    $status = $_GET['status'];
}
include_once('includes/db_conn.php');
$sql2 = mysql_query("select stations from users where id = '$user_id'");
while ($row = mysql_fetch_array($sql2)) {
    $stations = $row['stations'];
}
//if($adminstatus != 1 || $adminstatus != 2 || $adminstatus != 4){
if ($adminstatus == 4) {
    include_once('includes/header.php');
    ?>
    <script type="text/javascript">
        document.location = "insufficient_permission.php";
    </script>
    <?php
} else {
    if (!empty($_GET)) {
        $mode = $_GET['mode'];
        $user_id = $_GET['user_id'];
        $id_status = $_GET['status'];
        $page_edit_status = $_GET['status'];
    }
    $transactiontime = date("Y-m-d G:i:s");
    if ($mode == 'edit') {
        $sql = mysql_query("select passportfileupload, gender, resumefileupload, resumefileupload_back, mobile_no, title, first_name, last_name, national_id, preffered_language, nickname, date_of_birth, marital, dependants, alt_phone, dis_phone, home_address, owns, home_occupy, stations, status, loan_officer, collections_officer, ref_first_name, ref_last_name, ref_known_as, ref_phone_number, ref_relationship, asset_list, ref_landlord_title, ref_landlord_first_name, ref_landlord_last_name, ref_landlord_known_as, ref_landlord_relationship, ref_landlord_phone, marketing_drive, mobile_friend, refer_friend, customer_state, market from users where id = '$user_id'");
        while ($row = mysql_fetch_array($sql)) {
            $passportfileupload = $row['passportfileupload'];
            $resumefileupload = $row['resumefileupload'];
            $resumefileupload_back = $row['resumefileupload_back'];
            $mobile_no = $row['mobile_no'];
            $title_name = $row['title'];
            $gender = $row['gender'];
            $first_name = $row['first_name'];
            $first_name = ucwords(strtolower($first_name));
            $last_name = $row['last_name'];
            $last_name = ucwords(strtolower($last_name));
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
            $ref_landlord_relationship = $row['ref_landlord_relationship'];
            $ref_landlord_phone = $row['ref_landlord_phone'];
            $ref_landlord_known_as = $row['ref_landlord_known_as'];
            $marketing_drive = $row['marketing_drive'];
            $mobile_friend = $row['mobile_friend'];
            $refer_friend = $row['refer_friend'];
            $customer_state = $row['customer_state'];
            $market = $row['market'];
        }
        $sql2 = mysql_query("select id, title from title_names where id = '$title_name'");
        while ($row = mysql_fetch_array($sql2)) {
            $title_id = $row['id'];
            $title_name = $row['title'];
        }
        $sql2 = mysql_query("select id, marital from marital where id = '$marital'");
        while ($row = mysql_fetch_array($sql2)) {
            $marital_id = $row['id'];
            $marital = $row['marital'];
        }

        $sql2 = mysql_query("SELECT id, gender FROM gender where id = '$gender'");
        while ($row = mysql_fetch_array($sql2)) {
            $gender_id = $row['id'];
            $gender = $row['gender'];
        }

        $sql2 = mysql_query("select id, stations from stations where id = '$stations'");
        while ($row = mysql_fetch_array($sql2)) {
            $stations_id = $row['id'];
            $stations = $row['stations'];
        }
        $sql2 = mysql_query("select id, status from loan_declined_status_codes where id = '$status'");
        while ($row = mysql_fetch_array($sql2)) {
            $decline_id = $row['id'];
            $decline_reason = $row['status'];
        }
        $sql2 = mysql_query("select id, first_name, last_name from user_profiles where id = '$loan_officer'");
        while ($row = mysql_fetch_array($sql2)) {
            $loan_officer_id = $row['id'];
            $loan_first_name = $row['first_name'];
            $loan_last_name = $row['last_name'];
            $loan_officer = $loan_first_name . " " . $loan_last_name;
        }
        $sql2 = mysql_query("select id, first_name, last_name from user_profiles where id = '$collections_officer'");
        while ($row = mysql_fetch_array($sql2)) {
            $collections_officer_id = $row['id'];
            $col_first_name = $row['first_name'];
            $col_last_name = $row['last_name'];
            $collections_officer = $col_first_name . " " . $col_last_name;
        }
        $sql2 = mysql_query("select market from markets where id = '$market'");
        while ($row = mysql_fetch_array($sql2)) {
            $market = $row['market'];
        }
        //if($status == '0'){
        //	$decline_reason = 'None';
        //}
        $page_title = "Update Customer Detail(s)";
    } else {
        $page_title = "Create new Customer Detail(s)";
    }
    //if (!empty($_SESSION)){
    //	$passportfileupload = $_SESSION["passportfileupload"];
    //	$resumefileupload = $_SESSION["resumefileupload"];
    //}

    include_once('includes/header.php');
    ?>		
    <div id="page">
        <div id="content">
            <div class="post">
                <h2><font color="#000A8B"><?php echo $page_title; ?></font></h2>
                <?php if ($id_status == 'exists_national_id') { ?>
                    <table width="60%">
                        <tr bgcolor="red">
                            <td><font color="white" size="2">&nbsp;&nbsp;Yikes! Something's gone wrong.</td>
                        </tr>
                    </table>
                    <font color="red">
                    * Either the National ID specified already exists<br />
                    <!--* Either the phone number length is not correct<br />
                    * Either the home address is not detailed enough<br />
                    * Either the primary mobile, disbursement phone or Alternate mobile exists in the system<br />-->
                    </font>
                <?php } else if ($id_status == 'length_mobile_no') {
                    ?>
                    <table width="60%">
                        <tr bgcolor="red">
                            <td><font color="white" size="2">&nbsp;&nbsp;Yikes! Something's gone wrong.</td>
                        </tr>
                    </table>
                    <font color="red">					
                    * Either the phone number length is not correct<br />
                    <!--* Either the National ID specified already exists<br />
                    * Either the home address is not detailed enough<br />
                    * Either the primary mobile, disbursement phone or Alternate mobile exists in the system<br />-->
                    </font>
                <?php } ?>
                <form id="frmOrder" name="frmOrder" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <?php if ($mode == 'edit') { ?>
                        <?php if ($passportfileupload == "") { ?>
                            <p><img class="alignleft" src="images/unknown.jpg" width="150px"></p>
                        <?php } else {
                            ?>
                            <p><img class="alignleft" src="<?php echo $passportfileupload ?>" width="150px"></p>
                        <?php } ?>
                    <?php } ?>

                    <table border="0" width="85%" cellspacing="2" cellpadding="2">	
                        <input type="hidden" name="users_id" id="users_id" value="<?php echo $user_id ?>" />		
                        <input type="hidden" name="page_status" id="page_status" value="<?php echo $mode ?>" />
                        <input type="hidden" name="id_status" id="id_status" value="<?php echo $id_status ?>" />
                        <tr bgcolor = #F0F0F6>
                            <td valign='top' width="15%">Upload Customer Photo </td>
                            <td valign='top' width="35%">
                                <?php if ($mode == 'edit') { ?>
                                    <?php if ($station != 4) { ?>
                                        <a href="passport_uploadform.php?user_id=<?php echo $user_id ?>">Click here to Upload the Customer Photo</a> <br />
                                    <?php } ?>
                                    <input id="passportfileupload" name="passportfileupload" value="<?php echo $passportfileupload ?>" type="text" readonly="true" size="30"/>
                                <?php } else {
                                    ?>
                                    <?php if ($station != 4) { ?>
                                        <a href="passport_uploadform.php">Click here to Upload the Customer Photo</a> <br />
                                    <?php } ?>
                                    <input id="passportfileupload" name="passportfileupload" value="<?php echo $passportfileupload ?>" type="text" readonly="true" size="30"/>
                                <?php } ?>
                            </td>
                            <td valign='top' width="15%">Upload National ID Photo </td>
                            <td valign='top' width="35%">
                                <?php if ($station != 4) { ?>
                                    <a href="resume_uploadform.php?user_id=<?php echo $user_id ?>">Click here to Upload National ID Photo - Front</a><br />
                                <?php } ?>
                                <input id="resumefileupload" name="resumefileupload" value="<?php echo $resumefileupload ?>" type="text" readonly="true" size="30"/><br />
                                <?php if ($station != 4) { ?>
                                    <a href="resume_uploadform_back.php?user_id=<?php echo $user_id ?>">Click here to Upload National ID Photo - Back</a><br />
                                <?php } ?>
                                <input id="resumefileupload_back" name="resumefileupload_back" value="<?php echo $resumefileupload_back ?>" type="text" readonly="true" size="30"/>
                            </td>
                        </tr>
                        <tr>
                            <td valign="top" width="15%">Primary Mobile # *<br />
                                <strong>Format: 254xxxxxxxxx</strong></td>
                            <td valign="top" width="35%" colspan="3">
                                <?php if ($mode == 'edit') { ?>
                                    <input title="Enter Mobile Number" value="<?php echo $mobile_no ?>" id="mobile_no" name="mobile_no" type="text" maxlength="100" class="main_input" size="35" />
                                    <input value="<?php echo $mobile_no ?>" id="old_mobile_no" name="old_mobile_no" type="hidden" />
                                <?php } else { ?>
                                    <input title="Enter Mobile Number" value="254" id="mobile_no" name="mobile_no" type="text" maxlength="100" class="main_input" size="35" />
                                    <input value="<?php echo $mobile_no ?>" id="old_mobile_no" name="old_mobile_no" type="hidden" />
                                <?php } ?>
                            </td>
                        </tr>
                        <tr bgcolor = #F0F0F6>
                            <td valign='top' width="15%">Title *</td>
                            <td valign='top' width="35%" colspan="3">
                                <select name='title_name' id='title_name'>
                                    <?php
                                    if ($mode == 'edit') {
                                        ?>
                                        <option value="<?php echo $title_id ?>"><?php echo $title_name ?></option>
                                        <option value=''> </option>
                                        <?php
                                    } else {
                                        ?>
                                        <option value=''> </option>
                                        <?php
                                    }
                                    //echo "<option value=''>" "</option>"; 										
                                    $sql2 = mysql_query("select id, title from title_names order by title asc");
                                    while ($row = mysql_fetch_array($sql2)) {
                                        $id = $row['id'];
                                        $title_name = $row['title'];
                                        echo "<option value='$id'>" . $title_name . "</option>";
                                    }
                                    ?>
                                </select>
                                <input value="<?php echo $id ?>" id="old_title" name="old_title" type="hidden" />
                            </td>
                        </tr>
                        <tr>
                            <td valign='top' width="15%">First Name *</td>
                            <td valign='top' width="35%">
                                <input title="Enter First Name" value="<?php echo $first_name ?>" id="first_name" name="first_name" type="text" maxlength="100" class="main_input" size="35" />
                                <input value="<?php echo $first_name ?>" id="old_first_name" name="old_first_name" type="hidden" />
                            </td>
                            <td valign='top' width="15%">Last Name *</td>
                            <td valign='top' width="35%">
                                <input title="Enter Last Name" value="<?php echo $last_name ?>" id="last_name" name="last_name" type="text" maxlength="100" class="main_input" size="35" />
                                <input value="<?php echo $last_name ?>" id="old_last_name" name="old_last_name" type="hidden" />
                            </td>
                        </tr>
                        <tr bgcolor = #F0F0F6>
                            <td valign='top' width="15%">National ID *</td>
                            <td valign='top' width="35%">
                                <input title="Enter National ID" value="<?php echo $national_id ?>" id="national_id" name="national_id" type="text" maxlength="100" class="main_input" size="35" />
                                <input value="<?php echo $national_id ?>" id="old_national_id" name="old_national_id" type="hidden" />
                            </td>
                            <td valign="top" width="15%">Preferred Language </td>
                            <td valign="top" width="35%">
                                <input title="Enter Preffered Language" value="<?php echo $preffered_language ?>" id="preffered_language" name="preffered_language" type="text" maxlength="100" class="main_input" size="35" />
                                <input value="<?php echo $preffered_language ?>" id="old_preffered_language" name="old_preffered_language" type="hidden" />
                            </td>
                        </tr>
                        <tr>
                            <td valign='top' width="15%">Also Known As (Nickname) </td>
                            <td valign='top' width="35%">
                                <input title="Enter Also Known As" value="<?php echo $nickname ?>" id="nickname" name="nickname" type="text" maxlength="100" class="main_input" size="35" />
                                <input value="<?php echo $nickname ?>" id="old_nickname" name="old_nickname" type="hidden" />
                            </td>
                            <td valign='top' width="15%">Date of Birth (DOB)</td>
                            <td valign='top' width="35%">
                                <input title="Enter Date of Birth" value="<?php echo $date_of_birth ?>" id="date_of_birth" name="date_of_birth" type="text" maxlength="100" class="main_input" size="35" />
                                <input value="<?php echo $date_of_birth ?>" id="old_date_of_birth" name="old_date_of_birth" type="hidden" />
                            </td>
                        </tr>
                        <tr bgcolor = #F0F0F6>
                            <td valign='top' width="15%">Marital Status </td>
                            <td valign='top' width="35%">
                                <select name='marital' id='marital'>
                                    <?php
                                    if ($mode == 'edit') {
                                        ?>
                                        <option value="<?php echo $marital_id ?>"><?php echo $marital ?></option>
                                        <?php
                                    } else {
                                        ?>
                                        <option value=''> </option>
                                        <?php
                                    }
                                    //echo "<option value=''>" "</option>"; 										
                                    $sql2 = mysql_query("select id, marital from marital order by marital asc");
                                    while ($row = mysql_fetch_array($sql2)) {
                                        $id = $row['id'];
                                        $marital = $row['marital'];
                                        echo "<option value='$id'>" . $marital . "</option>";
                                    }
                                    ?>
                                </select>
                                <input value="<?php echo $marital ?>" id="old_marital" name="old_marital" type="hidden" />
                            </td>
                            <td valign="top" width="15%">No. of Dependants </td>
                            <td valign="top" width="35%">
                                <input title="Enter No of Dependants" value="<?php echo $dependants ?>" id="dependants" name="dependants" type="text" maxlength="100" class="main_input" size="35" />
                                <input value="<?php echo $dependants ?>" id="old_dependants" name="old_dependants" type="hidden" />
                            </td>
                        </tr>

                        <tr bgcolor = #F0F0F6>
                            <td valign='top' width="15%">Gender </td>
                            <td valign='top' width="35%">
                                <select name='gender' id='gender'>
                                    <?php
                                    if ($mode == 'edit') {
                                        ?>
                                        <option value="<?php echo $gender_id ?>"><?php echo $gender ?></option>
                                        <?php
                                    }
                                    ?>

                                    <?php
                                    //echo "<option value=''>" "</option>"; 										
                                    $sql2 = mysql_query("SELECT id, gender FROM gender");
                                    while ($row = mysql_fetch_array($sql2)) {
                                        $id = $row['id'];
                                        $gender = $row['gender'];
                                        echo "<option value='$id'>" . $gender . "</option>";
                                    }
                                    ?>
                                </select>
                                <input value="<?php echo $gender ?>" id="old_marital" name="old_gender" type="hidden" />
                            </td>
                        </tr>

                        <tr>
                            <td valign='top' width="15%">Alternate Phone # <br />
                                <strong>Format: 254xxxxxxxxx</strong></td>
                            <td valign='top' width="35%">
                                <?php if ($mode == 'edit') { ?>
                                    <input title="Enter Alternate Phone Number" value="<?php echo $alt_phone ?>" id="alt_phone" name="alt_phone" type="text" maxlength="100" class="main_input" size="35" />
                                    <input value="<?php echo $alt_phone ?>" id="old_alt_phone" name="old_alt_phone" type="hidden" />
                                <?php } else { ?>
                                    <input title="Enter Alternate Phone Number" value="254" id="alt_phone" name="alt_phone" type="text" maxlength="100" class="main_input" size="35" />
                                    <input value="<?php echo $alt_phone ?>" id="old_alt_phone" name="old_alt_phone" type="hidden" />
                                <?php } ?>

                            </td>
                            <td valign='top' width="15%">Disbursement Number *<br />
                                <strong>Format: 254xxxxxxxxx</strong></td>
                            <td valign='top' width="35%">
                                <?php if ($mode == 'edit') { ?>
                                    <input title="Enter Disbursement Phone Number" value="<?php echo $dis_phone ?>" id="dis_phone" name="dis_phone" type="text" maxlength="100" class="main_input" size="35" />
                                    <input value="<?php echo $dis_phone ?>" id="old_dis_phone" name="old_dis_phone" type="hidden" />
                                <?php } else { ?>
                                    <input title="Enter Disbursement Phone Number" value="254" id="dis_phone" name="dis_phone" type="text" maxlength="100" class="main_input" size="35" />
                                    <input value="<?php echo $dis_phone ?>" id="old_dis_phone" name="old_dis_phone" type="hidden" />
                                <?php } ?>
                            </td>
                        </tr>
                        <tr bgcolor = #F0F0F6>
                            <td valign='top' >Home Address *</td>
                            <td valign='top' colspan="3">
                                <textarea title="Enter Home Addres" name="home_address" id="home_address" cols="95" rows="5" class="textfield"><?php echo $home_address ?></textarea>
                                <input value="<?php echo $home_address ?>" id="old_home_address" name="old_home_address" type="hidden" />
                            </td>
                        </tr>
                        <tr>
                            <td valign='top' width="15%">Home Ownership </td>
                            <td valign='top' width="35%">
                                <select name='owns' id='owns'>
                                    <?php
                                    if ($mode == 'edit') {
                                        ?>
                                        <option value="<?php echo $owns ?>"><?php echo $owns ?></option>
                                        <?php
                                    } else {
                                        ?>
                                        <option value=''> </option>
                                        <?php
                                    }
                                    echo "<option value=''></option>";
                                    echo "<option value='Rents'>Rents</option>";
                                    echo "<option value='Owns'>Owns</option>";
                                    ?>
                                </select>
                                <input value="<?php echo $marital ?>" id="old_marital" name="old_marital" type="hidden" />
                            </td>
                            <td valign='top' width="15%">Lived there since? </td>
                            <td valign='top' width="35%" colspan="3">
                                <input title="Enter how long the customer has lived there" value="<?php echo $home_occupy ?>" id="home_occupy" name="home_occupy" type="text" maxlength="100" class="main_input" size="35" />
                                <input value="<?php echo $home_occupy ?>" id="old_home_occupy" name="old_home_occupy" type="hidden" />
                            </td>
                            <!--<td valign='top' width="15%">Geotagging? </td>
                            <td valign='top' width="35%">
                                    <input title="Enter Disbursement Phone Number" value="<?php echo $mapLat ?>" id="mapLat" name="mapLat" type="text" maxlength="100" class="main_input" size="35" />-->
                            </td>
                        </tr>
                        <tr bgcolor = #F0F0F6>
                            <td valign='top' width="15%">Branch: *</td>
                            <td valign='top' width="35%" colspan="3">
                                <select name='stations' id='stations'>
                                    <?php
                                    if ($mode == 'edit') {
                                        ?>
                                        <option value="<?php echo $stations_id ?>"><?php echo $stations ?></option>
                                        <?php
                                    } else {
                                        ?>
                                        <option value=''> </option>
                                        <?php
                                    }
                                    //echo "<option value=''>" "</option>"; 
                                    if ($station == '3') {
                                        $sql2 = mysql_query("select id, stations from stations where active = '0' order by stations asc");
                                    } else {
                                        $sql2 = mysql_query("select id, stations from stations where active = '0' and id = '$station' order by stations asc");
                                    }
                                    while ($row = mysql_fetch_array($sql2)) {
                                        $id = $row['id'];
                                        $stations = $row['stations'];
                                        echo "<option value='$id'>" . $stations . "</option>";
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
                                    if ($mode == 'edit') {
                                        ?>
                                        <option value="<?php echo $loan_officer_id ?>"><?php echo $loan_officer ?></option>
                                        <option value=''> </option>	
                                        <?php
                                    } else {
                                        ?>
                                        <option value=''> </option>
                                        <?php
                                    }
                                    //echo "<option value=''>" "</option>"; 										
                                    if ($station == '3') {
                                        $sql2 = mysql_query("select user_profiles.id, first_name, last_name, stations.stations from user_profiles inner join stations on stations.id = user_profiles.station where title = '1' and user_status = '1'");
                                    } else if ($title == '3' || $title == '8') {
                                        $sql2 = mysql_query("select user_profiles.id, first_name, last_name, stations.stations from user_profiles inner join stations on stations.id = user_profiles.station where title = '1' and user_status = '1' and station = '$station'");
                                    } else {
                                        $sql2 = mysql_query("select id, first_name, last_name from user_profiles where station = '$station' and id = '$userid'");
                                    }
                                    while ($row = mysql_fetch_array($sql2)) {
                                        $loan = $row['id'];
                                        $first_name = $row['first_name'];
                                        $last_name = $row['last_name'];
                                        if ($station == '3') {
                                            $stations = $row['stations'];
                                            echo "<option value='$loan'>" . $stations . ": " . $first_name . " " . $last_name . "</option>";
                                        } else {
                                            echo "<option value='$loan'>" . $first_name . " " . $last_name . "</option>";
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
                                    if ($mode == 'edit') {
                                        ?>
                                        <option value="<?php echo $collections_officer_id ?>"><?php echo $collections_officer ?></option>
                                        <option value=''> </option>	
                                        <?php
                                    } else {
                                        ?>
                                        <option value=''> </option>
                                        <?php
                                    }
                                    //echo "<option value=''>" "</option>";
                                    if ($station == '3') {
                                        $sql2 = mysql_query("select user_profiles.id, first_name, last_name, stations.stations from user_profiles inner join stations on stations.id = user_profiles.station where title = '2' and user_status = '1'");
                                    } else if ($title == '3' || $title == '8') {
                                        $sql2 = mysql_query("select user_profiles.id, first_name, last_name, stations.stations from user_profiles inner join stations on stations.id = user_profiles.station where title = '2' and user_status = '1' and station = '$station'");
                                    } else {
                                        $sql2 = mysql_query("select id, first_name, last_name from user_profiles where station = '$station' and title = '2' and user_status = '1'");
                                    }
                                    while ($row = mysql_fetch_array($sql2)) {
                                        $loan = $row['id'];
                                        $first_name = $row['first_name'];
                                        $last_name = $row['last_name'];
                                        if ($station == '3') {
                                            $stations = $row['stations'];
                                            echo "<option value='$loan'>" . $stations . ": " . $first_name . " " . $last_name . "</option>";
                                        } else {
                                            echo "<option value='$loan'>" . $first_name . " " . $last_name . "</option>";
                                        }
                                    }
                                    ?>
                                </select>
                                <input value="<?php echo $collections_officer_id ?>" id="old_collections_officer" name="old_collections_officer" type="hidden" />
                            </td>
                        </tr>
                        <tr bgcolor = #F0F0F6>
                            <td valign='top' width="15%">Ref 1: First Name *</td>
                            <td valign='top' width="35%">
                                <input title="Enter Reference First Name" value="<?php echo $ref_first_name ?>" id="ref_first_name" name="ref_first_name" type="text" maxlength="100" class="main_input" size="35" />
                                <input value="<?php echo $ref_first_name ?>" id="old_ref_first_name" name="old_ref_first_name" type="hidden" />
                            </td>
                            <td valign='top' width="15%">Ref 1: Last Name *</td>
                            <td valign='top' width="35%">
                                <input title="Enter Reference Last Name" value="<?php echo $ref_last_name ?>" id="ref_last_name" name="ref_last_name" type="text" maxlength="100" class="main_input" size="35" />
                                <input value="<?php echo $ref_last_name ?>" id="old_ref_last_name" name="old_ref_last_name" type="hidden" />
                            </td>
                        </tr>
                        <tr>
                            <td valign='top' width="15%">Ref 1: Also Know As </td>
                            <td valign='top' width="35%">
                                <input title="Enter Reference Also Know As" value="<?php echo $ref_known_as ?>" id="ref_known_as" name="ref_known_as" type="text" maxlength="100" class="main_input" size="35" />
                                <input value="<?php echo $ref_known_as ?>" id="old_ref_known_as" name="old_ref_known_as" type="hidden" />
                            </td>
                            <td valign='top' width="15%">Ref 1: Phone # *<br />
                                <strong>Format: 254xxxxxxxxx</strong></td>
                            <td valign='top' width="35%">
                                <?php if ($mode == 'edit') { ?>
                                    <input title="Enter Reference Phone Number" value="<?php echo $ref_phone_number ?>" id="ref_phone_number" name="ref_phone_number" type="text" maxlength="100" class="main_input" size="35" />
                                    <input value="<?php echo $ref_phone_number ?>" id="old_ref_phone_number" name="old_ref_phone_number" type="hidden" />
                                <?php } else { ?>
                                    <input title="Enter Reference Phone Number" value="254" id="ref_phone_number" name="ref_phone_number" type="text" maxlength="100" class="main_input" size="35" />
                                    <input value="<?php echo $ref_phone_number ?>" id="old_ref_phone_number" name="old_ref_phone_number" type="hidden" />
                                <?php } ?>

                            </td>
                        </tr>
                        <tr bgcolor = #F0F0F6>
                            <td valign='top' width="15%">Ref 1: Relationship </td>
                            <td valign='top' width="35%" colspan="3">
                                <input title="Enter Reference Relationship" value="<?php echo $ref_relationship ?>" id="ref_relationship" name="ref_relationship" type="text" maxlength="100" class="main_input" size="35" />
                                <input value="<?php echo $ref_relationship ?>" id="old_ref_relationship" name="old_ref_relationship" type="hidden" />
                            </td>
                        </tr>
                        <tr>
                            <td valign='top' width="15%">Ref 2: First Name *</td>
                            <td valign='top' width="35%">
                                <input title="Enter Reference First Name" value="<?php echo $ref_landlord_first_name ?>" id="ref_landlord_first_name" name="ref_landlord_first_name" type="text" maxlength="100" class="main_input" size="35" />
                                <input value="<?php echo $ref_landlord_first_name ?>" id="old_ref_first_name" name="old_ref_first_name" type="hidden" />
                            </td>
                            <td valign='top' width="15%">Ref 2: Last Name *</td>
                            <td valign='top' width="35%">
                                <input title="Enter Reference Last Name" value="<?php echo $ref_landlord_last_name ?>" id="ref_landlord_last_name" name="ref_landlord_last_name" type="text" maxlength="100" class="main_input" size="35" />
                                <input value="<?php echo $ref_landlord_last_name ?>" id="old_ref_last_name" name="old_ref_last_name" type="hidden" />
                            </td>
                        </tr>
                        <tr bgcolor = #F0F0F6>
                            <td valign='top' width="15%">Ref 2: Also Known As </td>
                            <td valign='top' width="35%">
                                <input title="Enter Reference First Name" value="<?php echo $ref_landlord_known_as ?>" id="ref_landlord_known_as" name="ref_landlord_known_as" type="text" maxlength="100" class="main_input" size="35" />
                                <input value="<?php echo $ref_landlord_known_as ?>" id="old_ref_first_name" name="old_ref_first_name" type="hidden" />
                            </td>
                            <td valign='top' width="15%">Ref 2: Phone #<br />
                                <strong>Format: 254xxxxxxxxx</strong></td>
                            <td valign='top' width="35%" colspan="3">
                                <?php if ($mode == 'edit') { ?>
                                    <input title="Enter Reference First Name" value="<?php echo $ref_landlord_phone ?>" id="ref_landlord_phone" name="ref_landlord_phone" type="text" maxlength="100" class="main_input" size="35" />
                                    <input value="<?php echo $ref_landlord_phone ?>" id="old_ref_landlord_phone" name="old_ref_landlord_phone" type="hidden" />
                                <?php } else { ?>
                                    <input title="Enter Reference First Name" value="254" id="ref_landlord_phone" name="ref_landlord_phone" type="text" maxlength="100" class="main_input" size="35" />
                                    <input value="<?php echo $ref_landlord_phone ?>" id="old_ref_landlord_phone" name="old_ref_landlord_phone" type="hidden" />
                                <?php } ?>
                            </td>
                        </tr>
                        <tr>
                            <td valign='top' width="15%">Ref 2: Relationship </td>
                            <td valign='top' width="35%">
                                <input title="Enter Reference Last Name" value="<?php echo $ref_landlord_relationship ?>" id="ref_landlord_relationship" name="ref_landlord_relationship" type="text" maxlength="100" class="main_input" size="35" />
                                <input value="<?php echo $ref_landlord_relationship ?>" id="old_ref_last_name" name="old_ref_last_name" type="hidden" />
                            </td>

                        </tr>
                        <?php if ($title == '8') { ?>
                            <tr bgcolor = #F0F0F6>
                                <td valign='top' width="15%">How did you hear about UPIA?</td>
                                <td valign='top' width="35%">
                                    <select name='marketing_drive' id='marketing_drive'>
                                        <?php
                                        if ($mode == 'edit') {
                                            ?>
                                            <option value="<?php echo $marketing_drive ?>"><?php echo $marketing_drive ?></option>
                                            <?php
                                        } else {
                                            ?>
                                            <option value=''> </option>
                                            <?php
                                        }
                                        echo "<option value='Marketing'>Marketing</option>";
                                        echo "<option value='Staff'>Staff</option>";
                                        echo "<option value='Friend'>Friend</option>";
                                        ?>
                                    </select>
                                </td>
                                <td valign='top' width="15%">Mobile Number of Friend who Refereed you</td>
                                <td valign='top' width="35%">
                                    <input title="Enter Reference Last Name" value="<?php echo $mobile_friend ?>" id="mobile_friend" name="mobile_friend" type="text" maxlength="100" class="main_input" size="35" />
                                </td>
                            </tr>
                            <tr>
                                <td valign='top' width="15%">Do you want to refer a friend?</td>
                                <td valign='top' width="35%" colspan="3">
                                    <textarea title="Refer a Friend" name="refer_friend" id="refer_friend" cols="95" rows="5" class="textfield"><?php echo $refer_friend ?></textarea>
                                </td>
                            </tr>
                        <?php } ?>
                        <?php if ($station == '3') { ?>
                            <tr bgcolor = #F0F0F6>
                                <td valign='top' width="15%">Customer State</td>
                                <td valign='top' width="35%" colspan="3">
                                    <select name='customer_state' id='customer_state'>
                                        <?php
                                        if ($mode == 'edit') {
                                            ?>
                                            <option value="<?php echo $customer_state ?>"><?php echo $customer_state ?></option>
                                            <option value=''> </option>
                                            <?php
                                        } else {
                                            ?>
                                            <option value=''> </option>
                                            <?php
                                        }
                                        echo "<option value='BLC'>Bad Luck Customer</option>";
                                        echo "<option value='BFC'>Bad Faith Customer</option>";
                                        ?>
                                    </select>
                                    <input value="<?php echo $customer_state ?>" id="old_customer_state" name="old_customer_state" type="hidden" />
                                </td>
                            </tr>
                        <?php } ?>
                        <?php if ($adminstatus == 1 || $adminstatus == 2 || $adminstatus == 3) { ?>
                            <tr>
                                <td valign='top' width="15%">Decline Reason: *</td>
                                <td valign='top' width="35%" colspan="3">
                                    <select name='decline_reason' id='decline_reason'>
                                        <?php
                                        if ($mode == 'edit') {
                                            ?>
                                            <option value="<?php echo $decline_id ?>"><?php echo $decline_reason ?></option>
                                            <?php
                                        } else {
                                            ?>
                                            <option value=''> </option>
                                            <?php
                                        }
                                        //echo "<option value=''>" "</option>"; 
                                        if ($station == '3') {
                                            $sql2 = mysql_query("select id, status from loan_declined_status_codes order by status asc");
                                        } else {
                                            $sql2 = mysql_query("select id, status from loan_declined_status_codes where id != '0' order by status asc");
                                        }
                                        while ($row = mysql_fetch_array($sql2)) {
                                            $id = $row['id'];
                                            $status = $row['status'];
                                            echo "<option value='$id'>" . $status . "</option>";
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                        <?php } ?>
                        <tr bgcolor = #F0F0F6>
                            <td valign='top' width="15%">Asset List?</td>
                            <td valign='top' width="35%">
                                <select name='asset_list' id='asset_list'>
                                    <?php
                                    if ($mode == 'edit') {
                                        ?>
                                        <option value="<?php echo $asset_list ?>"><?php echo $asset_list ?></option>
                                        <?php
                                    } else {
                                        ?>
                                        <option value=''> </option>
                                        <?php
                                    }
                                    echo "<option value='Yes'>Yes</option>";
                                    echo "<option value='No'>No</option>";
                                    ?>
                                </select>
                                <input value="<?php echo $asset_list ?>" id="old_asset_list" name="old_asset_list" type="hidden" />
                            </td>
                            <td valign='top' width="15%">Customer Market</td>
                            <td valign='top' width="35%">
                                <select name='market' id='market'>
                                    <?php
                                    if ($mode == 'edit') {
                                        ?>
                                        <option value="<?php echo $market ?>"><?php echo $market ?></option>
                                        <?php
                                    } else {
                                        ?>
                                        <option value=''> </option>
                                        <?php
                                    }
                                    if ($station == '3') {
                                        $sql2 = mysql_query("select id, market from markets order by market asc");
                                    } else {
                                        $sql2 = mysql_query("select id, market from markets where station = '$station' order by market asc");
                                    }
                                    while ($row = mysql_fetch_array($sql2)) {
                                        $id = $row['id'];
                                        $market = $row['market'];
                                        echo "<option value='$id'>" . $market . "</option>";
                                    }
                                    ?>
                                </select>
                                <input value="<?php echo $market ?>" id="old_market" name="old_market" type="hidden" />
                            </td>
                        </tr>
                        <?php if ($mode == 'edit') { ?>
                            <tr>
                                <td valign="top" colspan="2">
                                    National ID Copy - Front:<br />
                                    <?php if ($resumefileupload == "") { ?>
                                        <p><img class="alignleft" src="images/blank_image.png" width="350px"></p>
                                    <?php } else {
                                        ?>
                                        <p><img class="alignleft" src="<?php echo $resumefileupload ?>" width="150px"></p>
                                    <?php } ?>

                                </td>
                                <td valign="top" colspan="2">
                                    National ID Copy - Back:<br />
                                    <?php if ($resumefileupload_back == "") { ?>
                                        <p><img class="alignleft" src="images/blank_image.png" width="350px"></p>
                                    <?php } else {
                                        ?>
                                        <p><img class="alignleft" src="<?php echo $resumefileupload_back ?>" width="150px"></p>
                                    <?php } ?>

                                </td>
                            </tr>
                        <?php } ?>

                    </table>
                    <?php if ($page_edit_status == 'new') { ?>
                        <?php if ($title == 1 || $title == 2 || $title == 4 || $title == 8 || $title == 3) { ?>
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
                        <?php } ?>
                    <?php } else { ?>
                        <?php if ($station == '3' && ($title != '11' && $title != '12' && $title != '13')) { ?>
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
                        <?php } ?>
                    <?php } ?>
                    <script  type="text/javascript">
                        var frmvalidator = new Validator("frmOrder");
                        frmvalidator.addValidation("mobile_no", "req", "Please enter the customer Primary Mobile Number");
                        frmvalidator.addValidation("first_name", "req", "Please enter the customer First Name");
                        frmvalidator.addValidation("last_name", "req", "Please enter the customer Last Name");
                        frmvalidator.addValidation("national_id", "req", "Please enter National ID");
                        frmvalidator.addValidation("dis_phone", "req", "Please enter the Disbursement Phone Number");
                        frmvalidator.addValidation("home_address", "req", "Please enter the Home Address");
                        frmvalidator.addValidation("loan_officer", "req", "Please enter the Loan Officer");
                        frmvalidator.addValidation("collections_officer", "req", "Please enter the Collections Officer");
                        frmvalidator.addValidation("ref_phone_number", "req", "Please enter Ref 1 Phone Number");
                        frmvalidator.addValidation("ref_landlord_phone", "req", "Please enter Ref 2 Phone Number");
                        frmvalidator.addValidation("gender", "req", "Please select gender");
                        frmvalidator.addValidation("dependants", "req", "Please enter no. of dependants");
                        frmvalidator.addValidation("market", "req", "Please enter the customers market");
                        //frmvalidator.addValidation("tenant_status","req","Please enter the Tenant Status");					
                    </script>
                </form>
            </div>
        </div>
        <br class="clearfix" />
    </div>
    </div>
    <?php
    if (!empty($_POST)) {
        $passportfileupload = $_POST['passportfileupload'];
        $resumefileupload = $_POST['resumefileupload'];
        $resumefileupload_back = $_POST['resumefileupload_back'];
        $mobile_no = $_POST['mobile_no'];
        $old_mobile_no = $_POST['old_mobile_no'];
        $title_name = $_POST['title_name'];
        $old_title = $_POST['old_title'];
        $first_name = $_POST['first_name'];
        $old_first_name = $_POST['old_first_name'];
        $last_name = $_POST['last_name'];
        $old_last_name = $_POST['old_last_name'];
        $national_id = $_POST['national_id'];
        $old_national_id = $_POST['old_national_id'];
        $preffered_language = $_POST['preffered_language'];
        $old_preffered_language = $_POST['old_preffered_language'];
        $nickname = $_POST['nickname'];
        $old_nickname = $_POST['old_nickname'];
        $date_of_birth = $_POST['date_of_birth'];
        $date_of_birth = date('Y-m-d', strtotime(str_replace('-', '/', $date_of_birth)));
        $old_date_of_birth = $_POST['old_date_of_birth'];
        $old_date_of_birth = date('Y-m-d', strtotime(str_replace('-', '/', $old_date_of_birth)));
        $marital = $_POST['marital'];
        $old_marital = $_POST['old_marital'];
        $gender = $_POST['gender'];
        $old_gender = $_POST['old_gender'];
        $dependants = $_POST['dependants'];
        $old_dependants = $_POST['old_dependants'];
        $alt_phone = $_POST['alt_phone'];
        $old_alt_phone = $_POST['old_alt_phone'];
        $dis_phone = $_POST['dis_phone'];
        $old_dis_phone = $_POST['old_dis_phone'];
        $home_address = $_POST['home_address'];
        $home_address = mysql_real_escape_string($home_address);
        $old_home_address = $_POST['old_home_address'];
        $owns = $_POST['owns'];
        $home_occupy = $_POST['home_occupy'];
        $home_occupy = date('Y-m-d', strtotime(str_replace('-', '/', $home_occupy)));
        $old_home_occupy = $_POST['old_home_occupy'];
        $old_home_occupy = date('Y-m-d', strtotime(str_replace('-', '/', $old_home_occupy)));
        $stations = $_POST['stations'];
        $old_stations = $_POST['old_stations'];
        $decline_reason = $_POST['decline_reason'];
        $loan_officer = $_POST['loan_officer'];
        $old_loan_officer = $_POST['old_loan_officer'];
        $collections_officer = $_POST['collections_officer'];
        $old_collections_officer = $_POST['old_collections_officer'];
        $ref_first_name = $_POST['ref_first_name'];
        $ref_last_name = $_POST['ref_last_name'];
        $ref_known_as = $_POST['ref_known_as'];
        $ref_phone_number = $_POST['ref_phone_number'];
        $old_ref_phone_number = $_POST['old_ref_phone_number'];
        $ref_relationship = $_POST['ref_relationship'];
        $ref_landlord_first_name = $_POST['ref_landlord_first_name'];
        $ref_landlord_last_name = $_POST['ref_landlord_last_name'];
        $ref_landlord_relationship = $_POST['ref_landlord_relationship'];
        $ref_landlord_phone = $_POST['ref_landlord_phone'];
        $old_ref_landlord_phone = $_POST['old_ref_landlord_phone'];
        $ref_landlord_known_as = $_POST['ref_landlord_known_as'];
        $marketing_drive = $_POST['marketing_drive'];
        $mobile_friend = $_POST['mobile_friend'];
        $refer_friend = $_POST['refer_friend'];
        $customer_state = $_POST['customer_state'];

        $asset_list = $_POST['asset_list'];
        $market = $_POST['market'];

        $page_status = $_POST['page_status'];
        $users_id = $_POST['users_id'];

        $sql = mysql_query("select national_id from users where national_id = '$national_id'");
        while ($row = mysql_fetch_array($sql)) {
            $exists_national_id = $row['national_id'];
        }
        $exists_national_id = strtolower($exists_national_id);
        $national_id = strtolower($national_id);

        $sql = mysql_query("select mobile_no from users where mobile_no = '$mobile_no'");
        while ($row = mysql_fetch_array($sql)) {
            $exists_mobile_no = $row['mobile_no'];
        }
        $sql = mysql_query("select dis_phone from users where dis_phone = '$dis_phone'");
        while ($row = mysql_fetch_array($sql)) {
            $exists_dis_phone = $row['dis_phone'];
        }
        $sql = mysql_query("select alt_phone from users where alt_phone = '$alt_phone'");
        while ($row = mysql_fetch_array($sql)) {
            $exists_alt_phone = $row['alt_phone'];
        }

        $length_first_name = strlen($first_name);
        $length_last_name = strlen($last_name);
        $length_mobile_no = strlen($mobile_no);
        $length_dis_phone = strlen($dis_phone);
        $length_home_address = strlen($home_address);
        $length_ref_landlord_phone = strlen($ref_landlord_phone);
        $length_ref_phone_number = strlen($ref_phone_number);

        if ($exists_national_id != $national_id && $length_mobile_no == '12' && $length_dis_phone == '12' && $length_home_address > '80' && $length_first_name > 3 && $length_last_name > 3 && $length_ref_landlord_phone == '12' && $length_ref_phone_number == '12') {
            //if($exists_national_id != $national_id && $length_mobile_no == '12' && $length_dis_phone == '12' && $length_home_address > '80' && $exists_mobile_no != $mobile_no && $exists_dis_phone != $dis_phone && $exists_alt_phone != $alt_phone){
            if ($page_status == 'new') {
                $sql3 = "update users set passportfileupload='$passportfileupload', resumefileupload = '$resumefileupload', resumefileupload_back = '$resumefileupload_back', mobile_no = '$mobile_no', title = '$title_name', first_name = '$first_name', last_name = '$last_name', national_id = '$national_id', preffered_language = '$preffered_language', nickname = '$nickname', date_of_birth = '$date_of_birth', marital = '$marital',gender = '$gender' , dependants = '$dependants', alt_phone = '$alt_phone', dis_phone = '$dis_phone', home_address = '$home_address', owns = '$owns', home_occupy = '$home_occupy', stations = '$stations', transactiontime = '$transactiontime', UID = '$userid', status = '$decline_reason', loan_officer = '$loan_officer', collections_officer = '$collections_officer', ref_first_name='$ref_first_name', ref_last_name='$ref_last_name', ref_known_as='$ref_known_as',  ref_phone_number='$ref_phone_number', ref_relationship='$ref_relationship', ref_landlord_first_name='$ref_landlord_first_name', ref_landlord_last_name='$ref_landlord_last_name', ref_landlord_relationship='$ref_landlord_relationship',  ref_landlord_phone='$ref_landlord_phone', ref_landlord_known_as='$ref_landlord_known_as', asset_list = '$asset_list', marketing_drive='$marketing_drive', mobile_friend='$mobile_friend', refer_friend='$refer_friend', customer_state = '$customer_state', market='$market' WHERE id  = '$users_id'";

                if ($old_mobile_no != $mobile_no) {
                    $sql4 = "insert into change_log(UID, table_name, customer_id, variable, old_value, new_value, transactiontime)values('$userid', 'users', '$users_id', 'mobile_no', '$old_mobile_no', '$mobile_no', '$transactiontime')";
                    //echo $sql4."<br />";
                    $result = mysql_query($sql4);
                }
                if ($old_first_name != $first_name) {
                    $sql4 = "insert into change_log(UID, table_name, customer_id, variable, old_value, new_value, transactiontime)values('$userid', 'users', '$users_id', 'first_name', '$old_first_name', '$first_name', '$transactiontime')";
                    //echo $sql4."<br />";
                    $result = mysql_query($sql4);
                }
                if ($old_last_name != $last_name) {
                    $sql4 = "insert into change_log(UID, table_name, customer_id, variable, old_value, new_value, transactiontime)values('$userid', 'users', '$users_id', 'last_name', '$old_last_name', '$last_name', '$transactiontime')";
                    //echo $sql4."<br />";
                    $result = mysql_query($sql4);
                }
                if ($old_national_id != $national_id) {
                    $sql4 = "insert into change_log(UID, table_name, customer_id, variable, old_value, new_value, transactiontime)values('$userid', 'users', '$users_id', 'national_id', '$old_national_id', '$national_id', '$transactiontime')";
                    //echo $sql4."<br />";
                    $result = mysql_query($sql4);
                }
                if ($old_dis_phone != $dis_phone) {
                    $sql4 = "insert into change_log(UID, table_name, customer_id, variable, old_value, new_value, transactiontime)values('$userid', 'users', '$users_id', 'disbursment_phone', '$old_dis_phone', '$dis_phone', '$transactiontime')";
                    //echo $sql4."<br />";
                    $result = mysql_query($sql4);
                }
                if ($old_alt_phone != $alt_phone) {
                    $sql4 = "insert into change_log(UID, table_name, customer_id, variable, old_value, new_value, transactiontime)values('$userid', 'users', '$users_id', 'alt_phone', '$old_alt_phone', '$alt_phone', '$transactiontime')";
                    //echo $sql4."<br />";
                    $result = mysql_query($sql4);
                }
                if ($old_stations != $stations) {
                    $sql4 = "insert into change_log(UID, table_name, customer_id, variable, old_value, new_value, transactiontime)values('$userid', 'users', '$users_id', 'branch', '$old_stations', '$stations', '$transactiontime')";
                    echo $sql4 . "<br />";
                    $result = mysql_query($sql4);
                }
                if ($old_home_address != $home_address) {
                    $sql4 = "insert into change_log(UID, table_name, customer_id, variable, old_value, new_value, transactiontime)values('$userid', 'users', '$users_id', 'home_address', '$old_home_address', '$home_address', '$transactiontime')";
                    //echo $sql4."<br />";
                    $result = mysql_query($sql4);
                }
                if ($old_loan_officer != $loan_officer) {
                    $sql2 = mysql_query("select first_name, last_name, station from user_profiles where id = '$old_loan_officer'");
                    while ($row = mysql_fetch_array($sql2)) {
                        $first_name = $row['first_name'];
                        $last_name = $row['last_name'];
                        $old_loan_officer_name = $first_name . ' ' . $last_name;
                    }
                    $sql2 = mysql_query("select first_name, last_name, station from user_profiles where id = '$loan_officer'");
                    while ($row = mysql_fetch_array($sql2)) {
                        $first_name = $row['first_name'];
                        $last_name = $row['last_name'];
                        $loan_officer_name = $first_name . ' ' . $last_name;
                    }
                    $sql4 = "insert into change_log(UID, table_name, customer_id, variable, old_value, new_value, transactiontime)values('$userid', 'users', '$users_id', 'loan_officer', '$old_loan_officer_name', '$loan_officer_name', '$transactiontime')";
                    //echo $sql4."<br />";
                    $result = mysql_query($sql4);
                }
                if ($old_collections_officer != $collections_officer) {
                    $sql2 = mysql_query("select first_name, last_name, station from user_profiles where id = '$old_collections_officer'");
                    while ($row = mysql_fetch_array($sql2)) {
                        $first_name = $row['first_name'];
                        $last_name = $row['last_name'];
                        $old_collections_officer_name = $first_name . ' ' . $last_name;
                    }
                    $sql2 = mysql_query("select first_name, last_name, station from user_profiles where id = '$collections_officer'");
                    while ($row = mysql_fetch_array($sql2)) {
                        $first_name = $row['first_name'];
                        $last_name = $row['last_name'];
                        $collections_officer_name = $first_name . ' ' . $last_name;
                    }
                    $sql4 = "insert into change_log(UID, table_name, customer_id, variable, old_value, new_value, transactiontime)values('$userid', 'users', '$users_id', 'collections_officer', '$old_collections_officer_name', '$collections_officer_name', '$transactiontime')";
                    //echo $sql4."<br />";
                    $result = mysql_query($sql4);
                }
                if ($old_ref_phone_number != $ref_phone_number) {
                    $sql4 = "insert into change_log(UID, table_name, customer_id, variable, old_value, new_value, transactiontime)values('$userid', 'users', '$users_id', 'ref_1_phone_number', '$old_ref_phone_number', '$ref_phone_number', '$transactiontime')";
                    //echo $sql4."<br />";
                    $result = mysql_query($sql4);
                }
                if ($old_ref_landlord_phone != $ref_landlord_phone) {
                    $sql4 = "insert into change_log(UID, table_name, table_id, variable, old_value, new_value, transactiontime)values('$userid', 'users', '$users_id', 'ref_2_phone_number', '$old_ref_landlord_phone', '$ref_landlord_phone', '$transactiontime')";
                    //echo $sql4."<br />";
                    $result = mysql_query($sql4);
                }
                //echo $sql3."<br />";
                //echo $sql5."<br />";
                $result = mysql_query($sql3);
                $result = mysql_query($sql5);
            } else {
                $sql = mysql_query("select id from user_id");
                while ($row = mysql_fetch_array($sql)) {
                    $user_id_latest = $row['id'];
                }
                $sql3 = "
					INSERT INTO users (id, passportfileupload, resumefileupload, resumefileupload_back, mobile_no, title, first_name, last_name, national_id, preffered_language, nickname, date_of_birth, marital, gender, dependants, alt_phone, dis_phone, home_address, owns, home_occupy, stations, transactiontime, UID, status, loan_officer, collections_officer, ref_first_name, ref_last_name, ref_known_as, ref_phone_number, ref_relationship, asset_list, ref_landlord_first_name, ref_landlord_last_name, ref_landlord_known_as, ref_landlord_relationship, ref_landlord_phone, marketing_drive, mobile_friend, refer_friend, customer_state, market)
					VALUES('$user_id_latest', '$passportfileupload', '$resumefileupload', '$resumefileupload_back', '$mobile_no', '$title_name', '$first_name', '$last_name', '$national_id', '$preffered_language', '$nickname', '$date_of_birth', '$marital', '$gender' , '$dependants', '$alt_phone', '$dis_phone', '$home_address', '$owns', '$home_occupy', '$stations', '$transactiontime', '$userid', '$decline_reason', '$loan_officer', '$collections_officer', '$ref_first_name', '$ref_last_name', '$ref_known_as', '$ref_phone_number', '$ref_relationship', '$asset_list', '$ref_landlord_first_name', '$ref_landlord_last_name', '$ref_landlord_known_as', '$ref_landlord_relationship', '$ref_landlord_phone', '$marketing_drive', '$mobile_friend', '$refer_friend', '$customer_state', '$market')";

                $user_id_latest = $user_id_latest + 1;

                $sql15 = "update user_id set id='$user_id_latest'";
                $result = mysql_query($sql15);

                $sql = mysql_query("select distinct id from users order by id desc limit 1");
                while ($row = mysql_fetch_array($sql)) {
                    $users_id_latest = $row['id'];
                }
                $sql5 = "insert into change_log(UID, table_name, table_id, transactiontime)values('$userid', 'users', '$users_id_latest', '$transactiontime')";
            }
            //echo $sql3."<br />";
            //echo $sql5."<br />";
            $result = mysql_query($sql3);
            $result = mysql_query($sql5);
            $query = "customer_details.php?user_id=$users_id&mode=edit";
            ?>
            <script type="text/javascript">
                <!--
                            document.location = "<?php echo $query ?>";
                //-->
            </script>
            <?php
        } else {
            if ($page_status == 'edit' && $old_national_id == $national_id && $length_mobile_no == '12' && $length_dis_phone == '12' && $length_home_address > '80' && $length_first_name > 3 && $length_last_name > 3 && $length_ref_landlord_phone == '12' && $length_ref_phone_number == '12') {
                $sql3 = "update users set passportfileupload='$passportfileupload', resumefileupload = '$resumefileupload', resumefileupload_back = '$resumefileupload_back', mobile_no = '$mobile_no', title = '$title_name', first_name = '$first_name', last_name = '$last_name', national_id = '$national_id', preffered_language = '$preffered_language', nickname = '$nickname', date_of_birth = '$date_of_birth', marital = '$marital', gender = '$gender' ,dependants = '$dependants', alt_phone = '$alt_phone', dis_phone = '$dis_phone', home_address = '$home_address', owns = '$owns', home_occupy = '$home_occupy', stations = '$stations', transactiontime = '$transactiontime', UID = '$userid', status = '$decline_reason', loan_officer = '$loan_officer', collections_officer = '$collections_officer', ref_first_name='$ref_first_name', ref_last_name='$ref_last_name', ref_known_as='$ref_known_as',  ref_phone_number='$ref_phone_number', ref_relationship='$ref_relationship', asset_list = '$asset_list', ref_landlord_first_name='$ref_landlord_first_name', ref_landlord_last_name='$ref_landlord_last_name', ref_landlord_relationship='$ref_landlord_relationship',  ref_landlord_phone='$ref_landlord_phone', ref_landlord_known_as='$ref_landlord_known_as', marketing_drive = '$marketing_drive', mobile_friend='$mobile_friend', refer_friend='$refer_friend', customer_state = '$customer_state', market='$market' WHERE id  = '$users_id'";

                if ($old_mobile_no != $mobile_no) {
                    $sql4 = "insert into change_log(UID, table_name, customer_id, variable, old_value, new_value, transactiontime)values('$userid', 'users', '$users_id', 'mobile_no', '$old_mobile_no', '$mobile_no', '$transactiontime')";
                    //echo $sql4."<br />";
                    $result = mysql_query($sql4);
                }
                if ($old_first_name != $first_name) {
                    $sql4 = "insert into change_log(UID, table_name, customer_id, variable, old_value, new_value, transactiontime)values('$userid', 'users', '$users_id', 'first_name', '$old_first_name', '$first_name', '$transactiontime')";
                    //echo $sql4."<br />";
                    $result = mysql_query($sql4);
                }
                if ($old_last_name != $last_name) {
                    $sql4 = "insert into change_log(UID, table_name, customer_id, variable, old_value, new_value, transactiontime)values('$userid', 'users', '$users_id', 'last_name', '$old_last_name', '$last_name', '$transactiontime')";
                    //echo $sql4."<br />";
                    $result = mysql_query($sql4);
                }
                if ($old_national_id != $national_id) {
                    $sql4 = "insert into change_log(UID, table_name, customer_id, variable, old_value, new_value, transactiontime)values('$userid', 'users', '$users_id', 'national_id', '$old_national_id', '$national_id', '$transactiontime')";
                    //echo $sql4."<br />";
                    $result = mysql_query($sql4);
                }
                if ($old_dis_phone != $dis_phone) {
                    $sql4 = "insert into change_log(UID, table_name, customer_id, variable, old_value, new_value, transactiontime)values('$userid', 'users', '$users_id', 'disbursment_phone', '$old_dis_phone', '$dis_phone', '$transactiontime')";
                    //echo $sql4."<br />";
                    $result = mysql_query($sql4);
                }
                if ($old_alt_phone != $alt_phone) {
                    $sql4 = "insert into change_log(UID, table_name, customer_id, variable, old_value, new_value, transactiontime)values('$userid', 'users', '$users_id', 'alt_phone', '$old_alt_phone', '$alt_phone', '$transactiontime')";
                    echo $sql4 . "<br />";
                    $result = mysql_query($sql4);
                }
                if ($old_home_address != $home_address) {
                    $sql4 = "insert into change_log(UID, table_name, customer_id, variable, old_value, new_value, transactiontime)values('$userid', 'users', '$users_id', 'home_address', '$old_home_address', '$home_address', '$transactiontime')";
                    //echo $sql4."<br />";
                    $result = mysql_query($sql4);
                }
                if ($old_stations != $stations) {
                    $sql2 = mysql_query("select stations from stations where id = '$stations'");
                    while ($row = mysql_fetch_array($sql2)) {
                        $stations_name = $row['stations'];
                    }
                    $sql2 = mysql_query("select stations from stations where id = '$old_stations'");
                    while ($row = mysql_fetch_array($sql2)) {
                        $old_stations_name = $row['stations'];
                    }
                    $sql4 = "insert into change_log(UID, table_name, customer_id, variable, old_value, new_value, transactiontime)values('$userid', 'users', '$users_id', 'branch', '$old_stations_name', '$stations_name', '$transactiontime')";
                    //echo $sql4."<br />";
                    $result = mysql_query($sql4);
                }
                if ($old_loan_officer != $loan_officer) {
                    $sql2 = mysql_query("select first_name, last_name, station from user_profiles where id = '$old_loan_officer'");
                    while ($row = mysql_fetch_array($sql2)) {
                        $first_name = $row['first_name'];
                        $last_name = $row['last_name'];
                        $old_loan_officer_name = $first_name . ' ' . $last_name;
                    }
                    $sql2 = mysql_query("select first_name, last_name, station from user_profiles where id = '$loan_officer'");
                    while ($row = mysql_fetch_array($sql2)) {
                        $first_name = $row['first_name'];
                        $last_name = $row['last_name'];
                        $loan_officer_name = $first_name . ' ' . $last_name;
                    }
                    $sql4 = "insert into change_log(UID, table_name, customer_id, variable, old_value, new_value, transactiontime)values('$userid', 'users', '$users_id', 'loan_officer', '$old_loan_officer_name', '$loan_officer_name', '$transactiontime')";
                    //echo $sql4."<br />";
                    $result = mysql_query($sql4);
                }
                if ($old_collections_officer != $collections_officer) {
                    $sql2 = mysql_query("select first_name, last_name, station from user_profiles where id = '$old_collections_officer'");
                    while ($row = mysql_fetch_array($sql2)) {
                        $first_name = $row['first_name'];
                        $last_name = $row['last_name'];
                        $old_collections_officer_name = $first_name . ' ' . $last_name;
                    }
                    $sql2 = mysql_query("select first_name, last_name, station from user_profiles where id = '$collections_officer'");
                    while ($row = mysql_fetch_array($sql2)) {
                        $first_name = $row['first_name'];
                        $last_name = $row['last_name'];
                        $collections_officer_name = $first_name . ' ' . $last_name;
                    }
                    $sql4 = "insert into change_log(UID, table_name, customer_id, variable, old_value, new_value, transactiontime)values('$userid', 'users', '$users_id', 'collections_officer', '$old_collections_officer_name', '$collections_officer_name', '$transactiontime')";
                    //echo $sql4."<br />";
                    $result = mysql_query($sql4);
                }
                if ($old_ref_phone_number != $ref_phone_number) {
                    $sql4 = "insert into change_log(UID, table_name, customer_id, variable, old_value, new_value, transactiontime)values('$userid', 'users', '$users_id', 'ref_1_phone_number', '$old_ref_phone_number', '$ref_phone_number', '$transactiontime')";
                    //echo $sql4."<br />";
                    $result = mysql_query($sql4);
                }
                if ($old_ref_landlord_phone != $ref_landlord_phone) {
                    $sql4 = "insert into change_log(UID, table_name, customer_id, variable, old_value, new_value, transactiontime)values('$userid', 'users', '$users_id', 'ref_2_phone_number', '$old_ref_landlord_phone', '$ref_landlord_phone', '$transactiontime')";
                    $result = mysql_query($sql4);
                }

                //echo $sql3."<br />";
                //echo $sql5."<br />";
                $result = mysql_query($sql3);
                $result = mysql_query($sql5);
                $query = "customer_details.php?user_id=$users_id&mode=edit";
                ?>
                <script type="text/javascript">
                <!--
                                document.location = "<?php echo $query ?>";
                //-->
                </script>
                <?php
            } else {
                //if($exists_national_id != $national_id && $length_mobile_no == '12' && $length_dis_phone == '12' && $length_home_address > '80' && $length_first_name > 3 && $length_last_name > 3 && $length_ref_landlord_phone == '12' && $length_ref_phone_number == '12'){
                if ($old_national_id == $national_id && $page_status == 'edit') {
                    $exists_national_id = MD5(exists_national_id);
                    $query = "customer_details.php?status=exists_national_id&exists_national_id=$exists_national_id&user_id=$users_id&mode=edit";
                    ?>
                    <script type="text/javascript">
                    <!--
                                    document.location = "<?php echo $query ?>";
                    //-->
                    </script>
                    <?php
                } else if ($length_mobile_no != '12') {
                    $length_mobile_no = MD5(length_mobile_no);
                    $query = "customer_details.php?status=mobile_no_length&exists_national_id=$length_mobile_no&user_id=$users_id&mode=edit";
                    ?>
                    <script type="text/javascript">
                    <!--
                                    document.location = "<?php echo $query ?>";
                    //-->
                    </script>
                    <?php
                }
            }
        }
    }
}
include_once('includes/footer.php');
?>
