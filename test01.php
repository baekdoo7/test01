<?php
	//phpinfo();
	//$memcache = new Memcache;
	//$memcache->connect('www.test01.com',11211) or die("Could not connect memcache !!!");
	
	//$version = $memcache->getversion();
	//phpinfo();

$a= 1;
$b= 1;
$c= $a + $b;
	
?>
<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="utf-8">
<script src="http://www.dt.co.kr/js/jquery.js"></script>
<title>광고테스트</title>
<style>
</style>
</head>
<body>
Testing...... <br />
광고 주소 :  http://compass.adop.cc/RD/db270da6-4016-4d8c-9280-ceeecd4ed29f  <br />

임시 120x600 : 56ff437e-0ee3-42d6-8355-5b1ce04b559c
<br />
<br />
<br />
<ins id="tt0"></ins>
    <script type="text/javascript" src="test01.js"></script>
<iframe id="t001" src="" style="width: 600px;height:300px;" scrolling="no" ></iframe>  
<iframe id="t002" src="test011.php" style="width: 300px;height:300px;" scrolling="no" ></iframe>  
<script>
	var obj = document.getElementById("t001");
	var url01 = "test011.php?cd=" +Math.random();
	obj.src = url01;
    location.hash = "1234";
</script>
<script type="text/javascript">
$(document).ready(function(){
	
	//var t01 = $("<iframe>");
	//t01.attr("src","http://www.daum.net");
	//t01.appendTo("#tt0");
	
});


</script>

<?php
	echo "memcached version : $version";
	echo "test";
?>
</body>
</html>