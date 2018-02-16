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

if ($adminstatus == 4) {
    include_once('includes/header.php');
    ?>
    <script type="text/javascript">
        document.location = "insufficient_permission.php";
    </script>
    <?php
} else {
    include_once('includes/db_conn.php');

    $transactiontime = date("Y-m-d G:i:s");
    $page_title = "Arrears Management - Field Agent Assignment";
    include_once('includes/header.php');

    $filter_month = date("m");
    $filter_year = date("Y");
    $filter_day = date("d");
    $current_date = $filter_year . '-' . $filter_month . '-' . $filter_day;

    $assignment = "";

    $filter_clerk = 0;
    if (!empty($_GET)) {
        $filter_clerk = $_GET['clerk'];
        $filter_start_date = date('Y-m-d', strtotime(str_replace('-', '/', $_GET['report_start_date'])));
    }
    ?>
    <div id="page">
        <div id="content">
            <div class="post">
                <h2><?php echo $page_title ?></h2>
                <form id="frmCreatePropertyItem" name="frmCreatePropertyItem" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <table width="100%" border="0" cellspacing="2" cellpadding="2" class="display" id="exampl">
                        <thead bgcolor="#E6EEEE">
                            <tr>
                                <th>Code</th>
                                <th>Name</th>
                                <th>Branch</th>
                                <th>Mobile</th>
                                <th>Due</th>
                                <th>Amount</th>
                                <th>Repayments</th>
                                <th>Balance</th>
                                <th>Status</th>
                                <th>Assigned to Collections Agent</th>
                                <th>Assign to Field Agent</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql_get_field_agent_unassigned_loans = mysql_query("select loan_id, loan_code, customer_id, customer_station, loan_mobile, loan_due_date, loan_total_interest, loan_status, arrears_assigned, current_collector from loan_application where late_status != 0 and late_status != '1' and (field_agent = '0' OR field_agent IS NULL) and (edc = '0' OR edc IS NULL) and loan_due_date !='' and loan_status != '13' and loan_status != '2' and loan_status != '12' and loan_status != '14' and loan_status != '11' order by loan_id asc", $dbh1);
                            $intcount = 0;
                            $total_loan_rep_amount = 0;

                            while ($row = mysql_fetch_array($sql_get_field_agent_unassigned_loans)) {
                                $intcount++;
                                $loan_id = $row['loan_id'];
                                $loan_code = $row['loan_code'];
                                $customer_id = $row['customer_id'];
                                $arrears_assigned = $row['arrears_assigned'];
                                $loan_mobile = substr($row['loan_mobile'], 3);
                                $loan_due_date = date("d M, Y", strtotime($row['loan_due_date']));
                                $loan_total_interest = $row['loan_total_interest'];                                
                                $loan_status = $row['loan_status'];
                                $customer_station = $row['customer_station'];
                                $previous_collector = $row['current_collector'];

                                $sql_get_customer_station = mysql_query("select id, stations from stations where id = '$customer_station'", $dbh1);
                                while ($row = mysql_fetch_array($sql_get_customer_station)) {
                                    $stations = ucwords(strtolower($row['stations']));
                                }                                

                                $sql_get_total_loan_repayments = mysql_query("select sum(loan_rep_amount) repayments from loan_repayments where loan_rep_code = '$loan_code' group by loan_rep_code", $dbh1);
                                while ($row = mysql_fetch_array($sql_get_total_loan_repayments)) {
                                    if (is_null($row['repayments']) || $row['repayments'] == '0') {
                                        $repayments = 0;
                                    } else {
                                        $repayments = $row['repayments'];
                                    }
                                }

                                $balance = $loan_total_interest - $repayments;

                                $sql_get_customer_name = mysql_query("select first_name, last_name from users where id = '$customer_id'", $dbh1);
                                while ($row = mysql_fetch_array($sql_get_customer_name)) {
                                    $first_name = ucwords(strtolower($row['first_name']));
                                    $last_name = ucwords(strtolower($row['last_name']));
                                    $name = $first_name . ' ' . $last_name;
                                }

                                $sql_get_customer_status = mysql_query("select status from customer_status where id = '$loan_status'", $dbh1);
                                while ($row = mysql_fetch_array($sql_get_customer_status)) {
                                    $status = $row['status'];
                                }

                                if ($balance > 0) {
                                    // Loan is not cleared

                                    if ($intcount % 2 == 0) {
                                        $display = '<tr bgcolor = #F0F0F6>';
                                    } else {
                                        $display = '<tr>';
                                    }

                                    echo $display;
                                    echo "<td valign='top'>$loan_code</td>";
                                    echo "<td valign='top'>$name</td>";
                                    echo "<td valign='top'>$stations</td>";
                                    echo "<td valign='top'>$loan_mobile</td>";
                                    echo "<td valign='top'>$loan_due_date</td>";
                                    echo "<td valign='top' align='right'>" . number_format($loan_total_interest, 2) . "</td>";
                                    echo "<td valign='top' align='right'>" . number_format($repayments, 2) . "</td>";
                                    echo "<td valign='top' align='right'>" . number_format($balance, 2) . "</td>";

                                    echo "<input type='hidden' id='loan_code[$loan_id]' name='loan_code[$loan_id]' value='$loan_code'>";
                                    echo "<td valign='top'>$status</td>";
                                    echo "<td valign='top'>$arrears_assigned</td>";
                                    echo "<td valign='top'>";
                                    echo "<input type='checkbox' name='assign[$loan_id]' id='assign[$loan_id]' value='$loan_id'>";
                                    echo "</td>";
                                    echo "</tr>";
                                }

                                echo "<input type='hidden' id='loan_id' name='loan_id' value='$loan_id'>";
                                echo "<input type='hidden' id='previous_collector' name='previous_collector' value='$previous_collector'>";                                
                            }

                            if ($station == '3') {
                                echo "<tr>";
                                echo "Field Agent: <select name='field_agent' id='field_agent'>";
                                echo "<option value=''> </option>";

                                $sql_get_field_agent_details = mysql_query("select user_profiles.id, first_name, last_name, stations.stations from user_profiles inner join stations on stations.id = user_profiles.station where title = '10' and user_status = '1' order by first_name asc", $dbh1);

                                while ($row = mysql_fetch_array($sql_get_field_agent_details)) {
                                    $field_agent_id = $row['id'];
                                    $first_name = $row['first_name'];
                                    $last_name = $row['last_name'];
                                    echo "<option value='$field_agent_id'>" . $first_name . " " . $last_name . "</option>";
                                }

                                echo "</select>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                        <tfoot bgcolor="#E6EEEE">
                            <tr>
                                <th>Code</th>
                                <th>Name</th>
                                <th>Branch</th>
                                <th>Mobile</th>
                                <th>Due</th>
                                <th>Amount</th>
                                <th>Repayments</th>
                                <th>Balance</th>
                                <th>Status</th>
                                <th>Assigned to Collections Agent</th>
                                <th>Assign to Field Agent</th>
                            </tr>
                        </tfoot>
                    </table>

                    <table border="0" width="100%">
                        <tr>
                            <td valign="top">
                                <button name="btnNewCard" id="button">Submit</button>
                            </td>
                            <td align="right">
                                <button name="reset" id="button2" type="reset">Reset</button>
                            </td>		
                        </tr>
                    </table>
                </form>
            </div>
        </div>
        <br class="clearfix" />
    </div>

    <?php
    if (!empty($_POST)) {
        $field_agent = $_POST['field_agent'];
        $loan_code = $_POST['loan_code'];
        $assign = $_POST['assign'];
        $previous_collector = $_POST['previous_collector'];

        $loan_id = $_POST['loan_id'];

        for ($assignment = 1; $assignment <= $loan_id; $assignment++) {
            if ($assign[$assignment] != 0) {
                $assign_unit = $assign[$assignment];

                if ($field_agent != "") {
                    $sql_update_field_agent = "update loan_application set current_collector = '$field_agent', field_agent='$field_agent', assigned_field_agent = '$current_date' where loan_id = '$assign_unit'";
                    $sql_record_field_agent_change = "insert into change_log(UID, table_name, customer_id, variable, loan_code, old_value, new_value, transactiontime) values('$userid', 'loan_application', '$user_id', 'field_agent', '$loan_code', '0', '$field_agent', '$transactiontime')";
                    $sql_record_current_collector_change = "insert into change_log(UID, table_name, customer_id, variable, loan_code, old_value, new_value, transactiontime) values('$userid', 'loan_application', '$user_id', 'current_collector', '$loan_code', '', '$field_agent', '$transactiontime')";
                    
                    $result = mysql_query($sql_update_field_agent, $dbh1);
                    $result_record_field_agent_change = mysql_query($sql_record_field_agent_change, $dbh1);
                    $result_record_current_collector_change = mysql_query($sql_record_current_collector_change, $dbh1);
                }
            }
        }

        $query = "arrears_assign_FA_new.php";
        ?>
        <script type="text/javascript">
            document.location = "<?php echo $query ?>";
        </script>
        <?php
    }
}
include_once('includes/footer.php');
?>
