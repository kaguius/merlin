
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="description" content="" />
		<meta name="keywords" content="" />
		<title><?php echo $page_title ?> | Afb Loan Management Portal&#x2122;</title>
		<link href='http://fonts.googleapis.com/css?family=Oxygen' rel='stylesheet' type='text/css'>
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
		<script src="http://maps.google.com/maps/api/js?sensor=false" type="text/javascript"></script>
		<script>
		
		$(function() {
		
			$( "#accordion" ).accordion();
	
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
			
			$( "#date_of_birth" ).datepicker({
				inline: true
			});
			
			$( "#home_occupy" ).datepicker({
				inline: true
			});
			
			$( "#trading_product" ).datepicker({
				inline: true
			});
			
			$( "#trading_location" ).datepicker({
				inline: true
			});
			
			$( "#report_start_date" ).datepicker({
				inline: true
			});
			
			$( "#report_end_date" ).datepicker({
				inline: true
			});
			
			$( "#loan_due_date" ).datepicker({
				inline: true
			});
			
			$( "#report_end_date_ind" ).datepicker({
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
					<h1><a href="http://www.4g-capital.biz"><img src="images/4gcapital.png" width="160px"></a></h1>
				</div>
				<?php
					include_once('includes/menu.php');
					//include_once('includes/menu2.php');
				?>
			</div>
