<?php
$userid = "";
$adminstatus = 4;
$property_manager_id = "";
$passportfileupload = "";
$resumefileupload = "";
session_start();
if (!empty($_SESSION)) {
    $userid = $_SESSION["userid"];
    $adminstatus = $_SESSION["adminstatus"];
    $station = $_SESSION["station"];
    $username = $_SESSION["username"];
    $title = $_SESSION["title"];
    $passportfileupload = $_SESSION["passportfileupload"];
    $resumefileupload = $_SESSION["resumefileupload"];
}

//if($adminstatus != 1 || $adminstatus != 2 || $adminstatus != 4){
if ($adminstatus == 4) {
    include_once('includes/header.php');
    ?>
    <script type="text/javascript">
        document.location = "login.php";
    </script>
    <?php
} else {
    if (!empty($_GET)) {
        $mode = $_GET['mode'];
        $user_id = $_GET['user_id'];
        $status = $_GET['status'];
    }

    $filter_month = date("m");
    $filter_year = date("Y");
    $filter_day = date("d");
    $current_date = $filter_year . '-' . $filter_month . '-' . $filter_day;

    include_once('includes/db_conn.php');
    include_once('classes/RecordCrb.php');
    //include_once('classes/query_crb.php');
    
    $recordCrb = new RecordCrb();

    $transactiontime = date("Y-m-d G:i:s");
    if ($mode == 'edit') {
        $sql = mysql_query("select affordability, owns from users where id = '$user_id'");
        while ($row = mysql_fetch_array($sql)) {
            $affordability = $row['affordability'];
            $owns = $row['owns'];
        }

        $sql = mysql_query("select completed,no_of_employees,datediff(now(),transactiontime) as no_of_days,id,user_id, business_cycle, business_category, business_type, trading_product, trading_location, business_address, stock_value, weekly_sales, spend_stock, income_explanation, business_rent, business_utilities, employees, licensing, storage, transport, house_rent, house_utilities, food_expense, school_fees, weekly_cont, chama_members, chama_payout, payout_freq, stock_neat, ledger_book, sales_activity, permanent_operation, proof_ownership, forthcoming, market_authorities, sound_reputation, lend, lend_amount, restocking_ratio, stock_health_multiplier, UID, weekly_restock, transactiontime, bank_account, bank_account_holder, credit, loan_account, loan_number, daily_customers from business_details where user_id = '$user_id' order by transactiontime desc limit 1");
        while ($row = mysql_fetch_array($sql)) {
            $no_of_days = $row['no_of_days'];
            $completed = $row['completed'];
            $business_id = $row['id'];
            $business_category = $row['business_category'];
            $business_type = $row['business_type'];
            $trading_product = $row['trading_product'];
            $trading_location = $row['trading_location'];
            $business_address = $row['business_address'];
            $stock_value = $row['stock_value'];
            $weekly_sales = $row['weekly_sales'];
            $spend_stock = $row['spend_stock'];
            $income_explanation = $row['income_explanation'];

            $bank_account = $row['bank_account'];
            $bank_account_holder = $row['bank_account_holder'];
            $credit = $row['credit'];
            $loan_account = $row['loan_account'];
            $loan_number = $row['loan_number'];
            $daily_customers = $row['daily_customers'];
            
            $weekly_restock = $row['weekly_restock'];
            $business_cycle = $row['business_cycle'];

            $business_rent = $row['business_rent'];
            $business_utilities = $row['business_utilities'];
            $employees = $row['employees'];
            $no_of_employees = $row['no_of_employees'];
            $licensing = $row['licensing'];
            $storage = $row['storage'];
            $transport = $row['transport'];
            $house_rent = $row['house_rent'];
            $house_utilities = $row['house_utilities'];
            $food_expense = $row['food_expense'];

            $school_fees = $row['school_fees'];
            $weekly_cont = $row['weekly_cont'];
            $chama_members = $row['chama_members'];
            $chama_payout = $row['chama_payout'];
            $payout_freq = $row['payout_freq'];
            $stock_neat = $row['stock_neat'];
            $ledger_book = $row['ledger_book'];
            $permanent_operation = $row['permanent_operation'];
            $proof_ownership = $row['proof_ownership'];
            $sales_activity = $row['sales_activity'];
            $forthcoming = $row['forthcoming'];
            $market_authorities = $row['market_authorities'];
            $sound_reputation = $row['sound_reputation'];
            $lend = $row['lend'];
            $lend_amount = $row['lend_amount'];
            $restocking_ratio = $row['restocking_ratio'];
            $stock_health_multiplier = $row['stock_health_multiplier'];
            $transaction_time = $row['transactiontime'];
        }
        $sql2 = mysql_query("select id, business from business where id = '$business_category' order by business asc");
        while ($row = mysql_fetch_array($sql2)) {
            $business_category_id = $row['id'];
            $business_category = $row['business'];
            $business_category = ucwords(strtolower($business_category));
        }
        $page_title = "Update Business Expense Detail(s)";
        $gross_profit = $weekly_sales - $spend_stock;
        $business_expenses = $business_rent + $employees + $business_utilities + $licensing + $storage + $transport;
        $net_profit = $gross_profit - $business_expenses;
        $cost_of_living = $house_rent + $house_utilities + $food_expense + $school_fees + $weekly_cont;
        $other_income = $weekly_cont - $chama_payout;
        $cost_of_sales = ($spend_stock / $weekly_sales) * 100;
        $min_aallowed_personal_expenses = (20 / 100) * $gross_profit;

        $sql2 = mysql_query("select count(loan_id)loan_count from loan_application where customer_id = '$user_id'");
        while ($row = mysql_fetch_array($sql2)) {
            $loan_count = $row['loan_count'];
        }

//        $sql1 = mysql_query("select deleted,customerid from crb WHERE customerid = '$user_id' ORDER BY request_time DESC LIMIT 1");
//        while ($row = mysql_fetch_array($sql1)) {
//            $ls = $row['deleted'];
//
//            if ($ls == '0') {
//                $crb_l = 'Listed';
//            } else {
//                $crb_l = 'Not Listed';
//            }
//        }

        $sql = mysql_query("select id, first_name, last_name, mobile_no, dis_phone, date_of_birth, gender, affordability from users where id = '$user_id' order by id asc");
        while ($row = mysql_fetch_array($sql)) {
            $user_id = $row['id'];
            $first_name = $row['first_name'];
            $last_name = $row['last_name'];
            $first_name = ucwords(strtolower($first_name));
            $last_name = ucwords(strtolower($last_name));
            $name = $first_name . ' ' . $last_name;
            $mobile_no = $row['mobile_no'];
            $dis_phone = $row['dis_phone'];
            $date_of_birth = $row['date_of_birth'];
            $gender = $row['gender'];
            if ($gender == '1') {
                $gender = "Male";
            } else {
                $gender = "Female";
            }
        }
    } else {
        $page_title = "Create new Customer Detail(s)";
    }
    $diff = abs(strtotime($current_date) - strtotime($date_of_birth));
    $years = floor($diff / (365 * 60 * 60 * 24));
    
    

    include_once('includes/header.php');
    ?>	
    <script type="text/javascript"><!--

        var formblock;
        var forminputs;

        function prepare() {
            formblock = document.getElementById('frmOrder');
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
                <h2><font color="#000A8B"><?php echo $page_title; ?></font></h2>
                <h2><strong>Customer Name:</strong> <?php echo $name ?>, <strong>Phone Number:</strong> <?php echo $mobile_no ?>,
                    <strong>CRB Listing:</strong> <?php echo $recordCrb->queryCrb($user_id) ?> </h2>
                <font size='5'><strong>Customer Affordability:</strong> KES <?php echo number_format($affordability, 2) ?></font><br />
                <?php if ($status == 'length_business_address') { ?>
                    <table width="60%">
                        <tr bgcolor="red">
                            <td><font color="white" size="2">&nbsp;&nbsp;Yikes! Something's gone wrong.</td>
                        </tr>
                    </table>
                    <font color="red">
                    * Either the business address or income explanation entered is not detailed enough<br />
                    </font>
                <?php } ?>	
                <form id="frmOrder" name="frmOrder" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <input type="hidden" name="users_id" id="users_id" value="<?php echo $user_id ?>" />		
                    <input type="hidden" name="page_status" id="page_status" value="<?php echo $mode ?>" />
                    <input type="hidden" name="no_of_days" id="no_of_days" value="<?php echo $no_of_days ?>" />
                    <input type="hidden" name="completed" id="completed" value="<?php echo $completed ?>" />
                    <input type="hidden" name="transaction_time" id="transaction_time" value="<?php echo $transaction_time ?>" />
                    <div id="tabs">
                        <ul>
                            <li><a href="#tabs-1">Business</a></li>
                            <li><a href="#tabs-2">Income</a></li>
                            <li><a href="#tabs-3">Expense</a></li>
                            <li><a href="#tabs-4">Personal</a></li>
                            <li><a href="#tabs-5">Chama</a></li>
                            <li><a href="#tabs-6">Assessment</a></li>
                            <li><a href="#tabs-7">Result</a></li>
                            <li><a href="#tabs-8">Social</a></li>
                        </ul>
                        <div id="tabs-1">
                            <table border="0" width="100%" cellspacing="2" cellpadding="2">	
                                <tr>
                                    <td valign="top" width="15%">Business Cycle *</td>
                                    <td valign='top' width="35%">
                                        <select name='business_cycle' id='business_cycle'>
                                            <?php
                                            if ($mode == 'edit') {
                                                ?>
                                                <option value="<?php echo $business_cycle ?>"><?php echo $business_cycle ?></option>
                                                <?php
                                            } else {
                                                ?>
                                                <option value=''> </option>
                                                <?php
                                            }
                                            echo "<option value=''></option>";
                                            echo "<option value='Daily'>Daily</option>";
                                            echo "<option value='Weekly'>Weekly</option>";
                                            echo "<option value='Monthly'>Monthly</option>";
                                            ?>
                                        </select>
                                        <input value="<?php echo $business_cycle ?>" id="old_business_cycles" name="old_business_cycles" type="hidden" />
                                    </td>
                                </tr>
                                <tr>
                                    <td valign="top" width="15%">Business Category *</td>
                                    <td valign='top' width="35%">
                                        <select name='business_category' id='business_category'>
                                            <?php
                                            if ($mode == 'edit') {
                                                ?>
                                                <option value="<?php echo $business_category_id ?>"><?php echo $business_category ?></option>
                                                <?php
                                            } else {
                                                ?>
                                                <option value=''> </option>
                                                <?php
                                            }
                                            //echo "<option value=''>" "</option>"; 										
                                            $sql2 = mysql_query("select id, business from business order by business asc");
                                            while ($row = mysql_fetch_array($sql2)) {
                                                $id = $row['id'];
                                                $business = $row['business'];
                                                $business = ucwords(strtolower($business));
                                                echo "<option value='$id'>" . $business . "</option>";
                                            }
                                            ?>
                                        </select>
                                        <input value="<?php echo $business ?>" id="old_business" name="old_business" type="hidden" />
                                    </td>
                                </tr>
                                <tr>
                                    <td valign='top' width="15%">Business Type </td>
                                    <td valign='top' width="35%">
                                        <select name='business_type' id='business_type'>
                                            <?php
                                            if ($mode == 'edit') {
                                                ?>
                                                <option value="<?php echo $business_type ?>"><?php echo $business_type ?></option>
                                                <?php
                                            } else {
                                                ?>
                                                <option value=''> </option>
                                                <?php
                                            }
                                            echo "<option value=''></option>";
                                            echo "<option value='Retailer'>Retailer</option>";
                                            echo "<option value='Wholesaler'>Wholesaler</option>";
                                            ?>
                                        </select>
                                        <input value="<?php echo $business_type ?>" id="old_business_type" name="old_business_type" type="hidden" />
                                    </td>
                                </tr>
                                <tr>
                                    <td valign='top' width="15%">Date started trading this product? </td>
                                    <td valign='top' width="35%" colspan="3">
                                        <input title="Enter the date customer started selling this product" value="<?php echo $trading_product ?>" id="trading_product" name="trading_product" type="text" maxlength="100" class="main_input" size="35" />
                                        <input value="<?php echo $trading_product ?>" id="old_trading_product" name="old_trading_product" type="hidden" />
                                    </td>
                                </tr>
                                <tr>
                                    <td valign='top' width="15%">Date started trading this location? </td>
                                    <td valign='top' width="35%" colspan="3">
                                        <input title="Enter the date customer started trading in this location" value="<?php echo $trading_location ?>" id="trading_location" name="trading_location" type="text" maxlength="100" class="main_input" size="35" />
                                        <input value="<?php echo $trading_location ?>" id="old_trading_location" name="old_trading_location" type="hidden" />
                                    </td>
                                </tr>
                                <tr >
                                    <td valign='top' >Business Address *</td>
                                    <td valign='top' colspan="3">
                                    	<input onblur="textCounter(this.form.recipients,this,150);" disabled  onfocus="this.blur();" tabindex="999" maxlength="3" size="1" value="150" name="character_count">characters remaining.
                                        <textarea onblur="textCounter(this,this.form.character_count,150);" onkeyup="textCounter(this,this.form.character_count,150);" name="business_address" id="business_address" cols="75" rows="10" ></textarea>
                                        <!--<textarea title="Enter Business Addres" name="business_address" id="business_address" cols="75" rows="5" class="textfield"><?php echo $business_address ?></textarea>-->
                                        <input value="<?php echo $business_address ?>" id="old_business_address" name="old_business_address" type="hidden" />
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div id="tabs-2">
                            <table border="0" width="100%" cellspacing="2" cellpadding="2">	
                                <tr>
                                    <td valign='top' width="15%">Current Stock Value </td>
                                    <td valign='top' width="35%">
                                        <input title="Enter customer current stock value" value="<?php echo $stock_value ?>" id="stock_value" name="stock_value" type="text" maxlength="100" class="main_input" size="35" />
                                        <input value="<?php echo $stock_value ?>" id="old_stock_value" name="old_stock_value" type="hidden" />
                                    </td>
                                </tr>
                                <tr>
                                    <td valign='top' width="15%">Sales </td>
                                    <td valign='top' width="35%">
                                        <input title="Enter customer Weekly Sales" value="<?php echo $weekly_sales ?>" id="weekly_sales" name="weekly_sales" type="text" maxlength="100" class="main_input" size="35" />
                                        <input value="<?php echo $weekly_sales ?>" id="old_weekly_sales" name="old_weekly_sales" type="hidden" />
                                    </td>
                                </tr>
                                <tr>
                                    <td valign='top' width="15%">Spend on Stock </td>
                                    <td valign='top' width="35%">
                                        <input title="Enter customer Weekly Spend of Stock" value="<?php echo $spend_stock ?>" id="spend_stock" name="spend_stock" type="text" maxlength="100" class="main_input" size="35" />
                                        <input value="<?php echo $spend_stock ?>" id="old_spend_stock" name="old_spend_stock" type="hidden" />
                                    </td>
                                </tr>
                                <tr >
                                    <td valign='top' >Explain the above figures *</td>
                                    <td valign='top' colspan="3">
                                        <textarea title="Enter Income Explanation" name="income_explanation" id="income_explanation" cols="75" rows="5" class="textfield"><?php echo $income_explanation ?></textarea>
                                        <input value="<?php echo $income_explanation ?>" id="old_income_explanation" name="old_income_explanation" type="hidden" />
                                    </td>
                                </tr>
                                <tr>
                                    <td valign='top' width="15%">How many times do you restock in a week </td>
                                    <td valign='top' width="35%">
                                        <input title="Enter Restock in a week" value="<?php echo $weekly_restock ?>" id="weekly_restock" name="weekly_restock" type="text" maxlength="100" class="main_input" size="35" />
                                        <input value="<?php echo $weekly_restock ?>" id="old_weekly_restock" name="old_weekly_restock" type="hidden" />
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div id="tabs-3">
                            <table border="0" width="100%" cellspacing="2" cellpadding="2">	
                                <tr>
                                    <td valign='top' width="15%">Business Rent </td>
                                    <td valign='top' width="35%">
                                        <input title="Enter customer Business Rent" value="<?php echo $business_rent ?>" id="business_rent" name="business_rent" type="text" maxlength="100" class="main_input" size="35" />
                                        <input value="<?php echo $business_rent ?>" id="old_business_rent" name="old_business_rent" type="hidden" />
                                    </td>
                                </tr>
                                <tr>
                                    <td valign='top' width="15%">Business Utilities [Electricity + Water Bills]</td>
                                    <td valign='top' width="35%">
                                        <input title="Enter customer Business Utilities" value="<?php echo $business_utilities ?>" id="business_utilities" name="business_utilities" type="text" maxlength="100" class="main_input" size="35" />
                                        <input value="<?php echo $business_utilities ?>" id="old_business_utilities" name="old_business_utilities" type="hidden" />
                                    </td>
                                </tr>
                                <tr>
                                    <td valign='top' width="15%">Amount to Current Employees </td>
                                    <td valign='top' width="35%">
                                        <input title="Enter Employees" value="<?php echo $employees ?>" id="employees" name="employees" type="text" maxlength="100" class="main_input" size="35" />
                                        <input value="<?php echo $employees ?>" id="old_employees" name="old_employees" type="hidden" />
                                    </td>
                                </tr>
				
                                <tr>
                                    <td valign='top' width="15%">No of Employees </td>
                                    <td valign='top' width="35%">
                                        <select name='no_of_employees' id='no_of_employees'>
                                            <option value="<?php echo $no_of_employees ?>"><?php echo $no_of_employees ?></option>
                                            <option value=''> </option>	
                                            <option value="0">0</option>
                                            <option value="1-2">1-2</option>
                                            <option value="2-4">2-4</option>
                                            <option value="5-10">5-10</option>
                                            <option value="10+">10+</option>
                                        </select>
                                        <input value="<?php echo $no_of_employees ?>" id="old_no_of_employees" name="old_no_of_employees" type="hidden" />
                                    </td>
                                </tr>
				
                                <tr>
                                    <td valign='top' width="15%">Licensing </td>
                                    <td valign='top' width="35%">
                                        <input title="Enter Licensing" value="<?php echo $licensing ?>" id="licensing" name="licensing" type="text" maxlength="100" class="main_input" size="35" />
                                        <input value="<?php echo $licensing ?>" id="old_licensing" name="old_licensing" type="hidden" />
                                    </td>
                                </tr>
                                <tr>
                                    <td valign='top' width="15%">Storage </td>
                                    <td valign='top' width="35%">
                                        <input title="Enter Storage" value="<?php echo $storage ?>" id="storage" name="storage" type="text" maxlength="100" class="main_input" size="35" />
                                        <input value="<?php echo $storage ?>" id="old_storage" name="old_storage" type="hidden" />
                                    </td>
                                </tr>
                                <tr>
                                    <td valign='top' width="15%">Transport </td>
                                    <td valign='top' width="35%">
                                        <input title="Enter Transport" value="<?php echo $transport ?>" id="transport" name="transport" type="text" maxlength="100" class="main_input" size="35" />
                                        <input value="<?php echo $transport ?>" id="old_transport" name="old_transport" type="hidden" />
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div id="tabs-4">
                            <table border="0" width="100%" cellspacing="2" cellpadding="2">	
                                <tr>
                                    <td valign='top' width="15%">Rent </td>
                                    <td valign='top' width="35%">
                                        <input title="Enter customer Rent" value="<?php echo $house_rent ?>" id="house_rent" name="house_rent" type="text" maxlength="100" class="main_input" size="35" />
                                        <input value="<?php echo $house_rent ?>" id="old_house_rent" name="old_house_rent" type="hidden" />
                                    </td>
                                </tr>
                                <tr>
                                    <td valign='top' width="15%">House Utilities [Electricity + Water Bills]</td>
                                    <td valign='top' width="35%">
                                        <input title="Enter customer House Utilities" value="<?php echo $house_utilities ?>" id="house_utilities" name="house_utilities" type="text" maxlength="100" class="main_input" size="35" />
                                        <input value="<?php echo $house_utilities ?>" id="old_house_utilities" name="old_house_utilities" type="hidden" />
                                    </td>
                                </tr>
                                <tr>
                                    <td valign='top' width="15%">Food </td>
                                    <td valign='top' width="35%">
                                        <input title="Enter Food Expense" value="<?php echo $food_expense ?>" id="food_expense" name="food_expense" type="text" maxlength="100" class="main_input" size="35" />
                                        <input value="<?php echo $food_expense ?>" id="old_food_expense" name="old_food_expense" type="hidden" />
                                    </td>
                                </tr>
                                <tr>
                                    <td valign='top' width="15%">School Fees </td>
                                    <td valign='top' width="35%">
                                        <input title="Enter School Fees" value="<?php echo $school_fees ?>" id="school_fees" name="school_fees" type="text" maxlength="100" class="main_input" size="35" />
                                        <input value="<?php echo $school_fees ?>" id="old_school_fees" name="old_school_fees" type="hidden" />
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div id="tabs-5">
                            <table border="0" width="100%" cellspacing="2" cellpadding="2">	
                                <tr>
                                    <td valign='top' width="15%">Weekly Chama Contribution </td>
                                    <td valign='top' width="35%">
                                        <input title="Enter Weekly Chama Contribution" value="<?php echo $weekly_cont ?>" id="weekly_cont" name="weekly_cont" type="text" maxlength="100" class="main_input" size="35" />
                                        <input value="<?php echo $weekly_cont ?>" id="old_weekly_cont" name="old_weekly_cont" type="hidden" />
                                    </td>
                                </tr>
                                <tr>
                                    <td valign='top' width="15%">Number of Members in the Chama</td>
                                    <td valign='top' width="35%">
                                        <input title="Enter Number of Members in the Chama" value="<?php echo $chama_members ?>" id="chama_members" name="chama_members" type="text" maxlength="100" class="main_input" size="35" />
                                        <input value="<?php echo $chama_members ?>" id="old_chama_members" name="old_chama_members" type="hidden" />
                                    </td>
                                </tr>
                                <tr>
                                    <td valign='top' width="15%">Chama Payout </td>
                                    <td valign='top' width="35%">
                                        <input title="Enter Chame Payout" value="<?php echo $chama_payout ?>" id="chama_payout" name="chama_payout" type="text" maxlength="100" class="main_input" size="35" />
                                        <input value="<?php echo $chama_payout ?>" id="old_chama_payout" name="old_chama_payout" type="hidden" />
                                    </td>
                                </tr>
                                <tr>
                                    <td valign='top' width="15%">Chama Payout Frequency </td>
                                    <td valign='top' width="35%">
                                        <input title="Enter Chame Payout Frequency" value="<?php echo $payout_freq ?>" id="payout_freq" name="payout_freq" type="text" maxlength="100" class="main_input" size="35" />
                                        <input value="<?php echo $payout_freq ?>" id="old_payout_freq" name="old_payout_freq" type="hidden" />
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div id="tabs-6">
                            <table border="0" width="100%" cellspacing="2" cellpadding="2">	
                                <tr>
                                    <td valign='top' width="15%">Stock Neat </td>
                                    <td valign='top' width="35%">
                                        <select name='stock_neat' id='stock_neat'>
                                            <?php
                                            if ($mode == 'edit') {
                                                ?>
                                                <option value="<?php echo $stock_neat ?>"><?php echo $stock_neat ?></option>
                                                <?php
                                            } else {
                                                ?>
                                                <option value=''> </option>
                                                <?php
                                            }
                                            echo "<option value='Yes'>Yes</option>";
                                            echo "<option value='No'>No</option>";
                                            ?>
                                        </select>
                                        <input value="<?php echo $stock_neat ?>" id="old_stock_neat" name="old_stock_neat" type="hidden" />
                                    </td>
                                </tr>
                                <tr>
                                    <td valign='top' width="15%">Accurate Ledger Book</td>
                                    <td valign='top' width="35%">
                                        <select name='ledger_book' id='ledger_book'>
                                            <?php
                                            if ($mode == 'edit') {
                                                ?>
                                                <option value="<?php echo $ledger_book ?>"><?php echo $ledger_book ?></option>
                                                <?php
                                            } else {
                                                ?>
                                                <option value=''> </option>
                                                <?php
                                            }
                                            echo "<option value='Yes'>Yes</option>";
                                            echo "<option value='No'>No</option>";
                                            ?>
                                        </select>
                                        <input value="<?php echo $ledger_book ?>" id="old_ledger_book" name="old_ledger_book" type="hidden" />
                                    </td>
                                </tr>
                                <tr>
                                    <td valign='top' width="15%">Evidence of Sales Activity</td>
                                    <td valign='top' width="35%">
                                        <select name='sales_activity' id='sales_activity'>
                                            <?php
                                            if ($mode == 'edit') {
                                                ?>
                                                <option value="<?php echo $sales_activity ?>"><?php echo $sales_activity ?></option>
                                                <?php
                                            } else {
                                                ?>
                                                <option value=''> </option>
                                                <?php
                                            }
                                            echo "<option value='Yes'>Yes</option>";
                                            echo "<option value='No'>No</option>";
                                            ?>
                                        </select>
                                        <input value="<?php echo $sales_activity ?>" id="old_sales_activity" name="old_sales_activity" type="hidden" />
                                    </td>
                                </tr>
                                <tr>
                                    <td valign='top' width="15%">Evidence of Permanent Operation</td>
                                    <td valign='top' width="35%">
                                        <select name='permanent_operation' id='permanent_operation'>
                                            <?php
                                            if ($mode == 'edit') {
                                                ?>
                                                <option value="<?php echo $permanent_operation ?>"><?php echo $permanent_operation ?></option>
                                                <?php
                                            } else {
                                                ?>
                                                <option value=''> </option>
                                                <?php
                                            }
                                            echo "<option value='Yes'>Yes</option>";
                                            echo "<option value='No'>No</option>";
                                            ?>
                                        </select>
                                        <input value="<?php echo $permanent_operation ?>" id="old_spermanent_operation" name="old_spermanent_operation" type="hidden" />
                                    </td>
                                </tr>
                                <tr>
                                    <td valign='top' width="15%">Proof of Ownership</td>
                                    <td valign='top' width="35%">
                                        <select name='proof_ownership' id='proof_ownership'>
                                            <?php
                                            if ($mode == 'edit') {
                                                ?>
                                                <option value="<?php echo $proof_ownership ?>"><?php echo $proof_ownership ?></option>
                                                <?php
                                            } else {
                                                ?>
                                                <option value=''> </option>
                                                <?php
                                            }
                                            echo "<option value='Yes'>Yes</option>";
                                            echo "<option value='No'>No</option>";
                                            ?>
                                        </select>
                                        <input value="<?php echo $proof_ownership ?>" id="old_proof_ownership" name="old_proof_ownership" type="hidden" />
                                    </td>
                                </tr>
                                <tr>
                                    <td valign='top' width="15%">Forthcoming & Transparent</td>
                                    <td valign='top' width="35%">
                                        <select name='forthcoming' id='forthcoming'>
                                            <?php
                                            if ($mode == 'edit') {
                                                ?>
                                                <option value="<?php echo $forthcoming ?>"><?php echo $forthcoming ?></option>
                                                <?php
                                            } else {
                                                ?>
                                                <option value=''> </option>
                                                <?php
                                            }
                                            echo "<option value='Yes'>Yes</option>";
                                            echo "<option value='No'>No</option>";
                                            ?>
                                        </select>
                                        <input value="<?php echo $forthcoming ?>" id="old_forthcoming" name="old_forthcoming" type="hidden" />
                                    </td>
                                </tr>
                                <tr>
                                    <td valign='top' width="15%">Known to Market Authorities</td>
                                    <td valign='top' width="35%">
                                        <select name='market_authorities' id='market_authorities'>
                                            <?php
                                            if ($mode == 'edit') {
                                                ?>
                                                <option value="<?php echo $market_authorities ?>"><?php echo $market_authorities ?></option>
                                                <?php
                                            } else {
                                                ?>
                                                <option value=''> </option>
                                                <?php
                                            }
                                            echo "<option value='Yes'>Yes</option>";
                                            echo "<option value='No'>No</option>";
                                            ?>
                                        </select>
                                        <input value="<?php echo $market_authorities ?>" id="old_market_authorities" name="old_market_authorities" type="hidden" />
                                    </td>
                                </tr>
                                <tr>
                                    <td valign='top' width="15%">Sound Reputation</td>
                                    <td valign='top' width="35%">
                                        <select name='sound_reputation' id='sound_reputation'>
                                            <?php
                                            if ($mode == 'edit') {
                                                ?>
                                                <option value="<?php echo $sound_reputation ?>"><?php echo $sound_reputation ?></option>
                                                <?php
                                            } else {
                                                ?>
                                                <option value=''> </option>
                                                <?php
                                            }
                                            echo "<option value='Yes'>Yes</option>";
                                            echo "<option value='No'>No</option>";
                                            ?>
                                        </select>
                                        <input value="<?php echo $sound_reputation ?>" id="old_sound_reputation" name="old_sound_reputation" type="hidden" />
                                    </td>
                                </tr>
                                <tr>
                                    <td valign='top' width="15%">Would I lend?</td>
                                    <td valign='top' width="35%">
                                        <select name='lend' id='lend'>
                                            <?php
                                            if ($mode == 'edit') {
                                                ?>
                                                <option value="<?php echo $lend ?>"><?php echo $lend ?></option>
                                                <?php
                                            } else {
                                                ?>
                                                <option value=''> </option>
                                                <?php
                                            }
                                            echo "<option value='Yes'>Yes</option>";
                                            echo "<option value='No'>No</option>";
                                            ?>
                                        </select>
                                        <input value="<?php echo $lend ?>" id="old_lend" name="old_lend" type="hidden" />
                                    </td>
                                </tr>
                                <tr>
                                    <td valign='top' width="15%">If yes, how much?</td>
                                    <td valign='top' width="35%">
                                        <input title="Enter Lend Amount" value="<?php echo $lend_amount ?>" id="lend_amount" name="lend_amount" type="text" maxlength="100" class="main_input" size="35" />
                                        <input value="<?php echo $lend_amount ?>" id="old_lend_amount" name="old_lend_amount" type="hidden" />
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div id="tabs-7">
                            <table border="0" width="100%" cellspacing="2" cellpadding="2">	
                                <tr>
                                    <td valign='top' width="15%">Gross Profit </td>
                                    <td valign='top' width="35%">
                                        <input title="Gross Profit" value="<?php echo $gross_profit ?>" id="gross_profit" name="gross_profit" type="text" maxlength="100" class="main_input" readonly size="35" />
                                    </td>
                                </tr>
                                <tr>
                                    <td valign='top' width="15%">Net Profit </td>
                                    <td valign='top' width="35%">
                                        <input title="Net Profit" value="<?php echo $net_profit ?>" id="net_profit" name="net_profit" type="text" maxlength="100" class="main_input" readonly size="35" />
                                    </td>
                                </tr>
                                <tr>
                                    <td valign='top' width="15%">Monthly Cost Of Living Expenses </td>
                                    <td valign='top' width="35%">
                                        <input title="Restocking Ratio" value="<?php echo $cost_of_living ?>" id="cost_of_living" name="cost_of_living" type="text" maxlength="100" class="main_input" readonly size="35" />
                                    </td>
                                </tr>
                                <tr>
                                    <td valign='top' width="15%">Other Income and Expenses </td>
                                    <td valign='top' width="35%">
                                        <input title="Restocking Ratio" value="<?php echo $other_income ?>" id="other_income" name="other_income" type="text" maxlength="100" class="main_input" readonly size="35" />
                                    </td>
                                </tr>
                                <tr>
                                    <td valign='top' width="15%">Minimum Allowed personal Expenses </td>
                                    <td valign='top' width="35%">
                                        <input title="Restocking Ratio" value="<?php echo $min_aallowed_personal_expenses ?>" id="min_aallowed_personal_expenses" name="min_aallowed_personal_expenses" type="text" maxlength="100" class="main_input" readonly size="35" />
                                    </td>
                                </tr>
                                <tr>
                                    <td valign='top' width="15%">Restocking Ratio </td>
                                    <td valign='top' width="35%">
                                        <input title="Restocking Ratio" value="<?php echo $restocking_ratio ?>" id="restocking_ratio" name="restocking_ratio" type="text" maxlength="100" class="main_input" readonly size="35" />
                                    </td>
                                </tr>
                                <tr>
                                    <td valign='top' width="15%">Cost of Sales </td>
                                    <td valign='top' width="35%">
                                        <input title="Restocking Ratio" value="<?php echo number_format($cost_of_sales, 2) ?>%" id="cost_of_sales" name="cost_of_sales" type="text" maxlength="100" class="main_input" readonly size="35" />
                                    </td>
                                </tr>

                                <tr>
                                    <td valign='top' width="15%">Stock Health Multiplier</td>
                                    <td valign='top' width="35%">
                                        <input title="Stock Health Multiplier" value="<?php echo number_format($stock_health_multiplier, 2) ?>" id="stock_health_multiplier" name="stock_health_multiplier" type="text" maxlength="100" class="main_input" readonly size="35" />
                                    </td>
                                </tr>
                                <?php if ($loan_count == 0) { ?>
                                    <tr >
                                        <td valign='top' >Comment *</td>
                                        <td valign='top' colspan="3">
                                            <textarea title="Enter comments" name="comments" id="comments" cols="75" rows="5" class="textfield">Since this is the first loan for <?php echo $name ?>, the max loan amount can only be KES 20,000.</textarea>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </table>
                        </div>
                        <div id="tabs-8">
                            <table border="0" width="100%" cellspacing="2" cellpadding="2">	
                                <tr>
                                    <td valign='top' width="15%">Gender </td>
                                    <td valign='top' width="35%">
                                        <input title="gender" value="<?php echo $gender ?>" id="gender" name="gender" type="text" maxlength="100" class="main_input" readonly size="35" />
                                    </td>
                                </tr>
                                <tr>
                                    <td valign='top' width="15%">Age </td>
                                    <td valign='top' width="35%">
                                        <input title="years" value="<?php echo $years ?>" id="years" name="years" type="text" maxlength="100" class="main_input" readonly size="35" />
                                    </td>
                                </tr>
                                <tr>
                                    <td valign='top' width="15%">Do you have a bank account</td>
                                    <td valign='top' width="35%">
                                        <select name='bank_account' id='bank_account'>
                                            <?php
                                            if ($mode == 'edit') {
                                                ?>
                                                <option value="<?php echo $bank_account ?>"><?php echo $bank_account ?></option>
                                                <?php
                                            } else {
                                                ?>
                                                <option value=''> </option>
                                                <?php
                                            }
                                            echo "<option value='Yes'>Yes</option>";
                                            echo "<option value='No'>No</option>";
                                            ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td valign='top' width="15%">If yes with what kind of organization</td>
                                    <td valign='top' width="35%">
                                        <select name='bank_account_holder' id='bank_account_holder'>
                                            <?php
                                            if ($mode == 'edit') {
                                                ?>
                                                <option value="<?php echo $bank_account_holder ?>"><?php echo $bank_account_holder ?></option>
                                                <?php
                                            } else {
                                                ?>
                                                <option value=''> </option>
                                                <?php
                                            }
                                            echo "<option value='Bank'>Bank</option>";
                                            echo "<option value='MFI'>MFI</option>";
                                            echo "<option value='Sacco'>Sacco</option>";
                                            ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td valign='top' width="15%">Do you have other access to credit</td>
                                    <td valign='top' width="35%">
                                        <select name='credit' id='credit'>
                                            <?php
                                            if ($mode == 'edit') {
                                                ?>
                                                <option value="<?php echo $credit ?>"><?php echo $credit ?></option>
                                                <?php
                                            } else {
                                                ?>
                                                <option value=''> </option>
                                                <?php
                                            }
                                            echo "<option value='Yes'>Yes</option>";
                                            echo "<option value='No'>No</option>";
                                            ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td valign='top' width="15%">If yes, with what organisation?</td>
                                    <td valign='top' width="35%">
                                        <select name='loan_account' id='loan_account'>
                                            <?php
                                            if ($mode == 'edit') {
                                                ?>
                                                <option value="<?php echo $loan_account ?>"><?php echo $loan_account ?></option>
                                                <?php
                                            } else {
                                                ?>
                                                <option value=''> </option>
                                                <?php
                                            }
                                            echo "<option value='Bank'>Bank</option>";
                                            echo "<option value='MFI'>MFI</option>";
                                            echo "<option value='Sacco'>Sacco</option>";
                                            echo "<option value='Chama'>Chama</option>";
                                            echo "<option value='Shylok'>Shylok</option>";
                                            echo "<option value='Telephone Company'>Telephone Company</option>";
                                            ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td valign='top' width="15%">How many loans do you have? </td>
                                    <td valign='top' width="35%">
                                        <input title="loan_number" value="<?php echo $loan_number ?>" id="loan_number" name="loan_number" type="text" maxlength="100" class="main_input" size="35" />
                                    </td>
                                </tr>
                                <tr>
                                    <td valign='top' width="15%">On average how many customers do you serve in a day?</td>
                                    <td valign='top' width="35%">
                                        <input title="daily_customers" value="<?php echo $daily_customers ?>" id="daily_customers" name="daily_customers" type="text" maxlength="100" class="main_input" size="35" />
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <?php if ($title == '1' || $title == '2' || $title == '4') { ?>
                        <table border="0" width="100%">
                            <tr>
                                <td valign="top">
                                    <button name="btnNewCard" id="button">Save</button>
                                </td>
                                <td align="right">
                                    <button name="reset" id="button2" type="reset">Reset</button>
                                </td>		
                            </tr>
                        </table>
                    <?php } ?>
                    <script  type="text/javascript">
                        var frmvalidator = new Validator("frmOrder");
                        frmvalidator.addValidation("business_cycle", "req", "Please select business cycle");
                        frmvalidator.addValidation("business_category", "req", "Please select business category");
                        frmvalidator.addValidation("business_type", "req", "Please select business type");
                        frmvalidator.addValidation("trading_product", "req", "Please enter trading product");
                        frmvalidator.addValidation("trading_location", "req", "Please enter trading location");
                        frmvalidator.addValidation("business_address", "req", "Please enter bisiness address");
                        frmvalidator.addValidation("stock_value", "req", "Please enter stock value");
                        frmvalidator.addValidation("weekly_sales", "req", "Please enter weekly sales");
                        frmvalidator.addValidation("spend_stock", "req", "Please enter spend stock");
                        frmvalidator.addValidation("business_rent", "req", "Please enter business rent");
                        frmvalidator.addValidation("business_utilities", "req", "Please enter business utilities");
                        frmvalidator.addValidation("employees", "req", "Please enter employees expenses");
                        frmvalidator.addValidation("licensing", "req", "Please enter licensing details");
                        frmvalidator.addValidation("storage", "req", "Please enter storage details");
                        frmvalidator.addValidation("transport", "req", "Please enter transport details");
                        frmvalidator.addValidation("no_of_employees", "req", "Please enter no of employees");
                        frmvalidator.addValidation("weekly_restock", "req", "Please enter the number of restocks in a week");
                        frmvalidator.addValidation("house_rent", "req", "Please enter house rent");
                        frmvalidator.addValidation("house_utilities", "req", "Please enter house utilities");
                        frmvalidator.addValidation("food_expense", "req", "Please enter food expenses");
                        frmvalidator.addValidation("school_fees", "req", "Please enter school fees");
                        frmvalidator.addValidation("stock_neat", "req", "Please enter stock neat");
                        frmvalidator.addValidation("ledger_book", "req", "Please enter ledger book");
                        frmvalidator.addValidation("sales_activity", "req", "Please enter sales activity");
                        frmvalidator.addValidation("permanent_operation", "req", "Please enter permanent operation");
                        frmvalidator.addValidation("proof_ownership", "req", "Please enter proof ownership");
                        frmvalidator.addValidation("forthcoming", "req", "Please enter forthcoming");
                        frmvalidator.addValidation("market_authorities", "req", "Please enter market authorities");
                        frmvalidator.addValidation("sound_reputation", "req", "Please enter sound reputation");
                        frmvalidator.addValidation("lend", "req", "Please select lend");
                        frmvalidator.addValidation("lend_amount", "req", "Please enter lend amount");
                        frmvalidator.addValidation("income_explanation", "req", "Please enter the Income Explanation");
                        frmvalidator.addValidation("bank_account", "req", "Please enter does the customer have a bank account");
                        //frmvalidator.addValidation("bank_account_holder","req","Please enter the Income Explanation")
                        frmvalidator.addValidation("credit", "req", "Please enter if the customer has access to credit");
                        //frmvalidator.addValidation("loan_account","req","Please enter the Income Explanation");
                        frmvalidator.addValidation("loan_number", "req", "Please enter how many loans they have");
                        frmvalidator.addValidation("daily_customers", "req", "Please enter the number of customers per day");
                    </script>
                </form>
            </div>
        </div>
        <br class="clearfix" />
    </div>
    </div>
    <?php
    if (!empty($_POST)) {
        $business_cycle = $_POST['business_cycle'];
        $business_category = $_POST['business_category'];
        $old_business_category = $_POST['old_business_category'];
        $business_type = $_POST['business_type'];
        $old_business_type = $_POST['old_business_type'];
        $business_address = $_POST['business_address'];
        $old_business_address = $_POST['old_business_address'];
        $business_address = mysql_real_escape_string($business_address);
        $trading_product = $_POST['trading_product'];
        $trading_product = date('Y-m-d', strtotime(str_replace('-', '/', $trading_product)));
        $old_trading_product = $_POST['old_trading_product'];
        $old_trading_product = date('Y-m-d', strtotime(str_replace('-', '/', $old_trading_product)));
        $trading_location = $_POST['trading_location'];
        $trading_location = date('Y-m-d', strtotime(str_replace('-', '/', $trading_location)));
        $old_trading_location = $_POST['old_trading_location'];
        $old_trading_location = date('Y-m-d', strtotime(str_replace('-', '/', $old_trading_location)));

        $stock_value = $_POST['stock_value'];
        $old_stock_value = $_POST['old_stock_value'];
        $weekly_sales = $_POST['weekly_sales'];
        $old_weekly_sales = $_POST['old_weekly_sales'];
        $spend_stock = $_POST['spend_stock'];
        $old_spend_stock = $_POST['old_spend_stock'];
        $income_explanation = $_POST['income_explanation'];
        $old_income_explanation = $_POST['old_income_explanation'];
        $weekly_restock = $_POST['weekly_restock'];

        $business_rent = $_POST['business_rent'];
        $old_business_rent = $_POST['old_business_rent'];
        $business_utilities = $_POST['business_utilities'];
        $old_business_utilities = $_POST['old_business_utilities'];
        $no_of_employees = $_POST['employees'];
        $old_employees = $_POST['old_employees'];
        $no_of_employees = $_POST['no_of_employees'];
        $old_no_of_employees = $_POST['old_no_of_employees'];
        $licensing = $_POST['licensing'];
        $old_licensing = $_POST['old_licensing'];
        $storage = $_POST['storage'];
        $old_storage = $_POST['old_storage'];
        $transport = $_POST['transport'];
        $old_transport = $_POST['old_transport'];

        $house_rent = $_POST['house_rent'];
        $old_house_rent = $_POST['old_house_rent'];
        $house_utilities = $_POST['house_utilities'];
        $old_house_utilities = $_POST['old_house_utilities'];
        $food_expense = $_POST['food_expense'];
        $old_food_expense = $_POST['old_food_expense'];
        $school_fees = $_POST['school_fees'];
        $old_school_fees = $_POST['old_school_fees'];

        $weekly_cont = $_POST['weekly_cont'];
        $old_weekly_cont = $_POST['old_weekly_cont'];
        $chama_members = $_POST['chama_members'];
        $old_chama_members = $_POST['old_chama_members'];
        $chama_payout = $_POST['chama_payout'];
        $old_chama_payout = $_POST['old_chama_payout'];
        $payout_freq = $_POST['payout_freq'];
        $old_payout_freq = $_POST['old_payout_freq'];

        $stock_neat = $_POST['stock_neat'];
        $old_stock_neat = $_POST['old_stock_neat'];
        $ledger_book = $_POST['ledger_book'];
        $old_ledger_book = $_POST['old_ledger_book'];
        $sales_activity = $_POST['sales_activity'];
        $old_sales_activity = $_POST['old_sales_activity'];
        $permanent_operation = $_POST['permanent_operation'];
        $old_permanent_operation = $_POST['old_permanent_operation'];
        $proof_ownership = $_POST['proof_ownership'];
        $old_proof_ownership = $_POST['old_proof_ownership'];
        $forthcoming = $_POST['forthcoming'];
        $old_forthcoming = $_POST['old_forthcoming'];
        $market_authorities = $_POST['market_authorities'];
        $old_market_authorities = $_POST['old_market_authorities'];
        $sound_reputation = $_POST['sound_reputation'];
        $old_sound_reputation = $_POST['old_sound_reputation'];
        $lend = $_POST['lend'];
        $old_lend = $_POST['old_lend'];
        $lend_amount = $_POST['lend_amount'];
        $old_lend_amount = $_POST['old_lend_amount'];

        $bank_account = $_POST['bank_account'];
        $bank_account_holder = $_POST['bank_account_holder'];
        $credit = $_POST['credit'];
        $loan_account = $_POST['loan_account'];
        $loan_number = $_POST['loan_number'];
        $daily_customers = $_POST['daily_customers'];

        $page_status = $_POST['page_status'];
        $users_id = $_POST['users_id'];

        $completed = $_POST['completed'];
        $no_of_days = $_POST['no_of_days'];
        $transaction_time = $_POST['transaction_time'];
        $owns = $_POST['owns'];

        if ($no_of_employees == '0') {
            $no_of_employees = '1';
        }


        if ($business_cycle == 'Daily') {
            $weekly_sales = $weekly_sales * 24;
            $spend_stock = $spend_stock * 24;
            $business_rent = $business_rent * 24;
            $business_utilities = $business_utilities * 24;
            if ($licensing == '0') {
                $licensing = 20;
            }
            $licensing = $licensing * 24;
            $storage = $storage * 24;
            $transport = $transport * 24;
            $house_rent = $house_rent * 24;
            $house_utilities = $house_utilities * 24;
            $food_expense = $food_expense * 24;
            $school_fees = $school_fees * 24;
        } else if ($business_cycle == 'Weekly') {
            $weekly_sales = $weekly_sales * 4;
            $spend_stock = $spend_stock * 4;
            $business_rent = $business_rent * 4;
            $business_utilities = $business_utilities * 4;
            if ($licensing == '0') {
                $licensing = 1000;
            }
            $licensing = $licensing * 4;
            $storage = $storage * 4;
            $transport = $transport * 4;
            $house_rent = $house_rent * 4;
            $house_utilities = $house_utilities * 4;
            $food_expense = $food_expense * 4;
            $school_fees = $school_fees * 4;
        } else if ($business_cycle == 'Monthly') {
            $weekly_sales = $weekly_sales;
            $spend_stock = $spend_stock;
            $business_rent = $business_rent;
            $business_utilities = $business_utilities;
            if ($licensing == '0') {
                $licensing = 400;
            }
            $licensing = $licensing;
            $storage = $storage;
            $transport = $transport;
            $house_rent = $house_rent;
            $house_utilities = $house_utilities;
            $food_expense = $food_expense;
            $school_fees = $school_fees;
        }

        //Affordability Calculation
        $restocking_ratio = $stock_value / $spend_stock;
        if ($restocking_ratio < 0.5) {
            $stock_health_multiplier = 1.5;
        } else if ($restocking_ratio <= 1) {
            $stock_health_multiplier = 1.25;
        } else if ($restocking_ratio <= 4) {
            $stock_health_multiplier = 1.1;
        } else {
            $stock_health_multiplier = 0.8;
        }

        //Rules
        $gross_profit = $weekly_sales - $spend_stock;
        $business_expenses = $business_rent + $employess + $business_utilities + $licensing + $storage + $transport;
        $net_profit = $gross_profit - $business_expenses;
        $cost_of_living = $house_rent + $house_utilities + $food_expense + $school_fees + $weekly_cont;
        $other_income = $weekly_cont - $chama_payout;
        $cost_of_sales = ($spend_stock / $weekly_sales) * 100;
        $min_aallowed_personal_expenses = (20 / 100) * $gross_profit;

        //Rule 1: If Min Allowed Personal Expenses = 20% of Gross Profit: affordability = 0
        if ($min_aallowed_personal_expenses == $cost_of_living) {
            $affordability = 0;
        }

        //Rule 2: Cost of Sales = Spend of Stock/ Sales; if Cost of Sales < 15% or > 85%; affordability = 0
        if ($cost_of_sales < 15 || $cost_of_sales > 85) {
            $affordability = 0;
        }

        //Rule 3: If minimum stock level is less than KES 2,500; affordability = 0
        if ($stock_value < 2500) {
            $affordability = 0;
        }

        //Rule 3.1: If number of restocks does not match stock health multiplier, affordability = 0 
        if ($stock_health_multiplier != $weekly_restock) {
            $affordability = 0;
        }

        //Rule 4: Overall Calculation
        //Affordabililty Calculation
        if ($affordability != 0 || $affordability == "") {
            $max_value = max($min_aallowed_personal_expenses, $cost_of_living);
            $affordability = ($net_profit - max($min_aallowed_personal_expenses, $cost_of_living)) * ($stock_health_multiplier / 1.30);
        } else {
            $affordability = 0;
        }

        $length_income_explanation = strlen($income_explanation);
        $length_business_address = strlen($business_address);
        $exists_national_id = MD5(exists_national_id);

        if ($length_business_address > '80' && $length_income_explanation > '80') {
            if ($page_status == 'edit') {
//                $sql2 = "delete from business_details where user_id = '$users_id'";
//     
                //    $result = mysql_query($sql);
                $date = date("l, F d", strtotime($transaction_time));

                if (($no_of_days < '1' && $date != date("l, F d") ) || $no_of_days >= '14') {

                    $bus = array();
                    $bus["user_id"] = "$users_id";
                    $bus["business_cycle"] = "$business_cycle";
                    $bus["business_category"] = "$business_category";
                    $bus["business_type"] = "$business_type";
                    $bus["trading_product"] = "$trading_product";
                    $bus["trading_location"] = "$trading_location";
                    $bus["business_address"] = "$business_address";
                    $bus["stock_value"] = "$stock_value";
                    $bus["weekly_sales"] = "$weekly_sales";
                    $bus["spend_stock"] = "$spend_stock";
                    $bus["income_explanation"] = "$income_explanation";
                    $bus["business_rent"] = "$business_rent";
                    $bus["business_utilities"] = "$business_utilities";
                    $bus["employees"] = "$employees";
                    $bus["licensing"] = "$licensing";
                    $bus["storage"] = "$storage";
                    $bus["transport"] = "$transport";
                    $bus["house_rent"] = "$house_rent";
                    $bus["house_utilities"] = "$house_utilities";
                    $bus["food_expense"] = "$food_expense";
                    $bus["school_fees"] = "$school_fees";
                    $bus["weekly_cont"] = "$weekly_cont";
                    $bus["chama_members"] = "$chama_members";
                    $bus["chama_payout"] = "$chama_payout";
                    $bus["payout_freq"] = "$payout_freq";
                    $bus["stock_neat"] = "$stock_neat";
                    $bus["ledger_book"] = "$ledger_book";
                    $bus["sales_activity"] = "$sales_activity";
                    $bus["permanent_operation"] = "$permanent_operation";
                    $bus["proof_ownership"] = "$proof_ownership";
                    $bus["forthcoming"] = "$forthcoming";
                    $bus["market_authorities"] = "$market_authorities";
                    $bus["sound_reputation"] = "$sound_reputation";
                    $bus["lend"] = "$lend";
                    $bus["lend_amount"] = "$lend_amount";
                    $bus["restocking_ratio"] = "$restocking_ratio";
                    $bus["stock_health_multiplier"] = "$stock_health_multiplier";
                    $bus["UID"] = "$userid";
                    $bus["no_of_employees"] = "$no_of_employees";
                    $bus["bus_flag"] = 'yes';
                    $bus["incom_flag"] = 'yes';
                    $bus["exp_flag"] = 'yes';
                    $bus["pers_flag"] = 'yes';
                    $bus["chama_flag"] = 'yes';
                    $bus["assess_flag"] = 'yes';
                    $bus["completed"] = 'yes';
                    $bus["bank_account"] = "$bank_account";
                    $bus["bank_account_holder"] = "$bank_account_holder";
                    $bus["credit"] = "$credit";
                    $bus["loan_account"] = "$loan_account";
                    $bus["loan_number"] = "$loan_number";
                    $bus["daily_customers"] = "$daily_customers";
                    $bus["weekly_restock"] = "$weekly_restock";


                    $busn = json_encode($bus);

                    $recordCrb = new RecordCrb();
                    $f = $recordCrb->updateBusinessDetails($busn, "$users_id", "$affordability");

                    $sql5 = "insert into change_log(UID, table_name, table_id, variable, old_value, new_value, transactiontime)values('$userid', 'users', '$id_latest', 'affordability', '$old_affordability', '$affordability', '$transactiontime')";
                    $result = mysql_query($sql5);

//                    // Check CRB Listing
//                    $data = get_crb_listing($users_id);
//                    $data_string = json_decode($data, true);
//
//                    $n = $data_string['data']['npaAccounts'];
//
//                    //$recordCrb = new RecordCrb();
//                    $c = $recordCrb->createCrbEntry($data, "$users_id");

//                    if ($n > 0) {
//                        $recordCrb = new RecordCrb();
//                        $c = $recordCrb->createCrbEntry($data, "$users_id");
//                    } else
//                    if ($n == 0) {
//                        $recordCrb = new RecordCrb();
//                        $d = $recordCrb->deleteCrbEntry($busn, "$users_id", "$affordability");
//                    } else {
//                        $recordCrb = new RecordCrb();
//                        $c = $recordCrb->createCrbEntry($data, "$users_id");
//                    }
                }
                $query = "business_details.php?user_id=$customer_id&mode=edit";
                ?>
                <script type="text/javascript">
                    < ! -
                            -
                            document.location = "<?php echo $query ?>";
                            //-->
                </script>
                <?php
            }
        } else {
            $query = "business_details.php?status=length_business_address&length_business_address=$exists_national_id&user_id=$users_id&mode=edit";
            ?>
            <script type="text/javascript">
                        < ! -
                        -
                        document.location = "<?php echo $query ?>";
                        //-->
            </script>
            <?php
        }
    }
}
include_once('includes/footer.php');
?>
