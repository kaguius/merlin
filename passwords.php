<?php
	$r1='/[A-Z]{1}/';  //Uppercase
	$r2='/[a-z]{1}/';  //lowercase
	$r3='/[!@#$%^&*()_=+{};:,<.>-]{1}/';  // whatever you mean by 'special char'
	$r4='/[0-9]{1}/';  //numbers

	$found = array();
	
	$count = 0;

	foreach (array('48Kaguius%', '48Kaguius%', '48Kaguius%') as $pass) {

		if (!preg_match_all($r4, $pass, $found)) {
			$count = $count - 1;
		} else {
			$count = $count + 1;
		}

		if (!preg_match_all($r2,$pass, $found)) {
			$count = $count - 1;
		} else {
			$count = $count + 1;
		}

		if (!preg_match_all($r1, $pass, $found)) {
			$count = $count - 1;
		} else {
			$count = $count + 1;
		}
		
		if (!preg_match_all($r3, $pass, $found)) {
			$count = $count - 1;
		} else {
			$count = $count + 1;
		}
	}
	
	echo $count."<br />";
?>