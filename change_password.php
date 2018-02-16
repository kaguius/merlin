<?php
$userid = "";
$adminstatus = "";
$property_manager_id = "";
session_start();
if (!empty($_SESSION)) {
    $userid = $_SESSION["userid"];
    $adminstatus = $_SESSION["adminstatus"];
    $station = $_SESSION["station"];
    $username = $_SESSION["username"];
}
if (!empty($_GET)) {
    $login_status = $_GET['login_status'];
    $status = $_GET['status'];
}
$page_title = "Change Password";
include_once('includes/db_conn.php');
$transactiontime = date("Y-m-d G:i:s");
include_once('includes/login_header.php');
require('password.php');
?>		
<div id="page">
    <div id="content">
        <div class="post">
            <h2><?php echo $page_title ?></h2>
            <p align="center"><img src="images/4gcapital.png" width="250px"></p>
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
            <table class="dataTable" width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="20%" align="right"></td>
                    <td align="left" valign="middle" width="60%" style="border: 1px dotted #C5D6FC; padding-left:2px;">
                        <form id="frmLogin" name="frmLogin" method="post" onSubmit='return vdator2.exec();' action="<?php echo $_SERVER['PHP_SELF']; ?>">
                            <table width="100%" border="0" align="center" cellpadding="5" cellspacing="5">
                                <tr>
                                    <td width="30%">Username :</td>
                                    <td width="70%">
                                        <input id="username" name="username" type="text" class="input" />
                                        <script language="JavaScript" type="text/javascript">if (document.getElementById)
                                                document.getElementById('username').focus();</script> 
                                    </td>
                                </tr>
                                <tr>
                                    <td>Enter Old Password :</td>
                                    <td >
                                        <input name="old_password" id="old_password" type="password" class="main_input" />
                                    </td>
                                </tr>
                                <tr>
                                    <td>Enter New Password :</td>
                                    <td >
                                        <input name="new_password" id="new_password" type="password" class="main_input" />
                                    </td>
                                </tr>
                                <tr>
                                    <td>Confirm Password :</td>
                                    <td >
                                        <input name="confirm_password" id="confirm_password" type="password" class="main_input" />
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" align="center">
                                        <input name="submit" type="submit" id="submit" class="input2" value="Submit" onclick="return CheckForm();" />
                                    </td>							
                                </tr>
                                <?php if ($login_status == 'badlogin') { ?>
                                    <tr>
                                        <td colspan="2" align="left">
                                            <font color="red" size="2px">The username or password don't match the records in the database, or your login has been disabled from the system.</font>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </table>
                    </td>
                    <td width="20%" align="left"></td>
                </tr>
            </table>

            </form>
            </table>
            </form>

            <br />

            <script  type="text/javascript">
                var frmvalidator = new Validator("frmLogin");
                frmvalidator.addValidation("username", "req", "Username cannot be empty");
                frmvalidator.addValidation("old_password", "req", "Please enter the Old password");
                frmvalidator.addValidation("new_password", "req", "Please enter the New  password");
                frmvalidator.addValidation("confirm_password", "req", "Please confirm the New Password");
            </script>
            </form>
        </div>
    </div>
    <br class="clearfix" />
</div>
</div>
<?php
if (!empty($_POST)) {
    $username = $_POST['username'];
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    $r1 = '/[A-Z]{1}/';  //Uppercase
    $r2 = '/[a-z]{1}/';  //lowercase
    $r3 = '/[!@#$%^&*()_=+{};:,<.>-]{1}/';  // whatever you mean by 'special char'
    $r4 = '/[0-9]{1}/';  //numbers

    $found = array();

    $count = 0;

    foreach (array($new_password, $new_password, $new_password) as $pass) {

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

    echo $count;

    $result = mysql_query("SELECT id, username, password_main, admin_status, station, user_status FROM user_profiles WHERE username = '$username'");
    while ($row = mysql_fetch_array($result)) {
        $intcount++;
        $user_id = $row['id'];
        $user_username_text = $row['username'];
        $user_username = mysql_real_escape_string($user_username_text);
        $user_password_text = $row['password_main'];
        $user_password = mysql_real_escape_string($user_password_text);
        $user_status = $row['user_status'];
        $admin_status = $row['admin_status'];
    }


    $hashed_old_password = password_hash($old_password, PASSWORD_DEFAULT);
    $hashed_new_password = password_hash($new_password, PASSWORD_DEFAULT);
    $hashed_confirm_password = password_hash($confirm_password, PASSWORD_DEFAULT);

    //$hashed_old_password = MD5($old_password);
    //$hashed_new_password = MD5($new_password);
    //$hashed_confirm_password = MD5($confirm_password);

    $url = 'https://ip4.me/';
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);

        $data = curl_exec($ch);
        curl_close($ch);
        $file = $data;
// Trim IP based on HTML formatting
        $pos = strpos($file, '+3') + 3;
        $ip = substr($file, $pos, strlen($file));
// Trim IP based on HTML formatting
        $pos = strpos($ip, '</');
        $ip = substr($ip, 0, $pos);
    

    if ($count < 12) {
        $password_strength = password_hash(password_strength, PASSWORD_DEFAULT);
        $query = "change_password.php?status=password_strength&password_strength=$password_strength&id=$user_id&action=edit";
        ?>
        <script type="text/javascript">
            <!--
                document.location = "<?php echo $query ?>";
            //-->
        </script>
        <?php
    } else if (password_verify($old_password, $user_password) && ($user_username == $username)) {
        $browsertype = $_SERVER['https_USER_AGENT'];

        $count_success_pass = 0;
        $result5 = mysql_query("SELECT username, password FROM password_log WHERE username = '$username'");
        while ($row = mysql_fetch_array($result5)) {
            $password = $row['password'];
            if (password_verify($new_password, $password)) {
                $count_success_pass = $count_success_pass + 1;
            }
        }

        if ($count_success_pass == 0) {
            $sql3 = "update user_profiles set password_main = '$hashed_new_password', password_confirm='$hashed_new_password', force_reset_pass = 0 WHERE username  = '$username'";
            $result = mysql_query($sql3);

            $sql4 = "INSERT INTO password_log (username, password) VALUES ('$username', '$hashed_new_password')";
            $result1 = mysql_query($sql4);
            //echo $sql3;

            $_SESSION["username"] = $username;
            $_SESSION["userid"] = $user_id;
            $_SESSION["adminstatus"] = $admin_status;
            ?>

            <script type="text/javascript">
            <!--
                    document.location = "index.php";
            //-->
            </script>

            <?php
        } else {
            $sql = "INSERT INTO users_failed_logs (username, password, ipaddress, tranasctiontime) 
				VALUES ('$username','$password', '$ip', '$transactiontime')";
            //echo $sql;
            $result = mysql_query($sql);

            $badlogin = password_hash(badlogin, PASSWORD_DEFAULT);
            $query = "change_password.php?login_status=usedpass&status=$badlogin";
            ?>
            <script type="text/javascript">
            <!--
                    /*alert("Either the Email Address or the Password do not match the records in the database or you have been disabled from the system, please contact the system admin at www.e-kodi.com/contact.php");*/
            document.location = "<?php echo $query ?>";
            //-->
            </script>
            <?php
        }
    } else {
        $sql = "INSERT INTO users_failed_logs (username, password, ipaddress, tranasctiontime) 
            VALUES ('$username','$password', '$ip', '$transactiontime')";
        //echo $sql;
        $result = mysql_query($sql);

        $badlogin = password_hash(badlogin, PASSWORD_DEFAULT);
        $query = "change_password.php?login_status=badlogin&status=$badlogin";
        ?>
        <script type="text/javascript">
        <!--
                /*alert("Either the Email Address or the Password do not match the records in the database or you have been disabled from the system, please contact the system admin at www.e-kodi.com/contact.php");*/
        document.location = "<?php echo $query ?>";
        //-->
        </script>
        <?php
    }
}
?>
<?php
include_once('includes/footer.php');
?>
