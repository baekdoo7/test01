<?php
/*
 * 풀텍스트 서칭 테스트
 * 
 * */
ob_start();
//메모리사이즈와 실행시간 프리...
ini_set("max_execution_time",3000);
ini_set("memory_limit",-1);


//ob_start();

//전역변수 설정
$dbInfo     = array("localhost","root","root","adop_test");
//$dbInfo     = array("52.78.191.196","root","root","study_3_10");
$fileInfo   = "tmp/keyDic.txt";
$exptKey    = array("작성","최종수정","기사입력","news","contents","table","border","collapse","프린트","00","inline","display","span","0px","padding","td","10px","top","margin","inherit","기자","뉴스핌","사진","25일","열린","이날","이어","없는","이후","각각","같은","20일","21일","22일","23일","24일","26일","27일","28일","29일","newspim","나선","바로","하지만","했다","오른","그러나","앞서","나서","1차","다시","com","Newspim","올해","보였다","대한","기록했다","지난","2017","됐다","말했다","것으로","취한","만큼","모든","대해서는","밝혔다","역시","아니다","100","아직","없다","차지했다","같다","때문에","페이스북","한편","주는","있는","있다","여전히","라는","에서","캡처","3월","보인다","으로","않았다","위해","가진","좋은","않아","크게","받았다","이번","20","되고","것도","하고","있었다","없이","넘게","하며","달리","그런","씨는","씨가","가장","다른","너무","그의","담은","오후","못한","않을","그렇게","아닌","한다","이런","이에","끌었다","많이","모두","하는","없었다","들었다","되면","에서는","알려졌다","담긴","받은","많은","되는","라고","면서","바란다","밝힌","함께","있게","2018","1년","3일","알려졌다","아니라","뿐만","당시","대해","확인할","어떤","등을","맞춰","2월","통해","또한","의해","그는","전했다","이를","갖고","일을","되어","앞으로","수도","02","10","11","12","13","14","15","16","17","18","19","21","22","23","24","25","26","27","28","2F","3A","55","200","yna","1일","2Eco","2Ekr","ifrm","www4","2Fimg","5Fnew","width","iframe","따라","Iframe1","siteKey","2Ecareer","2Fcareer","2Fcommon","5F200x55","5Fcareer","noresize","scrolling","이라고","하시기","frameborder","framespacing","없습니다","남았습니다","30","된다","또한","것을","등이","있어","않은","있을","있으며","등으로","만에","않는","옵니다","09","arl","60","갖고","보면","않는다","000");
$dic        = array();
$dicStr     = array();

$imp = 0;
//기본함수 정리
$time01 = get_time();
$today = date("Y-m-d");

/*함수정리*/
//시간 처리를 위한 함수
function get_time() {
	list($usec, $sec) = explode(" ", microtime());
	return ((float)$usec + (float)$sec);
}
//사전만들기
function makeDic($key01,$str){
	global $dic,$dicStr,$fileInfo,$exptKey;
	$string = preg_replace("/[ #\&\+\-%@=\/\\\:;,\.'\"\^`~\_|\!\?\*$#<>()\[\]\{\}“”◆▲·‘’…ㆍ•△]/u", " ", $str);	
	$arrayTmp = explode(" ", $string);
	
	foreach ($arrayTmp as $key){
		//echo $key."<br />\n";
        $key = trim($key);
		//if($key != "" && mb_strlen($key) >= 2 && !in_array($key,$exptKey)){
        if($key != "" && mb_strlen($key) >= 2 ){
			if(isset($dic[$key])){
				if($dic[$key][1] == $key01){
					continue;					
				}
				else{
					$dic[$key][0] = $dic[$key][0].",".$key01;
					$dic[$key][1] = $key01;		
				}
							
			}else{
				$dic[$key][0] = $key01;
				$dic[$key][1] = $key01;
			}			
		}		
	}
    //키제거
    foreach($exptKey as $key04){
        unset($dic[$key04]);        
    }
    
//print_r($dic);
//echo "\n\n\n\n";
}

