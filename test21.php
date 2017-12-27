<?php

ini_set("max_execution_time",3000);
ini_set("memory_limit",-1);
$startTime = get_time();

//디비정보
$dbInfo     = array("localhost","root","root","adop_test");
$dbInfo2    = array("compassread.cnzcxy7quzcs.ap-northeast-2.rds.amazonaws.com","adopadmin","Adop*^14","platon");
//$dbInfo2    = array("localhost","root","root","adop_test");
//$dbInfo2    = array("52.78.191.196","root","root","study_3_10");
//$dbInfo     = array("52.78.191.196","root","root","study_3_10");

//전역변수
$iplist = array();

/*함수정리*/
//시간 처리를 위한 함수
function get_time() {
	list($usec, $sec) = explode(" ", microtime());
	return ((float)$usec + (float)$sec);
}


function mov_krip(){
    global $dbInfo,$dbInfo2,$iplist;
	list($hostUrl,$user,$pwd,$dbIns) = $dbInfo2;
	$conn=mysqli_connect($hostUrl,$user,$pwd,$dbIns);
	if(!$conn){
		echo "Debugging errno : ". mysqli_connect_error() .PHP_EOL;
		exit();
	}
	$conn->set_charset("utf8");
    
    list($hostUrl2,$user2,$pwd2,$dbIns2) = $dbInfo;
	$conn2=mysqli_connect($hostUrl2,$user2,$pwd2,$dbIns2);
	if(!$conn2){
		echo "Debugging errno : ". mysqli_connect_error() .PHP_EOL;
		exit();
	}
	$conn2->set_charset("utf8");
    
    //$readDataQuery =  "select * from `log_info` where time > '2017-03-15' limit 10 " ;
    $readDataQuery =  "select ip from `ip_info` " ;
	$resultSet = mysqli_query($conn,$readDataQuery);
    
    while($row = mysqli_fetch_row($resultSet)){
        $iplist[] = $row[0];
        //echo $row[0]."<br />\n";
        
    }
    
    $readDataQuery =  "select * from `log_info` where time >= '2017-04-17' " ;
    $resultSet = mysqli_query($conn,$readDataQuery);
    while($row = mysqli_fetch_row($resultSet)){
        $ipTmp = $row[1];
        $ipTmp1 = explode(".",$ipTmp);
        $ipTmp2 = $ipTmp1[0].".".$ipTmp1[1].".".$ipTmp1[2];
        
        if(in_array($ipTmp2,$iplist)){
                $insertQuery = "INSERT INTO `log_info` (time,ip,user_agent,area,area_name,origin_area,call_area,is_vh,loc) ";
	            $insertQuery .= "VALUES ('".$row[0]."','".$row[1]."','".$row[2]."','".$row[3]."','".$row[4]."','".$row[5]."','".$row[6]."','".$row[7]."','".$row[8]."') ";
	            $insertQuery .= "ON DUPLICATE KEY UPDATE time='".$row[0]."',ip='".$row[1]."',user_agent='".$row[2]."',area='".$row[3]."',area_name='".$row[4]."',origin_area='".$row[5]."',call_area='".$row[6]."',is_vh='".$row[7]."',loc='".$row[8]."'";

	           mysqli_query($conn2, $insertQuery);

        }
        
        
    }
    
    mysqli_close($conn2); //db 종료	
    mysqli_close($conn); //db 종료	
    
}

/*프로그램시작*/
    mov_krip();

$time = get_time() - $startTime;


echo '<br/>'.$time.'초 걸림';

?>