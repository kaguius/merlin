<?php

include_once('../includes/db_conn.php');

function get_marketing($filter_start_date, $filter_end_date) {
    $response = array();

    $result = mysql_query(" CALL `merlin`.`marketing`('$filter_start_date', '$filter_end_date')");

    if (mysql_num_rows($result) > 0) {

        $response["users"] = array();

        while ($row = mysql_fetch_array($result)) {

            $user = array();

	    $user["year"] = $row["m_year"];
	    $user["month"] = $row["m_month"];
            $user["stations"] = $row["stations"];
            $user["fliers"] = $row["Fliers"];
            $user["newspaper"] = $row["Newspaper"];
            $user["branch_ambassador"] = $row["Branch_Ambassadors"];
            $user["marketing_drive"] = $row["Marketing_Drive"];
            $user["loan_officer"] = $row["Loan_Officer"];
	    $user["access_afya"] = $row["Access_Afya"];
            $user["others"] = $row["others"];


            array_push($response["users"], $user);
        }
    }

    return json_encode($response);
}

?>

