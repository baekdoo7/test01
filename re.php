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

$docHeadStart = "";
$docHeadConts = "";
$docHeadEnd   = "";
$docBodyStart = "";
$docBodyConts = "";
$docBodyEnd   = "";

$area_idx = $_GET['area_idx'];
//수평>수직 전환영역일경우
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
/**
 * 수평 로직
 */
if($ad_info[0]['weight_direction'] == "h") {
    $loc = urldecode($_GET['loc']);
    $GLOBALS['loc'] = $loc;
    if (isset($_GET['adop_bk'])) {
        foreach ($ad_info as $Key => $Val) {
            if ($Val['adv_idx'] == $_GET['adop_bk']) {
                unset($ad_info[$Key]);
            }
        }
        $ad_info = array_values($ad_info);
    }

    //                    가중치 계산해서 광고를 가져온다.
    $ad_info[0] = array_select($ad_info);

    if ($ad_info[0]['ad_type'] == 1) { // ad network
        // Google Page URL
        if( strpos( $ad_info[0]['html_code'] , "__DATA_PAGE_URL__") !== FALSE ) {
            /** Compass -> Compass 일경우 처리 */
            if(strpos(urldecode($_SERVER['HTTP_REFERER']), "compass.adop.cc") !== false) {
                $_Compass_Referer = parse_url($_SERVER['HTTP_REFERER']);
                if( $_Compass_Referer['host'] == "compass.adop.cc" ) {
                    parse_str($_Compass_Referer['query'], $RefererParam);
                    $_LocUrl = $RefererParam['loc'];
                }
            } else {
                $_LocUrl = $_SERVER['HTTP_REFERER'];
            }

            $_curPageUrl = ( is_null($loc) or !$loc ) ? $_LocUrl : $loc;
            $ad_info[0]['html_code'] = str_replace ( "__DATA_PAGE_URL__", $_curPageUrl, $ad_info[0]['html_code'] );
        }

        //  Fill Rate ( Passbock )
        if( strpos( $ad_info[0]['html_code'] , "_ZONEID_") !== FALSE ) {
            $ad_info[0]['html_code'] = passBackFunc($area_idx, $ad_info[0]['adv_idx'], $ad_info[0]['size_width'], $ad_info[0]['size_height'], urlencode($loc)) . str_replace('_ZONEID_', 1, $ad_info[0]['html_code']);
        }

        //  Fill Rate ( Passbock ) - Interactivy
        if( strpos( $ad_info[0]['html_code'] , "__INTERACTIVY__") !== FALSE ) {
            $__PassBackURL = ADSERVER_HOST . "/RD/" . $area_idx . "?adop_bk=" . $ad_info[0]['adv_idx'];
            $ad_info[0]['html_code'] = str_replace ( "__INTERACTIVY__", $__PassBackURL, $ad_info[0]['html_code'] );
        }

        //  Fill Rate ( Passbock ) - Widerplanet
        if( strpos( $ad_info[0]['html_code'] , "__WIDERPLANET__") !== FALSE ) {
            $__PassBackURL = ADSERVER_HOST . "/RD/" . $area_idx . "?adop_bk=" . $ad_info[0]['adv_idx'];
            $ad_info[0]['html_code'] = "<iframe src='".$__PassBackURL."' frameborder='0' marginwidth='0' marginheight='0' paddingwidth='0' paddingheight='0' scrolling='no' style='width: 100%; height: 100%;'></iframe>";
        }

        //구글 패스백url있을경우
        if (isset($_GET['pbu'])) {
            if (strpos($ad_info[0]['html_code'], "google_ad_slot") !== FALSE) {
                $ad_info[0]['html_code'] = google_PassBack_Replace($ad_info[0]['html_code'], $_GET['pbu']);
            }
        }
    }

    if ($ad_info[0]['ad_type'] == 2) {
        if ($ad_info[0]['add_type'] == 1) {
            $key = $ad_info[0]['adv_file_path'] . $ad_info[0]['adv_md5_filename'];
            $imgpathname = 'http://dezf3o8j9jdt6.cloudfront.net/adimage/' . $key;
        } else {
            $imgpathname = $ad_info[0]['adv_url_hp'] . $ad_info[0]['adv_url'];
        }

        $_BACKPOPUP = (isset($ad_info[0]['adv_backpopup']) && $ad_info[0]['adv_backpopup'] == 'Y') ? "&lightcoms=y" : "";

        $clk_url = ADSERVER_HOST . "/serving/clk.php?ad=" . $ad_info[0]['adv_idx'] . "&ar=" . $area_idx . "&r=" . urlencode($ad_info[0]['adv_clk_url']) . $_BACKPOPUP . "";
        $height = $ad_info[0]['size_height'];
        $width = $ad_info[0]['size_width'];
        $img_url = $imgpathname;

        $ad_template = file_get_contents('../AD_HTML/adHTML.html');
        $ad_code = str_replace('__ADOP_IMAGE_URL__', $img_url, str_replace('__ADOP_CLICK_URL__', $clk_url, $ad_template));

        $ad_info[0]['html_code'] = $ad_code;

    } else if ($ad_info[0]['ad_type'] == 3) {
        $_sendData = array(
            'id' => get_dsp_unique_id(),
            'cur' => 'USD',
            'imp' => array(
                array(
                    'banner' => array(
                        'h' => $ad_info[0]['size_height'],
                        'w' => $ad_info[0]['size_width'],
                        'pos' => 0
                    ),
                    "bidfloor" => (is_null($ad_info[0]['adv_price']) || $ad_info[0]['adv_price'] == "") ? 0.1000 : $ad_info[0]['adv_price'],
                    'id' => $ad_info[0]['adv_idx']
                ),
            ),
            'site' => array(
                'loc' => $loc,
                'id' => $area_idx,
                'domain' => getHttpHost(),
//                        'domain' => 'http://www.adop.cc',
                'page' => (is_null($loc) or !$loc) ? $_SERVER['HTTP_REFERER'] : $loc,
                'name' => is_null($ad_info[0]['site_name']) ? '' : $ad_info[0]['site_name'],
//                        'page' => 'http://www.adop.cc/1234.html',
                'publisher' => array(
                    'id' => $ad_info[0]['com_idx']
                ),
            ),
            'device' => array(
                'ua' => $_SERVER['HTTP_USER_AGENT'],
                'ip' => $_SERVER['REMOTE_ADDR']
            ),
            'user' => array(
                'id' => GUID(),
                'buyeruid' => GUID()
            )
        );

        if ($ad_info[0]['adv_supply'] == '1') { //Bidswitch
            $_url = 'http://exchange.ads-optima.com/rtb/bidswitch';
        } else if ($ad_info[0]['adv_supply'] == 'rubicon') {
            $_url = 'http://exchange.ads-optima.com/rtb/rubicon';
        } else if ($ad_info[0]['adv_supply'] == '2') {
            $test03 = new rtObject;
            if($test03){
                $tmp = $test03->isADs();
            }
            if($tmp !== false){
                //제이슨 타입으로 광고 정보 가져 오는 부분
                $rt_info = $test03->getADs();
            }
            if(!empty($rt_info)) {
                $ad_info[0]['network_adv_idx'] = 'rt';
                $_sendData['retarget'] = $rt_info['retarget'];
                $_url = 'http://dsp.adop.cc/serving/rt';
            }else {
                //ATOM 이미지
                $_url = 'http://dsp.adop.cc/serving/z';
            }
        } else if ($ad_info[0]['adv_supply'] == '3') {
            $_sendData['slot']['board_idx'] = $ad_info[0]['pann_idx'];
            $_url = 'http://dsp.adop.cc/serving/t';
        } else if ($ad_info[0]['adv_supply'] == '4') { //Appier
            $_url = "http://exchange.ads-optima.com/rtb/appier";
        } else if ($ad_info[0]['adv_supply'] == '5') { //나스미디어
            $_url = "http://exchange.ads-optima.com/rtb/nasmedia";
        } else if ($ad_info[0]['adv_supply'] == '6') { //ATOM리타겟팅
            $ad_info[0]['network_adv_idx'] = 'rt';
            $_url = 'http://dsp.adop.cc/serving/rt2';
            $test03 = new rtObject;
            if($test03){
                $tmp = $test03->isADs();
            }
            if($tmp !== false){
                //제이슨 타입으로 광고 정보 가져 오는 부분
                $rt_info = $test03->getADs();
            }
            if(!empty($rt_info)) {
                $_sendData['retarget'] = $rt_info['retarget'];
            }
        } else if($ad_info[0]['adv_supply'] == '7') { //Moloco
            $_url = "http://exchange.ads-optima.com/rtb/moloco";
        }
        $_jsonData = json_encode($_sendData);
        if($protocol!= "http"){
            $_url = str_replace('http://','https://',$_url);
        }
        $html_code = Data_Connect_Curl_Json($_url, $_jsonData);

        if ($html_code == 'noads') {
            $ad_info[0]['html_code'] = passBackFunc($area_idx, $ad_info[0]['adv_idx'], $ad_info[0]['size_width'], $ad_info[0]['size_height'], urlencode($loc)) . str_replace('_ZONEID_', 1, $ad_info[0]['html_code']);
        } else {
            $ad_info[0]['html_code'] = $html_code;
        }
    }

    /**
     * 수직
     */
}else{

    //옵티마A 전환인지, CFS에 걸렸었는지
    $ad_info[0]['trans'] = $_GET['trs'];
    $ad_info[0]['cfs_chk'] = $_GET['c_chk'];

    if($protocol!="http"){
        $ad_info[0]['net_adv_passback'] = str_replace('http://','https://',$ad_info[0]['net_adv_passback']);
    }

    $ex_time = time()+(60*60*9)+60;
    $loc_tmp = urldecode($_GET['loc']);

    if($ad_info[0]['area_type'] == '0' ) {
        if($loc_tmp == ""){
            $loc_tmp = urldecode($_SERVER['HTTP_REFERER']);
        }
        setcookie('ADOP_P_U', $loc_tmp, $ex_time,"/",".adop.cc");
        $loc = $loc_tmp;
    }else{
        if($_COOKIE['ADOP_P_U']!="") {
            $loc = $_COOKIE['ADOP_P_U'];
        }else{
            $loc = $loc_tmp;
        }
    }
    $GLOBALS['loc'] = $loc;

    //비중과 워터폴방식에 따라 라벨 조건 체크 조건
    if($ad_info[0]['weight_direction']=='h'){
        $label_view = label_check();
        $label_view_child = label_check();
    }elseif($ad_info[0]['weight_direction']=='v') {
        $label_view = new_label_check($ad_info[0]['imp']);
        $label_view_child = label_check();
    }else{
        $label_view = false;
        $label_view_child = false;
    }
    $ad_info[0]['label_role'] = $label_view;

    if($ad_info[0]['ad_weight'] != 100){
        $area_weight = $ad_info[0]['ad_weight'];
        $weight_rmd = rand(0,100);
        if($area_weight <= $weight_rmd ){
            //패스백 주소를 RD -> RC 로 수정
            $ad_info[0]['net_adv_passback'] = str_replace("RD","RC",$ad_info[0]['net_adv_passback']);
	    $ad_info[0]['net_adv_passback'] = str_replace("compass.adop.cc","compasstest.adop.cc",$ad_info[0]['net_adv_passback']);
                
            if($label_view!=false){
                $html_code_tmp = '<script src="'.$ad_info[0]['net_adv_passback'].'" ></script>';
                $ad_info[0]['html_code'] = $html_code_tmp;
                echo2(net_label($ad_info));
                exit;
            }else {
                $html_code_tmp = '<script src="'.$ad_info[0]['net_adv_passback'].'" ></script>';
                echo "document.write('".$html_code_tmp."');";
                exit;
            }
        }
    }

    if ($ad_info[0]['ad_type'] == 1) { // ad network      
        if ($ad_info[0]['network_adv_idx'] == 'f3bb08bb-b596-42ff-af87-6939846a5c8b' || $ad_info[0]['network_adv_idx'] == '41350b05-4415-44b2-8e17-b5fe52d1bd6e' || $ad_info[0]['network_adv_idx'] == 'd5373708-ff0a-11e6-950e-02c31b446301') {
            if (cfs_test($loc) == "Y") {
                if (isset($_GET['pbu']) && $_GET['pbu'] != "") {
                    $ad_info[0]['net_adv_passback'] = $_GET['pbu'];
                }
                $ad_info[0]['net_adv_passback'] = str_replace("RD","RC",$ad_info[0]['net_adv_passback']);
                if ($label_view!=false) {
                    $ad_info[0]['cfs_chk'] = "Y";
                    $html_code_tmp = '<script src="'.$ad_info[0]['net_adv_passback'].'" ></script>';   
                    $ad_info[0]['html_code'] = $html_code_tmp;
                    echo2(net_label($ad_info));
                    exit;
                }else{
                    $html_code_tmp = '<script src="'.$ad_info[0]['net_adv_passback'].'" ></script>';
                    echo "document.write('".$html_code_tmp."');";
                    exit;
                }
            }else{
                $ad_info[0]['cfs_chk'] = "N";
            }
            //구글 패스백url있을경우
            if (isset($_GET['pbu'])) {
                if (strpos($ad_info[0]['html_code'], "google_ad_slot") !== FALSE) {
                    $ad_info[0]['html_code'] = google_PassBack_Replace_V($ad_info[0]['html_code'], $_GET['pbu']);
                }
            }
        }
        if (strpos( $ad_info[0]['html_code'],'api.adballoon.co.kr')!==false) {
            $ad_info[0]['html_code'] = str_replace('__adop_page_url__', urlencode($loc), $ad_info[0]['html_code']);
        }else{
            $ad_info[0]['html_code'] = str_replace('__adop_page_url__', $loc, $ad_info[0]['html_code']);
        }
    }elseif ($ad_info[0]['ad_type'] == 3) {//DSP, 아톰
        $_sendData = array(
            'id' => get_dsp_unique_id(),
            'cur' => 'USD',
            'imp' => array(
                array(
                    'banner' => array(
                        'h' => $ad_info[0]['size_height'],
                        'w' => $ad_info[0]['size_width'],
                        'pos' => 0
                    ),
                    "bidfloor" => (is_null($ad_info[0]['adv_price']) || $ad_info[0]['adv_price'] == "") ? 0.1000 : $ad_info[0]['adv_price'],
                    'id' => $ad_info[0]['adv_idx']
                ),
            ),
            'site' => array(
                'loc' => $loc,
                'id' => $area_idx,
                'domain' => getHttpHost(),
//                        'domain' => 'http://www.adop.cc',
                'page' => (is_null($loc) or !$loc) ? $_SERVER['HTTP_REFERER'] : $loc,
                'name' => is_null($ad_info[0]['site_name']) ? '' : $ad_info[0]['site_name'],
//                        'page' => 'http://www.adop.cc/1234.html',
                'publisher' => array(
                    'id' => $ad_info[0]['com_idx']
                ),
            ),
            'device' => array(
                'ua' => $_SERVER['HTTP_USER_AGENT'],
                'ip' => $_SERVER['REMOTE_ADDR']
            ),
            'user' => array(
                'id' => GUID(),
                'buyeruid' => GUID()
            )
        );

        if ($ad_info[0]['adv_supply'] == '1') { //Bidswitch
		$_url = 'http://exchange-elb-699622095.ap-northeast-2.elb.amazonaws.com/rtb/bidswitch';
            //$_url = 'http://exchange.ads-optima.com/rtb/bidswitch';
        } else if ($ad_info[0]['adv_supply'] == 'rubicon') {
            $_url = 'http://exchange.ads-optima.com/rtb/rubicon';
        } else if ($ad_info[0]['adv_supply'] == '2') {
            $test03 = new rtObject;
            if($test03){
                $tmp = $test03->isADs();
            }
            if($tmp !== false){
                //제이슨 타입으로 광고 정보 가져 오는 부분
                $rt_info = $test03->getADs();
            }
            if(!empty($rt_info)) {
                $ad_info[0]['network_adv_idx'] = 'rt';
                $_sendData['site']['id'] = $ad_info[0]['origin_areacd'];
                $_sendData['retarget'] = $rt_info['retarget'];
                $_url = 'http://dsp.adop.cc/serving/rt';
            }else {
                //ATOM 이미지
                $_sendData['site']['id'] = $ad_info[0]['origin_areacd'];
                $_url = 'https://atomtest.adop.cc/serving/z';
            }
        } else if ($ad_info[0]['adv_supply'] == '3') {
            //ATOM 텍스트
            $_sendData['slot']['board_idx'] = $ad_info[0]['pann_idx'];
            $_url = 'http://dsp.adop.cc/serving/t';
        } else if ($ad_info[0]['adv_supply'] == '4') { //Appier
            $_url = "http://exchange.ads-optima.com/rtb/appier";
        } else if ($ad_info[0]['adv_supply'] == '5') { //나스미디어
            $_url = "http://exchange.ads-optima.com/rtb/nasmedia";
        } else if ($ad_info[0]['adv_supply'] == '6') { //ATOM리타겟팅
            $ad_info[0]['network_adv_idx'] = 'rt';
            $_url = 'http://dsp.adop.cc/serving/rt2';
            $test03 = new rtObject;
            if($test03){
                $tmp = $test03->isADs();
            }
            if($tmp !== false){
                //제이슨 타입으로 광고 정보 가져 오는 부분
                $rt_info = $test03->getADs();
            }
            if(!empty($rt_info)) {
                $_sendData['site']['id'] = $ad_info[0]['origin_areacd'];
                $_sendData['retarget'] = $rt_info['retarget'];
            }
        } else if($ad_info[0]['adv_supply'] == '7') { //Moloco
            $_url = "http://exchange.ads-optima.com/rtb/moloco";
        }
        $_jsonData = json_encode($_sendData);
        if($protocol!= "http"){
            $_url = str_replace('http://','https://',$_url);
        }
        $html_code = Data_Connect_Curl_Json($_url, $_jsonData);

        if ($html_code == 'noads') {
            $_ResponseLogURL = LOGSERVER_HOST . "/compass/res?a=" . $ad_info[0]['adv_idx'] . "&z=" . $area_idx . "&adver_type=compass";
            Data_Connect_Curl_No_Return($_ResponseLogURL);
            $_ImpLogURL = LOGSERVER_HOST . "/compass/imp?a=" . $ad_info[0]['adv_idx'] . "&z=" . $area_idx . "&adver_type=compass";
            Data_Connect_Curl_No_Return($_ImpLogURL);
            $_fake_scr = '<script type="application/javascript">
/*
function label_on(cnt, c_chk){
        if (typeof parent.label_on  != "undefined") {
                parent.label_on(cnt, c_chk);
        }else if(typeof parent.parent.label_on  != "undefined"){
                parent.parent.label_on(cnt, c_chk);
        }else if(typeof parent.parent.parent.label_on  != "undefined"){
                parent.parent.parent.label_on(cnt, c_chk);
        }else if(typeof parent.parent.parent.parent.label_on  != "undefined"){
                parent.parent.parent.parent.label_on(cnt, c_chk);
        }
}
*/
label_on('.$ad_info[0]['imp'].', "'.$ad_info[0]['cfs_chk'].'");
</script>';
            $ifr_tag = sprintf("<iframe src='%s' frameborder='0' marginwidth='0' marginheight='0' paddingwidth='0' paddingheight='0' width='%s' height='%s' scrolling='no'></iframe>",$ad_info[0]['net_adv_passback'],'100%','100%');
            //$ifr_tag = = '<script src="'.$ad_info[0]['net_adv_passback'].'" ></script>'; 
            if($ad_info[0]['imp']==1) {
                if ($label_view!=false) {
                    $ad_info[0]['html_code'] = $ifr_tag;
                    echo2(net_label($ad_info));
                    exit;
                } else {
                    if($label_view_child!=false) {
                        echo "document.write('".$_fake_scr.$ifr_tag .$_fake_scr."');";
//                        echo $_fake_scr.$ifr_tag .$_fake_scr;
                    }else{
                        echo "document.write('".$ifr_tag."');";
//                        echo $ifr_tag;
                    }
                    exit;
                }
            }else{
                if($label_view_child!=false) {
                    echo "document.write('".$_fake_scr.$ifr_tag .$_fake_scr."');";
                    //                        echo $_fake_scr.$ifr_tag .$_fake_scr;
                }else{
                    echo "document.write('".$ifr_tag."');";
                    //                        echo $ifr_tag;
                }
                exit;
            }
        } else {
            $ad_info[0]['html_code'] = $html_code;
        }
    }
}
$_ResponseLogURL = LOGSERVER_HOST . "/compass/res?a=" . $ad_info[0]['adv_idx'] . "&z=" . $area_idx . "&adver_type=compass";
Data_Connect_Curl_No_Return($_ResponseLogURL);

