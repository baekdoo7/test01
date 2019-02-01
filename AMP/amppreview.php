<?php
/**
 * Created by IntelliJ IDEA.
 * User: baekdoo
 * Date: 2018. 11. 5.
 * Time: PM 4:32
 */

$mainDomain     = '' ;
$ampTitle       = '' ;
$ampContents    = '' ;
$ampContents2   = '' ;
$ampImage       = '' ;
$ampImageWidth  = 0  ;
$ampImageHeight = 0  ;
$ampLogo        = '' ;
$ampLogoback    = '' ;
$wroteDate      = '' ;
$writer         = '' ;
$siteUrl        = '' ;
$html           = '' ;
$keywordRank    = array();



include("simple_html_dom.php");
include("siteTagInfo.php");


function getKeyWord($str){
    global $siteInfo,$mainDomain;
    $retArray = array();
    $noKeyword = array("을 ","를 ","은 ","는 ","이 ","가 ","에게 ","에서 ","로 ","대로 ","의 ");
    $noKeyword = array_merge($noKeyword,$siteInfo[$mainDomain]['deletekeyword']);

var_dump($noKeyword);
exit();
    $tmp01 = preg_replace("/[ #\&\+\-%@=\/\:;,\.'\"\^`~\_|\!\?\*$#<>()\[\]\{\}]/i"," ",$str);
    $tmp01 = preg_replace("/[\s]+/i"," ", $tmp01);
    $tmp01 = str_replace($noKeyword," ",$tmp01);


    $tmp01 = preg_split("/[\s]/i", $tmp01);
    $tmp02 = array_count_values($tmp01);

    foreach ($tmp02 as $key => $val){
        $tmp02[$key] = mb_strlen($key) * 0.5 * $val;
    }

    arsort($tmp02);
    foreach ($tmp02 as $key => $val){
        array_push($retArray,$key);
    }
    return $retArray;

}
function rel2abs($rel, $base){
    if (parse_url($rel, PHP_URL_SCHEME) != '') return $rel;
    if ($rel[0]=='#' || $rel[0]=='?') return $base.$rel;
    extract(parse_url($base));
    $path = preg_replace('#/[^/]*$#', '', $path);
    if ($rel[0] == '/') $path = '';
    $abs = "$host$path/$rel";
    $re = array('#(/\.?/)#', '#/(?!\.\.)[^/]+/\.\./#');
    for($n=1; $n>0;$abs=preg_replace($re,'/', $abs,-1,$n)){}
    $abs=str_replace("../","",$abs);
    return $scheme.'://'.$abs;
}
function perfect_url($u,$b){
    $bp=parse_url($b);
    if(($bp['path']!="/" && $bp['path']!="") || $bp['path']==''){
        if($bp['scheme']==""){$scheme="http";}else{$scheme=$bp['scheme'];}
        $b=$scheme."://".$bp['host']."/";
    }
    if(substr($u,0,2)=="//"){
        $u="http:".$u;
    }
    if(substr($u,0,4)!="http"){
        $u=rel2abs($u,$b);
    }
    return $u;
}
//domain read
function getDomainName($url)
{
 $parse = parse_url($url);
 return $parse['host'];
}
//read from element
function getFromElement($ele,$tar,$ord=0){
    return $ele->find($tar,$ord);
}
//read from pattern
function getFromPattern($txt,$ptn){
    $cnt = preg_match($ptn,$txt,$tmp);
    if($cnt){
       return $tmp[1];
    }

    return "No Matching!";


}
//title read
function getTitle(){
    global $html,$ampTitle,$siteInfo,$mainDomain;
    if($siteInfo[$mainDomain]['title'][0] == 1) {
        $ampTitle = getFromElement($html, $siteInfo[$mainDomain]['title'][1])->plaintext;
    }
    elseif ($siteInfo[$mainDomain]['title'][0] == 2){
        $ampTitle = getFromPattern($html,$siteInfo[$mainDomain]['title'][1])    ;
    }
    else{
        $ampTitle = 'No Setting!';
    }
}

