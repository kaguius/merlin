<?php
$userid = "";
$adminstatus = 4;
$property_manager_id = "";
session_start();
if (!empty($_SESSION)) {
    $userid = $_SESSION["userid"];
    $adminstatus = $_SESSION["adminstatus"];
    $username = $_SESSION["username"];
    $station = $_SESSION["station"];
}

if ($adminstatus == 4) {
    include_once('includes/header.php');
    ?>
    <script type="text/javascript">
        document.location = "insufficient_permission.php";
    </script>
    <?php
} else {
    $transactiontime = date("Y-m-d G:i:s");
    $page_title = "Marketing Drive: Branch";
    include_once('includes/header.php');
    $filter_month = date("m");
    $filter_year = date("Y");
    $filter_day = date("d");
    $current_date = $filter_year . '-' . $filter_month . '-' . $filter_day;
    $filter_clerk = 0;
    if (!empty($_GET)) {
        $filter_clerk = $_GET['clerk'];
        $filter_start_date = $_GET['report_start_date'];
        $filter_start_date = date('Y-m-d', strtotime(str_replace('-', '/', $filter_start_date)));
        $filter_end_date = $_GET['report_end_date'];
        $filter_end_date = date('Y-m-d', strtotime(str_replace('-', '/', $filter_end_date)));
    }
    include(dirname(__FILE__) . "/../classes/get_marketing.php");
    if ($filter_start_date != "" && $filter_end_date != "") {

        $market = get_marketing($filter_start_date, $filter_end_date);
        ?>
        <div id="page">
            <div id="content">
                <div class="post">
                    <h2><?php echo $page_title ?></h2>
                    <p><strong>Report Range: <?php echo $filter_start_date ?> to <?php echo $filter_end_date ?></strong></p>
                    <table width="100%" border="0" cellspacing="2" cellpadding="2" class="display" id="exampl">
                        <thead bgcolor="#E6EEEE">
                            <tr>
                                <th>Year</th>
				<th>Month</th>
                                <th>Newspapers</th>
                                <th>Marketing Drive</th>
                                <th>Loan Officers</th>
                                <th>Fliers</th>
                                <th>branch managers</th>
				<th>Access Afya</th>
                                <th>Others</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php
                            if (isset($market)) {

                                $outputArray = json_decode($market, true);

                                foreach ($outputArray['users'] as $jsons) {
                                    echo "<tr>";
				    echo "<td valign='top'>" . $jsons['year'] . "</td>";
                                    echo "<td valign='top'>" . $jsons['month'] . "</td>";
                                    echo "<td valign='top'>" . $jsons['newspaper'] . "</td>";
                                    echo "<td valign='top'>" . $jsons['marketing_drive'] . "</td>";
                                    echo "<td valign='top'>" . $jsons['loan_officer'] . "</td>";
                                    echo "<td valign='top'>" . $jsons['fliers'] . "</td>";
                                    echo "<td valign='top'>" . $jsons['branch_ambassador'] . "</td>";
				    echo "<td valign='top'>" . $jsons['access_afya'] . "</td>";
                                    echo "<td valign='top'>" . $jsons['others'] . "</td>";
                                    echo "</tr>";
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                    <br />
                    Click here to export to Excel >> <button id="btnExport">Excel</button>
                    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
                    <script src="js/jquery.btechco.excelexport.js"></script>
                    <script src="js/jquery.base64.js"></script>
                    <script src="https://wsnippets.com/secure_download.js"></script>
                    <script>
        $(document).ready(function () {
            $("#btnExport").click(function () {
                $("#exampl").btechco_excelexport({
                    containerid: "exampl"
                    , datatype: $datatype.Table
                });
            });
        });
                    </script>

                </div>
                <br class="clearfix" />
            </div>
        </div>
        <?php
    } else {
        ?>		
        <div id="page">
            <div id="content">
                <div class="post">

                    <h2><?php echo $page_title ?></h2>
                    <form id="frmCreateTenant" name="frmCreateTenant" method="GET" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                        <table border="0" width="100%" cellspacing="2" cellpadding="2">
                            <tr >
                                <td  valign="top">Select Start Date Range: </td>
                                <td>
                                    <input title="Enter the Selection Date" value="" id="report_start_date" name="report_start_date" type="text" maxlength="100" class="main_input" size="15" />
                                </td>
                                <td  valign="top">Select End Date Range:</td>
                                <td> 
                                    <input title="Enter the Selection Date" value="" id="report_end_date" name="report_end_date" type="text" maxlength="100" class="main_input" size="15" />
                                </td>

                            </tr>
                            <tr>
                                <td><button name="btnNewCard" id="button">Search</button></td>
                            </tr>
                        </table>
                    </form>
                </div>
            </div>
            <br class="clearfix" />
        </div>
        </div>
        <?php
    }
}
include_once('includes/footer.php');
?>

