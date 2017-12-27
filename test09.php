<?php

// MySQL 데이터베이스 연결
$link = mysqli_connect("compassread.cnzcxy7quzcs.ap-northeast-2.rds.amazonaws.com", "adopadmin", "Adop*^14", "platon");

// 연결 오류 발생 시 스크립트 종료
if (mysqli_connect_errno()) {
	die('Connect Error: '.mysqli_connect_error());
}

$link->set_charset("utf8");
// 쿼리문 전송
if ($result = mysqli_query($link, "select * from advertise_ad where adv_idx='29ead681-8520-4686-885e-98cf95d6cacf'")) {
    // 레코드 출력
    while ($row = mysqli_fetch_object($result)) {
        //echo $row->adv_idx.' / '.$row->html_code.'<br />';
        //echo $row->adv_name;
        //print_r($row) ;
        $tmp = json_encode($row);
        //echo $tmp;
        $fp = fopen("jsontest.txt", "w") or die("Unable to open file!");
        fwrite($fp, $tmp);
        fclose($fp);
    }
   
    // 메모리 정리
    mysqli_free_result($result);
}

// 접속 종료
mysqli_close($link);



$fp  = fopen("jsontest.txt", "r") or die("Unable to open file!"); //광고주 원데이타
$buffer = fgets($fp);
$tmp001 = json_decode($buffer,true);
print_r($tmp001);
fclose($fp); 

?>
//