//remove prohibited tag
function removeAmptag($tmp){
    global $siteInfo,$mainDomain;

    $noamptag = array('script','base','img','video','audio','iframe','frame','frameset','object','param','applet','form','style','ins','table');
    $noamptag = array_merge($noamptag,$siteInfo[$mainDomain]['deletetag']);
    foreach ($noamptag as $notag) {
        foreach ($tmp->find($notag) as $t1) {
            $t1->outertext = '';
        }
    }
    return $tmp;
}
//get image
function getImage(){
    global $html,$ampImage,$siteUrl,$siteInfo,$mainDomain;

    if($siteInfo[$mainDomain]['image'][0] == 1) {
        $ampImage = perfect_url(getFromElement($html, $siteInfo[$mainDomain]['image'][1])->src,$siteUrl);
    }
    elseif ($siteInfo[$mainDomain]['image'][0] == 2){
        $ampImage = perfect_url(getFromPattern($html,$siteInfo[$mainDomain]['image'][1]),$siteUrl)    ;
    }
    elseif ($siteInfo[$mainDomain]['image'][0] == 3){
        $ampImage = perfect_url(getFromElement($html, $siteInfo[$mainDomain]['image'][1])->getAttribute($siteInfo[$mainDomain]['image'][2]),$siteUrl);;
    }
    else{
        $ampImage = 'No Setting!';
    }

}
function getImageSizeV(){
    global $ampImageWidth,$ampImageHeight,$ampImage;

    $info = getimagesize($ampImage);
    if($info == false){
        $ampImage = str_replace("http://","https://",$ampImage);
        $info = getimagesize($ampImage);

    }

    $ampImageWidth  = $info[0];
    $ampImageHeight = $info[1];
}
//get wrote date
function getWroteDate(){
    global $html,$wroteDate,$siteUrl,$siteInfo,$mainDomain;

    if($siteInfo[$mainDomain]['wrotedate'][0] == 1) {
        $wroteDate = getFromElement($html, $siteInfo[$mainDomain]['wrotedate'][1])->plaintext;
    }
    elseif ($siteInfo[$mainDomain]['wrotedate'][0] == 2){
        $wroteDate = getFromPattern($html,$siteInfo[$mainDomain]['wrotedate'][1])    ;
    }
    elseif ($siteInfo[$mainDomain]['wrotedate'][0] == 3){
        $wroteDate = perfect_url(getFromElement($html, $siteInfo[$mainDomain]['wrotedate'][1])->getAttribute($siteInfo[$mainDomain]['wrotedate'][2]),$siteUrl);
    }
    elseif ($siteInfo[$mainDomain]['wrotedate'][0] == 4){
        $wroteDate = getFromElement($html, $siteInfo[$mainDomain]['wrotedate'][1],$siteInfo[$mainDomain]['wrotedate'][2])->plaintext;
    }
    else{
        $wroteDate = 'No Setting!';
    }

}

//get writer
function getWriter(){
    global $html,$writer,$siteUrl,$siteInfo,$mainDomain;

    if($siteInfo[$mainDomain]['writer'][0] == 1) {
        $writer = getFromElement($html, $siteInfo[$mainDomain]['writer'][1])->plaintext;
    }
    elseif ($siteInfo[$mainDomain]['writer'][0] == 2){
        $writer = getFromPattern($html,$siteInfo[$mainDomain]['writer'][1])    ;
    }
    elseif ($siteInfo[$mainDomain]['writer'][0] == 3){
        $writer = perfect_url(getFromElement($html, $siteInfo[$mainDomain]['writer'][1])->getAttribute($siteInfo[$mainDomain]['writer'][2]),$siteUrl);
    }
    elseif ($siteInfo[$mainDomain]['writer'][0] == 4){
        $writer = getFromElement($html, $siteInfo[$mainDomain]['writer'][1],$siteInfo[$mainDomain]['writer'][2])->plaintext;
    }
    elseif ($siteInfo[$mainDomain]['writer'][0] == 5){
        $writer = getFromElement($html, $siteInfo[$mainDomain]['writer'][1])->getAttribute($siteInfo[$mainDomain]['writer'][2]);
    }
    else{
        $writer = 'No Setting!';
    }


}
//get logo image
function getLogo(){
    global $html,$ampLogo,$ampLogoback,$siteUrl,$siteInfo,$mainDomain;

    $ampLogo     = $siteInfo[$mainDomain]['imglogo'];
    $ampLogoback = $siteInfo[$mainDomain]['imglogoback'];
}
//contents read
function getConts(){
    global $html,$ampContents,$ampContents2,$siteInfo,$mainDomain,$keywordRank;


    if($siteInfo[$mainDomain]['conts'][0] == 1) {
        $cntsTmp = getFromElement($html, $siteInfo[$mainDomain]['conts'][1]);
        $ampContents2 = getFromElement($html, $siteInfo[$mainDomain]['conts'][1])->plaintext;
        $cntsTmp = removeAmptag($cntsTmp);
        $ampContents = $cntsTmp;
        $keywordRank = getKeyWord($ampContents2);

    }
    elseif ($siteInfo[$mainDomain]['conts'][0] == 2){

        $cntsTmp = getFromPattern($html,$siteInfo[$mainDomain]['conts'][1])       ;
        //$ampContents2 = removeAmptag(getFromPattern($html,$siteInfo[$mainDomain]['conts'][1]))  ;

        $cntsTmpEle = new simple_html_dom();
        $cntsTmpEle->load($cntsTmp);

        $cntsTmp = removeAmptag($cntsTmpEle);
        $ampContents = $cntsTmp;
        $keywordRank = getKeyWord($cntsTmp);
    }
    else{
        $writer = 'No Setting!';
    }


}
//contents crawl
function cnts_crawl(){
    global $html,$siteUrl,$mainDomain,$siteInfo;

    //파라미터 확인
    if(isset($_GET['d']) && !empty($_GET['d'])){
        $siteUrl = $_GET['d'];
    }
    else{
        die("usage : amppreview.php?d= + [encoded URL] ");
    }

    $mainDomain = getDomainName($siteUrl);

    $html = file_get_html($siteUrl);
    if(empty($html)){
        die('Url is not valid');
    }

    //캐릭터 셋 설정
    header("Content-Type: text/html; charset=" . $siteInfo[$mainDomain]['charset']);

}

