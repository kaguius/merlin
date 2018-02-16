<?php

// array for JSON response
$response = array();

// include db connect class
require_once 'db_connect.php';

// connecting to db
$db = new DB_CONNECT();

// check for post data
if (isset($_GET["id"])) {
    $id = $_GET['id'];

    // get a product from products table
    $result = mysql_query("SELECT * FROM users WHERE id = $id");

    if (!empty($result)) {
        // check for empty result
        if (mysql_num_rows($result) > 0) {

            $result = mysql_fetch_array($result);


            // temp user array
            $customer = array();
            $customer["id"] = $result["id"];
            $customer["passportfileupload"] = $result["passportfileupload"];
            $customer["resumefileupload"] = $result["resumefileupload"];
            $customer["resumefileupload_back"] = $result["resumefileupload_back"];
            $customer["mobile_no"] = $result["mobile_no"];
            $customer["title"] = $result["title"];
            $customer["first_name"] = $result["first_name"];
            $customer["last_name"] = $result["last_name"];
            $customer["national_id"] = $result["national_id"];
            $customer["preffered_language"] = $result["preffered_language"];
            $customer["nickname"] = $result["nickname"];
            $customer["date_of_birth"] = $result["date_of_birth"];
            $customer["marital"] = $result["marital"];
            $customer["dependants"] = $result["dependants"];
            $customer["alt_phone"] = $result["alt_phone"];
            $customer["dis_phone"] = $result["dis_phone"];
            $customer["home_address"] = $result["home_address"];
            $customer["owns"] = $result["owns"];
            $customer["home_occupy"] = $result["home_occupy"];
            $customer["stations"] = $result["stations"];
            $customer["affordability"] = $result["affordability"];
            $customer["status"] = $result["status"];
            $customer["loan_officer"] = $result["loan_officer"];
            $customer["collections_officer"] = $result["collections_officer"];
            $customer["collections_agent"] = $result["collections_agent"];
            $customer["next_visit"] = $result["next_visit"];
            $customer["lead_outcome"] = $result["lead_outcome"];
            $customer["ref_first_name"] = $result["ref_first_name"];
            $customer["ref_last_name"] = $result["ref_last_name"];
            $customer["ref_known_as"] = $result["ref_known_as"];
            $customer["ref_phone_number"] = $result["ref_phone_number"];
            $customer["ref_relationship"] = $result["ref_relationship"];
            $customer["asset_list"] = $result["asset_list"];
            $customer["gender"] = $result["gender"];
            $customer["ref_landlord_title"] = $result["ref_landlord_title"];
            $customer["ref_landlord_first_name"] = $result["ref_landlord_first_name"];
            $customer["ref_landlord_last_name"] = $result["ref_landlord_last_name"];
            $customer["ref_landlord_known_as"] = $result["ref_landlord_known_as"];
            $customer["ref_landlord_relationship"] = $result["ref_landlord_relationship"];
            $customer["ref_landlord_phone"] = $result["ref_landlord_phone"];
            $customer["lat"] = $result["lat"];
            $customer["lng"] = $result["lng"];
            $customer["UID"] = $result["UID"];
            $customer["customer_comments"] = $result["customer_comments"];
            $customer["marketing_drive"] = $result["marketing_drive"];
            $customer["mobile_friend"] = $result["mobile_friend"];
            $customer["refer_friend"] = $result["refer_friend"];
            $customer["limit_loan_amount"] = $result["limit_loan_amount"];
            $customer["customer_state"] = $result["customer_state"];
            $customer["transactiontime"] = $result["transactiontime"];
            $customer["override_consq"] = $result["override_consq"];
            $customer["other_sources"] = $result["other_sources"];


            // success
            $response["success"] = 1;

            // user node
            $response["customers"] = array();

            array_push($response["customers"], $customer);

            // echoing JSON response
            echo json_encode($response);
        } else {
            // no product found
            $response["success"] = 0;
            $response["message"] = "No customer found";

            // echo no users JSON
            echo json_encode($response);
        }
    } else {
        // no product found
        $response["success"] = 0;
        $response["message"] = "No customer found";

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