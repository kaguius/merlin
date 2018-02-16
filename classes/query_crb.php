<?php

include_once('../includes/db_conn.php');
include_once('RecordCrb.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function get_crb_listing($customer_id) {

    $name1 = "";
    $name2 = "";
    $nationalid = "";
    
    $sql = "select IF(LOCATE(' ', first_name),SUBSTR(first_name, 1, LOCATE(' ', first_name)),
        first_name) first_name,IF(LOCATE(' ', last_name),SUBSTR(last_name, 1, LOCATE(' ', last_name)),
        last_name)last_name, national_id FROM users where id = '$customer_id';";
    $result = mysql_query($sql) or die(mysql_error());

    if (mysql_num_rows($result) > 0) {
        while ($row = mysql_fetch_array($result)) {

            $name1 = preg_replace('/\s+/', '', $row["first_name"]);
            $name2 = preg_replace('/\s+/', '', $row["last_name"]);
            $nationalid = preg_replace('/\s+/', '', $row["national_id"]);
        }
    }
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "http://197.232.2.169:28080/ApiApp/crb?name1=$name1&name2=$name2&nationalid=$nationalid");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);

    $output = curl_exec($ch);

    curl_close($ch);

    return $output;
}

$data = get_crb_listing('6178');
//
echo $data;

