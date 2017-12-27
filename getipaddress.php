<?php
/*
// www.krnic.or.kr/jsp/infoboard/stats/interProCurrentXml.jsp 에서
// 아이피정보를 받아 아이피를 국내 IP 생성
//http://www.ipwatch.co.kr/ip/?ip=210.180.216.195
*/

ob_start();
//메모리사이즈와 실행시간 프리...
ini_set("max_execution_time",3000);
ini_set("memory_limit",-1);

/*전역변수 정의*/
$dbInfo     = array("localhost","root","root","adop_test"); //로컬디비
//$dbInfo     = array("52.78.191.196","root","root","study_3_10");
$krnicUrl   = "http://www.krnic.or.kr/jsp/infoboard/stats/interProCurrentXml.jsp"; //kr닉 주소
$startTime  = get_time(); //시작시간
$fileInfo   = "tmp/krnicIp.txt";
$workingIP  = "";


/*함수 정리*/
//시간 처리를 위한 함수
function get_time() {
	list($usec, $sec) = explode(" ", microtime());
	return ((float)$usec + (float)$sec);
}

//최초 DB생성
function makeIpdata(){
    global $dbInfo;
    
    list($hostUrl,$user,$pwd,$dbIns) = $dbInfo;
	$conn=mysqli_connect($hostUrl,$user,$pwd,$dbIns);
	if(!$conn){
		echo "Debugging errno : ". mysqli_connect_error() .PHP_EOL;
		exit();
	}
	$conn->set_charset("utf8");
	
	//테이블 없으면 신규 생성
	$createDicTableSql  = "CREATE TABLE IF NOT EXISTS `krnicIpdata` ( ";
	$createDicTableSql .= "`ip` varchar(50) NOT NULL,";
    $createDicTableSql .= "`agency` varchar(50) NOT NULL,";
    $createDicTableSql .= "`address` varchar(50) NOT NULL,";
    $createDicTableSql .= "`zipcode` varchar(50) NOT NULL,";
    $createDicTableSql .= "`chk` char(1) NOT NULL,";
	$createDicTableSql .= "PRIMARY KEY (`ip`)";
	$createDicTableSql .= ") ENGINE=InnoDB DEFAULT CHARSET=utf8;";
	$createDicTableSql .= "";
	
	mysqli_query($conn, $createDicTableSql);
    
    mysqli_close($conn); //db 종료	
}
//화일읽어 오기
function readXml($url){ 

  if (function_exists('curl_init')) {
   // curl 리소스를 초기화
   $ch = curl_init(); 

   // url을 설정
   curl_setopt($ch, CURLOPT_URL, $url); 

   // 헤더는 제외하고 content 만 받음
   curl_setopt($ch, CURLOPT_HEADER, 0); 

   // 응답 값을 브라우저에 표시하지 말고 값을 리턴
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 

   // 브라우저처럼 보이기 위해 user agent 사용
   curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.7.5) Gecko/20041107 Firefox/1.0'); 

   $content = curl_exec($ch); 

   // 리소스 해제를 위해 세션 연결 닫음
   curl_close($ch);
      
   //xml 파싱하기
  
   return $content;    
} else {
   // curl 라이브러리가 설치 되지 않음. 다른 방법 알아볼 것
    echo "error";
    exit("curl not exist!");  
}  
    
} 
//토르 이용 화일읽어 오기
function readTor($url){ 
  if (function_exists('curl_init')) {
   //아이피변경
 
      
      
   // curl 리소스를 초기화
   $ch = curl_init(); 

   // url을 설정
   curl_setopt($ch, CURLOPT_URL, $url); 

   // 헤더는 제외하고 content 만 받음
   curl_setopt($ch, CURLOPT_HEADER, 0); 

   // 응답 값을 브라우저에 표시하지 말고 값을 리턴
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 

   // 브라우저처럼 보이기 위해 user agent 사용
   curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.7.5) Gecko/20041107 Firefox/1.0'); 

   //토르를 이용하게 세팅
   curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
   curl_setopt($ch, CURLOPT_PROXY, "socks5://127.0.0.1:9150");      
      
   $content = curl_exec($ch); 

   // 리소스 해제를 위해 세션 연결 닫음
   curl_close($ch);
      
   //xml 파싱하기
  
   return $content;    
  } else {
   // curl 라이브러리가 설치 되지 않음. 다른 방법 알아볼 것
    echo "error";
    exit("curl not exist!");  
  }      
} 
//파일읽어 파싱하기
function startParsing(){
    global $krnicUrl,$fileInfo;    
    $cnt = 0;
    
	//화일로 찍기위해 오픈
	$fp1 = fopen($fileInfo, "w") or die("Unable to open file!");
    
    $xml = simplexml_load_string(readXml($krnicUrl));
    //print_r($xml->ipv4[1]);
    foreach($xml->ipv4 as $obj){
        //echo $obj->sno."<br />\n";
        $startIp = explode(".",$obj->sno);
        $endIp   = explode(".",$obj->eno);
        for($i=$startIp[1];$i <= $endIp[1];$i++){
            for($j=$startIp[2];$j <= $endIp[2];$j++){
                //echo "$startIp[0]".".".$i.".".$j."<br />\n";
                fwrite($fp1, "$startIp[0]".".".$i.".".$j."\n");            
                $cnt++;
                if($cnt%1000 == 0){
                    echo $cnt."<br />\n";
                    ob_flush();
                    flush();                    
                }
            }
        }
        
        
    }
    
    //화일클로즈
	fclose($fp1);    
}
//파일에서 DB로 화일 업로드
function fileToDb(){
	global $dbInfo,$fileInfo;
	list($hostUrl,$user,$pwd,$dbIns) = $dbInfo;
	$conn=mysqli_connect($hostUrl,$user,$pwd,$dbIns);
	if(!$conn){
		echo "Debugging errno : ". mysqli_connect_error() .PHP_EOL;
		exit();
	}
	$conn->set_charset("utf8");
	

	// csv 단위 로딩...
	$sql = "LOAD DATA LOCAL INFILE '$fileInfo' REPLACE INTO TABLE `krnicIpdata` FIELDS TERMINATED BY '\t' (ip);";
	mysqli_query($conn, $sql);
	
	mysqli_close($conn); //db 종료	    
}

