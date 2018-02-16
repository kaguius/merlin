<?php

/*
 * Following code will create a new product row
 * All product details are read from HTTP Post Request
 */

// array for JSON response
$response = array();

// check for required fields
if (isset($_POST['myHttpData'])) {
    try {
        $data = json_decode($_POST['myHttpData']);


        //get data from the decoded object
        //enclose in try catch 

        $id = $data->id;
        $first_name = $data->first_name;
        $last_name = $data->last_name;
        $mobile_no = $data->mobile_no;
        $loan_officer = $data->loan_officer;
        $collections_officer = $data->collections_officer;
        $lead_outcome = $data->lead_outcome;
        $next_visit = $data->next_visit;

        // include db connect class
        require_once 'db_connect.php';

        // connecting to db
        $db = new DB_CONNECT();

        // mysql inserting a new row
        $result = mysql_query("UPDATE users SET first_name = COALESCE('$first_name',first_name), last_name = COALESCE('$last_name',last_name), mobile_no = COALESCE('$mobile_no',mobile_no),loan_officer = COALESCE('$loan_officer',loan_officer),collections_officer = COALESCE('$collections_officer',collections_officer),lead_outcome = COALESCE('$lead_outcome',lead_outcome),next_visit = COALESCE('$next_visit',next_visit) WHERE id = '$id'");

        // check if row inserted or not
        if ($result) {
            // successfully inserted into database
            $response["success"] = 1;
            $response["message"] = "Lead successfully updated.";

            // echoing JSON response
            // echo json_encode($response);
        } else {
            // failed to insert row
            $response["success"] = 0;
            $response["message"] = "Oops! An error occurred.";
        }
    } catch (Exception $e) {
        // failed to insert row
        $response["success"] = 0;
        $response["message"] = "Oops! An error occurred.";
    } finally {
        // echoing JSON response
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