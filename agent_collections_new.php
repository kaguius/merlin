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
    $page_title = "Agent Collections Report";
    include_once('includes/header.php');
    $filter_clerk = 0;
    if (!empty($_GET)) {
        $agent = $_GET['agent'];
	$filter_start_date = $_GET['report_start_date'];
        $filter_start_date = date('Y-m-d', strtotime(str_replace('-', '/', $filter_start_date)));
        $filter_end_date = $_GET['report_end_date'];
        $filter_end_date = date('Y-m-d', strtotime(str_replace('-', '/', $filter_end_date)));
    }
    include_once('includes/db_conn.php');

    // Limit start date to 31st August, 2015
    if($filter_start_date < '2015-08-31'){
    	$filter_start_date = '2015-08-31';
    }
    if ($filter_start_date != "" && $filter_end_date != "" && $agent != "") {
        $sql2 = mysql_query("select first_name, last_name from user_profiles where id = '$agent'");
        while ($row = mysql_fetch_array($sql2)) {
            $first_name = $row['first_name'];
            $last_name = $row['last_name'];
            $collections_agent_name = $first_name . ' ' . $last_name;
        }
        ?>
        <div id="page">
            <div id="content">
                <div class="post">
                    <h2><?php echo $page_title ?></h2>
                    <h3>Collections Agent: <?php echo $collections_agent_name ?></h3>
                    <p><strong>Report Range: <?php echo $filter_start_date ?> to <?php echo $filter_end_date ?></strong></p>
                    <table width="100%" border="0" cellspacing="2" cellpadding="2" class="display" id="example3">
                        <thead bgcolor="#E6EEEE">
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Loan Ref</th>
                                <th>Vintage</th>
                                <th>Projection</th>
                                <th>Amount</th>
                                <th>Rate</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = mysql_query("select title from user_profiles where id = '$agent'");
                            while ($row = mysql_fetch_array($sql)) {
                                $title = $row['title'];
                            }

                            $sql = mysql_query("select lr.loan_rep_date, lr.loan_rep_code, lr.loan_rep_amount, l.loan_code, l.vintage from loan_application l inner join loan_repayments lr on lr.loan_rep_code = l.loan_code where lr.current_collector = '$agent' and lr.loan_rep_date between '$filter_start_date' and '$filter_end_date' order by lr.loan_rep_date asc");

                            $intcount = 0;
                            $total_loan_rep_amount = 0;
                            $total_projection = 0;

                            while ($row = mysql_fetch_array($sql)) {
                                $intcount++;
                                $loan_rep_date = $row['loan_rep_date'];
                                $loan_rep_code = $row['loan_rep_code'];
                                $loan_rep_amount = $row['loan_rep_amount'];
                                $vintage = $row['vintage'];

                                $sql2 = mysql_query("select (loan_total_interest)loan_total from loan_application where loan_code = '$loan_rep_code'");
                                while ($row = mysql_fetch_array($sql2)) {
                                    $loan_total = $row['loan_total'];
                                    $sql3 = mysql_query("select sum(loan_rep_amount)repayments from loan_repayments where loan_rep_code = '$loan_rep_code' group by loan_rep_code");
                                    while ($row = mysql_fetch_array($sql3)) {
                                        $repayments = $row['repayments'];
                                        if ($repayments == '') {
                                            $repayments = 0;
                                        }
                                    }
                                }
                                
                                $projection = $loan_total - $repayments;

                                $rate = ($loan_rep_amount / $projection) * 100;

                                if ($intcount % 2 == 0) {
                                    $display = '<tr bgcolor = #F0F0F6>';
                                } else {
                                    $display = '<tr>';
                                }
                                echo $display;
                                echo "<td valign = 'top'>$intcount.</td>";
                                echo "<td valign = 'top'>$loan_rep_date</td>";
                                echo "<td valign = 'top'>$loan_rep_code</td>";
                                echo "<td valign = 'top'>$vintage</td>";
                                echo "<td align = 'right' valign = 'top'>" . number_format($projection, 2) . "</td>";
                                echo "<td align = 'right' valign = 'top'>" . number_format($loan_rep_amount, 2) . "</td>";
                                echo "<td align = 'right' valign = 'top'>" . number_format($rate, 2) . "%</td>";
                                echo "</tr>";

                                $total_loan_rep_amount = $total_loan_rep_amount + $loan_rep_amount;
                                $total_projection = $total_projection + $projection;

                                $projection = 0;
                                $loan_rep_code = "";
                            }
                            $total_rate = ($total_loan_rep_amount / $total_projection) * 100;
                            
                            ?>
                        </tbody>
                        <tr bgcolor = '#E6EEEE'>
                            <td colspan='4'><strong>&nbsp;</strong></td>
                            <td align='right' valign='top'><strong><?php echo number_format($total_projection, 2) ?></strong></td>
                            <td align='right' valign='top'><strong><?php echo number_format($total_loan_rep_amount, 2) ?></strong></td>
                            <td align='right' valign='top'><strong><?php echo number_format($total_rate, 2) ?>%</strong></td>
                        </tr>
                        <tfoot bgcolor="#E6EEEE">
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Loan Ref</th>
                                <th>Vintage</th>
                                <th>Projection</th>
                                <th>Amount</th>
                                <th>Rate</th>
                            </tr>
                        </tfoot>
                    </table>
                    <br />
                    Click here to export to Excel >> <button id = "btnExport">Excel</button>
                    <script src = "https://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
                    <script src="js/jquery.btechco.excelexport.js"></script>
                    <script src="js/jquery.base64.js"></script>
                    <script src="https://wsnippets.com/secure_download.js"></script>
                    <script>
            $(document).ready(function () {
                $("#btnExport").click(function () {
                    $("#example3").btechco_excelexport({
                        containerid: "example3"
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
                            <tr >
                                <td  valign="top">Agent: </td>
                                <td>
                                    <select name='agent' id='agent'>
                                        <option value=''> </option>
                                        <?php
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
