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
        document.location = "login.php";
    </script>
    <?php
} else {
    if (!empty($_GET)) {
        $mode = $_GET['mode'];
        $id = $_GET['id'];
    }
    include_once('includes/db_conn.php');
    $transactiontime = date("Y-m-d G:i:s");
    if ($mode == 'edit') {
        $sql = mysql_query("select id, receipt, date, details, status, withdrawn, paid_in, balance, balance_confirmed, trans_type, other_party_info, trans_party_details from suspence_accounts where id = '$id'");
        while ($row = mysql_fetch_array($sql)) {
            $id = $row['id'];
            $receipt = $row['receipt'];
            $date = $row['date'];
            $details = $row['details'];
            $status = $row['status'];
            $paid_in = $row['paid_in'];
            $balance = $row['balance'];
            $withdrawn = $row['withdrawn'];
            $balance_confirmed = $row['balance_confirmed'];
            $trans_type = $row['trans_type'];
            $other_party_info = $row['other_party_info'];
            $trans_party_details = $row['trans_party_details'];
        }
        $page_title = "Update Suspense Transaction Detail(s)";
    } else {
        $page_title = "Create new Customer Detail(s)";
    }

    $mobile = substr($other_party_info, 0, 12);
    $mobile_old = substr($other_party_info, 0, 1);

    //$length_mobile = strlen($mobile);
    if ($mobile_old == '0') {
        $mobile = substr($other_party_info, 1, 10);
        $mobile = "254" . $mobile;
    }
    //else{
    //	$mobile = substr($other_party_info, 1, 10);
    //	$mobile = "254".$mobile;
    //}
    include_once('includes/header.php');
    ?>		
    <div id="page">
        <div id="content">
            <div class="post">
                <h2><font color="#000A8B"><?php echo $page_title; ?></font></h2>
                <br />
                <?php if ($station == '3') { ?>
                    <p align="right"><img src="images/delete.png"> - <a href="payment_reversal_details.php?loan_rep_sus_id=<?php echo $id ?>">Reverse this Payment</a></p>
                <?php } ?>
                <form id="frmOrder" name="frmOrder" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <table border="0" width="100%" cellspacing="2" cellpadding="2">	
                        <input type="hidden" name="id" id="id" value="<?php echo $id ?>" />		
                        <input type="hidden" name="page_status" id="page_status" value="<?php echo $mode ?>" />
                        <tr bgcolor = #F0F0F6>
                            <td valign="top" width="15%">Receipt *</td>
                            <td valign="top" width="35%">
                                <input title="Enter Receipt" value="<?php echo $receipt ?>" id="receipt" name="receipt" type="text" maxlength="100" class="main_input" readonly size="35" />
                            </td>
                            <td valign="top" width="15%">Date *</td>
                            <td valign="top" width="35%">
                                <input title="Enter Date" value="<?php echo $date ?>" id="date" name="date" type="text" maxlength="100" class="main_input" readonly size="35" />
                            </td>
                        </tr>
                        <tr>
                            <td valign='top' width="15%">Details *</td>
                            <td valign='top' width="35%">
                                <textarea title="Enter Details" id="details" rows="4" cols="50" name="details" type="text" readonly><?php echo $details ?></textarea>
                            </td>

                        </tr>
                        <tr bgcolor = #F0F0F6>
                            <td valign='top' >Withdrawn *</td>
                            <td valign='top'>
                                <input title="Enter Gender" value="<?php echo $withdrawn ?>" id="withdrawn" name="withdrawn" type="text" maxlength="100" class="main_input" readonly size="35" />
                            </td>
                            <td valign='top' >Paid In *</td>
                            <td valign='top'>
                                <input title="Enter Gender" value="<?php echo $paid_in ?>" id="paid_in" name="paid_in" type="text" maxlength="100" class="main_input" readonly size="35" />
                            </td>
                        </tr>
                        <tr>
                            <td valign='top' width="15%">Balance *</td>
                            <td valign='top' width="35%">
                                <input title="Enter Details" value="<?php echo $balance ?>" id="balance" name="balance" type="text" maxlength="100" class="main_input" readonly size="35" />
                            </td>
                            <td valign="top" width="15%">Balance Confirmed *</td>
                            <td valign="top" width="35%">
                                <input title="Enter Transaction ID" value="<?php echo $balance_confirmed ?>" id="balance_confirmed" name="balance_confirmed" type="text" maxlength="100" class="main_input" readonly size="35" />
                            </td>
                        </tr>
                        <tr bgcolor = #F0F0F6>
                            <td valign='top' >Trans Type *</td>
                            <td valign='top'>
                                <input title="Enter Gender" value="<?php echo $trans_type ?>" id="trans_type" name="trans_type" type="text" maxlength="100" class="main_input" readonly size="35" />
                            </td>
                            <td valign='top' >Other Party Info *</td>
                            <td valign='top'>
                                <input title="Enter Gender" value="<?php echo $other_party_info ?>" id="other_party_info" name="other_party_info" type="text" maxlength="100" readonly class="main_input" size="35" />
                            </td>
                        </tr>
                        <tr>
                            <td valign='top' width="15%">Mobile *</td>
                            <td valign='top' width="35%">
                                <input title="Enter Details" value="<?php echo $mobile ?>" id="mobile" name="mobile" type="text" maxlength="100" class="main_input" readonly size="35" />
                            </td>
                            <td valign="top" width="15%">Status *</td>
                            <td valign="top" width="35%" colspan="3">
                                <input title="Enter Transaction ID" value="<?php echo $status ?>" id="status" name="status" type="text" maxlength="100" class="main_input" readonly size="35" />
                            </td>
                        </tr>
                        <?php //if($userid == '13'){  ?>
                        <?php if ($userid == '82' || $userid == '171' || $userid == '168' || $userid == '253') { ?>
                            <tr>
                                <td valign='top' width="15%">Loan Code *</td>
                                <td valign='top' width="35%">							
                                    <input title="Enter Details" value="<?php echo $trans_party_details ?>" id="trans_party_details" name="trans_party_details" type="text" maxlength="100" class="main_input" size="35" />
                                    <input value="<?php echo $trans_party_details ?>" id="old_trans_party_details" name="old_trans_party_details" type="hidden" />
                                </td>
                            </tr>
                        <?php } else { ?>
                            <tr>
                                <td valign='top' width="15%">Loan Code *</td>
                                <td valign='top' width="35%">
                                    <input title="Enter Details" value="<?php echo $trans_party_details ?>" id="trans_party_details" name="trans_party_details" type="text" maxlength="100" readonly class="main_input" size="35" />
                                    <input value="<?php echo $trans_party_details ?>" id="old_trans_party_details" name="old_trans_party_details" type="hidden" />
                                </td>
                            </tr>
                        <?php } ?>
                    </table>
                    <table border="0" width="100%">
                        <tr>
                            <td valign="top">
                                <button name="btnNewCard" id="button">Update Records</button>
                            </td>
                        </tr>
                    </table>
                    <script  type="text/javascript">
                        var frmvalidator = new Validator("frmOrder");
                        frmvalidator.addValidation("trans_party_details", "req", "Please enter the loan_code for the repayment");
                    </script>
                </form><br />
                <h3>Similar Loan References</h3>
                <?php
                $sql = mysql_query("select loan_code from loan_application order by loan_code asc");
                while ($row = mysql_fetch_array($sql)) {
                    $loan_code = $row['loan_code'];
                    similar_text($loan_code, $trans_party_details, $percent);
                    if ($percent >= 80) {
                        echo "<strong>Loan Reference</strong>: $loan_code; <strong>Similarity</strong>: " . number_format($percent, 2) . "%";
                        echo "<br />";
                    }
                }
                ?>
                <br />
                <h3>Active Loans with Balances</h3>
                <?php
                $sql = mysql_query("select loan_date, loan_mobile, loan_amount, loan_total_interest, loan_code from loan_application inner join users on users.Mobile_no = loan_application.Loan_mobile where loan_mobile = '$mobile ' order by loan_date desc");
                $loan_amount = 0;
                $intcount = 0;
                $repayment = 0;
                $overdue = 0;
                $total_loan_amount = 0;
                $total_repayment = 0;
                $total_overdue = 0;
                $intcount = 0;
                while ($row = mysql_fetch_array($sql)) {
                    $intcount++;
                    $name = $row['loan_name'];
                    $name = ucwords(strtolower($name));
                    $agent_name = $row['Agent_Name'];
                    $agent_name = ucwords(strtolower($agent_name));
                    $loan_date = $row['loan_date'];
                    $loan_mobile = $row['loan_mobile'];
                    $loan_amount = $row['loan_total_interest'];
                    $loan_code = $row['loan_code'];
                    $agent_mobile_no = $row['loan_agent_mobile'];

                    $sql3 = mysql_query("select sum(loan_rep_amount)repayment from loan_repayments where loan_rep_code = '$loan_code' group by loan_rep_code");
                    while ($row = mysql_fetch_array($sql3)) {
                        $repayment = $row['repayment'];
                    }

                    $balance = $loan_amount - $repayment;

                    echo "<strong>Loan Reference</strong>: $loan_code, <strong>Loan Balance</strong>: " . number_format($balance, 2) . "";
                    echo "<br />";
                }
                ?>
            </div>
        </div>
        <br class="clearfix" />
    </div>
    </div>
    <?php
    if (!empty($_POST)) {

        $trans_party_details = $_POST['trans_party_details'];
        $old_trans_party_details = $_POST['old_trans_party_details'];
        $mobile = $_POST['mobile'];
        $date = $_POST['date'];

        $page_status = $_POST['page_status'];
        $id = $_POST['id'];


        //if($userid == '13'){
        if ($userid == '82' || $userid == '171' || $userid == '168' || $userid == '253') {
            if ($page_status == 'edit') {
                $sql3 = "update suspence_accounts set trans_party_details='$trans_party_details' WHERE id  = '$id'";

                $sql4 = "insert into change_log(UID, table_name, table_id, old_value, new_value, transactiontime)values('$userid', 'suspence_accounts', '$id', '$old_trans_party_details', '$trans_party_details', '$transactiontime');";

                $result = mysql_query($sql3);
                $result = mysql_query($sql4);
            }
        }
        //else if($userid == '17'){
        else if ($userid == '107' || $userid == '111' || $userid == '254' || $userid == '170' || $userid == '1' || $userid == '252') {
            $sql3 = "update suspence_accounts set resolved = '1' WHERE id  = '$id'";

            $sql4 = "insert into change_log(UID, table_name, table_id, old_value, new_value, transactiontime)values('$userid', 'suspence_accounts', '$id', '$old_trans_party_details', '$trans_party_details', '$transactiontime');";

            //echo $sql3.'<br />';
            //echo $sql4.'<br />';
            $result = mysql_query($sql3);
            $result = mysql_query($sql4);

            $sql = mysql_query("select customer_id, customer_station, loan_status, current_collector from loan_application where loan_code = '$trans_party_details'");
            while ($row = mysql_fetch_array($sql)) {
                $customer_id = $row['customer_id'];
                $customer_station = $row['customer_station'];
                $loan_status = $row['loan_status'];
                $current_collector = $row['current_collector'];
            }

            $sql = mysql_query("select id, receipt, date, details, status, withdrawn, paid_in, balance, balance_confirmed, trans_type, other_party_info, trans_party_details from suspence_accounts where id = '$id'");
            while ($row = mysql_fetch_array($sql)) {
                $id = $row['id'];
                $receipt = $row['receipt'];
                $date = $row['date'];
                $details = $row['details'];
                $status = $row['status'];
                $paid_in = $row['paid_in'];
                $balance = $row['balance'];
                $withdrawn = $row['withdrawn'];
                $balance_confirmed = $row['balance_confirmed'];
                $trans_type = $row['trans_type'];
            }



            $sql5 = "INSERT INTO loan_repayments (loan_rep_date, customer_id, customer_station, loan_rep_mobile, loan_rep_amount, loan_rep_mpesa_code, loan_rep_code, UID, loan_status, current_collector)
			VALUES('$date', '$customer_id', '$customer_station', '$mobile', '$paid_in', '$receipt', '$trans_party_details', '$userid', '$loan_status', '$current_collector');";

            $sql3 = mysql_query("select mobile_no from users where id = '$customer_id'");
            while ($row = mysql_fetch_array($sql3)) {
                $mobile_no = $row['mobile_no'];
            }

            $message_text = "Dear customer, your payment of KES " . number_format($loan_rep_amount, 0) . " has been received. Payment Ref: $loan_rep_mpesa_code. For more info 0205144099.";

            echo $message_text . "<br />";
            $sql7 = "INSERT INTO out_msg_logs (loan_code, customer_id, mobile_no, msg_text, status, new, transactiontime) 
			VALUES('$loan_rep_code', '$customer_id', '$mobile_no', '$message_text', '1', '2', '$transactiontime')";


            $sql6 = "insert into change_log(UID, table_name, table_id, new_value, transactiontime)values('$userid', 'loan_repayments', '$id', '$trans_party_details', '$transactiontime');";

            //echo $sql3.'<br />';
            //echo $sql4.'<br />';
            //echo $sql5.'<br />';
            //echo $sql6.'<br />';


            $result = mysql_query($sql5);
            $result = mysql_query($sql6);
            $result = mysql_query($sql7);


            $sql3 = mysql_query("select loan_amount, loan_date, loan_total_interest, loan_late_interest, loan_code, loan_due_date, late_status, initiation_fee, loan_extension from loan_application where customer_id = '$customer_id' and loan_code = '$trans_party_details'");
            while ($row = mysql_fetch_array($sql3)) {
                $current_loan = $row['loan_amount'];
                $loan_date = $row['loan_date'];
                $latest_loan = $row['loan_total_interest'];
                $latest_loan_code = $row['loan_code'];
                $loan_due_date = $row['loan_due_date'];
                $initiation_fee = $row['initiation_fee'];
                $loan_extension = $row['loan_extension'];
            }

            $sql4 = mysql_query("select sum(loan_rep_amount)repayments from loan_repayments where customer_id = '$customer_id' and loan_rep_code = '$trans_party_details' group by loan_rep_code");
            while ($row = mysql_fetch_array($sql4)) {
                $repayments = $row['repayments'];
                if ($repayments == "") {
                    $repayments = 0;
                }
            }

            $sql5 = mysql_query("select loan_rep_date from loan_repayments where customer_id = '$customer_id' and loan_rep_code = '$trans_party_details' order by loan_rep_date desc limit 1");
            while ($row = mysql_fetch_array($sql5)) {
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
            //echo "Due ".$due_loan_total_interest."<br />";
            //echo "Paid In ".$paid_in."<br />";
            //echo "Loan ".$latest_loan."<br />";
            //echo "Repayments ".$repayments."<br />";
            
            include_once('classes/mobile_survey.php');

            if ($due_loan_total_interest == $repayments) {
                $early_settlement = $latest_loan - $repayments;
                $latest_loan = $latest_loan - $early_settlement;
                $sql6 = "update loan_application set loan_status='13' WHERE loan_code  = '$trans_party_details'";
                $result = mysql_query($sql5);
                $data = getSurvey($loan_rep_mobile, 'safaricom', '4GLOAN', $customer_id);
                //echo $sql6."<br />";
                if ($early_settlement > 0) {
                    $early_settlement = -$early_settlement;
                    $sql7 = "update loan_application set early_settlement='$early_settlement', loan_total_interest = '$latest_loan' WHERE loan_code  = '$trans_party_details'";
                    $result = mysql_query($sql7);
                    //echo $sql7."<br />";
                }
            } else if ($repayments >= $latest_loan) {
                $early_settlement_surplus = $repayments - $latest_loan;
                $latest_loan = $latest_loan - $early_settlement_surplus;
                $sql5 = "update loan_application set loan_status='13' WHERE loan_code  = '$trans_party_details'";
                $result = mysql_query($sql5);
                $data = getSurvey($loan_rep_mobile, 'safaricom', '4GLOAN', $customer_id);
                if ($early_settlement_surplus > 0) {
                    $early_settlement_surplus_figure = -$early_settlement_surplus;
                    $sql15 = "update overpayments_schedule set loan_balance = '$early_settlement_surplus' WHERE loan_code  = '$trans_party_details'";
                    $result = mysql_query($sql15);
                    //echo $sql15."<br />";
                }
            }
        }

        $query = "suspence_account.php";
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