$rmd_code = make_rmd_code();

$_fake_img = '<div style="position: absolute; left: -10000px; top: 0px; overflow: hidden; width:1px; height: 1px;">';
$_fake_img.= '<script src="https://log2.adop.cc/compass/imp?a=' . $ad_info[0]['adv_idx'] . '&z=' . $area_idx . '&adver_type=compass&r='.$rmd_code.'" style="width:1px; height:1px;"></script>';
$_fake_img.= '</div>';
$_fake_scr = '<script type="application/javascript">
/*
function label_on(cnt, c_chk){
        if (typeof parent.label_on  != "undefined") {
                parent.label_on(cnt, c_chk);
        }else if(typeof parent.parent.label_on  != "undefined"){
                parent.parent.label_on(cnt, c_chk);
        }else if(typeof parent.parent.parent.label_on  != "undefined"){
                parent.parent.parent.label_on(cnt, c_chk);
        }else if(typeof parent.parent.parent.parent.label_on  != "undefined"){
                parent.parent.parent.parent.label_on(cnt, c_chk);
        }
}
*/
label_on('.$ad_info[0]['imp'].', "'.$ad_info[0]['cfs_chk'].'");
</script>';
if($protocol!= "http"){
    $ad_info[0]['html_code'] = str_replace('http://','https://',$ad_info[0]['html_code']);
}

