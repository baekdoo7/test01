<?php
/**
 * Created by IntelliJ IDEA.
 * User: baekdoo
 * Date: 2018. 9. 18.
 * Time: PM 5:56
 * 오리지널 버전에서 page_url을 처리하기 위해
 * 수정됨
 * https://support.google.com/admanager/answer/4359047?hl=ko
 */


ob_start();

//메모리사이즈와 실행시간 프리...
ini_set("max_execution_time",6000);
ini_set("memory_limit",-1);

//전역변수 설정
//$dbInfo     = array("localhost","root","root","adop_test");
$dbInfo     = array("compass-cluster.cluster-cnzcxy7quzcs.ap-northeast-2.rds.amazonaws.com","adopadmin","Adop*^14","platon");

//DB 연결변수
$connGlobal = null;

//***사전 함수정리***
//시간 체크를 위한 함수
function get_time() {
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}
//글로벌 DB 컨넥트
function dbConnect(){
    global $dbInfo,$connGlobal;

    list($hostUrl,$user,$pwd,$dbIns) = $dbInfo;
    $connGlobal=new mysqli($hostUrl,$user,$pwd,$dbIns);
    if($connGlobal->connect_error){
        die("Connection failed: " . $connGlobal->connect_error);
    }
    $connGlobal->set_charset("utf8");
}
function dbDisConnect(){
    global $connGlobal;


    $connGlobal->close();

}

