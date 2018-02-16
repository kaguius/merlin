<?php
$userid = "";
$adminstatus = 4;
$property_manager_id = "";
session_start();
if (!empty($_SESSION)) {
    $userid = $_SESSION["userid"];
    $adminstatus = $_SESSION["adminstatus"];
    $station = $_SESSION["station"];
    $username = $_SESSION["username"];
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
        $loan_rep_id = $_GET['loan_rep_id'];
        $mode = $_GET['mode'];
        $user_id = $_GET['user_id'];
        $id_status = $_GET['status'];
        //$loan_rep_code = $_GET['loan_code'];
        //$settle = $_GET['settle'];
    }
    include_once('includes/db_conn.php');
    include_once('classes/clear_loan.php');
    include_once('classes/mobile_survey.php');

    $sql = mysql_query("select first_name, last_name, mobile_no, dis_phone, affordability, loan_officer, collections_officer, stations from users where id = '$user_id'");
    while ($row = mysql_fetch_array($sql)) {
        $first_name = $row['first_name'];
        $last_name = $row['last_name'];
        $first_name = ucwords(strtolower($first_name));
        $last_name = ucwords(strtolower($last_name));
        $customer_name = $first_name . ' ' . $last_name;
        $mobile_no = $row['mobile_no'];
        $dis_phone = $row['dis_phone'];
        $affordability = $row['affordability'];
        $loan_officer_id = $row['loan_officer'];
        $collections_officer_id = $row['collections_officer'];
        $customer_station = $row['stations'];
    }

    $sql3 = mysql_query("select loan_amount, loan_date, loan_total_interest, loan_late_interest, loan_code, loan_due_date, late_status, initiation_fee, loan_status from loan_application where customer_id = '$user_id' and loan_status != '12' and loan_status != '11' and loan_status != '14' order by loan_date desc limit 1");
    while ($row = mysql_fetch_array($sql3)) {
        $loan_rep_code = $row['loan_code'];
    }
    $transactiontime = date("Y-m-d G:i:s");
    if ($mode == 'edit') {
        $sql = mysql_query("select loan_rep_id, loan_rep_date, loan_rep_mobile, loan_rep_amount, loan_rep_mpesa_code, loan_rep_code, comment, current_collector from loan_repayments where loan_rep_id = '$loan_rep_id'");
        while ($row = mysql_fetch_array($sql)) {
            $loan_rep_id = $row['loan_rep_id'];
            $loan_rep_date = $row['loan_rep_date'];
            $loan_rep_mobile = $row['loan_rep_mobile'];
            $loan_rep_agent_mobile = $row['loan_rep_agent_mobile'];
            $loan_rep_amount = $row['loan_rep_amount'];
            $loan_rep_status = $row['loan_rep_status'];
            $loan_rep_acc_id = $row['loan_rep_acc_id'];
            $loan_rep_mpesa_code = $row['loan_rep_mpesa_code'];
            $loan_rep_code = $row['loan_rep_code'];
            $loan_rep_code = ucwords(strtolower($loan_rep_code));
            $comment = $row['comment'];
            $current_collector = $row['current_collector'];
            $sql2 = mysql_query("select id, first_name, last_name from user_profiles where id = '$current_collector'");
            while ($row = mysql_fetch_array($sql2)) {
                $agent_id = $row['id'];
                $agent_first_name = $row['first_name'];
                $agent_last_name = $row['last_name'];
                $current_collector_name = $agent_first_name . " " . $agent_last_name;
            }
        }
        $page_title = "Update Loan Repayment Detail(s)";
    } else {
        $page_title = "Create new Loan Repayment Detail(s)";
    }
    include_once('includes/header.php');
    ?>		
    <div id="page">
        <div id="content">
            <div class="post">
                <h2><font color="#000A8B"><?php echo $page_title; ?></font></h2>
                <h3>Customer Name: <?php echo $customer_name ?>, Disbursement #: <?php echo $dis_phone ?></h3>
                <br />
                <?php if ($id_status == 'value_above_limit') { ?>
                    <table width="60%">
                        <tr bgcolor="red">
                            <td><font color="white" size="2">&nbsp;&nbsp;Yikes! Something's gone wrong.</td>
                        </tr>
                    </table>
                    <font color="red">
                    * Either the value entered is either above the limit or below (0)<br />
                    * Either the phone number length is not correct<br />
                    * Either the Mobile Money Code is blank or already exists in the system<br />
                    </font>
                <?php } ?>	
                <?php if ($station == '3') { ?>
                    <p align="right"><img src="images/delete.png"> - <a href="payment_reversal_details.php?loan_rep_id=<?php echo $loan_rep_id ?>">Reverse this Payment</a></p>
                <?php } ?>
                <form id="frmOrder" name="frmOrder" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <table border="0" width="100%" cellspacing="2" cellpadding="2">	
                        <input type="hidden" name="loan_rep_id" id="loan_rep_id" value="<?php echo $loan_rep_id ?>" />
                        <input type="hidden" name="user_id" id="user_id" value="<?php echo $user_id ?>" />		
                        <input type="hidden" name="page_status" id="page_status" value="<?php echo $mode ?>" />
                        <input type="hidden" name="settle" id="settle" value="<?php echo $settle ?>" />
                        <tr>
                            <td valign='top' width="15%">Repayment Date *</td>
                            <td valign='top' width="35%" colspan="3">
                                <input title="Enter Repyment Mobile" value="<?php echo $loan_rep_date ?>" id="loan_rep_date" name="loan_rep_date" type="text" maxlength="100" readonly class="main_input" size="35" />
                            </td>
                        </tr>
                        <tr bgcolor = #F0F0F6>
                            <td valign='top' width="15%">Repayment Mobile *</td>
                            <td valign='top' width="35%">
                                <?php if ($mode == 'edit') { ?>
                                    <input title="Enter Repyment Mobile" value="<?php echo $loan_rep_mobile ?>" id="loan_rep_mobile" name="loan_rep_mobile" type="text" maxlength="100" class="main_input" size="35" />
                                    <input value="<?php echo $loan_rep_agent_mobile ?>" id="old_loan_rep_agent_mobile" name="old_loan_rep_agent_mobile" type="hidden" />
                                <?php } else { ?>
                                    <input title="Enter Repyment Mobile" value="254" id="loan_rep_mobile" name="loan_rep_mobile" type="text" maxlength="100" class="main_input" size="35" />
                                    <input value="<?php echo $loan_rep_agent_mobile ?>" id="old_loan_rep_agent_mobile" name="old_loan_rep_agent_mobile" type="hidden" />
                                <?php } ?>

                            </td>
                            <td valign="top" width="15%">Repayment Amount *</td>
                            <td valign="top" width="35%">
                                <?php if ($station == '3') { ?>
                                    <input title="Enter Repayment Amount" value="<?php echo $loan_rep_amount ?>" id="loan_rep_amount" name="loan_rep_amount" type="text" maxlength="10" class="main_input" size="35" />
                                    <input value="<?php echo $loan_rep_amount ?>" id="old_loan_rep_amount" name="old_loan_rep_amount" type="hidden" />
                                <?php } else { ?>
                                    <input title="Enter Repayment Amount" value="<?php echo $loan_rep_amount ?>" id="loan_rep_amount" name="loan_rep_amount" type="text" maxlength="5" class="main_input" size="35" />
                                    <input value="<?php echo $loan_rep_amount ?>" id="old_loan_rep_amount" name="old_loan_rep_amount" type="hidden" />
                                <?php } ?>



                            </td>
                        </tr>
                        <tr >
                            <td valign='top' >Loan Ref Number *</td>
                            <td valign='top'>
                                <input title="Enter Gender" value="<?php echo $loan_rep_code ?>" id="loan_rep_code" name="loan_rep_code" type="text" maxlength="100" class="main_input" size="35" />
                                <input value="<?php echo $loan_rep_code ?>" id="old_loan_rep_code" name="old_loan_rep_code" type="hidden" />
                            </td>
                            <td valign='top' >Mobile Money Code *</td>
                            <td valign='top' colspan="3">
                                <input title="Enter Gender" value="<?php echo $loan_rep_mpesa_code ?>" id="loan_rep_mpesa_code" name="loan_rep_mpesa_code" type="text" maxlength="100" class="main_input" size="35" />
                                <input value="<?php echo $loan_rep_mpesa_code ?>" id="old_loan_rep_mpesa_code" name="old_loan_rep_mpesa_code" type="hidden" />
                            </td>
                        </tr >
                        <tr bgcolor = #F0F0F6>
                            <td valign='top' >Comment *</td>
                            <td valign='top' colspan="3">
                                <textarea title="Enter Comment" name="comment" id="comment" cols="45" rows="5" class="textfield"><?php echo $comment ?></textarea>
                            </td>
                        </tr>
                        <?php if ($station == '3') { ?>
                            <tr bgcolor = #F0F0F6>
                                <td valign='top' >Current Collector *</td>
                                <td valign='top' colspan="3">
                                    <select name='agent' id='agent'>
                                        <?php
                                        if ($mode == 'edit') {
                                            ?>
                                            <option value="<?php echo $agent_id ?>"><?php echo $current_collector_name ?></option>
                                            <option value=''> </option>	
                                            <?php
                                        } else {
                                            ?>
                                            <option value=''> </option>
                                            <?php
                                        }
                                        $sql2 = mysql_query("select id from `user_profiles` where title IN ( '10','7','9' ) and `user_status` != '0'");
                                        while ($row = mysql_fetch_array($sql2)) {
                                            $id = $row['id'];
                                            $sql3 = mysql_query("select first_name, last_name from user_profiles where id = '$id'");
                                            while ($row = mysql_fetch_array($sql3)) {
                                                $first_name = $row['first_name'];
                                                $last_name = $row['last_name'];
                                                $staff_name = $first_name . " " . $last_name;
                                            }
                                            echo "<option value='$id'>" . $staff_name . "</option>";
                                            $staff_name = "";
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>	
                        <?php } ?>
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
                        var frmvalidator = new Validator("frmOrder");
                        frmvalidator.addValidation("comment", "req", "Please enter the Comment");
                        frmvalidator.addValidation("loan_rep_amount", "req", "Please enter the Repayment Amount");
                        frmvalidator.addValidation("loan_rep_mpesa_code", "req", "Please enter the Mobile Money Code");
                        frmvalidator.addValidation("loan_rep_date", "req", "Please enter the Repayment Date");
                        frmvalidator.addValidation("loan_rep_code", "req", "Please enter the Loan Ref Number");
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

        $loan_rep_date = $_POST['loan_rep_date'];
        $loan_rep_date = date('Y-m-d', strtotime(str_replace('-', '/', $loan_rep_date)));
        $loan_rep_mobile = $_POST['loan_rep_mobile'];
        $loan_rep_mobile = trim($loan_rep_mobile);
        $old_loan_rep_mobile = $_POST['old_loan_rep_mobile'];
        $loan_rep_amount = $_POST['loan_rep_amount'];
        $loan_rep_amount = trim($loan_rep_amount);
        $old_loan_rep_amount = $_POST['old_loan_rep_amount'];
        $loan_rep_acc_id = $_POST['loan_rep_acc_id'];
        $old_loan_rep_acc_id = $_POST['old_loan_rep_acc_id'];
        $loan_rep_code = $_POST['loan_rep_code'];
        $loan_rep_code = trim($loan_rep_code);
        $old_loan_rep_code = $_POST['old_loan_rep_code'];
        $loan_rep_mpesa_code = $_POST['loan_rep_mpesa_code'];
        $loan_rep_mpesa_code = trim($loan_rep_mpesa_code);
        $old_loan_rep_mpesa_code = $_POST['old_loan_rep_mpesa_code'];
        $comment = $_POST['comment'];
        $settle = $_POST['settle'];
        $agent = $_POST['agent'];

        $page_status = $_POST['page_status'];
        $loan_rep_id = $_POST['loan_rep_id'];
        $user_id = $_POST['user_id'];


        if ($page_status == 'edit') {
            if ($loan_rep_amount < 70000) {
                $sql3 = "
		update loan_repayments set loan_rep_date='$loan_rep_date', loan_rep_mobile='$loan_rep_mobile', loan_rep_amount='$loan_rep_amount', loan_rep_code ='$loan_rep_code', loan_rep_mpesa_code='$loan_rep_mpesa_code', comment='$comment', UID='$userid', current_collector = '$agent' WHERE loan_rep_id  = '$loan_rep_id'";

                if ($old_loan_rep_date != $loan_rep_date) {
                    $sql4 = "insert into change_log(UID, table_name, customer_id, variable, old_value, new_value, transactiontime, comment)values('$userid', 'loan_repayments', '$user_id', 'repayment_date', '$old_loan_rep_date', '$loan_rep_date', '$transactiontime', '$comment')";
                }
                if ($old_loan_rep_mobile != $loan_rep_mobile) {
                    $sql4 = "insert into change_log(UID, table_name, customer_id, variable, old_value, new_value, transactiontime, comment)values('$userid', 'loan_repayments', '$user_id', 'repayment_mobile', '$old_loan_rep_mobile', '$loan_rep_mobile', '$transactiontime', '$comment')";
                }
                if ($old_loan_rep_amount != $loan_rep_amount) {
                    $sql4 = "insert into change_log(UID, table_name, customer_id, variable, old_value, new_value, transactiontime, comment)values('$userid', 'loan_repayments', '$user_id', 'amount', '$old_loan_rep_amount', '$loan_rep_amount', '$transactiontime', '$comment')";
                }
                if ($old_loan_rep_code != $loan_rep_code) {
                    $sql4 = "insert into change_log(UID, table_name, customer_id, variable, old_value, new_value, transactiontime, comment)values('$userid', 'loan_repayments', '$user_id', 'loan_code', '$old_loan_rep_code', '$loan_rep_code', '$transactiontime', '$comment')";
                }
                if ($old_loan_rep_mpesa_code != $loan_rep_mpesa_code) {
                    $sql4 = "insert into change_log(UID, table_name, customer_id, variable, old_value, new_value, transactiontime, comment)values('$userid', 'loan_repayments', '$user_id', 'mpesa_code', '$old_loan_rep_mpesa_code', '$loan_rep_mpesa_code', '$transactiontime', '$comment')";
                }
            } else {
                $value_above_limit = MD5(value_above_limit);
                $query = "repayment_details.php?status=value_above_limit&value_above_limit=$value_above_limit&user_id=$user_id&loan_rep_id=$loan_rep_id&mode=edit";
                ?>
                <script type="text/javascript">
                    <!--
                        /*alert("Either the Email Address or the Password do not match the records in the database or you have been disabled from the system, please contact the system admin at www.e-kodi.com/contact.php");*/
                    document.location = "<?php echo $query ?>";
                    //-->
                </script>
                <?php
            }


            //echo $sql3."<br />";
            //echo $sql4."<br />";
            $result = mysql_query($sql3);
            $result = mysql_query($sql4);
        } else {
            $sql = mysql_query("select customer_id, current_collector, customer_station, loan_status from loan_application where loan_code = '$trans_party_details'");
            while ($row = mysql_fetch_array($sql)) {
                $customer_id = $row['customer_id'];
                $customer_station = $row['customer_station'];
                $loan_status = $row['loan_status'];
                $current_collector = $row['current_collector'];
            }
            $sql = mysql_query("select distinct loan_rep_mpesa_code from loan_repayments where loan_rep_mpesa_code = '$loan_rep_mpesa_code'");
            while ($row = mysql_fetch_array($sql)) {
                $exists_loan_mpesa_code = $row['loan_rep_mpesa_code'];
            }

            //echo "Exists: ".$exists_loan_mpesa_code."<br />";
            //echo "Entered: ".$loan_rep_mpesa_code."<br />";

            if ($loan_rep_amount <= 70000 && $loan_rep_mobile != '254' && $loan_rep_mpesa_code != "" && $loan_rep_amount > 0 && $loan_rep_mpesa_code != $exists_loan_mpesa_code) {
                //if($loan_rep_amount <= 70000 && $loan_rep_mobile != '254' && $loan_rep_mpesa_code != ""){
                $sql3 = "
				INSERT INTO loan_repayments (loan_rep_date, customer_id, customer_station, loan_rep_mobile, loan_rep_amount, loan_rep_mpesa_code, loan_rep_code, UID, loan_status, current_collector)
				VALUES('$loan_rep_date', '$user_id', '$station', '$loan_rep_mobile', '$loan_rep_amount', '$loan_rep_mpesa_code', '$loan_rep_code', '$userid', '$loan_status', '$current_collector');";

                $sql = mysql_query("select distinct loan_rep_id from loan_repayments order by loan_rep_id desc limit 1");
                while ($row = mysql_fetch_array($sql)) {
                    $loan_rep_id_latest = $row['loan_rep_id'];
                }

                $sql4 = "insert into change_log(UID, table_name, customer_id, variable, old_value, new_value, transactiontime, comment)values('$userid', 'loan_repayments', '$user_id', 'loan_repayments', '0', '$loan_rep_amount', '$transactiontime', '$comment')";

                //echo $sql3."<br />";
                //echo $sql4."<br />";
                $result = mysql_query($sql3);
                $result = mysql_query($sql4);
            } else {
                $value_above_limit = MD5(value_above_limit);
                $query = "repayment_details.php?status=value_above_limit&value_above_limit=$value_above_limit&user_id=$user_id";
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


        $sql3 = mysql_query("select loan_amount, loan_date, loan_total_interest, loan_late_interest, loan_code, loan_due_date, late_status, initiation_fee, loan_extension from loan_application where customer_id = '$user_id' order by loan_date desc limit 1");
        while ($row = mysql_fetch_array($sql3)) {
            $current_loan = $row['loan_amount'];
            $loan_date = $row['loan_date'];
            $latest_loan = $row['loan_total_interest'];
            $latest_loan_code = $row['loan_code'];
            $loan_due_date = $row['loan_due_date'];
            $initiation_fee = $row['initiation_fee'];
            $loan_extension = $row['loan_extension'];
        }

        //$loan_due_date = date('d/m/y', strtotime(str_replace('-', '/', $loan_due_date)));

        $sql4 = mysql_query("select sum(loan_rep_amount)repayments from loan_repayments where customer_id = '$user_id' and loan_rep_code = '$latest_loan_code' group by loan_rep_code");
        while ($row = mysql_fetch_array($sql4)) {
            $repayments = $row['repayments'];
            if ($repayments == "") {
                $repayments = 0;
            }
        }

        $balance = $latest_loan - $repayments;
        $loan_balance = $balance * -1;

        $sql3 = mysql_query("select loan_rep_date from loan_repayments where customer_id = '$user_id' and loan_rep_code = '$latest_loan_code' order by loan_rep_date desc limit 1");
        while ($row = mysql_fetch_array($sql3)) {
            $latest_loan_rep_date = $row['loan_rep_date'];
        }

        $date1 = strtotime($loan_date);
        $date2 = strtotime($latest_loan_rep_date);
        $dateDiff = $date2 - $date1;
        $due_days = floor($dateDiff / (60 * 60 * 24));

        if ($due_days < 14) {
            $due_days = 14;
        } else if ($due_days > 30) {
            $due_days = 14;
        } else {
            $due_days = $due_days;
        }

        $due_loan_interest = $current_loan * ($due_days / 100);
        $due_loan_total_interest = $due_loan_interest + $current_loan + $initiation_fee + $loan_extension;

        //echo $latest_loan_rep_date."<br />";
        //echo $due_days."<br />";
        //echo $due_loan_total_interest."<br />";
        //echo $loan_rep_amount."<br />";
        //echo $latest_loan."<br />";

//        $ln_rep = new clear_loan();
//        $ln_bal = $ln_rep->get_loan_details($loan_rep_code);
//        if ($ln_bal <= 0) {
//            $data = getSurvey($loan_rep_mobile, 'safaricom', '4GLOAN', $customer_id);
//        }
        
        include_once('classes/mobile_survey.php');

        if ($due_loan_total_interest == $repayments) {

            //if($due_loan_total_interest == $loan_rep_amount){
            $early_settlement = $latest_loan - $repayments;
            $latest_loan = $latest_loan - $early_settlement;
            $sql5 = "update loan_application set loan_status='13' WHERE loan_code  = '$loan_rep_code'";
            $result = mysql_query($sql5);
            
            $data = getSurvey($loan_rep_mobile, 'safaricom', '4GLOAN', $customer_id);

            if ($early_settlement > 0) {
                $early_settlement = -$early_settlement;
                $sql6 = "update loan_application set early_settlement='$early_settlement', loan_total_interest = '$latest_loan' WHERE loan_code  = '$loan_rep_code'";
                $result = mysql_query($sql6);
            }
        } else if ($repayments >= $latest_loan) {
            //else if($loan_rep_amount >= $latest_loan){
            $early_settlement_surplus = $repayments - $latest_loan;
            $latest_loan = $latest_loan - $early_settlement_surplus;
            $sql5 = "update loan_application set loan_status='13' WHERE loan_code  = '$loan_rep_code'";
            $result = mysql_query($sql5);
            
            $data = getSurvey($loan_rep_mobile, 'safaricom', '4GLOAN', $customer_id);
            if ($early_settlement_surplus > 0) {
                $early_settlement_surplus_figure = -$early_settlement_surplus;
                $sql15 = "update overpayments_schedule set loan_balance = '$early_settlement_surplus' WHERE loan_code  = '$loan_rep_code'";
                $result = mysql_query($sql15);
            }
        }
        $query = "customer_loans.php?user_id=$user_id";
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
include_once('includes/footer.php');
?>
