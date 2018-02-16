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
		$page_title = "Leads Report";
		include_once('includes/header.php');
		$filter_clerk = 0;
		if (!empty($_GET)){	
			$market = $_GET['market'];
			$filter_start_date = $_GET['report_start_date'];
			$filter_start_date = date('Y-m-d', strtotime(str_replace('-', '/', $filter_start_date)));
			$filter_end_date = $_GET['report_end_date'];
			$filter_end_date = date('Y-m-d', strtotime(str_replace('-', '/', $filter_end_date)));
		}
		include_once('includes/db_conn.php');
		if ($filter_start_date != "" && $filter_end_date != ""){
		    $sql2 = mysql_query("select market from markets where id = '$market'");
            while($row = mysql_fetch_array($sql2)) {
                $market_name = $row['market'];
            }
            
            if($station == '3'){
                $sql2 = mysql_query("select count(id)leads from users where national_id = '' and transactiontime between '$filter_start_date' and '$filter_end_date'");
                while($row = mysql_fetch_array($sql2)) {
                    $leads = $row['leads'];
                }
                
                $sql2 = mysql_query("select count(loan_id)customers from loan_application where initiation_fee != '0' and loan_date between '$filter_start_date' and '$filter_end_date' and loan_status != '4' and loan_status != '3' and loan_status != '10' and loan_status != '11' and loan_status != '12' order by customer_station");
                while($row = mysql_fetch_array($sql2)) {
                    $customers = $row['customers'];
                }
                
                $all_leads = $customers + $leads;
                $conversion = ($customers/ $all_leads) * 100;
            }
            else{
                $sql2 = mysql_query("select count(id)leads from users where national_id = '' and stations = '$station' and transactiontime between '$filter_start_date' and '$filter_end_date'");
                while($row = mysql_fetch_array($sql2)) {
                    $leads = $row['leads'];
                }

                $sql2 = mysql_query("select count(loan_id)customers from loan_application where initiation_fee != '0' and loan_date between '$filter_start_date' and '$filter_end_date' and loan_status != '4' and loan_status != '3' and loan_status != '10' and loan_status != '11' and loan_status != '12' and customer_station = '$station' order by customer_station");
                while($row = mysql_fetch_array($sql2)) {
                    $customers = $row['customers'];
                }
                
                $all_leads = $customers + $leads;
                $conversion = ($customers/ $all_leads) * 100;
            }
			
		?>
		<div id="page">
			<div id="content">
				<div class="post">
					<h2><?php echo $page_title ?></h2>
					<?php if($station == '3'){ ?>
					    <h2>Lead Convertion Rate: <?php echo number_format($conversion, 2) ?>%</h2>
					<?php } else { ?>
					    <h2>Lead Convertion Rate: <?php echo number_format($conversion, 2) ?>%</h2>
					<?php } ?>
					<?php if($market != ""){ ?>
					    <h3>Market: <?php echo $market_name ?></h3>
					<?php } ?>
					<p><strong>Report Range: <?php echo $filter_start_date ?> to <?php echo $filter_end_date ?></strong></p>
					<table width="100%" border="0" cellspacing="2" cellpadding="2" class="display" id="example2">
					<thead bgcolor="#E6EEEE">
						<tr>
							<th>#</th>
							<th>Name</th>
							<th>Mobile No</th>
							<th>Next Visit</th>
							<th>Branch</th>
							<th>Market</th>
							<th>Date Created</th>
						</tr>
					</thead>
					<tbody>
					<?php
						if($station == '3'){
						    if($market != ""){
						        $sql = mysql_query("select concat(first_name, ' ', last_name)customer_name, mobile_no, next_visit, stations, market, transactiontime from users where national_id = '' and transactiontime between '$filter_start_date' and '$filter_end_date' and market = '$market' order by next_visit desc");
						    }
						    else{
						        $sql = mysql_query("select concat(first_name, ' ', last_name)customer_name, mobile_no, next_visit, stations, market, transactiontime from users where national_id = '' and transactiontime between '$filter_start_date' and '$filter_end_date' order by next_visit desc");
						    }
						}
						else{
						    if($market != ""){
						        $sql = mysql_query("select concat(first_name, ' ', last_name)customer_name, mobile_no, next_visit, stations, market, transactiontime from users where national_id = '' and transactiontime between '$filter_start_date' and '$filter_end_date' and stations = '$station' and market = '$market' order by next_visit desc");
						    }
						    else{
							    $sql = mysql_query("select concat(first_name, ' ', last_name)customer_name, mobile_no, next_visit, stations, market, transactiontime from users where national_id = '' and transactiontime between '$filter_start_date' and '$filter_end_date' and stations = '$station' order by next_visit desc");
							}
						}
						
						while ($row = mysql_fetch_array($sql))
						{
							$intcount++;
							$customer_name = $row['customer_name'];
							$customer_name = ucwords(strtolower($customer_name));
							$mobile_no = $row['mobile_no'];
							$next_visit = $row['next_visit'];
							$stations = $row['stations'];
							$market = $row['market'];
							$transactiontime = $row['transactiontime'];
							
							$sql2 = mysql_query("select id, stations from stations where id = '$stations'");
							while($row = mysql_fetch_array($sql2)) {
								$stations = $row['stations'];
								$stations = ucwords(strtolower($stations));
							}
							$sql2 = mysql_query("select market from markets where id = '$market'");
							while($row = mysql_fetch_array($sql2)) {
								$market = $row['market'];
							}
							if ($intcount % 2 == 0) {
								$display= '<tr bgcolor = #F0F0F6>';
							}
							else {
								$display= '<tr>';
							}
							echo $display;
							echo "<td valign='top'>$intcount.</td>";
							echo "<td valign='top'>$customer_name</td>";
							echo "<td valign='top'>$mobile_no</td>";	
							echo "<td valign='top'>$next_visit</td>";	
							echo "<td valign='top'>$stations</td>";	
							echo "<td valign='top'>$market</td>";	
							echo "<td valign='top'>$transactiontime</td>";
							echo "</tr>";
						}
						?>
					</tbody>
					<tfoot bgcolor="#E6EEEE">
						<tr>
							<th>#</th>
							<th>Name</th>
							<th>Mobile No</th>
							<th>Next Visit</th>
							<th>Branch</th>
							<th>Market</th>
							<th>Date Created</th>
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
							<tr >
                                <td  valign="top">Market: </td>
                                <td>
                                    <select name='market' id='market'>
                                        <option value=''> </option>
                                        <?php
                                        if($station == '3'){
                                            $sql2 = mysql_query("select id, market from markets order by market asc");
                                        }
                                        else{
                                            $sql2 = mysql_query("select id, market from markets where station = '$station' order by market asc");
                                        }
                                        while ($row = mysql_fetch_array($sql2)) {
                                            $id = $row['id'];
                                            $market = $row['market'];
                                            echo "<option value='$id'>" . $market . "</option>";
                                            $staff_name = "";
                                        }
                                        ?>
                                    </select>
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
