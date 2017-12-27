<?php
ini_set("max_execution_time",3000);
ini_set("memory_limit",-1);
$startTime = get_time();

//디비정보
$dbInfo     = array("localhost","root","root","adop_test");
$dbInfo2    = array("localhost","root","root","adop_test");
//$dbInfo2    = array("52.78.191.196","root","root","study_3_10");
//$dbInfo     = array("52.78.191.196","root","root","study_3_10");

//키워드 입력 받음
$kwd = isset($_POST['kwd'])?$_POST['kwd']:"";
$kwd1 = strtolower($kwd);
$worktime = 0;
$workcnt  = 0;
$impCnt   = 0;
$kval     = array();
$andKwda  = array();
$orKwda   = array();
$resultSet = array();

if($kwd == ""){
    $kwd = "Search";
}


if($kwd1 != ""){
    //키워드분리
    $orKwd = explode("or",$kwd1);    
    foreach($orKwd as $val){
        if(trim($val) == ""){
            continue;
        }
        $andKwd = explode(" ",$val);

        foreach($andKwd as $val01){
            if(trim($val01) == ""){
                continue;
            }
            $kval[] = getKey($val01);
        }

        foreach($kval as $kval01){
            $keyTmp = explode(",",$kval01);
            $keyTmp = array_filter(array_map('trim',$keyTmp));
            if(count($andKwda) > 0){
                $andKwda = array_intersect($andKwda,$keyTmp);
            }else{
                $andKwda = array_unique($keyTmp);
            }
        }
        //echo "$val";
        $orKwda[] = $andKwda; 
        unset($kval);
        unset($andKwda);
        $kval     = array();
        $andKwda  = array();
    }
      //print_r($orKwda);  //키워드보여주는거
      //echo "<br />\n";
      $resultSet = getVal($orKwda);
      /*    
      if(($resultSet) != ""){
        //$workcnt   = mysqli_num_rows($resultSet);
        $workcnt   = count($orKwda);
      }else{
        $workcnt   = 0;  
      }
      */
}
else{
    
}

$worktime = round(get_time() - $startTime,3);
/*함수정리*/
//시간 처리를 위한 함수
function get_time() {
	list($usec, $sec) = explode(" ", microtime());
	return ((float)$usec + (float)$sec);
}

//키값가져오는 함수
function getKey($str){
  global $dbInfo;
         $valTmp = ""; 
    
  
    list($hostUrl,$user,$pwd,$dbIns) = $dbInfo;
	$conn=mysqli_connect($hostUrl,$user,$pwd,$dbIns);
	if(!$conn){
		echo "Debugging errno : ". mysqli_connect_error() .PHP_EOL;
		exit();
	}
	$conn->set_charset("utf8");
    $readDataQuery = "select newsval from newsDic where newskey like '$str%'";
	$resultSet     = mysqli_query($conn,$readDataQuery);
	while($row = mysqli_fetch_row($resultSet)){
        $valTmp .= ",".$row[0];	
	}    
    
    mysqli_close($conn); //db 종료
    return $valTmp;    
}
//키값에 따른 값 가져오는 함수
function getVal($keyArray){
    global $dbInfo2,$workcnt;
    $keylist = "";
    $cntImsi = 0;
    $countLimit = array();
  
    foreach($keyArray as $querykey){
        if($cntImsi == 0){
            $keylist .= implode(",",$querykey);  
            $cntImsi++;
        }else{
            $keylist .= ",".implode(",",$querykey);  
            $cntImsi++;
        }
        
        //echo $cntImsi."<br />\n";
        
    }
    if(trim($keylist) == ""){
        $workcnt = 0;    
    }else{
        $countLimit = explode(",",$keylist);
        $workcnt = count($countLimit);
        if($workcnt > 100){
            array_splice($countLimit,100);
            $keylist = implode(",",$countLimit);
        }
        
    } 
    

  
    list($hostUrl,$user,$pwd,$dbIns) = $dbInfo2;
	$conn=mysqli_connect($hostUrl,$user,$pwd,$dbIns);
	if(!$conn){
		echo "Debugging errno : ". mysqli_connect_error() .PHP_EOL;
		exit();
	}
	$conn->set_charset("utf8");
    
    $readDataQuery = "select * from newsSearch where id in ($keylist)";
	$resultSet     = mysqli_query($conn,$readDataQuery);
	//while($row = mysqli_fetch_row($resultSet)){
    //    $valTmp .= ",".$row[0];	
	//}  
    
    mysqli_close($conn); //db 종료
    return $resultSet;    
}

?>
<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="utf-8">
<title>풀텍스트서치</title>
<style>
    .sizeup {
       font-size:18px;   
   }
    .label01 {
        width: 150px;
        text-align: center;
        font-size: 30px;
        background-color: #aaa;
        border-radius: 25px;
        float: left;
        
    }
    .label02 {
        width: 150px;
        text-align: center;
        font-size: 30px;
        float: left;
       
    }
    .label03 {
        display: inline;    
        text-align: center;
        font-size: 30px;
    }
    .t01{
        display: inline-block;
        font-size: 30px;
        width: 150px;
        text-align: center;
        background-color: #aaa;
        border-radius: 25px;
    }
    .i01{
        font-size: 30px;
        width: 600px;
        margin-left: 10px;
    }
    .b01{
        font-size: 30px;
    }
    .content01{
        border: 1px solid blue;
        widows: 100%;
        height: 200px;
        overflow: hidden;
            
    }
    .n1{
        display: inline;
        height: 100%;
        line-height: 200px;
        text-align: center;
        width: 100px;
        float: left;
        background-color: #aa0;
        border-radius: 25px;
        font-size: 20px;
            
    }
    .c01{
        display: inline;
        height: 200px;
        width: 800px;
        overflow:auto;
        float: left;
        margin-left: 30px;
    }

</style>
</head>
<body>     
<form class="" method="post" >
  <div class="form-group" >
    <span class="t01">ADOP</span>
    <input type="text" name="kwd" class="i01" placeholder="<?=$kwd?>" value="<?=($kwd=='Search')?'':$kwd; ?>">
       <button type="submit" class="b01">검 색</button>
  </div>
 
</form>                                                                                           
<hr />
    <div class="label01">  처리시간 </div><div class="label02"><?=$worktime?></div><div class="label03">초</div><div></div><br />
    <div class="label01">  처리결과 </div><div class="label02"><?=$workcnt?></div><div class="label03">건</div>
<hr />
    <?
    if($workcnt > 0){
        while($row = mysqli_fetch_row($resultSet)){
            if($impCnt > 50){
                break;
            }
            $impCnt++;
    ?>    
     <div class="content01">
        <div class="n1"><?=$row[0]?></div>
        <div class="c01">
            <pre>
            <?=$row[1]?>
            </pre>
        </div>        
    </div>      
    <?
	   }
    }
    ?>
</body>
</html>
                                         
                                               
                                               