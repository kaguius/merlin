<?php

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

include_once('../includes/db_conn.php');

/**
 * Description of RecordCrb
 *
 * @author mecmurimi
 */
class RecordCrb {

    public function createCrbEntry($param, $param1) {

        $d = json_decode($param, true);

        $sql = "insert into crb(customerid, crbName, reportDate, reportType, productDisplayName, requester,"
                . "requestNo, pdfId, crn, salutation, surname, otherNames, fullName, nationaIID, passportNo,"
                . "drivingLicenseNo, serviceID, alienID, accountStatus, creditHistory, npaAccounts,"
                . "npaClosedAccounts, npaOpenAccounts, paAccounts, paClosedAccounts, paOpenAccounts)"
                . "VALUES(";
        $sql .= "'" . $param1 . "','" . $d['data']['crbName'] . "','" . $d['data']['reportDate'] . "','"
                . $d['data']['reportType'] . "','" . $d['data']['productDisplayName'] . "','" . $d['data']['requester']
                . "','" . $d['data']['requestNo'] . "','" . $d['data']['pdfId'] . "','" . $d['data']['crn']
                . "','" . $d['data']['salutation'] . "','" . $d['data']['surname'] . "','" . $d['data']['otherNames']
                . "','" . $d['data']['fullName'] . "','" . $d['data']['nationaIID'] . "','" . $d['data']['passportNo']
                . "','" . $d['data']['drivingLicenseNo'] . "','" . $d['data']['serviceID'] . "','" . $d['data']['alienID']
                . "','" . $d['data']['accountStatus'] . "','" . $d['data']['creditHistory'] . "','" . $d['data']['npaAccounts']
                . "','" . $d['data']['npaClosedAccounts'] . "','" . $d['data']['npaOpenAccounts'] . "','" . $d['data']['paAccounts']
                . "','" . $d['data']['paClosedAccounts'] . "','" . $d['data']['paOpenAccounts'] . "');";


        $sql1 = "update users set affordability='0' WHERE id  = '$param1'";




        $result3 = mysql_query($sql3) or die(mysql_error());
        $result = mysql_query($sql) or die(mysql_error());
        $result1 = mysql_query($sql1) or die(mysql_error());

        return $result3 . $result . $result1;
    }

    public function deleteCrbEntry($param1, $param2, $param3) {

        $e = json_decode($param1, true);

        $sql3 = "INSERT INTO business_details (user_id, business_category, business_type, trading_product,
                        trading_location, business_address, stock_value, weekly_sales, spend_stock, income_explanation,
                        business_rent, business_utilities, employees, licensing, storage, transport, house_rent,
                        house_utilities, food_expense, school_fees, weekly_cont, chama_members, chama_payout,
                        payout_freq, stock_neat, ledger_book, sales_activity, permanent_operation, proof_ownership,
                        forthcoming, market_authorities, sound_reputation, lend, lend_amount, restocking_ratio,
                        stock_health_multiplier, UID,no_of_employees,bus_flag,incom_flag,exp_flag,pers_flag,chama_flag,
                        assess_flag,completed, bank_account, bank_account_holder, credit, loan_account, loan_number,
                        daily_customers) VALUES(";

        $sql3 .= "'" . $e['user_id'] . "','" . $e['business_category'] . "','" . $e['business_type'] . "','"
                . $e['trading_product'] . "','" . $e['trading_location'] . "','" . $e['business_address'] . "','"
                . $e['stock_value'] . "','" . $e['weekly_sales'] . "','" . $e['spend_stock'] . "','"
                . $e['income_explanation'] . "','" . $e['business_rent'] . "','" . $e['business_utilities'] . "','"
                . $e['employees'] . "','" . $e['licensing'] . "','" . $e['storage'] . "','"
                . $e['transport'] . "','" . $e['house_rent'] . "','" . $e['house_utilities'] . "','"
                . $e['food_expense'] . "','" . $e['school_fees'] . "','" . $e['weekly_cont'] . "','"
                . $e['chama_members'] . "','" . $e['chama_payout'] . "','" . $e['payout_freq'] . "','"
                . $e['stock_neat'] . "','" . $e['ledger_book'] . "','" . $e['sales_activity'] . "','"
                . $e['permanent_operation'] . "','" . $e['proof_ownership'] . "','" . $e['forthcoming'] . "','"
                . $e['market_authorities'] . "','" . $e['sound_reputation'] . "','" . $e['lend'] . "','"
                . $e['lend_amount'] . "','" . $e['restocking_ratio'] . "','" . $e['stock_health_multiplier'] . "','"
                . $e['UID'] . "','" . $e['no_of_employees'] . "','" . $e['bus_flag'] . "','"
                . $e['incom_flag'] . "','" . $e['exp_flag'] . "','" . $e['pers_flag'] . "','"
                . $e['chama_flag'] . "','" . $e['assess_flag'] . "','" . $e['completed'] . "','"
                . $e['bank_account'] . "','" . $e['bank_account_holder'] . "','" . $e['credit'] . "','"
                . $e['loan_account'] . "','" . $e['loan_number'] . "','" . $e['daily_customers'] . "');";



        $sql2 = "update crb set deleted='1' WHERE customerid = '$param2' ORDER BY request_time DESC LIMIT 1";

        $sql1 = "update users set affordability='$param3' WHERE id  = '$param2'";

        $result3 = mysql_query($sql3) or die(mysql_error());
        $result2 = mysql_query($sql2) or die(mysql_error());
        $result1 = mysql_query($sql1) or die(mysql_error());

        return $result3 . $result2 . $result1;
    }

