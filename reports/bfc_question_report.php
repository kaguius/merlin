<?php
	$userid = "";
	$adminstatus = 4;
	$property_manager_id = "";
	session_start();
	if (!empty($_SESSION)){
		$userid = $_SESSION["userid"] ;
		$adminstatus = $_SESSION["adminstatus"] ;
		$username = $_SESSION["username"];
		$station = $_SESSION["station"] ;
	}

	//if($adminstatus != 1 || $adminstatus != 2 || $adminstatus != 4){
	if($adminstatus == 4){
		include_once('includes/header.php');
		?>
		<script type="text/javascript">
			document.location = "insufficient_permission.php";
		</script>
		<?php
	}
	else{
		$transactiontime = date("Y-m-d G:i:s");
		$page_title = "BLC/ BFC Questions Report";
		include_once('includes/header.php');
		$filter_clerk = 0;
		if (!empty($_GET)){	
			$filter_clerk = $_GET['clerk'];
			$filter_start_date = $_GET['report_start_date'];
			$filter_start_date = date('Y-m-d', strtotime(str_replace('-', '/', $filter_start_date)));
			$filter_end_date = $_GET['report_end_date'];
			$filter_end_date = date('Y-m-d', strtotime(str_replace('-', '/', $filter_end_date)));
		}
		include_once('includes/db_conn.php');
		if ($filter_start_date != "" && $filter_end_date != ""){
		    $filter_start_date = $filter_start_date.' 00:00:00';
			$filter_end_date = $filter_end_date.' 23:59:59';
			
		?>
		<div id="page">
			<div id="content">
				<div class="post">
					<h2><?php echo $page_title ?></h2>
					<p><strong>Report Range: <?php echo $filter_start_date ?> to <?php echo $filter_end_date ?></strong></p>
					<table width="100%" border="0" cellspacing="2" cellpadding="2" class="display" id="example2">
                            <thead bgcolor="#E6EEEE">
                                <tr>
                                    <th>#</th>
                                    <th>Customer</th>
                                    <th>Loan Code</th>
                                    <th>Loan Status</th>
                                    <th>Result</th>
                                    <th>Category</th>
                                    <th>Reason</th>
                                    <th>Detail</th>
                                    <th>Staff</th>
                                    <th>Transactiontime</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql = mysql_query("select id, loan_id, total_sum, reason_for_default, other_sources, UID, transactiontime from bfc_questions where transactiontime between '$filter_start_date' and '$filter_end_date' order by transactiontime asc");
                                $intcount = 0;
                                while ($row = mysql_fetch_array($sql)) {
                                    $intcount++;
                                    $id = $row['id'];
                                    $loan_id = $row['loan_id'];
                                    $total_sum = $row['total_sum'];
                                    $reason_for_default = $row['reason_for_default'];
                                    $other_sources = $row['other_sources'];
                                    $UID = $row['UID'];
                                    $transactiontime = $row['transactiontime'];
                                    if($total_sum >= '4'){
                                        $category = 'BFC';
                                    }
                                    else{
                                        $category = 'BLC';
                                    }

                                    $sql2 = mysql_query("select loan_code, customer_id, customer_status.status from loan_application inner join customer_status on customer_status.id = loan_application.loan_status where loan_id = '$loan_id'");
                                    while ($row = mysql_fetch_array($sql2)) {
                                        $loan_code = $row['loan_code'];
                                        $status = $row['status'];
                                        $customer_id = $row['customer_id'];
                                    }
                                    
                                    $sql2 = mysql_query("select first_name, last_name from users where id = '$customer_id'");
                                    while ($row = mysql_fetch_array($sql2)) {
                                        $first_name = $row['first_name'];
                                        $last_name = $row['last_name'];
                                        $customer_name = $first_name . " " . $last_name;
                                    }
                                    
                                    $sql2 = mysql_query("select first_name, last_name from user_profiles where id = '$UID'");
                                    while ($row = mysql_fetch_array($sql2)) {
                                        $first_name = $row['first_name'];
                                        $last_name = $row['last_name'];
                                        $staff_name = $first_name . " " . $last_name;
                                    }
                                    
                                    $sql2 = mysql_query("select reason_for_default from reason_for_default where id = '$reason_for_default'");
									while($row = mysql_fetch_array($sql2)) {
										$reason_for_default_name = $row['reason_for_default'];
									}

                                    if ($intcount % 2 == 0) {
                                        $display = '<tr bgcolor = #F0F0F6>';
                                    } else {
                                        $display = '<tr>';
                                    }
                                    echo $display;
                                    echo "<td valign='top'>$intcount.</td>";
                                    echo "<td valign='top'>$customer_name.</td>";
                                    echo "<td valign='top'>$loan_code</td>";
                                    echo "<td valign='top'>$status</td>";
                                    echo "<td valign='top'>$total_sum</td>";
                                    echo "<td valign='top'>$category</td>";
                                    echo "<td valign='top'>$reason_for_default_name</td>";
                                    echo "<td valign='top'>$other_sources</td>";
                                    echo "<td valign='top'>$staff_name</td>";
                                    echo "<td valign='top'>$transactiontime</td>";
                                    echo "</tr>";
                                }
                                ?>
                            </tbody>
                            <tfoot bgcolor="#E6EEEE">
                                <tr>
                                    <th>#</th>
                                    <th>Customer</th>
                                    <th>Loan Code</th>
                                    <th>Loan Status</th>
                                    <th>Result</th>
                                    <th>Category</th>
                                    <th>Reason</th>
                                    <th>Detail</th>
                                    <th>Staff</th>
                                    <th>Transactiontime</th>
                                </tr>
                            </tfoot>
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
                                $("#example2").btechco_excelexport({
                                containerid: "example2"
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
		}
		else{
		?>		
		<div id="page">
			<div id="content">
				<div class="post">
				
					<h2><?php echo $page_title ?></h2>
					<form id="frmCreateTenant" name="frmCreateTenant" method="GET" action="<?php echo $_SERVER['PHP_SELF'];?>">
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
