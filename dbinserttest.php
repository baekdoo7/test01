#!/Applications/MAMP/bin/php/php7.1.0/bin/php
<?php 
/*
 *
 *
 * 
 * */

//#!/usr/bin/php
function get_time() {
	list($usec, $sec) = explode(" ", microtime());
	return ((float)$usec + (float)$sec);
}

//작업 시작 시간
$time01 = get_time();

//디비연결 
$conn=mysqli_connect("localhost","root","root","adop_test");
if(!$conn){
	echo "Debugging errno : ". mysqli_connect_error() .PHP_EOL;
	exit;
}

	$ikey   = 0;
	$imod2  = 0;
	$imod5  = 0;
	$imod13 = 0;
	$calpha = 0;
	$rowValue = "";
	$query = "";
	
	

	for ($i=1;$i<=1000000;$i+=10){	
		$ikey   = $i;
		$calpha = 'a';

	
			$rowValue .= "($ikey  ," . $ikey%2 . "," . $ikey%5 . "," . $ikey%13 . ",'$calpha'),";
			$rowValue .= "($ikey+1  ," . ($ikey+1)%2 . "," . ($ikey+1)%5 . "," . ($ikey+1)%13 . ",'$calpha'),";
			$rowValue .= "($ikey+2  ," . ($ikey+2)%2 . "," . ($ikey+2)%5 . "," . ($ikey+2)%13 . ",'$calpha'),";
			$rowValue .= "($ikey+3  ," . ($ikey+3)%2 . "," . ($ikey+3)%5 . "," . ($ikey+3)%13 . ",'$calpha'),";
			$rowValue .= "($ikey+4  ," . ($ikey+4)%2 . "," . ($ikey+4)%5 . "," . ($ikey+4)%13 . ",'$calpha'),";
			$rowValue .= "($ikey+5  ," . ($ikey+5)%2 . "," . ($ikey+5)%5 . "," . ($ikey+5)%13 . ",'$calpha'),";
			$rowValue .= "($ikey+6  ," . ($ikey+6)%2 . "," . ($ikey+6)%5 . "," . ($ikey+6)%13 . ",'$calpha'),";
			$rowValue .= "($ikey+7  ," . ($ikey+7)%2 . "," . ($ikey+7)%5 . "," . ($ikey+7)%13 . ",'$calpha'),";
			$rowValue .= "($ikey+8  ," . ($ikey+8)%2 . "," . ($ikey+8)%5 . "," . ($ikey+8)%13 . ",'$calpha'),";
			$rowValue .= "($ikey+9  ," . ($ikey+9)%2 . "," . ($ikey+9)%5 . "," . ($ikey+9)%13 . ",'$calpha')";			
			//$rowValue .= "($ikey  ," . $ikey%2 . "," . $ikey%5 . "," . $ikey%13 . ",'$calpha')";
			$query = "insert into testmysql values".$rowValue;

			mysqli_query($conn,$query);
			$rowValue = "";
	
		
	
		
	}
	
	

//열린 테이터베이스 클로즈
mysqli_close($conn);



echo  "[작업종료] : ".(get_time() - $time01)."초 ".PHP_EOL;
?>