<?php
$userid = "";
$adminstatus = "";
session_start();
if (!empty($_SESSION)) {
    $userid = $_SESSION["userid"];
    $adminstatus = $_SESSION["adminstatus"];
    $station = $_SESSION["station"];
    $title = $_SESSION["title"];
}
if ($adminstatus == 3) {
    include_once('includes/header.php');
    ?>
    <script type="text/javascript">
        document.location = "insufficient_permission.php";
    </script>
    <?php
} else {
    include_once('includes/db_conn.php');
    require('password.php');
    $transactiontime = date("Y-m-d G:i:s");
    $page_title = "User Profiles";
    if (!empty($_GET)) {
        $page_title = "User Profile";
        $action = $_GET['action'];
        $user_id = $_GET['id'];
        $status = $_GET['status'];
        if ($action == 'edit') {
            $page_title = "Edit User Profiles";
            $result = mysql_query("select first_name, last_name, station, username, title, freeze, email_address, admin_status, user_status, campaign_id, list_id, collections from user_profiles where id = '$user_id'");
            while ($row = mysql_fetch_array($result)) {
                $first_name = $row['first_name'];
                $last_name = $row['last_name'];
                $username = $row['username'];
                $email_address = $row['email_address'];
                $station_id = $row['station'];
                $title_id = $row['title'];
                $user_status = $row['user_status'];
                $admin_status = $row['admin_status'];
                $campaign_id = $row['campaign_id'];
                $list_id = $row['list_id'];
                $freeze = $row['freeze'];
                $collections = $row['collections'];
                $sql2 = mysql_query("select id, admin_status from admin_status where id = '$admin_status'");
                while ($row = mysql_fetch_array($sql2)) {
                    $admin_id = $row['id'];
                    $admin_status = $row['admin_status'];
                }
                $sql2 = mysql_query("select id, stations from stations where id = '$station_id'");
                while ($row = mysql_fetch_array($sql2)) {
                    $station_id = $row['id'];
                    $stations = $row['stations'];
                }
                $sql2 = mysql_query("select id, title from title where id = '$title_id'");
                while ($row = mysql_fetch_array($sql2)) {
                    $title_id = $row['id'];
                    $title_name = $row['title'];
                }
                $sql2 = mysql_query("select id, concat(first_name, ' ', last_name)collections_officer_name from user_profiles where id = '$collections'");
                while ($row = mysql_fetch_array($sql2)) {
                    $collections_officer_id = $row['id'];
                    $collections_officer_name = $row['collections_officer_name'];
                }
                if ($user_status == '1') {
                    $user_status = 'Active';
                } else {
                    $user_status = 'Disabled';
                }
                if ($freeze == '1') {
                    $freeze_name = 'No';
                } else {
                    $freeze_name = 'Yes';
                }
                $user_station_id = $station_id;
                $user_title_id = $title_id;
                //echo $user_title_id;
            }
        }
    }
    include_once('includes/header.php');
    ?>		
    <div id="page">
        <div id="content">
            <div class="post">
                <h2><font color="#FA9828">Admin Function:</font> <?php echo $page_title ?></h2>
                <form id="frmUserProfiles" name="frmUserProfiles" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <input type="hidden" name="page_status" id="page_status" value="<?php echo $action ?>" />
                    <input type="hidden" name="user_id" id="user_id" value="<?php echo $user_id ?>" />
                    <?php if ($status == 'password_strength') { ?>
                        <table width="60%">
                            <tr bgcolor="red">
                                <td><font color="white" size="2">&nbsp;&nbsp;Yikes! Something's gone wrong.</td>
                            </tr>
                        </table>
                        <font color="red">
                        * The password is not strong enough<br />
                        (It should have a special character, a number, a capital letter and a lower letter)<br />
                        </font>
                    <?php } ?>	
                    <table border="0" width="100%">
                        <tr >
                            <td width="30%">First Name *</td>
                            <td width="70%">
                                <?php if ($station == 3) { ?>
                                    <input title="Enter First Name" value="<?php echo $first_name ?>" id="first_name" name="first_name" type="text" maxlength="100" class="main_input" size="20" />
                                <?php } else { ?>
                                    <input title="Enter First Name" value="<?php echo $first_name ?>" id="first_name" name="first_name" type="text" maxlength="100" readonly class="main_input" size="20" />
                                <?php } ?>
                            </td>

                        </tr>
                        <tr>
                            <td >Last Name *</td>
                            <td>
                                <?php if ($station == 3) { ?>
                                    <input title="Enter Last Name" class="main_input" value="<?php echo $last_name ?>" id="last_name" name="last_name" type="text" maxlength="100" size="20" />
                                <?php } else { ?>
                                    <input title="Enter Last Name" class="main_input" value="<?php echo $last_name ?>" id="last_name" name="last_name" readonly type="text" maxlength="100" size="20" />
                                <?php } ?>

                            </td>
                        </tr>
                        <tr>
                            <td >Username *</td>
                            <td>
                                <?php if ($station == 3) { ?>
                                    <input title="Enter Username" class="main_input" value="<?php echo $username ?>" id="username" name="username" type="text" maxlength="100" size="25" />
                                <?php } else { ?>
                                    <input title="Enter Username" class="main_input" value="<?php echo $username ?>" id="username" name="username" type="text" maxlength="100" readonly size="25" />
                                <?php } ?>
                            </td>

                        </tr>
                        <tr>
                            <td >Email Address *</td>
                            <td>
                                <?php if ($station == 3) { ?>
                                    <input title="Enter the Email Address" class="main_input" value="<?php echo $email_address ?>" id="email_address" name="email_address" type="text" maxlength="100" size="25" />
                                <?php } else { ?>
                                    <input title="Enter the Email Address" class="main_input" value="<?php echo $email_address ?>" id="email_address" name="email_address" readonly type="text" maxlength="100" size="25" />
                                <?php } ?>

                            </td>



                        </tr>
                        <tr>
                            <td width="20%">Station *</td>
                            <td width="55%">
                                <select name='station' id='station'>
                                    <?php
                                    if ($action == 'edit') {
                                        ?>
                                        <option value="<?php echo $station_id ?>"><?php echo $stations ?></option>
                                        <option value=''> </option>	
                                        <?php
                                    } else {
                                        ?>
                                        <option value=''> </option>
                                        <?php
                                    }
                                    //echo "<option value=''>" "</option>"; 										
                                    if ($adminstatus == 1) {
                                        $sql2 = mysql_query("select id, stations from stations where active = '0' order by stations asc");
                                        while ($row = mysql_fetch_array($sql2)) {
                                            $station_id = $row['id'];
                                            $stations = $row['stations'];
                                            echo "<option value='$station_id'>" . $stations . "</option>";
                                        }
                                    }
                                    ?>
                                </select>

                            </td>
                        </tr>
                        <tr>
                            <td width="20%">Title *</td>
                            <td width="55%">
                                <select name='title' id='title'>
                                    <?php
                                    if ($action == 'edit') {
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
                                    if ($adminstatus == 1) {
                                        $sql2 = mysql_query("select id, title from title where active = '0' order by title asc");
                                        while ($row = mysql_fetch_array($sql2)) {
                                            $title_id = $row['id'];
                                            $title_name = $row['title'];
                                            echo "<option value='$title_id'>" . $title_name . "</option>";
                                        }
                                    }
                                    ?>
                                </select>

                            </td>
                        </tr>
                        <?php
                        if ($action == 'edit') {
                            ?>
                            <tr>
                                <td colspan="4"><strong>If you want to edit the password, please enter it here, if not please leave it blank.</strong></td>

                            </tr>
                            <?php
                        }
                        ?>
                        <tr>
                            <td >Profile Password *</td>
                            <td>
                                <input title="Enter Profile Password" class="main_input" value="<?php echo $password_main ?>" id="password_main" name="password_main" type="password" maxlength="100" size="15" />
                            </td>

                        </tr>
                        <tr>
                            <td >Retype Profile Password *</td>
                            <td>
                                <input title="Retype Profile Password" class="main_input" value="<?php echo $password_confirm ?>" id="password_confirm" name="password_confirm" type="password" maxlength="100" size="15" />
                            </td>

                        </tr>
                        <tr>
                            <td width="20%">Admin Status *</td>
                            <td width="55%">
                                <select name='admin_status' id='admin_status'>
                                    <?php
                                    if ($action == 'edit') {
                                        ?>
                                        <option value="<?php echo $admin_id ?>"><?php echo $admin_status ?></option>
                                        <option value=''> </option>	
                                        <?php
                                    } else {
                                        ?>
                                        <option value=''> </option>
                                        <?php
                                    }
                                    //echo "<option value=''>" "</option>"; 										
                                    if ($adminstatus == 1) {
                                        $sql2 = mysql_query("select id, admin_status from admin_status order by admin_status asc");
                                        while ($row = mysql_fetch_array($sql2)) {
                                            $admin_id = $row['id'];
                                            $admin_status = $row['admin_status'];
                                            echo "<option value='$admin_id'>" . $admin_status . "</option>";
                                        }
                                    }
                                    ?>
                                </select>

                            </td>
                        </tr>
                        <?php
                        if ($action == 'edit') {
                            ?>
                            <?php
                        }
                        ?>
                        <?php if ($user_station_id == '4') { ?>
                            <tr>
                                <td >Campaign ID </td>
                                <td>
                                    <input title="Enter Campaign ID" class="main_input" value="<?php echo $campaign_id ?>" id="campaign_id" name="campaign_id" type="text" maxlength="100" size="25" />
                                </td>

                            </tr>
                            <tr>
                                <td >List ID </td>
                                <td>
                                    <input title="Enter List ID" class="main_input" value="<?php echo $list_id ?>" id="list_id" name="list_id" type="text" maxlength="100" size="25" />
                                </td>

                            </tr>
                        <?php } ?>
                        <?php if ($user_title_id == '1') { ?>
                            <tr >
                                <td valign='top' >Freeze Pair *</td>
                                <td valign='top' >
                                    <select name='freeze' id='freeze'>
                                        <?php
                                        if ($action == 'edit') {
                                            ?>
                                            <option value="<?php echo $freeze ?>"><?php echo $freeze_name ?></option>	
                                            <?php
                                        } else {
                                            ?>
                                            <option value=''> </option>
                                            <?php
                                        }
                                        ?>
                                        <option value=''> </option>
                                        <option value='0'>Yes</option>
                                        <option value='1'>No</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td width="20%">Collection Officer Pair *</td>
                                <td width="55%">
                                    <select name='collections_officer' id='collections_officer'>
                                        <?php
                                        if ($action == 'edit') {
                                            ?>
                                            <option value="<?php echo $collections_officer_id ?>"><?php echo $collections_officer_name ?></option>
                                            <option value=''> </option>	
                                            <?php
                                        }
                                        //echo "<option value=''>" "</option>"; 										
                                        $sql2 = mysql_query("select id, first_name, last_name from user_profiles where title = '2' and station = '$user_station_id' and user_status = '1' order by first_name asc");
                                        while ($row = mysql_fetch_array($sql2)) {
                                            $id = $row['id'];
                                            $first_name = $row['first_name'];
                                            $last_name = $row['last_name'];
                                            echo "<option value='$id'>" . $first_name . " " . $last_name . "</option>";
                                        }
                                        ?>
                                    </select>

                                </td>
                            </tr>


                        <?php } ?>
                        <tr>
                            <td>User System Status:</td>
                            <td>
                                <strong><?php echo $user_status ?></strong>			
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
                    <script  type="text/javascript">
                        var frmvalidator = new Validator("frmUserProfiles");
                        frmvalidator.addValidation("first_name", "req", "Please enter the User's First Name");
                        frmvalidator.addValidation("last_name", "req", "Please enter the User's Last Name");
                        frmvalidator.addValidation("email_address", "req", "Please enter User's Email Address");
                        frmvalidator.addValidation("phone_number", "req", "Please enter the user's Phone Number");
                        frmvalidator.addValidation("admin_status", "req", "Please enter the user's System status");
                        frmvalidator.addValidation("station", "req", "Please enter the user's Station");
                        frmvalidator.addValidation("title", "req", "Please enter the user's Title");
                    </script>
                </form>
            </div>
        </div>
        <br class="clearfix" />
    </div>
    </div>
    <?php
    if (!empty($_POST)) {
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $username = $_POST['username'];
        $station = $_POST['station'];
        $title = $_POST['title'];
        $freeze = $_POST['freeze'];
        $email_address = $_POST['email_address'];
        $password_main = $_POST["password_main"];
        $password_confirm = $_POST["password_confirm"];
        $admin_status = $_POST['admin_status'];
        $campaign_id = $_POST['campaign_id'];
        $list_id = $_POST['list_id'];
        $collections_officer = $_POST['collections_officer'];

        $page_status = $_POST['page_status'];
        $user_id = $_POST['user_id'];

        $r1 = '/[A-Z]{1}/';  //Uppercase
        $r2 = '/[a-z]{1}/';  //lowercase
        $r3 = '/[!@#$%^&*()_=+{};:,<.>-]{1}/';  // whatever you mean by 'special char'
        $r4 = '/[0-9]{1}/';  //numbers

        $found = array();

        $count = 0;

        foreach (array($password_main, $password_main, $password_main) as $pass) {

            if (!preg_match_all($r4, $pass, $found)) {
                $count = $count - 1;
            } else {
                $count = $count + 1;
            }

            if (!preg_match_all($r2, $pass, $found)) {
                $count = $count - 1;
            } else {
                $count = $count + 1;
            }

            if (!preg_match_all($r1, $pass, $found)) {
                $count = $count - 1;
            } else {
                $count = $count + 1;
            }

            if (!preg_match_all($r3, $pass, $found)) {
                $count = $count - 1;
            } else {
                $count = $count + 1;
            }
        }

        //echo $count."<br />";

        if ($page_status == 'edit') {
            if ($password_main != "") {

                if ($count < 12) {
                    $password_strength = password_hash(password_strength, PASSWORD_DEFAULT);
                    $query = "user_profiles.php?status=password_strength&password_strength=$password_strength&id=$user_id&action=edit";
                    ?>
                    <script type="text/javascript">
                        <!--
                            document.location = "<?php echo $query ?>";
                        //-->
                    </script>
                    <?php
                } else {
                    $password_confirm = password_hash($password_confirm, PASSWORD_DEFAULT);
                    $password_main = password_hash($password_main, PASSWORD_DEFAULT);
                    if ($title == '1') {
                        $sql3 = "
                                    update user_profiles set first_name='$first_name', last_name='$last_name', username='$username', station='$station', title='$title', email_address = '$email_address', freeze = '$freeze', admin_status ='$admin_status', UID = '$userid', password_main = '$password_main', password_confirm='$password_confirm', campaign_id = '$campaign_id', list_id = '$list_id', collections = '$collections_officer' WHERE ID  = '$user_id'";
                    } else {
                        $sql3 = "
                                    update user_profiles set first_name='$first_name', last_name='$last_name', username='$username', station='$station', title='$title', email_address = '$email_address', admin_status ='$admin_status', UID = '$userid', password_main = '$password_main', password_confirm='$password_confirm', campaign_id = '$campaign_id', list_id = '$list_id', collections = '$collections_officer' WHERE ID  = '$user_id'";
                    }

                    $result = mysql_query($sql3);
                    //echo $sql3;
                }
            } else {
                if ($title == '1') {
                    $sql3 = "
                                update user_profiles set first_name='$first_name', last_name='$last_name', username='$username',  station='$station', title='$title', email_address = '$email_address', freeze = '$freeze', admin_status ='$admin_status', UID = '$userid', campaign_id = '$campaign_id', list_id = '$list_id', collections = '$collections_officer' WHERE ID  = '$user_id'";
                } else {
                    $sql3 = "
                                update user_profiles set first_name='$first_name', last_name='$last_name', username='$username',  station='$station', title='$title', email_address = '$email_address', admin_status ='$admin_status', UID = '$userid', campaign_id = '$campaign_id', list_id = '$list_id', collections = '$collections_officer' WHERE ID  = '$user_id'";
                }
                $result = mysql_query($sql3);
                //echo $sql3;
            }
        } else {
            if ($count < 12) {
                $password_strength = password_hash(password_strength, PASSWORD_DEFAULT);
                $query = "user_profiles.php?status=password_strength&password_strength=$password_strength&id=$user_id&action=edit";
                ?>
                <script type="text/javascript">
                        <!--
                        document.location = "<?php echo $query ?>";
                        //-->
                </script>
                <?php
            } else {
                $password_confirm = password_hash($password_confirm, PASSWORD_DEFAULT);
                $password_main = password_hash($password_main, PASSWORD_DEFAULT);

                if ($title == '1') {
                    $sql = "
                                INSERT INTO user_profiles (first_name, last_name, username, station, title, email_address, password_main, password_confirm, transactiontime, admin_status, user_status, UID, campaign_id, list_id, collections, freeze)
                                VALUES('$first_name','$last_name', '$username', '$station', '$title', '$email_address', '$password_main', '$password_confirm', '$transactiontime', '$admin_status', '1', '$userid', '$campaign_id', '$list_id', '$collections_officer', '$freeze')";
                } else {
                    $sql = "
                                INSERT INTO user_profiles (first_name, last_name, username, station, title, email_address, password_main, password_confirm, transactiontime, admin_status, user_status, UID, campaign_id, list_id, collections)
                                VALUES('$first_name','$last_name', '$username', '$station', '$title', '$email_address', '$password_main', '$password_confirm', '$transactiontime', '$admin_status', '1', '$userid', '$campaign_id', '$list_id', '$collections_officer')";
                }

                //echo $sql;
                $result = mysql_query($sql);
            }
        }
        ?>
        <script type="text/javascript">
                        <!--
                document.location = "user_details.php";
                        //-->
        </script>
        <?php
    }
}
?>
<?php
include_once('includes/footer.php');
?>
