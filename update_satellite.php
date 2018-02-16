<?php
$userid = "";
$adminstatus = "";
session_start();
if (!empty($_SESSION)) {
    $userid = $_SESSION["userid"];
    $adminstatus = $_SESSION["adminstatus"];
}
if ($adminstatus == 3) {
    include_once('includes/header.php');
    ?>
    <script type="text/javascript">
        document.location = "insufficient_permission.php";
    </script>
    <?php
} else {
    include_once('includes/db_conn.php');
    if (!empty($_GET)) {
        $status = $_GET['status'];
        $transactiontime = date("Y-m-d G:i:s");
        // This is set in GET query when "Edit" in satellite.php is clicked
        $action = $_GET['action'];

        $id = $_GET['id'];
        if ($action == 'edit') {
            $page_title = "Edit Satellite Details";
            $result = mysql_query("select id, stations, parent_station_id, active, transactiontime from stations where id = '$id'");
            while ($row = mysql_fetch_array($result)) {
                $id = $row['id'];
                $stations = $row['stations'];
                $active = $row['active'];
                $parent_station_id = $row['parent_station_id'];

                // Get the name of the parent branch
                $parent_branch_name = "";
                $sql_parent_branch_name = mysql_query("select stations from stations where id = '$parent_station_id'");
                while ($row = mysql_fetch_array($sql_parent_branch_name)) {
                    $parent_branch_name = $row['stations'];
                }

                // A satellite is active if it has an active entry of '0', inactive if the entry is '1'
                if ($active == '1') {
                    $active_name = 'No';
                } else {
                    $active_name = 'Yes';
                }
            }
        }
    } else {
        $page_title = "Create New Satellite";
    }
    include_once('includes/header.php');
    ?>
    <div id="page">
        <div id="content">
            <div class="post">
                <h2><?php echo $page_title ?></h2>
                <p class="right"><a class="link" href="satellite.php">Back to Satellites</a></p>
                <div class="clear"></div>
                <form id="frmExecPosition" name="frmExecPosition" method="post" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>">						
                    <input type="hidden" name="page_status" id="page_status" value="<?php echo $action ?>" />
                    <input type="hidden" name="id" id="id" value="<?php echo $id ?>" />
                    <table border="0" width="100%">
                        <tr bgcolor = #F0F0F6>
                            <td>Satellite Name *</td>
                            <td>
                                <input title="Enter Satellite Name" value="<?php echo $satellites ?>" id="satellites" 
                                       name="satellites" type="text" maxlength="100" class="main_input" size="30" />
                            </td>
                        </tr>							
                        <tr>
                            <td valign='top' >Parent Branch *</td>
                            <td valign='top' >
                                <select name='branch_id' id='parent_station_id'>
                                    <?php
                                    if ($action == 'edit') {
                                        ?>
                                        <option style="padding:5pxo 8px" value="<?php echo $parent_station_id; ?>"><?php echo $parent_branch_name; ?></option>	
                                        <?php
                                    } else {
                                        ?>
                                        <option style="padding:5px 8px" value=''>--- Select parent branch ---</option>
                                        <?php
                                    }

                                    // Get a list of possible parent branches
                                    $sql_parent_stations = mysql_query("select id, stations from stations WHERE id <> '3' AND id <> '4' AND id <> '10' AND parent_station_id = '0' order by stations asc", $dbh1);
                                    while ($row = mysql_fetch_array($sql_parent_stations)) {
                                        $parent_id = $row['id'];
                                        $candidate_parent_station = $row['stations'];
                                        echo "<option style='padding:5px 8px' value='$parent_id'>" . $candidate_parent_station . "</option>";
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr >
                            <td valign='top' >Active *</td>
                            <td valign='top' >
                                <select name='active' id='active'>
                                    <?php
                                    if ($action == 'edit') {
                                        ?>
                                        <option style="padding:5px 8px" value="<?php echo $active ?>"><?php echo $active_name ?></option>	
                                        <?php
                                    } else {
                                        ?>
                                        <option style="padding:5px 8px" value=''>--- Set status ---</option>
                                        <?php
                                    }
                                    ?>
                                    <option style="padding:5px 8px" value='0'>Yes</option>
                                    <option style="padding:5px 8px" value='1'>No</option>
                                </select>
                            </td>
                        </tr>
                        <?php if ($status == 'satellite_exists') { ?>
                            <tr>
                                <td colspan="2">
                                    <span style="color:red">The satellite specified already exists, please enter a different one.</span></a>	
                                </td>
                            </tr>
                        <?php } ?>		
                    </table>			
                    <table border="0" width="100%">
                        <tr>
                            <td valign="top">
                                <button name="submit" id="button" type="submit">Save</button>
                            </td>
                            <td align="right">
                                <button name="reset" id="button2" type="reset">Reset</button>
                            </td>		
                        </tr>
                    </table>
                    <script type="text/javascript">
                        var frmvalidator = new Validator("frmExecPosition");
                        frmvalidator.addValidation("satellites", "req", "Please enter the Satellite's name");
                        frmvalidator.addValidation("active", "req", "Please choose the status of the Satellite");
                    </script>
                </form>
            </div>
        </div>
        <br class="clearfix" />
    </div>
    </div>
    <?php
    if (!empty($_POST)) {
        $satellites = $_POST["satellites"];
        $active = $_POST["active"];
        $page_status = $_POST["page_status"];
        $id = $_POST["id"];
        $parent_station_id = $_POST["branch_id"];

        echo "DEBUG: satellite name = $satellites" . "<br>";
        echo "DEBUG: satellite status = $active" . "<br>";
        //echo "DEBUG 3: page_status (active when editing) = $page_status"."<br>";
        //echo "DEBUG 4: id (active when editing) = $id"."<br>";
        echo "DEBUG: parent_station_id = $parent_station_id" . "<br>";

        if ($page_status == 'edit') {
            echo "DEBUG: Updating satellite ... " . "<br>";
            $sql3 = "update stations set parent_station_id='$parent_station_id', active= '$active', transactiontime = '$transactiontime' WHERE id = '$id'";
            $result = mysql_query($sql3);
            //echo $sql3;
        } else {
            $result_tender = mysql_query("select distinct stations from stations where stations = '$satellites'");
            while ($row = mysql_fetch_array($result_tender)) {
                $satellite_exists = $row['stations'];
            }

            $satellite_exists = strtolower($satellite_exists);
            $satellites = strtolower($satellites);

            if (($satellite_exists != $satellites)) {
                echo "DEBUG: Satellite does not exist ...  " . "<br>";

                $satellites = ucwords(strtolower($satellites));

                // Get details of parent branch to use when creating the satellite entry
                $parent_daily_target = "";
                $parent_weekly_target = "";
                $parent_monthly_target = "";
                $parent_paybill = "";

                $sql_parent_station_details = mysql_query("SELECT daily_target, weekly_target, monthly_target, paybill FROM stations WHERE id = '$parent_station_id'");
                while ($row = mysql_fetch_array($sql_parent_station_details)) {
                    $parent_daily_target = $row['daily_target'];
                    $parent_weekly_target = $row['weekly_target'];
                    $parent_monthly_target = $row['monthly_target'];
                    $parent_paybill = $row['paybill'];
                }

                //echo "DEBUG: parent_daily_target = $parent_daily_target"."<br>";
                //echo "DEBUG: parent_weekly_target = $parent_weekly_target"."<br>";
                //echo "DEBUG: parent_monthly_target = $parent_monthly_target"."<br>";
                //echo "DEBUG: parent_paybill = $parent_paybill"."<br>";
                // Save the new satellite and populate values of daily_target, weekly_target, monthly_target, paybill used by the parent
                $transactiontime = date("Y-m-d G:i:s");

                $sql = "INSERT INTO stations (stations, daily_target, weekly_target, monthly_target, paybill, active, transactiontime, parent_station_id)
						VALUES('$satellites', '$parent_daily_target', '$parent_weekly_target', '$parent_monthly_target', '$parent_paybill', '$active', '$transactiontime', '$parent_station_id')";

                echo "DEBUG: sql = $sql" . "<br>";

                $result = mysql_query($sql);

                $query = "satellite.php";
                ?>
                <script type="text/javascript">
                    <!--
                        /*alert("Either the Email Address or the Password do not match the records in the database or you have been disabled from the system, please contact the system admin at www.e-kodi.com/contact.php");*/
                    document.location = "<?php echo $query ?>";
                    //-->
                </script>
                <?php
            } else {
                echo "DEBUG: Satellite exists ...  ";

                $satellite_exists = MD5(satellite_exists);
                $query = "update_satellite.php?status=satellite_exists&satellites_status=$satellite_exists";
                //echo $query;
                ?>
                <script type="text/javascript">
                    <!--
                        /*alert("Either the Email Address or the Password do not match the records in the database or you have been disabled from the system, please contact the system admin at www.e-kodi.com/contact.php");*/
                    document.location = "<?php echo $query ?>";
                    //-->
                </script>
                <?php
            }
        }
        ?>
        <script type="text/javascript">
                    <!--
                //document.location = "satellite.php";
                    //-->
        </script>
        <?php
    }
}
?>
<?php
include_once('includes/footer.php');
?>
