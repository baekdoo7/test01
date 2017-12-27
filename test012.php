<?php 
	header("Pragma-directive: no-cache");
	header("Cache-directive: no-cache");
	header("Cache-control: no-cache");
	header("Pragma: no-cache");
	header("Expires: 0");
?>
<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="utf-8">
<title>광고테스트</title>
<style>
img.t1 {
	width:1px;
	height:1px;
}
div.t3 {
	display:none;
}
</style>
<script src="//code.jquery.com/jquery.min.js"></script>
</head>
<body>
테스팅.... <br />
시 간 : <input type="input" value = 0  style="text-align: right;width:20px;" id='timer01' ></input> 초  
<br />
<div>URL  : <input type="text" value="http://52.78.191.196/baekdoo.html" style="text-align:left;width:200px;" id="url01" ></input></div>
<br />
<div>결과</div>
<input type="text" value=0  style="text-align: right;" id="result01"></input>
<br />
<br />
<button onclick="test02();">테스트</button><button onclick="timeCheck();">링크방식</button><button onclick="aJaxCtrl();">ajax</button>

<br />
<br />
<br />
<img alt="" src="aa.jpg" class="t1">
<div id="imggroup" class="t2">
<div id="imgtest">
</div>
</div>
<script type="text/javascript">
 var timer = 0;
 var resultCnt = 0;
function test02(){
	for(var ii=1;ii<250;ii++){
		test01();
	}
}
function test01(){
	var aa = Math.random();
	var urlTmp = $("#url01").val();
	urlTmp += "?a="+aa;
//console.log(urlTmp);	
	//$("<img src='aa.jpg?a="+ aa +"' class='t1' onload='completeDo(this);' />").insertBefore("#imgtest");
	$("<img src='"+urlTmp+"' class='t1' onload='completeDo(this);' onerror='completeDo(this);'/>").insertBefore("#imgtest");
	//alert("test");
}
function timeCheck(){
	if(timer <= 30){
		$("#timer01").attr("value",timer); 
		timer++;
		test02();
		setTimeout('timeCheck();',1000);
		//console.log(timer);
	}
	else{
	
	}
}
 function completeDo(img){
	 if(img.complete == true){
		if(timer <= 30){
			resultCnt++;
		 	$("#result01").attr("value",resultCnt); 
		}
		
	 	//console.log(resultCnt);
	 }
}
function aJaxCtrl(){
	if(timer <= 30){
		$("#timer01").attr("value",timer); 
		timer++;		
		for(var ii = 0;ii<=150;ii++ ){
			aJaxtype();
		}
		
		setTimeout('aJaxCtrl();',1000);
	}
	
}	
function aJaxtype(){
	var aa = Math.random();
	var urlTmp = $("#url01").val();
	urlTmp += "?a="+aa;
	
	 $.ajax({
         type:"GET",
         url:urlTmp,
         dataType:"text", // 옵션이므로 JSON으로 받을게 아니면 안써도 됨
         success : function(data) {
				if(timer < 30){
     	 			resultCnt++;
        	 		$("#result01").attr("value",resultCnt); 
				}
               // 통신이 성공적으로 이루어졌을 때 이 함수를 타게 된다.
               // TODO
         },
         complete : function(data) {
               // 통신이 실패했어도 완료가 되었을 때 이 함수를 타게 된다.
               // TODO
         },
         error : function(xhr, status, error) {
               alert("에러발생");
         }
   });



	
	
}
	

</script>
  
</body>
</html>