#!/usr/local/php-fpm/bin/php
<?php 
ini_set("max_execution_time",300);
ini_set("memory_limit",-1);


function get_time() { 
    list($usec, $sec) = explode(" ", microtime());
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

//파일로 읽어서 디비 인서트 
$sql = "LOAD DATA LOCAL INFILE 'itemfeed3.txt' REPLACE INTO TABLE testmysql FIELDS TERMINATED BY '\t' ";
$mysqli->real_query($sql);

//화일삭제
//unlink("itemfeed3.txt");

// 접속 종료
$mysqli->close();

$end = get_time();
$time = $end - $start;

echo "$ikey<br />\n";
echo '<br/>'.$time.'초 걸림';
?>
          	 