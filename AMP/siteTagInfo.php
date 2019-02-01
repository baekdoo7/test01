<?php
/**
 * Created by IntelliJ IDEA.
 * User: baekdoo
 * Date: 2018. 11. 7.
 * Time: AM 9:32
 */


$siteInfo = array();
$tmp =  array('url'=>'m.theceluv.com','charset'=>'utf-8','title'=>array(1,'#newstitle'),'conts'=>array(1,'div.jul'),'image'=>array(1,'#news_padding img'),'wrotedate'=>array(1,'font.date'),'writer'=>array(2,'/.*\[(.*?) news.*\]/i'),'imglogo'=>'http://www.theceluv.com/mainimg/logon.png','imglogoback'=>'#000','deletetag'=>array(),'deletekeyword'=>array());
$siteInfo['m.theceluv.com'] = $tmp;
$tmp =  array('url'=>'tvdaily.asiae.co.kr','charset'=>'utf-8','title'=>array(1,'title'),'conts'=>array(2,'/<!-- ADOPCONS -->(.*?)<!-- ADOPCONE -->/i'),'image'=>array(1,'img#s3dpop_searchImgID01'),'wrotedate'=>array(1,'font.read_time'),'writer'=>array(2,'/.*\[티브이데일리(.*?) news.*\]/i'),'imglogo'=>'http://amp.adop.cc/assets/img/tvdaily_logo_min.png','imglogoback'=>'#eeeeee','deletetag'=>array(),'deletekeyword'=>array("티브이데일리","br"));
$siteInfo['tvdaily.asiae.co.kr'] = $tmp;
$tmp =  array('url'=>'kpopchart.net','charset'=>'utf-8','title'=>array(1,'h1.entry-title'),'conts'=>array(1,'div.entry-content'),'image'=>array(3,'div.post-thumbnail-wrapper img','data-lazy-src'),'wrotedate'=>array(1,'time.entry-date'),'writer'=>array(1,'span.author'),'imglogo'=>'https://kpopchart.net/wp-content/uploads/2017/05/header-kcn.png','imglogoback'=>'#eeeeee','deletetag'=>array('blockquote','div.sharedaddy','div.arve-embed-container'),'deletekeyword'=>array());
$siteInfo['kpopchart.net'] = $tmp;
$tmp =  array('url'=>'www.ligaolahraga.com','charset'=>'utf-8','title'=>array(1,'h1[itemprop=name]'),'conts'=>array(1,'div.content-detail'),'image'=>array(1,'figure img'),'wrotedate'=>array(1,'div.date span'),'writer'=>array(1,'div.news_by span'),'imglogo'=>'https://www.ligaolahraga.com/images/logo-ligaolahraga.png','imglogoback'=>'#333','deletetag'=>array(),'deletekeyword'=>array());
$siteInfo['www.ligaolahraga.com'] = $tmp;
$tmp =  array('url'=>'trip-n-travel.com','charset'=>'utf-8','title'=>array(1,'h1.entry-title'),'conts'=>array(1,'div.entry-content'),'image'=>array(1,'div.entry-content img'),'wrotedate'=>array(1,'time'),'writer'=>array(1,'span.author a'),'imglogo'=>'https://pbs.twimg.com/profile_images/691192257144164352/3LKC0lDw_400x400.png','imglogoback'=>'#ff9a1d','deletetag'=>array('blockquote'),'deletekeyword'=>array(" the "," a "," an "," of "," and "," but "," or "," so "," for "," in "));
$siteInfo['trip-n-travel.com'] = $tmp;
$tmp =  array('url'=>'cintaihidup.com','charset'=>'utf-8','title'=>array(1,'h2.entry-title'),'conts'=>array(1,'div.fusion-post-content-container'),'image'=>array(1,'div.fusion-image-wrapper img'),'wrotedate'=>array(4,'p.fusion-single-line-meta span',4),'writer'=>array(1,'span.vcard span a'),'imglogo'=>'http://cintaihidup.com/wp-content/uploads/2018/11/Logo-Cintai-Hidup-01.png','imglogoback'=>'#fffff0','deletetag'=>array('blockquote'),'deletekeyword'=>array(" the "," a "," an "," of "," and "," but "," or "," so "," for "," in "));
$siteInfo['cintaihidup.com'] = $tmp;
$tmp =  array('url'=>'www.provoke-online.com','charset'=>'utf-8','title'=>array(1,'h2[itemprop=headline]'),'conts'=>array(1,'div[itemprop=articleBody]'),'image'=>array(1,'div[itemprop=articleBody] p img'),'wrotedate'=>array(1,'time[itemprop=datePublished]',0),'writer'=>array(1,'li[itemprop=author] span[itemprop=name]'),'imglogo'=>'http://www.provoke-online.com/templates/shape5_vertex/images/headern.png','imglogoback'=>'#fffff0','deletetag'=>array('blockquote'),'deletekeyword'=>array(" the "," a "," an "," of "," and "," but "," or "," so "," for "," in "));
$siteInfo['www.provoke-online.com'] = $tmp;
$tmp =  array('url'=>'m.seoul.co.kr','charset'=>'euc-kr','title'=>array(1,'h2[itemprop=title]'),'conts'=>array(1,'#contentsBodyDiv'),'image'=>array(1,'img.photoImage'),'wrotedate'=>array(1,'span.date',0),'writer'=>array(5,'meta[property=dable:author]','content'),'imglogo'=>'http://imgmo.seoul.co.kr/img//14m_seoul_ci.gif?v=20150805','imglogoback'=>'#ffffff','deletetag'=>array('blockquote'),'deletekeyword'=>array(" 있다 "," 때문에 "));
$siteInfo['m.seoul.co.kr'] = $tmp;


?>