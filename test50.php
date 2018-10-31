<?php
/**
 * Created by IntelliJ IDEA.
 * User: baekdoo
 * Date: 2018. 5. 18.
 * Time: PM 6:51
 */


$url = "http://www.test01.com/test49.php";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
//curl_setopt($ch, CURLOPT_HEADER, TRUE);
//curl_setopt($ch, CURLOPT_NOBODY, TRUE); // remove body
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; 문어');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
$head = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);


echo $head;
?>