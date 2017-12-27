#!/usr/local/php-fpm/bin/php
<?php 
//ini_set('max_execution_time',300);
//ini_set("memory_limit",-1);
// sudo mount -t tmpfs -o size=1024M,nr_inodes=10k,mode=1777 tmpfs ttest

function get_time() { 
    list($usec, $sec) = explode(' ', microtime());
    return ((float)$usec + (float)$sec);
}

$ikey =0;
$start = get_time();


$host     = "localhost:3306";
$user     = "root";
$password = "root";
$dbname   = "test";

// MySQL 데이터베이스 연결
$mysqli = new mysqli($host, $user, $password, $dbname);

// 연결 오류 발생 시 스크립트 종료
if ($mysqli->connect_errno) {
    die('Connect Error: '.$mysqli->connect_error);
}

//랜덤문자 하나를 찍기위한 배열
$chars=array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z');

//화일로 찍기위해 오픈 
$fp1 = fopen('itemfeed3.txt', 'w') or die('Unable to open file!');
$wheretmp = '';
$saveData = '';
for($i=1;$i <= 10000000 ;$i++){
//foreach (range(1,10000000) as $i){
	$ikey = $i;
	$imod2 = $i%2;
	$imod5 = $i%5;
	$imod13 = $i%13;
	$calpha = $chars[mt_rand(0,25)];
	if($i%1000000 == 0){
		$saveData .= $ikey."\t".$imod2."\t".$imod5."\t".$imod13."\t".$calpha."\n";
		fwrite($fp1, $saveData);
		$saveData = "";
	}else{
		$saveData .= $ikey."\t".$imod2."\t".$imod5."\t".$imod13."\t".$calpha."\n";
	}
}
//화일클로즈
fclose($fp1);

//파일로 읽어서 디비 인서트 
$sql = "LOAD DATA LOCAL INFILE 'itemfeed3.txt' REPLACE INTO TABLE testmysql FIELDS TERMINATED BY '\t' ";
$mysqli->real_query($sql);

//화일삭제
unlink('itemfeed3.txt');

// 접속 종료
$mysqli->close();

$end = get_time();
$time = $end - $start;

echo "$ikey<br />\n";
echo '<br/>'.$time.'초 걸림';
?>
          	 