if($ad_info[0]['imp']==1 || $ad_info[0]['imp']==0) {
    if ($label_view!=false) {
        //echo net_label($ad_info) . $_fake_img;
        echo2((net_label($ad_info) . $_fake_img));
        
    } else {       
        if($label_view_child!=false) {
            //echo $_fake_scr . $ad_info[0]['html_code'] . $_fake_img . $_fake_scr;
            echo make_script2tag($_fake_scr ."\n". $ad_info[0]['html_code'] ."\n". $_fake_img ."\n". $_fake_scr);
        }else{
            //echo $ad_info[0]['html_code'] . $_fake_img;
            echo make_script2tag($ad_info[0]['html_code'] ."\n". $_fake_img);
        }
    }
}else{
    if($label_view_child!=false) {
        //echo $_fake_scr.$ad_info[0]['html_code'] . $_fake_img.$_fake_scr;
        echo make_script2tag($_fake_scr."\n".$ad_info[0]['html_code'] ."\n". $_fake_img.$_fake_scr);
    }else{
        //echo $ad_info[0]['html_code'] . $_fake_img;
        echo make_script2tag($ad_info[0]['html_code'] ."\n". $_fake_img);
    }

}

function net_label($ad_info){
    include "../AD_HTML/labelScript.php";
}

function echo2($strTmp){
    global $docHeadStart ;
    global $docHeadConts ;
    global $docHeadEnd   ;
    global $docBodyStart ;
    global $docBodyConts ;
    global $docBodyEnd   ;
    
    //문자열 조정  
    echo $docHeadStart;
    echo $docHeadConts;
    echo $docHeadEnd;
    echo $docBodyStart;
    echo $docBodyConts;
    echo $docBodyEnd;
    
    //echo "</script>"
}

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
