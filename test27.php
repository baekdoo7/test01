<?php
/*
* imp_re_schedule() 
*    하루에 한번 실행(새벽 0시 10분에 로그 동기화 후에 돌면 될거 같음
*    ad_imp_schedule 테이블에 스케줄링을 실제 노출 카운트를 감안하여 다시 계산
* imp_schedule(광고그룹코드)
*    광고그룹 생성 및 수정 되었을때 호출
*    노출 타겟별로 노출 스케쥴을 계산하여 ad_imp_schedule에 생성
*
*/

  //디비정보
  $dbInfo     = array("103.60.126.88","root","root","compass");
  $dbInfoLog  = array("103.60.126.88","root","root","compass_log");

//시간 처리를 위한 함수
function get_time() {
	list($usec, $sec) = explode(" ", microtime());
	return ((float)$usec + (float)$sec);
}

//노출량 체커(10분간격으로 돌면서 노출량 제어 화일을 생성한다.)
function imp_checker(){
    global $dbInfo,$dbInfoLog;
    
    $json_imp = '"12345678-1234-1234-1234-123456789012":0';
    //디비연결
    list($hostUrl,$user,$pwd,$dbIns) = $dbInfo;
	$conn=mysqli_connect($hostUrl,$user,$pwd,$dbIns);
	if(!$conn){
		echo "Debugging errno : ". mysqli_connect_error() .PHP_EOL;
		exit();
	}
	$conn->set_charset("utf8"); 

    list($hostUrl,$user,$pwd,$dbIns) = $dbInfoLog;
	$conn2=mysqli_connect($hostUrl,$user,$pwd,$dbIns);
	if(!$conn2){
		echo "Debugging errno : ". mysqli_connect_error() .PHP_EOL;
		exit();
	}
	$conn2->set_charset("utf8"); 
    
    //현재 날짜 시간 세팅
     $today_ymd     = date("Ymd",strtotime("+9 hours"));
     $today_hour    = date("H",strtotime("+9 hours"));
    
     $yesterday_ymd = date("Y-m-d",strtotime($today_ymd)- 60 * 60 * 24)." 15:00:00";
     $today_ymd2    = date("Y-m-d",strtotime($today_ymd))." 14:59:59";
      
     $range01 = 
    
    //노출량 조절 광고그룹코드 읽어 오기
     $read_gpid = "select gp_idx,imp_cnt from ad_imp_schedule where sch_date = '$today_ymd' and imp_stime <= $today_hour and imp_etime >= $today_hour ";
     $resultSet     = mysqli_query($conn,$read_gpid);
   
     while($row = mysqli_fetch_row($resultSet)){
         //그룹아이디별 현재 노출량 읽어 오기
         $read_impsum = "select if(sum(impr) <> '',sum(impr),0) from compass_log where gp_idx = '".$row[0]."' and search_date between '$yesterday_ymd' and '$today_ymd2'  "; 
         $resultSet2  = mysqli_query($conn2,$read_impsum);
         $impCnt      = mysqli_fetch_row($resultSet2);
         $till_count  = $impCnt[0];
         
         //현재 시간까지 노출량 및 노출가능 수량 구해서 계산 하기
         if($today_hour < 21){
             $able_imp = round(($row[1] / 23) * ($today_hour + 1));
         }
         else{
             $able_imp = $row[1];
         }
         
         
         if($till_count >= $able_imp){
             $avaiable_imp = 0;
         }else{
             $avaiable_imp = $able_imp - $till_count;
         }
         
         $json_imp .= ",\"".$row[0]."\":".$avaiable_imp;
         //$remainder   = $row[3] - $till_count;
         //echo $able_imp."<br />";
     }
   
//echo "{".$json_imp."}";
   
    $check_count = json_decode("{".$json_imp."}",true);
    
    //광고그룹 스케줄 갯수가 기본 1개 이상이면 출력
    if(count($check_count) > 1){
        //파일생성    
        $fp1 = fopen('impCtrl.json', 'w') or die('Unable to open file!'); 
        fwrite($fp1, "{".$json_imp."}");
        //파일클로즈
        fclose($fp1); 
    }
    
//echo $yesterday_ymd."/".$today_ymd2;
     mysqli_close($conn2); //db 종료	
     mysqli_close($conn); //db 종료	
    
}
//광고 리스케쥴러(하루에 한번씩 돌며 진행중인 광고의 스케줄을 다시 잡아 준다.)
function imp_re_schedule(){
    global $dbInfo,$dbInfoLog;
    
    $gp_ids = "'_'";
    
    //실행시간 5분으로 메모리 무제한
    ini_set("max_execution_time",3000);
    ini_set("memory_limit",-1);
    $startTime = get_time();
    
    //디비연결
    list($hostUrl,$user,$pwd,$dbIns) = $dbInfo;
	$conn=mysqli_connect($hostUrl,$user,$pwd,$dbIns);
	if(!$conn){
		echo "Debugging errno : ". mysqli_connect_error() .PHP_EOL;
		exit();
	}
	$conn->set_charset("utf8"); 

    list($hostUrl,$user,$pwd,$dbIns) = $dbInfoLog;
	$conn2=mysqli_connect($hostUrl,$user,$pwd,$dbIns);
	if(!$conn2){
		echo "Debugging errno : ". mysqli_connect_error() .PHP_EOL;
		exit();
	}
	$conn2->set_charset("utf8");    
    
    //날자 세팅(날짜 실서버세팅시 새로 설정)
    $today_ymd     = date("Ymd",strtotime("+9 hours"));
    $yesterday_ymd = date("Ymd",strtotime("-15 hours")); 
    
    $f_date = date("Y-m-d",strtotime("+9 hours"));
    $e_date = date("Y-m-d",strtotime("-15 hours"));
    
     
    //광고 인벤토리에서 시작일이 명일인거 준비에서 진행으로 상태 변경
    $read_query    = "select gp_idx from advertise_ad_group where del_yn = 'N' and gp_state = 0 and f_date like '".$f_date."%' ";
    $resultSet     = mysqli_query($conn,$read_query);
     while($row = mysqli_fetch_row($resultSet)){
         $gp_ids .= ",'".$row[0]."'";
     }
    
     if($gp_ids != "'_'"){
         //광고그룹에서 상태를 진행으로(0->1)로 변경
          $update_query = "update advertise_ad_group set gp_state = 1 where  gp_idx in (".$gp_ids.")";
          mysqli_query($conn,$update_query); 
         
         //광고소재에서 상태를 진행으로(0->1)로 변경
          $update_query = "update advertise_ad set adv_state = 1 where  gp_idx in (".$gp_ids.")";
          mysqli_query($conn,$update_query);
         
         //영역광고에서 상태를 진행으로(0->1)로 변경
          $update_query = "update inventory_area_ad set ad_state = 1 where  gp_idx in (".$gp_ids.")";
          mysqli_query($conn,$update_query);
     }
  
    //광고 인벤토리에서 종료일이 금일인거 진행에서 종료
    $read_query    = "select gp_idx from advertise_ad_group where del_yn = 'N' and gp_state = 1 and e_date like '".$e_date."%' ";
    $resultSet     = mysqli_query($conn,$read_query);
     while($row = mysqli_fetch_row($resultSet)){
         $gp_ids .= ",'".$row[0]."'";
     }
    
     if($gp_ids != "'_'"){
         //광고그룹에서 상태를 진행으로(1->3)로 변경
          $update_query = "update advertise_ad_group set gp_state = 3 where  gp_idx in (".$gp_ids.")";
          mysqli_query($conn,$update_query); 
         
         //광고소재에서 상태를 진행으로(1->3)로 변경
          $update_query = "update advertise_ad set adv_state = 3 where  gp_idx in (".$gp_ids.")";
          mysqli_query($conn,$update_query);
         
         //영역광고에서 상태를 진행으로(1->3)로 변경
          $update_query = "update inventory_area_ad set ad_state = 3 where  gp_idx in (".$gp_ids.")";
          mysqli_query($conn,$update_query);
     }
    
    
    
    //광고수치 다시 재계산 처리
    $read_query  = "select gp_idx,f_date,e_date,quota from advertise_ad_group where del_yn = 'N' and gp_state = 1 and target_type = 2 ";
    $resultSet   = mysqli_query($conn,$read_query); 
    
    //목표노출 완료 방식만 가져다가 현재까지 노출량 빼고 재계산
    while($row = mysqli_fetch_row($resultSet)){
        $read_impsum = "select if(sum(impr) <> '',sum(impr),0) from compass_log where gp_idx = '".$row[0]."' and search_date >= '".$row[1]."' "; 
        $resultSet2  = mysqli_query($conn2,$read_impsum);
        $impCnt      = mysqli_fetch_row($resultSet2);
        $till_count  = $impCnt[0];        
        $remainder   = $row[3] - $till_count;
        
        //스케줄 다시 생성
        insert_imp_limittarget($f_date." 00:00:00",$row[2],$remainder,$row[0]);
        //echo $remainder;
     }
    
    mysqli_close($conn2); //db 종료	
    mysqli_close($conn); //db 종료	
//echo $read_query ."/".$e_date;    
}//광고 리스케쥴러