//사전만들기(배열에서 -> 스트링) 
function makeDicStr(){
	global $dic,$dicStr;
	foreach ($dic as $key02=>$val02){
		if(isset($dic[$key02]) && is_array($dic[$key02])){
			//배열로 처리
			$dic[$key02] = array_unique($dic[$key02]);
			//문자열로 처리
			$keyTmp = implode(",",$dic[$key02]);
			$dicStr[$key02] = $keyTmp;
				
		}
	}
}
//사전에서 파일만들기 
function makeFile(){
	global $dic,$fileInfo;
	//화일로 찍기위해 오픈
	$fp1 = fopen($fileInfo, "w") or die("Unable to open file!");
	
	foreach ($dic as $key03 => $val03){
		fwrite($fp1, $key03."\t".$val03[0]."\n");
	}
	
	//화일클로즈
	fclose($fp1);
}

//사전DB 만들기
function makeDicDb(){
	global $dbInfo,$fileInfo;
	list($hostUrl,$user,$pwd,$dbIns) = $dbInfo;
	$conn=mysqli_connect($hostUrl,$user,$pwd,$dbIns);
	if(!$conn){
		echo "Debugging errno : ". mysqli_connect_error() .PHP_EOL;
		exit();
	}
	$conn->set_charset("utf8");
	
	//테이블 없으면 신규 생성
	$createDicTableSql  = "CREATE TABLE IF NOT EXISTS `newsDic` ( ";
	$createDicTableSql .= "`newsKey` varchar(50) NOT NULL,";
	$createDicTableSql .= "`newsVal` MEDIUMTEXT NOT NULL,";
	$createDicTableSql .= "PRIMARY KEY (`newsKey`)";
	$createDicTableSql .= ") ENGINE=InnoDB DEFAULT CHARSET=utf8;";
	$createDicTableSql .= "";
	
	mysqli_query($conn, $createDicTableSql);

	// csv 단위 로딩...
	$sql = "LOAD DATA LOCAL INFILE '$fileInfo' REPLACE INTO TABLE `newsDic` FIELDS TERMINATED BY '\t' ";
	mysqli_query($conn, $sql);
	
	mysqli_close($conn); //db 종료	
}
//사전화일 만드는 프로시져
function mekeDicStart(){
	global $dbInfo,$imp;
	
	/*프로그램시작*/
	list($hostUrl,$user,$pwd,$dbIns) = $dbInfo;
	$conn=mysqli_connect($hostUrl,$user,$pwd,$dbIns);
	if(!$conn){
		echo "Debugging errno : ". mysqli_connect_error() .PHP_EOL;
		exit();
	}
	$conn->set_charset("utf8");
    
	
	$readDataQuery =  "select * from `newsSearch` " ;
	//$readDataQuery =  "select * from `newsSearch` limit 100000" ;
  
	$resultSet = mysqli_query($conn,$readDataQuery);
echo "starting... <br />\n";	
	while($row = mysqli_fetch_row($resultSet)){
		makeDic($row[0],$row[1]);
		//echo $row[0].":::".$imp."<br />\n";		
		$imp++;
		if($imp%1000 == 0){
			echo "$imp <br />\n";
			ob_flush();
			flush();
		}
	
	}
	
	//사전을 키워드 리스트로 만듦 
	//makeDicStr();
	
	//사전을 화일로 찍음 
	makeFile();
	unset($dic);
    
	//화일을 DB로 옮김 
	//makeDicDb();
}

//$str = "한글";
//echo mb_strlen($str);

//사전 만들때 호출 
//mekeDicStart();

//화일을 DB로 옮림 
makeDicDb();

//echo "<pre> \n";
//print_r($dic);

//print_r($dic);
//echo "test";

//print_r($dic) ;
//print_r($dicStr);
//$dic['황교안'] = array_unique($dic['황교안']);
//echo "<br />";
//print_r($dicStr['국회에서']) ;

 



?>
//
