<?php

/*
 * Following code will get single product details
 * A product is identified by product id (pid)
 */

// array for JSON response
$response = array();

// include db connect class
require_once 'db_connect.php';

// connecting to db
$db = new DB_CONNECT();

// check for post data
if (isset($_GET["loan_officer"]) && isset($_GET['admin_status'])) {
    $loan_officer = $_GET['loan_officer'];
    $admin_status = $_GET['admin_status'];

    if ($admin_status === "'1'") {
        $result = mysql_query("SELECT * FROM users where national_id !='' and national_id is not null and passportfileupload !='' and passportfileupload is not null order by transactiontime desc LIMIT 20;");
    } else {
        $result = mysql_query("SELECT * FROM users where (loan_officer = $loan_officer or collections_officer = $loan_officer) and (national_id !='' or national_id is not null) order by transactiontime desc LIMIT 20;");
    }

    if (!empty($result)) {
        // check for empty resultproducts
        if (mysql_num_rows($result) > 0) {

            // looping through all results
            // users node
            $response["customers"] = array();

            while ($row = mysql_fetch_array($result)) {

                // temp user array
                $customer = array();
                $customer["id"] = $row["id"];
                $customer["passportfileupload"] = $row["passportfileupload"];
                $customer["resumefileupload"] = $row["resumefileupload"];
                $customer["resumefileupload_back"] = $row["resumefileupload_back"];
                $customer["mobile_no"] = $row["mobile_no"];
                $customer["title"] = $row["title"];
                $customer["first_name"] = $row["first_name"];
                $customer["last_name"] = $row["last_name"];
                $customer["national_id"] = $row["national_id"];
                $customer["preffered_language"] = $row["preffered_language"];
                $customer["nickname"] = $row["nickname"];
                $customer["date_of_birth"] = $row["date_of_birth"];
                $customer["marital"] = $row["marital"];
                $customer["dependants"] = $row["dependants"];
                $customer["alt_phone"] = $row["alt_phone"];
                $customer["dis_phone"] = $row["dis_phone"];
                $customer["home_address"] = $row["home_address"];
                $customer["owns"] = $row["owns"];
                $customer["home_occupy"] = $row["home_occupy"];
                $customer["stations"] = $row["stations"];
                $customer["affordability"] = $row["affordability"];
                $customer["status"] = $row["status"];
                $customer["loan_officer"] = $row["loan_officer"];
                $customer["collections_officer"] = $row["collections_officer"];
                $customer["collections_agent"] = $row["collections_agent"];
                $customer["next_visit"] = $row["next_visit"];
                $customer["lead_outcome"] = $row["lead_outcome"];
                $customer["ref_first_name"] = $row["ref_first_name"];
                $customer["ref_last_name"] = $row["ref_last_name"];
                $customer["ref_known_as"] = $row["ref_known_as"];
                $customer["ref_phone_number"] = $row["ref_phone_number"];
                $customer["ref_relationship"] = $row["ref_relationship"];
                $customer["asset_list"] = $row["asset_list"];
                $customer["gender"] = $row["gender"];
                $customer["ref_landlord_title"] = $row["ref_landlord_title"];
                $customer["ref_landlord_first_name"] = $row["ref_landlord_first_name"];
                $customer["ref_landlord_last_name"] = $row["ref_landlord_last_name"];
                $customer["ref_landlord_known_as"] = $row["ref_landlord_known_as"];
                $customer["ref_landlord_relationship"] = $row["ref_landlord_relationship"];
                $customer["ref_landlord_phone"] = $row["ref_landlord_phone"];
                $customer["lat"] = $row["lat"];
                $customer["lng"] = $row["lng"];
                $customer["UID"] = $row["UID"];
                $customer["customer_comments"] = $row["customer_comments"];
                $customer["marketing_drive"] = $row["marketing_drive"];
                $customer["mobile_friend"] = $row["mobile_friend"];
                $customer["refer_friend"] = $row["refer_friend"];
                $customer["limit_loan_amount"] = $row["limit_loan_amount"];
                $customer["customer_state"] = $row["customer_state"];
                $customer["transactiontime"] = $row["transactiontime"];
                $customer["override_consq"] = $row["override_consq"];
                $customer["other_sources"] = $row["other_sources"];

                array_push($response["customers"], $customer);
            }
            // success
            $response["success"] = 1;

            // echoing JSON response
            echo json_encode($response);
        } else {
            // no product found
            $response["success"] = 0;
            $response["message"] = "No customer found one";

            // echo no users JSON
            echo json_encode($response);
        }
    } else {
        // no product found
        $response["success"] = 0;
        $response["message"] = "No customer found two";

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