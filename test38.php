<?php
/*
 DFP 라인 아이템 테스팅을 위한 페이징
*/
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>최단거리 알고리즘</title>
    <script type="application/javascript">
        var path = [];
        var pathBlock = [];
        var shortCnt = 110;
        
        //path.push(31);
        //path = [11,12,13,14,16,17,18,19,9]
        //path = new Array(11,12,13,14,16,17,18,19,9,33,34,35,36,37,38,39,40,51,52,53,54,55);

      
        
        function gopath(){
            var obj = document.getElementById("b_1");
            path = obj.value.split(","); 
            pathBlock = obj.value.split(","); 
            kkk = findPath(1,path,1);
            draw();
            console.log(kkk);
        }
        
        
        function findPath(curNum,pathP,cnt01){
            var a = {};
            var bb = {};
            var tmpArray = [];
            var cnt02 = cnt01 + 1;
            var nextArray = [];
            var nTmp;
            
            bb.find = false;
            bb.cnt  = 100;
            bb.path = [];
            
            if(inArray(curNum,pathP)){
                return bb;   
            }
            if(shortCnt < cnt01){
                a.find = false;
                a.curNum = curNum;
                a.path = [];
                return a;
            }
            if(curNum == 100){
               // console.log("찾았다.");
                if(shortCnt > cnt01){shortCnt = cnt01}
                a.find   = true;
                a.cnt    = cnt01;
                a.curNum = curNum;
                a.path   = [];
                a.path.push(curNum);
                return a;
            } 
            
            pathP.push(curNum);
            nextArray = nextNum(curNum,pathP);
            nextArray.sort(function(a,b){return b - a;});
            nTmp = nextArray.length;
            if(nTmp == 0){
                a.find = false;
                a.cnt  = cnt01;
                a.curNum = curNum;
                a.path = [];
                return a;
            }
            
            for(var i=0 ;i < nTmp; i++){
                tmpArray.push(findPath(nextArray[i],pathP,cnt02));
            }
            
            for(var j=0; j < tmpArray.length;j++){
               if(tmpArray[j].find == true && tmpArray[j].cnt <= bb.cnt){
                   console.log(curNum);
                   bb.find = true;
                   bb.cnt  = tmpArray[j].cnt;
                   bb.path = tmpArray[j].path;
                   bb.path.push(curNum);
                   
               }
            }
            
            
            return bb;
            //console.log(nTmp);
            
            
        }
        
        function nextNum(curNum,stopPath){
            
            var frontNo;
            var backNo;
            var n_up,n_down,n_left,n_right;
            var returnArray = [];
            
            frontNo = parseInt(curNum / 10);
            backNo  = parseInt(curNum % 10);
            
            if(frontNo == 0){
               if(backNo == 0){
                   n_up    = 0   ;
                   n_down  = 0   ;
                   n_left  = 0   ;
                   n_right = 0   ;     
               }
               else if(backNo == 1) {
                   n_up    = 0   ;
                   n_down  = 11  ;
                   n_left  = 0   ;
                   n_right = 2   ;   
               }
               else{
                   n_up    = 0            ;
                   n_down  = 10 +  backNo ;  
                   n_left  = backNo - 1   ;
                   n_right = backNo + 1   ;                      
               }
            }
            else if (frontNo == 9){ 
               if(backNo == 0){
                   n_up    = 80  ;
                   n_down  = 100 ;
                   n_left  = 89  ;
                   n_right = 0   ;     
               }
               else if(backNo == 1) {
                   n_up    = 81  ;
                   n_down  = 0   ;
                   n_left  = 0   ;
                   n_right = 92  ;   
               }
               else{
                   n_up    = 80 + backNo  ;
                   n_down  = 0            ;  
                   n_left  = curNum - 1   ;
                   n_right = curNum + 1   ;                      
               }
                
            }
            else{
               if(backNo == 0){
                   n_up    = curNum - 10  ;
                   n_down  = curNum + 10  ;
                   n_left  = curNum - 1   ;
                   n_right = 0   ;     
               }
               else if(backNo == 1) {
                   n_up    = curNum - 10  ;
                   n_down  = curNum + 10  ;
                   n_left  = 0   ;
                   n_right = curNum + 1   ;   
               }
               else{
                   n_up    = curNum - 10  ;
                   n_down  = curNum + 10  ;
                   n_left  = curNum - 1   ;
                   n_right = curNum + 1   ;   
               }
                
            }
            
            if(inArray(n_up   ,stopPath)) n_up = 0;
            if(inArray(n_down ,stopPath)) n_down = 0;
            if(inArray(n_left ,stopPath)) n_left = 0;
            if(inArray(n_right,stopPath)) n_right = 0;
            
            if(n_up != 0)    returnArray.push(n_up);
            if(n_down != 0)  returnArray.push(n_down);
            if(n_left != 0)  returnArray.push(n_left);
            if(n_right != 0) returnArray.push(n_right);
            
            //console.log("n_up :" +n_up + "   n_down:" + n_down + "   n_left:" + n_left + "   n_right:" + n_right);
            
            return returnArray;
            
        }
        function inArray(needle, haystack) {
            var length = haystack.length;
                for(var i = 0; i < length; i++) {
                    if(haystack[i] == needle) return true;
                }
            return false;
        }        
        function clear01(){
            var obj = document.getElementById("b_1");
            obj.value = "";
        }
        function draw(){
            document.getElementById("container1").innerHTML = "";
            document.getElementById("depth1").innerHTML = "최단경로는 "+kkk.cnt+" 단계 입니다.";
            for(var i=1 ; i<= 100 ; i++){
                var obj = document.getElementById("container1");
                var ele = document.createElement("div");
                ele.style.width  = "48px";
                ele.style.height = "30px";
                ele.style.paddingTop = "18px";
                ele.style.border = "1px solid black";
                ele.style.textAlign = "center";
                ele.style.verticalAlign = "middle";  
                ele.style.float = "left";
                ele.textContent = i;
                if(inArray(i,pathBlock)){
                    ele.style.backgroundColor = "brown";   
                }
                if(inArray(i,kkk.path)){
                    ele.style.backgroundColor = "yellow";   
                }
                obj.appendChild(ele);                
            }
        }
    </script>
    <style type="text/css">
        #container1 {
                width: 500px;
                height: 500px;
                position: absolute;
                top: 150px;
                left: 10px;
        }
        block1 {
                width: 10px;
                height: 10px;
        }
    </style>
</head>
<body>
    최단거리 찾기
    <hr />
    블록 : <input name="b_1" id="b_1" type=text></input>
    <button onclick="clear01()">클리어</button>
    <button onclick="gopath()">시작</button>
    <button onclick="draw()">그리기</button>
    <div id="depth1"></div>
    <div id="container1"></div>
</body> 
</html>