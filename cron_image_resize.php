<?php
	//update customer_id and customer_station for loan_repayments
	include_once('includes/db_conn.php');
	
	//$source_image = imagecreatefromjpeg("uploads/image_to_resize.jpg");
	$src = ImageCreateFromJPEG('uploads/image_to_resize.jpg');
	$width = ImageSx($src);
	$height = ImageSy($src);
	$x = $width/2; $y = $height/2;
	$dst = ImageCreateTrueColor($x,$y);
	ImageCopyResampled($dst,$src,0,0,0,0,$x,$y,$width,$height);
	//echo $x."<br />";
	header('Content-Type: image/png');
	ImagePNG($dst);
?>