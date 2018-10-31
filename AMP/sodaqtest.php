<?php
/**
 * Created by IntelliJ IDEA.
 * User: baekdoo
 * Date: 2018. 10. 30.
 * Time: PM 3:30
 */
?>


<!doctype html>
<html lang="ko" ng-app="GoodocSocdoc">
<head>
    <base href="http://test-goodoc-socdoc.s3-website-ap-northeast-1.amazonaws.com/">
    <meta charset="UTF-8">
    <meta name="fragment" content="!">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="apple-touch-fullscreen" content="yes">
    <meta name="mobile-web-app-capable" content="yes">
    <title>언니가 갖고 싶어서 만든 앱, 속닥</title>
    <meta name="description" content="언니가 가지고 싶어서 만든 언니들의 익명 커뮤니티앱, 속닥!">
    <meta name="keywords" content="여성커뮤니티, 익명커뮤니티, 골라줘, 뷰티, 패션, 다이어트, 결혼, 시집, 연애, 성고민, 취업, 직장, 10대, 20대, 30대">
    <meta property="og:title" content="언니가 갖고 싶어서 만든 앱, 속닥">
    <meta property="og:description" content="언니가 가지고 싶어서 만든 언니들의 익명 커뮤니티앱, 속닥!">
    <meta property="og:image" content="http://images.goodoc.kr/images/socdoc.png">
    <meta property="og:url" content="http://www.socdoc.co.kr">
    <meta name="naver-site-verification" content="dc51aa95194ee6675186dc6f7ed7968ff5b0f51c"/>
    <link rel="apple-touch-icon" sizes="57x57" href="./img/favicon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="./img/favicon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="./img/favicon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="./img/favicon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="./img/favicon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="./img/favicon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="./img/favicon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="./img/favicon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="./img/favicon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="./img/favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="./img/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="./img/favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="./img/favicon/favicon-16x16.png">
    <link rel="manifest" href="./img/favicon/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="./img/favicon/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
    <!-- STYLES -->
    <link rel="stylesheet" href="lib/css/lib.min.css"/>
    <!-- Custom CSS -->
    <link rel="stylesheet" type="text/css" href="css/style.css?1540864195">
    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-29903049-17', 'auto');
        ga('send', 'pageview');
    </script>

</head>
<body ng-class="{ index: $state.includes('index') }" ng-controller="MasterCtrl" ng-cloak>
<!-- Main Content -->
<div ui-view class="fade-effect"></div>
<div class="loading" ng-show="loading">
    <svg id="load" version="1.1" id="loader-1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
         viewBox="0 0 50 50" style="enable-background:new 0 0 50 50;" xml:space="preserve">
            <path fill="#000" d="M43.935,25.145c0-10.318-8.364-18.683-18.683-18.683c-10.318,0-18.683,8.365-18.683,18.683h4.068c0-8.071,6.543-14.615,14.615-14.615c8.072,0,14.615,6.543,14.615,14.615H43.935z">
                <animateTransform attributeType="xml"
                                  attributeName="transform"
                                  type="rotate"
                                  from="0 25 25"
                                  to="360 25 25"
                                  dur="0.6s"
                                  repeatCount="indefinite"/>
            </path>
        </svg>
</div>
<!-- SCRIPTS -->
<script src="lib/js/lib.min.js"></script>
<!-- Custom Scripts -->
<script src="templates/templateCachePartials.js?1540864195"></script>
<script type="text/javascript" src="js/app.min.js?1540864195"></script>
<script src="//developers.kakao.com/sdk/js/kakao.min.js"></script>
<script>
    // 테스트/리얼 서버 분리 코드
    var url = document.location.host;
    var server = '';
    console.log(url)
    if(url == 'www.socdoc.co.kr' || url == 'socdoc.co.kr'){
        server = 'prod'
        if(location.hash) {
            window.redirectUrl = location.hash.substring(2);
        }
    }else{
        server = 'test'
    }
    Kakao.init('d1d6e2215bf01be773dec13b8c4b97a2');
</script>
</body>
</html>

