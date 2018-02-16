<?php
 
/*
 * Following code will get single product details
 * A product is identified by product id (pid)
 */
 
// array for JSON response
$response = array();
 
// include db connect class
require_once  'db_connect.php';
 
// connecting to db
$db = new DB_CONNECT();
 
// check for post data
if (isset($_GET["id"])) {
    $id = $_GET['id'];
 
    // get a product from products table
    $result = mysql_query("SELECT * FROM users where id = $id");
 
    if (!empty($result)) {
        // check for empty result
        if (mysql_num_rows($result) > 0) {
 
            $row = mysql_fetch_array($result);
            
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
            
             // success
            $response["success"] = 1;

             // user node
            $response["leads"] = array();
 
            array_push($response["leads"], $lead);
 
            // echoing JSON response
            echo json_encode($response);
        } else {
            // no product found
            $response["success"] = 0;
            $response["message"] = "No lead found";
 
            // echo no users JSON
            echo json_encode($response);
        }
    } else {
        // no product found
        $response["success"] = 0;
        $response["message"] = "No lead found";
 
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