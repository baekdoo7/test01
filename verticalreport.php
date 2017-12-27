#!/Applications/MAMP/bin/php/php7.1.0/bin/php
<?php 
/*
 * 1. search_date 날짜를 입력 받고 없으면 오늘날짜로 utc / pacific / kor 날짜 생성
 * 2. platon_log.compass_log_live 한달(35일)전 데이타 삭제 
 * 3. platon_log.compass_req_log_live -> platon_log2.compass_req_log_live (전날 데이타)
 * 4. platon_log.compass_req_log_live 한달(35일)전 데이타 삭제 
 * 5. platon_log 에 advertise_ad/inventory_area/i_network_info 복제 
 * 
 * */

$adInfo = array();
$adInfo['Critoe']['area'] = "";
$adInfo['Critoe']['area'] = "";
 		

//#!/usr/bin/php
function get_time() {
	list($usec, $sec) = explode(" ", microtime());
	return ((float)$usec + (float)$sec);
}

function getTimezone($date = ""){

	if($date == ""){
		return null;
	}
	$timeZone['date']   = date("Y-m-d",strtotime($date));
	$timeZone['utc']['startDate'] = $timeZone['date']." 00:00:00";
	$timeZone['utc']['endDate']   = $timeZone['date']." 23:59:59";
	
	$timeZone['kor']['startDate'] = date("Y-m-d",strtotime($searchDate - 1))." 15:00:00";
	$timeZone['kor']['endDate']   = date("Y-m-d",strtotime($searchDate))." 14:59:59";
	
	$timeZone['pst']['startDate'] = date("Y-m-d",strtotime($searchDate - 1))." 08:00:00";
	$timeZone['pst']['endDate']   = date("Y-m-d",strtotime($searchDate))." 07:59:59";
	
	return  $timeZone;
}
//작업 시작 시간
$time01 = get_time();

//조회 날짜를 입력 받음
$searchDate = $_GET['sdate'];
$searchDate = $argv[1];

//전날 날자 생성
//2017-01-17 12:00:00
 $timeZone = getTimezone($searchDate);


//echo $startDate."-".$endDate."-".$deleteDate."\n";

echo $searchDate."test \n";
print_r($timeZone);

?>