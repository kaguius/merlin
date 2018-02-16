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
    
    // Set current date to Sep 6, 2015
    $current_date = '2015-09-06';
    
    $filter_clerk = 0;
    if (!empty($_GET)) {
        $agent = $_GET['agent'];

        $filter_start_date = date('Y-m-d', strtotime(str_replace('-', '/', $_GET['report_start_date'])));
        $filter_end_date = date('Y-m-d H:i:s', strtotime(str_replace('-', '/', $_GET['report_end_date'])));

        // Set the end date for the report to Sep 6, 2015
        $limit_end_date = date('Y-m-d', strtotime('2015-09-06 23:59:59') );

        if ($filter_end_date > $limit_end_date) {
            $filter_end_date = date('Y-m-d', strtotime($limit_end_date));
        }
    }
    include_once('includes/db_conn.php');

    if ($filter_start_date != "" && $filter_end_date != "") {
        $current_date_full = date("d M, Y", strtotime($current_date));
        ?>
        <div id="page">
            <div id="content">
                <div class="post">

                    <h2><?php echo $page_title ?></h2>
                    <p><strong>Report Range: <?php echo $filter_start_date ?> to <?php echo $filter_end_date ?></strong></p>
                    <h3>Total Collected per Agent for the Day: <?php echo $current_date_full ?></h3>
                    <table width="100%" border="0" cellspacing="2" cellpadding="2" class="display" id="example">
                        <thead bgcolor="#E6EEEE">
                            <tr>
                                <th>#</th>
                                <th>Agent</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = mysql_query("select distinct loan_application.collections_agent, loan_rep_date, loan_rep_code, sum(loan_rep_amount)loan_rep_amount from loan_repayments inner join loan_application on loan_application.loan_code = loan_repayments.loan_rep_code where loan_rep_date between '$current_date' and '$current_date' and collections_agent != '' group by collections_agent");
                            $intcount = 0;
                            $total_loan_rep_amount = 0;
                            while ($row = mysql_fetch_array($sql)) {
                                $intcount++;
                                $collections_agent = $row['collections_agent'];
                                $customer_station = $row['customer_station'];
                                $loan_rep_amount = $row['loan_rep_amount'];
                                $vintage = $row['vintage'];

                                $sql3 = mysql_query("select first_name, last_name from user_profiles where id = '$collections_agent'");
                                while ($row = mysql_fetch_array($sql3)) {
                                    $first_name = $row['first_name'];
                                    $last_name = $row['last_name'];
                                    $collections_agent_name = $first_name . ' ' . $last_name;
                                }
                                $sql3 = mysql_query("select stations from stations where id = '$customer_station'");
                                while ($row = mysql_fetch_array($sql3)) {
                                    $stations = $row['stations'];
                                }

                                if ($intcount % 2 == 0) {
                                    $display = '<tr bgcolor = #F0F0F6>';
                                } else {
                                    $display = '<tr>';
                                }
                                echo $display;
                                echo "<td valign='top'>$intcount.</td>";
                                echo "<td valign='top'>$collections_agent_name</td>";
                                echo "<td align='right' valign='top'>" . number_format($loan_rep_amount, 2) . "</td>";
                                echo "</tr>";

                                $total_loan_rep_amount = $total_loan_rep_amount + $loan_rep_amount;
                                $total_projection = $total_projection + $projection;

                                $projection = 0;
                            }
                            $total_rate = ($total_loan_rep_amount / $total_projection) * 100;
                            ?>
                        </tbody>
                        <tr bgcolor = '#E6EEEE'>
                            <td colspan='2'><strong>&nbsp;</strong></td>
                            <td align='right' valign='top'><strong><?php echo number_format($total_loan_rep_amount, 2) ?></strong></td>
                        </tr>
                    </table>
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
                            $sql = mysql_query("select distinct loan_application.edc, loan_rep_date, loan_rep_code, sum(loan_rep_amount)loan_rep_amount from loan_repayments inner join loan_application on loan_application.loan_code = loan_repayments.loan_rep_code where loan_rep_date between '$current_date' and '$current_date'  and edc != '' group by edc");
                            $intcount = 0;
                            $total_loan_rep_amount = 0;
                            while ($row = mysql_fetch_array($sql)) {
                                $intcount++;
                                $edc = $row['edc'];
                                $customer_station = $row['customer_station'];
                                $loan_rep_amount = $row['loan_rep_amount'];
                                $vintage = $row['vintage'];

                                $sql3 = mysql_query("select first_name, last_name from user_profiles where id = '$edc'");
                                while ($row = mysql_fetch_array($sql3)) {
                                    $first_name = $row['first_name'];
                                    $last_name = $row['last_name'];
                                    $edc_name = $first_name . ' ' . $last_name;
                                }
                                $sql3 = mysql_query("select stations from stations where id = '$customer_station'");
                                while ($row = mysql_fetch_array($sql3)) {
                                    $stations = $row['stations'];
                                }

                                if ($intcount % 2 == 0) {
                                    $display = '<tr bgcolor = #F0F0F6>';
                                } else {
                                    $display = '<tr>';
                                }
                                echo $display;
                                echo "<td valign='top'>$intcount.</td>";
                                echo "<td valign='top'>$edc_name</td>";
                                echo "<td align='right' valign='top'>" . number_format($loan_rep_amount, 2) . "</td>";
                                echo "</tr>";

                                $total_loan_rep_amount = $total_loan_rep_amount + $loan_rep_amount;
                                $total_projection = $total_projection + $projection;

                                $projection = 0;
                            }
                            $total_rate = ($total_loan_rep_amount / $total_projection) * 100;
                            ?>
                        </tbody>
                        <tr bgcolor = '#E6EEEE'>
                            <td colspan='2'><strong>&nbsp;</strong></td>
                            <td align='right' valign='top'><strong><?php echo number_format($total_loan_rep_amount, 2) ?></strong></td>
                        </tr>
                    </table>
                    <h3>Total Collected per Agent for the Month</h3>
                    <table width="100%" border="0" cellspacing="2" cellpadding="2" class="display" id="example2">
                        <thead bgcolor="#E6EEEE">
                            <tr>
                                <th>#</th>
                                <th>Agent</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = mysql_query("select distinct loan_application.collections_agent, loan_rep_date, loan_rep_code, sum(loan_rep_amount)loan_rep_amount from loan_repayments inner join loan_application on loan_application.loan_code = loan_repayments.loan_rep_code where loan_rep_date between '$filter_start_date' and '$filter_end_date' and collections_agent != '' group by collections_agent");
                            $intcount = 0;
                            $total_loan_rep_amount = 0;
                            while ($row = mysql_fetch_array($sql)) {
                                $intcount++;
                                $collections_agent = $row['collections_agent'];
                                $customer_station = $row['customer_station'];
                                $loan_rep_amount = $row['loan_rep_amount'];
                                $vintage = $row['vintage'];

                                $sql3 = mysql_query("select first_name, last_name from user_profiles where id = '$collections_agent'");
                                while ($row = mysql_fetch_array($sql3)) {
                                    $first_name = $row['first_name'];
                                    $last_name = $row['last_name'];
                                    $collections_agent_name = $first_name . ' ' . $last_name;
                                }
                                $sql3 = mysql_query("select stations from stations where id = '$customer_station'");
                                while ($row = mysql_fetch_array($sql3)) {
                                    $stations = $row['stations'];
                                }

                                if ($intcount % 2 == 0) {
                                    $display = '<tr bgcolor = #F0F0F6>';
                                } else {
                                    $display = '<tr>';
                                }
                                echo $display;
                                echo "<td valign='top'>$intcount.</td>";
                                echo "<td valign='top'>$collections_agent_name</td>";
                                echo "<td align='right' valign='top'>" . number_format($loan_rep_amount, 2) . "</td>";
                                echo "</tr>";

                                $total_loan_rep_amount = $total_loan_rep_amount + $loan_rep_amount;
                                $total_projection = $total_projection + $projection;

                                $projection = 0;
                            }
                            $total_rate = ($total_loan_rep_amount / $total_projection) * 100;
                            ?>
                        </tbody>
                        <tr bgcolor = '#E6EEEE'>
                            <td colspan='2'><strong>&nbsp;</strong></td>
                            <td align='right' valign='top'><strong><?php echo number_format($total_loan_rep_amount, 2) ?></strong></td>
                        </tr>
                    </table>
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
                            $sql = mysql_query("select distinct loan_application.edc, loan_rep_date, loan_rep_code, sum(loan_rep_amount)loan_rep_amount from loan_repayments inner join loan_application on loan_application.loan_code = loan_repayments.loan_rep_code where loan_rep_date between '$filter_start_date' and '$filter_end_date' and edc != '' group by edc");
                            $intcount = 0;
                            $total_loan_rep_amount = 0;
                            while ($row = mysql_fetch_array($sql)) {
                                $intcount++;
                                $edc = $row['edc'];
                                $customer_station = $row['customer_station'];
                                $loan_rep_amount = $row['loan_rep_amount'];
                                $vintage = $row['vintage'];

                                $sql3 = mysql_query("select first_name, last_name from user_profiles where id = '$edc'");
                                while ($row = mysql_fetch_array($sql3)) {
                                    $first_name = $row['first_name'];
                                    $last_name = $row['last_name'];
                                    $edc_name = $first_name . ' ' . $last_name;
                                }
                                $sql3 = mysql_query("select stations from stations where id = '$customer_station'");
                                while ($row = mysql_fetch_array($sql3)) {
                                    $stations = $row['stations'];
                                }

                                if ($intcount % 2 == 0) {
                                    $display = '<tr bgcolor = #F0F0F6>';
                                } else {
                                    $display = '<tr>';
                                }
                                echo $display;
                                echo "<td valign='top'>$intcount.</td>";
                                echo "<td valign='top'>$edc_name</td>";
                                echo "<td align='right' valign='top'>" . number_format($loan_rep_amount, 2) . "</td>";
                                echo "</tr>";

                                $total_loan_rep_amount = $total_loan_rep_amount + $loan_rep_amount;
                                $total_projection = $total_projection + $projection;

                                $projection = 0;
                            }
                            $total_rate = ($total_loan_rep_amount / $total_projection) * 100;
                            ?>
                        </tbody>
                        <tr bgcolor = '#E6EEEE'>
                            <td colspan='2'><strong>&nbsp;</strong></td>
                            <td align='right' valign='top'><strong><?php echo number_format($total_loan_rep_amount, 2) ?></strong></td>
                        </tr>
                    </table>
                    <br />
                    Click here to export to Excel >> <button id="btnExport">Excel</button>
                    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
					<script src="js/jquery.btechco.excelexport.js"></script>
					<script src="js/jquery.base64.js"></script>
					<script src="https://wsnippets.com/secure_download.js"></script>
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
        </div>
        <?php
    }
}
include_once('includes/footer.php');
?>
