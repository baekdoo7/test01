<?php 
$g_passback = "http://www.adop.cc";
$g_code = "<script type=\"text/javascript\"><!--
google_ad_client = \"ca-pub-1474238860523410\";
/* DP_slimpost_M_M_320x100 */
google_ad_slot = \"4680440278\";
google_ad_width = 320;
google_ad_height = 100;
//-->
</script>
<script type=\"text/javascript\"
src=\"//pagead2.googlesyndication.com/pagead/show_ads.js\">
</script>";


function _googlePassBaceReplace($adCode,$adPassBack){
	if(strpos($adCode, "google_ad_slot") === FALSE){
		return $adCode;
	}
	
	$adCode = str_replace("google_ad_slot", "google_passback = ".urlencode($adPassBack)."; \n"."google_ad_slot", $adCode);
	//str_replace("google_ad_slot", "", $adCode);
	return $adCode;
}

?>

<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="utf-8">
<title>광고테스트</title>
<style>
</style>
</head>
<body>
테스트.... <br />

<!-- 구글 광고 테스트  함수 사용전 -->
<?php 
	echo $g_code;
?>

<!-- 구글 광고 테스트  함수 사용 -->
<?php 
	echo _googlePassBaceReplace($g_code,$g_passback);
?>


</body>
</html>