//디비에서 아이피 가져와서 주소값 넣기처리
function setAddress(){
    global $dbInfo,$workingIP;
    $searchIP = "";
    $agency   = "";
    $address  = "";
    $zipcode  = "";
    $range    = "";
    list($hostUrl,$user,$pwd,$dbIns) = $dbInfo;
	$conn=mysqli_connect($hostUrl,$user,$pwd,$dbIns);
	if(!$conn){
		echo "Debugging errno : ". mysqli_connect_error() .PHP_EOL;
		exit();
	}
	$conn->set_charset("utf8");
    
    //하나의 데이터 가져오기
    //$itemQuery = "select * from `krnicIpdata` where address = '' order by RAND() limit 1";
    $itemQuery = "select * from `krnicIpdata` where chk <> 'Y' order by RAND() limit 1";
    $resultSet     = mysqli_query($conn,$itemQuery);
	$row = mysqli_fetch_row($resultSet);
    $searchIP = $row[0];
    $workingIP = $row[0];
    
    //$contents = readTor("http://www.ipwatch.co.kr/ip/?ip=".$searchIP.".".mt_rand(1,255));
    $contents = readTor("http://whois.kisa.or.kr/openapi/whois.jsp?query=".$searchIP.".".mt_rand(1,255)."&key=2013031411240702592637&answer=json");
    $jsonTmp = json_decode($contents); 
    //print_r($jsonTmp->whois->korean->user->netinfo);
    if(isset($jsonTmp->whois->korean->user)){
        $agency  = $jsonTmp->whois->korean->user->netinfo->orgName;
        $address = $jsonTmp->whois->korean->user->netinfo->addr;
        $zipcode = $jsonTmp->whois->korean->user->netinfo->zipCode;
        $range   = $jsonTmp->whois->korean->user->netinfo->range;        
    }
    elseif(isset($jsonTmp->whois->korean->ISP)){
        $agency  = $jsonTmp->whois->korean->ISP->netinfo->orgName;
        $address = $jsonTmp->whois->korean->ISP->netinfo->addr;
        $zipcode = $jsonTmp->whois->korean->ISP->netinfo->zipCode;
        $range   = $jsonTmp->whois->korean->ISP->netinfo->range;                    
    }    
    else{
            $updateQuery = "update krnicIpdata set chk = 'Y' where ip = '$searchIP' ";
            //echo $updateQuery."<br />\n";
            mysqli_query($conn,$updateQuery);          
    }

    /*
    preg_match("/기관:(.*?)→ <a/i",$contents,$tmp);
    $agency = trim($tmp[1]);
    preg_match("/주소:(.*?)→ <a/i",$contents,$tmp);
    $address = trim($tmp[1]);
    preg_match("/우편번호:(.*?)<li>/i",$contents,$tmp);
    $zipcode = trim($tmp[1]);
    preg_match("/대역:(.*?)<li>/i",$contents,$tmp);
    $range = trim($tmp[1]);
    */
//echo $agency."/".$address."/".$zipcode."/".$range;    
    if($agency != "" && $address != "" && $zipcode != "" && $range != ""){
       $addressTmp = explode("-",$range);
       $address01  = explode(".",trim($addressTmp[0]));    
       $address02  = explode(".",trim($addressTmp[1]));        
       for($i=(int)$address01[1];$i<=(int)$address02[1];$i++){
           if($i == (int)$address01[1]){
               if($i == (int)$address02[1]){
                    for($j=(int)$address01[2];$j <= (int)$address02[2]; $j++ ){ 
                        $updateQuery = "update krnicIpdata set agency = '$agency',address = '$address',zipcode = '$zipcode',chk='Y' where ip = '".(int)$address01[0].".$i.$j' ";
                        //echo $updateQuery."<br />\n";
                        mysqli_query($conn,$updateQuery);
                    }    
                   
               }
               else{
                    for($j=(int)$address01[2];$j <= 255; $j++ ){ 
                        $updateQuery = "update krnicIpdata set agency = '$agency',address = '$address',zipcode = '$zipcode',chk='Y' where ip = '".(int)$address01[0].".$i.$j' ";
                        //echo $updateQuery."<br />\n";
                        mysqli_query($conn,$updateQuery);
                   
                    }
               }
           }
           else{
               if($i == (int)$address02[1]){
                    for($j=0;$j <= (int)$address02[2]; $j++ ){ 
                        $updateQuery = "update krnicIpdata set agency = '$agency',address = '$address',zipcode = '$zipcode',chk='Y' where ip = '".(int)$address01[0].".$i.$j' ";
                        //echo $updateQuery."<br />\n";
                        mysqli_query($conn,$updateQuery);
                    }    
                   
               }
               else{
                    for($j=0;$j <= 255; $j++ ){ 
                        $updateQuery = "update krnicIpdata set agency = '$agency',address = '$address',zipcode = '$zipcode',chk='Y' where ip = '".(int)$address01[0].".$i.$j' ";
                        //echo $updateQuery."<br />\n";
                        mysqli_query($conn,$updateQuery);
                   
                    }
               }
               
           }
       
       }    
    }
    //echo "$agency/$address/$zipcode/$range";
    
    
    
    mysqli_close($conn); //db 종료    
    
}

/*프로그램 시작*/


//makeIpdata(); //테이블 생성
//startParsing(); //kr닉에서 아이피주소 읽어서 화일로 생성
//fileToDb(); // 화일에서 디비로 올리기
for($i=0;$i<5000;$i++){
    setAddress();
    echo $i."($workingIP)<br />\n";
    ob_flush();
    flush();
    
}


//$test = preg_match("/123(.*)213/i",'123213123동해물과 백두산이 2134123',$tmp);
//print_r($test);
//echo readTor("http://ipinfo.io");

//echo readTor("http://www.ipwatch.co.kr/ip/?ip=210.180.129.195");

//http://whois.kisa.or.kr/openapi/whois.jsp?query=210.180.216.195&key=2013031411240702592637
//http://whois.kisa.or.kr/openapi/whois.jsp?query=210.180.215.195&key=2013031411240702592637&answer=json
$worktime = round(get_time() - $startTime,3);
echo "<br />\n";
echo "[처리시간]: ".$worktime." 초";
?>