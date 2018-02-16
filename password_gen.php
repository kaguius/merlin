<?php
session_start();
$page_title = "Password Generator";
include_once('includes/login_header.php');
include_once('includes/db_conn.php');
require('password.php');
$transactiontime = date("Y-m-d G:i:s");
?>
<div id="page">
    <div id="content">
        <div class="post">
            <h2><?php echo $page_title ?></h2>		
            <table class="dataTable" width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="128" align="right"><img src="images/unlock.png" alt="" /></td>
                    <td align="left" valign="middle" width="500" style="border: 1px dotted #C5D6FC; padding-left:2px;">
                        <form id="frmLogin" name="frmLogin" method="post" onSubmit='return vdator2.exec();' action="<?php echo $_SERVER['PHP_SELF']; ?>">
                            <table border="0" align="left" cellpadding="5" cellspacing="5">
                                <tr>
                                    <td>Enter the Email Address you registered with:</td>
                                </tr>
                                <tr>
                                    <td valign="top">
                                        <input id="email" name="email" type="text" class="textfield" />                          
                                        <script language="JavaScript" type="text/javascript">if (document.getElementById)
                                                                                        document.getElementById('email').focus();</script> 
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="2" >
                                        <button name="submit" id="button" onclick="return CheckForm();">Submit</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2"><p>The <b>New Password</b> will be sent to the email address entered.</p></td>
                                </tr>
                            </table>
                    </td>
                </tr>
            </table>
            </form>
            <script language="JavaScript">
                var frmvalidator = new Validator("frmLogin");
                frmvalidator.addValidation("email", "req", "Please enter your Email Address");
            </script>
            </table>
            </form>
        </div>
    </div>
    <br class="clearfix" />
</div>
</div>
<?php
if (!empty($_POST)) {
    $email = $_POST['email'];


    $result_tender = mysql_query("SELECT email_address, user_status FROM user_profiles WHERE email_address = '$email'");
    while ($row = mysql_fetch_array($result_tender)) {
        $intcount++;

        $bidderemailid = $row['id'];
        $bidderemail = $row['email_address'];
        $bidderemail = mysql_real_escape_string($bidderemail);
        $user_status = $row['user_status'];
    }


    if ($bidderemail == $email && $user_status == '1') {

        $result_tender = mysql_query("select passwords from passwords order by rand() limit 1");
        while ($row = mysql_fetch_array($result_tender)) {
            $genpassword = $row['passwords'];
        }

        $genhashedpassword = password_hash($genpassword, PASSWORD_DEFAULT);

        $sql = "update user_profiles SET password_main='$genhashedpassword', password_confirm='$genhashedpassword' WHERE email_address = '$email'";
        $result = mysql_query($sql);
        ?>
        <script type="text/javascript">
            <!--
                document.location = "gen_pass.php?pass=<?php echo $genpassword ?>&email=<?php echo $email ?>";
            //-->
        </script>
        <?php
    } else {
        ?>
        <script type="text/javascript">
            <!--
                alert("The Email Address entered does not exist in the database. Please enter the email address you used when you registered in the website.");
            document.location = "password_gen.php";
            //-->
        </script>
        <?php
    }
}
include_once('includes/footer.php');
?>
