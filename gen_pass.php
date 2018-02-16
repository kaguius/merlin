<?php
include_once('includes/db_conn.php');
$genpass = $_GET['pass'];
$genemail = $_GET['email'];

$email = 'support@afbkenya.freshdesk.com';
$name = '4G-Capital Loan Portal Administrator';

$result_tender = mysql_query("select first_name, last_name, username from user_profiles where email_address = '$genemail'");
while ($row = mysql_fetch_array($result_tender))
{
	$first_name = $row['first_name'];
	$last_name = $row['last_name'];
	$username = $row['username'];
}

$mailto = "$genemail" ;
$subject = "[4G-Capital Loan Portal] Your new Password" ;
$formurl = "password_gen.php" ;
$thankyouurl = "password_confirmation.php?email_address=$genemail" ;

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
	"Hi $first_name, \n" .
	"\n" .
	"User's name: $first_name $last_name\n" .
	"Username: $username\n" .
	"Password: $genpass\n" .
	"\n" .
	"Regards,\n" .
	"---\n" .
	"4G-Capital Loan Portal\n" .
	"4G-Capital Client Services Team\n" .
	"\n\n------------------------------------------------------------\n" ;

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
