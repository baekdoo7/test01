#!/usr/local/php-fpm/bin/php -q
<?php
/**
 * 실행주기  : 매일 새벽 1시
 * 작성자   : baekdoo
 * 작성일   : 2018. 7. 24.
 * 작업내용  : 미디에이션 자동화를 위하여 광고단위별 ECPM을 생성및 광고 딜리버리 캐싱화일 생성한다. 
 */



//초기 환경 설정 (메모리4기가 실행시간 1시간 타입존 utc)
require 'ecpm_collector.php';
require 'runAutoMediation.php';

error_reporting(E_ALL);
ini_set('memory_limit','4096M');
ini_set('max_execution_time', 60 * 60 * 3);
ini_set('display_errors', 1);
date_default_timezone_set('UTC');

$startTime = get_time();

$msgQ = "";

//한국시간세팅을 위한 설정
$year = date('Y', strtotime('+9 hours'));
$month = date('m', strtotime('+9 hours'));
$day = date('d', strtotime('+9 hours'));
$hour = date('H', strtotime('+9 hours'));

$yesterday = date('Ymd', strtotime('-15 hours'));
$daybeforeyesterday = date('Ymd', strtotime('-39 hours'));



//ECPM 처리를 위한 모듈 호출
$ret_ecpm = json_decode(start_ecpm_collector());
 
//결과값이 없으면 에러 코드 넣고 종료
if(!isset($ret_ecpm) || empty($ret_ecpm) ) {
    writelog(200,"error in ecpm module");
    exit("result of makeecpmdata is null!");
}


//ecpm 모듈 에러 리턴일 경우
if($ret_ecpm->code == 200){ //문제 생겼을 경우
    writelog(200,$ret_ecpm->msg);
    exit("receive error return from ecpm module");
}else{
    $msgQ = $msgQ.$ret_ecpm->msg;
}

//생성테이블 백업 및 예전 테이블 삭제
tablebackup();


//딜리버리 위한 캐싱 모듈 호출
$ret_adinfo = json_decode(runAutoMediation());

//결과값이 없으면 에러 코드 넣고 종료
if(!isset($ret_adinfo) || empty($ret_adinfo) ) {
    writelog(200,"error in make ad_info module");
    exit("error in make ad_info module!");
}

//딜리버리 모듈 에러 리턴일 경우
if($ret_adinfo->code == 200){ //문제 생겼을 경우
    writelog(200,$ret_adinfo->msg);
    exit("receive error return from adinfo module");
}else{
    $msgQ = "wtime:".round(get_time() - $startTime,2)." ".$msgQ;
    $msgQ = $msgQ." ".$ret_adinfo->msg;
}


//최종 메시지 저장 및 종료
writelog(100,$msgQ);

echo "\n정상적으로 처리 되었습니다.\n";




//ecpm 처리 모듈
function makeecpmdata(){

    (object)$ret = null;
    $ret->cd = 100;
    $ret->msg = "checked:100 ecpm:100";
    
   
    return $ret;
}

//딜리버리 캐싱 처리 모듈
function makeadinfo(){
    (object)$ret = null;
    $ret->cd = 100;
    $ret->msg = "adinfo:300 cache:400";
    
   
    return $ret;
}


//테이블 백업처리
function tablebackup(){
    
    global $yesterday,$daybeforeyesterday;  
    $renameTable = "advertise_ad_ecpm_".$yesterday;
    $deleteTable = "advertise_ad_ecpm_".$daybeforeyesterday;
    
    //DB연결
    $_compassDB = mysqli_connect("compass-cluster.cluster-cnzcxy7quzcs.ap-northeast-2.rds.amazonaws.com", "adopadmin", "Adop*^14", "platon");
    if ($_compassDB->connect_errno) {
        die('Platon DB Connect Error: ' . $_compassDB->connect_error);
    } 
    //$sqlQuery = "select 1";
    $sqlQuery =  "SELECT 1 FROM information_schema.tables WHERE TABLE_NAME like \"advertise_ad_ecpm_tmp\"";
    $RetQuery = mysqli_query($_compassDB,$sqlQuery);

    if($RetQuery->num_rows == 1){
        //기존테이블 리네임
        $sqlQuery =  "RENAME TABLE advertise_ad_ecpm TO ".$renameTable;
        mysqli_query($_compassDB,$sqlQuery);
        $sqlQuery =  "RENAME TABLE advertise_ad_ecpm_tmp TO advertise_ad_ecpm";
        mysqli_query($_compassDB,$sqlQuery);
        $sqlQuery =  "DROP TABLE ".$deleteTable;
        mysqli_query($_compassDB,$sqlQuery);
        
    }else{
        //테이블 에러 처리
        $_compassDB->close();
        writelog(200,"none of ECPM Table");
        exit("none of ECPM Table!!!");
    }
    
    $_compassDB->close();

}
 
//로그기록
function writelog($cd,$msg){
    //DB연결
    $_compassDB = mysqli_connect("compass-cluster.cluster-cnzcxy7quzcs.ap-northeast-2.rds.amazonaws.com", "adopadmin", "Adop*^14", "platon");
    if ($_compassDB->connect_errno) {
        die('Platon DB Connect Error: ' . $_compassDB->connect_error);
    }  
    mysqli_query($_compassDB,"set names utf8");
    $sqlQuery =  "insert into worklog(log_num,log_type,msg) value(102,".$cd.",'$msg')";
    mysqli_query($_compassDB,$sqlQuery);
    $_compassDB->close();
}

//수행시간을 체크하기 위한 시간 리턴함수
function get_time() {
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}

?>