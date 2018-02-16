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
    $page_title = "Portfolio at Risk: Branch View";
    include_once('includes/header.php');
    $filter_month = date("m");
    $filter_year = date("Y");
    $filter_day = date("d");
    $current_date = $filter_year . '-' . $filter_month . '-' . $filter_day;
    $filter_clerk = 0;
    if (!empty($_GET)) {
        $filter_clerk = $_GET['clerk'];
        $filter_start_date = $_GET['report_start_date'];
        $filter_start_date = date('Y-m-d', strtotime(str_replace('-', '/', $filter_start_date)));
        $filter_end_date = $_GET['report_end_date'];
        $filter_end_date = date('Y-m-d', strtotime(str_replace('-', '/', $filter_end_date)));
    }
    include_once('includes/db_conn.php');
    if ($filter_start_date != "" && $filter_end_date != "") {
        ?>
        <div id="page">
            <div id="content">
                <div class="post">
                    <h2><?php echo $page_title ?></h2>
                    <p><strong>Report Range: <?php echo $filter_start_date ?> to <?php echo $filter_end_date ?></strong></p>
                    <table width="100%" border="0" cellspacing="2" cellpadding="2" class="display" id="exampl">
                        <thead bgcolor="#E6EEEE">
                            <tr>
                                <th>Not at risk</th>
                                <th>PAR1</th>
                                <th>PAR5</th>
                                <th>PAR10</th>
                                <th>PAR20</th>
                                <th>PAR30</th>
                                <th>PAR60</th>
                                <th>PAR90+</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = mysql_query("select truncate(sum(loan_total_interest),2) due from loan_application where loan_status not in('10','9','11','12','14','15') and customer_station != '0' and loan_due_date > now() and loan_failure_status = '0'");

                            while ($row = mysql_fetch_array($sql)) {
                                $total_due = $row['due'];
                            }
                            $sql2 = mysql_query("select sum(loan_rep_amount)repayments from loan_repayments where loan_rep_code in(select distinct loan_code from loan_application where loan_due_date > now() and customer_station != '0' and loan_status not in('10','9','11','12','14','15') and loan_failure_status = '0')");
                            while ($row = mysql_fetch_array($sql2)) {
                                $total_repayments = $row['repayments'];
                            }

                            if ($total_repayments == 0) {
                                $not_at_risk = $total_due;
                            } else {
                                if ($total_repayments < $total_due) {
                                    $not_at_risk = $total_due - $total_repayments;
                                } else if ($total_repayments >= $total_due) {
                                    $not_at_risk = 0;
                                }
                            }

                            $due_sql_par1 = mysql_query("select truncate(sum(loan_total_interest),2) due from loan_application where loan_status not in('10','9','11','12','14','15') and loan_due_date between '$filter_start_date' and '$filter_end_date' and customer_station != '0' and datediff(now(),loan_due_date) = 1 and loan_failure_status = '0'");
                            while ($row1 = mysql_fetch_array($due_sql_par1)) {
                                $duepar1 = $row1['due'];
                            }

                            $pay_sql_par1 = mysql_query("select sum(loan_rep_amount)repayments from loan_repayments where loan_rep_code in(select distinct loan_code from loan_application where loan_due_date between '$filter_start_date' and '$filter_end_date' and customer_station != '0' and datediff(now(),loan_due_date) = 1 and loan_status not in('10','9','11','12','14','15') and loan_failure_status = '0')");
                            while ($row2 = mysql_fetch_array($pay_sql_par1)) {
                                $pay_par1 = $row2['repayments'];
                            }

                            if ($pay_par1 == 0) {
                                $par1 = $duepar1;
                            } else {
                                if ($pay_par1 < $duepar1) {
                                    $par1 = $duepar1 - $pay_par1;
                                } else if ($pay_par1 >= $duepar1) {
                                    $par1 = 0;
                                }
                            }

                            $due_sql_par5 = mysql_query("select truncate(sum(loan_total_interest),2) due from loan_application where loan_status not in('10','9','11','12','14','15') and loan_due_date between '$filter_start_date' and '$filter_end_date' and customer_station != '0' and datediff(now(),loan_due_date) between 2 and 5 and loan_failure_status = '0'");
                            while ($row1 = mysql_fetch_array($due_sql_par5)) {
                                $duepar5 = $row1['due'];
                            }

                            $pay_sql_par5 = mysql_query("select sum(loan_rep_amount)repayments from loan_repayments where loan_rep_code in(select distinct loan_code from loan_application where loan_due_date between '$filter_start_date' and '$filter_end_date' and customer_station != '0' and datediff(now(),loan_due_date) between 2 and 5 and loan_status not in('10','9','11','12','14','15') and loan_failure_status = '0')");
                            while ($row2 = mysql_fetch_array($pay_sql_par5)) {
                                $pay_par5 = $row2['repayments'];
                            }

                            if ($pay_par5 == 0) {
                                $par5 = $duepar5;
                            } else {
                                if ($pay_par5 < $duepar5) {
                                    $par5 = $duepar5 - $pay_par5;
                                } else if ($pay_par5 >= $duepar5) {
                                    $par5 = 0;
                                }
                            }

                            $due_sql_par10 = mysql_query("select truncate(sum(loan_total_interest),2) due from loan_application where loan_status not in('10','9','11','12','14','15') and loan_due_date between '$filter_start_date' and '$filter_end_date' and customer_station != '0' and datediff(now(),loan_due_date) between 6 and 10 and loan_failure_status = '0'");
                            while ($row1 = mysql_fetch_array($due_sql_par10)) {
                                $duepar10 = $row1['due'];
                            }

                            $pay_sql_par10 = mysql_query("select sum(loan_rep_amount)repayments from loan_repayments where loan_rep_code in(select distinct loan_code from loan_application where loan_due_date between '$filter_start_date' and '$filter_end_date' and customer_station != '0' and datediff(now(),loan_due_date) between 6 and 10 and loan_status not in('10','9','11','12','14','15') and loan_failure_status = '0')");
                            while ($row2 = mysql_fetch_array($pay_sql_par10)) {
                                $pay_par10 = $row2['repayments'];
                            }

                            if ($pay_par10 == 0) {
                                $par10 = $duepar10;
                            } else {
                                if ($pay_par10 < $duepar10) {
                                    $par10 = $duepar10 - $pay_par10;
                                } else if ($pay_par10 >= $duepar10) {
                                    $par10 = 0;
                                }
                            }

                            $due_sql_par20 = mysql_query("select truncate(sum(loan_total_interest),2) due from loan_application where loan_status not in('10','9','11','12','14','15') and loan_due_date between '$filter_start_date' and '$filter_end_date' and customer_station != '0' and datediff(now(),loan_due_date) between 11 and 20 and loan_failure_status = '0'");
                            while ($row1 = mysql_fetch_array($due_sql_par20)) {
                                $duepar20 = $row1['due'];
                            }

                            $pay_sql_par20 = mysql_query("select sum(loan_rep_amount)repayments from loan_repayments where loan_rep_code in(select distinct loan_code from loan_application where loan_due_date between '$filter_start_date' and '$filter_end_date' and customer_station != '0' and datediff(now(),loan_due_date) between 11 and 20 and loan_status not in('10','9','11','12','14','15') and loan_failure_status = '0')");
                            while ($row2 = mysql_fetch_array($pay_sql_par20)) {
                                $pay_par20 = $row2['repayments'];
                            }

                            if ($pay_par20 == 0) {
                                $par20 = $duepar20;
                            } else {
                                if ($pay_par20 < $duepar20) {
                                    $par20 = $duepar20 - $pay_par20;
                                } else if ($pay_par20 >= $duepar20) {
                                    $par20 = 0;
                                }
                            }

                            $due_sql_par30 = mysql_query("select truncate(sum(loan_total_interest),2) due from loan_application where loan_status not in('10','9','11','12','14','15') and loan_due_date between '$filter_start_date' and '$filter_end_date' and customer_station != '0' and datediff(now(),loan_due_date) between 21 and 30 and loan_failure_status = '0'");
                            while ($row1 = mysql_fetch_array($due_sql_par30)) {
                                $duepar30 = $row1['due'];
                            }

                            $pay_sql_par30 = mysql_query("select sum(loan_rep_amount)repayments from loan_repayments where loan_rep_code in(select distinct loan_code from loan_application where loan_due_date between '$filter_start_date' and '$filter_end_date' and customer_station != '0' and datediff(now(),loan_due_date) between 21 and 30 and loan_status not in('10','9','11','12','14','15') and loan_failure_status = '0')");
                            while ($row2 = mysql_fetch_array($pay_sql_par30)) {
                                $pay_par30 = $row2['repayments'];
                            }

                            if ($pay_par30 == 0) {
                                $par30 = $duepar30;
                            } else {
                                if ($pay_par30 < $duepar30) {
                                    $par30 = $duepar30 - $pay_par30;
                                } else if ($pay_par30 >= $duepar30) {
                                    $par30 = 0;
                                }
                            }

                            $due_sql_par60 = mysql_query("select truncate(sum(loan_total_interest),2) due from loan_application where loan_status not in('10','9','11','12','14','15') and loan_due_date between '$filter_start_date' and '$filter_end_date' and customer_station != '0' and datediff(now(),loan_due_date) between 31 and 90 and loan_failure_status = '0'");
                            while ($row1 = mysql_fetch_array($due_sql_par60)) {
                                $duepar60 = $row1['due'];
                            }

                            $pay_sql_par60 = mysql_query("select sum(loan_rep_amount)repayments from loan_repayments where loan_rep_code in(select distinct loan_code from loan_application where loan_due_date between '$filter_start_date' and '$filter_end_date' and customer_station != '0' and datediff(now(),loan_due_date) between 31 and 90 and loan_status not in('10','9','11','12','14','15') and loan_failure_status = '0')");
                            while ($row2 = mysql_fetch_array($pay_sql_par60)) {
                                $pay_par60 = $row2['repayments'];
                            }

                            if ($pay_par60 == 0) {
                                $par60 = $duepar60;
                            } else {
                                if ($pay_par60 < $duepar60) {
                                    $par60 = $duepar60 - $pay_par60;
                                } else if ($pay_par60 >= $duepar60) {
                                    $par60 = 0;
                                }
                            }

                            $due_sql_par90 = mysql_query("select truncate(sum(loan_total_interest),2) due from loan_application where loan_status not in('10','9','11','12','14','15') and loan_due_date between '$filter_start_date' and '$filter_end_date' and customer_station != '0' and datediff(now(),loan_due_date) > 91 and loan_failure_status = '0'");
                            while ($row1 = mysql_fetch_array($due_sql_par90)) {
                                $duepar90 = $row1['due'];
                            }

                            $pay_sql_par90 = mysql_query("select sum(loan_rep_amount)repayments from loan_repayments where loan_rep_code in(select distinct loan_code from loan_application where loan_due_date between '$filter_start_date' and '$filter_end_date' and customer_station != '0' and datediff(now(),loan_due_date) > 91 and loan_status not in('10','9','11','12','14','15') and loan_failure_status = '0')");
                            while ($row2 = mysql_fetch_array($pay_sql_par90)) {
                                $pay_par90 = $row2['repayments'];
                            }

                            if ($pay_par90 == 0) {
                                $par90 = $duepar90;
                            } else {
                                if ($pay_par90 < $duepar90) {
                                    $par90 = $duepar90 - $pay_par90;
                                } else if ($pay_par90 >= $duepar90) {
                                    $par90 = 0;
                                }
                            }

                            $display = '<tr>';

                            echo $display;
                            echo "<td align='right' valign='top'>" . number_format($not_at_risk, 2) . "</td>";
                            echo "<td align='right' valign='top'>" . number_format($par1, 2) . "</td>";
                            echo "<td align='right' valign='top'>" . number_format($par5, 2) . "</td>";
                            echo "<td align='right' valign='top'>" . number_format($par10, 2) . "</td>";
                            echo "<td align='right' valign='top'>" . number_format($par20, 2) . "</td>";
                            echo "<td align='right' valign='top'>" . number_format($par30, 2) . "</td>";
                            echo "<td align='right' valign='top'>" . number_format($par60, 2) . "</td>";
                            echo "<td align='right' valign='top'>" . number_format($par90, 2) . "</td>";
                            echo "</tr>";
                            ?>
                        </tbody>
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
                $("#exampl").btechco_excelexport({
                    containerid: "exampl"
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