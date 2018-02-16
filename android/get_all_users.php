<?php

// include db connect class
require_once 'db_connect.php';

function getUsers() {

    // array for JSON response
    $response = array();

    $db = new DB_CONNECT();
    // array for json response
    // $response = array();
    //$response["users"] = array();
    // Mysql select query
    $result = mysql_query("SELECT * FROM user_profiles where title in(1,2) and user_status !='0'");

// check for empty result
    if (mysql_num_rows($result) > 0) {

        // looping through all results
        // users node
        $response["users"] = array();

        while ($row = mysql_fetch_array($result)) {

            $user = array();
            $user["id"] = $row["id"];
            $user["email_address"] = $row["email_address"];
            $user["password_main"] = $row["password_main"];
            $user["user_status"] = $row["user_status"];
            $user["admin_status"] = $row["admin_status"];
            $user["station"] = $row["station"];
            $user["title"] = $row["title"];
            $user['first_name'] = $row['first_name'];
            $user['last_name'] = $row['last_name'];
            $user['username'] = $row['username'];


            array_push($response["users"], $user);
        }
        // success
        $response["success"] = 1;

        // echoing JSON response
        echo json_encode($response);
    } else {
// no products found
        $response["success"] = 0;
        $response["message"] = "No user found";

// echo no users JSON
        echo json_encode($response);
    }
}

getUsers();
?>