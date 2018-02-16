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
    $title = $_SESSION["title"];
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

    include_once('includes/db_conn.php');
    include_once('classes/RecordCrb.php');
    $recordCrb = new RecordCrb();

    $transactiontime = date("Y-m-d G:i:s");
    $page_title = "Customer Details";

    if (!empty($_GET)) {
        $user_id = $_GET['user_id'];
        $action = $_GET['action'];
        $user_req_id = $_GET['user_req_id'];
        $mode = $_GET['mode'];
        $loan_rep_id = $_GET['loan_rep_id'];
        $action = $_GET['action'];
        $loan_id = $_GET['loan_id'];
        $loan_code_update = $_GET['loan_code'];
    }
    include_once('includes/header.php');
    include_once('cron_limit_loans.php');

    $filter_month = date("m");
    $filter_year = date("Y");
    $filter_day = date("d");
    $current_date = $filter_year . '-' . $filter_month . '-' . $filter_day;
    //$current_date = '2015-06-10';
    
    $result = mysql_query("select first_name, last_name, email_address, freeze from user_profiles where id = '$userid'");
    while ($row = mysql_fetch_array($result))
    {
        $staff_first_name = $row['first_name'];
        $staff_last_name = $row['last_name'];
        $freeze = $row['freeze'];
    }
    

    $sql = mysql_query("select id, passportfileupload, resumefileupload, resumefileupload_back, lat, lng, "
            . "first_name, last_name, mobile_no, dis_phone, affordability, status, customer_comments, "
            . "limit_loan_amount, customer_state, loan_officer, collections_officer from users "
            . "where id = '$user_id' order by id asc");
    while ($row = mysql_fetch_array($sql)) {
        $user_id = $row['id'];
        $first_name = $row['first_name'];
        $last_name = $row['last_name'];
        $first_name = ucwords(strtolower($first_name));
        $last_name = ucwords(strtolower($last_name));
        $name = $first_name . ' ' . $last_name;
        $mobile_no = $row['mobile_no'];
        $dis_phone = $row['dis_phone'];
        $affordability = $row['affordability'];
        $customer_status = $row['status'];
        $passportfileupload = $row['passportfileupload'];
        $resumefileupload = $row['resumefileupload'];
        $resumefileupload_back = $row['resumefileupload_back'];
        $lat = $row['lat'];
        $lng = $row['lng'];
        $customer_comments = $row['customer_comments'];
        $limit_loan_amount = $row['limit_loan_amount'];
        $customer_state = $row['customer_state'];
        $loan_officer = $row['loan_officer'];
        $collections_officer = $row['collections_officer'];
    }

    //echo $passportfileupload."<br />";
    //echo $resumefileupload."<br />";
    //echo $resumefileupload_back."<br />";

    $sql2 = mysql_query("select loan_status from loan_application where customer_id = '$user_id' order by loan_id desc limit 1");
    while ($row = mysql_fetch_array($sql2)) {
        $last_loan_status = $row['loan_status'];
    }
    //if($last_loan_status == '2'){
    //	$sql3 = mysql_query("select loan_amount, loan_date, loan_total_interest, loan_late_interest, loan_code, loan_due_date, late_status from loan_application where customer_id = '$user_id' and loan_status = '2' order by loan_id desc limit 1");
    //}
    //else if($last_loan_status == '5'){
    //	$sql3 = mysql_query("select loan_amount, loan_date, loan_total_interest, loan_late_interest, loan_code, loan_due_date, late_status from loan_application where customer_id = '$user_id' and loan_status = '5' order by loan_id desc limit 1");
    //}
    //else{
    //	$sql3 = mysql_query("select loan_amount, initiation_fee, loan_date, loan_total_interest, loan_late_interest, loan_code, loan_due_date, late_status from loan_application where customer_id = '$user_id' and loan_status = '6' order by loan_id desc limit 1");
    //}
    $sql3 = mysql_query("select loan_id, loan_amount, loan_date, loan_total_interest, loan_late_interest, loan_code, loan_due_date, late_status, initiation_fee, loan_status, admin_fee, appointment_fee, early_settlement, early_settlement_surplus, fix, joining_fee from loan_application where customer_id = '$user_id' and loan_status != '12' and loan_status != '11' and loan_status != '14' order by loan_date desc limit 1");
    while ($row = mysql_fetch_array($sql3)) {
        $latest_loan_id = $row['loan_id'];
        $current_loan = $row['loan_amount'];
        $loan_date = $row['loan_date'];
        $latest_loan = $row['loan_total_interest'];
        $latest_loan_code = $row['loan_code'];
        $loan_due_date = $row['loan_due_date'];
        $loan_late_interest = $row['loan_late_interest'];
        $late_status = $row['late_status'];
        $initiation_fee = $row['initiation_fee'];
        $loan_status = $row['loan_status'];
        $admin_fee = $row['admin_fee'];
        $appointment_fee = $row['appointment_fee'];
        $early_settlement = $row['early_settlement'];
        $early_settlement_surplus = $row['early_settlement_surplus'];
        $fix = $row['fix'];
        $joining_fee = $row['joining_fee'];
    }

    //$loan_due_date = date('d/m/y', strtotime(str_replace('-', '/', $loan_due_date)));

    $sql4 = mysql_query("select sum(loan_rep_amount)repayments from loan_repayments where loan_rep_code = '$latest_loan_code' group by loan_rep_code");

    while ($row = mysql_fetch_array($sql4)) {
        $repayments = $row['repayments'];
        if (is_null($repayments) || $repayments == '') {
            $repayments = 0;
        }
    }

    $balance = $latest_loan - $repayments;


    $date1 = strtotime($loan_due_date);
    $date2 = strtotime($current_date);
    $dateDiff = $date1 - $date2;
    $days = floor($dateDiff / (60 * 60 * 24));

    //echo $days;

    $date1 = "";
    $date2 = "";

    $date1 = strtotime($loan_date);
    $date2 = strtotime($current_date);
    $dateDiff = $date2 - $date1;
    $due_days = floor($dateDiff / (60 * 60 * 24));

    if ($due_days < 14) {
        $due_days = 14;
    }
    //else if($due_days > 30){
    //	$due_days = 14;
    //}
    else {
        $due_days = $due_days;
    }

    //$due_days = 30;
    //echo $current_date.'<br />';
    //echo $due_days.'<br />';

    if ($due_days <= 30) {
        //$loan_amount_settle = $current_loan + $initiation_fee;

        $due_loan_interest = $current_loan * ($due_days / 100);
        $due_loan_total_interest = $due_loan_interest + $current_loan + $initiation_fee + $admin_fee + $appointment_fee + $early_settlement + $early_settlement_surplus + $fix + $joining_fee;
        $due_loan_total_interest = $due_loan_total_interest - $repayments;
    } else {
        //$loan_amount_settle = $current_loan + $initiation_fee;
        $due_loan_interest = $current_loan * ($due_days / 100);
        $due_loan_total_interest = $due_loan_interest + $current_loan + $initiation_fee;
        $due_loan_total_interest = $due_loan_total_interest - $repayments;
    }

    //echo $due_days."<br />";
    //echo $current_loan."<br />";
    //echo $due_loan_total_interest."<br />";
    //echo $balance."<br />";
    //echo $loan_status."<br />";
    //echo $due_loan_total_interest."<br />";
    //echo $balance."<br />";
    //if($balance > 0){
    //	$balance = $balance;
    //	$due_loan_total_interest = $due_loan_total_interest;
    //	$sql16="update loan_application set loan_status='2' where loan_code = '$latest_loan_code'";
    //echo $sql16."<br />";  
    //$result = mysql_query($sql16);
    //}
    //else{
    //if($loan_status == '13' && $due_loan_total_interest > 0){
    //	if($balance == 0 || $due_loan_total_interest > 0){
    if ($loan_status == '13' && $due_loan_total_interest > 0) {
        if ($balance == 0 || $due_loan_total_interest > 0) {
            $sql16 = "update loan_application set loan_status='13' where loan_code = '$latest_loan_code'";

            //echo $sql16."<br />";  
            $result = mysql_query($sql16);

            $due_loan_total_interest = 0;
            $balance = 0;
        }
    }
    //else if($loan_status == '2' || $due_loan_total_interest <= 0 || $balance == 0){
    //	if($balance == 0 || $due_loan_total_interest <= 0){
    else if ($loan_status == '2' || $balance == 0) {
        if ($balance == 0) {
            //$sql16="update loan_application set loan_status='13', early_settlement = '$due_loan_total_interest' where loan_code = '$latest_loan_code'";
            $sql16 = "update loan_application set loan_status='13' where loan_code = '$latest_loan_code'";

            //echo $sql16."<br />";  
            $result = mysql_query($sql16);
            $due_loan_total_interest = 0;
            $balance = 0;

            $query = "customer_loans.php?user_id=$user_id";
            ?>
            <script type="text/javascript">
                <!--
                    /*alert("Either the Email Address or the Password do not match the records in the database or you have been disabled from the system, please contact the system admin at www.e-kodi.com/contact.php");*/
                //document.location = "<?php echo $query ?>";
                //-->
            </script>
            <?php
        } else {
            if ($due_days < 30) {
                $due_loan_total_interest = $due_loan_total_interest;
            } else if ($due_days > 30) {
                $due_loan_total_interest = $balance;
            }
            if ($due_loan_total_interest == 0) {
                $balance = 0;
            }
        }
    } else if ($loan_status == '13') {
        $balance = 0;
        //if($due_loan_total_interest < 0){
        //	$due_loan_total_interest = 0;
        //}
        //else{
        $due_loan_total_interest = 0;
        //}
        if ($balance == 0 || $due_loan_total_interest == 0) {
            $sql16 = "update loan_application set loan_status='13' where loan_code = '$latest_loan_code'";

            //echo $sql16."<br />";  
            $result = mysql_query($sql16);
        }
    } else {
        $balance = $balance;
        if ($due_days < 30) {
            $due_loan_total_interest = $due_loan_total_interest;
        } else if ($due_days > 30) {
            $due_loan_total_interest = $balance;
        }
        if ($due_loan_total_interest == 0) {
            $balance = 0;
        }
    }
    //}
    //echo $due_loan_total_interest."<br />";
    //echo $balance."<br />";


    $sql = mysql_query("select count(loan_id)loan_count from loan_application where customer_id = '$user_id'");
    while ($row = mysql_fetch_array($sql)) {
        $loan_count = $row['loan_count'];
        if ($loan_count == "") {
            $loan_count = 0;
        }
    }

    if ($mode == 'delete') {
        $sql2 = "delete from loan_repayments where loan_rep_id = '$loan_rep_id'";
        $result = mysql_query($sql2);
        //echo $sql2."<br />";
        $query = "customer_loans.php?user_id=$user_id";
        ?>
        <script type="text/javascript">
                <!--
                document.location = "<?php echo $query ?>";
                //-->
        </script>
        <?php
    }

    $current_date_day = date("l", strtotime($current_date));
    $sql = mysql_query("select holiday_name from holiday_names where holiday_date = '$current_date'");
    while ($row = mysql_fetch_array($sql)) {
        $holiday_name = $row['holiday_name'];
        if ($holiday_name != "") {
            $comments = 'holiday_exists';
        }
    }
    if ($action == 'reverse') {
        $sql2 = "update users set override_consq = '1', customer_state = '' where id = '$user_id'";
        $result = mysql_query($sql2);
        $sql2 = "update loan_application set customer_state = '' where customer_id = '$user_id'";
        $result = mysql_query($sql2);
        $sql5 = "insert into change_log(UID, table_name, customer_id, variable, old_value, new_value, transactiontime)
        values('$userid', 'loan_application', '$user_id', 'reverse_consq', 'None', 'reverse_consq', '$transactiontime')";
        $result = mysql_query($sql5);
        $query_link = "customer_loans.php?user_id=$user_id";
        //echo $query_link;
        ?>
        <script type="text/javascript">
                <!--
                //document.location = "user_details.php";
                document.location = "<?php echo $query_link ?>";
                //-->
        </script>
        <?php
    }
    if ($action == 'update_api') {
        $sql2 = "update loan_application set loan_failure_status = '0' where loan_code = '$loan_code_update'";
        $sql5 = "insert into change_log(UID, table_name, customer_id, variable, loan_code, old_value, new_value, transactiontime)
        values('$userid', 'loan_application', '$user_id', 'update_api_status', '$loan_code_update', 'Queued/ Failed', 'Sucess', '$transactiontime')";
        $result = mysql_query($sql2);
        $result = mysql_query($sql5);
        
        $query_link = "customer_loans.php?user_id=$user_id";
        //echo $query_link;
        ?>
        <script type="text/javascript">
                <!--
                //document.location = "user_details.php";
                document.location = "<?php echo $query_link ?>";
                //-->
        </script>
        <?php
    }
    if ($mode == 'BFC') {
        $sql10 = "update users set customer_state ='BFC' WHERE id  = '$user_id'";
        $result = mysql_query($sql10);
        echo $sql10 . "<br />";
        if ($mode == 'BFC') {
            $sql11 = "update loan_application set customer_state ='BFC', loan_status = '5', late_status = '2' WHERE loan_id = '$loan_id'";
            $result = mysql_query($sql11);
            $sql5 = "insert into change_log(UID, table_name, customer_id, variable, old_value, new_value, transactiontime)
            values('$userid', 'users', '$user_id', 'BFC/BLC', 'None', 'BFC', '$transactiontime')";
            $result = mysql_query($sql5);
            //echo $sql11 . "<br />";
        }
        $query = "customer_loans.php?user_id=$user_id";
        ?>
        <script type="text/javascript">
                <!--
                        document.location = "<?php echo $query ?>";
                //-->
        </script>
        <?php
    }
    if ($mode == 'BLC') {
        $sql10 = "update users set customer_state ='BLC' WHERE id  = '$user_id'";
        $result = mysql_query($sql10);
        $sql5 = "insert into change_log(UID, table_name, customer_id, variable, old_value, new_value, transactiontime)
        values('$userid', 'users', '$user_id', 'BFC/BLC', 'None', 'BLC', '$transactiontime')";
        $result = mysql_query($sql5);
        $query = "customer_loans.php?user_id=$user_id";
        ?>
        <script type="text/javascript">
                <!--
                        document.location = "<?php echo $query ?>";
                //-->
        </script>
        <?php
    }
    ?>	
    <div id="page">
        <div id="content">
            <div class="post">
                <h2><font color="#000A8B"><?php echo $page_title; ?></font></h2>
                <h3><strong>Customer Name:</strong> <?php echo $name ?>, <strong>Phone Number:</strong> <?php echo $mobile_no ?></h3>
                <h3><strong>Customer Affordability:</strong> KES <?php echo number_format($affordability, 2) ?></font>, 
                    <strong>Effective Balance:</strong> KES <?php echo number_format($due_loan_total_interest, 2) ?></font>,
                    <strong>CRB Listing:</strong> <?php echo $recordCrb->queryCrb($user_id) ?></font></h3>
                <?php if ($userid == '16' || $userid == '214' || $userid == '107' || $userid == '111' || $userid == '1' || $userid == '213') { ?>
                    <p align="right"><img src="images/delete.png"> - <a href="customer_loans.php?user_id=<?php echo $user_id ?>&action=reverse">Reverse the consequence of default</a></p>
                <?php } ?>
                <?php if ($freeze == '0') { ?>
                       <table width="60%">
                            <tr bgcolor="red">
                                 <td><font color="white" size="2">&nbsp;&nbsp;<?php echo $staff_first_name.' '.$staff_last_name; ?>: Your pair is on Freeze. You shall not be able to initiate loans. Please contact management.</td>
                            </tr>
                       </table>
                    <?php } ?>
                <?php if ($customer_state == 'BFC') { ?>
                    <table width="60%">
                        <tr bgcolor="red">
                            <td><font color="white" size="2">&nbsp;&nbsp;Customer tagged as BFC, thus no loan can be disbursed to this customer. Please contact management.</td>
                        </tr>
                    </table>
                <?php } ?>
                <?php if ($customer_state == 'BLC') { ?>
                    <table width="60%">
                        <tr bgcolor="orange">
                            <td><font color="white" size="2">&nbsp;&nbsp;Customer tagged as a Bad Luck Customer. Please contact management.</td>
                        </tr>
                    </table>
                <?php } ?>
                <?php if ($customer_comments != "" || $limit_loan_amount > '1') { ?>
                    <?php if ($limit_loan_amount > '1') { ?>
                        <table width="60%">
                            <tr bgcolor="red">
                                <td><font color="white" size="2">&nbsp;&nbsp;Limit loan amount to: <?php echo number_format($limit_loan_amount, 2) ?></td>
                            </tr>
                        </table>
                    <?php } ?>
                    <?php if ($customer_comments != "") { ?>
                        <font size="2"><strong>NOTES: <?php echo $customer_comments ?></strong></font><br />
                    <?php } ?>
                <?php } ?>
                <p align="right"><font size="3"><strong>To Settle Today</strong></font></h3>
                <p align="right"><font size="5"><strong>KES <?php echo number_format($due_loan_total_interest, 2) ?></strong> </font></p>
                <?php if ($title == '1' || $title == '2') { ?>
                    <?php if ($customer_state == 'BFC' || $customer_state == 'BLC') { ?>
                        <p align="right"><font><strong>TAG: BFC| BLC</strong> </font></p>
                    <?php } else {
                        ?>
                        <p align="right"><font><strong>TAG: <a href='bfc_questions.php?loan_id=<?php echo $latest_loan_id ?>&user_id=<?php echo $user_id ?>'>BFC</a> | <a href='bfc_questions.php?loan_id=<?php echo $latest_loan_id ?>&user_id=<?php echo $user_id ?>'>BLC</a></strong> </font></p>
                    <?php } ?>
                <?php } ?>
                <div id="tabs">
                    <ul>
                        <li><a href="#tabs-1">Loans</a></li>
                        <li><a href="#tabs-2">Repayments</a></li>
                        <li><a href="#tabs-3">Messages</a></li>
                        <li><a href="#tabs-4">BLC/ BFC</a></li>
                        <li><a href="#tabs-5">Letters</a></li>
                        <li><a href="#tabs-6">Interactions</a></li>
                        <li><a href="#tabs-7">Waivers</a></li>
                        <li><a href="#tabs-8">Comments</a></li>
                        <!--
                        <?php if ($station == '3') { ?>
                        <li><a href="#tabs-9">Change Log</a></li>
                        <?php } ?>
                        -->
                    </ul>
                    <div id="tabs-1">
                        <?php if ($calculated_loan_balance == '0' && $comments != 'holiday_exists') { ?>
                            <?php if ($customer_state == "" || $customer_state == 'BLC' || $customer_state === NULL) { ?>
                                <?php if ($loan_count == 0 && $affordability != 0 && ($title == 1) && $freeze != '0') { ?>
                                    <?php if ($passportfileupload != "" && $resumefileupload != "" && $resumefileupload_back != "" && $lat != "" && $lng != "" && $loan_officer != "" && $collections_officer != "") { ?>
                                        <p>+ <a href="loan_details.php?user_id=<?php echo $user_id ?>">Add a new Loan Application</a></p>
                                    <?php } ?>
                                <?php } else if ($affordability != 0 && $loan_status == 13 && $loan_status == 11 && $loan_status == 12 && $loan_status == 14 && ($title == 1) && $loan_officer != "" && $collections_officer != "" && $freeze != '0') { ?>
                                    <p>+ <a href="loan_details.php?user_id=<?php echo $user_id ?>">Add a new Loan Application</a></p>
                                <?php } else if ($affordability != 0 && $loan_status != 2 && $loan_status != 3 && $loan_status != 4 && $loan_status != 5 && $loan_status != 6 && $loan_status != 7 && $loan_status != 9 && $loan_status != 10 && ($title == 1) && $loan_officer != "" && $collections_officer != "" && $freeze != '0') { ?>
                                    <p>+ <a href="loan_details.php?user_id=<?php echo $user_id ?>">Add a new Loan Application</a></p>
                                <?php } ?>
                            <?php } ?>
                        <?php } else {
                            ?>
                            <table width="60%">
                                <tr bgcolor="#600000">
                                    <td><font color="white" size="2">&nbsp;&nbsp;Please clear all loan balances to disburse a new loan. Please contact management.</td>
                                </tr>
                            </table>
                        <?php } ?>
                        <table width="100%" border="0" cellspacing="2" cellpadding="2" class="display" id="example2">
                            <thead bgcolor="#E6EEEE">
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Due</th>
                                    <th>Code</th>
                                    <th>Money</th>
                                    <th>Status</th>
                                    <th>API</th>
                                    <th>Disbursed</th>
                                    <th>Fees</th>
                                    <th>Total</th>
                                    <th>Repayments</th>
                                    <th>Balance</th>
                                    <?php if ($title == '3' || $title == '8' || $title == '4' || $title == '6' || $title == '7' || $title == '10' || $title == '11') { ?>
                                        <th>Edit</th>
                                    <?php } ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql = mysql_query("select loan_id, loan_date, loan_due_date, loan_mobile, initiation_fee, loan_amount, loan_extension, loan_interest, loan_late_interest, waiver, loan_total_interest, loan_code, loan_mpesa_code, loan_failure_status, loan_status, loan_status, admin_fee, appointment_fee, early_settlement, early_settlement_surplus, fix, joining_fee, loan_failure_status, UID from loan_application where customer_id = '$user_id' and loan_status != '12' order by loan_date desc");
                                $interest = 0;
                                $total_loan_amount = 0;
                                $total_interest = 0;
                                $total_loan_total_interest = 0;
                                $intcount = 0;
                                $total_allocation_fees = 0;
                                $repayments = 0;
                                $loan_balance = 0;
                                $total_loan_balance = 0;
                                $total_repayments = 0;
                                $total_fees = 0;
                                $fees = 0;
                                while ($row = mysql_fetch_array($sql)) {
                                    $intcount++;
                                    $loan_id = $row['loan_id'];
                                    $loan_date = $row['loan_date'];
                                    $loan_date = date("d M, Y", strtotime($loan_date));
                                    $loan_due_date = $row['loan_due_date'];
                                    $loan_due_date = date("d M, Y", strtotime($loan_due_date));
                                    $loan_mobile = $row['loan_mobile'];
                                    $initiation_fee = $row['initiation_fee'];
                                    $loan_amount = $row['loan_amount'];
                                    $loan_extension = $row['loan_extension'];
                                    $loan_interest = $row['loan_interest'];
                                    $loan_total_interest = $row['loan_total_interest'];
                                    $loan_status = $row['loan_status'];
                                    $loan_code = $row['loan_code'];
                                    $loan_status = $row['loan_status'];
                                    $loan_late_interest = $row['loan_late_interest'];
                                    $loan_mpesa_code = $row['loan_mpesa_code'];
                                    $waiver = $row['waiver'];
                                    $loan_failure_status = $row['loan_failure_status'];
                                    $UID = $row['UID'];

                                    $admin_fee = $row['admin_fee'];
                                    $appointment_fee = $row['appointment_fee'];
                                    $early_settlement = $row['early_settlement'];
                                    $early_settlement_surplus = $row['early_settlement_surplus'];
                                    $fix = $row['fix'];
                                    $joining_fee = $row['joining_fee'];
                                    $loan_failure_status = $row['loan_failure_status'];

                                    if ($loan_failure_status == '0') {
                                        $loan_failure_status_name = 'Success';
                                    } else if ($loan_failure_status == '1') {
                                        $loan_failure_status_name = 'Queued';
                                    } else if ($loan_failure_status == '2') {
                                        $loan_failure_status_name = 'Failed';
                                    }

                                    $allocation_fees = $waiver + $admin_fee + $appointment_fee + $early_settlement + $early_settlement_surplus + $fix + $joining_fee;

                                    $fees = $loan_extension + $initiation_fee + $loan_late_interest + $allocation_fees + $loan_interest;

                                    $sql2 = mysql_query("select status from customer_status where id = '$loan_status'");
                                    while ($row = mysql_fetch_array($sql2)) {
                                        $loan_status_name = $row['status'];
                                    }

                                    $sql2 = mysql_query("select sum(loan_rep_amount)repayments from loan_repayments where loan_rep_code = '$loan_code' group by loan_rep_code");
                                    while ($row = mysql_fetch_array($sql2)) {
                                        $repayments = $row['repayments'];
                                        if ($repayments == '') {
                                            $repayments = 0;
                                        }
                                    }

                                    $loan_balance = $loan_total_interest - $repayments;
                                    if ($loan_status == '12' || $loan_status == '11' || $loan_status == '14' || $loan_status == '15' || $loan_status == '18') {
                                        $loan_balance = 0;
                                    }
                                    
                                    if ($UID == '94') {
                                        $repeater = 'R';
                                    } else {
                                        $repeater = 'M';
                                    }

                                    if ($intcount % 2 == 0) {
                                        $display = '<tr bgcolor = #F0F0F6>';
                                    } else {
                                        $display = '<tr>';
                                    }
                                    echo $display;
                                    echo "<td valign='top'>$intcount.</td>";
                                    echo "<td valign='top'>$loan_date</td>";
                                    echo "<td valign='top'>$loan_due_date</td>";
                                    echo "<td valign='top'>$loan_code</td>";
                                    //echo "<td valign='top'>$loan_mobile</td>";					
                                    echo "<td valign='top'>$loan_mpesa_code</td>";
                                    echo "<td valign='top'>$loan_status_name</td>";
                                    if ($userid == '171' || $userid == '170' || $userid == '82'|| $userid == '1' || $userid == '111') {
                                        if($loan_failure_status != '0'){
                                            echo "<td valign='top'><a href='customer_loans.php?user_id=$user_id&action=update_api&loan_code=$loan_code'>$loan_failure_status_name - $repeater<img src='images/red_flag.png' width='20%'></a></td>";
                                        }
                                        else{
                                            echo "<td valign='top'>$loan_failure_status_name - $repeater</td>";
                                        }
                                    }
                                    else{
                                        echo "<td valign='top'>$loan_failure_status_name - $repeater</td>";
                                    }
                                    
                                    echo "<td valign='top' align='right'>" . number_format($loan_amount, 2) . "</td>";
                                    echo "<td valign='top' align='right'>" . number_format($fees, 2) . "</td>";
                                    echo "<td valign='top' align='right'>" . number_format($loan_total_interest, 2) . "</td>";
                                    echo "<td valign='top' align='right'>" . number_format($repayments, 2) . "</td>";
                                    echo "<td valign='top' align='right'>" . number_format($loan_balance, 2) . "</td>";
                                    if ($title == '3' || $title == '8' || $title == '4' || $title == '6' || $title == '7' || $title == '10' || $title == '11') {
                                        echo "<td valign='top'><a href='loan_details.php?loan_id=$loan_id&user_id=$user_id&mode=edit'><img src='images/edit.png' width='35px'></a></td>";
                                    }
                                    echo "</tr>";
                                    $total_fees = $total_fees + $fees;
                                    $total_repayments = $total_repayments + $repayments;
                                    $total_loan_balance = $total_loan_balance + $loan_balance;
                                    $total_initiation_fee = $total_initiation_fee + $initiation_fee;
                                    $total_loan_amount = $total_loan_amount + $loan_amount;
                                    $total_interest = $total_interest + $loan_interest;
                                    $total_extension = $total_extension + $loan_extension;
                                    $total_loan_late_interest = $total_loan_late_interest + $loan_late_interest;
                                    $total_loan_total_interest = $total_loan_total_interest + $loan_total_interest;
                                    $total_allocation_fees = $total_allocation_fees + $allocation_fees;

                                    $initiation_fee = 0;
                                    $loan_amount = 0;
                                    $loan_extension = 0;
                                    $loan_late_interest = 0;
                                    $loan_interest = 0;
                                    $loan_total_interest = 0;
                                    $allocation_fees = 0;
                                    $loan_mpesa_code = "";
                                    $repayments = 0;
                                    $loan_balance = 0;
                                    $fees = 0;
                                }
                                ?>
                            </tbody>
                            <tr bgcolor = '#E6EEEE'>
                                <td colspan='7'><strong>&nbsp;</strong></td>
                                <td align='right' valign='top'><strong><?php echo number_format($total_loan_amount, 2) ?></strong></td>
                                <td align='right' valign='top'><strong><?php echo number_format($total_fees, 2) ?></strong></td>
                                <td align='right' valign='top'><strong><?php echo number_format($total_loan_total_interest, 2) ?></strong></td>
                                <td align='right' valign='top'><strong><?php echo number_format($total_repayments, 2) ?></strong></td>
                                <td align='right' valign='top'><strong><?php echo number_format($total_loan_balance, 2) ?></strong></td>
                            </tr>
                            </tr>
                            <tfoot bgcolor="#E6EEEE">
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Due</th>
                                    <th>Code</th>
                                    <th>Money</th>
                                    <th>Status</th>
                                    <th>API</th>
                                    <th>Disbursed</th>
                                    <th>Fees</th>
                                    <th>Total</th>
                                    <th>Repayments</th>
                                    <th>Balance</th>
                                    <?php if ($title == '3' || $title == '8' || $title == '4' || $title == '6' || $title == '7' || $title == '10' || $title == '11') { ?>
                                        <th>Edit</th>
                                    <?php } ?>
                                </tr>
                            </tfoot>
                        </table>
                        <br /><br />
                        <strong>Notes</strong>
                        <br />R: Repeater loans
                        <br />M: Manual loans
                    </div>

                    <div id="tabs-2">
                        <?php if ($userid == '86' || $userid == '39' || $userid == '176' || $userid == '144') { ?>
                            <p>+ <a href="repayment_details.php?user_id=<?php echo $user_id ?>">Add a new Loan Repayment Detail</a></p>
                        <?php } ?>
                        <table width="100%" border="0" cellspacing="2" cellpadding="2" class="display" id="example3">
                            <thead bgcolor="#E6EEEE">
                                <tr>
                                    <th>Date</th>
                                    <th>Mobile</th>
                                    <th>M. Money</th>
                                    <th>Loan Code</th>
                                    <th>Repayment</th>
                                    <?php if ($userid == '171' || $userid == '170' || $userid == '82'|| $userid == '1') { ?>
                                        <th>Edit</th>
                                        <th>Delete</th>
                                    <?php } ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $total_loan_rep_amount = 0;
                                $sql = mysql_query("select distinct loan_code from loan_application where customer_id = '$user_id' order by loan_code asc");
                                while ($row = mysql_fetch_array($sql)) {
                                    $loan_code = $row['loan_code'];

                                    $sql2 = mysql_query("select loan_rep_id, loan_rep_date, loan_rep_mobile, loan_rep_amount, loan_rep_mpesa_code, loan_rep_code from loan_repayments inner join loan_application on loan_application.loan_code = loan_repayments.loan_rep_code where loan_rep_code = '$loan_code' order by loan_repayments.loan_rep_date desc");
                                    $intcount = 0;

                                    while ($row = mysql_fetch_array($sql2)) {
                                        $intcount++;
                                        $loan_rep_id = $row['loan_rep_id'];
                                        $loan_rep_date = $row['loan_rep_date'];
                                        $loan_rep_date = date("d M, Y", strtotime($loan_rep_date));
                                        $loan_rep_mobile = $row['loan_rep_mobile'];
                                        $loan_rep_amount = $row['loan_rep_amount'];
                                        $loan_rep_mpesa_code = $row['loan_rep_mpesa_code'];
                                        $loan_rep_code = $row['loan_rep_code'];
                                        $loan_rep_agent_mobile = $row['loan_rep_agent_mobile'];
                                        $loan_rep_code = ucwords(strtolower($loan_rep_code));


                                        if ($intcount % 2 == 0) {
                                            $display = '<tr bgcolor = #F0F0F6>';
                                        } else {
                                            $display = '<tr>';
                                        }
                                        echo $display;
                                        echo "<td valign='top'>$loan_rep_date</td>";
                                        echo "<td valign='top'>$loan_rep_mobile</td>";
                                        echo "<td valign='top'>$loan_rep_mpesa_code</td>";
                                        echo "<td valign='top'>$loan_rep_code</td>";
                                        echo "<td valign='top' align='right'>" . number_format($loan_rep_amount, 2) . "</td>";
                                        if ($userid == '171' || $userid == '170' || $userid == '82'|| $userid == '1') {
                                            echo "<td valign='top'><a href='repayment_details.php?user_id=$user_id&loan_rep_id=$loan_rep_id&mode=edit'><img src='images/edit.png' width='35px'></a></td>";
                                            echo "<td valign='top'><a href='customer_loans.php?user_id=$user_id&loan_rep_id=$loan_rep_id&mode=delete'><img src='images/delete.png' width='35px'></a></td>";
                                        }
                                        echo "</tr>";
                                        $total_loan_rep_amount = $total_loan_rep_amount + $loan_rep_amount;

                                        $loan_rep_id = "";
                                        $loan_rep_date = "";
                                        $loan_rep_mobile = "";
                                        $loan_rep_amount = "";
                                        $loan_rep_mpesa_code = "";
                                        $loan_rep_code = "";
                                        $loan_rep_agent_mobile = "";
                                    }
                                    //$loan_rep_code = "";
                                }
                                ?>
                            </tbody>
                            <tr bgcolor = '#E6EEEE'>
                                <td colspan='4'><strong>&nbsp;</strong></td>
                                <td align='right' valign='top'><strong>KES <?php echo number_format($total_loan_rep_amount, 2) ?></strong></td>
                            </tr>
                            <tfoot bgcolor="#E6EEEE">
                                <tr>
                                    <th>Date</th>
                                    <th>Mobile</th>
                                    <th>M. Money</th>
                                    <th>Loan Code</th>
                                    <th>Repayment</th>
                                    <?php if ($userid == '171' || $userid == '170' || $userid == '82'|| $userid == '1') { ?>
                                        <th>Edit</th>
                                        <th>Delete</th>
                                    <?php } ?>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div id="tabs-3">
                        <table width="100%" border="0" cellspacing="2" cellpadding="2" class="display" id="example5">
                            <thead bgcolor="#E6EEEE">
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Mobile</th>
                                    <th>Message</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql = mysql_query("select mobile_no, msg_text, status, transactiontime from out_msg_logs where customer_id = '$user_id' order by transactiontime desc limit 50");
                                $intcount = 0;
                                $total_loan_rep_amount = 0;
                                while ($row = mysql_fetch_array($sql)) {
                                    $intcount++;
                                    $transactiontime = $row['transactiontime'];
                                    $mobile_no = $row['mobile_no'];
                                    $msg_text = $row['msg_text'];
                                    $status = $row['status'];
                                    if ($status == '1') {
                                        $status = 'Delivered';
                                    } else {
                                        $status = 'Pending';
                                    }

                                    if ($intcount % 2 == 0) {
                                        $display = '<tr bgcolor = #F0F0F6>';
                                    } else {
                                        $display = '<tr>';
                                    }
                                    echo $display;
                                    echo "<td valign='top'>$intcount.</td>";
                                    echo "<td valign='top'>$transactiontime</td>";
                                    echo "<td valign='top'>$mobile_no</td>";
                                    echo "<td valign='top'>$msg_text</td>";
                                    echo "<td valign='top'>$status</td>";
                                    echo "</tr>";
                                }
                                ?>
                            </tbody>
                            <tfoot bgcolor="#E6EEEE">
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Mobile</th>
                                    <th>Message</th>
                                    <th>Status</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div id="tabs-4">
                        <table width="100%" border="0" cellspacing="2" cellpadding="2" class="display" id="example7">
                            <thead bgcolor="#E6EEEE">
                                <tr>
                                    <th>#</th>
                                    <th>Loan Code</th>
                                    <th>Loan Status</th>
                                    <th>Result</th>
                                    <th>Category</th>
                                    <th>Reason</th>
                                    <th>Staff</th>
                                    <th>Transactiontime</th>
                                    <th>View</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql = mysql_query("select id, loan_id, total_sum, reason_for_default, other_sources, UID, transactiontime from bfc_questions where user_id = '$user_id' order by transactiontime asc");
                                $intcount = 0;
                                while ($row = mysql_fetch_array($sql)) {
                                    $intcount++;
                                    $id = $row['id'];
                                    $loan_id = $row['loan_id'];
                                    $total_sum = $row['total_sum'];
                                    $reason_for_default = $row['reason_for_default'];
                                    $UID = $row['UID'];
                                    $transactiontime = $row['transactiontime'];
                                    if($total_sum >= '4'){
                                        $category = 'BFC';
                                    }
                                    else{
                                        $category = 'BLC';
                                    }

                                    $sql2 = mysql_query("select loan_code, customer_status.status from loan_application inner join customer_status on customer_status.id = loan_application.loan_status where loan_id = '$loan_id'");
                                    while ($row = mysql_fetch_array($sql2)) {
                                        $loan_code = $row['loan_code'];
                                        $status = $row['status'];
                                    }
                                    
                                    $sql2 = mysql_query("select first_name, last_name from user_profiles where id = '$UID'");
                                    while ($row = mysql_fetch_array($sql2)) {
                                        $first_name = $row['first_name'];
                                        $last_name = $row['last_name'];
                                        $staff_name = $first_name . " " . $last_name;
                                    }
                                    
                                    $sql2 = mysql_query("select reason_for_default from reason_for_default where id = '$reason_for_default'");
									while($row = mysql_fetch_array($sql2)) {
										$reason_for_default_name = $row['reason_for_default'];
									}

                                    if ($intcount % 2 == 0) {
                                        $display = '<tr bgcolor = #F0F0F6>';
                                    } else {
                                        $display = '<tr>';
                                    }
                                    echo $display;
                                    echo "<td valign='top'>$intcount.</td>";
                                    echo "<td valign='top'>$loan_code</td>";
                                    echo "<td valign='top'>$status</td>";
                                    echo "<td valign='top'>$total_sum</td>";
                                    echo "<td valign='top'>$category</td>";
                                    echo "<td valign='top'>$reason_for_default_name</td>";
                                    echo "<td valign='top'>$staff_name</td>";
                                    echo "<td valign='top'>$transactiontime</td>";
                                    echo "<td valign='top'><a href='bfc_questions_report.php?bfc_id=$id&mode=edit'><img src='images/edit.png' width='35px'></a></td>";
                                    echo "</tr>";
                                }
                                ?>
                            </tbody>
                            <tfoot bgcolor="#E6EEEE">
                                <tr>
                                    <th>#</th>
                                    <th>Loan Code</th>
                                    <th>Loan Status</th>
                                    <th>Result</th>
                                    <th>Category</th>
                                    <th>Reason</th>
                                    <th>Staff</th>
                                    <th>Transactiontime</th>
                                    <th>View</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div id="tabs-5">
                        <table width="100%" border="0" cellspacing="2" cellpadding="2" class="display" id="example6">
                            <thead bgcolor="#E6EEEE">
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Due</th>
                                    <th>Code</th>
                                    <th>Loan Status</th>
                                    <th>View</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql = mysql_query("select loan_id, loan_date, loan_due_date, loan_mobile, loan_amount, loan_extension, loan_interest, loan_late_interest, loan_total_interest, loan_code, loan_mpesa_code, loan_failure_status, loan_status, loan_status from loan_application where customer_id = '$user_id' and loan_status != '1' and loan_status != '2' and loan_status != '3' and loan_status != '8' and loan_status != '9' and loan_status != '10' and loan_status != '11' and loan_status != '12' and loan_status != '14' and loan_status != '15' order by loan_date desc");
                                $interest = 0;
                                $total_loan_amount = 0;
                                $total_interest = 0;
                                $total_loan_total_interest = 0;
                                $intcount = 0;
                                while ($row = mysql_fetch_array($sql)) {
                                    $intcount++;
                                    $loan_id = $row['loan_id'];
                                    $loan_date = $row['loan_date'];
                                    $loan_due_date = $row['loan_due_date'];
                                    $loan_mobile = $row['loan_mobile'];
                                    $loan_amount = $row['loan_amount'];
                                    $loan_extension = $row['loan_extension'];
                                    $loan_interest = $row['loan_interest'];
                                    $loan_total_interest = $row['loan_total_interest'];
                                    $loan_status = $row['loan_status'];
                                    $loan_code = $row['loan_code'];
                                    $loan_status = $row['loan_status'];
                                    $loan_late_interest = $row['loan_late_interest'];
                                    $loan_mpesa_code = $row['loan_mpesa_code'];
                                    $loan_failure_status = $row['loan_failure_status'];
                                    $interest = $loan_total_interest - $loan_amount;

                                    $sql2 = mysql_query("select status from customer_status where id = '$loan_status'");
                                    while ($row = mysql_fetch_array($sql2)) {
                                        $loan_status_name = $row['status'];
                                    }

                                    if ($intcount % 2 == 0) {
                                        $display = '<tr bgcolor = #F0F0F6>';
                                    } else {
                                        $display = '<tr>';
                                    }
                                    echo $display;
                                    echo "<td valign='top'>$intcount.</td>";
                                    echo "<td valign='top'>$loan_date</td>";
                                    echo "<td valign='top'>$loan_due_date</td>";
                                    echo "<td valign='top'>$loan_code</td>";
                                    echo "<td valign='top'>$loan_status_name</td>";
                                    if ($loan_status == '13') {
                                        echo "<td valign='top'><a href='clearance_letter.php?loan_id=$loan_id&user_id=$user_id'><img src='images/printer_image.png' width='35px'></a></td>";
                                    } else {
                                        echo "<td valign='top'><a href='demand_letter.php?loan_id=$loan_id&user_id=$user_id'><img src='images/printer_image.png' width='35px'></a></td>";
                                    }
                                    echo "</tr>";
                                }
                                ?>
                            </tbody>
                            <tfoot bgcolor="#E6EEEE">
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Due</th>
                                    <th>Code</th>
                                    <th>Loan Status</th>
                                    <th>View</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div id="tabs-6">
    <?php //if($balance > 0){  ?>
                        <p>+ <a href="ptp_details.php?user_id=<?php echo $user_id ?>&loan_balance=<?php echo $balance ?>">Add a new Customer Interaction</a></p>
                        <?php //} ?>
                        <table width="100%" border="0" cellspacing="2" cellpadding="2" class="display" id="example">
                            <thead bgcolor="#E6EEEE">
                                <tr>
                                    <th>#</th>
                                    <th>Category</th>
                                    <th>Call/ Visit</th>
                                    <th>Vintage</th>
                                    <th>Days</th>
                                    <th>Loan</th>
                                    <th>Next Interaction</th>
                                    <th>Outcome</th>
                                    <th>Amount</th>
                                    <th>Comments</th>
                                    <th>Staff</th>
                                </tr>
                            </thead>
                            <tbody>
    <?php
    $sql = mysql_query("select id, category, loan_code, vintage, overdue_days, customer_id, loan_balance, pay_date, comments, call_outcome, transactiontime, UID from promise_to_pay where customer_id = '$user_id' order by transactiontime desc");
    $intcount = 0;
    while ($row = mysql_fetch_array($sql)) {
        $intcount++;
        $id = $row['id'];
        $loan_code = $row['loan_code'];
        $customer_id = $row['customer_id'];
        $loan_balance = $row['loan_balance'];
        $pay_date = $row['pay_date'];
        $UID = $row['UID'];
        $category = $row['category'];
        $call_outcome = $row['call_outcome'];
        $transactiontime = $row['transactiontime'];
        $vintage = $row['vintage'];
        $comments = $row['comments'];
        $overdue_days = $row['overdue_days'];
        $sql2 = mysql_query("select first_name, last_name from user_profiles where id = '$UID'");
        while ($row = mysql_fetch_array($sql2)) {
            $first_name = $row['first_name'];
            $last_name = $row['last_name'];
            $staff_name = $first_name . " " . $last_name;
        }
        $sql2 = mysql_query("select reason_code from call_outcome where id = '$call_outcome'");
        while ($row = mysql_fetch_array($sql2)) {
            $reason_code = $row['reason_code'];
        }
        if ($intcount % 2 == 0) {
            $display = '<tr bgcolor = #F0F0F6>';
        } else {
            $display = '<tr>';
        }
        echo $display;
        echo "<td valign='top'>$intcount.</td>";
        echo "<td valign='top'>$category</td>";
        echo "<td valign='top'>$transactiontime</td>";
        echo "<td valign='top'>$vintage</td>";
        echo "<td valign='top'>$overdue_days</td>";
        echo "<td valign='top'>$loan_code</td>";
        echo "<td valign='top'>$pay_date</td>";
        echo "<td valign='top'>$reason_code</td>";
        echo "<td valign='top'>" . number_format($loan_balance, 2) . "</td>";
        echo "<td valign='top'>$comments</td>";
        echo "<td valign='top'>$staff_name</td>";
        echo "</tr>";
    }
    ?>
                            </tbody>
                            <tfoot bgcolor="#E6EEEE">
                                <tr>
                                    <th>#</th>
                                    <th>Category</th>
                                    <th>Call/ Visit</th>
                                    <th>Vintage</th>
                                    <th>Days</th>
                                    <th>Loan</th>
                                    <th>Next Interaction</th>
                                    <th>Outcome</th>
                                    <th>Amount</th>
                                    <th>Comments</th>
                                    <th>Staff</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div id="tabs-7">
                    <?php if ($station == '3' && ($title != '11' && $title != '12' && $title != '13')) { ?>
                            <p>+ <a href="customer_waivers.php?user_id=<?php echo $user_id ?>">Add a Customer Waiver</a></p>
                        <?php } ?>
                        <table width="100%" border="0" cellspacing="2" cellpadding="2" class="display" id="example10">
                            <thead bgcolor="#E6EEEE">
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Loan Code</th>
                                    <th>Reason</th>
                                    <th>Amount</th>
                                    <th>Staff</th>
                                </tr>
                            </thead>
                            <tbody>
    <?php
    $sql = mysql_query("select id, waiver_date, loan_code, waive_amount, UID, transactiontime, extension_reason from waiver_table where customer_id = '$user_id' order by waiver_date desc");
    $intcount = 0;
    while ($row = mysql_fetch_array($sql)) {
        $intcount++;
        $id = $row['id'];
        $waiver_date = $row['waiver_date'];
        $loan_code = $row['loan_code'];
        $waive_amount = $row['waive_amount'];
        $UID = $row['UID'];
        $transactiontime = $row['transactiontime'];
        $extension_reason = $row['extension_reason'];

        $sql2 = mysql_query("select extension from extension_reason where id = '$extension_reason'");
        while ($row = mysql_fetch_array($sql2)) {
            $extension = $row['extension'];
        }

        $sql2 = mysql_query("select first_name, last_name from user_profiles where id = '$UID'");
        while ($row = mysql_fetch_array($sql2)) {
            $first_name = $row['first_name'];
            $last_name = $row['last_name'];
            $first_name = ucwords(strtolower($first_name));
            $last_name = ucwords(strtolower($last_name));
            $staff_name = $first_name . ' ' . $last_name;
        }

        if ($intcount % 2 == 0) {
            $display = '<tr bgcolor = #F0F0F6>';
        } else {
            $display = '<tr>';
        }
        echo $display;
        echo "<td valign='top'>$intcount.</td>";
        echo "<td valign='top'>$waiver_date</td>";
        echo "<td valign='top'>$loan_code</td>";
        echo "<td valign='top'>$extension</td>";
        echo "<td valign='top' align='right'>" . number_format($waive_amount, 2) . "</td>";
        echo "<td valign='top'>$staff_name</td>";
        echo "</tr>";
    }
    ?>
                            </tbody>
                            <tfoot bgcolor="#E6EEEE">
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Loan Code</th>
                                    <th>Reason</th>
                                    <th>Amount</th>
                                    <th>Staff</th>
                                </tr>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div id="tabs-8">
                        <form id="frmOrder" name="frmOrder" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                            <input type="hidden" name="user_id" id="user_id" value="<?php echo $user_id ?>" />	
                            <table border="0" width="100%" cellspacing="2" cellpadding="2">	
                                <tr>
                                    <td valign='top' >Limit Loan Amount </td>
                                </tr>
                                <tr>
                                    <td valign='top' >
                                        <?php if ($title == '2' || $title == '4' || $title == '7' || ($userid == '75')) { ?>
                                            <select name='limit_loan_amount' id='limit_loan_amount'>
                                            <option value="<?php echo $limit_loan_amount ?>"><?php echo number_format($limit_loan_amount, 2) ?></option>
                                            <option value=''> </option>	
                                            <option value="0">0.00 - The customer will have repeater access</option>
                                            <option value="2">2.00 - The customer will not have repeater access</option>
                                            <?php
                                            
                                                $sql2 = mysql_query("select max(loan_amount)maX_loan from loan_application where customer_id = '$user_id'");
                                                while ($row = mysql_fetch_array($sql2)) {
                                                    $maX_loan = $row['maX_loan'];
                                                }
                                                //echo "select max(loan_amount)maX_loan from loan_application where customer_id = '$user_id'";
                                                $sql3 = mysql_query("select id, loan_band from loan_bands where loan_band <= '$maX_loan' order by id asc");
                                                while ($row = mysql_fetch_array($sql3)) {
                                                    $loan_band = $row['loan_band'];
                                                    echo "<option value='$loan_band'>" . number_format($loan_band, 2) . "</option>";
                                                    //echo "<option value='$x'>" . number_format($x, 2) . "</option>";
                                                }
                                            ?>
                                            </select>
                                            <input value="<?php echo $limit_loan_amount ?>" id="old_limit_loan_amount" name="old_limit_loan_amount" type="hidden" />
                                        <?php } else { ?>
                                            <input title="Enter Loan Date" value="<?php echo $limit_loan_amount ?>" id="limit_loan_amount" name="limit_loan_amount" type="text" readonly maxlength="100" class="main_input" size="35" />
                                        <?php } ?>
                                    </td>
                                </tr>
                                <tr >
                                    <td valign='top' >Comments on the Customer </td>
                                </tr>
                                <tr>
                                    <td valign='top' colspan="3">
                                        <textarea title="Enter Customer Account Comments" name="customer_comments" id="customer_comments" cols="100" rows="7" class="textfield"><?php echo $customer_comments ?></textarea>
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
                    <!--
                    <?php if ($station == '3') { ?>
                    <div id="tabs-9">
                        <table width="100%" border="0" cellspacing="2" cellpadding="2" class="display" id="example11">
                            <thead bgcolor="#E6EEEE">
                                <tr>
                                    <th>#</th>
                                    <th>Table</th>
                                    <th>Variable</th>
                                    <th>Loan Code</th>
                                    <th>Old Value</th>
                                    <th>New Value</th>
                                    <th>Staff</th>
                                    <th>Datetime</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql = mysql_query("select id, UID, table_name, variable, loan_code, old_value, new_value, transactiontime from change_log where customer_id = '$user_id' order by transactiontime desc");
                                $intcount = 0;
                                $total_loan_rep_amount = 0;
                                while ($row = mysql_fetch_array($sql)) {
                                    $intcount++;
                                    $id = $row['id'];
                                    $UID = $row['UID'];
                                    $table_name = $row['table_name'];
                                    $variable = $row['variable'];
                                    $loan_code = $row['loan_code'];
                                    $old_value = $row['old_value'];
                                    $new_value = $row['new_value'];
                                    $transactiontime = $row['transactiontime'];
                                    
                                    $sql2 = mysql_query("select username, first_name, last_name from user_profiles where id = '$UID'");
                                    while ($row = mysql_fetch_array($sql2)) {
                                        $first_name = $row['first_name'];
                                        $last_name = $row['last_name'];
                                        $username = $row['username'];
                                        $first_name = ucwords(strtolower($first_name));
                                        $last_name = ucwords(strtolower($last_name));
                                        $staff_name = $first_name . ' ' . $last_name;
                                    }

                                    if ($intcount % 2 == 0) {
                                        $display = '<tr bgcolor = #F0F0F6>';
                                    } else {
                                        $display = '<tr>';
                                    }
                                    echo $display;
                                    echo "<td valign='top'>$intcount.</td>";
                                    echo "<td valign='top'>$table_name</td>";
                                    echo "<td valign='top'>$variable</td>";
                                    echo "<td valign='top'>$loan_code</td>";
                                    echo "<td valign='top'>$old_value</td>";
                                    echo "<td valign='top'>$new_value</td>";
                                    echo "<td valign='top'>$username</td>";
                                    echo "<td valign='top'>$transactiontime</td>";
                                    echo "</tr>";
                                }
                                ?>
                            </tbody>
                            <tfoot bgcolor="#E6EEEE">
                                <tr>
                                    <th>#</th>
                                    <th>Table</th>
                                    <th>Variable</th>
                                    <th>Loan Code</th>
                                    <th>Old Value</th>
                                    <th>New Value</th>
                                    <th>Staff</th>
                                    <th>Datetime</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <?php } ?>
                    -->
                </div>
                
            </div>
            <br class="clearfix" />
        </div>
    </div>
    <?php
    if (!empty($_POST)) {
        $limit_loan_amount = $_POST['limit_loan_amount'];
        $old_limit_loan_amount = $_POST['old_limit_loan_amount'];
        $customer_comments = $_POST['customer_comments'];
        $customer_comments = mysql_real_escape_string($customer_comments);
        $user_id = $_POST['user_id'];

        $transactiontime = date("Y-m-d G:i:s");

        $sql4 = "update users set customer_comments='$customer_comments', limit_loan_amount = '$limit_loan_amount' WHERE id  = '$user_id'";

        $sql5 = "insert into change_log(UID, table_name, customer_id, variable, old_value, new_value, transactiontime, comment)values('$userid', 'users', '$user_id', 'loan_size_limit', '$old_limit_loan_amount', '$limit_loan_amount', '$transactiontime', '$customer_comments')";

        //echo $sql4."<br />";
        $result = mysql_query($sql4);
        $result = mysql_query($sql5);

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
