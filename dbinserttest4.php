#!/usr/local/php-fpm/bin/php
<?php 
//ini_set("max_execution_time",300);
//ini_set("memory_limit",-1);


function get_time() { 
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}

$ikey =0;
$start = get_time();


// MySQL 데이터베이스 연결
$mysqli = new mysqli('localhost:3306', 'root', 'root', 'test');

// 연결 오류 발생 시 스크립트 종료
//if ($mysqli->connect_errno) {
//   die('Connect Error: '.$mysqli->connect_error);
//}

//랜덤문자 하나를 찍기위한 배열
$chars=array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z');


$wheretmp = '';
$saveData = '';
$imod2  = 0;

//$info = array();
for($i=1;$i <= 10000000 ;$i++){
	//fwrite($fp1, $i."\t". $i%2 ."\t". $i%5 ."\t". $i%13 ."\t". $chars[mt_rand(0,25)] ."\n");
	$ikey = $i;
	//$imod2 = $i%2;
	$imod2=$imod2==0?1:0;
	$imod5  = $ikey%5;
	$imod13 = $ikey%13;
	$calpha = $chars[mt_rand(0,25)];

	
	//화일로 처리
	if($i%1000000 == 0){
		$saveData .= $imod2."\t".$imod5."\t".$imod13."\t".$calpha."\n";
		//$saveData .= $ikey."\t".$calpha."\n";
		file_put_contents("itemfeed3.txt", $saveData,FILE_APPEND);
		$saveData = "";
	}else{
		//$saveData .= $ikey."\t".$calpha."\n";
		$saveData .= $imod2."\t".$imod5."\t".$imod13."\t".$calpha."\n";
	}
	


}

//파일로 읽어서 디비 인서트 
$sql = "LOAD DATA LOCAL INFILE 'itemfeed3.txt' REPLACE INTO TABLE testmysql9 FIELDS TERMINATED BY '\t' (imod2,imod5,imod13,calpha)";
$mysqli->real_query($sql);

//화일삭제
unlink('itemfeed3.txt');

// 접속 종료
$mysqli->close();

$end = get_time();
$time = $end - $start;

//echo "$ikey<br />\n";
echo '<br/>'.$time.'초 걸림';
?>
          	 