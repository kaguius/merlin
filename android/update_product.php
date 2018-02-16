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


        $pid = $data->pid;
        $name = $data->name;
        $price = $data->price;
        $description = $data->description;

        if (!isset($pid) || !isset($name) || !isset($price) || !isset($description)) {
            $response["success"] = 0;
            $response["message"] = "Required field(s) is missing";
        } else {


            // include db connect class
            require_once 'db_connect.php';

            // connecting to db
            $db = new DB_CONNECT();

            // mysql update row with matched pid
            $result = mysql_query("UPDATE products SET name = '$name', price = '$price', description = '$description' WHERE pid = $pid");

            // check if row inserted or not
            if ($result) {
                // successfully updated
                $response["success"] = 1;
                $response["message"] = "Product successfully updated.";
            } else {
                
            }
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