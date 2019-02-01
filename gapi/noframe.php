<?php
    $icnt = $_REQUEST['cnt'];
    $rndNo = rand(1,1000000);
    if($icnt <= 1){

    ?>
        var ifr = document.createElement("iframe");
        ifr.setAttribute("id", "Btest" + Math.round(Math.random() * 10000)),
        ifr.setAttribute("frameborder", "0"),
        //ifr.setAttribute("allowtransparency", "true"),
        ifr.setAttribute("hspace", "0"),
        ifr.setAttribute("marginwidth", "0"),
        ifr.setAttribute("marginheight", "0"),
        ifr.setAttribute("scrolling", "no"),
        ifr.setAttribute("vspace", "0"),
        ifr.setAttribute("width", "300px"),
        ifr.setAttribute("height", "250px");
    var obj = document.getElementById("t100");
        obj.appendChild(ifr);
    var iframeObj = ifr.contentWindow.document;
    var inTxt = "<script src='//compass.adop.cc/RE/a5dbcee0-a229-4cd7-ac0e-4210872252b5' ></scr"+"ipt>";
        iframeObj.open();
        iframeObj.write(inTxt);
        iframeObj.close();
    <?
        exit("console.log('exit because of count under 1')");

    }

    $icnt = $icnt - 1;

?>

function testgo() {
    var ifr = document.createElement("iframe");
        ifr.setAttribute("id", "Btest" + Math.round(Math.random() * 10000)),
        ifr.setAttribute("frameborder", "0"),
        //ifr.setAttribute("allowtransparency", "true"),
        ifr.setAttribute("hspace", "0"),
        ifr.setAttribute("marginwidth", "0"),
        ifr.setAttribute("marginheight", "0"),
        ifr.setAttribute("scrolling", "no"),
        ifr.setAttribute("vspace", "0"),
        ifr.setAttribute("width", "300px"),
        ifr.setAttribute("height", "250px");
    var obj = document.getElementById("t100");
        obj.appendChild(ifr);
    var iframeObj = ifr.contentWindow.document;
    var inTxt = "<body><div id='t100'></div><scr" + "ipt src='noframe.php?cnt=<?=$icnt?>&rnd=<?=$rndNo?>'></scr" + "ipt></body>";
        iframeObj.open();
        iframeObj.write(inTxt);
        iframeObj.close();
}


testgo();



//c921fe22-aaa5-4ad1-93b1-0750eff96b26 slr 우측 상단 영역
