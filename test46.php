<?php
/*
 * 엑셀비드 연동 테스트용 페이지
 * 2018.01.31 테스팅
 * 온누리 dmc 개발자 손광고 010-3277-7664
 *
 * */
?>
<!doctype html>
<html lang="ko">
<head>
</head>
<body>

<script type='text/javascript'>;
    !function (w,d,s,u,t,ss,fs) {
        if(w.exelbidtag)return;t=w.exelbidtag={};if(!window.t) window.t = t;
        t.push = function() {t.callFunc?t.callFunc.apply(t,arguments) : t.cmd.push(arguments);};
        t.cmd=[];ss = document.createElement(s);ss.async=!0;ss.src=u;
        fs=d.getElementsByTagName(s)[0];fs.parentNode.insertBefore(ss,fs);
    }(window,document,'script','//st2.exelbid.com/js/ads.js');
</script>

<script type='text/javascript'>
    var old1 = new Date() ;
    // You can get add request result
    function ExelbidResponseCallback_6098610bb48794a65b78c3b76fec34035b057617(result){
        if(result.status == 'OK'){
            console.log('OK');
        }else if(result.status == 'NOBID'){
            console.log('NOBID');
            var new1 = new Date();
            console.log ("old 1 time : " + (new1 - old1));
        }else if(result.status == 'ERROR'){
            console.log('ERROR');
        }
    };
    exelbidtag.push(function () {
        exelbidtag.initAdBanner('6098610bb48794a65b78c3b76fec34035b057617', 300, 250, 'div-exelbid-6098610bb48794a65b78c3b76fec34035b057617')
            .setResponseCallback(ExelbidResponseCallback_6098610bb48794a65b78c3b76fec34035b057617)
            .setTestMode(false);
    });
</script>
<!-- Ad Space -->
<div id='div-exelbid-6098610bb48794a65b78c3b76fec34035b057617'>
    <script type='text/javascript'>
        exelbidtag.push(function () {
            exelbidtag.loadAd('6098610bb48794a65b78c3b76fec34035b057617');
        });
    </script>
</div>
</body>
</html>