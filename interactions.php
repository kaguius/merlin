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
    if (!empty($_GET)) {
        $report_title = $_GET['report_title'];
        $filter_clerk = $_GET['clerk'];
        $report_start_date = $_GET['report_start_date'];
        $report_start_date = date('Y-m-d', strtotime(str_replace('-', '/', $report_start_date)));
        if($report_title == 'duration'){
            $report_end_date = $_GET['report_end_date_ptp_report'];
        }
        else{
            $report_end_date = $_GET['report_end_date'];
        }
        $report_end_date = date('Y-m-d', strtotime(str_replace('-', '/', $report_end_date)));
    }
    $report_start_date = $report_start_date.' 00:00:00';
	$report_end_date = $report_end_date.' 23:59:59';
    $filter_month = date("m");
    $filter_year = date("Y");
    $filter_day = date("d");
    $current_date = $filter_year . '-' . $filter_month . '-' . $filter_day;
    $add_days = 1;
    $tomorrow_date = date('Y-m-d', strtotime($current_date) + (24 * 3600 * $add_days));
    include_once('includes/db_conn.php');
    $transactiontime = date("Y-m-d G:i:s");
    $page_title = "Customer Interactions";
    include_once('includes/header.php');
    ?>		
    <div id="page">
        <div id="content">
            <div class="post">
                <h2><font color="#000A8B"><?php echo $page_title; ?></font></h2>
                Select a Report: <a href="interactions.php?report_title=due_today">Interactions due today</a> | <a href="interactions.php?report_title=due_tomorrow">Interactions due tomorrow</a> | <a href="interactions.php?report_title=duration">Interaction Listing per Period</a>
                <?php if ($report_title == 'due_today') { ?>
                    <h3>Interactions Due Today</h3>
                    <p><strong>Report Range: <?php echo $current_date ?></strong></p>
                    <table width="100%" border="0" cellspacing="2" cellpadding="2" class="display" id="example">
                        <thead bgcolor="#E6EEEE">
                            <tr>
                                <th>#</th>
                                <th>Call Date</th>
                                <th>Category</th>
                                <th>Branch</th>
                                <th>Mobile</th>
                                <th>Followup Date</th>
                                <th>Staff</th>
                                <th>Outcome</th>
                                <th>Comments</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($station == '3' || $userid == '31') {
                                $sql = mysql_query("select promise_to_pay.id, loan_code, users.mobile_no, vintage, overdue_days, customer_id, loan_balance, pay_date, comments, call_outcome, promise_to_pay.transactiontime, promise_to_pay.UID, users.stations, category from promise_to_pay inner join users on promise_to_pay.customer_id = users.id where pay_date = '$current_date' and customer_id != '0' and category != 'Promise to Pay' order by promise_to_pay.id asc");
                            } else if ($station == '4') {
                                $sql = mysql_query("select promise_to_pay.id, loan_code, users.mobile_no, vintage, overdue_days, customer_id, loan_balance, pay_date, comments, call_outcome, promise_to_pay.transactiontime, promise_to_pay.UID, users.stations, category from promise_to_pay inner join users on promise_to_pay.customer_id = users.id where pay_date = '$current_date' and promise_to_pay.UID = '$userid' and customer_id != '0' and category != 'Promise to Pay' order by promise_to_pay.id asc");
                            } else {
                                if ($title == '3') {
                                    $sql = mysql_query("select promise_to_pay.id, loan_code, users.mobile_no,  vintage, overdue_days, customer_id, loan_balance, pay_date, comments, call_outcome, promise_to_pay.transactiontime, promise_to_pay.UID, users.stations, category from promise_to_pay inner join users on promise_to_pay.customer_id = users.id where pay_date = '$current_date' and users.stations = '$station' and customer_id != '0' and category != 'Promise to Pay' order by promise_to_pay.id asc");
                                } else {
                                    $sql = mysql_query("select promise_to_pay.id, loan_code, users.mobile_no,  vintage, overdue_days, customer_id, loan_balance, pay_date, comments, call_outcome, promise_to_pay.transactiontime, promise_to_pay.UID, users.stations, category from promise_to_pay inner join users on promise_to_pay.customer_id = users.id where pay_date = '$current_date' and users.stations = '$station' and promise_to_pay.UID = '$userid' and customer_id != '0' and category != 'Promise to Pay' order by promise_to_pay.id asc");
                                }
                            }
                            $intcount = 0;
                            while ($row = mysql_fetch_array($sql)) {
                                $intcount++;
                                $id = $row['id'];
                                $loan_code = $row['loan_code'];
                                $mobile_no = $row['mobile_no'];
                                $customer_id = $row['customer_id'];
                                $loan_balance = $row['loan_balance'];
                                $pay_date = $row['pay_date'];
                                $UID = $row['UID'];
                                $category = $row['category'];
                                $call_outcome = $row['call_outcome'];
                                $transactiontime = $row['transactiontime'];
                                $vintage = $row['vintage'];
                                $stations = $row['stations'];
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
                                $sql2 = mysql_query("select id, stations from stations where id = '$stations'");
                                while ($row = mysql_fetch_array($sql2)) {
                                    $stations = $row['stations'];
                                    $stations = ucwords(strtolower($stations));
                                }

                                if ($intcount % 2 == 0) {
                                    $display = '<tr bgcolor = #F0F0F6>';
                                } else {
                                    $display = '<tr>';
                                }
                                echo $display;
                                echo "<td valign='top'>$intcount.</td>";
                                echo "<td valign='top'>$transactiontime</td>";
                                echo "<td valign='top'>$category</td>";
                                echo "<td valign='top'>$stations</td>";
                                echo "<td valign='top'>$mobile_no</td>";
                                echo "<td valign='top'>$pay_date</td>";
                                echo "<td valign='top'>$staff_name</td>";
                                echo "<td valign='top'>$reason_code</td>";
                                echo "<td valign='top'>$comments</td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                        <tfoot bgcolor="#E6EEEE">
                            <tr>
                                <th>#</th>
                                <th>Call Date</th>
                                <th>Category</th>
                                <th>Branch</th>
                                <th>Mobile</th>
                                <th>Followup Date</th>
                                <th>Staff</th>
                                <th>Outcome</th>
                                <th>Comments</th>
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
                $("#example").btechco_excelexport({
                    containerid: "example"
                    , datatype: $datatype.Table
                });
            });
        });
                    </script>
                <?php } else if ($report_title == 'due_tomorrow') { ?>
                    <h3>Interactions Due Tomorrow</h3>
                    <p><strong>Report Range: <?php echo $tomorrow_date ?></strong></p>
                    <table width="100%" border="0" cellspacing="2" cellpadding="2" class="display" id="example">
                        <thead bgcolor="#E6EEEE">
                            <tr>
                                <th>#</th>
                                <th>Call Date</th>
                                <th>Category</th>
                                <th>Branch</th>
                                <th>Mobile</th>
                                <th>Followup Date</th>
                                <th>Staff</th>
                                <th>Outcome</th>
                                <th>Comments</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($station == '3' || $userid == '31') {
                                $sql = mysql_query("select promise_to_pay.id, loan_code, users.mobile_no,  vintage, overdue_days, customer_id, loan_balance, pay_date, comments, call_outcome, promise_to_pay.transactiontime, promise_to_pay.UID, users.stations, category from promise_to_pay inner join users on promise_to_pay.customer_id = users.id where pay_date = '$tomorrow_date' and customer_id != '0' and category != 'Promise to Pay' order by promise_to_pay.id asc");
                            } else if ($station == '4') {
                                $sql = mysql_query("select promise_to_pay.id, loan_code, users.mobile_no,  vintage, overdue_days, customer_id, loan_balance, pay_date, comments, call_outcome, promise_to_pay.transactiontime, promise_to_pay.UID, users.stations, category from promise_to_pay inner join users on promise_to_pay.customer_id = users.id where pay_date = '$tomorrow_date' and customer_id != '0' and promise_to_pay.UID = '$userid' and category != 'Promise to Pay' order by promise_to_pay.id asc");
                            } else {
                                if ($title == '3') {
                                    $sql = mysql_query("select promise_to_pay.id, loan_code, users.mobile_no,  vintage, overdue_days, customer_id, loan_balance, pay_date, comments, call_outcome, promise_to_pay.transactiontime, promise_to_pay.UID, users.stations, category from promise_to_pay inner join users on promise_to_pay.customer_id = users.id where pay_date = '$tomorrow_date' and users.stations = '$station' and customer_id != '0' and category != 'Promise to Pay' order by promise_to_pay.id asc");
                                } else {
                                    $sql = mysql_query("select promise_to_pay.id, loan_code, users.mobile_no,  vintage, overdue_days, customer_id, loan_balance, pay_date, comments, call_outcome, promise_to_pay.transactiontime, promise_to_pay.UID, users.stations, category from promise_to_pay inner join users on promise_to_pay.customer_id = users.id where pay_date = '$tomorrow_date' and users.stations = '$station' and promise_to_pay.UID = '$userid' and customer_id != '0' and category != 'Promise to Pay' order by promise_to_pay.id asc");
                                }
                            }
                            $intcount = 0;
                            while ($row = mysql_fetch_array($sql)) {
                                $intcount++;
                                $id = $row['id'];
                                $loan_code = $row['loan_code'];
                                $mobile_no = $row['mobile_no'];
                                $customer_id = $row['customer_id'];
                                $loan_balance = $row['loan_balance'];
                                $pay_date = $row['pay_date'];
                                $UID = $row['UID'];
                                $category = $row['category'];
                                $call_outcome = $row['call_outcome'];
                                $transactiontime = $row['transactiontime'];
                                $vintage = $row['vintage'];
                                $stations = $row['stations'];
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
                                $sql2 = mysql_query("select id, stations from stations where id = '$stations'");
                                while ($row = mysql_fetch_array($sql2)) {
                                    $stations = $row['stations'];
                                    $stations = ucwords(strtolower($stations));
                                }

                                if ($intcount % 2 == 0) {
                                    $display = '<tr bgcolor = #F0F0F6>';
                                } else {
                                    $display = '<tr>';
                                }
                                echo $display;
                                echo "<td valign='top'>$intcount.</td>";
                                echo "<td valign='top'>$transactiontime</td>";
                                echo "<td valign='top'>$category</td>";
                                echo "<td valign='top'>$stations</td>";
                                echo "<td valign='top'>$mobile_no</td>";
                                echo "<td valign='top'>$pay_date</td>";
                                echo "<td valign='top'>$staff_name</td>";
                                echo "<td valign='top'>$reason_code</td>";
                                echo "<td valign='top'>$comments</td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                        <tfoot bgcolor="#E6EEEE">
                            <tr>
                                <th>#</th>
                                <th>Call Date</th>
                                <th>Category</th>
                                <th>Branch</th>
                                <th>Mobile</th>
                                <th>Followup Date</th>
                                <th>Staff</th>
                                <th>Outcome</th>
                                <th>Comments</th>
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
                $("#example").btechco_excelexport({
                    containerid: "example"
                    , datatype: $datatype.Table
                });
            });
        });
                    </script>
                <?php } else if ($report_title == 'honoured') { ?>
                    <h3>Interactions Honoured</h3>
                    <?php if ($report_start_date != "" && $report_end_date != "" && $report_start_date != '1970-01-01 00:00:00' && $report_end_date != '1970-01-01 23:59:59 ') { ?>
                        <p><strong>Report Range: <?php echo $report_start_date ?> to <?php echo $report_end_date ?></strong></p>
                        <table width="100%" border="0" cellspacing="2" cellpadding="2" class="display" id="example">
                            <thead bgcolor="#E6EEEE">
                                <tr>
                                    <th>#</th>
                                    <th>Call Date</th>
                                    <th>Vintage</th>
                                    <th>Branch</th>
                                    <th>Days</th>
                                    <th>Loan Code</th>
                                    <th>Mobile</th>
                                    <th>Pay Date</th>
                                    <th>Staff</th>
                                    <th>Outcome</th>
                                    <th>Payments</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($station == '3' || $userid == '31') {
                                    $sql = mysql_query("select promise_to_pay.id, promise_to_pay.loan_code, users.mobile_no,  promise_to_pay.vintage, overdue_days, promise_to_pay.customer_id, loan_balance, pay_date, comments, call_outcome, promise_to_pay.transactiontime, promise_to_pay.UID, users.stations from promise_to_pay inner join users on promise_to_pay.customer_id = users.id inner join loan_application on loan_application.loan_code = promise_to_pay.loan_code where loan_status = '13' and promise_to_pay.transactiontime between '$report_start_date' and '$report_end_date' and category != 'Promise to Pay' group by promise_to_pay.loan_code order by promise_to_pay.id asc");
                                } else if ($station == '4') {
                                    $sql = mysql_query("select promise_to_pay.id, promise_to_pay.loan_code, users.mobile_no,  promise_to_pay.vintage, overdue_days, promise_to_pay.customer_id, loan_balance, pay_date, comments, call_outcome, promise_to_pay.transactiontime, promise_to_pay.UID, users.stations from promise_to_pay inner join users on promise_to_pay.customer_id = users.id inner join loan_application on loan_application.loan_code = promise_to_pay.loan_code where loan_status = '13' and promise_to_pay.UID = '$userid' and promise_to_pay.transactiontime between '$report_start_date' and '$report_end_date' and category != 'Promise to Pay' group by promise_to_pay.loan_code order by promise_to_pay.id asc");
                                } else {
                                    if ($title == '3') {
                                        $sql = mysql_query("select promise_to_pay.id, promise_to_pay.loan_code, users.mobile_no,  promise_to_pay.vintage, overdue_days, promise_to_pay.customer_id, loan_balance, pay_date, comments, call_outcome, promise_to_pay.transactiontime, promise_to_pay.UID, users.stations from promise_to_pay inner join users on promise_to_pay.customer_id = users.id inner join loan_application on loan_application.loan_code = promise_to_pay.loan_code where loan_status = '13' and users.stations = '$station' and promise_to_pay.transactiontime between '$report_start_date' and '$report_end_date' and category != 'Promise to Pay' group by promise_to_pay.loan_code order by promise_to_pay.id asc");
                                    } else {
                                        $sql = mysql_query("select promise_to_pay.id, promise_to_pay.loan_code, users.mobile_no,  promise_to_pay.vintage, overdue_days, promise_to_pay.customer_id, loan_balance, pay_date, comments, call_outcome, promise_to_pay.transactiontime, promise_to_pay.UID, users.stations from promise_to_pay inner join users on promise_to_pay.customer_id = users.id inner join loan_application on loan_application.loan_code = promise_to_pay.loan_code where loan_status = '13' and users.stations = '$station' and promise_to_pay.UID = '$userid' and promise_to_pay.transactiontime between '$report_start_date' and '$report_end_date' and category != 'Promise to Pay' group by promise_to_pay.loan_code order by promise_to_pay.id asc");
                                    }
                                }
                                $intcount = 0;
                                while ($row = mysql_fetch_array($sql)) {
                                    $intcount++;
                                    $id = $row['id'];
                                    $loan_code = $row['loan_code'];
                                    $mobile_no = $row['mobile_no'];
                                    $customer_id = $row['customer_id'];
                                    $loan_balance = $row['loan_balance'];
                                    $pay_date = $row['pay_date'];
                                    $UID = $row['UID'];
                                    $call_outcome = $row['call_outcome'];
                                    $transactiontime = $row['transactiontime'];
                                    $vintage = $row['vintage'];
                                    $stations = $row['stations'];
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
                                    $sql2 = mysql_query("select id, stations from stations where id = '$stations'");
                                    while ($row = mysql_fetch_array($sql2)) {
                                        $stations = $row['stations'];
                                        $stations = ucwords(strtolower($stations));
                                    }
                                    $sql2 = mysql_query("select sum(loan_rep_amount)repayments from loan_repayments where loan_rep_code = '$loan_code'");
                                    while ($row = mysql_fetch_array($sql2)) {
                                        $repayments = $row['repayments'];
                                        //$stations = ucwords(strtolower($stations));
                                    }

                                    if ($intcount % 2 == 0) {
                                        $display = '<tr bgcolor = #F0F0F6>';
                                    } else {
                                        $display = '<tr>';
                                    }
                                    echo $display;
                                    echo "<td valign='top'>$intcount.</td>";
                                    echo "<td valign='top'>$transactiontime</td>";
                                    echo "<td valign='top'>$vintage</td>";
                                    echo "<td valign='top'>$stations</td>";
                                    echo "<td valign='top'>$overdue_days</td>";
                                    echo "<td valign='top'>$loan_code</td>";
                                    echo "<td valign='top'>$mobile_no</td>";
                                    echo "<td valign='top'>$pay_date</td>";
                                    echo "<td valign='top'>$staff_name</td>";
                                    echo "<td valign='top'>$reason_code</td>";
                                    echo "<td valign='top'>" . number_format($repayments, 2) . "</td>";
                                    echo "</tr>";
                                }
                                ?>
                            </tbody>
                            <tfoot bgcolor="#E6EEEE">
                                <tr>
                                    <th>#</th>
                                    <th>Call Date</th>
                                    <th>Vintage</th>
                                    <th>Branch</th>
                                    <th>Days</th>
                                    <th>Loan Code</th>
                                    <th>Mobile</th>
                                    <th>Pay Date</th>
                                    <th>Staff</th>
                                    <th>Outcome</th>
                                    <th>Payments</th>
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
                $("#example").btechco_excelexport({
                    containerid: "example"
                    , datatype: $datatype.Table
                });
            });
            });
                        </script>
                    <?php } else { ?>
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
                                <input title="Enter the Selection Date" value="honoured" hidden id="report_title" name="report_title" type="text" maxlength="100" class="main_input" size="15" />
                                </tr>
                                <tr>
                                    <td><button name="btnNewCard" id="button">Search</button></td>
                                </tr>
                            </table>
                        </form>
                    <?php } ?>
                <?php } else if ($report_title == 'broken') { ?>
                    <h3>Interactions Broken</h3>
                    <?php if ($report_start_date != "" && $report_end_date != "" && $report_start_date != '1970-01-01 00:00:00' && $report_end_date != '1970-01-01 23:59:59 ') { ?>
                        <p><strong>Report Range: <?php echo $report_start_date ?> to <?php echo $report_end_date ?></strong></p>
                        <table width="100%" border="0" cellspacing="2" cellpadding="2" class="display" id="example">
                            <thead bgcolor="#E6EEEE">
                                <tr>
                                    <th>#</th>
                                    <th>Call Date</th>
                                    <th>Vintage</th>
                                    <th>Branch</th>
                                    <th>Days</th>
                                    <th>Loan Code</th>
                                    <th>Mobile</th>
                                    <th>Pay Date</th>
                                    <th>Staff</th>
                                    <th>Outcome</th>
                                    <th>Payments</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($station == '3' || $userid == '31') {
                                    $sql = mysql_query("select promise_to_pay.id, promise_to_pay.loan_code, users.mobile_no,  promise_to_pay.vintage, overdue_days, promise_to_pay.customer_id, loan_balance, pay_date, comments, call_outcome, promise_to_pay.transactiontime, promise_to_pay.UID, users.stations from promise_to_pay inner join users on promise_to_pay.customer_id = users.id inner join loan_application on loan_application.loan_code = promise_to_pay.loan_code where loan_status != '13' and  promise_to_pay.pay_date between '$report_start_date' and '$report_end_date' and category != 'Promise to Pay' group by promise_to_pay.loan_code order by promise_to_pay.id asc");
                                } else if ($station == '4') {
                                    $sql = mysql_query("select promise_to_pay.id, promise_to_pay.loan_code, users.mobile_no,  promise_to_pay.vintage, overdue_days, promise_to_pay.customer_id, loan_balance, pay_date, comments, call_outcome, promise_to_pay.transactiontime, promise_to_pay.UID, users.stations from promise_to_pay inner join users on promise_to_pay.customer_id = users.id inner join loan_application on loan_application.loan_code = promise_to_pay.loan_code where loan_status != '13' and promise_to_pay.UID = '$userid' and promise_to_pay.pay_date between '$report_start_date' and '$report_end_date' and category != 'Promise to Pay' group by promise_to_pay.loan_code order by promise_to_pay.id asc");
                                } else {
                                    if ($title == '3') {
                                        $sql = mysql_query("select promise_to_pay.id, promise_to_pay.loan_code, users.mobile_no,  promise_to_pay.vintage, overdue_days, promise_to_pay.customer_id, loan_balance, pay_date, comments, call_outcome, promise_to_pay.transactiontime, promise_to_pay.UID, users.stations from promise_to_pay inner join users on promise_to_pay.customer_id = users.id inner join loan_application on loan_application.loan_code = promise_to_pay.loan_code where loan_status != '13' and users.stations = '$station' and promise_to_pay.pay_date between '$report_start_date' and '$report_end_date' and category != 'Promise to Pay' group by promise_to_pay.loan_code order by promise_to_pay.id asc");
                                    } else {
                                        $sql = mysql_query("select promise_to_pay.id, promise_to_pay.loan_code, users.mobile_no,  promise_to_pay.vintage, overdue_days, promise_to_pay.customer_id, loan_balance, pay_date, comments, call_outcome, promise_to_pay.transactiontime, promise_to_pay.UID, users.stations from promise_to_pay inner join users on promise_to_pay.customer_id = users.id inner join loan_application on loan_application.loan_code = promise_to_pay.loan_code where loan_status != '13' and users.stations = '$station' and promise_to_pay.UID = '$userid' and promise_to_pay.pay_date between '$report_start_date' and '$report_end_date' and category != 'Promise to Pay' group by promise_to_pay.loan_code order by promise_to_pay.id asc");
                                    }
                                }
                                $intcount = 0;
                                while ($row = mysql_fetch_array($sql)) {
                                    $intcount++;
                                    $id = $row['id'];
                                    $loan_code = $row['loan_code'];
                                    $mobile_no = $row['mobile_no'];
                                    $customer_id = $row['customer_id'];
                                    $loan_balance = $row['loan_balance'];
                                    $pay_date = $row['pay_date'];
                                    $UID = $row['UID'];
                                    $call_outcome = $row['call_outcome'];
                                    $transactiontime = $row['transactiontime'];
                                    $vintage = $row['vintage'];
                                    $stations = $row['stations'];
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
                                    $sql2 = mysql_query("select id, stations from stations where id = '$stations'");
                                    while ($row = mysql_fetch_array($sql2)) {
                                        $stations = $row['stations'];
                                        $stations = ucwords(strtolower($stations));
                                    }
                                    $sql2 = mysql_query("select sum(loan_rep_amount)repayments from loan_repayments where loan_rep_code = '$loan_code'");
                                    while ($row = mysql_fetch_array($sql2)) {
                                        $repayments = $row['repayments'];
                                        //$stations = ucwords(strtolower($stations));
                                    }

                                    if ($intcount % 2 == 0) {
                                        $display = '<tr bgcolor = #F0F0F6>';
                                    } else {
                                        $display = '<tr>';
                                    }
                                    echo $display;
                                    echo "<td valign='top'>$intcount.</td>";
                                    echo "<td valign='top'>$transactiontime</td>";
                                    echo "<td valign='top'>$vintage</td>";
                                    echo "<td valign='top'>$stations</td>";
                                    echo "<td valign='top'>$overdue_days</td>";
                                    echo "<td valign='top'>$loan_code</td>";
                                    echo "<td valign='top'>$mobile_no</td>";
                                    echo "<td valign='top'>$pay_date</td>";
                                    echo "<td valign='top'>$staff_name</td>";
                                    echo "<td valign='top'>$reason_code</td>";
                                    echo "<td valign='top'>" . number_format($repayments, 2) . "</td>";
                                    echo "</tr>";
                                }
                                ?>
                            </tbody>
                            <tfoot bgcolor="#E6EEEE">
                                <tr>
                                    <th>#</th>
                                    <th>Call Date</th>
                                    <th>Vintage</th>
                                    <th>Branch</th>
                                    <th>Days</th>
                                    <th>Loan Code</th>
                                    <th>Mobile</th>
                                    <th>Pay Date</th>
                                    <th>Staff</th>
                                    <th>Outcome</th>
                                    <th>Payments</th>
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
                            $("#example").btechco_excelexport({
                                containerid: "example"
                                , datatype: $datatype.Table
                            });
                        });
                        });
                        </script>
                    <?php } else { ?>
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
                                <input title="Enter the Selection Date" value="broken" hidden id="report_title" name="report_title" type="text" maxlength="100" class="main_input" size="15" />
                                </tr>
                                <tr>
                                    <td><button name="btnNewCard" id="button">Search</button></td>
                                </tr>
                            </table>
                        </form>
                    <?php } ?>
                <?php } else if ($report_title == 'duration') { ?>
                    <h3>Interactions per Duration</h3>
                    <?php if ($report_start_date != "" && $report_end_date != "" && $report_start_date != '1970-01-01 00:00:00' && $report_end_date != '1970-01-01 23:59:59 ') { ?>
                        <p><strong>Report Range: <?php echo $report_start_date ?> to <?php echo $report_end_date ?></strong></p>
                        <table width="100%" border="0" cellspacing="2" cellpadding="2" class="display" id="example">
                            <thead bgcolor="#E6EEEE">
                                <tr>
                                    <th>#</th>
                                    <th>Call Date</th>
                                    <th>Category</th>
                                    <th>Branch</th>
                                    <th>Mobile</th>
                                    <th>Followup Date</th>
                                    <th>Staff</th>
                                    <th>Outcome</th>
                                    <th>Comments</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($station == '3' || $userid == '31') {
                                    $sql = mysql_query("select promise_to_pay.id, promise_to_pay.loan_code, users.mobile_no,  promise_to_pay.vintage, overdue_days, promise_to_pay.customer_id, loan_balance, pay_date, comments, call_outcome, promise_to_pay.transactiontime, promise_to_pay.UID, users.stations, category from promise_to_pay inner join users on promise_to_pay.customer_id = users.id inner join loan_application on loan_application.loan_code = promise_to_pay.loan_code where loan_status != '13' and  promise_to_pay.transactiontime between '$report_start_date' and '$report_end_date' and category != 'Promise to Pay' group by promise_to_pay.loan_code order by promise_to_pay.id asc");
                                } else if ($station == '4') {
                                    $sql = mysql_query("select promise_to_pay.id, promise_to_pay.loan_code, users.mobile_no,  promise_to_pay.vintage, overdue_days, promise_to_pay.customer_id, loan_balance, pay_date, comments, call_outcome, promise_to_pay.transactiontime, promise_to_pay.UID, users.stations, category from promise_to_pay inner join users on promise_to_pay.customer_id = users.id inner join loan_application on loan_application.loan_code = promise_to_pay.loan_code where loan_status != '13' and promise_to_pay.UID = '$userid' and promise_to_pay.transactiontime between '$report_start_date' and '$report_end_date' and category != 'Promise to Pay' group by promise_to_pay.loan_code order by promise_to_pay.id asc");
                                } else {
                                    if ($title == '3') {
                                        $sql = mysql_query("select promise_to_pay.id, promise_to_pay.loan_code, users.mobile_no,  promise_to_pay.vintage, overdue_days, promise_to_pay.customer_id, loan_balance, pay_date, comments, call_outcome, promise_to_pay.transactiontime, promise_to_pay.UID, users.stations, category from promise_to_pay inner join users on promise_to_pay.customer_id = users.id inner join loan_application on loan_application.loan_code = promise_to_pay.loan_code where loan_status != '13' and users.stations = '$station' and promise_to_pay.transactiontime between '$report_start_date' and '$report_end_date' and category != 'Promise to Pay' group by promise_to_pay.loan_code order by promise_to_pay.id asc");
                                    } else {
                                        $sql = mysql_query("select promise_to_pay.id, promise_to_pay.loan_code, users.mobile_no,  promise_to_pay.vintage, overdue_days, promise_to_pay.customer_id, loan_balance, pay_date, comments, call_outcome, promise_to_pay.transactiontime, promise_to_pay.UID, users.stations, category from promise_to_pay inner join users on promise_to_pay.customer_id = users.id inner join loan_application on loan_application.loan_code = promise_to_pay.loan_code where loan_status != '13' and users.stations = '$station' and promise_to_pay.UID = '$userid' and promise_to_pay.transactiontime between '$report_start_date' and '$report_end_date' and category != 'Promise to Pay' group by promise_to_pay.loan_code order by promise_to_pay.id asc");
                                    }
                                }
                                $intcount = 0;
                                while ($row = mysql_fetch_array($sql)) {
                                    $intcount++;
                                    $id = $row['id'];
                                    $loan_code = $row['loan_code'];
                                    $mobile_no = $row['mobile_no'];
                                    $customer_id = $row['customer_id'];
                                    $loan_balance = $row['loan_balance'];
                                    $pay_date = $row['pay_date'];
                                    $UID = $row['UID'];
                                    $category = $row['category'];
                                    $comments = $row['comments'];
                                    $call_outcome = $row['call_outcome'];
                                    $transactiontime = $row['transactiontime'];
                                    $vintage = $row['vintage'];
                                    $stations = $row['stations'];
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
                                    $sql2 = mysql_query("select id, stations from stations where id = '$stations'");
                                    while ($row = mysql_fetch_array($sql2)) {
                                        $stations = $row['stations'];
                                        $stations = ucwords(strtolower($stations));
                                    }
                                    $sql2 = mysql_query("select sum(loan_rep_amount)repayments from loan_repayments where loan_rep_code = '$loan_code'");
                                    while ($row = mysql_fetch_array($sql2)) {
                                        $repayments = $row['repayments'];
                                        //$stations = ucwords(strtolower($stations));
                                    }

                                    if ($intcount % 2 == 0) {
                                        $display = '<tr bgcolor = #F0F0F6>';
                                    } else {
                                        $display = '<tr>';
                                    }
                                    echo $display;
                                    echo "<td valign='top'>$intcount.</td>";
                                    echo "<td valign='top'>$transactiontime</td>";
                                    echo "<td valign='top'>$category</td>";
                                    echo "<td valign='top'>$stations</td>";
                                    echo "<td valign='top'>$mobile_no</td>";
                                    echo "<td valign='top'>$pay_date</td>";
                                    echo "<td valign='top'>$staff_name</td>";
                                    echo "<td valign='top'>$reason_code</td>";
                                    echo "<td valign='top'>$comments</td>";
                                    echo "</tr>";
                                }
                                ?>
                            </tbody>
                            <tfoot bgcolor="#E6EEEE">
                                <tr>
                                    <th>#</th>
                                    <th>Call Date</th>
                                    <th>Category</th>
                                    <th>Branch</th>
                                    <th>Mobile</th>
                                    <th>Followup Date</th>
                                    <th>Staff</th>
                                    <th>Outcome</th>
                                    <th>Comments</th>
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
                            $("#example").btechco_excelexport({
                                containerid: "example"
                                , datatype: $datatype.Table
                            });
                        });
                        });
                        </script>
                    <?php } else { ?>
                        <form id="frmCreateTenant" name="frmCreateTenant" method="GET" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                            <table border="0" width="100%" cellspacing="2" cellpadding="2">
                                <tr >
                                    <td  valign="top">Select Start Date Range: </td>
                                    <td>
                                        <input title="Enter the Selection Date" value="" id="report_start_date" name="report_start_date" type="text" maxlength="100" class="main_input" size="15" />
                                    </td>
                                    <td  valign="top">Select End Date Range:</td>
                                    <td> 
                                        <input title="Enter the Selection Date" value="" id="report_end_date_ptp_report" name="report_end_date_ptp_report" type="text" maxlength="100" class="main_input" size="15" />
                                    </td>
                                <input title="Enter the Selection Date" value="duration" hidden id="report_title" name="report_title" type="text" maxlength="100" class="main_input" size="15" />
                                </tr>
                                <tr>
                                    <td><button name="btnNewCard" id="button">Search</button></td>
                                </tr>
                            </table>
                        </form>
                    <?php } ?>
                <?php } ?>
            </div>
        </div>
        <br class="clearfix" />
    </div>
    </div>
    <?php
}
include_once('includes/footer.php');
?>
