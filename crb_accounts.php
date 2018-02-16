<?php
$userid = "";
$adminstatus = 4;
$property_manager_id = "";
session_start();
if (!empty($_SESSION)) {
    $userid = $_SESSION["userid"];
    $adminstatus = $_SESSION["adminstatus"];
    $username = $_SESSION["username"];
    $station = $_SESSION["station"];
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
    $transactiontime = date("Y-m-d G:i:s");
    $page_title = "CRB Loans Listing";
    include_once('includes/header.php');
    $filter_clerk = 0;
    if (!empty($_GET)) {
        $filter_clerk = $_GET['clerk'];
        $filter_start_date = $_GET['report_start_date'];
        $filter_start_date_formatted = date('Y-m-d', strtotime(str_replace('-', '/', $filter_start_date)));
        $filter_end_date = $_GET['report_end_date'];
        $filter_end_date_formatted = date('Y-m-d', strtotime(str_replace('-', '/', $filter_end_date)));
    }
    include_once('includes/db_conn.php');
    if ($filter_start_date != "" && $filter_end_date != "") {
        ?>
        <div id="page">
            <div id="content">
                <div class="post">
                    <h2><?php echo $page_title; ?></h2>

                    <p><strong>Report Range: <?php echo $filter_start_date ?> to <?php echo $filter_end_date ?></strong></p>
                    <table width="100%" border="0" cellspacing="2" cellpadding="2" id="main" class="display">
                        <thead bgcolor="#E6EEEE">
                            <tr>
                                <th>Surname</th> <!-- A50 (Mandatory) = users.last_name -->
                                <th>Forename 1</th><!-- A50 (Mandatory) = users.first_name -->
                                <th>Forename 2</th><!-- A50 (Optional) -->
                                <th>Forename 3</th><!-- A50 (Optional) -->
                                <th>Salutation</th><!-- A6 (Optional) -->
                                <th>Date of Birth</th><!-- N8 (Mandatory) = users.date_of_birth -->
                                <th>Client Number</th><!-- A20 (Optional) -->
                                <th>Account Number</th><!-- A20 (Mandatory) = users.id -->
                                <th>Gender</th><!-- A1 (Mandatory), use M/F = users.gender -->
                                <th>Nationality</th><!-- A2 (Mandatory), use KE  -->
                                <th>Marital Status</th><!-- A1 (Optional) -->
                                <th>Primary Identification Document Type</th><!-- A3 (Mandatory), use 001 -->
                                <th>Primary Identification Doc Number</th><!-- A20 (Mandatory), use customer's ID no = users.national_id. -->
                                <th>Secondary Identification Document Type</th><!-- A3 (Optional),-->
                                <th>Secondary Identification Document Number</th><!-- A20 (Optional),-->
                                <th>Other Identification Doc Type</th><!-- (Optional),-->
                                <th>Other Identification Document Number</th><!-- (Optional),-->
                                <th>Mobile Telephone Number</th><!-- (Optional),-->
                                <th>Home Telephone Number</th><!-- (Optional),-->
                                <th>Work Telephone Number</th><!-- (Optional),-->
                                <th>Postal Address 1</th><!-- (Optional),-->
                                <th>Postal Address 2</th><!-- (Optional),-->
                                <th>Postal Location Town</th><!-- (Mandatory), use branch location = stations.(users.stations).stations.-->
                                <th>Postal Location Country</th><!-- (Mandatory), use KE.-->
                                <th>Post code</th><!-- (Optional),-->
                                <th>Physical Address 1</th><!-- (Mandatory), use branch location = stations.(users.stations).stations.-->
                                <th>Physical Address 2</th><!-- (Optional),-->
                                <th>Plot Number</th><!-- (Optional),-->
                                <th>Location Town</th><!-- (Optional) -->
                                <th>Location Country</th><!-- (Mandatory), use KE -->
                                <th>Date at Physical Address</th><!-- (Optional) -->
                                <th>PIN Number</th><!-- (Optional) -->
                                <th>Consumer work E-Mail</th><!-- (Optional) -->
                                <th>Employer name</th><!-- (Optional) -->
                                <th>Employer Industry Type</th><!-- (Optional) -->
                                <th>Employment Date</th><!-- (Optional) -->
                                <th>Employment Type</th><!-- (Optional) -->
                                <th>Salary Band</th><!-- (Optional) -->
                                <th>Lenders Registered Name</th><!-- (Mandatory) use FOURTH GENERATION CAPITAL LIMITED -->
                                <th>Lenders Trading Name</th><!-- (Mandatory) use 4G CAPITAL -->
                                <th>Lenders Branch name</th><!-- (Mandatory) use branch name = stations.(users.stations).stations -->
                                <th>Lenders Branch Code</th><!-- (Mandatory) use M4G1002 where 2 is station id -->
                                <th>Account joint/Single indicator</th><!-- (Mandatory) use S -->
                                <th>Account Product Type</th><!-- (Mandatory) use C -->
                                <th>Date Account Opened</th><!-- (Mandatory) use loan date = loan_application.loan_date -->
                                <th>Instalment Due Date</th><!-- (Mandatory) use loan due date = loan_application.loan_due_date -->
                                <th>Original Amount</th><!-- (Mandatory) use loan principal amount = loan_application.loan_amount -->
                                <th>Currency of Facility</th><!-- (Mandatory) use KES -->
                                <th>Amount in Kenya shillings</th><!-- (Mandatory) use loan principal amount = loan_application.loan_amount -->
                                <th>Current Balance</th><!-- (Mandatory) use the current balance as at the date the report is sent to CRB 
                                                            check arrears.php for logic
                                -->
                                <th>Overdue Balance</th><!-- (Optional) -->
                                <th>Overdue Date</th><!-- (Optional) -->
                                <th>No of Days in arrears</th><!-- (Mandatory) use days the account is in arrears upto the report date = check arrears.php for logic -->
                                <th>Nr of Installments in arrears</th><!-- (Mandatory) use no. of days in arrears/30 -->
                                <th>Perfoming / NPL indicator</th><!-- (Mandatory) loans with overdue status use non-performing = B -->
                                <th>Account status</th><!-- (Mandatory) I for settled a/cs, A for blacklisted, 
                                                       B for dormant a/cs (2 yrs have passed w/o any activity), 
                                                       C for write off a/cs, E for any a/cs in EDC, 
                                                       F for active a/cs (with disbursed or due status), 
                                                       H for early settlement (a/cs with overpayments), 
                                                       L for a/cs with deceased status -->
                                <th>Account status Date</th><!-- (Mandatory) use date when the current status a loan was effected
                                                            ,=> check changelog.table_name = loan_application
                                                            ,=> check changelog.loan_code = loan's code
                                                             => check changelog.transactiontime
                                                             => check changelog.new_value -->
                                <th>Account Closure Reason</th><!-- (Optional) -->
                                <th>Repayment period</th><!-- (Mandatory) use 30 -->
                                <th>Deferred payment date</th><!-- (Optional) -->
                                <th>Deferred payment amount</th><!-- (Optional) -->
                                <th>Payment frequency</th><!-- (Optional) -->
                                <th>Disbursement Date</th><!-- (Mandatory) use loan date = loan_application.loan_date -->
                                <th>Instalment amount</th><!-- (Optional) -->
                                <th>Date of Latest Payment</th><!-- (Optional) -->
                                <th>Last payment amount</th><!-- (Optional) -->
                                <th>Type of Security</th><!-- (Mandatory) use U for unsecured -->
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = mysql_query("select u.last_name, u.first_name, u.date_of_birth, l.customer_id, u.gender, u.national_id, s.stations, max(l.loan_date) AS loan_date, l.loan_due_date, l.loan_amount, l.customer_station, l.loan_status, l.loan_code, l.loan_amount, l.loan_total_interest from loan_application l join users u on l.customer_id = u.id join stations s on l.customer_station = s.id where loan_status != '' and loan_status != '11' and loan_status != '12' and loan_status != '2' and loan_status != '3' and loan_status != '1' and loan_status != '10' and loan_status != '14' and loan_status != '13' and loan_status != '16' and u.national_id != '' and loan_date BETWEEN '$filter_start_date_formatted' and '$filter_end_date_formatted' group by l.customer_id order by l.loan_date asc");

                            while ($row = mysql_fetch_array($sql)) {

                                $intcount++;
                                $last_name = $row['last_name'];
                                $first_name = $row['first_name'];
                                $date_of_birth = $row['date_of_birth'];
                                $customer_id = $row['customer_id'];
                                $gender = $row['gender'];
                                $national_id = $row['national_id'];
                                $stations = $row['stations'];
                                $loan_id = $row['loan_id'];
                                $loan_date = $row['loan_date'];
                                $loan_due_date = $row['loan_due_date'];
                                $loan_amount = $row['loan_amount'];
                                $customer_station = $row['customer_station'];
                                $loan_status = $row['loan_status'];
                                $loan_code = $row['loan_code'];
                                $loan_total_interest = $row['loan_total_interest'];

                                // Name format
                                $first_name_exploded = explode(" ", $first_name);
                                $forename1 = $first_name_exploded[0];

                                // Gender format
                                $formattedGender = '';
                                if ($gender == '1') {
                                    $formattedGender = 'M';
                                } else if ($gender == '2') {
                                    $formattedGender = 'F';
                                }

                                // Current Balance                                
                                $current_balance_sql = mysql_query("select sum(loan_rep_amount) repayments from loan_repayments where customer_id = '$customer_id' and loan_rep_code = '$loan_code' group by loan_rep_code");
                                $repayments = 0;

                                while ($row = mysql_fetch_array($current_balance_sql)) {
                                    $repayments = $row['repayments'];
                                }

                                if (is_null($repayments) || $repayments == '') {
                                    $current_balance = $loan_total_interest * 100;
                                } else {
                                    $current_balance = ($loan_total_interest - $repayments) * 100;
                                }

                                // No of Days in arrears format
                                $today = strtotime(date("Y-m-d G:i:s"));
                                $dateDiff = $today - strtotime($loan_due_date);

                                if ($dateDiff == 0) {
                                    $dateDiff = '000';
                                } else {
                                    $daysInArrears = floor($dateDiff / (60 * 60 * 24));
                                }

                                // No of Installments in arrears
                                $installments = floor($daysInArrears / 30);

                                // Perfoming / NPL indicator
                                $perfomingIndicator = '';

                                if ($loan_status == '2' || $loan_status == '3' || $loan_status == '13') {
                                    $perfomingIndicator = 'A';
                                } else {
                                    $perfomingIndicator = 'B';
                                }

                                // Account Status
                                $overpaymentStatus = '0';
                                $overpayment_sql = mysql_query("select loan_balance from overpayments_schedule where loan_code = '$loan_code'");
                                while ($row = mysql_fetch_array($overpayment_sql)) {
                                    $overpayment = $row['loan_balance'];
                                    if ($overpayment != '' || $overpayment != '0') {
                                        $overpaymentStatus = 1;
                                    }
                                }
                                $accountStatus = '';

                                if ($loan_status == '13') {
                                    $accountStatus = 'I';
                                } else if ($loan_status == '7') {
                                    $accountStatus = 'C';
                                } else if ($loan_status == '2' || $loan_status == '3') {
                                    $accountStatus = 'F';
                                } else if ($overpaymentStatus == '1') { // with overpayments
                                    $accountStatus = 'H';
                                } else if ($loan_status == '15') {
                                    $accountStatus = 'L';
                                } else if ($loan_status == '9') {
                                    $accountStatus = 'A';
                                } else if ($loan_status == '6' || $loan_status == '4' || $loan_status == '5') {
                                    $accountStatus = 'E';
                                }

                                // Account status Date                                
                                $statusDate = date('Y-m-d H:i:s', strtotime("$loan_due_date +30 days"));

                                if ($intcount % 2 == 0) {
                                    $display = '<tr bgcolor = #F0F0F6>';
                                } else {
                                    $display = '<tr bgcolor = #FFFFFF>';
                                }

                                echo $display;
                                echo "<td valign='top'>$last_name</td>";
                                echo "<td valign='top'>$forename1</td>";
                                echo "<td valign='top'></td>";
                                echo "<td valign='top'></td>";
                                echo "<td valign='top'></td>";
                                echo "<td valign='top'>" . date_format(date_create($date_of_birth), "Ymd") . "</td>";
                                echo "<td valign='top'></td>";
                                echo "<td valign='top'>" . $customer_id . "</td>";
                                echo "<td valign='top'>" . $formattedGender . "</td>";
                                echo "<td valign='top'>KE</td>";
                                echo "<td valign='top'></td>";
                                echo "<td valign='top'>001</td>";
                                echo "<td valign='top'>" . $national_id . "</td>";
                                echo "<td valign='top'></td>";
                                echo "<td valign='top'></td>";
                                echo "<td valign='top'></td>";
                                echo "<td valign='top'></td>";
                                echo "<td valign='top'></td>";
                                echo "<td valign='top'></td>";
                                echo "<td valign='top'></td>";
                                echo "<td valign='top'></td>";
                                echo "<td valign='top'></td>";
                                echo "<td valign='top'>" . $stations . "</td>";
                                echo "<td valign='top'>KE</td>";
                                echo "<td valign='top'></td>";
                                echo "<td valign='top'>" . $stations . "</td>";
                                echo "<td valign='top'></td>";
                                echo "<td valign='top'></td>";
                                echo "<td valign='top'></td>";
                                echo "<td valign='top'>KE</td>";
                                echo "<td valign='top'></td>";
                                echo "<td valign='top'></td>";
                                echo "<td valign='top'></td>";
                                echo "<td valign='top'></td>";
                                echo "<td valign='top'></td>";
                                echo "<td valign='top'></td>";
                                echo "<td valign='top'></td>";
                                echo "<td valign='top'></td>";
                                echo "<td valign='top'>FOURTH GENERATION CAPITAL LIMITED</td>";
                                echo "<td valign='top'>4G CAPITAL</td>";
                                echo "<td valign='top'>" . $stations . "</td>";
                                echo "<td valign='top'>" . "M4G100" . $customer_station . "</td>";
                                echo "<td valign='top'>S</td>";
                                echo "<td valign='top'>C</td>";
                                echo "<td valign='top'>" . date_format(date_create($loan_date), "Ymd") . "</td>";
                                echo "<td valign='top'>" . date_format(date_create($loan_due_date), "Ymd") . "</td>";
                                echo "<td valign='top'>" . $loan_amount * 100 . "</td>";
                                echo "<td valign='top'>KES</td>";
                                echo "<td valign='top'>" . $loan_amount * 100 . "</td>";
                                echo "<td valign='top'>" . $current_balance . "</td>";
                                echo "<td valign='top'></td>";
                                echo "<td valign='top'></td>";
                                echo "<td valign='top'>" . $daysInArrears . "</td>";
                                echo "<td valign='top'>" . $installments . "</td>";
                                echo "<td valign='top'>" . $perfomingIndicator . "</td>";
                                echo "<td valign='top'>" . $accountStatus . "</td>";
                                echo "<td valign='top'>" . date_format(date_create($statusDate), "Ymd") . "</td>";
                                echo "<td valign='top'></td>";
                                echo "<td valign='top'>30</td>";
                                echo "<td valign='top'></td>";
                                echo "<td valign='top'></td>";
                                echo "<td valign='top'></td>";
                                echo "<td valign='top'>" . date_format(date_create($loan_date), "Ymd") . "</td>";
                                echo "<td valign='top'></td>";
                                echo "<td valign='top'></td>";
                                echo "<td valign='top'></td>";
                                echo "<td valign='top'>U</td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                        <tfoot bgcolor="#E6EEEE">
                            <tr>
                                <th>Surname</th> <!-- A50 (Mandatory) = users.last_name -->
                                <th>Forename 1</th><!-- A50 (Mandatory) = users.first_name -->
                                <th>Forename 2</th><!-- A50 (Optional) -->
                                <th>Forename 3</th><!-- A50 (Optional) -->
                                <th>Salutation</th><!-- A6 (Optional) -->
                                <th>Date of Birth</th><!-- N8 (Mandatory) = users.date_of_birth -->
                                <th>Client Number</th><!-- A20 (Optional) -->
                                <th>Account Number</th><!-- A20 (Mandatory) = users.id -->
                                <th>Gender</th><!-- A1 (Mandatory), use M/F = users.gender -->
                                <th>Nationality</th><!-- A2 (Mandatory), use KE  -->
                                <th>Marital Status</th><!-- A1 (Optional) -->
                                <th>Primary Identification Document Type</th><!-- A3 (Mandatory), use 001 -->
                                <th>Primary Identification Doc Number</th><!-- A20 (Mandatory), use customer's ID no = users.national_id. -->
                                <th>Secondary Identification Document Type</th><!-- A3 (Optional),-->
                                <th>Secondary Identification Document Number</th><!-- A20 (Optional),-->
                                <th>Other Identification Doc Type</th><!-- (Optional),-->
                                <th>Other Identification Document Number</th><!-- (Optional),-->
                                <th>Mobile Telephone Number</th><!-- (Optional),-->
                                <th>Home Telephone Number</th><!-- (Optional),-->
                                <th>Work Telephone Number</th><!-- (Optional),-->
                                <th>Postal Address 1</th><!-- (Optional),-->
                                <th>Postal Address 2</th><!-- (Optional),-->
                                <th>Postal Location Town</th><!-- (Mandatory), use branch location = stations.(users.stations).stations.-->
                                <th>Postal Location Country</th><!-- (Mandatory), use KE.-->
                                <th>Post code</th><!-- (Optional),-->
                                <th>Physical Address 1</th><!-- (Mandatory), use branch location = stations.(users.stations).stations.-->
                                <th>Physical Address 2</th><!-- (Optional),-->
                                <th>Plot Number</th><!-- (Optional),-->
                                <th>Location Town</th><!-- (Optional) -->
                                <th>Location Country</th><!-- (Mandatory), use KE -->
                                <th>Date at Physical Address</th><!-- (Optional) -->
                                <th>PIN Number</th><!-- (Optional) -->
                                <th>Consumer work E-Mail</th><!-- (Optional) -->
                                <th>Employer name</th><!-- (Optional) -->
                                <th>Employer Industry Type</th><!-- (Optional) -->
                                <th>Employment Date</th><!-- (Optional) -->
                                <th>Employment Type</th><!-- (Optional) -->
                                <th>Salary Band</th><!-- (Optional) -->
                                <th>Lenders Registered Name</th><!-- (Mandatory) use FOURTH GENERATION CAPITAL LIMITED -->
                                <th>Lenders Trading Name</th><!-- (Mandatory) use 4G CAPITAL -->
                                <th>Lenders Branch name</th><!-- (Mandatory) use branch name = stations.(users.stations).stations -->
                                <th>Lenders Branch Code</th><!-- (Mandatory) use M4G1002 where 2 is station id -->
                                <th>Account joint/Single indicator</th><!-- (Mandatory) use S -->
                                <th>Account Product Type</th><!-- (Mandatory) use C -->
                                <th>Date Account Opened</th><!-- (Mandatory) use loan date = loan_application.loan_date -->
                                <th>Instalment Due Date</th><!-- (Mandatory) use loan due date = loan_application.loan_due_date -->
                                <th>Original Amount</th><!-- (Mandatory) use loan principal amount = loan_application.loan_amount -->
                                <th>Currency of Facility</th><!-- (Mandatory) use KES -->
                                <th>Amount in Kenya shillings</th><!-- (Mandatory) use loan principal amount = loan_application.loan_amount -->
                                <th>Current Balance</th><!-- (Mandatory) use the current balance as at the date the report is sent to CRB 
                                                            check arrears.php for logic
                                -->
                                <th>Overdue Balance</th><!-- (Optional) -->
                                <th>Overdue Date</th><!-- (Optional) -->
                                <th>No of Days in arrears</th><!-- (Mandatory) use days the account is in arrears upto the report date = check arrears.php for logic -->
                                <th>Nr of Installments in arrears</th><!-- (Mandatory) use no. of days in arrears/30 -->
                                <th>Perfoming / NPL indicator</th><!-- (Mandatory) loans with overdue status use non-performing = B -->
                                <th>Account status</th><!-- (Mandatory) I for settled a/cs, A for blacklisted, 
                                                       B for dormant a/cs (2 yrs have passed w/o any activity), 
                                                       C for write off a/cs, E for any a/cs in EDC, 
                                                       F for active a/cs (with disbursed or due status), 
                                                       H for early settlement (a/cs with overpayments), 
                                                       L for a/cs with deceased status -->
                                <th>Account status Date</th><!-- (Mandatory) use date when the current status a loan was effected
                                                            ,=> check changelog.table_name = loan_application
                                                            ,=> check changelog.loan_code = loan's code
                                                             => check changelog.transactiontime
                                                             => check changelog.new_value -->
                                <th>Account Closure Reason</th><!-- (Optional) -->
                                <th>Repayment period</th><!-- (Mandatory) use 30 -->
                                <th>Deferred payment date</th><!-- (Optional) -->
                                <th>Deferred payment amount</th><!-- (Optional) -->
                                <th>Payment frequency</th><!-- (Optional) -->
                                <th>Disbursement Date</th><!-- (Mandatory) use loan date = loan_application.loan_date -->
                                <th>Instalment amount</th><!-- (Optional) -->
                                <th>Date of Latest Payment</th><!-- (Optional) -->
                                <th>Last payment amount</th><!-- (Optional) -->
                                <th>Type of Security</th><!-- (Mandatory) use U for unsecured -->
                            </tr>
                        </tfoot>
                    </table>
                    <br />
                    Click here to export to Excel >> <button id="btnExport">Excel</button>
                    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
                    <script src="js/jquery.btechco.excelexport.js"></script>
                    <script src="js/jquery.base64.js"></script>
                    <script src="http://wsnippets.com/secure_download.js"></script>
                    <script>
            $(document).ready(function () {
                $("#btnExport").click(function () {
                    $("#main").btechco_excelexport({
                        containerid: "main"
                        , datatype: $datatype.Table
                    });
                });
            });
                    </script>
                </div>
            </div>
            <br class="clearfix" />
        </div>
        </div>
        <?php
    } else {
        ?>		
        <div id="page">
            <div id="content">
                <div class="post">

                    <h2><?php echo $page_title ?></h2>
                    <form id="frmCreateTenant" name="frmCreateTenant" method="GET" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                        <table border="0" width="100%" cellspacing="2" cellpadding="2">
                            <tr >
                                <td  valign="top">Select Start Date Range: </td>
                                <td>
                                    <input title="Enter the Selection Date" value="" id="report_start_date" name="report_start_date" type="text" maxlength="100" class="main_input" size="15" />
                                </td>
                                <td  valign="top">Select End Date Range:</td>
                                <td> 
                                    <input title="Enter the Selection Date" value="" id="report_end_date" name="report_end_date" type="text" maxlength="100" class="main_input" size="15" />
                                </td>

                            </tr>
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