//프로그램 시작
function preview_start(){
    global $mainDomain,$html,$ampTitle,$ampContents,$ampImage,$wroteDate;



    cnts_crawl();
    getTitle();
    getConts();
    getImage();
    getImageSizeV();
    getWroteDate();
    getWriter();
    getLogo();


}

preview_start();


?>

<!doctype html>
<html ⚡>
<head>
    <meta charset="utf-8">
    <script async src="https://cdn.ampproject.org/v0.js"></script>
    <script async custom-element="amp-iframe" src="https://cdn.ampproject.org/v0/amp-iframe-0.1.js"></script>
    <script async custom-element="amp-addthis" src="https://cdn.ampproject.org/v0/amp-addthis-0.1.js"></script>
    <title><?=$ampTitle?></title>
    <link rel="canonical" href="<?=$siteUrl?>">
    <meta name="viewport" content="width=device-width,minimum-scale=1,initial-scale=1">
    <link href="https://fonts.googleapis.com/css?family=Roboto:200,300,400,500,700" rel="stylesheet">
    <style amp-boilerplate>body{-webkit-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-moz-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-ms-animation:-amp-start 8s steps(1,end) 0s 1 normal both;animation:-amp-start 8s steps(1,end) 0s 1 normal both}@-webkit-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-moz-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-ms-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-o-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}</style><noscript><style amp-boilerplate>body{-webkit-animation:none;-moz-animation:none;-ms-animation:none;animation:none}</style></noscript>
    <style amp-custom>
        body { font-size:12pt; }
        h1, h2, p, ul, dl, dd { margin:0; padding:0; }
        ul li { list-style-type: none; }
        a { text-decoration: none; color:#333; }

        .header { position:fixed; top:0; left:0; width:100%; height:60px; z-index: 2147483647; }
        .headerLogo { width:100%; height:50px; background-color:<?=$ampLogoback?>; text-align:center; padding-top:10px; }
        .logoBox { display:block; margin:0 auto; max-width:220px; height:40px;
            background: url('<?=$ampLogo?>') no-repeat center center;
            background-size: auto 40px;
        }
        .article { margin-top:60px; }
        .a_text p { margin-bottom:10px; font-size:12pt; line-height:1.6em; }

        .grayBox { border:1px solid #eee; background-color:#fdfdfd; }
        .keywordBox { width:100%; height:1em; line-height:1em; margin:0 auto; }
        .keywordBox li { float:left; width:33%; text-align: center; }
        .keywordBox li a { display:block; color:#dc0062; border-right: 1px solid #ccc; }
        .keywordBox li:last-child a { border-right: none; }
        .middleDot { float:left; width:5px; height:5px; background-color:#dc0062; margin:7px 10px 7px 0;}

        .bestArticle li { border-bottom: 1px solid #ccc; padding:15px;}
        .bestArticle li:last-child { border-bottom: none; }
        .bestArticle .d-table { height:60px; }
        .bestImg { width:80px; height:60px; margin-left:10px; overflow:hidden; }
        .two-line{
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            word-wrap:break-word;
            line-height: 1.3em;
            height: 2.6em;
        }
        .pa-a15 { padding:15px; }
        .pa-b15 { padding-bottom:15px; }
        .ma-b5 { margin-bottom:5px; }
        .text-gray { color:#888; }
        .text-xsmall { font-size:80%; }
        .text-small { font-size:95%; }
        .text-large { font-size:110%; }
        .text-normal { font-weight:normal; }
        .d-table { display:table; width:100%; }
        .d-tablecell { display:table-cell; }
        .wid-80p { width:80%; }
        .wid-20p { width:20%; }
        .v-top { vertical-align:top; }
        .bd-bottom { border-bottom:1px solid #eee; }
        .powered-by-logo,.powered-by-text{display:table-cell;vertical-align:middle}
        .powered-by-text{padding:0 10px}
        .powered-by{float:right;padding:10px 15px;font-family:Roboto,Helvetica,sans-serif;font-size:12px;}
        .powered-by a{display:table;border:none;color:#ababab}
    </style>
</head>
<body>
<div class="header">
    <h1 class="headerLogo">
        <span class="logoBox"></span>
        <!-- 권장 로고 사이즈 : 가로 최대 200px / 세로 최소 40px
             ex) 시크뉴스 로고 사이즈 : 151x50 -->
    </h1>
</div>
<div class="article">
    <div class="a_img">
        <amp-img src="<?=$ampImage?>" width="<?=$ampImageWidth?>" height="<?=$ampImageHeight?>" layout="responsive"></amp-img>
    </div>
    <div class="a_title pa-a15 bd-bottom">
        <h2 class="ma-b5"><?=$ampTitle?></h2>
        <p class="text-gray text-xsmall">
            <span><?=$wroteDate?></span> |
            <strong class="text-normal"><?=$writer?></strong>
        </p>
    </div>
    <div class="a_text pa-a15 text-gray">
        <?=$ampContents?>
    </div>
</div>



<div style="text-align: center;width: 300px; margin: 0 auto;">
    <amp-iframe width="300"
                title="Netflix House of Cards branding: The Stack"
                height="250"
                layout="responsive"
                sandbox="allow-scripts allow-same-origin allow-popups"
                allowfullscreen
                frameborder="0"
                src="https://compass.adop.cc/RD/c785b04b-e517-4685-912b-7106dca50840?type=iframe&amp;loc=&amp;size_width=300&amp;size_height=250"
                id="c785b04b-e517-4685-912b-7106dca50840">
    </amp-iframe>
    <br/>
    <!--    <amp-ad type="a9"-->
    <!--            width="300"-->
    <!--            height="250"-->
    <!--            data-aax_size="300x250"-->
    <!--            data-aax_pubname="test123"-->
    <!--            data-aax_src="302">-->
    <!--    </amp-ad>-->
    <!--    <amp-ad width=300 height=250-->
    <!--            type="adsense"-->
    <!--            data-ad-client="ca-pub-2005682797531342"-->
    <!--            data-ad-slot="7046626912">-->
    <!--    </amp-ad>-->
</div>
<div>
    <amp-addthis
        width="320"
        height="92"
        layout="responsive"
        data-pub-id="ra-5b7658210b43b204"
        data-widget-id="z3e3">
    </amp-addthis>
</div>
<div class="grayBox">
    <dl class="pa-a15 bd-bottom">
        <dt class="pa-b15 text-large"><div class="middleDot"></div>Keywords</dt>
        <dd>
            <ul class="keywordBox text-small">
                <li><a href="#"><?=$keywordRank[0]?></a></li>
                <li><a href="#"><?=$keywordRank[1]?></a></li>
                <li><a href="#"><?=$keywordRank[2]?></a></li>
                <li><a href="#"></a></li>
            </ul>
        </dd>
    </dl>
</div>
<div class="powered-by ">
    <a href="http://www.adop.cc">
        <span class="powered-by-logo">
        </span><span class="powered-by-text">This article is generated by <span class="nowrap">ADOP</span></span></a></div>
</body>
</html>


