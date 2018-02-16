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
if (isset($_GET["username"])) {
    $username = $_GET['username'];
 
    // get a product from products table
    $result = mysql_query("SELECT * FROM user_profiles WHERE username = $username");
 
    if (!empty($result)) {
        // check for empty result
        if (mysql_num_rows($result) > 0) {
 
            $result = mysql_fetch_array($result);
            
 
             // temp user array
            $customer = array();
            $customer["id"] = $result["id"];
            $customer["email_address"] = mysql_real_escape_string($result["email_address"]);
            $customer["password_main"] = mysql_real_escape_string($result["password_main"]);
            $customer["user_status"] = mysql_real_escape_string($result["user_status"]);
            $customer["admin_status"] = mysql_real_escape_string($result["admin_status"]);
            $customer["station"] = mysql_real_escape_string($result["station"]);
            $customer["title"] = mysql_real_escape_string($result["title"]);
            $customer['first_name'] = mysql_real_escape_string($result['first_name']);
            $customer['last_name'] = mysql_real_escape_string($result['last_name']);
            $customer['username'] = mysql_real_escape_string($result['username']);
            
             // success
            $response["success"] = 1;

             // user node
            $response["users"] = array();
 
            array_push($response["users"], $customer);
 
            // echoing JSON response
            echo json_encode($response);
        } else {
            // no product found
            $response["success"] = 0;
            $response["message"] = "No user found";
 
            // echo no users JSON
            echo json_encode($response);
        }
    } else {
        // no product found
        $response["success"] = 0;
        $response["message"] = "No user found";
 
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