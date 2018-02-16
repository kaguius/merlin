<?php
	$userid = "";
	$adminstatus = 4;
	$property_manager_id = "";
	session_start();
	if (!empty($_SESSION)){
		$userid = $_SESSION["userid"] ;
		$adminstatus = $_SESSION["adminstatus"] ;
		$station = $_SESSION["station"] ;
		$username = $_SESSION["username"];
		$title = $_SESSION["title"];
	}
	if (!empty($_GET)) {
		$user_id = $_GET['user_id'];
	}

//if($adminstatus != 1 || $adminstatus != 2 || $adminstatus != 4){
	if ($adminstatus == 4) {
		include_once('includes/header.php');
		?>
		<script type="text/javascript">
			document.location = "insufficient_permission.php";
		</script>
		<?php
	} else {
		include_once('includes/db_conn.php');
		$transactiontime = date("Y-m-d G:i:s");
		$page_title = "Customer Bulk Messaging";
		include_once('includes/header.php');
		$filter_clerk = 0;
		if (!empty($_GET)) {	
			$phone_number = $_GET['phone_number'];
			$first_name = $_GET['first_name'];
			$last_name = $_GET['last_name'];
    }
    ?>
    <script type="text/javascript"><!--

        var formblock;
        var forminputs;

        function prepare() {
            formblock = document.getElementById('frmCreateTenant');
            forminputs = formblock.getElementsByTagName('input');
        }

        function select_all(name, value) {
            for (i = 0; i < forminputs.length; i++) {
    // regex here to check name attribute
                var regex = new RegExp(name, "i");
                if (regex.test(forminputs[i].getAttribute('name'))) {
                    if (value == '1') {
                        forminputs[i].checked = true;
                    } else {
                        forminputs[i].checked = false;
                    }
                }
            }
        }

        if (window.addEventListener) {
            window.addEventListener("load", prepare, false);
        } else if (window.attachEvent) {
            window.attachEvent("onload", prepare)
        } else if (document.getElementById) {
            window.onload = prepare;
        }


		function textCounter( field, countfield, maxlimit ) {
			if ( field.value.length > maxlimit ) {
				field.value = field.value.substring( 0, maxlimit );
				field.blur();
				field.focus();
		  		return false;
			} else {
		  		countfield.value = maxlimit - field.value.length;
			}
		}
		
    //--></script>
    <div id="page">
        <div id="content">
            <div class="post">

                <h2><?php echo $page_title ?></h2>
                <h3>Bulk SMS Units/ Satelittes</h3>
                <form id="frmCreateTenant" name="frmCreateTenant" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <table width="100%" border="0" cellspacing="2" cellpadding="2" class="display">
                        <tr>
                            <td width="50%" valign="top">
                                <h3>Select Recipients</h3>
                                <a href="#" onClick="select_all('message', '1');">Check All</a> | <a href="#" onClick="select_all('message', '0');">Uncheck All</a><br>
                                <table width="100%" border="0" cellspacing="2" cellpadding="2" class="display" id="eample">
                                    <thead bgcolor="#E6EEEE">
                                        <tr>
                                            <th>Unit/ Satelitte</th>
                                            <th>Select</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sql = mysql_query("select id, stations from stations where active = '0' and id not in ('3', '10') order by id asc");
                                        while ($row = mysql_fetch_array($sql)) {
                                            $intcount++;
                                            $station_id = $row['id'];
                                            $unit_name = $row['stations'];

                                            if ($intcount % 2 == 0) {
                                                $display = '<tr bgcolor = #F0F0F6>';
                                            } else {
                                                $display = '<tr>';
                                            }
                                            echo $display;
                                            echo "<td valign='top'>$unit_name</td>";
                                            echo "<td valign='top' align='right'><input type='checkbox' class='checkbox1' id='message[$station_id]' name='message[$station_id]' value='$station_id'></td>";
                                            echo "</tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                                <br />
                                <!--<button type="button" class="clickit">Check All</button>-->
                            </td>
                            <td width="50%" valign="top">
                                <h3>Enter Message</h3>
								<br />
                                <table width="100%" border="0" cellspacing="2" cellpadding="2" class="display" id="exampl3">
                                    <tr>
                                        <td>
                                        	<input onblur="textCounter(this.form.recipients,this,140);" disabled  onfocus="this.blur();" tabindex="999" maxlength="3" size="1" value="140" name="character_count">characters remaining.
                                        	<textarea onblur="textCounter(this,this.form.character_count,140);" onkeyup="textCounter(this,this.form.character_count,140);" name="message_detail" id="message_detail" cols="75" rows="10" ></textarea>
                                        	<!--<textarea title="Enter Message Detail" name="message_detail" id="message_detail" cols="75" rows="10" class="textfield"><?php echo $message_detail ?></textarea>-->
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="top">
                                            <button name="btnNewCard" id="button">Submit</button>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
				<?php
				echo "<input type='hidden' id='expense' name='expense' value='$station_id'>";
				?>
                    </table>
                </form>
            </div>
        </div>
        <br class="clearfix" />
    </div>
    </div>
    <?php
    if (!empty($_POST)) {
        $message = $_POST['message'];
        $message_detail = $_POST['message_detail'];
        $message_detail = mysql_real_escape_string($message_detail);

        $expense = $_POST['expense'];

        for ($counter = 1; $counter <= $expense; $counter++) {
            if ($message[$counter] != 0) {
                $station = $message[$counter];

                $sql = mysql_query("select distinct customer_id, loan_mobile from loan_application where customer_station = '$station'");
                while ($row = mysql_fetch_array($sql)) {
                    $loan_mobile = $row['loan_mobile'];
                    $customer_id = $row['customer_id'];
                    
                    if($loan_mobile == '254712653826' || $loan_mobile == '254706527601'){
                    	$sql2 = "INSERT INTO out_msg_logs (customer_id, mobile_no, msg_text, status, new, transactiontime)
						VALUES('$customer_id','$loan_mobile', '$message_detail', '1', '1', '$transactiontime')";
						//echo $sql2."<br />";
						$result = mysql_query($sql2);
                    }
                    else{
						$sql2 = "INSERT INTO out_msg_logs (customer_id, mobile_no, msg_text, status, new, transactiontime)
						VALUES('$customer_id','$loan_mobile', '$message_detail', '1', '2', '$transactiontime')";
						//echo $sql2."<br />";
						$result = mysql_query($sql2);
                    }
                }
            }
        }

        $query = "message_confirmation.php";
        ?>
        <script type="text/javascript">
        <!--
            document.location = "<?php echo $query ?>";
        //-->
        </script>
        <?php
    }
}
include_once('includes/footer.php');
?>
