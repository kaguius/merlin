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
        document.location = "insufficient_permission.php";
    </script>
    <?php
} else {
    $transactiontime = date("Y-m-d G:i:s");
    $page_title = "Payments Search Filter";
    include_once('includes/header.php');
    $filter_clerk = 0;
    if (!empty($_GET)) {
        $mpesa_code = $_GET['mpesa_code'];
        $loan_code = $_GET['loan_code'];
    }
    include_once('includes/db_conn.php');
    if ($mpesa_code != "" || $loan_code != "") {
        $page_title = "Payments Search Filter";
        ?>
        <div id="page">
            <div id="content">
                <div class="post">
                    <h2><?php echo $page_title ?></h2>
                    <h3>Payments Table Details</h3>
                    <table width="100%" border="0" cellspacing="2" cellpadding="2" class="display" id="example4">
                        <thead bgcolor="#E6EEEE">
                            <tr bgcolor='#fff'>
                                <th>#</th>
                                <th>M. Money</th>
                                <th>Date</th>
                                <th>Loan Name</th>
                                <th>Loan Mobile</th>
                                <th>Loan Ref.</th>
                                <th>Repayment</th>
                                <th>View</th>
                            </tr>
                        </thead>
                        <tbody>
        <?php
        if ($mpesa_code != "") {
            if ($station == '3' || $station == '4') {
                $sql = mysql_query("select customer_id, loan_rep_id, loan_rep_date, loan_rep_mobile, loan_rep_amount, loan_rep_mpesa_code, loan_rep_code from loan_repayments where loan_rep_mpesa_code = '$mpesa_code' order by loan_rep_date asc");
            } else {
                $sql = mysql_query("select customer_id, loan_rep_id, loan_rep_date, loan_rep_mobile, loan_rep_amount, loan_rep_mpesa_code, loan_rep_code from loan_repayments where loan_rep_mpesa_code = '$mpesa_code' and customer_station = '$station' order by loan_rep_date asc");
            }
        } else {
            if ($station == '3' || $station == '4') {
                $sql = mysql_query("select customer_id, loan_rep_id, loan_rep_date, loan_rep_mobile, loan_rep_amount, loan_rep_mpesa_code, loan_rep_code from loan_repayments where loan_rep_code = '$loan_code' order by loan_rep_date asc");
            } else {
                $sql = mysql_query("select customer_id, loan_rep_id, loan_rep_date, loan_rep_mobile, loan_rep_amount, loan_rep_mpesa_code, loan_rep_code from loan_repayments where loan_rep_code = '$loan_code' and customer_station = '$station' order by loan_rep_date asc");
            }
        }
        $intcount = 0;
        $total_loan_rep_amount = 0;
        while ($row = mysql_fetch_array($sql)) {
            $intcount++;
            $loan_rep_id = $row['loan_rep_id'];
            $customer_id = $row['customer_id'];
            $loan_rep_date = $row['loan_rep_date'];
            $loan_rep_mobile = $row['loan_rep_mobile'];
            $loan_rep_amount = $row['loan_rep_amount'];
            $loan_rep_acc_id = $row['loan_rep_acc_id'];
            $loan_rep_mpesa_code = $row['loan_rep_mpesa_code'];
            $loan_rep_code = $row['loan_rep_code'];
            $loan_rep_code = ucwords(strtolower($loan_rep_code));

            $sql2 = mysql_query("select first_name, last_name from users where mobile_no = '$loan_rep_mobile'");
            while ($row = mysql_fetch_array($sql2)) {
                $first_name = $row['first_name'];
                $last_name = $row['last_name'];
                $loan_rep_name = $first_name . " " . $last_name;
                $loan_rep_name = ucwords(strtolower($loan_rep_name));
            }

            if ($intcount % 2 == 0) {
                $display = '<tr bgcolor = #F0F0F6>';
            } else {
                $display = '<tr>';
            }
            echo $display;
            echo "<td valign='top'>$intcount.</td>";
            echo "<td valign='top'>$loan_rep_mpesa_code</td>";
            echo "<td valign='top'>$loan_rep_date</td>";
            echo "<td valign='top'>$loan_rep_name</td>";
            echo "<td valign='top'>$loan_rep_mobile</td>";
            echo "<td valign='top'>$loan_rep_code</td>";
            echo "<td valign='top' align='right'>" . number_format($loan_rep_amount, 2) . "</td>";
            echo "<td valign='top'><a href='customer_loans.php?user_id=$customer_id&mode=edit'><img src='images/edit.png' width='35px'></a></td>";
            echo "</tr>";
            $total_loan_rep_amount = $total_loan_rep_amount + $loan_rep_amount;
        }
        ?>
                        </tbody>
                    </table>
                </div>
                <p>&nbsp;</p>
                <h3>Loan Applications Details</h3>
                <table width="100%" border="0" cellspacing="2" cellpadding="2" class="display" id="example2">
                    <thead bgcolor="#E6EEEE">
                        <tr bgcolor='#fff'>
                            <th>#</th>
                            <th>M. Money</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Loan Name</th>
                            <th>Loan Mobile</th>
                            <th>Loan Ref.</th>
                            <th>Amount</th>
                            <th>View</th>
                        </tr>
                    </thead>
                    <tbody>
        <?php
        if ($station == '3' || $station == '4') {
            $sql = mysql_query("select customer_id, loan_id, loan_date, loan_due_date, loan_mobile, loan_amount, loan_total_interest, loan_status, loan_code, loan_mpesa_code from loan_application where loan_code = '$loan_code' order by loan_date asc");
        } else {
            $sql = mysql_query("select customer_id, loan_id, loan_date, loan_due_date, loan_mobile, loan_amount, loan_total_interest, loan_status, loan_code, loan_mpesa_code from loan_application where loan_code = '$loan_code' and customer_station = '$station' order by loan_date asc");
        }
        $interest = 0;
        $total_loan_amount = 0;
        $total_interest = 0;
        $total_loan_total_interest = 0;
        $intcount = 0;
        while ($row = mysql_fetch_array($sql)) {
            $intcount++;
            $loan_id = $row['loan_id'];
            $loan_date = $row['loan_date'];
            $customer_id = $row['customer_id'];
            $loan_expiry_date = $row['Loan_expiry_date'];
            $loan_agent_mobile = $row['loan_agent_mobile'];
            $loan_mobile = $row['loan_mobile'];
            $loan_amount = $row['loan_amount'];
            $loan_total_interest = $row['loan_total_interest'];
            $loan_acc_no = $row['loan_acc_no'];
            $loan_acc_no = ucwords(strtolower($loan_acc_no));
            $loan_status = $row['loan_status'];
            $loan_code = $row['loan_code'];
            $Loan_Pay_ID = $row['Loan_Pay_ID'];
            $loan_mpesa_code = $row['loan_mpesa_code'];
            $Loan_Failure_Status = $row['Loan_Failure_Status'];
            $interest = $loan_total_interest - $loan_amount;

            $sql2 = mysql_query("select status from customer_status where id = '$loan_status'");
            while ($row = mysql_fetch_array($sql2)) {
                $loan_status_name = $row['status'];
            }
            $sql2 = mysql_query("select first_name, last_name from users where mobile_no = '$loan_mobile'");
            while ($row = mysql_fetch_array($sql2)) {
                $first_name = $row['first_name'];
                $last_name = $row['last_name'];
                $loan_name = $first_name . " " . $last_name;
                $loan_name = ucwords(strtolower($loan_name));
            }

            if ($intcount % 2 == 0) {
                $display = '<tr bgcolor = #F0F0F6>';
            } else {
                $display = '<tr>';
            }
            echo $display;
            echo "<td valign='top'>$intcount.</td>";
            echo "<td valign='top'>$loan_mpesa_code</td>";
            echo "<td valign='top'>$loan_status_name</td>";
            echo "<td valign='top'>$loan_date</td>";
            echo "<td valign='top'>$loan_name</td>";
            echo "<td valign='top'>$loan_mobile</td>";
            echo "<td valign='top'>$loan_code</td>";
            echo "<td valign='top' align='right'>" . number_format($loan_total_interest, 2) . "</td>";
            echo "<td valign='top'><a href='customer_loans.php?user_id=$customer_id'><img src='images/edit.png' width='35px'></a></td>";
            echo "</tr>";
            $total_loan_amount = $total_loan_amount + $loan_amount;
            $total_interest = $total_interest + $interest;
            $total_loan_total_interest = $total_loan_total_interest + $loan_total_interest;
        }
        ?>
                    </tbody>	
                    </tfoot>
                </table>
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
                                <td  valign="top">Search by Mobile Money Code: </td>
                                <td>
                                    <input title="Enter the Phone Number" value="" id="mpesa_code" name="mpesa_code" type="text" maxlength="100" class="main_input" size="15" /> - OR -
                                </td> 						
                            </tr>
                            <tr >
                                <td  valign="top">Search by Loan Code: </td>
                                <td>
                                    <input title="Enter the Phone Number" value="" id="loan_code" name="loan_code" type="text" maxlength="100" class="main_input" size="15" /> - OR -
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
