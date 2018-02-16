<?php
	$userid = "";
	$adminstatus = 4;
	$property_manager_id = "";
	$passportfileupload = "";
	$resumefileupload = "";
	session_start();
	if (!empty($_SESSION)){
		$userid = $_SESSION["userid"] ;
		$adminstatus = $_SESSION["adminstatus"] ;
		$station = $_SESSION["station"] ;
		$username = $_SESSION["username"];
		$passportfileupload = $_SESSION["passportfileupload"];
		$resumefileupload = $_SESSION["resumefileupload"];
	}
	
	if (!empty($_GET)){	
		$mode = $_GET['mode'];
		$user_id = $_GET['user_id'];
		$status = $_GET['status'];
		$id_status = $_GET['status'];
		$loan_id = $_GET['loan_id'];
		$user_id = $_GET['user_id'];
	}
	include_once('includes/db_conn.php');
	
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
		if (!empty($_GET)){	
			$mode = $_GET['mode'];
			$loan_id = $_GET['loan_id'];
			$user_id = $_GET['user_id'];
			$bfc_id = $_GET['bfc_id'];
		}
		if ($mode=='edit'){
			$sql = mysql_query("select id, user_id, loan_id, question1, question2, question3, question4, question5, question6, question7, question8, question9, question10, total_sum, reason_for_default, other_sources, UID, transactiontime from bfc_questions where id = '$bfc_id'");
			while ($row = mysql_fetch_array($sql))
			{
				$question1 = $row['question1'];	
				$question2 = $row['question2'];	
				$question3 = $row['question3'];	
				$question4 = $row['question4'];	
				$question5 = $row['question5'];	
				$question6 = $row['question6'];	
				$question7 = $row['question7'];	
				$question8 = $row['question8'];	
				$question9 = $row['question9'];	
				$question10 = $row['question10'];	
				$total_sum = $row['total_sum'];	
				$UID = $row['UID'];	
				$transactiontime = $row['transactiontime'];	
				$reason_for_default = $row['reason_for_default'];
				$other_sources = $row['other_sources'];
				$UID = $row['UID'];
				if($total_sum >= '4'){
                    $category = 'BFC';
                }
                else{
                    $category = 'BLC';
                }
				
				$sql2 = mysql_query("select reason_for_default from reason_for_default where id = '$reason_for_default'");
                while ($row = mysql_fetch_array($sql2)) {
                    $reason_for_default = $row['reason_for_default'];
                }
                
                $sql2 = mysql_query("select first_name, last_name from user_profiles where id = '$UID'");
                while ($row = mysql_fetch_array($sql2)) {
                    $first_name = $row['first_name'];
                    $last_name = $row['last_name'];
                    $staff_name = $first_name . " " . $last_name;
                }
			}
			$page_title = "BFC/ BLC Questionnare Report";
		}
		else{
			$page_title = "BFC/ BLC Questionnare";
		}
		
		include_once('includes/header.php');
		
		?>		
		<div id="page">
			<div id="content">
				<div class="post">
				<h2><font color="#000A8B"><?php echo $page_title; ?></font></h2>
				<h3>Category: <?php echo $category ?></h3>
				<?php if($id_status == 'phone_number_length'){ ?>
					<table width="60%">
						<tr bgcolor="red">
							<td><font color="white" size="2">&nbsp;&nbsp;Yikes! Something's gone wrong.</td>
						</tr>
					</table>
					<font color="red">
					* Either the phone number entered is not the required format<br />
				</font>
				<?php } ?>	
				<form id="frmOrder" name="frmOrder" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
				<table border="0" width="100%" cellspacing="2" cellpadding="2">	
				<input type="hidden" name="loan_id" id="loan_id" value="<?php echo $loan_id	 ?>" />		
				<input type="hidden" name="user_id" id="user_id" value="<?php echo $user_id ?>" />
				<thead>
					<tr bgcolor='#FF8000'>
						<th width="50%">Question</th>
						<th width="30%">Objective</th>
						<th width="20%">Score</th>
					</th>
				</thead>
				<tbody>
					<tr>
						<td valign="top">Does the customer sound suspicious (are they evasive, tone of voice & answers arouse suspicion, is customer hostile or over assertive)</td>
						<td valign="top">Sense check to assess integrity</td>
						<td valign="top">
						<?php
						    if($question1 = '0'){
						        echo "Sincere";
						    }
						    else{
						        echo "Suspicious";
						    }
						?>
						</td>
					</tr>
					<tr>
						<td valign="top">Is the customer taking the situation seriously? Is their behaviour professional?</td>
						<td valign="top">Sense check to assess intent</td>
						<td valign="top">
						<?php
						    if($question2 = '0'){
						        echo "Sincere";
						    }
						    else{
						        echo "Suspicious";
						    }
						?>
						</td>
					</tr>
					<tr>
						<td valign="top">Is this their first loan?</td>
						<td valign="top">Credit History check</td>
						<td valign="top">
						<?php
						    if($question3 = '0'){
						        echo "No";
						    }
						    else{
						        echo "Yes";
						    }
						?>
						</td>
					</tr>
					<tr>
						<td valign="top">If not, how many previous loans have they had and what (if any) amount increments?</td>
						<td valign="top">Has branch breached increment SOPs? Have we granted them unmanageable credit?</td>
						<td valign="top">
						<?php
						    if($question4 = '0'){
						        echo "Good History";
						    }
						    else{
						        echo "Increment policy exceeded";
						    }
						?>
						</td>
					</tr>
					<tr>
						<td valign="top">Have previous payments been on time?</td>
						<td valign="top">Credit History check</td>
						<td valign="top">
						<?php
						    if($question5 = '0'){
						        echo "Yes";
						    }
						    else{
						        echo "No (incld extensions)";
						    }
						?>
						</td>
					</tr>
					<tr>
						<td valign="top">Is customer offering to repay at all?</td>
						<td valign="top">Sense check to assess intent</td>
						<td valign="top">
						<?php
						    if($question6 = '0'){
						        echo "Yes";
						    }
						    else{
						        echo "No";
						    }
						?>
						</td>
					</tr>
					<tr>
						<td valign="top">Is customer offering to repay the full amount?</td>
						<td valign="top">Sense check to assess integrity</td>
						<td valign="top">
						<?php
						    if($question7 = '0'){
						        echo "Yes";
						    }
						    else{
						        echo "No";
						    }
						?>
						</td>
					</tr>
					<tr>
						<td valign="top">Is customer suggesting a repayment plan of very small amounts over time?</td>
						<td valign="top">Sense check to assess integrity</td>
						<td valign="top">
						<?php
						    if($question8 = '0'){
						        echo "Yes";
						    }
						    else{
						        echo "Full Amount";
						    }
						?>
						</td>
					</tr>
					<tr>
						<td valign="top">What sector is the customer in?</td>
						<td valign="top">High or low risk customer?</td>
						<td valign="top">
						<?php
						    if($question9 = '0'){
						        echo "Jua Kali";
						    }
						    else{
						        echo "SME";
						    }
						?>
						</td>
					</tr>
					<tr>
						<td valign="top">Is customer a notable person in their community? E.g.: Local Politician, Community Church or business leader, Law Enforcement, Military or Government employee</td>
						<td valign="top">High or low risk customer?</td>
						<td valign="top">
						<?php
						    if($question10 = '0'){
						        echo "No";
						    }
						    else{
						        echo "Yes";
						    }
						?>
						</td>
					</tr>
					<tr>
						<td valign="top">Reasons for Default?</td>
						<td valign="top" colspan="2">
							<?php echo $reason_for_default ?>
						</td>
					</tr>
					<tr>
                            <td valign='top'>If others, please give more details</td>
                            <td valign='top' colspan="2">
                                <?php echo $other_sources ?>
                            </td>
                        </tr>
				</tbody>
				</table>
				    <script  type="text/javascript">
                        var frmvalidator = new Validator("frmOrder");
						frmvalidator.addValidation("mobile_no","req","Please specify the leads mobile number");
                        frmvalidator.addValidation("marketing_drive","req","Please specify how the lead came to know about us");
                    </script>
				</form>
				<p><strong><i>Created by <?php echo $staff_name ?> at <?php echo $transactiontime ?></i></strong></p>
			</div>
			</div>
			<br class="clearfix" />
			</div>
		</div>
