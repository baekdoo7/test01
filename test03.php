<?php
//header("Location: http://compass.adop.cc/RD/ddc4abff-c6df-4a59-a133-1f429343da6b"); 
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <? echo "<meta http-equiv='refresh' content='0; url=http://compass.adop.cc/RD/ddc4abff-c6df-4a59-a133-1f429343da6b'>";exit(); ?> 
<meta charset="utf-8">
<script src="http://www.dt.co.kr/js/jquery.js"></script>
<title>광고테스트</title>
<style>
</style>
</head>
<body>
  
<!--    
<iframe src="http://www.adop.cc"></iframe>    
<iframe src="http://www.daum.net"></iframe>
<iframe src="http://www.dt.co.kr"></iframe>
-->
<br />
<iframe src="http://compass.adop.cc/RD/ddc4abff-c6df-4a59-a133-1f429343da6b" style="width:1024px;height:800px;"></iframe>

<script type="application/javascript">
var obj = document.getElementsByTagName("iframe");
var adUrl = new Array("adop.cc",
                      "korea.com",
                     "compass.adop.cc");

function test001(){
    for(var j in obj){
        //console.log(obj[j].src);
        if(checkUrl(obj[j].src)){
            obj[j].style = "visibility:hidden";
        }
        
    }

}

function checkUrl(ifrUrl){  
    for(var i in adUrl){
        if(ifrUrl.indexOf(adUrl[i]) > -1 ){
            return true;
        }
    }
    return false;
}

//test001();
    
</script>


    
    
</body>
</html>