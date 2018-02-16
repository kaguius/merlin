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
		$page_title = "Loans Disbursed Report";
		include_once('includes/db_conn.php');
		
		$filter_month = date("m");
		$filter_year = date("Y");
		$filter_day = date("d");
		$current_date = $filter_year.'-'.$filter_month.'-'.$filter_day;
		$current_date_full = date("d M, Y", strtotime($current_date));
		
		$report_term = 7;
		$start_report_date = date('Y-m-d',strtotime($current_date) - (24 * 3600 * $report_term));

		if (!empty($_GET)){	
			$loan_officer = $_GET['loan_officer'];
			$filter_start_date = $_GET['report_start_date'];
			$filter_start_date = date('Y-m-d', strtotime(str_replace('-', '/', $filter_start_date)));
			$filter_end_date = $_GET['report_end_date'];
			$filter_end_date = date('Y-m-d', strtotime(str_replace('-', '/', $filter_end_date)));
		}
		$filter_start_date = '2016-02-01';
		$filter_end_date = '2015-04-31';
			
		?>
					<h2><?php echo $page_title ?></h2>
					<h3>Report Date: <?php echo $filter_start_date_full ?> and <?php echo $filter_end_date_full ?></h3>
					<table width="100%" border="0" cellspacing="2" cellpadding="2" class="display" id="example2">
					<thead bgcolor="#E6EEEE">
						<tr>
							<th>#</th>
							<th>ID</th>
							<th>Affordability</th>
						</tr>
					</thead>
					<tbody>
					<?php
					    $intcount = 0;
						
						$sql10 = mysql_query("select user_id from statsketch_user_ids order by id asc");
						while ($row = mysql_fetch_array($sql10))
						{
						    $intcount++;
						    $user_id = $row['user_id'];	
						    
                            $sql = mysql_query("select customer_id, merlin_affordability from score where customer_id = '$user_id' order by id asc limit 1;");
                            while ($row = mysql_fetch_array($sql))
                            {
                                $intcount++;
                                $customer_id = $row['customer_id'];
                                $affordability = $row['merlin_affordability'];
                            
                                if ($intcount % 2 == 0) {
                                    $display= '<tr bgcolor = #F0F0F6>';
                                }
                                else {
                                    $display= '<tr>';
                                }
                            
                                echo $display;
                                echo "<td valign='top'>$intcount.</td>";
                                echo "<td valign='top'>$customer_id</td>";	
                                echo "<td valign='top'>$affordability</td>";	
                            }
                        }
						?>
					</tbody>
				</table>

<?php
	}
	include_once('includes/footer.php');
?>
