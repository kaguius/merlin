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
            $page_title = "Edit Sector Details";
            $result = mysql_query("select id, sector, parent_branch, branch_manager, transactiontime from sectors where id = '$id'");
            while ($row = mysql_fetch_array($result)) {
                $id = $row['id'];
                $sectors = $row['sector'];
                $parent_station_id = $row['parent_branch'];
				$branch_manager_id = $row['branch_manager'];

                // Get the name of the parent branch
                $parent_branch_name = "";
                $sql_parent_branch_name = mysql_query("select stations from stations where id = '$parent_station_id'");
                while ($row = mysql_fetch_array($sql_parent_branch_name)) {
                    $parent_branch_name = $row['stations'];
                }
				$sql_parent_branch_name = mysql_query("select concat(first_name, ' ', last_name)branch_manager_name from user_profiles where id = '$branch_manager_id'");
                while ($row = mysql_fetch_array($sql_parent_branch_name)) {
                    $branch_manager_name = $row['branch_manager_name'];
                }

            }
        }
    } else {
        $page_title = "Create New Sectors";
    }
    include_once('includes/header.php');
    ?>
    <div id="page">
        <div id="content">
            <div class="post">
                <h2><?php echo $page_title ?></h2>
                <p class="right"><a class="link" href="sectors.php">Back to Sectors</a></p>
                <div class="clear"></div>
                <form id="frmExecPosition" name="frmExecPosition" method="post" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>">						
                    <input type="hidden" name="page_status" id="page_status" value="<?php echo $action ?>" />
                    <input type="hidden" name="id" id="id" value="<?php echo $id ?>" />
                    <table border="0" width="100%">
                        <tr bgcolor = #F0F0F6>
                            <td>Sector Name *</td>
                            <td>
                                <input title="Enter Sector Name" value="<?php echo $sectors ?>" id="sectors" 
                                       name="sectors" type="text" maxlength="100" class="main_input" size="30" />
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
			<tr bgcolor = #F0F0F6>
                            <td valign='top' >Branch Manager *</td>
                            <td valign='top' >
                                <select name='branch_manager' id='branch_manager'>
                                    <?php
                                    if ($action == 'edit') {
                                        ?>
                                        <option style="padding:5pxo 8px" value="<?php echo $branch_manager_id; ?>"><?php echo $branch_manager_name; ?></option>	
                                        <?php
                                    } else {
                                        ?>
                                        <option style="padding:5px 8px" value=''>--- Select Branch Manager ---</option>
                                        <?php
                                    }

                                    // Get a list of possible parent branches
                                    $sql_parent_stations = mysql_query("select id, concat(first_name, ' ', last_name)branch_manager_name from user_profiles WHERE title = '11' and user_status = '1' order by first_name asc", $dbh1);
                                    while ($row = mysql_fetch_array($sql_parent_stations)) {
                                        $branch_manager_id = $row['id'];
                                        $branch_manager_name = $row['branch_manager_name'];
                                        echo "<option style='padding:5px 8px' value='$branch_manager_id'>" . $branch_manager_name . "</option>";
                                    }
                                    ?>
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
                        frmvalidator.addValidation("sectors", "req", "Please enter the Sector's name");
                    </script>
                </form>
            </div>
        </div>
        <br class="clearfix" />
    </div>
    </div>
    <?php
    if (!empty($_POST)) {
        $sectors = $_POST["sectors"];
        $page_status = $_POST["page_status"];
        $id = $_POST["id"];
        $parent_station_id = $_POST["branch_id"];
		$branch_manager_id = $_POST["branch_manager"];

        if ($page_status == 'edit') {
            echo "DEBUG: Updating Sector ... " . "<br>";
            $sql3 = "update sectors set sector = '$sectors', parent_branch = '$parent_station_id', branch_manager = '$branch_manager_id', transactiontime = '$transactiontime' WHERE id = '$id'";
            $result = mysql_query($sql3);
            //echo $sql3;
        } else {
            $result_tender = mysql_query("select distinct sector from sectors where sector = '$sectors'");
            while ($row = mysql_fetch_array($result_tender)) {
                $sectors_exists = $row['sectors'];
            }

            $sectors_exists = strtolower($sectors_exists);
            $sectors = strtolower($sectors);

            if (($sectors_exists != $sectors)) {
                //echo "DEBUG: Sectors does not exist ...  " . "<br>";

                $sectors = ucwords(strtolower($sectors));
                $transactiontime = date("Y-m-d G:i:s");

                $sql = "INSERT INTO sectors (sector, parent_branch, branch_manager, transactiontime)
				VALUES('$sectors', '$parent_station_id', '$branch_manager_id', '$transactiontime')";

                //echo "DEBUG: sql = $sql" . "<br>";

                $result = mysql_query($sql);

                $query = "sectors.php";
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

                $sectors_exists = MD5(sectors_exists);
                $query = "update_sectors.php?status=sectors_exists&sectors_status=$sectors_exists";
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
                	document.location = "sectors.php";
                    //-->
        </script>
        <?php
    }
}
?>
<?php
include_once('includes/footer.php');
?>
