<?php
/**
 * Created by IntelliJ IDEA.
 * User: baekdoo
 * Date: 2018. 11. 7.
 * Time: AM 9:32
 */


$siteInfo = array();
$tmp =  array('url'=>'m.theceluv.com','title'=>array(1,'#newstitle'),'conts'=>array(1,'div.jul'),'image'=>array(1,'#news_padding img'),'wrotedate'=>array(1,'font.date'),'writer'=>array(2,'/.*\[(.*?) news.*\]/i'),'imglogo'=>'http://www.theceluv.com/mainimg/logon.png','imglogoback'=>'#000','deletetag'=>array());
$siteInfo['m.theceluv.com'] = $tmp;
$tmp =  array('url'=>'tvdaily.asiae.co.kr','title'=>array(1,'title'),'conts'=>array(2,'/<!-- ADOPCONS -->(.*?)<!-- ADOPCONE -->/i'),'image'=>array(1,'img#s3dpop_searchImgID01'),'wrotedate'=>array(1,'font.read_time'),'writer'=>array(2,'/.*\[티브이데일리(.*?) news.*\]/i'),'imglogo'=>'http://amp.adop.cc/assets/img/tvdaily_logo_min.png','imglogoback'=>'#eeeeee','deletetag'=>array());
$siteInfo['tvdaily.asiae.co.kr'] = $tmp;
$tmp =  array('url'=>'kpopchart.net','title'=>array(1,'h1.entry-title'),'conts'=>array(1,'div.entry-content'),'image'=>array(3,'div.post-thumbnail-wrapper img','data-lazy-src'),'wrotedate'=>array(1,'time.entry-date'),'writer'=>array(1,'span.author'),'imglogo'=>'https://kpopchart.net/wp-content/uploads/2017/05/header-kcn.png','imglogoback'=>'#eeeeee','deletetag'=>array('blockquote','div.sharedaddy','div.arve-embed-container'));
$siteInfo['kpopchart.net'] = $tmp;
$tmp =  array('url'=>'www.ligaolahraga.com','title'=>array(1,'h1[itemprop=name]'),'conts'=>array(1,'div.content-detail'),'image'=>array(1,'figure img'),'wrotedate'=>array(1,'div.date span'),'writer'=>array(1,'div.news_by span'),'imglogo'=>'https://www.ligaolahraga.com/images/logo-ligaolahraga.png','imglogoback'=>'#333','deletetag'=>array());
$siteInfo['www.ligaolahraga.com'] = $tmp;
$tmp =  array('url'=>'trip-n-travel.com','title'=>array(1,'h1.entry-title'),'conts'=>array(1,'div.entry-content'),'image'=>array(1,'div.entry-content img'),'wrotedate'=>array(1,'time'),'writer'=>array(1,'span.author a'),'imglogo'=>'https://pbs.twimg.com/profile_images/691192257144164352/3LKC0lDw_400x400.png','imglogoback'=>'#ff9a1d','deletetag'=>array('blockquote'));
$siteInfo['trip-n-travel.com'] = $tmp;


?>