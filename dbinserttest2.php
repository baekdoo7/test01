#!/Applications/MAMP/bin/php/php7.1.0/bin/php
<?php 
ini_set("max_execution_time",300);


function get_time() {
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}

$ikey =0;
$start = get_time();


$host     = "localhost";
$user     = "root";
$password = "root";
$dbname   = "adop_test";

// MySQL 데이터베이스 연결
$mysqli = new mysqli($host, $user, $password, $dbname);

// 연결 오류 발생 시 스크립트 종료
if ($mysqli->connect_errno) {
    die('Connect Error: '.$mysqli->connect_error);
}

//랜덤문자 하나를 찍기위한 배열
$chars=array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z');

//화일로 찍기위해 오픈 
$fp1 = fopen("itemfeed3.txt", "w") or die("Unable to open file!");

 //$query = "insert into inserttest value(1,1,1,1,'a')";
 //$mysqli->query($query);

// 쿼리문 전송
//if ($result = $mysqli->query('SELECT * FROM `temp`)) {
    // 레코드 출력
//    while ($row = $result->fetch_object()) {
  //      echo $row->name.' / '.$row->desc.'<br />';
   // }

    // 메모리 정리
   // $result->free();
//}




$wheretmp = "";
$saveData = "";
for($i=1;$i <= 10000000 ;$i++){
    $ikey = $i;
    $imod2 = $i%2;
    $imod5 = $i%5;
    $imod5 = $i%13;   
    $calpha = $chars[$i%26];
	//화일로 처리
	if($i%200000 == 0){
		$saveData .= $ikey."\t".$imod2."\t".$imod5."\t".$imod5."\t".$calpha."\n";
		fwrite($fp1, $saveData);
		$saveData = 0;
	}else{
		$saveData .= $ikey."\t".$imod2."\t".$imod5."\t".$imod5."\t".$calpha."\n";
	} 
    //fwrite($fp1, $ikey."\t".$imod2."\t".$imod5."\t".$imod5."\t".$calpha."\n");
   
    /* db로 처리부분 
    $wheretmp .= "($ikey,$imod2,$imod5,$imod13,'$calpha')";

    if($i%200000 == 0){
        $query = "insert into testmysql values ".$wheretmp;
        //$mysqli->real_query($query);
        //$mysqli->multi_query( $query);
        $wheretmp = "";
    }
    else{
        $wheretmp .= ",";
    }
    */

}

//화일클로즈
fclose($fp1);

//파일로 읽어서 디비 인서트 
$sql = "LOAD DATA LOCAL INFILE 'itemfeed3.txt' REPLACE INTO TABLE testmysql FIELDS TERMINATED BY '\t' ";
$mysqli->real_query($sql);

//화일삭제
unlink("itemfeed3.txt");

// 접속 종료
$mysqli->close();

$end = get_time();
$time = $end - $start;

echo "$ikey<br />\n";
echo '<br/>'.$time.'초 걸림';
?>