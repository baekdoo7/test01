<?php
/**
 * Created by IntelliJ IDEA.
 * User: baekdoo
 * Date: 2018. 10. 29.
 * Time: PM 3:41
 */

/*
$tmp = "1adopADshow('div-apt-ad-7SD7j0q8Fy',320,50,'http://ad.adnmore.co.kr/cgi-bin/PelicanC.dll?impr&pageid=05qg&grade=3&out=iframe');";
$replace = "ㅂㅏ꿀려는 문자열";
preg_match("/adopADshow\([^,]*,[^,]*,[^,]*,'(.*?)'\s*\)/i",$tmp,$tmp1);
$tmp2 = str_replace($tmp1[1],$replace,$tmp);
echo $tmp2;
echo "<hr />";

echo changePurl($tmp,"오징어");
*/
/*
function changePurl($template,$purl){
    $cnt = preg_match("/adopADshow\([^,]*,[^,]*,[^,]*,'(.*?)'\s*\)/i",$template,$tmp1);
    if($cnt){
        $template = str_replace($tmp1[1],$purl,$template);
    }
    return $template;
}
$tmp = "1  adopADshow('div-apt-ad-7SD7j0q8Fy',320,50,'http://ad.adnmore.co.kr/cgi-bin/PelicanC.dll?impr&pageid=05qg&grade=3&out=iframe');";
echo changePurl($tmp,"오징어");
*/
$string = "뭐래냐?";

$tmp = iconv("EUC-KR","UTF-8", $string);