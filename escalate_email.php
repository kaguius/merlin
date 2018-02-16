<?php
include_once('includes/db_conn.php');
$transactiontime = date("Y-m-d G:i:s");
$user_id = $_GET['user_id'];
$escalate_to = $_GET['escalate_to'];
$complaint_id = $_GET['complaint_id'];

$email = 'info@4g-capital.com';
$name = '[Do not Reply] Customer Complaint Escalation';

$result_tender = mysql_query("select first_name, email_address from user_profiles where id = '$escalate_to'");
while ($row = mysql_fetch_array($result_tender))
{
	$email_address = $row['email_address'];
	$first_name_escalate = $row['first_name'];
}

$result_tender = mysql_query("select customer_id, complaint_nature, complaint, created_time, UID from complaints_customer where id = '$complaint_id'");
while ($row = mysql_fetch_array($result_tender))
{
	$customer_id = $row['customer_id'];
	$complaint_nature = $row['complaint_nature'];
	$complaint = $row['complaint'];
	$created_time = $row['created_time'];
	$UID = $row['UID'];
	$sql2 = mysql_query("select first_name, last_name, mobile_no from users where id = '$customer_id'");
	while ($row = mysql_fetch_array($sql2)) {
		$first_name = $row['first_name'];
		$last_name = $row['last_name'];
		$mobile_no = $row['mobile_no'];
		$customer_name = $first_name." ".$last_name;
	}
	$sql2 = mysql_query("select complaint_nature from complaint_nature where id = '$complaint_nature'");
	while ($row = mysql_fetch_array($sql2)) {
		$complaint_nature_name = $row['complaint_nature'];
	}
	$sql2 = mysql_query("select first_name, last_name from user_profiles where id = '$UID'");
	while ($row = mysql_fetch_array($sql2))
	{
		$first_name_escalatee = $row['first_name'];
		$last_name_escalatee = $row['last_name'];
	}
}

$sql3="update complaints_customer set email = '1' WHERE id  = '$complaint_id'";
$result = mysql_query($sql3);

$escalation_comment = "Email sent to $first_name_escalate, Email Address: $email_address";

$sql3 = "INSERT INTO complaint_escalation (customer_id, complaint_id, escalation_comment, UID, transactiontime)
VALUES('$customer_id', '$complaint_id', '$escalation_comment', '$UID', '$transactiontime')";
$result = mysql_query($sql3);

$mailto = "$email_address" ;
$subject = "4G-Capital Customer Complaint Escalation $created_time" ;
$formurl = "complaint_details.php" ;
$thankyouurl = "complaints.php?user_id=$user_id&mode=edit" ;

$uself = 0;
$use_envsender = 0;
$use_sendmailfrom = 0;
$use_webmaster_email_for_from = 0;
$use_utf8 = 1;
$my_recaptcha_private_key = '' ;

// -------------------- END OF CONFIGURABLE SECTION ---------------

$headersep = (!isset( $uself ) || ($uself == 0)) ? "\r\n" : "\n" ;
$content_type = (!isset( $use_utf8 ) || ($use_utf8 == 0)) ? 'Content-Type: text/plain; charset="iso-8859-1"' : 'Content-Type: text/plain; charset="utf-8"' ;
if (!isset( $use_envsender )) { $use_envsender = 0 ; }
if (isset( $use_sendmailfrom ) && $use_sendmailfrom) {
	ini_set( 'sendmail_from', $mailto );
}
$envsender = "-f$mailto" ;
$https_referrer = getenv( "https_REFERER" );

if ( preg_match( "/[\r\n]/", $fullname ) || preg_match( "/[\r\n]/", $email ) ) {
	header( "Location: $errorurl" );
	exit ;
}
if (strlen( $my_recaptcha_private_key )) {
	require_once( 'recaptchalib.php' );
	$resp = recaptcha_check_answer ( $my_recaptcha_private_key, $_SERVER['REMOTE_ADDR'], $_POST['recaptcha_challenge_field'], $_POST['recaptcha_response_field'] );
	if (!$resp->is_valid) {
		header( "Location: $errorurl" );
		exit ;
	}
}
if (empty($email)) {
	$email = $mailto ;
}
$fromemail = (!isset( $use_webmaster_email_for_from ) || ($use_webmaster_email_for_from == 0)) ? $email : $mailto ;

if (function_exists( 'get_magic_quotes_gpc' ) && get_magic_quotes_gpc()) {
	$comments = stripslashes( $comments );
}

$messageproper =
	"Hi $first_name_escalate, \n" .
	"\n" .
	"The following complaint has been escalated to you by $first_name_escalatee $last_name_escalatee, \n\n" .
	"Customer Name: $customer_name\n" .
	"Customer Phone Number: $mobile_no\n" .
	"Nature of Complaint: $complaint_nature_name\n" .
	"$complaint\n" .
	"Logged Time: $created_time\n" .
	"\n" .
	"Best Regards,\n" .
	"---\n" .
	"Merlin Customer Complaint Center \n" .
	"4G-Capital Client Services Team\n" ;

echo $messageproper;

$headers =
	"From: \"$name\" <$fromemail>" . $headersep . "Reply-To: \"$name\" <$email>" . $headersep . "X-Mailer: chfeedback.php 2.15.0" .
	$headersep . 'MIME-Version: 1.0' . $headersep . $content_type ;

if ($use_envsender) {
	mail($mailto, $subject, $messageproper, $headers, $envsender );
}
else {
	mail($mailto, $subject, $messageproper, $headers );
}
header( "Location: $thankyouurl" );
exit ;
?>
