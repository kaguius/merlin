<?php

include_once('../includes/db_conn.php');
include_once('../beans/Loan_application.php');
include_once('../beans/Loan_repayment.php');

/**
 * Description of clear_loan
 *
 * @author mecmurimi
 */
class clear_loan {

    function get_loan_details($loan_code) {

        $la = new Loan_application;

        $sql = mysql_query("select * from loan_application where loan_code = '$loan_code'");

        while ($row = mysql_fetch_array($sql)) {

            $la->setLoan_date($row['loan_date']);
            $la->setLoan_due_date($row['loan_due_date']);
            $la->setCustomer_id($row['customer_id']);
            $la->setCustomer_station($row['customer_station']);
            $la->setLoan_mobile($row['loan_mobile']);
            $la->setInitiation_fee($row['initiation_fee']);
            $la->setLoan_amount($row['loan_amount']);
            $la->setLoan_extension($row['loan_extension']);
            $la->setLoan_interest($row['loan_interest']);
            $la->setLoan_total_interest($row['loan_late_interest']);
            $la->setAdmin_fee($row['admin_fee']);
            $la->setWaiver($row['waiver']);
            $la->setAppointment_fee($row['appointment_fee']);
            $la->setEarly_settlement($row['early_settlement']);
            $la->setEarly_settlement_surplus($row['early_settlement_surplus']);
            $la->setFix($row['fix']);
            $la->setJoining_fee($row['joining_fee']);
            $la->setLoan_total_interest($row['loan_total_interest']);
            $la->setLoan_status($row['loan_status']);
            $la->setLoan_code($row['loan_code']);
            $la->setLoan_mpesa_code($row['loan_mpesa_code']);
            $la->setLoan_disbursed($row['loan_disbursed']);
            $la->setLoan_failure_status($row['loan_failure_status']);
            $la->setLoan_pay_id($row['loan_pay_id']);
            $la->setLoan_officer($row['loan_officer']);
            $la->setCollections_officer($row['collections_officer']);
            $la->setCollections_agent($row['collections_agent']);
            $la->setField_agent($row['field_agent']);
            $la->setEdc($row['edc']);
            $la->setLate_status($row['late_status']);
            $la->setSeven_day_status($row['seven_day_status']);
            $la->setFortyeight_hr_status($row['fortyeight_hr_status']);
            $la->setFinal_status($row['final_status']);
            $la->setMsg($row['msg']);
            $la->setComment($row['comment']);
            $la->setCustomer_state($row['customer_state']);
            $la->setUID($row['UID']);
            $la->setVintage($row['vintage']);
            $la->setArreardays($row['arreardays']);
            $la->setArrears_assigned($row['arrears_assigned']);
            $la->setAssigned_field_agent($row['assigned_field_agent']);
            $la->setAssigned_EDC($row['assigned_EDC']);
            $la->setLoan_creation($row['loan_creation']);
            $la->setCrb($row['crb']);
            $la->setCurrent_collector($row['current_collector']);
            $la->setTransactiontime($row['transactiontime']);
        }
        
        $ln_rep = new clear_loan();
        $ln_rep = $ln_rep->get_loan_repayments($loan_code);
        $ln_bal = $la->getLoan_total_interest() - $ln_rep;

        return $ln_bal;
    }

    function get_loan_repayments($loan_rep_code) {

        $repayments = 0;

        $sql4 = mysql_query("select sum(loan_rep_amount)repayments from loan_repayments where "
                . "loan_rep_code = '$loan_rep_code' group by loan_rep_code");
        while ($row = mysql_fetch_array($sql4)) {
            $repayments = $row['repayments'];
            if ($repayments == "") {
                $repayments = 0;
            }
        }

        return $repayments;
    }

}
