#!/Applications/MAMP/bin/php/php7.1.0/bin/php
<?php 
/*
 * 1. platon_log.compass_log_live -> platon_log2.compass_log_live (전날 데이타) 
 * 2. platon_log.compass_log_live 한달(35일)전 데이타 삭제 
 * 3. platon_log.compass_req_log_live -> platon_log2.compass_req_log_live (전날 데이타)
 * 4. platon_log.compass_req_log_live 한달(35일)전 데이타 삭제 
 * 5. platon_log 에 advertise_ad/inventory_area/i_network_info 복제 
 * 
 * */

//#!/usr/bin/php
function get_time() {
	list($usec, $sec) = explode(" ", microtime());
	return ((float)$usec + (float)$sec);
}

//작업 시작 시간
$time01 = get_time();

//전날 날자 생성
//2017-01-17 12:00:00
$today = date("Y-m-d");
$endDate = date("Y-m-d",strtotime("-2 day"))." 17:00:00";
$startDate = date("Y-m-d",strtotime("-3 day"))." 18:00:00";
$deleteDate = date("Y-m-d",strtotime("-365 day"))." 14:00:00";

//echo $startDate."-".$endDate."-".$deleteDate."\n";

//DB 설정(platon_log/platon_log2/compassreal/insightDB)
//최근 35일치 데이터 저장 DB 정보 
$conn_platon_log=mysqli_connect("compasslog.cnzcxy7quzcs.ap-northeast-2.rds.amazonaws.com","adopadmin","Adop*^14","platon_log");
if(!$conn_platon_log){
	echo "Debugging errno : ". mysqli_connect_error() .PHP_EOL;
	exit;
}
$conn_platon_log->set_charset("utf8");

//풀데이타 저장 데이터 저장 DB 정보 
$conn_platon_log2=mysqli_connect("compass-log-all-cluster.cluster-cnzcxy7quzcs.ap-northeast-2.rds.amazonaws.com","adopadmin","Adop*^14","platon_log");
if(!$conn_platon_log2){
	echo "Debugging errno : ". mysqli_connect_error() .PHP_EOL;
	exit;
}
$conn_platon_log2->set_charset("utf8");

//콤파스 영역및 광고 DB 정보
$conn_platon=mysqli_connect("compassread.cnzcxy7quzcs.ap-northeast-2.rds.amazonaws.com","adopadmin","Adop*^14","platon");
if(!$conn_platon){
	echo "Debugging errno : ". mysqli_connect_error() .PHP_EOL;
	exit;
}
$conn_platon->set_charset("utf8");

//인사이트 네트워크 DB 정보
$conn_insight=mysqli_connect("insight-cluster-1.cluster-cnzcxy7quzcs.ap-northeast-2.rds.amazonaws.com","adopadmin","Adop*^14","insight");
if(!$conn_insight){
	echo "Debugging errno : ". mysqli_connect_error() .PHP_EOL;
	exit;
}
$conn_insight->set_charset("utf8");


$readDataQuery =  "select * from `compass_log_live` where search_date >= '".$startDate."' and search_date <= '".$endDate."' limit 10";
$resultSet = mysqli_query($conn_platon_log,$readDataQuery);


//데이터 복사(compass_log_live) 
while($row = mysqli_fetch_row($resultSet)){
	$insertQuery = "INSERT INTO `compass_log_live` (idx,area_idx,adv_idx,com_idx,search_date,impr,click,res,reg_date) ";
	$insertQuery .= "VALUES ('".$row[0]."','".$row[1]."','".$row[2]."','".$row[3]."','".$row[4]."','".$row[5]."','".$row[6]."','".$row[7]."','".$row[8]."') ";
	$insertQuery .= "ON DUPLICATE KEY UPDATE impr = '".$row[5]."',click='".$row[6]."',res='".$row[7]."',reg_date='".$row[8]."'";

	mysqli_query($conn_platon_log2, $insertQuery);
	
	//print_r($row);
	//echo "\n";
	
}


$readDataQuery =  "select * from `compass_req_log_live` where search_date >= '".$startDate."' and search_date <= '".$endDate."' limit 10";
$resultSet = mysqli_query($conn_platon_log,$readDataQuery);

//데이터 복사(compass_req_log_live)
while($row = mysqli_fetch_row($resultSet)){
	$insertQuery = "INSERT INTO `compass_req_log_live` (idx,area_idx,com_idx,search_date,req,reg_date) ";
	$insertQuery .= "VALUES ('".$row[0]."','".$row[1]."','".$row[2]."','".$row[3]."','".$row[4]."','".$row[5]."') ";
	$insertQuery .= "ON DUPLICATE KEY UPDATE req = '".$row[4]."',reg_date='".$row[5]."'";

	mysqli_query($conn_platon_log2, $insertQuery);

	//print_r($row);
	//echo "\n";

}


