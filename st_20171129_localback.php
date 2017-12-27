<?php

//error_reporting(E_ALL);
//ini_set("display_errors", 1);


/**
 * 공통 로드될 것들
 */
date_default_timezone_set('UTC');
include_once('../assets/lib/constant.php');
include_once('../assets/lib/common.php');
include_once('./switch_list.php');
include_once ('./adopRTclass.php');
include_once ('/Data/ad_info/RT/adopRTdata.php');


$area_idx = $_GET['area_idx'];

if(isset($zonInfo[$area_idx])){
    $area_idx = $zonInfo[$area_idx]["VCode"];
}

$protocol_tmp = parse_url($_SERVER['HTTP_REFERER']);
if($protocol_tmp['scheme'] != "http" && $protocol_tmp['scheme'] != "https"){
    $protocol = "http";
}else{
    $protocol = $protocol_tmp['scheme'];
}

$_RequestLogURL = LOGSERVER_HOST . "/compass/req?adver_type=compass&z=" . $area_idx;
Data_Connect_Curl_No_Return($_RequestLogURL);

if(file_exists(ADINFO_PATH.$area_idx."/ad_info.txt")) {
    $ad_info_tmp = file_get_contents(ADINFO_PATH . $area_idx . '/ad_info.txt');
}else{
    $ad_info_tmp = make_ad_info($area_idx);
}

$ad_info = json_decode($ad_info_tmp,true);
$addate = date("Y-m-d");
$GLOBALS['area_idx'] = $area_idx;


$rmd_code = make_rmd_code();

$_fake_img = '<div style="position: absolute; left: -10000px; top: 0px; overflow: hidden; width:1px; height: 1px;">';
$_fake_img.= '<script src="https://log2.adop.cc/compass/imp?a=' . $ad_info[0]['adv_idx'] . '&z=' . $area_idx . '&adver_type=compass&r='.$rmd_code.'" style="width:1px; height:1px;"></script>';
$_fake_img.= '</div>';

if($protocol!= "http"){
    $ad_info[0]['html_code'] = str_replace('http://','https://',$ad_info[0]['html_code']);
}

 echo make_script2tag($ad_info[0]['html_code'] ."\n". $_fake_img);


function make_script2tag($str){
    $retVal = "";   
    $str001 = explode("\n",$str);
    foreach($str001 as $k => $v){        
        $vtmp = "";
	$vtmp = str_replace("\r","",$v);
	//$vtmp = nl2br($v);
        $vtmp = str_replace("\"","\\\"",$vtmp);
        $vtmp = str_replace("</script","<\\/script",$vtmp);
	//$vtmp = nl2br($vtmp);
        
        $vtmp = "document.writeln(\" " .$vtmp. "     \");\n";
        $retVal .= $vtmp;
    }
    return $retVal;
}
