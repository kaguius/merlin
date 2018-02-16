<?php

include_once('../includes/db_conn.php');

function getScore($customer_id, $req_amount, $user) {

    $response = array();

     $resultscore = mysql_query("SELECT fa_id FROM user_profiles WHERE id = '$user'");
    while ($row_score = mysql_fetch_array($resultscore)) {
        $users_id = $row_score['fa_id'];
    }

    $result = mysql_query("SELECT u.id as user_id,u.alt_phone,u.mobile_no,u.stations,u.national_id,u.first_name,u.last_name,u.gender,u.date_of_birth,u.preffered_language,u.marital,u.dependants,u.owns,u.home_occupy,bd.business_category,bd.business_type,bd.id,bd.trading_product,bd.trading_location,bd.business_address,bd.stock_value,bd.weekly_sales,bd.spend_stock,bd.business_rent,bd.business_utilities,bd.employees,bd.licensing,bd.storage,bd.transport,bd.house_rent,bd.house_utilities,bd.food_expense,bd.school_fees,bd.weekly_cont,bd.chama_members,bd.chama_payout,bd.payout_freq,bd.stock_neat,bd.ledger_book,bd.sales_activity,bd.permanent_operation,bd.proof_ownership,bd.forthcoming,bd.market_authorities,bd.sound_reputation FROM users u join business_details bd on u.id = bd.user_id where u.id = '$customer_id' order by bd.transactiontime desc limit 1 ;");

    if (mysql_num_rows($result) > 0) {


        $response["borrower_data"] = array();

        while ($row = mysql_fetch_array($result)) {

            $score = array();
            $score["alt_phone"] = $row["mobile_no"];
            $score["national_id"] = $row["national_id"];
            $score["first_name"] = $row["first_name"];
            $score["last_name"] = $row["last_name"];
            $score["gender"] = $row["gender"];
            $score["date_of_birth"] = $row["date_of_birth"];
            $score["preffered_language"] = $row["preffered_language"];
            $score["marital"] = $row["marital"];
            $score["dependants"] = $row["dependants"];
            $score["owns"] = $row["owns"];
            $score["home_occupy"] = $row["home_occupy"];
            $score["customer_id"] = $customer_id;
            $score["customer_station"] = $row["stations"];
            $score["initiation_fee"] = 500;
            $score["business_category"] = $row["business_category"];
            $score["business_type"] = $row["business_type"];
            $score["business_id"] = $row["id"];
            $score["trading_product"] = $row["trading_product"];
            $score["trading_location"] = $row["trading_location"];
            $score["business_address"] = $row["business_address"];
            $score["stock_value"] = $row["stock_value"];
            $score["weekly_sales"] = $row["weekly_sales"];
            $score["spend_stock"] = $row["spend_stock"];
            $score["business_rent"] = $row["business_rent"];
            $score["business_utilities"] = $row["business_utilities"];
            $score["employees"] = $row["employees"];
            $score["licensing"] = $row["licensing"];
            $score["storage"] = $row["storage"];
            $score["transport"] = $row["transport"];
            $score["house_rent"] = $row["house_rent"];
            $score["house_utilities"] = $row["house_utilities"];
            $score["food_expense"] = $row["food_expense"];
            $score["school_fees"] = $row["school_fees"];
            $score["weekly_cont"] = $row["weekly_cont"];
            $score["chama_members"] = $row["chama_members"];
            $score["chama_payout"] = $row["chama_payout"];
            $score["payout_freq"] = $row["payout_freq"];
            $score["stock_neat"] = $row["stock_neat"];
            $score["ledger_book"] = $row["ledger_book"];
            $score["sales_activity"] = $row["sales_activity"];
            $score["permanent_operation"] = $row["permanent_operation"];
            $score["proof_ownership"] = $row["proof_ownership"];
            $score["forthcoming"] = $row["forthcoming"];
            $score["market_authorities"] = $row["market_authorities"];
            $score["sound_reputation"] = $row["sound_reputation"];
            $score["requested_loan_size"] = $req_amount;

            $response["borrower_data"] = $score;
            $response["company_id"] = 7;
            $response["external_user_id"] = $user;
            $response["mobile_number"] = $row["mobile_no"];
            $response["borrower_id"] = "$customer_id";
            $response["async_callback_url"] = null;

            
        }
    }

    return $response;
}

$data = getScore('6194', '47000');
$data_string = json_encode($data);

$url = "http://10.0.1.245:8080/score?key=eda2c6a97bec3e4fea2a97f9c7e77";

echo $data_string;

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Content-Length: ' . strlen($data_string)));

$result = curl_exec($ch);

echo $result;

//function saveScore($sql) {
  //return mysql_query($sql);
//}