//advertise_ad 복사 
$readDataQuery =  "select adv_idx,cmp_idx,gp_idx,com_idx,adv_name,network_adv_idx,reg_date,weight_direction from `advertise_ad` where reg_date >= '".$startDate."'";
//$readDataQuery =  "select adv_idx,cmp_idx,gp_idx,com_idx,adv_name,network_adv_idx,reg_date,weight_direction from `advertise_ad` ";
$resultSet = mysqli_query($conn_platon,$readDataQuery);

//데이터 복사(advertise_ad)
while($row = mysqli_fetch_row($resultSet)){
	$insertQuery = "INSERT INTO `advertise_ad` (adv_idx,cmp_idx,gp_idx,com_idx,adv_name,network_adv_idx,reg_date,weight_direction) ";
	$insertQuery .= "VALUES ('".$row[0]."','".$row[1]."','".$row[2]."','".$row[3]."','".$row[4]."','".$row[5]."','".$row[6]."','".$row[7]."') ";
	$insertQuery .= "ON DUPLICATE KEY UPDATE cmp_idx='".$row[1]."',gp_idx='".$row[2]."',com_idx='".$row[3]."',adv_name='".$row[4]."',network_adv_idx='".$row[5]."',reg_date='".$row[6]."',weight_direction='".$row[7]."'";

	mysqli_query($conn_platon_log2, $insertQuery);
	mysqli_query($conn_platon_log, $insertQuery);
    
}

//inventory_area 복사
$readDataQuery =  "select area_idx,site_idx,section_idx,area_name,com_idx,reg_date,modi_date,del_yn,area_type,area_no,weight_direction,origin_areacd from `inventory_area` where modi_date >= '".$startDate."'";
//$readDataQuery =  "select area_idx,site_idx,section_idx,area_name,com_idx,reg_date,modi_date,del_yn,area_type,area_no,weight_direction,origin_areacd from `inventory_area` ";
$resultSet = mysqli_query($conn_platon,$readDataQuery);

//데이터 복사(inventory_area)
while($row = mysqli_fetch_row($resultSet)){
	$insertQuery = "INSERT INTO `inventory_area` (area_idx,site_idx,section_idx,area_name,com_idx,reg_date,modi_date,del_yn,area_type,area_no,weight_direction,origin_areacd) ";
	$insertQuery .= "VALUES ('".$row[0]."','".$row[1]."','".$row[2]."','".$row[3]."','".$row[4]."','".$row[5]."','".$row[6]."','".$row[7]."','".$row[8]."','".$row[9]."','".$row[10]."','".$row[11]."') ";
	$insertQuery .= "ON DUPLICATE KEY UPDATE site_idx='".$row[1]."',section_idx='".$row[2]."',area_name='".$row[3]."',com_idx='".$row[4]."',reg_date='".$row[5]."',modi_date='".$row[6]."',del_yn='".$row[7]."',area_type='".$row[8]."',area_no='".$row[9]."',weight_direction='".$row[10]."',origin_areacd='".$row[11]."'";

	mysqli_query($conn_platon_log2, $insertQuery);
	mysqli_query($conn_platon_log, $insertQuery);

}

//i_network_info 복사
$readDataQuery =  "select net_idx,net_nm,net_site_nm,compass_net_idx,compass_use_yn,compass_sort_no from `i_network_info`";
$resultSet = mysqli_query($conn_insight,$readDataQuery);

//데이터 복사(i_network_info)
while($row = mysqli_fetch_row($resultSet)){
	$insertQuery = "INSERT INTO `i_network_info` (net_idx,net_nm,net_site_nm,compass_net_idx,compass_use_yn,compass_sort_no) ";
	$insertQuery .= "VALUES ('".$row[0]."','".$row[1]."','".$row[2]."','".$row[3]."','".$row[4]."','".$row[5]."') ";
	$insertQuery .= "ON DUPLICATE KEY UPDATE net_nm='".$row[1]."',net_site_nm='".$row[2]."',compass_net_idx='".$row[3]."',compass_use_yn='".$row[4]."',compass_sort_no='".$row[5]."'";

	mysqli_query($conn_platon_log2, $insertQuery);
	mysqli_query($conn_platon_log, $insertQuery);

}

//한달전(35일)데이타 삭제 
$deleteDataQuery = "delete from `compass_req_log_live` where search_date <= '".$deleteDate."'";
mysqli_query($conn_platon_log, $deleteDataQuery);
$deleteDataQuery = "delete from `compass_log_live` where search_date <= '".$deleteDate."'";
mysqli_query($conn_platon_log, $deleteDataQuery);



//열린 테이터베이스 클로즈 
mysqli_close($conn_insight);
mysqli_close($conn_platon);
mysqli_close($conn_platon_log2);
mysqli_close($conn_platon_log); 

//echo $readDataQuery."\n";

echo  "[".$today."]".(get_time() - $time01)."초  well done!".PHP_EOL;
//echo $deleteDataQuery.PHP_EOL;
?>