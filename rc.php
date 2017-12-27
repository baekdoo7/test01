<?php
    $area_idx = $_GET['area_idx'];
    $w        = $_GET['w']; 
    $h        = $_GET['h']; 
if($w==""){
$w="100%";
}else{
$w=$w."px";
}
if($h==""){
$h="100%";
}else{
$h=$h."px";
}
?>


function passback_exec(url,width,height){
    
    //아이프레임 변수 및 아이프레임 네이밍 
    var strIframeId   = "adopB" + Math.floor(Math.random()*10000) + 1;
    var strIframe     = "<iframe id='"+strIframeId+"' frameborder='0' marginwidth='0' marginheight='0' paddingwidth='0' paddingheight='0' scrolling='no' style='width:"+width+"; height:"+height+";' ></iframe>"; //속성추가할것
    var strScriptLink = "<script src='"+url+"'><\/script>";
    var strScriptPgm  = "";
    
    document.write(strIframe);
    var passbackIframe = document.getElementById(strIframeId);
    var passbackIframeDoc = passbackIframe.contentDocument || passbackIframe.contentWindow.document;
    if(passbackIframeDoc != null){
        passbackIframeDoc.open();
        passbackIframeDoc.write(strScriptLink);
        passbackIframeDoc.close();
    }    
}

passback_exec("http://compasstest.adop.cc/RE/<?=$area_idx?>",'<?=$w?>','<?=$h?>');
        
       