    public function updateBusinessDetails($param1, $param2, $param3) {

        $e = json_decode($param1, true);

        $sql3 = "INSERT INTO business_details (user_id, business_cycle, business_category, business_type, trading_product,
                        trading_location, business_address, stock_value, weekly_sales, spend_stock, income_explanation,
                        business_rent, business_utilities, employees, licensing, storage, transport, house_rent,
                        house_utilities, food_expense, school_fees, weekly_cont, chama_members, chama_payout,
                        payout_freq, stock_neat, ledger_book, sales_activity, permanent_operation, proof_ownership,
                        forthcoming, market_authorities, sound_reputation, lend, lend_amount, restocking_ratio,
                        stock_health_multiplier, UID,no_of_employees,bus_flag,incom_flag,exp_flag,pers_flag,chama_flag,
                        assess_flag,completed, bank_account, bank_account_holder, credit, loan_account, loan_number,
                        daily_customers, weekly_restock) VALUES(";

        $sql3 .= "'" . $e['user_id'] . "','" . $e['business_cycle'] . "', '" . $e['business_category'] . "','" . $e['business_type'] . "','"
                . $e['trading_product'] . "','" . $e['trading_location'] . "','" . $e['business_address'] . "','"
                . $e['stock_value'] . "','" . $e['weekly_sales'] . "','" . $e['spend_stock'] . "','"
                . $e['income_explanation'] . "','" . $e['business_rent'] . "','" . $e['business_utilities'] . "','"
                . $e['employees'] . "','" . $e['licensing'] . "','" . $e['storage'] . "','"
                . $e['transport'] . "','" . $e['house_rent'] . "','" . $e['house_utilities'] . "','"
                . $e['food_expense'] . "','" . $e['school_fees'] . "','" . $e['weekly_cont'] . "','"
                . $e['chama_members'] . "','" . $e['chama_payout'] . "','" . $e['payout_freq'] . "','"
                . $e['stock_neat'] . "','" . $e['ledger_book'] . "','" . $e['sales_activity'] . "','"
                . $e['permanent_operation'] . "','" . $e['proof_ownership'] . "','" . $e['forthcoming'] . "','"
                . $e['market_authorities'] . "','" . $e['sound_reputation'] . "','" . $e['lend'] . "','"
                . $e['lend_amount'] . "','" . $e['restocking_ratio'] . "','" . $e['stock_health_multiplier'] . "','"
                . $e['UID'] . "','" . $e['no_of_employees'] . "','" . $e['bus_flag'] . "','"
                . $e['incom_flag'] . "','" . $e['exp_flag'] . "','" . $e['pers_flag'] . "','"
                . $e['chama_flag'] . "','" . $e['assess_flag'] . "','" . $e['completed'] . "','"
                . $e['bank_account'] . "','" . $e['bank_account_holder'] . "','" . $e['credit'] . "','"
                . $e['loan_account'] . "','" . $e['loan_number'] . "','" . $e['daily_customers'] . "','" . $e['weekly_restock'] . "');";

        $result3 = mysql_query($sql3) or die(mysql_error());


//
//        $res = mysql_query($sql1) or die(mysql_error());

        $sql2 = "update users set affordability='$param3' WHERE id  = '$param2'";

//        if ($ls == 0) {
//
//            $sql1 = "update users set affordability='0' WHERE id  = '$param2'";
//        }

        $result1 = mysql_query($sql2) or die(mysql_error());

        return $result3 . $result1;
    }

    public function queryCrb($param) {

        $sql1 = mysql_query("select deleted,customerid from crb "
                . "WHERE customerid = '$param' ORDER BY request_time DESC LIMIT 1");

        if (mysql_num_rows($sql1) > 0) {

            while ($row = mysql_fetch_array($sql1)) {
                $ls = $row['deleted'];

                if ($ls == '0') {
                    $crb_l = 'Listed';
                } else {
                    $crb_l = 'Not Listed';
                }
            }
        } else {

            $crb_l = 'Not Listed';
        }

        return $crb_l;
    }

}
