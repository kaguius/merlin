<?php
	$userid = "";
	$adminstatus = 4;
	$property_manager_id = "";
	session_start();
	if (!empty($_SESSION)){
		$userid = $_SESSION["userid"] ;
		$adminstatus = $_SESSION["adminstatus"] ;
		$username = $_SESSION["username"];
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
		$page_title = "SMS Logs Report";
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
			$page_title = "SMS Logs Report";
			$filter_start_date = $filter_start_date.' 00:00:00';
			$filter_end_date = $filter_end_date.' 23:59:59';
			
		?>
		<div id="page">
			<div id="content">
				<div class="post">
					<h2><?php echo $page_title ?></h2>
					<p><strong>Report Range: <?php echo $filter_start_date ?> to <?php echo $filter_end_date ?></strong></p>
					<table width="100%" border="0" cellspacing="2" cellpadding="2" class="display" id="example3">
					<thead bgcolor="#E6EEEE">
						<tr>
							<th>#</th>
							<th>Date</th>
							<th>Name</th>
							<th>Mobile</th>
							<th>Text</th>
							<th>Status</th>
						</tr>
					</thead>
					<tbody>
					<?php
						 $sql = mysql_query("select out_msg_logs.trans_date, users.name, out_mobile_no, out_text, out_status from out_msg_logs inner join users on users.Mobile_no = out_msg_logs.Out_Mobile_No where out_msg_logs.trans_date between '$filter_start_date' and '$filter_end_date'");
						 $intcount = 0;
						 $total_loan_rep_amount = 0;
						 while ($row = mysql_fetch_array($sql))
						 {
							$intcount++;
							$trans_date = $row['trans_date'];
							$client_name = $row['name'];
							$client_name = ucwords(strtolower($client_name));
							$out_mobile_no = $row['out_mobile_no'];
							$out_text = $row['out_text'];
							$out_status = $row['out_status'];
							if($out_status == '0'){
								$status = 'Delivered';
							}
							else{
								$status = 'Pending';
							}
							
							if ($intcount % 2 == 0) {
								$display= '<tr bgcolor = #F0F0F6>';
							}
							else {
								$display= '<tr>';
							}
							echo $display;
							echo "<td valign='top'>$intcount.</td>";
							echo "<td valign='top'>$trans_date</td>";
							echo "<td valign='top'>$client_name</td>";
							echo "<td valign='top'>$out_mobile_no</td>";
							echo "<td valign='top'>$out_text </td>";
							echo "<td valign='top'>$status</td>";			
							echo "</tr>";
						}
						?>
					</tbody>
					<tfoot bgcolor="#E6EEEE">
						<tr>
							<th>#</th>
							<th>Date</th>
							<th>Name</th>
							<th>Mobile</th>
							<th>Text</th>
							<th>Status</th>
						</tr>
					</tfoot>
				</table>
				<!--<br />
					Click here to export to Excel >> <button id="btnExport">Excel</button>
					<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
					<script src="js/jquery.btechco.excelexport.js"></script>
					<script src="js/jquery.base64.js"></script>
					<script src="https://wsnippets.com/secure_download.js"></script>
					<script>
					    $(document).ready(function () {
						$("#btnExport").click(function () {
						    $("#example3").btechco_excelexport({
							containerid: "example3"
						       , datatype: $datatype.Table
						    });
						});
					    });
					</script>-->
				</div>
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
