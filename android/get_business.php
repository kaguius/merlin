<?php

// array for JSON response
$response = array();

// include db connect class
require_once 'db_connect.php';

// connecting to db
$db = new DB_CONNECT();

// check for post data
if (isset($_GET["user_id"])) {
    $user_id = $_GET['user_id'];

    // get a product from products table
    $result = mysql_query("SELECT * FROM business_details where user_id = $user_id");

    if (!empty($result)) {
        // check for empty result
        if (mysql_num_rows($result) > 0) {

            $result = mysql_fetch_array($result);


            // temp user array
            $business = array();
            $business["id"] = $result["id"];
            $business["user_id"] = $result["user_id"];
            $business["business_category"] = $result["business_category"];
            $business["business_type"] = $result["business_type"];
            $business["trading_product"] = $result["trading_product"];
            $business["trading_location"] = $result["trading_location"];
            $business["business_address"] = $result["business_address"];
            $business["stock_value"] = $result["stock_value"];
            $business["weekly_sales"] = $result["weekly_sales"];
            $business["spend_stock"] = $result["spend_stock"];
            $business["business_rent"] = $result["business_rent"];
            $business["business_utilities"] = $result["business_utilities"];
            $business["employees"] = $result["employees"];
            $business["licensing"] = $result["licensing"];
            $business["storage"] = $result["storage"];
            $business["transport"] = $result["transport"];
            $business["house_rent"] = $result["house_rent"];
            $business["house_utilities"] = $result["house_utilities"];
            $business["food_expense"] = $result["food_expense"];
            $business["school_fees"] = $result["school_fees"];
            $business["weekly_cont"] = $result["weekly_cont"];
            $business["chama_members"] = $result["chama_members"];
            $business["chama_payout"] = $result["chama_payout"];
            $business["payout_freq"] = $result["payout_freq"];
            $business["stock_neat"] = $result["stock_neat"];
            $business["ledger_book"] = $result["ledger_book"];
            $business["sales_activity"] = $result["sales_activity"];
            $business["permanent_operation"] = $result["permanent_operation"];
            $business["proof_ownership"] = $result["proof_ownership"];
            $business["forthcoming"] = $result["forthcoming"];
            $business["market_authorities"] = $result["market_authorities"];
            $business["sound_reputation"] = $result["sound_reputation"];
            $business["lend"] = $result["lend"];
            $business["lend_amount"] = $result["lend_amount"];
            $business["restocking_ratio"] = $result["restocking_ratio"];
            $business["stock_health_multiplier"] = $result["stock_health_multiplier"];
            $business["UID"] = $result["UID"];
            $business["transactiontime"] = $result["transactiontime"];

            // success
            $response["success"] = 1;

            // user node
            $response["businesses"] = array();

            array_push($response["businesses"], $business);

            // echoing JSON response
            echo json_encode($response);
        } else {

            $result1 = mysql_query("INSERT INTO business_details(user_id) values($user_id)");

            // check if row inserted or not
            if ($result1) {

                $result2 = mysql_query("SELECT * FROM business_details where user_id = $user_id");
                if (mysql_num_rows($result2) > 0) {

                    $result2 = mysql_fetch_array($result);
                    
                    // temp user array
                    $business = array();
                    $business["id"] = $result2["id"];
                    $business["user_id"] = $result2["user_id"];
                    $business["business_category"] = $result2["business_category"];
                    $business["business_type"] = $result2["business_type"];
                    $business["trading_product"] = $result2["trading_product"];
                    $business["trading_location"] = $result2["trading_location"];
                    $business["business_address"] = $result2["business_address"];
                    $business["stock_value"] = $result2["stock_value"];
                    $business["weekly_sales"] = $result2["weekly_sales"];
                    $business["spend_stock"] = $result2["spend_stock"];
                    $business["business_rent"] = $result2["business_rent"];
                    $business["business_utilities"] = $result2["business_utilities"];
                    $business["employees"] = $result2["employees"];
                    $business["licensing"] = $result2["licensing"];
                    $business["storage"] = $result2["storage"];
                    $business["transport"] = $result2["transport"];
                    $business["house_rent"] = $result2["house_rent"];
                    $business["house_utilities"] = $result2["house_utilities"];
                    $business["food_expense"] = $result2["food_expense"];
                    $business["school_fees"] = $result2["school_fees"];
                    $business["weeekly_cont"] = $result2["weeekly_cont"];
                    $business["chama_members"] = $result2["chama_members"];
                    $business["chama_payout"] = $result2["chama_payout"];
                    $business["payout_frequency"] = $result2["payout_frequency"];
                    $business["stock_neat"] = $result2["stock_neat"];
                    $business["ledger_book"] = $result2["ledger_book"];
                    $business["sales_activity"] = $result2["sales_activity"];
                    $business["permanent_operation"] = $result2["permanent_operation"];
                    $business["proof_ownership"] = $result2["proof_ownership"];
                    $business["forthcoming"] = $result2["forthcoming"];
                    $business["market_authorities"] = $result2["market_authorities"];
                    $business["sound_reputation"] = $result2["sound_reputation"];
                    $business["lend"] = $result2["lend"];
                    $business["lend_amount"] = $result2["lend_amount"];
                    $business["restocking_ratio"] = $result2["restocking_ratio"];
                    $business["stock_health_multiplier"] = $result2["stock_health_multiplier"];
                    $business["UID"] = $result2["UID"];
                    $business["transactiontime"] = $result2["transactiontime"];

                    // success
                    $response["success"] = 1;

                    // user node
                    $response["businesses"] = array();

                    array_push($response["businesses"], $business);

                    // echoing JSON response
                    echo json_encode($response);
                }
            } else {
                // failed to insert row
                $response["success"] = 0;
                $response["message"] = "Oops! An error occurred.";
            }
        }
    } else {
        // no product found
        $response["success"] = 0;
        $response["message"] = "No business found";

        // echo no users JSON
        echo json_encode($response);
    }
} else {
    // required field is missing
    $response["success"] = 0;
    $response["message"] = "Required field(s) is missing";

    // echoing JSON response
    echo json_encode($response);
}
?>