//변경대상인지 체크 로직
function chkGoogleAd($str){
    $ptn01 = "/google_ad_client/i";
    $ptn02 = "/ca-pub-1474238860523410/i";
    $ptn03 = "/ca-pub-5111137191506013/i";

    $matching1 = preg_match($ptn01,$str);
    $matching2 = preg_match($ptn02,$str);
    $matching3 = preg_match($ptn03,$str);

//echo $matching1."/".$matching2."/".$matching3."/";

    if($matching1 + $matching2 + $matching3 == 2){
        return true;
    }else{
        return false;
    }
}
//사이트 url정보 뽑아오기
function getSiteInfo($advIdx){
    global $connGlobal;
    $retUrl = "";

    $readDataQuery = "select * from i_site_info_tmp where adv_idx = '".$advIdx."'";
    $resultSet     = $connGlobal->query($readDataQuery);
    $row = $resultSet->fetch_assoc();

    if(is_null($row['area_platform'])){
        $retUrl = "compass.adop.cc";
    }
    elseif($row['area_platform'] == 'M'){
        $retUrl = $row['site_mobile_url'];
    }
    else{
        $retUrl = $row['site_web_url'];
    }

    if(empty($retUrl)){
        $retUrl = "compass.adop.cc";
    }

    $retUrl = preg_replace("(^https?://)", "", $retUrl );
    $retUrl = preg_replace("(/+$\s*)", "", $retUrl );

    return $retUrl;

}
//구글 정보 뽑아오기
function getGoogleAd($str,$advIdx){

    $obj = (object)array("error"=>true);

    $cnt = preg_match('/google_ad_client\s*=\s*\"(.*?)\"\s*;/i',$str,$tmp);
    if($cnt){
        $obj->pub = $tmp[1];
    }
    else{
        return $obj;
    }
    $cnt = preg_match('/google_ad_slot\s*=\s*\"(.*?)\"\s*;/i',$str,$tmp);
    if($cnt){
        $obj->slot = $tmp[1];
    }
    else{
        return $obj;
    }
    $cnt = preg_match('/google_page_url\s*=\s*\"(.*?)\"\s*;/i',$str,$tmp);
    if($cnt){
        $obj->pageUrl =  preg_replace("(^https?://)", "", $tmp[1] );
        $obj->pageUrl =  preg_replace("(/+\s*$)", "", $obj->pageUrl );
    }
    else{
        $obj->pageUrl = getSiteInfo($advIdx);
    }

    $cnt = preg_match('/google_ad_width\s*=\s*(.*?)\s*;/i',$str,$tmp);
    if($cnt){
        $obj->w = $tmp[1];
    }
    else{
        return $obj;
    }
    $cnt = preg_match('/google_ad_height\s*=\s*(.*?)\s*;/i',$str,$tmp);
    if($cnt){
        $obj->h = $tmp[1];
    }
    else{
        return $obj;
    }
    $cnt = preg_match('/google_alternate_ad_url\s*=\s*\"(.*?)\";/i',$str,$tmp);
    if($cnt && $tmp[1] <> ""){
        $obj->passback = $tmp[1];
    }else{
        $obj->passback = "ADOP_PBU";
    }

    if($obj->pub == 'ca-pub-1474238860523410'){
        $obj->fkey = '5932629';
    }elseif ($obj->pub == 'ca-mb-app-pub-1474238860523410'){
        $obj->fkey = '5932629';
    }elseif ($obj->pub == 'video-pub-1474238860523410'){
        $obj->fkey = '5932629';
    }elseif ($obj->pub == 'ca-pub-5111137191506013'){
        $obj->fkey = '223513049';
    }

    $obj->error = false;
    return $obj;
}
//랜덤 문자열
function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
//템플릿 처리
function makeTemplete($obj){

    $width  = 300;
    $height = 250;
    $areaId = 'div-gpt-ad-1530613899473-0';
    $googleCd = '/5932629/ca-pub-1474238860523410-tag/1461757438';
    $passback = '';

    $width    = $obj->w;
    $height   = $obj->h;
    $areaId   = 'div-apt-ad-'.generateRandomString();
    $pageUrl  = $obj->pageUrl;
    $googleCd = '/'.$obj->fkey.'/'.$obj->pub.'-tag/'.$obj->slot;

    $cnt = preg_match('/(.{8}-.{4}-.{4}-.{4}-.{12})/i',$obj->passback,$tmp);
    echo $obj->passback ."<br />\n";
    if($cnt){
        $passback = $tmp[1];
    }else{

        $passback = $obj->passback;
    }


    $scriptTmp  = "<script async='async' src='https://www.googletagservices.com/tag/js/gpt.js'></script>"."\n";
    $scriptTmp .= "<script>"."\n";
    //$scriptTmp .=  "var adoptagdfp = \"".base64_encode("eval(function(p,a,c,k,e,d){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--){d[e(c)]=k[c]||e(c)}k=[function(e){return d[e]}];e=function(){return'\\\\w+'};c=1};while(c--){if(k[c]){p=p.replace(new RegExp('\\\\b'+e(c)+'\\\\b','g'),k[c])}}return p}('5 4=4||{};4.9=4.9||[];5 8=8||v(a,c,e,b){5 f=\"//u.t.s/w/\"+b,d=\"x\"+6.r(z*6.y())+1;b=3.B(\"o\");b.2(\"j\",d);b.2(\"i\",\"k\");b.2(\"q\",0);b.2(\"p\",0);b.2(\"l\",0);b.2(\"n\",0);b.2(\"m\",0);b.2(\"A\",c);b.2(\"G\",e);b.2(\"C\",\"P\");c=\"<h S=\\'\"+f+\"\\'>R/h>\";U(a=3.7(a)){V(a.2(\"T\",\"N\");a.O();)a.F(a.E);a.D(b)}H I g;a=3.7(d);a=a.M||a.L.3;g!=a&&(a.K(),a.J(c),a.Q())};',58,58,'||setAttribute|document|googletag|var|Math|getElementById|adopADshow|cmd|||||||null|script|border|id|none|marginHeight|paddingHeight|paddingWidth|IFRAME|marginWidth|frameBorder|floor|cc|adop|compass|function|RE|adopB|random|1E4|width|createElement|scrolling|appendChild|firstChild|removeChild|height|else|return|write|open|contentWindow|contentDocument|adsbyadop_|hasChildNodes|no|close|<|src|class|if|for'.split('|'),0,{}));")."\"\n";
    $scriptTmp .=  "var adoptagdfp = \"".base64_encode("eval(function(p,a,c,k,e,d){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--){d[e(c)]=k[c]||e(c)}k=[function(e){return d[e]}];e=function(){return'\\\\w+'};c=1};while(c--){if(k[c]){p=p.replace(new RegExp('\\\\b'+e(c)+'\\\\b','g'),k[c])}}return p}('4 5=5||{};5.l=5.l||[];4 m=m||C(c,d,g,b){4 f=!0,h=\"//B.F.o/q/\"+b,e=\"H\"+i.A(y*i.D())+1;-1!=b.G(\"//\")&&(f=!1);4 a=3.E(\"x\");a.2(\"w\",e);a.2(\"p\",\"n\");a.2(\"r\",0);a.2(\"s\",0);a.2(\"v\",0);a.2(\"u\",0);a.2(\"t\",0);a.2(\"z\",d);a.2(\"Z\",g);a.2(\"X\",\"I\");d=\"<9 k=\'\"+h+\"\'>V/9>\";j(c=3.6(c)){U(c.2(\"Y\",\"W\");c.S();)c.M(c.T);c.L(a)}K 8 7;j(\"J\"==b)8 7;f?(a=3.6(e),b=a.N||a.O.3,7!=b&&(b.R(),b.Q(d),b.P())):(a=3.6(e),a.k=b)};',62,62,'||setAttribute|document|var|googletag|getElementById|null|return|script|||||||||Math|if|src|cmd|adopADshow|none|cc|border|RE|frameBorder|marginWidth|paddingHeight|paddingWidth|marginHeight|id|IFRAME|1E4|width|floor|compass|function|random|createElement|adop|indexOf|adopB|no|ADOP_PBU|else|appendChild|removeChild|contentDocument|contentWindow|close|write|open|hasChildNodes|firstChild|for|<|adsbyadop_|scrolling|class|height'.split('|'),0,{}));")."\"\n";
    $scriptTmp .= "eval(atob(adoptagdfp));"."\n";
    $scriptTmp .= "</script>"."\n";
    $scriptTmp .= ""."\n";
    $scriptTmp .= "<script>"."\n";
    $scriptTmp .= "  googletag.cmd.push(function() {"."\n";
    $scriptTmp .= "    googletag.defineSlot('@googleCd', [[@width,@height]], '@areaId').addService(googletag.pubads());"."\n";
    $scriptTmp .= "    googletag.pubads().enableSingleRequest();"."\n";
    $scriptTmp .= "    googletag.pubads().set(\"page_url\", \"@pageUrl\");"."\n";
    $scriptTmp .= "    googletag.pubads().addEventListener('slotRenderEnded', function(event) {"."\n";
    $scriptTmp .= "    if(event.isEmpty){"."\n";
    $scriptTmp .= "        adopADshow('@areaId',@width,@height,'@passback');   "."\n";
    $scriptTmp .= "    }"."\n";
    $scriptTmp .= "});"."\n";
    $scriptTmp .= "    googletag.enableServices();"."\n";
    $scriptTmp .= "  });"."\n";
    $scriptTmp .= "</script>"."\n";
    $scriptTmp .= "<div id='@areaId'>"."\n";
    $scriptTmp .= "<script>"."\n";
    $scriptTmp .= "googletag.cmd.push(function() { googletag.display('@areaId'); });"."\n";
    $scriptTmp .= "</script>"."\n";
    $scriptTmp .= "</div>"."\n";
    $scriptTmp .= ""."\n";
    $scriptTmp .= ""."\n";

    //템플릿 적용

    $scriptTmp = str_replace('@width',$width,$scriptTmp);
    $scriptTmp = str_replace('@height',$height,$scriptTmp);
    $scriptTmp = str_replace('@areaId',$areaId,$scriptTmp);
    $scriptTmp = str_replace('@googleCd',$googleCd,$scriptTmp);
    $scriptTmp = str_replace('@passback',$passback,$scriptTmp);
    $scriptTmp = str_replace('@pageUrl',$pageUrl,$scriptTmp);


    return $scriptTmp;
    //echo $scriptTmp;




}
//데이터 변경 시작
function runConvert(){
    ob_start();
    global $connGlobal;
    $count01 = 0;


    //$readDataQuery = "select * from advertise_ad2 limit 10";
    //$readDataQuery = "select * from advertise_ad2 where com_idx = 'a252ab7a-3306-4038-9475-3fb5001e4855' and network_adv_idx in ('d5373708-ff0a-11e6-950e-02c31b446301','41350b05-4415-44b2-8e17-b5fe52d1bd6e','d5373708-ff0a-11e6-950e-02c31b446301') and adv_idx ='4c4d07ed-091f-4d59-98ec-4879f3cb68e9'";
    $readDataQuery = "select * from advertise_ad2 where com_idx = 'a252ab7a-3306-4038-9475-3fb5001e4855' and network_adv_idx  = '41350b05-4415-44b2-8e17-b5fe52d1bd6e' ";
    $resultSet     = $connGlobal->query($readDataQuery);

    while($row = $resultSet->fetch_assoc()){
        $count01++;

       //echo $row["adv_idx"] ."/".chkGoogleAd($row["html_code"])."<br />";
       if(chkGoogleAd($row["html_code"])){
           $tmp = getGoogleAd($row["html_code"],$row["adv_idx"]);
           //echo $row["adv_idx"]."/".$tmp->error."<br />\n";
           if(!$tmp->error){
               $htmCode = makeTemplete($tmp);
               $updateQuery = "UPDATE advertise_ad2 SET html_code=\"".addslashes($htmCode)."\",network_adv_idx=\"9dbc26fa-bc8f-11e8-bbc3-02c31b446301\",net_adv_param01=\"".$tmp->fkey."\",net_adv_param02=\"".$tmp->pub."-tag\",net_adv_param03=\"".$tmp->slot."\" WHERE adv_idx=\"".$row["adv_idx"]."\"";
               //echo $updateQuery;
               $connGlobal->query($updateQuery);

               //$updateQuery2 = "UPDATE inventory_area_ad iaa INNER JOIN inventory_area2 ia2 ON iaa.area_idx = ia2.area_idx SET ia2.area_type = '9dbc26fa-bc8f-11e8-bbc3-02c31b446301' WHERE iaa.adv_idx = '".$row["adv_idx"]."'";
               $updateQuery2 = "UPDATE inventory_area2 ia2,(SELECT origin_areacd FROM inventory_area_ad iaa INNER JOIN inventory_area2 ia2 ON iaa.area_idx = ia2.area_idx WHERE iaa.adv_idx = '".$row["adv_idx"]."') tmp SET ia2.area_type = '9dbc26fa-bc8f-11e8-bbc3-02c31b446301' WHERE ia2.origin_areacd = tmp.origin_areacd  AND ia2.area_type = '41350b05-4415-44b2-8e17-b5fe52d1bd6e';";
//echo    $updateQuery2;
               $connGlobal->query($updateQuery2);
           }
           //echo $row["adv_idx"] ."/".$tmp->fkey. "/".$tmp->pub."/".$tmp->slot."/".$tmp->w."/".$tmp->h."/".$tmp->passback."/".$tmp->error ."<br />";
       }
       if($count01%100 == 0){
           //exit();
           echo $count01."<br />\n";
           ob_flush();
           flush();

       }
    }


}

//

//프로그램 스타트
$time01 = get_time();
dbConnect();
runConvert();
//echo addslashes("12\'34");

dbDisConnect();
echo "<hr />";
echo "전체 진행 시간 : ".(get_time() - $time01)."\n";

//echo generateRandomString();

//$tmp = getGoogleAd('1234');


/*
$obj =(object)array();
$obj->w         = 300;
$obj->h         = 250;
$obj->fkey      = '5932629';
$obj->pub       = 'ca-pub-1474238860523410';
$obj->slot      = '1461757438';
$obj->passback  = 'http://compass.adop.cc/RD/0c319ab4-905d-4a1f-b9a9-e2b662673e8b';

makeTemplete($obj);

*/

?>