//광고 그룹 스케줄러
function imp_schedule($gpidx){
    global $dbInfo;
    
    //실행시간 5분으로 메모리 무제한
    ini_set("max_execution_time",3000);
    ini_set("memory_limit",-1);
    $startTime = get_time();

   
    
    
    //디비연결
    list($hostUrl,$user,$pwd,$dbIns) = $dbInfo;
	$conn=mysqli_connect($hostUrl,$user,$pwd,$dbIns);
	if(!$conn){
		echo "Debugging errno : ". mysqli_connect_error() .PHP_EOL;
		exit();
	}
	$conn->set_charset("utf8");
    
    //gpadx로 광고 그룹 정보 리딩
    $readDataQuery = "select f_date,e_date,target_type,quota,unit_cost,total_amt from advertise_ad_group where gp_idx = '$gpidx' and gp_type = 1 and del_yn = 'N'";
    
    $resultSet = mysqli_query($conn,$readDataQuery);
    $row = mysqli_fetch_row($resultSet);
    
    if($row){
         if($row[2] == 2){ //목표타입이 목표노출
            insert_imp_limittarget($row[0],$row[1],$row[3],$gpidx,2);
         }
        elseif($row[2] == 3){ // 목표타입이 일 제한
            insert_imp_limitdate($row[0],$row[1],$row[3],$gpidx,1);
        }   
    }
    
        
    mysqli_close($conn); //db 종료	
       
}
//광고 일별 노출량 세팅(목표노출 타입)
function insert_imp_limittarget($startDate,$endDate,$amt,$gb_idx){
    global $dbInfo;
    
    //종료일이 시작일보다 작을경우 종료
    if($startDate >= $endDate){
        return;
    }
    
    //디비연결
    list($hostUrl,$user,$pwd,$dbIns) = $dbInfo;
	$conn=mysqli_connect($hostUrl,$user,$pwd,$dbIns);
	if(!$conn){
		echo "Debugging errno : ". mysqli_connect_error() .PHP_EOL;
		exit();
	}
	$conn->set_charset("utf8");
    
    //기존 노출량 삭제
    $deleteQuery = "delete from ad_imp_schedule where gp_idx = '$gb_idx'";
    mysqli_query($conn,$deleteQuery);
    
    //날짜사이를 시간으로 환산
    $tot_time = round((strtotime("$endDate") - strtotime("$startDate"))/(60 * 60)); //캠페인 기간을 전체 시간 으로 환산 
    
    $imp_onehour = round($amt/$tot_time); //시간당 노출량
    
    $imp_oneday  = round($imp_onehour * 24);//하루 노출량
        
    $sdate = date("Ymd",strtotime($startDate));
    $stime = date("H",strtotime($startDate));
    $edate = date("Ymd",strtotime($endDate));
    $etime = date("H",strtotime($endDate));
    
    
    if($sdate == $edate){     //시작일과 종료일이 같은 경우 
        insert_imp_limitdate($startDate,$endDate,$amt,$gb_idx,2);
    }
    elseif(date("Ymd",strtotime($startDate) + 60 * 60 * 24) == $edate ){ //시작일 다음날이 종료일인 경우 
         $end_date_imp = $imp_onehour * $etime;
         $start_day = $amt - $end_date_imp;
         
         insert_imp_limitdate($startDate,date("Y-m-d",strtotime($startDate))." 23:59:59",$start_day,$gb_idx,2);
         insert_imp_limitdate(date("Y-m-d",strtotime($endDate)). " 00:00:00",$endDate,$end_date_imp ,$gb_idx,2);
    } 
    else{     //시작일과 종료일 사이가 하루 이상인 경우
        $after_sdate  = date("Y-m-d",strtotime($startDate) + 60 * 60 * 24);
        $before_edate = date("Y-m-d",strtotime($endDate) - 60 * 60 * 24);
        $after_sdate .= " 00:00:00";
        $before_edate .= " 23:59:59";
        
        $day_count = (strtotime($before_edate) - strtotime($after_sdate) + 1) / (60 * 60 * 24);
        
        $mid_day_imp  = $imp_oneday ;
        $end_date_imp = $imp_onehour * ($etime + 1); 
        $start_day    = $amt - ($mid_day_imp * $day_count) - $end_date_imp;

        insert_imp_limitdate($startDate,date("Y-m-d",strtotime($startDate))." 23:59:59",$start_day,$gb_idx,2);
        insert_imp_limitdate($after_sdate,$before_edate,$mid_day_imp,$gb_idx,2);
        insert_imp_limitdate(date("Y-m-d",strtotime($endDate)). " 00:00:00",$endDate,$end_date_imp ,$gb_idx,2);
    }
    
    //echo $tot_time;
    
    
    mysqli_close($conn); //db 종료	
}
//광고 일별 노출량 세팅(일제한 타입) 
function insert_imp_limitdate($startDate,$endDate,$amt,$gb_idx,$flag){
    global $dbInfo;
//echo  $startDate."/".$endDate."<br/>";   
    //종료일이 시작일보다 작을경우 종료
    if($startDate > $endDate){
        return;
    }

    //디비연결
    list($hostUrl,$user,$pwd,$dbIns) = $dbInfo;
	$conn=mysqli_connect($hostUrl,$user,$pwd,$dbIns);
	if(!$conn){
		echo "Debugging errno : ". mysqli_connect_error() .PHP_EOL;
		exit();
	}
	$conn->set_charset("utf8");
    
    //기존 노출량 삭제($flag 가 1인경우만 삭제)
    if($flag == 1){
            $deleteQuery = "delete from ad_imp_schedule where gp_idx = '$gb_idx'";
            mysqli_query($conn,$deleteQuery);
    }
    
    $sdate = date("Ymd",strtotime($startDate));
    $stime = date("H",strtotime($startDate));
    $edate = date("Ymd",strtotime($endDate));
    $etime = date("H",strtotime($endDate));
    
    $sdateTmp = $sdate;
    $edateTmp = $edate;
    
 
    //echo  date("Ymd",strtotime($sdate) + 60 * 60 * 24);
    
    while($sdateTmp <= $edate){
        if($sdateTmp == $sdate){
            if($sdate == $edate){
                $stimeTmp = $stime;
                $etimeTmp = $etime;
                }
            else{
                $stimeTmp = $stime;
                $etimeTmp = 23;            
            }
        }
        elseif($sdateTmp == $edate ){
            $stimeTmp = 0;
            $etimeTmp = $etime;
        }
        else{
            $stimeTmp = 0;
            $etimeTmp = 23;    
        }
        $insertQuery = "insert into ad_imp_schedule values('$sdateTmp','$gb_idx',$amt,$stimeTmp,$etimeTmp)";
        mysqli_query($conn,$insertQuery);
        
        $sdateTmp =  date("Ymd",strtotime($sdateTmp) + 60 * 60 * 24);
        //echo $sdate."<br />";
    }
        
    mysqli_close($conn); //db 종료	
}


//imp_schedule("225799bd-0afe-4c59-9d92-06b7613d865a");
//insert_imp_limitdate("2017-05-17 14:00:00","2017-05-19 16:00:00",400,"225799bd-0afe-4c59-9d92-06b7613d865a",1);
//insert_imp_limittarget("2017-05-17 14:00:00","2017-05-20 16:59:59",100000,"225799bd-0afe-4c59-9d92-06b7613d865a");
//imp_checker();
//imp_re_schedule();
//echo date("Y-m-d H:i:s",strtotime("+9 hours"));

  $tot_time = round((strtotime("2017-06-28 14:59:59") - strtotime("2017-06-27 12:00:00"))/(60 * 60)); //캠페인 기간을 전체 시간 으로 환산
  echo  $tot_time;  
?>
<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="utf-8">
<script src="http://www.dt.co.kr/js/jquery.js"></script>
<title>광고테스트</title>
<style>
</style>
</head>
<body>


    
테스트
<br />
<br />
<br />
테스트
    

</body>
</html>