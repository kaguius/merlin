<?php
	$page = $_SERVER['PHP_SELF'];
	$sec = "60";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="description" content="" />
		<meta name="keywords" content="" />
		<meta http-equiv="refresh" content="<?php echo $sec?>;URL='<?php echo $page?>'">
		<title><?php echo $page_title ?> | Pesa Pata Tujenge Portal&#x2122;</title>
		<link rel="stylesheet" type="text/css" href="css/style.css" />
		<link rel="stylesheet" type="text/css" href="css/demo_page.css" />
		<link rel="stylesheet" type="text/css" href="css/demo_table.css" />
		<script type="text/javascript" language="javascript" src="js/tables/jquery.js"></script>
		<script type="text/javascript" language="javascript" src="js/tables/jquery.dataTables.js"></script>
		<script type="text/javascript" src="j/jquery.min.js"></script>
		<script type="text/javascript" src="js/raphael.js"></script>
		<script type="text/javascript" src="js/jquery.enumerable.js"></script>
		<script type="text/javascript" src="js/jquery.tufte-graph.js"></script>
		<script type="text/javascript" src="https://www.google.com/jsapi"></script>

		<script src="jsprototype.js" type="text/javascript"></script>
		<script src="js/excanvas.js" type="text/javascript"></script>
		<script src="js/plotr.js" type="text/javascript"></script>
		<script src="js/gen_validatorv4.js" type="text/javascript"></script>
		<script type="text/javascript" charset="utf-8">
			$(document).ready(function() {
				$('#example').dataTable();
				$('#example2').dataTable();
				$('#example3').dataTable();
				$('#example4').dataTable();
				$('#example5').dataTable();
				$('#example6').dataTable();
				$('#example7').dataTable();
				$('#example8').dataTable();
				$('#example9').dataTable();
				$('#example10').dataTable();
				$('#tblExport').dataTable();
			} );
		</script>

		<link href="css/south-street/jquery-ui-1.10.4.custom.css" rel="stylesheet">
		<script src="j/jquery-1.8.3.js"></script>
		<script src="js/jquery-ui-1.9.2.custom.js"></script>
		<script>
		
		$(function() {
		
			$( "#accordion" ).accordion();
		

		
			var availableTags = [
				"ActionScript",
				"AppleScript",
				"Asp",
				"BASIC",
				"C",
				"C++",
				"Clojure",
				"COBOL",
				"ColdFusion",
				"Erlang",
				"Fortran",
				"Groovy",
				"Haskell",
				"Java",
				"JavaScript",
				"Lisp",
				"Perl",
				"PHP",
				"Python",
				"Ruby",
				"Scala",
				"Scheme"
			];
			$( "#autocomplete" ).autocomplete({
				source: availableTags
			});
		

		
			$( "#button" ).button();
			$( "#button2" ).button();
			$( "#radioset" ).buttonset();
		

		
			$( "#tabs" ).tabs();
		

		
			$( "#dialog" ).dialog({
				autoOpen: false,
				width: 400,
				buttons: [
					{
						text: "Ok",
						click: function() {
							$( this ).dialog( "close" );
						}
					},
					{
						text: "Cancel",
						click: function() {
							$( this ).dialog( "close" );
						}
					}
				]
			});

			// Link to open the dialog
			$( "#dialog-link" ).click(function( event ) {
				$( "#dialog" ).dialog( "open" );
				event.preventDefault();
			});
		

		
			$( "#datepicker" ).datepicker({
				inline: true
			});
			$( "#report_start_date" ).datepicker({
				inline: true
			});
			$( "#report_end_date" ).datepicker({
				inline: true
			});

			$( "#select_date" ).datepicker({
				inline: true
			});
			$( "#lease_end_date" ).datepicker({
				inline: true
			});

			$( "#trans_date" ).datepicker({
				inline: true
			});
			
			$( "#loan_date" ).datepicker({
				inline: true
			});
			
			$( "#payment_date" ).datepicker({
				inline: true
			});
			
			$( "#reversal_date" ).datepicker({
				inline: true
			});
			
			$( "#loan_expiry_date" ).datepicker({
				inline: true
			});
			
			$( "#loan_rep_date" ).datepicker({
				inline: true
			});
			
			$( "#user_req_date" ).datepicker({
				inline: true
			});
			
			$( "#susp_acc_date" ).datepicker({
				inline: true
			});
		
			$( "#slider" ).slider({
				range: true,
				values: [ 17, 67 ]
			});
		

		
			$( "#progressbar" ).progressbar({
				value: 20
			});
		

			// Hover states on the static widgets
			$( "#dialog-link, #icons li" ).hover(
				function() {
					$( this ).addClass( "ui-state-hover" );
				},
				function() {
					$( this ).removeClass( "ui-state-hover" );
				}
			);
		});
		</script>

	</head>
	<body onload="load()">
		<div id="wrapper">
			<div id="header">
				<div id="logo">
					<h1><a href="#"><img src="images/pesapatalogo.jpg" width="200px"></a></h1>
				</div>
				<?php
					//include_once('includes/menu.php');
					//include_once('includes/menu2.php');
				?>
			</div>
