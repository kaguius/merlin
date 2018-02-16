<?php
$userid = "";
$adminstatus = 4;
$property_manager_id = "";
session_start();
if (!empty($_SESSION)) {
    $userid = $_SESSION["userid"];
    $adminstatus = $_SESSION["adminstatus"];
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
    $transactiontime = date("Y-m-d G:i:s");
    $page_title = "Agent Collections Grouping Report";
    include_once('includes/header.php');
    $filter_month = date("m");
    $filter_year = date("Y");
    $filter_day = date("d");
    //$filter_day = 16;
    $current_date = $filter_year . '-' . $filter_month . '-' . $filter_day;    
    
    $filter_clerk = 0;
    if (!empty($_GET)) {
        $agent = $_GET['agent'];
        $filter_start_date = $_GET['report_start_date'];
        $filter_start_date = date('Y-m-d', strtotime(str_replace('-', '/', $filter_start_date)));
        $filter_end_date = $_GET['report_end_date'];
        $filter_end_date = date('Y-m-d', strtotime(str_replace('-', '/', $filter_end_date)));
    }
    include_once('includes/db_conn.php');
    include_once('includes/db_conn_dialer.php');

    if ($filter_start_date != "" && $filter_end_date != "") {
        $current_date_full = date("d M, Y", strtotime($current_date));
        ?>
        <div id="page">
            <div id="content">
                <div class="post">
                    <h2><?php echo $page_title ?></h2>
                    <p><strong>Report Range : <?php echo $filter_start_date ?> to <?php echo $filter_end_date ?></strong></p>
                    <h3>Total Collected per Collections Agent for the Day: <?php echo $current_date_full ?></h3>
                    <table width="100%" border="0" cellspacing="2" cellpadding="2" class="display" id="example">
                        <thead bgcolor="#E6EEEE">
                            <tr>
                                <th>#</th>
                                <th>Collections Agent</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $intcount = 0;
                            $total_loan_rep_amount_cc = 0;
                            $loan_rep_amount_cc = 0;

                            // Get a list of collections agents
                            $sql_collections_agent = mysql_query("select id from user_profiles where title = 7 and user_status = 1");
                            while ($row = mysql_fetch_array($sql_collections_agent)) {
                                $collections_agent = $row['id'];

                                //$sql = mysql_query("select distinct loan_application.collections_agent, loan_rep_date, loan_rep_code, sum(loan_rep_amount)loan_rep_amount from loan_repayments inner join loan_application on loan_application.loan_code = loan_repayments.loan_rep_code where loan_rep_date between '$current_date' and '$current_date' and collections_agent != '' group by collections_agent");
                                $sql_get_payments = mysql_query("select distinct lr.current_collector,lr.loan_rep_date, lr.loan_rep_code, sum(lr.loan_rep_amount)loan_rep_amount from loan_repayments lr inner join loan_application l on l.loan_code = lr.loan_rep_code where lr.loan_rep_date = '$current_date' and lr.current_collector = '$collections_agent'  group by lr.current_collector");
                                while ($row = mysql_fetch_array($sql_get_payments)) {
                                    $intcount++;
                                    $collections_agent = $row['current_collector'];
                                    $loan_rep_amount_cc = $row['loan_rep_amount'];

                                    $sql3 = mysql_query("select first_name, last_name from user_profiles where id = '$collections_agent'");
                                    while ($row = mysql_fetch_array($sql3)) {
                                        $first_name = $row['first_name'];
                                        $last_name = $row['last_name'];
                                        $collections_agent_name = $first_name . ' ' . $last_name;
                                    }

                                    if ($intcount % 2 == 0) {
                                        $display = '<tr bgcolor = #F0F0F6>';
                                    } else {
                                        $display = '<tr>';
                                    }
                                    echo $display;
                                    echo "<td valign='top'>$intcount.</td>";
                                    echo "<td valign='top'>$collections_agent_name</td>";
                                    echo "<td align='right' valign='top'>" . number_format($loan_rep_amount_cc, 2) . "</td>";
                                    echo "</tr>";

                                    $total_loan_rep_amount_cc = $total_loan_rep_amount_cc + $loan_rep_amount_cc;
                                    $total_projection = $total_projection + $projection;

                                    $projection = 0;
                                }
                                $collections_agent = 0;
                            }
                            $total_rate = ($total_loan_rep_amount_cc / $total_projection) * 100;
                            ?>
                        </tbody>
                        <tr bgcolor = '#E6EEEE'>
                            <td colspan='2'><strong>&nbsp;</strong></td>
                            <td align='right' valign='top'><strong><?php echo number_format($total_loan_rep_amount_cc, 2) ?></strong></td>
                        </tr>
                    </table>
                    <br />
                    <br />

                    <h3>Total Collected per Field Agent for the Day: <?php echo $current_date_full ?></h3>
                    <table width="100%" border="0" cellspacing="2" cellpadding="2" class="display" id="example">
                        <thead bgcolor="#E6EEEE">
                            <tr>
                                <th>#</th>
                                <th>Field Agent</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $intcount = 0;
                            $total_loan_rep_amount_fa = 0;
                            $loan_rep_amount_fa = 0;

                            $sql_field_agent = mysql_query("select id from user_profiles where title = 10 and user_status = 1");
                            while ($row = mysql_fetch_array($sql_field_agent)) {
                                $field_agent = $row['id'];

                                //$sql = mysql_query("select distinct loan_application.collections_agent, loan_rep_date, loan_rep_code, sum(loan_rep_amount)loan_rep_amount from loan_repayments inner join loan_application on loan_application.loan_code = loan_repayments.loan_rep_code where loan_rep_date between '$current_date' and '$current_date' and collections_agent != '' group by collections_agent");
                                $sql_get_payments = mysql_query("select distinct lr.current_collector,lr.loan_rep_date, lr.loan_rep_code, sum(lr.loan_rep_amount)loan_rep_amount from loan_repayments lr inner join loan_application l on l.loan_code = lr.loan_rep_code where lr.loan_rep_date = '$current_date' and lr.current_collector = '$field_agent' group by lr.current_collector");
                                while ($row = mysql_fetch_array($sql_get_payments)) {
                                    $intcount++;
                                    $loan_rep_amount_fa = $row['loan_rep_amount'];

                                    $sql3 = mysql_query("select first_name, last_name from user_profiles where id = '$field_agent'");
                                    while ($row = mysql_fetch_array($sql3)) {
                                        $first_name = $row['first_name'];
                                        $last_name = $row['last_name'];
                                        $field_agent_name = $first_name . ' ' . $last_name;
                                    }

                                    if ($intcount % 2 == 0) {
                                        $display = '<tr bgcolor = #F0F0F6>';
                                    } else {
                                        $display = '<tr>';
                                    }
                                    echo $display;
                                    echo "<td valign='top'>$intcount.</td>";
                                    echo "<td valign='top'>$field_agent_name</td>";
                                    echo "<td align='right' valign='top'>" . number_format($loan_rep_amount_fa, 2) . "</td>";
                                    echo "</tr>";

                                    $total_loan_rep_amount_fa = $total_loan_rep_amount_fa + $loan_rep_amount_fa;
                                    $total_projection = $total_projection + $projection;

                                    $projection = 0;
                                }
                            }
                            $total_rate = ($total_loan_rep_amount_fa / $total_projection) * 100;
                            ?>
                        </tbody>
                        <tr bgcolor = '#E6EEEE'>
                            <td colspan='2'><strong>&nbsp;</strong></td>
                            <td align='right' valign='top'><strong><?php echo number_format($total_loan_rep_amount_fa, 2) ?></strong></td>
                        </tr>
                    </table>
                    <br />
                    <br />
                    <h3>Total Collected per EDC for the Day: <?php echo $current_date_full ?></h3>
                    <table width="100%" border="0" cellspacing="2" cellpadding="2" class="display" id="example">
                        <thead bgcolor="#E6EEEE">
                            <tr>
                                <th>#</th>
                                <th>EDC</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $intcount = 0;
                            $total_loan_rep_amount_edc = 0;
                            $loan_rep_amount_edc = 0;

                            $sql_edc = mysql_query("select id, first_name, last_name from user_profiles where title = 9 and user_status = 1");
                            while ($row = mysql_fetch_array($sql_edc)) {
                                $edc = $row['id'];
                                $first_name = $row['first_name'];
                                $last_name = $row['last_name'];

                                //$sql = mysql_query("select distinct loan_application.edc, loan_rep_date, loan_rep_code, sum(loan_rep_amount)loan_rep_amount from loan_repayments inner join loan_application on loan_application.loan_code = loan_repayments.loan_rep_code where loan_rep_date between '$current_date' and '$current_date'  and edc != '' group by edc");
                                $sql_get_payments = mysql_query("select distinct lr.current_collector,lr.loan_rep_date, lr.loan_rep_code, sum(lr.loan_rep_amount)loan_rep_amount from loan_repayments lr inner join loan_application l on l.loan_code = lr.loan_rep_code where lr.loan_rep_date = '$current_date' and lr.current_collector = '$edc' group by lr.current_collector");

                                while ($row = mysql_fetch_array($sql_get_payments)) {
                                    $intcount++;
                                    $edc = $row['edc'];
                                    $loan_rep_amount_edc = $row['loan_rep_amount'];

                                    $edc_name = $first_name . ' ' . $last_name;

                                    if ($intcount % 2 == 0) {
                                        $display = '<tr bgcolor = #F0F0F6>';
                                    } else {
                                        $display = '<tr>';
                                    }
                                    echo $display;
                                    echo "<td valign='top'>$intcount.</td>";
                                    echo "<td valign='top'>$edc_name</td>";
                                    echo "<td align='right' valign='top'>" . number_format($loan_rep_amount_edc, 2) . "</td>";
                                    echo "</tr>";

                                    $total_loan_rep_amount_edc = $total_loan_rep_amount_edc + $loan_rep_amount_edc;
                                    $total_projection = $total_projection + $projection;

                                    $projection = 0;
                                }
                            }

                            $total_rate = ($total_loan_rep_amount_edc / $total_projection) * 100;
                            ?>
                        </tbody>
                        <tr bgcolor = '#E6EEEE'>
                            <td colspan='2'><strong>&nbsp;</strong></td>
                            <td align='right' valign='top'><strong><?php echo number_format($total_loan_rep_amount_edc, 2) ?></strong></td>
                        </tr>
                    </table>
                    <br />
                    <br />

                    <h3>Total Collected per Collections Agent for the Month</h3>
                    <table width="100%" border="0" cellspacing="2" cellpadding="2" class="display" id="example2">
                        <thead bgcolor="#E6EEEE">
                            <tr>
                                <th>#</th>
                                <th>Collections Agent</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $intcount = 0;
                            $total_loan_rep_amount_cc = 0;
                            $loan_rep_amount_cc = 0;
                            $collections_agent = 0;

                            // Get a list of collections agents
                            $sql_collections_agent = mysql_query("select id from user_profiles where title = 7 and user_status = 1");
                            while ($row = mysql_fetch_array($sql_collections_agent)) {
                                $collections_agent = $row['id'];

                                //$sql = mysql_query("select distinct loan_application.collections_agent, loan_rep_date, loan_rep_code, sum(loan_rep_amount)loan_rep_amount from loan_repayments inner join loan_application on loan_application.loan_code = loan_repayments.loan_rep_code where loan_rep_date between '$current_date' and '$current_date' and collections_agent != '' group by collections_agent");
                                $sql_get_payments = mysql_query("select distinct lr.current_collector,lr.loan_rep_date, lr.loan_rep_code, sum(lr.loan_rep_amount)loan_rep_amount from loan_repayments lr inner join loan_application l on l.loan_code = lr.loan_rep_code where lr.loan_rep_date between '$filter_start_date' and '$filter_end_date' and lr.current_collector = '$collections_agent'  group by lr.current_collector");

                                while ($row = mysql_fetch_array($sql_get_payments)) {
                                    $intcount++;
                                    $collections_agent = $row['current_collector'];
                                    $loan_rep_amount_cc = $row['loan_rep_amount'];

                                    $sql3 = mysql_query("select first_name, last_name from user_profiles where id = '$collections_agent'");
                                    while ($row = mysql_fetch_array($sql3)) {
                                        $first_name = $row['first_name'];
                                        $last_name = $row['last_name'];
                                        $collections_agent_name = $first_name . ' ' . $last_name;
                                    }

                                    if ($intcount % 2 == 0) {
                                        $display = '<tr bgcolor = #F0F0F6>';
                                    } else {
                                        $display = '<tr>';
                                    }
                                    echo $display;
                                    echo "<td valign='top'>$intcount.</td>";
                                    echo "<td valign='top'>$collections_agent_name</td>";
                                    echo "<td align='right' valign='top'>" . number_format($loan_rep_amount_cc, 2) . "</td>";
                                    echo "</tr>";

                                    $total_loan_rep_amount_cc = $total_loan_rep_amount_cc + $loan_rep_amount_cc;
                                    $total_projection = $total_projection + $projection;

                                    $projection = 0;
                                }
                                $collections_agent = 0;
                            }
                            $total_rate = ($total_loan_rep_amount_cc / $total_projection) * 100;
                            ?>
                        </tbody>
                        <tr bgcolor = '#E6EEEE'>
                            <td colspan='2'><strong>&nbsp;</strong></td>
                            <td align='right' valign='top'><strong><?php echo number_format($total_loan_rep_amount_cc, 2) ?></strong></td>
                        </tr>
                    </table>
                    <br />
                    <br />

                    <h3>Total Collected per Field Agent for the Month</h3>
                    <table width="100%" border="0" cellspacing="2" cellpadding="2" class="display" id="example">
                        <thead bgcolor="#E6EEEE">
                            <tr>
                                <th>#</th>
                                <th>Field Agent</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $intcount = 0;
                            $total_loan_rep_amount_fa = 0;
                            $loan_rep_amount_fa = 0;
                            $sql_field_agent = mysql_query("select id from user_profiles where title = 10 and user_status = 1");
                            while ($row = mysql_fetch_array($sql_field_agent)) {
                                $field_agent = $row['id'];

                                //$sql = mysql_query("select distinct loan_application.collections_agent, loan_rep_date, loan_rep_code, sum(loan_rep_amount)loan_rep_amount from loan_repayments inner join loan_application on loan_application.loan_code = loan_repayments.loan_rep_code where loan_rep_date between '$current_date' and '$current_date' and collections_agent != '' group by collections_agent");
                                $sql_get_payments = mysql_query("select distinct lr.current_collector,lr.loan_rep_date, lr.loan_rep_code, sum(lr.loan_rep_amount)loan_rep_amount from loan_repayments lr inner join loan_application l on l.loan_code = lr.loan_rep_code where lr.loan_rep_date between '$filter_start_date' and '$filter_end_date' and lr.current_collector = '$field_agent' group by lr.current_collector");

                                while ($row = mysql_fetch_array($sql_get_payments)) {
                                    $intcount++;
                                    $field_agent = $row['current_collector'];
                                    $loan_rep_amount_fa = $row['loan_rep_amount'];

                                    $sql3 = mysql_query("select first_name, last_name from user_profiles where id = '$field_agent'");
                                    while ($row = mysql_fetch_array($sql3)) {
                                        $first_name = $row['first_name'];
                                        $last_name = $row['last_name'];
                                        $field_agent_name = $first_name . ' ' . $last_name;
                                    }

                                    if ($intcount % 2 == 0) {
                                        $display = '<tr bgcolor = #F0F0F6>';
                                    } else {
                                        $display = '<tr>';
                                    }
                                    echo $display;
                                    echo "<td valign='top'>$intcount.</td>";
                                    echo "<td valign='top'>$field_agent_name</td>";
                                    echo "<td align='right' valign='top'>" . number_format($loan_rep_amount_fa, 2) . "</td>";
                                    echo "</tr>";

                                    $total_loan_rep_amount_fa = $total_loan_rep_amount_fa + $loan_rep_amount_fa;
                                    $total_projection = $total_projection + $projection;

                                    $projection = 0;
                                }
                            }
                            $total_rate = ($total_loan_rep_amount_fa / $total_projection) * 100;
                            ?>
                        </tbody>
                        <tr bgcolor = '#E6EEEE'>
                            <td colspan='2'><strong>&nbsp;</strong></td>
                            <td align='right' valign='top'><strong><?php echo number_format($total_loan_rep_amount_fa, 2) ?></strong></td>
                        </tr>
                    </table>
                    <br />
                    <br />
                    <h3>Total Collected per EDC for the Month</h3>
                    <table width="100%" border="0" cellspacing="2" cellpadding="2" class="display" id="example2">
                        <thead bgcolor="#E6EEEE">
                            <tr>
                                <th>#</th>
                                <th>EDC</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $intcount = 0;
                            $total_loan_rep_amount_edc = 0;
                            $loan_rep_amount_edc = 0;
                            $sql_edc = mysql_query("select id, first_name, last_name from user_profiles where title = 9 and user_status = 1");
                            while ($row = mysql_fetch_array($sql_edc)) {
                                $edc = $row['id'];
                                $first_name = $row['first_name'];
                                $last_name = $row['last_name'];

                                //$sql = mysql_query("select distinct loan_application.edc, loan_rep_date, loan_rep_code, sum(loan_rep_amount)loan_rep_amount from loan_repayments inner join loan_application on loan_application.loan_code = loan_repayments.loan_rep_code where loan_rep_date between '$current_date' and '$current_date'  and edc != '' group by edc");
                                $sql_get_payments = mysql_query("select distinct lr.current_collector,lr.loan_rep_date, lr.loan_rep_code, sum(lr.loan_rep_amount)loan_rep_amount from loan_repayments lr inner join loan_application l on l.loan_code = lr.loan_rep_code where lr.loan_rep_date between '$filter_start_date' and '$filter_end_date' and lr.current_collector = '$edc' group by lr.current_collector");

                                while ($row = mysql_fetch_array($sql_get_payments)) {
                                    $intcount++;
                                    $loan_rep_amount_edc = $row['loan_rep_amount'];
                                    $edc_name = $first_name . ' ' . $last_name;

                                    if ($intcount % 2 == 0) {
                                        $display = '<tr bgcolor = #F0F0F6>';
                                    } else {
                                        $display = '<tr>';
                                    }
                                    echo $display;
                                    echo "<td valign='top'>$intcount.</td>";
                                    echo "<td valign='top'>$edc_name</td>";
                                    echo "<td align='right' valign='top'>" . number_format($loan_rep_amount_edc, 2) . "</td>";
                                    echo "</tr>";

                                    $total_loan_rep_amount_edc = $total_loan_rep_amount_edc + $loan_rep_amount_edc;
                                    $total_projection = $total_projection + $projection;

                                    $projection = 0;
                                }
                            }

                            $total_rate = ($total_loan_rep_amount_edc / $total_projection) * 100;
                            ?>
                        </tbody>
                        <tr bgcolor = '#E6EEEE'>
                            <td colspan='2'><strong>&nbsp;</strong></td>
                            <td align='right' valign='top'><strong><?php echo number_format($total_loan_rep_amount_edc, 2) ?></strong></td>
                        </tr>
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
                    $("#example2").btechco_excelexport({
                        containerid: "example2"
                        , datatype: $datatype.Table
                    });
                });
            });
                    </script>
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
        <?php
    }
}
include_once('includes/footer.php');
?>
