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
    $page_title = "Loans Disbursed Report";
    include_once('includes/db_conn.php');

    $filter_month = date("m");
    $filter_year = date("Y");
    $filter_day = date("d");
    $current_date = $filter_year . '-' . $filter_month . '-' . $filter_day;
    $current_date_full = date("d M, Y", strtotime($current_date));

    $report_term = 7;
    $start_report_date = date('Y-m-d', strtotime($current_date) - (24 * 3600 * $report_term));

    if (!empty($_GET)) {
        $loan_officer = $_GET['loan_officer'];
        $filter_start_date = $_GET['report_start_date'];
        $filter_start_date = date('Y-m-d', strtotime(str_replace('-', '/', $filter_start_date)));
        $filter_end_date = $_GET['report_end_date'];
        $filter_end_date = date('Y-m-d', strtotime(str_replace('-', '/', $filter_end_date)));
    }
    $filter_start_date = '2015-10-17';
    $filter_end_date = '2015-11-30';
    $filter_repayments_date = '2016-02-29';
    ?>
    <table width="100%" border="0" cellspacing="2" cellpadding="2" class="display" id="example2">
        <thead bgcolor="#E6EEEE">
            <tr>
                <th>#</th>
                <th>Customer_id</th>
                <th>Type of Business</th>
                <th>Disburement Date</th>
                <th>Loan Amount</th>
                <th>Tenor Days</th>
                <th>Partial Payments (DD)</th>
                <th>Partial Payments (DD + 1)</th>
                <th>No of Loan</th>
                <th>Gender</th>
                <th>Age Years</th>
                <th>Marital Status</th>
                <th>Own/ Rents</th>
                <th>Dependants</th>
                <th>Branch ID</th>
                <th>Branch Name</th>
                <th>Total Expenses</th>
                <th>Sales</th>
                <th>Cost of Goods Sold</th>
                <th>Expenses</th>
                <th>Affordability</th>
                <th>Trading Product</th>
                <th>Trading Location</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = mysql_query("select loan_code, customer_station, loan_date, loan_due_date, loan_amount, customer_id "
                    . "from loan_application where customer_station = 5 and loan_status NOT IN ('11', '12', '14') "
                    . "order by customer_id, loan_date asc");
            while ($row = mysql_fetch_array($sql)) {
                $intcount++;
                $loan_code = $row['loan_code'];
                $loan_date = $row['loan_date'];
                $loan_due_date = $row['loan_due_date'];
                $loan_amount = $row['loan_amount'];
                $customer_id = $row['customer_id'];
                $customer_station = $row['customer_station'];

                $sql2 = mysql_query("select count(loan_rep_id)partial, sum(loan_rep_amount)repayments from loan_repayments "
                        . "where loan_rep_code = '$loan_code' and loan_rep_date between '$loan_date' and '$loan_due_date' "
                        . "group by loan_rep_code");
                while ($row = mysql_fetch_array($sql2)) {
                    $repayments = $row['repayments'];
                    $partial = $row['partial'];
                    if ($repayments == '') {
                        $repayments = 0;
                    }
                    if ($partial == '') {
                        $partial = 0;
                    }
                    if ($partial > '1') {
                        $partial_name = '1';
                    } else {
                        $partial_name = '0';
                    }
                }

                $sql2 = mysql_query("select loan_rep_date from loan_repayments where loan_rep_code = '$loan_code' "
                        . "order by loan_rep_date desc limit 1");
                while ($row = mysql_fetch_array($sql2)) {
                    $loan_rep_date = $row['loan_rep_date'];
                }

                $loan_due_date = date('Y-m-d', strtotime($loan_due_date) + (24 * 3600 * 1));
                $sql2 = mysql_query("select count(loan_rep_id)partial_DD, sum(loan_rep_amount)repayments "
                        . "from loan_repayments where loan_rep_code = '$loan_code' and loan_rep_date "
                        . "between '$loan_due_date' and '$loan_rep_date' group by loan_rep_code");
                while ($row = mysql_fetch_array($sql2)) {
                    $partial_DD = $row['partial_DD'];
                    //if ($partial_DD == '') {
                    //	$partial_DD = 0;
                    //}
                    if ($partial_DD > '1') {
                        $partial_name_DD = '1';
                    } else {
                        $partial_name_DD = '0';
                    }
                }

                $sql2 = mysql_query("select stations from stations where id = '$customer_station'");
                while ($row = mysql_fetch_array($sql2)) {
                    $stations = $row['stations'];
                }

                $tenure_daye = $loan_rep_date - $loan_date;
                $diff = abs(strtotime($loan_rep_date) - strtotime($loan_date));
                $tenure_daye = floor($diff / (60 * 60 * 24));
                if ($tenure_daye >= '33') {
                    $tenure_daye = '1';
                } else {
                    $tenure_daye = '0';
                }

                $sql2 = mysql_query("select gender, date_of_birth, marital, owns, dependants, affordability, market "
                        . "from users where id = '$customer_id'");
                while ($row = mysql_fetch_array($sql2)) {
                    $gender = $row['gender'];
                    $date_of_birth = $row['date_of_birth'];
                    $marital = $row['marital'];
                    $owns = $row['owns'];
                    $dependants = $row['dependants'];
                    $affordability = $row['affordability'];
                    $market = $row['market'];

                    if ($owns = 'Owns') {
                        $owns = '1';
                    } else {
                        $owns = '2';
                    }
                }

                $sql2 = mysql_query("select business_category, trading_product, trading_location, business_address, "
                        . "business_rent, business_utilities, employees, licensing, storage, transport, weekly_sales, "
                        . "spend_stock from business_details where user_id = '$customer_id' order by id desc limit 1");
                while ($row = mysql_fetch_array($sql2)) {
                    $business_category = $row['business_category'];
                    $business_address = $row['business_address'];
                    $trading_product = $row['trading_product'];
                    $trading_location = $row['trading_location'];
                    $business_rent = $row['business_rent'];
                    $business_utilities = $row['business_utilities'];
                    $employees = $row['employees'];
                    $licensing = $row['licensing'];
                    $storage = $row['storage'];
                    $transport = $row['transport'];
                    $weekly_sales = $row['weekly_sales'];
                    $spend_stock = $row['spend_stock'];
                    $business_expenses = $business_rent + $employess + $business_utilities + $licensing + $storage + $transport;
                }

                $diff = abs(strtotime($current_date) - strtotime($date_of_birth));
                $years = floor($diff / (365 * 60 * 60 * 24));

                $diff = abs(strtotime($current_date) - strtotime($trading_product));
                $trading_product = floor($diff / (365 * 60 * 60 * 24));

                $diff = abs(strtotime($current_date) - strtotime($trading_location));
                $trading_location = floor($diff / (365 * 60 * 60 * 24));

                if ($intcount % 2 == 0) {
                    $display = '<tr bgcolor = #F0F0F6>';
                } else {
                    $display = '<tr>';
                }

                echo $display;
                echo "<td valign='top'>$intcount.</td>";
                echo "<td valign='top'>$customer_id</td>";
                echo "<td valign='top'>$business_category</td>";
                echo "<td valign='top'>$loan_date</td>";
                echo "<td valign='top' align='right'>" . number_format($loan_amount, 0) . "</td>";
                echo "<td valign='top'>$tenure_daye</td>";
                echo "<td valign='top'>$partial_name</td>";
                echo "<td valign='top'>$partial_name_DD</td>";
                echo "<td valign='top'>$partial_name</td>";
                echo "<td valign='top'>$gender</td>";
                echo "<td valign='top'>$years</td>";
                echo "<td valign='top'>$marital</td>";
                echo "<td valign='top'>$owns</td>";
                echo "<td valign='top'>$dependants</td>";
                echo "<td valign='top'>$customer_station</td>";
                echo "<td valign='top'>$stations</td>";
                echo "<td valign='top' align='right'>" . number_format($business_expenses, 0) . "</td>";
                echo "<td valign='top' align='right'>" . number_format($weekly_sales, 0) . "</td>";
                echo "<td valign='top' align='right'>" . number_format($spend_stock, 0) . "</td>";
                echo "<td valign='top' align='right'>" . number_format($business_expenses, 0) . "</td>";
                echo "<td valign='top' align='right'>" . number_format($affordability, 0) . "</td>";
                echo "<td valign='top'>$trading_product</td>";
                echo "<td valign='top'>$trading_location</td>";
                echo "</tr>";

                $$partial_DD = "";
                $partial = "";
                $repayments = "";
                $partial_name_DD = "";
                $partial_name = "";
                $trading_product = "";
                $trading_location = "";
                $marital = "";
                $dependants = "";
                $home_address = "";
                $owns = "";
                $home_occupy = "";
            }
            ?>
        </tbody>
    </table>

    <?php
}
include_once('includes/footer.php');
?>
