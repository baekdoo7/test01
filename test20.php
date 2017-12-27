<?php
    $ad01 = isset($_GET['a'])?$_GET['a']:"";
    $ad02 = isset($_GET['b'])?$_GET['b']:"";
    $ad03 = isset($_GET['c'])?$_GET['c']:"";
    $div = "";
    $url = "";
    if($ad01 == "1"){
        $url = "test20.php?a=2&b=$ad02&c=$ad03";
        $div = "<div class='label01'>criteo</div>";
    }
    elseif($ad01 == "2"){
        $url = "test20.php?a=3&b=$ad02&c=$ad03";
        $div = "<div class='label02'>Adx</div>";       
    }
    elseif($ad01 == "3"){
        $url = "test20.php?a=4&b=$ad02&c=$ad03";
        $div = "<div class='label03'>AdSense</div>";       
    }
    elseif($ad01 == "4"){
        $url = "test20.php?a=5&b=1&c=$ad03";
        $div = "<div class='label04'>Wider</div>";       
        if($ad02 == "1"){
            $url = "http://www.www.www";
            $div = "<div class='label04'>Error</div>";  
        }
        if($ad03 == "1"){
            $url = "http://203.233.19.171/tm/?a=IE&b=WIN&c=799002667060&d=10003&e=6021&f=Y29tcGFzcy5hZG9wLmNjL1JELzAyYWRiOTYwLWU2MGQtNDg0Yy1iYjRmLTczM2M3NjFiNjdiYw==&g=1489135907641&h=1489135907749&y=0&z=0&x=1&w=2016-10-07&in=6021_00040883&id=20170310";
            $div = "<div class='label04'>Error</div>";  
        }
    }
    else{
      null;
    }

?>
<!DOCTYPE HTML>
<html lang="ko">
<head>    
<title>아이프레임 테스트</title>
<!-- <script type="text/javascript" src="/script/jquery.1.6.min.js"></script> -->
<script src="//code.jquery.com/jquery.min.js"></script>
<script>    
    function kkk(no){
        if(no == 1){
            jQuery("#ifr01").attr("src","test20.php?a=1");
        }
        else if(no == 2){
            jQuery("#ifr01").attr("src","test20.php?a=1&b=1");
        }
        else if(no == 3){
            jQuery("#ifr01").attr("src","test20.php?a=1&c=1");
        }
    }    
setTimeout(function(){jQuery("#ifr01").attr("src","<?=$url?>")},1000);     
    function ttt(no){
        if(no < 0)return;
            console.log(no);
            var t = document.createElement("div");
            t.innerHTML = no;
            oHead = document.getElementsByTagName('body').item(0);
            oHead.appendChild(t);
        setTimeout(function(){ttt(no - 1)},1000);
    }
function sleep(num){	//[1/1000초]

			 var now = new Date();

			   var stop = now.getTime() + num;

			   while(true){

				 now = new Date();

				 if(now.getTime() > stop)return;

			   }

}

 
</script>
<style type="text/css">
    body {
        padding: 0;
        margin: 0;
    }
    .btn01 {
        font-size: 20px;
    }
    .iframe01{
        width: 200px;
        height: 500px;
        border: 0;
    }
    .label01{
        background-color: coral;
        width: 50px;
    }
    .label02{
        background-color:blueviolet;
        width: 30px;
    }
    .label03{
        background-color:darkgoldenrod;
        width: 60px;
    }
    .label04{
        background-color:fuchsia;
        width: 40px;
    }    
    
</style>
</head>
<body>
<?if($ad01 == ""){?>    
<button onclick="kkk(1)" class="btn01">정상</button>
<button onclick="kkk(2)" class="btn01">에러처리</button>
<button onclick="kkk(3)" class="btn01">이상동작</button>
<button onclick="ttt(5)" class="btn01">로딩중</button>
<!--메인 플로팅배너-->
<hr />
<?}?>
<?echo $div?>    
<iframe src="" class="iframe01" id="ifr01"></iframe>
<!--
<iframe src="http://203.233.19.171/tm/?a=IE&b=WIN&c=799002667060&d=10003&e=6021&f=Y29tcGFzcy5hZG9wLmNjL1JELzAyYWRiOTYwLWU2MGQtNDg0Yy1iYjRmLTczM2M3NjFiNjdiYw==&g=1489135907641&h=1489135907749&y=0&z=0&x=1&w=2016-10-07&in=6021_00040883&id=20170310"></iframe>
-->
<script>
    kkk(3);
    ttt(10);
</script>
</body>
</html>