<?php
	if (!empty($_POST)) {
		$user_id = $_POST['user_id'];
		$loan_id = $_POST['loan_id'];
		$question1 = $_POST['question1'];
		$question2 = $_POST['question2'];
		$question3 = $_POST['question3'];
		$question4 = $_POST['question4'];
		$question5 = $_POST['question5'];
		$question6 = $_POST['question6'];
		$question7 = $_POST['question7'];
		$question8 = $_POST['question8'];
		$question9 = $_POST['question9'];
		$question10 = $_POST['question10'];
		$reason_for_default = $_POST['reason_for_default'];
		$other_sources = $_POST['other_sources'];
		
		$total_sum = $question1 + $question12 + $question3 + $question4 + $question5 + $question6 + $questio7 + $question8 + $question9 + $question10;
		
		$sql = "INSERT INTO bfc_questions (user_id, loan_id, question1, question2, question3, question4, question5, question6, question7, question8, question9, question10, total_sum, reason_for_default, other_sources, UID, transactiontime)
		VALUES('$user_id', '$loan_id', '$question1', '$question2', '$question3', '$question4', '$question5', '$question6', '$question7', '$question8', '$question9', '$question10', '$total_sum', '$reason_for_default', '$other_sources', '$userid', '$transactiontime')";
		$result = mysql_query($sql);
		
		if($total_sum >= '4'){
		
			$sql = "update users set customer_state ='BFC' WHERE id  = '$user_id'";
			$result = mysql_query($sql);
			
			$sql2 = "update loan_application set customer_state ='BFC', loan_status = '5', late_status = '2' WHERE loan_id = '$loan_id'";
			$result = mysql_query($sql2);
		}
		else{
			$sql = "update users set customer_state ='BLC' WHERE id  = '$user_id'";
			$result = mysql_query($sql);
		}
		
		$query = "customer_loans.php?user_id=$user_id";
		
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
