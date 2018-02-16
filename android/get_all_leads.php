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
        $result = mysql_query("SELECT * FROM users where national_id ='' or national_id is null order by transactiontime desc LIMIT 20;");
    } else {
        $result = mysql_query("SELECT * FROM users where (loan_officer = $loan_officer or collections_officer = $loan_officer) and (national_id ='' or national_id is null) order by transactiontime desc LIMIT 20;");
    }

    if (!empty($result)) {
        // check for empty resultproducts
        if (mysql_num_rows($result) > 0) {

            // looping through all results
            // users node
            $response["leads"] = array();

            while ($row = mysql_fetch_array($result)) {

                // temp user array
                $lead = array();
                $lead["id"] = $row["id"];
                $lead["first_name"] = mysql_real_escape_string($row["first_name"]);
                $lead["last_name"] = mysql_real_escape_string($row["last_name"]);
                $lead["mobile_no"] = mysql_real_escape_string($row["mobile_no"]);
                $lead["loan_officer"] = mysql_real_escape_string($row["loan_officer"]);
                $lead["collections_officer"] = mysql_real_escape_string($row["collections_officer"]);
                $lead["lead_outcome"] = mysql_real_escape_string($row["lead_outcome"]);
                $lead["next_visit"] = mysql_real_escape_string($row["next_visit"]);

                array_push($response["leads"], $lead);
            }
            // success
            $response["success"] = 1;

            // echoing JSON response
            echo json_encode($response);
        } else {
            // no product found
            $response["success"] = 0;
            $response["message"] = "No lead found one";

            // echo no users JSON
            echo json_encode($response);
        }
    } else {
        // no product found
        $response["success"] = 0;
        $response["message"] = "No lead found two";

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