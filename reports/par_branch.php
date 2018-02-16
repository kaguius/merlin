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
                                <th>Branch</th>
                                <th>Principal(Total disbursed)</th>
                                <th>Interest</th>
                                <th>Arrears Principal</th>
                                <th>Arrears Interest</th>
                                <th>1 - 30 Days</th>
                                <th>31 - 60 Days</th>
                                <th>61- 90 Days</th>
                                <th>91 - 120 Days</th>
                                <th>Over 120 Days</th>
                                <th>PAR 30</th>
                                <th>PAR 60</th>
                                <th>PAR 90</th>
                                <th>PAR 120</th>
                                <th>PAR > 120 </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = mysql_query("select la.customer_station,s.stations,sum(la.loan_amount) as loan_amount,truncate(sum(loan_total_interest),2) due from loan_application as la join stations s on la.customer_station = s.id where la.loan_status not in('10','9','11','12','14','15') and la.customer_station != '0' and la.loan_due_date between '$filter_start_date' and '$filter_end_date' and la.loan_failure_status = '0' group by la.customer_station order by la.customer_station asc");

                            while ($row = mysql_fetch_array($sql)) {
                                $intcount++;

                                $stationid = $row['customer_station'];
                                $station = $row['stations'];
                                $total_due = $row['due'];
                                $principal = $row['loan_amount'];
                                $interest = $total_due - $principal;


                                $sql2 = mysql_query("select sum(loan_rep_amount)repayments from loan_repayments where loan_rep_code in(select distinct loan_code from loan_application where loan_due_date between '$filter_start_date' and '$filter_end_date' and customer_station = '$stationid' and loan_status not in('10','9','11','12','14','15') and loan_failure_status = '0')");
                                while ($row = mysql_fetch_array($sql2)) {
                                    $total_repayments = $row['repayments'];
                                }


                                if ($total_repayments == 0) {

                                    $arrear_interest = $interest;
                                    $arrear_principal = $principal;
                                } else {
                                    if ($total_repayments > $interest && $total_repayments < $total_due) {
                                        $arrear_interest = 0;
                                        $interest_balance = $total_repayments - $interest;
                                        $arrear_principal = $principal - $interest_balance;
                                    } else if ($total_repayments < $interest) {
                                        $arrear_interest = $interest - $total_repayments;
                                        $arrear_principal = $principal;
                                    } else if ($total_repayments > $total_due) {
                                        $arrear_interest = 0;
                                        $arrear_principal = 0;
                                    }
                                }


                                // Calculations for 30 and below

                                $sql30due = mysql_query("select truncate(sum(loan_total_interest),2) due from loan_application where loan_code in(select distinct loan_code from loan_application where loan_due_date between '$filter_start_date' and '$filter_end_date' and customer_station = '$stationid' and datediff(now(),loan_due_date) <= 30 and loan_status not in('10','9','11','12','14','15') and loan_failure_status = '0')");
                                while ($row1 = mysql_fetch_array($sql30due)) {
                                    $total_due30 = $row1['due'];
                                }

                                $sql_30 = mysql_query("select sum(loan_rep_amount)repayments from loan_repayments where loan_rep_code in(select distinct loan_code from loan_application where loan_due_date between '$filter_start_date' and '$filter_end_date' and customer_station = '$stationid' and datediff(now(),loan_due_date) <= 30 and loan_status not in('10','9','11','12','14','15') and loan_failure_status = '0')");
                                while ($row2 = mysql_fetch_array($sql_30)) {
                                    $total_repayments30 = $row2['repayments'];
                                }

                                $arrear30 = abs($total_due30 - $total_repayments30);


                                // Calculations for 30 to 60

                                $sql60due = mysql_query("select truncate(sum(loan_total_interest),2) due from loan_application where loan_code in(select distinct loan_code from loan_application where loan_due_date between '$filter_start_date' and '$filter_end_date' and customer_station = '$stationid' and datediff(now(),loan_due_date) between 31 and 60 and loan_status not in('10','9','11','12','14','15') and loan_failure_status = '0')");
                                while ($row3 = mysql_fetch_array($sql60due)) {
                                    $total_due60 = $row3['due'];
                                }

                                $sql_60 = mysql_query("select sum(loan_rep_amount)repayments from loan_repayments where loan_rep_code in(select distinct loan_code from loan_application where loan_due_date between '$filter_start_date' and '$filter_end_date' and customer_station = '$stationid' and datediff(now(),loan_due_date) between 31 and 60 and loan_status not in('10','9','11','12','14','15') and loan_failure_status = '0')");
                                while ($row4 = mysql_fetch_array($sql_60)) {
                                    $total_repayments60 = $row4['repayments'];
                                }

                                $arrear60 = abs($total_due60 - $total_repayments60);


                                // Calculations for 60 to 90

                                $sql90due = mysql_query("select truncate(sum(loan_total_interest),2) due from loan_application where loan_code in(select distinct loan_code from loan_application where loan_due_date between '$filter_start_date' and '$filter_end_date' and customer_station = '$stationid' and datediff(now(),loan_due_date) between 61 and 90 and loan_status not in('10','9','11','12','14','15') and loan_failure_status = '0')");
                                while ($row5 = mysql_fetch_array($sql90due)) {
                                    $total_due90 = $row5['due'];
                                }

                                $sql_90 = mysql_query("select sum(loan_rep_amount)repayments from loan_repayments where loan_rep_code in(select distinct loan_code from loan_application where loan_due_date between '$filter_start_date' and '$filter_end_date' and customer_station = '$stationid' and datediff(now(),loan_due_date) between 61 and 90 and loan_status not in('10','9','11','12','14','15') and loan_failure_status = '0')");
                                while ($row6 = mysql_fetch_array($sql_90)) {
                                    $total_repayments90 = $row6['repayments'];
                                }

                                $arrear90 = abs($total_due90 - $total_repayments90);


                                // Calculations for 90 to 120

                                $sql120due = mysql_query("select truncate(sum(loan_total_interest),2) due from loan_application where loan_code in(select distinct loan_code from loan_application where loan_due_date between '$filter_start_date' and '$filter_end_date' and customer_station = '$stationid' and datediff(now(),loan_due_date) between 91 and 120 and loan_status not in('10','9','11','12','14','15') and loan_failure_status = '0')");
                                while ($row7 = mysql_fetch_array($sql120due)) {
                                    $total_due120 = $row7['due'];
                                }

                                $sql_120 = mysql_query("select sum(loan_rep_amount)repayments from loan_repayments where loan_rep_code in(select distinct loan_code from loan_application where loan_due_date between '$filter_start_date' and '$filter_end_date' and customer_station = '$stationid' and datediff(now(),loan_due_date) between 91 and 120 and loan_status not in('10','9','11','12','14','15') and loan_failure_status = '0')");
                                while ($row8 = mysql_fetch_array($sql_120)) {
                                    $total_repayments120 = $row8['repayments'];
                                }

                                $arrear120 = abs($total_due120 - $total_repayments120);



                                // Calculations for 120 and above

                                $sql120pdue = mysql_query("select truncate(sum(loan_total_interest),2) due from loan_application where loan_code in(select distinct loan_code from loan_application where loan_due_date between '$filter_start_date' and '$filter_end_date' and customer_station = '$stationid' and datediff(now(),loan_due_date) > 120 and loan_status not in('10','9','11','12','14','15') and loan_failure_status = '0')");
                                while ($row9 = mysql_fetch_array($sql120pdue)) {
                                    $total_due120p = $row9['due'];
                                }

                                $sql_120p = mysql_query("select sum(loan_rep_amount)repayments from loan_repayments where loan_rep_code in(select distinct loan_code from loan_application where loan_due_date between '$filter_start_date' and '$filter_end_date' and customer_station = '$stationid' and datediff(now(),loan_due_date) > 120 and loan_status not in('10','9','11','12','14','15') and loan_failure_status = '0')");
                                while ($row10 = mysql_fetch_array($sql_120p)) {
                                    $total_repayments120p = $row10['repayments'];
                                }

                                $arrear120p = abs($total_due120p - $total_repayments120p);



                                $par30 = ($arrear30 / $total_due) * 100;
                                $par60 = ($arrear60 / $total_due) * 100;
                                $par90 = ($arrear90 / $total_due) * 100;
                                $par120 = ($arrear120 / $total_due) * 100;
                                $par120plus = ($arrear120p / $total_due) * 100;

                                if ($intcount % 2 == 0) {
                                    $display = '<tr bgcolor = #F0F0F6>';
                                } else {
                                    $display = '<tr>';
                                }
                                echo $display;
                                echo "<td valign='top'>$station</td>";
                                echo "<td align='right' valign='top'>" . number_format($principal, 2) . "</td>";
                                echo "<td align='right' valign='top'>" . number_format($interest, 2) . "</td>";
                                echo "<td align='right' valign='top'>" . number_format($arrear_principal, 2) . "</td>";
                                echo "<td align='right' valign='top'>" . number_format($arrear_interest, 2) . "</td>";
                                echo "<td align='right' valign='top'>" . number_format($arrear30, 2) . "</td>";
                                echo "<td align='right' valign='top'>" . number_format($arrear60, 2) . "</td>";
                                echo "<td align='right' valign='top'>" . number_format($arrear90, 2) . "</td>";
                                echo "<td align='right' valign='top'>" . number_format($arrear120, 2) . "</td>";
                                echo "<td align='right' valign='top'>" . number_format($arrear120p, 2) . "</td>";
                                echo "<td align='left' valign='top'>" . number_format($par30, 2) . '%' . "</td>";
                                echo "<td align='left' valign='top'>" . number_format($par60, 2) . '%' . "</td>";
                                echo "<td align='left' valign='top'>" . number_format($par90, 2) . '%' . "</td>";
                                echo "<td align='left' valign='top'>" . number_format($par120, 2) . '%' . "</td>";
                                echo "<td align='left' valign='top'>" . number_format($par120plus, 2) . '%' . "</td>";
                                echo "</tr>";


                                $bus_principal = $bus_principal + $principal;
                                $bus_interest = $bus_interest + $interest;
                                $bus_arrear_principal = $bus_arrear_principal + $arrear_principal;
                                $bus_arrear_interest = $bus_arrear_interest + $arrear_interest;
                                $bus_arrear30 = $bus_arrear30 + $arrear30;
                                $bus_arrear60 = $bus_arrear60 + $arrear60;
                                $bus_arrear90 = $bus_arrear90 + $arrear90;
                                $bus_arrear120 = $bus_arrear120 + $arrear120;
                                $bus_arrear120p = $bus_arrear120p + $arrear120p;

				$principal = 0;
				$interest = 0;
				$arrear_principal = 0;
				$arrear_interest = 0;
				$arrear30 = 0;
				$arrear60 = 0;
				$arrear90 = 0;
				$arrear120 = 0;
				$arrear120p = 0;


                            }
                            $bus_par30 = ($bus_arrear30 / ($bus_principal + $bus_interest)) * 100;
                            $bus_par60 = ($bus_arrear60 / ($bus_principal + $bus_interest)) * 100;
                            $bus_par90 = ($bus_arrear90 / ($bus_principal + $bus_interest)) * 100;
                            $bus_par120 = ($bus_arrear120 / ($bus_principal + $bus_interest)) * 100;
                            $bus_par120p = ($bus_arrear120p / ($bus_principal + $bus_interest)) * 100;
                            ?>
                        </tbody>
                        <tr bgcolor = '#E6EEEE'>
                            <td colspan='1'><strong>Business</strong></td>
                            <td align='right' valign='top'><strong><?php echo number_format($bus_principal, 2) ?></strong></td>
                            <td align='right' valign='top'><strong><?php echo number_format($bus_interest, 2) ?></strong></td>
                            <td align='right' valign='top'><strong><?php echo number_format($bus_arrear_principal, 2) ?></strong></td>
                            <td align='right' valign='top'><strong><?php echo number_format($bus_arrear_interest, 2) ?></strong></td>
                            <td align='right' valign='top'><strong><?php echo number_format($bus_arrear30, 2) ?></strong></td>
                            <td align='right' valign='top'><strong><?php echo number_format($bus_arrear60, 2) ?></strong></td>
                            <td align='right' valign='top'><strong><?php echo number_format($bus_arrear90, 2) ?></strong></td>
                            <td align='right' valign='top'><strong><?php echo number_format($bus_arrear120, 2) ?></strong></td>
                            <td align='right' valign='top'><strong><?php echo number_format($bus_arrear120p, 2) ?></strong></td>       
                            <td align='right' valign='top'><strong><?php echo number_format($bus_par30, 2) ?>%</strong></td>
                            <td align='right' valign='top'><strong><?php echo number_format($bus_par60, 2) ?>%</strong></td>
                            <td align='right' valign='top'><strong><?php echo number_format($bus_par90, 2) ?>%</strong></td>
                            <td align='right' valign='top'><strong><?php echo number_format($bus_par120, 2) ?>%</strong></td>
                            <td align='right' valign='top'><strong><?php echo number_format($bus_par120p, 2) ?>%</strong></td>
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
