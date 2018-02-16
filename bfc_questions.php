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
		}
		$transactiontime = date("Y-m-d G:i:s");
		if ($mode=='edit'){
			$sql = mysql_query("select passportfileupload, resumefileupload, mobile_no, title, first_name, last_name, national_id, preffered_language, nickname, next_visit, marital, dependants, alt_phone, dis_phone, lead_outcome, owns, home_occupy, stations, status, loan_officer, collections_officer, next_visit from users where id = '$user_id'");
			while ($row = mysql_fetch_array($sql))
			{
				$passportfileupload = $row['passportfileupload'];					
				$resumefileupload = $row['resumefileupload'];
				$mobile_no = $row['mobile_no'];
				$title = $row['title'];
				$first_name = $row['first_name'];
				$first_name = ucwords(strtolower($first_name));
				$last_name = $row['last_name'];
				$last_name = ucwords(strtolower($last_name));
				$national_id = $row['national_id'];
				$preffered_language = $row['preffered_language'];
				$nickname = $row['nickname'];
				$date_of_birth = $row['next_visit'];
				$marital = $row['marital'];
				$dependants = $row['dependants'];
				$alt_phone = $row['alt_phone'];
				$dis_phone = $row['dis_phone'];
				$lead_outcome = $row['lead_outcome'];
				$loan_officer = $row['loan_officer'];
				$collections_officer = $row['collections_officer'];
				$owns = $row['owns'];
				$owns = ucwords(strtolower($owns));
				$home_occupy = $row['home_occupy'];
				$stations = $row['stations'];
				$status = $row['status'];
				$loan_officer = $row['loan_officer'];
				$collections_officer = $row['collections_officer'];
				$next_visit_date = $row['next_visit'];
			}
			$page_title = "Update Lead Detail(s)";
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
							<select name='question1' id='question1'>
								<option value='0'>Sincere</option>
					    		<option value='1'>Suspicious</option>
							</select>
						</td>
					</tr>
					<tr>
						<td valign="top">Is the customer taking the situation seriously? Is their behaviour professional?</td>
						<td valign="top">Sense check to assess intent</td>
						<td valign="top">
							<select name='question2' id='question2'>
								<option value='0'>Sincere</option>
					    		<option value='1'>Suspicious</option>
							</select>
						</td>
					</tr>
					<tr>
						<td valign="top">Is this their first loan?</td>
						<td valign="top">Credit History check</td>
						<td valign="top">
							<select name='question3' id='question3'>
								<option value='0'>No</option>
					    		<option value='1'>Yes</option>
							</select>
						</td>
					</tr>
					<tr>
						<td valign="top">If not, how many previous loans have they had and what (if any) amount increments?</td>
						<td valign="top">Has branch breached increment SOPs? Have we granted them unmanageable credit?</td>
						<td valign="top">
							<select name='question4' id='question4'>
								<option value='0'>Good History</option>
					    		<option value='-1'>Increment policy exceeded</option>
							</select>
						</td>
					</tr>
					<tr>
						<td valign="top">Have previous payments been on time?</td>
						<td valign="top">Credit History check</td>
						<td valign="top">
							<select name='question5' id='question5'>
								<option value='0'>Yes</option>
					    		<option value='1'>No (incld extensions)</option>
							</select>
						</td>
					</tr>
					<tr>
						<td valign="top">Is customer offering to repay at all?</td>
						<td valign="top">Sense check to assess intent</td>
						<td valign="top">
							<select name='question6' id='question6'>
								<option value='0'>Yes</option>
					    		<option value='1'>No</option>
							</select>
						</td>
					</tr>
					<tr>
						<td valign="top">Is customer offering to repay the full amount?</td>
						<td valign="top">Sense check to assess integrity</td>
						<td valign="top">
							<select name='question7' id='question7'>
								<option value='0'>Yes</option>
					    		<option value='1'>No</option>
							</select>
						</td>
					</tr>
					<tr>
						<td valign="top">Is customer suggesting a repayment plan of very small amounts over time?</td>
						<td valign="top">Sense check to assess integrity</td>
						<td valign="top">
							<select name='question8' id='question8'>
								<option value='0'>Yes</option>
					    		<option value='1'>Full amount</option>
							</select>
						</td>
					</tr>
					<tr>
						<td valign="top">What sector is the customer in?</td>
						<td valign="top">High or low risk customer?</td>
						<td valign="top">
							<select name='question9' id='question9'>
								<option value='0'>Jua Kali</option>
					    		<option value='1'>SME</option>
							</select>
						</td>
					</tr>
					<tr>
						<td valign="top">Is customer a notable person in their community? E.g.: Local Politician, Community Church or business leader, Law Enforcement, Military or Government employee</td>
						<td valign="top">High or low risk customer?</td>
						<td valign="top">
							<select name='question10' id='question10'>
								<option value='0'>No</option>
					    		<option value='3'>Yes</option>
							</select>
						</td>
					</tr>
					<tr>
						<td valign="top">Reasons for Default?</td>
						<td valign="top" colspan="2">
							<select name='reason_for_default' id='reason_for_default'>
								<?php
									if($mode == 'edit'){
								?>
									<option value="<?php echo $reason_for_default_id ?>"><?php echo $reason_for_default ?></option>
									<option value=''> </option>	
								<?php
									}
									else{
									?>
									<option value=''> </option>
									<?php
									}
									//echo "<option value=''>" "</option>"; 																				
									$sql2 = mysql_query("select id, reason_for_default from reason_for_default order by reason_for_default asc");
									while($row = mysql_fetch_array($sql2)) {
										$reason_for_default_id = $row['id'];
										$reason_for_default = $row['reason_for_default'];
										echo "<option value='$reason_for_default_id'>".$reason_for_default."</option>"; 
									}
									?>
								</select>
						</td>
					</tr>
					<tr>
                            <td valign='top'>If others, please give more details</td>
                            <td valign='top' colspan="2">
                                <textarea title="other_sources" name="other_sources" id="other_sources" cols="95" rows="5" class="textfield"></textarea>
                            </td>
                        </tr>
				</tbody>
				</table>
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
				    <script  type="text/javascript">
                        var frmvalidator = new Validator("frmOrder");
						frmvalidator.addValidation("mobile_no","req","Please specify the leads mobile number");
                        frmvalidator.addValidation("marketing_drive","req","Please specify how the lead came to know about us");
                    </script>
				</form>
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
		
		if($total_sum >= '5'){
		
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
