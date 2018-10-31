<?php
    header('Content-Type: text/html; charset=utf-8');
?>

<!DOCTYPE html>
<html>
<head>
    <title>HTML5 Puzzle</title>
    <style type="text/css">
    .input{
        position: absolute;
        top:70px;
    }
    .input-text{
        display: block;
        margin: 0;
        padding: 0.8rem 1.6rem;
        color: inherit;
        width: 260px;
        font-family: inherit;
        font-size: 2.1rem;
        font-weight: inherit;
        line-height: 1.8;
        border: 4 solid #000;
        border-radius: 0.4rem;
        transition: box-shadow 300ms;
    }
	.buttons{
		position:absolute;
		left:100px;
		top:460px;
	}
	.button {
	   border: 1px solid #0a3c59;
	   background: #3e779d;
	   background: -webkit-gradient(linear, left top, left bottom, from(#65a9d7), to(#3e779d));
	   background: -webkit-linear-gradient(top, #65a9d7, #3e779d);
	   background: -moz-linear-gradient(top, #65a9d7, #3e779d);
	   background: -ms-linear-gradient(top, #65a9d7, #3e779d);
	   background: -o-linear-gradient(top, #65a9d7, #3e779d);
	   background-image: -ms-linear-gradient(top, #65a9d7 0%, #3e779d 100%);
	   padding: 10.5px 21px;
	   -webkit-border-radius: 14px;
	   -moz-border-radius: 14px;
	   border-radius: 14px;
	   -webkit-box-shadow: rgba(255,255,255,0.4) 0 1px 0, inset rgba(255,255,255,0.4) 0 1px 0;
	   -moz-box-shadow: rgba(255,255,255,0.4) 0 1px 0, inset rgba(255,255,255,0.4) 0 1px 0;
	   box-shadow: rgba(255,255,255,0.4) 0 1px 0, inset rgba(255,255,255,0.4) 0 1px 0;
	   text-shadow: #7ea4bd 0 1px 0;
	   color: #06426c;
	   ##font-size: 24px;
	   font-family: helvetica, serif;
	   text-decoration: none;
	   vertical-align: middle;
	   }
	.button:hover {
	   border: 1px solid #0a3c59;
	   text-shadow: #1e4158 0 1px 0;
	   background: #3e779d;
	   background: -webkit-gradient(linear, left top, left bottom, from(#65a9d7), to(#3e779d));
	   background: -webkit-linear-gradient(top, #65a9d7, #3e779d);
	   background: -moz-linear-gradient(top, #65a9d7, #3e779d);
	   background: -ms-linear-gradient(top, #65a9d7, #3e779d);
	   background: -o-linear-gradient(top, #65a9d7, #3e779d);
	   background-image: -ms-linear-gradient(top, #65a9d7 0%, #3e779d 100%);
	   color: #fff;
	   }
	.button:active {
	   text-shadow: #1e4158 0 1px 0;
	   border: 1px solid #0a3c59;
	   background: #65a9d7;
	   background: -webkit-gradient(linear, left top, left bottom, from(#3e779d), to(#3e779d));
	   background: -webkit-linear-gradient(top, #3e779d, #65a9d7);
	   background: -moz-linear-gradient(top, #3e779d, #65a9d7);
	   background: -ms-linear-gradient(top, #3e779d, #65a9d7);
	   background: -o-linear-gradient(top, #3e779d, #65a9d7);
	   background-image: -ms-linear-gradient(top, #3e779d 0%, #65a9d7 100%);
	   color: #fff;
	   }	
	.main_container {
		left:286px;
		top:30px;
		height: 46px;
		width: 136px;
		padding: 3px;
		margin: 2px;
		max-width: 300px;
		background-color: #555555;
		align-content: center;
		position:absolute;
		}
	.container_inner {
		height: auto;
		border: none;
		background-color: #555555;
		max-width: 290px;
		vertical-align: center;
		padding-top: 12px;
		padding-left: 10px;
		align-content: center;
		}
	.num_tiles {
		width:30px;
		height: 30px;
		background-color: #888888;
		color: #ffffff;
		font-size: 22px;
		text-align: center;
		line-height: 20px;
		padding: 3px;
		margin: 1.5px;
		font-family: verdana;
		vertical-align: center;
		}	
	.back1 {
		width:340px;
		height:340px;
		background-color:#000;
		left:90px;
		top:90px;
		position:absolute;
		
	}
	.block {
		width: 100px;
		height:100px;
		cursor:pointer;
		text-align:center;
		line-height:100px;
		background-color:#0C0;
		position:absolute;
	}
	span{
		font-size:24px;
	}
	._black{
		width: 100%;
		height:100%;
		background-color:#000;
	}
	.num1{
		 left:10px;
		 top:10px;
		 transition:0.5s;
 		 transition-timing-function:ease-in-out;
	}
	.num2{
		left:120px;
		top: 10px;
		transition:0.5s;
 		transition-timing-function:ease-in-out;
	}
	.num3{
		left:230px;
		top: 10px;
		transition:0.5s;
 		transition-timing-function:ease-in-out;
	}
	.num4{
		left:10px;
		top: 120px;
		transition:0.5s;
 		transition-timing-function:ease-in-out;
	}
	.num5{
		left:120px;
		top: 120px;
		transition:0.5s;
 		transition-timing-function:ease-in-out;
	}
	.num6{
		left:230px;
		top: 120px;
		transition:0.5s;
 		transition-timing-function:ease-in-out;
	}
	.num7{
		left:10px;
		top: 230px;
		transition:0.5s;
 		transition-timing-function:ease-in-out;
	}
	.num8{
		left:120px;
		top: 230px;
		transition:0.5s;
 		transition-timing-function:ease-in-out;
	}
	.num9{
		left:230px;
		top: 230px;
		transition:0.5s;
 		transition-timing-function:ease-in-out;
	}
		
    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="numdata.js"></script>
    <script type="application/javascript">
	var playing =  false;
	var complete = true;
	var movCount = 0;
		$(document).ready(function() {			
					$('.block').click(function(){
						if($(this).hasClass('num1')){
							moving("num1");
						}
						else if($(this).hasClass('num2')){
							moving("num2");
						}
						else if($(this).hasClass('num3')){
							moving("num3");
						}
						else if($(this).hasClass('num4')){
							moving("num4");
						}
						else if($(this).hasClass('num5')){
							moving("num5");
						}
						else if($(this).hasClass('num6')){
							moving("num6");
						}
						else if($(this).hasClass('num7')){
							moving("num7");
						}
						else if($(this).hasClass('num8')){
							moving("num8");
						}
						else if($(this).hasClass('num9')){
							moving("num9");
						}
	
					});
					setcount(movCount);
				});
		function setNum(){
		    var num01 = $("#input").val();
		    if(typeof(qdata[num01])=="undefined"){
		        alert("맞출수 없는 숫자 배치 입니다.");
            }
            else{
                setNumber(num01);
                movCount = 0;
                setcount(movCount);

            }
		    console.log(num01);
        }
		function getRandomInt(min, max) {
  			return Math.floor(Math.random() * (max - min)) + min;
		}
		function autoPlay(num){
		    var num2 = '';
		    num2 = qdata[num];
            setNumber(num);
            setcount(movCount/9);
		    if(!complete){
		        setTimeout(function(){
		            autoPlay(num2);
                },500);
            }


        }
		function autoGame(){
		    var curNum = readCnum();

		    var nextNum01 = qdata[curNum];

		   // console.log(curNum);
		   // console.log(nextNum01);//
		   // return 0;
		    if(nextNum01 != ''){
			    console.log('자동 정렬이 시작되었습니다.');
			    autoPlay(nextNum01);
			    //console.log(readCnum());
		    }



		}
		function readCnum(){
            var numOrder = "";
            numOrder += $(".num1").attr('value');
            numOrder += $(".num2").attr('value');
            numOrder += $(".num3").attr('value');
            numOrder += $(".num4").attr('value');
            numOrder += $(".num5").attr('value');
            numOrder += $(".num6").attr('value');
            numOrder += $(".num7").attr('value');
            numOrder += $(".num8").attr('value');
            numOrder += $(".num9").attr('value');
            return numOrder;

        }
		function chkgameover(){
			var numOrder = "";
			numOrder += $(".num1").attr('value');
			numOrder += $(".num2").attr('value');
			numOrder += $(".num3").attr('value');
			numOrder += $(".num4").attr('value');
			numOrder += $(".num5").attr('value');
			numOrder += $(".num6").attr('value');
			numOrder += $(".num7").attr('value');
			numOrder += $(".num8").attr('value');
			numOrder += $(".num9").attr('value');
			
			if(numOrder == '123456789'){
				playing = false;
				return true;
			}
			else{
				return false;
			}
		}
		function startGame(){
			var arrayTmp = Object.keys(qdata); 
			var numTmp   = arrayTmp[getRandomInt(0,arrayTmp.length)];
			//console.log(numTmp);
			setNumber(numTmp);
			movCount = 0;
			setcount(movCount);	
			playing = true;
			complete = false;
			console.log('게임이 시작 되었습니다.');
		}
		function initGame(){
			//console.log('????????.');
			setNumber('123456789');
			movCount = 0;
			playing = false;
			complete = false;
			setcount(movCount);
		}	
		function setNumber(num){
			for (var i = 0; i < num.length; i++) {
        		//console.log(num[i]);
				//console.log($("div[value="+num[i]+"]").attr('class').substring(6,10));
				
				swapab("num"+(i+1),$("div[value="+num[i]+"]").attr('class').substring(6,10));
      		}
      		if(num == '123456789'){
			    complete = true;
            }

		}	
		function setcount(num){
			var display_div = document.getElementById("display_div_id");
			var numtmp = 100000 + num;
			display_str = numtmp.toString();
			display_str = display_str.substring(1,6);
      
	  		while (display_div.hasChildNodes()) {
          		display_div.removeChild(display_div.lastChild);
      		}			
			for (var i = 0; i < display_str.length; i++) {
        		var new_span = document.createElement('span');
       			 new_span.className = 'num_tiles';
       				new_span.innerText = display_str[i];
        			display_div.appendChild(new_span);
      			}
		}		
		function moving(num){
			switch(num){
				case 'num1':
						if($('.num2').attr('value')==9){
							swapab("num1","num2");
						}
						else if($('.num4').attr('value')==9){
							swapab("num1","num4");
						}
						break;
				case 'num2':
						if($('.num1').attr('value')==9){
							swapab("num1","num2");
						}
						else if($('.num3').attr('value')==9){
							swapab("num3","num2");
						}
						else if($('.num5').attr('value')==9){
							swapab("num5","num2");
						}
						break;
				case 'num3':
						if($('.num2').attr('value')==9){
							swapab("num2","num3");
						}
						else if($('.num6').attr('value')==9){
							swapab("num6","num3");
						}				
						break;
				case 'num4':
						if($('.num1').attr('value')==9){
							swapab("num1","num4");
						}
						else if($('.num5').attr('value')==9){
							swapab("num5","num4");
						}
						else if($('.num7').attr('value')==9){
							swapab("num7","num4");
						}				
						break;
				case 'num5':
						if($('.num2').attr('value')==9){
							swapab("num5","num2");
						}
						else if($('.num4').attr('value')==9){
							swapab("num4","num5");
						}
						else if($('.num6').attr('value')==9){
							swapab("num6","num5");
						}
						else if($('.num8').attr('value')==9){
							swapab("num8","num5");
						}				
						break;
				case 'num6':
						if($('.num3').attr('value')==9){
							swapab("num3","num6");
						}
						else if($('.num5').attr('value')==9){
							swapab("num5","num6");
						}
						else if($('.num9').attr('value')==9){
							swapab("num9","num6");
						}				
						break;
				case 'num7':
						if($('.num4').attr('value')==9){
							swapab("num4","num7");
						}
						else if($('.num8').attr('value')==9){
							swapab("num8","num7");
						}				
						break;
				case 'num8':
						if($('.num5').attr('value')==9){
							swapab("num5","num8");
						}
						else if($('.num7').attr('value')==9){
							swapab("num8","num7");
						}
						else if($('.num9').attr('value')==9){
							swapab("num8","num9");
						}			
						break;
				case 'num9':
						if($('.num6').attr('value')==9){
							swapab("num6","num9");
						}
						else if($('.num8').attr('value')==9){
							swapab("num8","num9");
						}				
						break;						
			}
			//console.log(num);
		}		
		function swapab(div1,div2){
			$tmp1 = $('.'+div1);
			$tmp2 = $('.'+div2);
		
			$($tmp1).removeClass(div1);
			$($tmp1).addClass(div2);
			
						
			$($tmp2).removeClass(div2);
			$($tmp2).addClass(div1);
			
			setcount(++movCount);
			if(playing && chkgameover()){
				playing = false;
                complete = true;
                setTimeout(function(){alert('숫자 정렬이 완료 되었습니다.');},500);
                console.log('정렬이 완료 되었습니다.');
				//alert("?????? ???? ????.");
			}
			else{
			    complete = false;
            }
		}	
	</script>
    
</head>
 
<body>
    	<div class="main_container" id="id_main_container">
      		<div class="container_inner" id="display_div_id">
      		</div>
    	</div>
	<div class="back1">
        <div class="block num9" value=9><div class="_black"></div></div>
        <div class="block num1" value=1><span>1</span></div>
        <div class="block num2" value=2><span>2</span></div>
        <div class="block num3" value=3><span>3</span></div>
        <div class="block num4" value=4><span>4</span></div>
        <div class="block num5" value=5><span>5</span></div>
        <div class="block num6" value=6><span>6</span></div>
        <div class="block num7" value=7><span>7</span></div>
        <div class="block num8" value=8><span>8</span></div>
    </div>

    <div class="buttons">
        <div class="input" >
            <input type="text" id="input" class="input-text">
        </div>
    	<a href='#' class='button' onClick="initGame();">초기화</a>
        <a href='#' class='button' onClick="setNum();">세 팅</a>
    	<a href='#' class='button' onClick="startGame();">시 작</a>
    	<a href='#' class='button' onClick="autoGame();">자 동</a>
    </div>
</body>
 
</html>