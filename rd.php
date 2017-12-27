<?php
/**
 * 공통 로드될 것들
 */
date_default_timezone_set('UTC');
include_once('../assets/lib/constant.php');
include_once('../assets/lib/common.php');
//include_once('./switch_list.php');
include_once ('./adopRTclass.php');
include_once ('/Data/ad_info/RT/adopRTdata.php');
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

/**
 * 수평 로직
 */
if($ad_info[0]['weight_direction'] == "h") {
    $loc = urldecode($_GET['loc']);

    if (isset($_GET['adop_bk'])) {
        foreach ($ad_info as $Key => $Val) {
            if ($Val['adv_idx'] == $_GET['adop_bk']) {
                unset($ad_info[$Key]);
            }
        }
        $ad_info = array_values($ad_info);
    }
    if (cfs_test($loc) == "Y") {
        foreach ($ad_info as $key => $row) {
            if ($row['network_adv_idx'] == 'f3bb08bb-b596-42ff-af87-6939846a5c8b' || $row['network_adv_idx'] == '41350b05-4415-44b2-8e17-b5fe52d1bd6e') {
                unset($ad_info[$key]);
            }
        }
        sort($ad_info);
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
            $imgpathname = 'http://compasscdn.adop.cc/adimage/' . $key;
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

        if ($ad_info[0]['adv_supply'] == '1') {
            $_url = 'http://exchange.ads-optima.com/rtb/bidswitch';
        } else if ($ad_info[0]['adv_supply'] == 'rubicon') {
            $_url = 'http://exchange.ads-optima.com/rtb/rubicon';
        } else if ($ad_info[0]['adv_supply'] == '2') {
	   $test03 = new rtObject;
            if($test03){
                $tmp = $test03->isADs();
            }
            if($tmp !== false){
                //$ad_info[0]['network_adv_idx'] = 'ca7e481c-fb59-41ab-81e5-7bc5ef62097d';
		$ad_info[0]['network_adv_idx'] = 'rt';
                //제이슨 타입으로 광고 정보 가져 오는 부분
                $rt_info = $test03->getADs();
            }
            if(!empty($rt_info)) {
                $_sendData['retarget'] = $rt_info['retarget'];
                $_url = 'http://13.124.54.58/serving/rt';
            }else {
		$_sendData['site']['id'] = $area_idx;
                //ATOM 이미지
                $_url = 'http://13.124.54.58/serving/z';
            }
        } else if ($ad_info[0]['adv_supply'] == '3') {
            //ATOM 텍스트
            $_sendData['slot']['board_idx'] = $ad_info[0]['pann_idx'];
            $_url = 'http://13.124.54.58/serving/t';
        } else if ($ad_info[0]['adv_supply'] == '4') { //Appier
            $_url = "http://exchange.ads-optima.com/rtb/appier";
        } else if ($ad_info[0]['adv_supply'] == '5') { //나스미디어
            $_url = "http://exchange.ads-optima.com/rtb/nasmedia";
	} else if ($ad_info[0]['adv_supply'] == '6') { //ATOM리타겟팅
            $test03 = new rtObject;
            if($test03){
                $tmp = $test03->isADs();
            }
            if($tmp !== false){
                //$ad_info[0]['network_adv_idx'] = 'ca7e481c-fb59-41ab-81e5-7bc5ef62097d';
                $ad_info[0]['network_adv_idx'] = 'rt';
                //제이슨 타입으로 광고 정보 가져 오는 부분
                $rt_info = $test03->getADs();
            }

            if(!empty($rt_info)) {
                $_sendData['retarget'] = $rt_info['retarget'];
                $_url = 'http://13.124.54.58/serving/rt2';
            }
	}

        $_jsonData = json_encode($_sendData);

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
    if($ad_info[0]['ad_weight'] != 100){
        $area_weight = $ad_info[0]['ad_weight'];
        $weight_rmd = rand(0,100);
        if($area_weight <= $weight_rmd ){
	    $rs_network = "fb992c43-6b84-11e7-8214-02c31b446301, 84efaf55-6426-44aa-9994-998dfe65c9d4, ea03d8dd-336c-4ad3-880a-8c4eb013a6b5";
            if(strpos($rs_network, $ad_info[0]['network_adv_idx'])!==false){
                $ad_info[0]['net_adv_passback'] = str_replace("RS","RD",$ad_info[0]['net_adv_passback']);
		$label_view_child = label_check();
                if($label_view_child){
                    //lv는 label_view
                    $ad_info[0]['net_adv_passback'] = $ad_info[0]['net_adv_passback']."?lv=y";
                }
            }
            redirect($ad_info[0]['net_adv_passback']);
        }
    }

    if ($ad_info[0]['ad_type'] == 1) { // ad network
if ($ad_info[0]['network_adv_idx'] == 'f3bb08bb-b596-42ff-af87-6939846a5c8b' || $ad_info[0]['network_adv_idx'] == '41350b05-4415-44b2-8e17-b5fe52d1bd6e' || $ad_info[0]['network_adv_idx'] == 'd5373708-ff0a-11e6-950e-02c31b446301') {
            if (cfs_test($loc) == "Y") {
	if (isset($_GET['pbu']) && $_GET['pbu']!="") {
                    redirect($_GET['pbu']);
                }else{
                    redirect($ad_info[0]['net_adv_passback']);
                }
            }else{
}
            //구글 패스백url있을경우
            if (isset($_GET['pbu'])) {
                if (strpos($ad_info[0]['html_code'], "google_ad_slot") !== FALSE) {
                    $ad_info[0]['html_code'] = google_PassBack_Replace_V($ad_info[0]['html_code'], $_GET['pbu']);
                }
            }

        }
        $ad_info[0]['html_code'] = str_replace('__adop_page_url__', $loc, $ad_info[0]['html_code']);
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
        if ($ad_info[0]['adv_supply'] == '1') {
            //$_url = 'http://exchange.ads-optima.com/rtb/bidswitch';
	    $_url = "http://exchange-elb-699622095.ap-northeast-2.elb.amazonaws.com/rtb/bidswitch";
        } else if ($ad_info[0]['adv_supply'] == 'rubicon') {
            $_url = 'http://exchange.ads-optima.com/rtb/rubicon';
        } else if ($ad_info[0]['adv_supply'] == '2') {
	     $test03 = new rtObject;
            if($test03){
                $tmp = $test03->isADs();
            }
            if($tmp !== false){
                //$ad_info[0]['network_adv_idx'] = 'ca7e481c-fb59-41ab-81e5-7bc5ef62097d';
		$ad_info[0]['network_adv_idx'] = 'rt';
                //제이슨 타입으로 광고 정보 가져 오는 부분
                $rt_info = $test03->getADs();
            }
            if(!empty($rt_info)) {
                $_sendData['retarget'] = $rt_info['retarget'];
                $_url = 'http://13.124.54.58/serving/rt';
            }else {
                //ATOM 이미지
		$_sendData['site']['id'] = $ad_info[0]['origin_areacd'];
                $_url = 'http://13.124.54.58/serving/z';
            }
        } else if ($ad_info[0]['adv_supply'] == '3') {
            //ATOM 텍스트
            $_sendData['slot']['board_idx'] = $ad_info[0]['pann_idx'];
            $_url = 'http://13.124.54.58/serving/t';
        } else if ($ad_info[0]['adv_supply'] == '4') { //Appier
            //$_url = "http://exchange.ads-optima.com/rtb/appier";
	    $_url = "http://exchange-elb-699622095.ap-northeast-2.elb.amazonaws.com/rtb/appier";
        } else if ($ad_info[0]['adv_supply'] == '5') { //나스미디어
            $_url = "http://exchange.ads-optima.com/rtb/nasmedia";
        } else if ($ad_info[0]['adv_supply'] == '6') { //ATOM리타겟팅
            $test03 = new rtObject;
            if($test03){
                $tmp = $test03->isADs();
            }
            if($tmp !== false){
                //$ad_info[0]['network_adv_idx'] = 'ca7e481c-fb59-41ab-81e5-7bc5ef62097d';
                $ad_info[0]['network_adv_idx'] = 'rt';
                //제이슨 타입으로 광고 정보 가져 오는 부분
                $rt_info = $test03->getADs();
            }

            if(!empty($rt_info)) {
                $_sendData['retarget'] = $rt_info['retarget'];
            }
	    //$_url = 'http://dsp.adop.cc/serving/rt2';
	   $_url = 'http://13.124.54.58/serving/rt2';
	}
        $_jsonData = json_encode($_sendData);
        $html_code = Data_Connect_Curl_Json($_url, $_jsonData);
        if ($html_code == 'noads') {
            $_ResponseLogURL = LOGSERVER_HOST . "/compass/res?a=" . $ad_info[0]['adv_idx'] . "&z=" . $area_idx . "&adver_type=compass";
            Data_Connect_Curl_No_Return($_ResponseLogURL);
            $_ImpLogURL = LOGSERVER_HOST . "/compass/imp?a=" . $ad_info[0]['adv_idx'] . "&z=" . $area_idx . "&adver_type=compass";
            Data_Connect_Curl_No_Return($_ImpLogURL);
            $labelTop     = $ad_info[0]['imp'] * 18 - 18;
            $labelTmp = "<div style='position:absolute;top:$labelTop;left:0;right:0;bottom:0;width:60px;height:15px;padding-top:3px;font-size:9pt;font-family:Sans-Serif;text-align:center;z-index:9999;background:rgba(10,50,100,1);color:#fff;\'>ATOM</div>";
            $ifr_tag = sprintf("<iframe src='%s' frameborder='0' marginwidth='0' marginheight='0' paddingwidth='0' paddingheight='0' width='%s' height='%s' scrolling='no'>",$ad_info[0]['net_adv_passback'],'100%','100%');
            $label_view = label_check();
            if($label_view) {
                echo $labelTmp . $ifr_tag;
            }else{
                echo $ifr_tag;
            }
            exit;
//            redirect($ad_info[0]['net_adv_passback']);
        } else {
            $ad_info[0]['html_code'] = $html_code;
        }
    }
}
$_ResponseLogURL = LOGSERVER_HOST . "/compass/res?a=" . $ad_info[0]['adv_idx'] . "&z=" . $area_idx . "&adver_type=compass";
Data_Connect_Curl_No_Return($_ResponseLogURL);

$rmd_code = make_rmd_code();
$label_view = label_check();

$_fake_img = '<div style="position: absolute; left: -10000px; top: 0px; overflow: hidden; width:1px; height: 1px;">';
$_fake_img.= '<script src="https://log2.adop.cc/compass/imp?a=' . $ad_info[0]['adv_idx'] . '&z=' . $area_idx . '&adver_type=compass&r='.$rmd_code.'" style="width:1px; height:1px;"></script>';
$_fake_img.= '<script>
var jb_w=0,jb_h=0;
jb_w = window.innerWidth;
jb_h = window.innerHeight;

if(jb_w >= 1000 && jb_h >= 700){
   var oHead = document.getElementsByTagName(\'HEAD\').item(0);
   var oScript = document.createElement("script");
       oScript.type  = "text/javascript";
       oScript.async = true;
       oScript.src   = "http://13.124.9.60/iframe?a=' . $ad_info[0]['adv_idx'] . '&z=' . $area_idx . '&r='.$rmd_code.'&loc='.$_SERVER['HTTP_REFERER'].'";
       oHead.appendChild(oScript);
}
</script>';
$_fake_img.= '</div>';
if($protocol!= "http"){
    $ad_info[0]['html_code'] = str_replace('http://','https://',$ad_info[0]['html_code']);
}
if( $label_view ) {
//    echo ADOP_LABEL.$ad_info[0]['html_code'].$_fake_img;
	echo net_label().$ad_info[0]['html_code'].$_fake_img;
}else{
    echo $ad_info[0]['html_code'].$_fake_img;
}

function net_label(){
	global 	$ad_info;
	$labelTop = 0;
	$labelWidth = 60;
	$labelBgcolor = "rgba(255,0,0,0.7)";
	$labelNm      = "ADOP AD";
	$labelTmp = "<div style='position:absolute;top:$labelTop;left:0;right:0;bottom:0;width:{$labelWidth}px;height:15px;padding-top:3px;font-size:9pt;font-family:Sans-Serif;text-align:center;z-index:9999;background:$labelBgcolor;color:#fff;\'>$labelNm</div>";
	$labelTmp2 = "<div style='position:absolute;top:0;left:0;right:0;bottom:0;width:60px;height:15px;padding-top:3px;font-size:9pt;font-family:Sans-Serif;text-align:center;z-index:9999;background:rgba(255,0,0,0.7);color:#fff;\'>ADOP AD</div>";
	$netInfo = array();
	$netInfo['976f7bf5-7445-464d-a509-450bd81a5de9'] = array('리얼클릭','rgba(150,150,0,1)',60);
	$netInfo['b07341be-50ba-4710-975c-af033f0abf51'] = array('Criteo','rgba(0,0,255,1)',60);
	$netInfo['5d0d57c2-11db-4ef1-b6b7-ae0fca302a63'] = array('카울리','rgba(255,0,0,1)',60);
	$netInfo['84efaf55-6426-44aa-9994-998dfe65c9d4'] = array('와이더플래닛','rgba(0,150,150,1)',68);
	$netInfo['f3bb08bb-b596-42ff-af87-6939846a5c8b'] = array('Google AdSense','rgba(0,200,0,1)',95);
	$netInfo['23f5ac5c-b46d-40ee-abea-dcbdba2da240'] = array('쏠스펙트럼','rgba(255,0,0,1)',60);
	$netInfo['3b401a74-d03a-4710-9750-c5d59d211c68'] = array('ADOP 환전대행','rgba(255,0,0,1)',60);
	$netInfo['721a916a-32f6-487f-aff2-3fa47cf1138e'] = array('AFS (검색용)','rgba(255,0,0,1)',60);
	$netInfo['aaf27b2b-1e01-4992-8a42-1a2fb5df02d8'] = array('LG U+','rgba(255,0,0,1)',60);
	$netInfo['314276d2-f14e-414d-ab90-202c2d7fb34a'] = array('YouTube','rgba(255,0,0,1)',60);
	$netInfo['f37249e9-23b9-4749-96f2-445307eef825'] = array('기타','rgba(255,0,0,1)',60);
	$netInfo['9273d809-02d0-474a-a578-97137270ddcd'] = array('와이즈넛','rgba(150,0,150,1)',60);
	$netInfo['750b01bf-dfa3-40f1-8c4c-e135d2d38588'] = array('N2S','rgba(50,100,100,1)',60);
	$netInfo['e4f26272-9cf2-4d16-ba88-7e903b34edb1'] = array('인터랙티비','rgba(100,50,50,1)',60);
	$netInfo['5e0ce4ec-57ec-4dbd-9703-dc330d027c40'] = array('DDN','rgba(255,0,0,1)',60);
	$netInfo['41350b05-4415-44b2-8e17-b5fe52d1bd6e'] = array('Google adx','rgba(150,0,150,1)',65);
	$netInfo['1b54a44c-9735-4890-ace7-ec4b1555dad4'] = array('엔트리','rgba(255,0,0,1)',60);
	$netInfo['b0667bf2-e4c3-4ddf-92ef-b2197e615a1f'] = array('크리테오(sg)','rgba(20,120,130,1)',60);
	$netInfo['21e6eb8c-8174-4768-a631-f6974cbd13c8'] = array('Smaato','rgba(255,0,0,1)',60);
	$netInfo['342bcb11-5c11-4f32-86a2-1451269a8547'] = array('United','rgba(255,0,0,1)',60);
	$netInfo['ae03975d-720c-499e-b460-22c49a4202c3'] = array('Criteo_P','rgba(30,90,30,1)',60);
	$netInfo['9e958f37-6173-4a19-8512-962e11de4c6b'] = array('싱크미디어','rgba(255,0,0,1)',60);
	$netInfo['8230a4bf-022e-4717-abe4-2f6ad44486d0'] = array('비디올로지','rgba(255,0,0,1)',60);
	$netInfo['c11ccf68-a339-495f-a97c-e92df60b42b6'] = array('윌름모바일 코리아','rgba(255,0,0,1)',60);
	$netInfo['6cb439f3-9f2a-4f92-9cbc-8f3bf8b154eb'] = array('리얼클릭_앱(레몬)','rgba(255,0,0,1)',60);
	$netInfo['2da616c3-a376-4f1d-ab5e-2bb4ea5b5f2c'] = array('시럽애드','rgba(255,0,0,1)',60);
	$netInfo['7f4a256b-dc4b-4180-af19-6aaedfd66f54'] = array('비져리','rgba(255,0,0,1)',60);
	$netInfo['d62fb381-1d66-455b-a788-462bed3d484d'] = array('에이치엔에스','rgba(255,0,0,1)',60);
	$netInfo['02db844b-636d-4052-9120-39c06c1fbe0e'] = array('애드나비','rgba(255,0,0,1)',60);
	$netInfo['f32a2be4-476b-4ddb-85dc-27e98e81e111'] = array('인모비','rgba(255,0,0,1)',60);
	$netInfo['b5d47932-a5bf-4845-9945-4f5f3a8acd23'] = array('TNK','rgba(255,0,0,1)',60);
	$netInfo['ca7e481c-fb59-41ab-81e5-7bc5ef62097d'] = array('ADOP','rgba(10,50,100,1)',60);
	$netInfo['1d53f36a-b433-4043-a620-ef34a89c2679'] = array('토스트','rgba(20,100,150,1)',40);
	$netInfo['251e1f6d-2f5d-4e18-b9b5-6ec488cc8e18'] = array('루미너스','rgba(150,100,2,1)',50);
	$netInfo['c881c6c5-f8d7-4588-8f41-2ddf0c62c6c6'] = array('네오클릭','rgba(255,0,0,1)',60);
	$netInfo['92c17540-f492-476c-985e-92e9ab723bcb'] = array('Google AdX (adop)','rgba(255,0,0,1)',60);
	$netInfo['66b2049b-67ef-498b-8e16-969f41cb4c49'] = array('미디어믹서','rgba(255,0,0,1)',60);
	$netInfo['9ab5ec19-781e-4403-adf3-16e20a342df1'] = array('ADOP Video Ads','rgba(255,0,0,1)',60);
	$netInfo['97500d9a-1313-4ab6-988d-3723d51eb2e1'] = array('SpotXchange','rgba(255,0,0,1)',60);
	$netInfo['b3b110de-9c00-4bd2-9fd8-86e5317e6bb7'] = array('인터웍스','rgba(100,50,10,1)',60);
	$netInfo['4c000510-5ce5-408b-8a09-952a55cc6c56'] = array('Adstars','rgba(255,0,0,1)',60);
	$netInfo['6b3f6dcc-8daa-4ff2-a197-217ce7a88017'] = array('애드베이','rgba(255,0,0,1)',60);
	$netInfo['e74d33b8-517a-4d8c-a494-49a5f68a2ff9'] = array('비드스위치','rgba(255,0,0,1)',60);
	$netInfo['e8bde12d-a059-4acb-8dcb-3fe863184cc8'] = array('메조미디어','rgba(255,0,0,1)',60);
	$netInfo['600a0349-ce1e-4a41-bb1b-bc31fd0c6e71'] = array('Taptica','rgba(255,0,0,1)',60);
	$netInfo['a2fc1913-7624-4d2e-affb-1159b4a8e54b'] = array('Rubicon','rgba(100,60,30,1)',60);
	$netInfo['6b5952af-0b87-4658-99ab-390ffffa7717'] = array('Adstir','rgba(30,60,100,1)',60);
	$netInfo['f6f26df7-ec5d-45b5-a649-53b287c88ee7'] = array('ADOP RTB','rgba(255,0,0,1)',60);
	$netInfo['rt'] = array('ADOP Retarget','rgba(255,0,0,1)',90);
	$netInfo['b0f02bd1-0ea5-4140-bda4-3baecda06a18'] = array('엠클라우드','rgba(30,60,90,1)',60);
	$netInfo['6b18f0ad-af2b-4dc3-88c1-ecce83c7730b'] = array('클릭몬','rgba(255,0,0,1)',60);
	$netInfo['7f10a2d6-ea4e-425a-83e4-2b5676f9a54e'] = array('OpenX','rgba(255,0,0,1)',60);
	$netInfo['ea03d8dd-336c-4ad3-880a-8c4eb013a6b5'] = array('Mobfox','rgba(255,0,0,1)',60);
	$netInfo['495f460e-a66e-4dde-9b6c-98ec16bcbd0d'] = array('보정금액','rgba(255,0,0,1)',60);
	$netInfo['d98f2b6b-d79c-11e6-950e-02c31b446301'] = array('Appier','rgba(30,90,60,1)',60);
	$netInfo['aeb913d4-d95f-11e6-950e-02c31b446301'] = array('리얼ssp','rgba(90,30,30,1)',60);

	//네트워크키값 확인
	if(isset($netInfo[$ad_info[0]['network_adv_idx']])){
		$labelTop     = $ad_info[0]['imp'] * 18 - 18;
        if ($labelTop < 0){
            $labelTop = 0;
        }
		$labelWidth   = $netInfo[$ad_info[0]['network_adv_idx']][2];
		$labelBgcolor = $netInfo[$ad_info[0]['network_adv_idx']][1];
		$labelNm      = $netInfo[$ad_info[0]['network_adv_idx']][0];
        if (isset($_GET['trans']) && $_GET['trans']=="no") {
            $labelTmp = "<div style='position:absolute;top:0;left:0;right:0;bottom:0;width:16px;height:15px;padding-top:3px;font-size:9pt;font-family:Sans-Serif;text-align:center;z-index:9999;background:rgba(255,0,0,1);color:#fff;\'>OP</div>";
            $labelTmp.= "<div style='position:absolute;top:$labelTop;left:16px;right:0;bottom:0;width:{$labelWidth}px;height:15px;padding-top:3px;font-size:9pt;font-family:Sans-Serif;text-align:center;z-index:9999;background:$labelBgcolor;color:#fff;\'>$labelNm</div>";
        }else{
            $labelTmp = "<div style='position:absolute;top:$labelTop;left:0;right:0;bottom:0;width:{$labelWidth}px;height:15px;padding-top:3px;font-size:9pt;font-family:Sans-Serif;text-align:center;z-index:9999;background:$labelBgcolor;color:#fff;\'>$labelNm</div>";
        }
		return $labelTmp;
	}
	return $labelTmp;
}
