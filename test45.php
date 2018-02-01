<?php
/*
 * 통합로그인 에러 체크용으로 만든 페이지
 *
 *
 * */
?>
<!DOCTYPE html>
<head>
    <title>ADOP LOGIN</title>
    <base href="http://go.adop.cc/">
    <!-- BEGIN META -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="keywords" content="your,keywords">
    <meta name="description" content="Short explanation about this website">
    <!-- END META -->

    <!-- favicon -->
    <link href="../../../assets/src/img/favicon/apple-icon-180x180.png" rel="apple-touch-icon" sizes="180x180" />
    <link href="../../../assets/src/img/favicon/favicon-32x32.png" rel="icon" sizes="32x32" type="image/png" />
    <link href="http://dezf3o8j9jdt6.cloudfront.net/ADOP_Common/Image/favicon_adop_16x16.ico" rel="icon" sizes="16x16" type="image/x-icon" />
    <link href="../../../assets/src/img/favicon/manifest.json" rel="manifest" />
    <meta content="#ffffff" name="theme-color" />
    <!-- end. favicon -->

    <!-- BEGIN STYLESHEETS -->
    <link href='http://fonts.googleapis.com/css?family=Roboto:300italic,400italic,300,400,500,700,900' rel='stylesheet' type='text/css'/>
    <link type="text/css" rel="stylesheet" href="../../assets/src/css/adop/adop.bootstrap.css" />
    <link type="text/css" rel="stylesheet" href="../../assets/src/css/adop/adop.materialadmin.css" />
    <link type="text/css" rel="stylesheet" href="../../assets/src/css/theme-default/font-awesome.min.css" />
    <link type="text/css" rel="stylesheet" href="../../assets/src/css/theme-default/material-design-iconic-font.min.css" />
    <link type="text/css" rel="stylesheet" href="../../assets/src/css/theme-default/libs/rickshaw/rickshaw.css" />
    <link type="text/css" rel="stylesheet" href="../../assets/src/css/theme-default/libs/morris/morris.core.css" />
    <link type="text/css" rel="stylesheet" href="../../assets/src/css/theme-default/libs/toastr/toastr.css?1422823374">
    <link type="text/css" rel="stylesheet" href="../../assets/src/css/theme-default/libs/flag-icon-css/css/flag-icon.css" />
    <!-- END STYLESHEETS -->

    <!-- BEGIN ADOP -->
    <link type="text/css" rel="stylesheet" href="../../assets/src/css/adop/adop.common.css" />
    <link type="text/css" rel="stylesheet" href="../../assets/src/css/adop/adserver.common.css" />
    <!-- END ADOP -->

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script type="text/javascript" src="../../assets/src/js/libs/utils/html5shiv.js?1403934957"></script>
    <script type="text/javascript" src="../../assets/src/js/libs/utils/respond.min.js?1403934956"></script>
    <![endif]-->
    <script src="../../assets/src/js/libs/jquery/jquery-1.11.2.min.js"></script>
    <script src="../../assets/src/js/libs/jquery/jquery-migrate-1.2.1.min.js"></script>
    <script src="../../assets/src/js/libs/bootstrap/bootstrap.min.js"></script>
    <script src="../../assets/src/js/libs/spin.js/spin.min.js"></script>
    <script src="../../assets/src/js/libs/autosize/jquery.autosize.min.js"></script>
    <script src="../../assets/src/js/libs/nanoscroller/jquery.nanoscroller.min.js"></script>
    <!--<script src="//www.codecovers.eu/assets/js/modules/materialadmin/core/cache/63d0445130d69b2868a8d28c93309746.js"></script>-->
    <script src="../../assets/src/js/core/demo/Demo.js"></script>
    <!--<script src="../../assets/src/js/libs/md5/md5.js"></script>-->

    <style>

        body{
            font-family: Roboto;
            overflow: hidden;
            background-color:#fff;
        }
        #canvas{
            width:100%;
            position: absolute;
            z-index: 1;
            left:0px;
        }
        .wrapper{
            z-index:999;
            position: absolute;
            left:0px;
        }
        input { outline:0; }
        button{padding:0; margin:0; font-family:inherit; background:none; border:0; outline:none;}
        button:active{outline:none !important;}

        .d-block{display:block;}
        .pa-t10{padding-top:10px;}
        .pa-r3{padding-right:3px;}
        .ma-t20{margin-top:20px;}
        .overflow-y{overflow:auto !important;}
        .media-width{width:440px;}
        @media (max-width: 320px) {
            .media-width{
                width: 250px;
            }
        }
        .btn-bordered{background-color:transparent; border:1px solid #ccc; color:#555;}
        .loginBox{width:100%; padding:0; background-color:#fff; color:#000; box-shadow:none;}
        .loginBox .idInput input:focus{border:1px solid #000;}
        .loginBox .idInput input{display:block; width:100%; padding:8px; border:1px solid #ccc; }
        .loginBox .idInput input[name='id']{margin-bottom:5px !important;}
        .btn-primary:hover .btn-bordered { background-color:#2a3a42;}

        @media (max-width: 1200px){
            .adBox{display:none;}
        }

        .loadingImg{
            position:absolute;
            top:0px;
            right:0px;
            width:100%;
            height:100%;
            background-color:#fff;
            background-image:url('/assets/src/img/ajax-loader.gif');
            background-repeat:no-repeat;
            background-position:center;
            z-index:10000000;
            opacity: 0.4;
            filter: alpha(opacity=40); /* For IE8 and earlier */
        }
    </style>
</head>
<body class="menubar-hoverable header-fixed overflow-y">
<!-- BEGIN LOGIN SECTION -->
<section class="section-account">
    <div class="row" style="margin:40px 0;">
        <div class="col-md-2"></div>
        <div class="col-md-8">
            <div style="max-width:800px; margin:0 auto;">
                <div class="col-lg-12 no-padding">
                    <div style="border-bottom:3px solid #ddd;">
                        <div class="d-inblock"><img src="http://www.adop.cc/wp-content/uploads/2017/09/logo_adop_150x50.png"/></div>
                        <div class="btn-group d-inblock float-r pa-t15">
                            <button type="button" class="btn btn-xs flag-btn btn-flat" name="ko"><i class="flag-icon flag-icon-kr text-lg bd-default"></i></button>
                            <button type="button" class="btn btn-xs flag-btn btn-flat" name="en"><i class="flag-icon flag-icon-us text-lg bd-default"></i></button>
                            <button type="button" class="btn btn-xs flag-btn btn-flat" name="in"><i class="flag-icon flag-icon-id text-lg bd-default"></i></button>
                            <button type="button" class="btn btn-xs flag-btn btn-flat" name="th"><i class="flag-icon flag-icon-th text-lg bd-default"></i></button>
                        </div>
                    </div>
                    <p class="text-center plzLogin" style="margin:40px 0;"><span class="text-xl">ADOP 통합 로그인 페이지입니다.</span><br>로그인하시면 애드오피의 다양한 플랫폼을 이용하실 수 있습니다.<br>기존 사용자는 인사이트(INSIGHT) 아이디로 로그인해주세요.</p>
                    <!--<p class="text-xl text-center plzLogin" style="margin:40px 0;">ADOP 통합로그인 페이지입니다.</p>-->
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-head">
                                <ul class="nav nav-tabs" data-toggle="tabs">
                                    <li class="active"><a href="#first1" data-toggle="tab"><span class="pLogin">퍼블리셔 센터</span></a></li>
                                    <li class=""><a href="#second1" data-toggle="tab"><span class="aLogin">광고주 센터</span></a></li>
                                </ul>
                            </div>
                            <div class="card-body tab-content">
                                <div class="tab-pane active" id="first1">
                                    <div class="col-md-12 col-lg-6 ma-t10">
                                        <form class="loginBox form-horizontal" id="login" name="login_info" action="/api/account/login_check" method="POST">
                                            <div class="form-group">
                                                <div class="col-sm-12 idInput">
                                                    <input name="id" type="text" class="ma-b5" placeholder="아이디" autofocus="autofocus" data-rule-required="true" data-msg-required="Please enter your username."/>
                                                    <input name="pwd" type="password" placeholder="비밀번호" data-rule-required="true" data-msg-required="Please enter your password." />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-2 control-label text-left pa-t10 beginning">초기화면 :</label>
                                                <div class="col-md-7 ma-b10">
                                                    <label class="radio-inline radio-styled">
                                                        <input type="radio" name="solutionOpt" value="1" checked="checked" /><span>INSIGHT</span>
                                                    </label>
                                                    <label class="radio-inline radio-styled">
                                                        <input type="radio" name="solutionOpt" value="2" /><span>COMPASS</span>
                                                    </label>
                                                </div>
                                                <div class="col-md-3">
                                                    <button type="button" class="btn btn-primary btn-block loginBtn" style="padding:10px 0;" id="loginBtn">Login</button>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 ma-b5">
                                                    <div><button type="button" class="btn btn-primary btn-bordered btn-block" id="findBtn"><span class="findUser">아이디 및 비밀번호 찾기</span></button></div>
                                                </div>
                                                <div class="col-md-6 ma-b5">
                                                    <div><button type="button" class="btn btn-primary btn-bordered btn-block" onclick="signup();"><span class="signUp">회원가입</span></button></div>
                                                </div>
                                            </div>
                                            <input type="hidden" id="selectedRegion" name="region"/>
                                        </form>
                                    </div>
                                    <div class="col-lg-6 adBox">
                                        <div>
                                            <a href="//adop.cc" class="d-block" target="_blank"><img src="//s3.ap-northeast-2.amazonaws.com/adop-common/Image/adserver_img.jpg" alt="" style="display:block; width:360px; min-height:220px; margin:0 auto;" /></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="second1">
                                    <div class="col-md-12 col-lg-6 ma-t10">
                                        <form class="loginBox form-horizontal" id="login2" name="login_info" action="http://atom.adop.cc/auth/logincheck" method="POST">
                                            <div class="form-group">
                                                <div class="col-sm-12 idInput">
                                                    <input name="username" type="text" class="ma-b5"
                                                           placeholder="아이디" autofocus="autofocus"
                                                           data-rule-required="true"
                                                           data-msg-required="Please enter your username."
                                                    />
                                                    <input name="password" type="password"
                                                           placeholder="비밀번호"
                                                           data-rule-required="true"
                                                           data-msg-required="Please enter your password." />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label text-left pa-t10 beginning">초기화면 :</label>
                                                <div class="col-sm-7 ma-b10">
                                                    <label class="radio-inline radio-styled">
                                                        <input type="radio" name="solutionOpt1" value="1" checked="checked" /><span>ATOM</span>
                                                    </label>
                                                </div>
                                                <div class="col-sm-3">
                                                    <button type="submit" class="btn btn-primary btn-block loginBtn" style="padding:10px 0;">Login</button>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12 ma-b5">
                                                    <div><button type="button" class="btn btn-primary btn-bordered btn-block" onclick="atomSignup();"><span class="signUp">회원가입</span></button></div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="col-lg-6 adBox">
                                        <div>
                                            <a href="//adop.cc" class="d-block" target="_blank"><img src="//s3.ap-northeast-2.amazonaws.com/adop-common/Image/adserver_img.jpg" alt="" style="display:block; width:360px; min-height:220px; margin:0 auto;" /></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2"></div>
    </div>
    <footer style="text-align:center;">
        <div class="col-md-2"></div>
        <div class="col-md-8" style="margin-top:60px;">
            <div class="bd-top-default" style="max-width:800px; margin:0 auto;">
                <div class="pa-t30 pa-b30 text-center" style="word-break: keep-all;">
                <span class="footerInfo">
                    Copyright © ADOP All Rights Reserved. 사업자 등록번호: 214-88-82841 / 대표자: 이원섭 / 주소: (06179) 서울특별시 강남구 테헤란 86 14(대치동) 윤천빌딩 4,5층 / Tel: 02-2052-1117 / Email: px@adop.cc
                </span>
                </div>
            </div>
        </div>
        <div class="col-md-2"></div>
    </footer>
</section>
<script>
    var option;
    var region = '';
    $(document).ready(function(){
        var cookieLang = getCookie("lang");
        if(cookieLang != ''){
            region = cookieLang;
        }else{
            region = 'ko';
        }

        setTimeout(chooseLanguage(function(){
            getLanguage();
        }), 100);

//        $("#loadingimg").fadeOut(100);
        $('#loadingimg').css("display", "none");

        $('.flag-btn').on('click', function(e){
            region = $(this).attr('name');
            chooseLanguage(function(){
                getLanguage();
            });
        });

        $("input[name=id]").keyup(function(event) {
            if (event.keyCode === 13) {
                login();
            }
        });

        $("input[name=pwd]").keyup(function(event) {
            if (event.keyCode === 13) {
                login();
            }
        });

        $('#loginBtn').on('click', function(){
            login();
        });

        $('#findBtn').on('click', function(){
            findInfo();
        });
    });

    function getCookie(cookieName) {
        cookieName = cookieName + '=';
        var cookie = document.cookie;
        var cookieValue = '';

        if( cookie.length > 0 ) {
            var start = cookie.indexOf(cookieName);

            if(start != -1){
                start += cookieName.length;
                var end = cookie.indexOf(';', start);
                if(end == -1)end = cookie.length;
                cookieValue = cookie.substring(start, end);
            }
        }
        return decodeURI(cookieValue)
//        return unescape(cookieValue);
    }

    function chooseLanguage(callback){
        if(region == '' || region == undefined){
            region = 'ko';
        }

        $('#selectedRegion').val(region);
        $('.langjs').remove();

        var jScript = document.createElement("script");
        jScript.src = "../../assets/src/js/adop/language."+region+".js";
        jScript.className = "langjs";
        document.body.appendChild(jScript);

        setTimeout(callback, 100);
    }

    function login(){
        var url = '/api/auth/login_check';
        option = $("input[name=solutionOpt]:checked").val();
        var lang = $('#selectedRegion').val();
//        console.log("lang : "+ lang);
        $.ajax({
            url: url,
            type: "POST",
            dataType: "json",
            data: $("#login").serialize(),
            beforeSend  : function(){
                $("#loadingimg").fadeIn(500);
            },
            success: function (data) {
                if(data == 200){
//                    var insight = "http://192.168.0.2:8888/";
//                    var insight = "http://localhost:8888/";
                    var insight = "http://insight.adop.cc/";
                    if(lang != 'ko'){
                        insight = insight + "?lang=" + lang;
                    }

                    if(option == 1){
                        window.location.href = insight;
                    }else if(option == 2){
//                        window.location.href = "http://localhost:8090/login";
//                        loginFake('http://localhost:8888/', function(re){ });
                        loginFake(insight, function(re){ });
                    }else{
                        alert(langs.ko.login.checkPlatform);
                    }
                }else{
                    alert(langs.ko.login.checkUser);
                    $("#loadingimg").fadeOut(500);
                }
            },
            error: function(){
                $("#loadingimg").fadeOut(500);
            }

        });
    }
    function signup(){
        location.href = "/view/signup";
    }

    function atomSignup(){
//            location.href = "http://atom.adop.cc/auth/signup";
        window.open("http://atom.adop.cc/auth/signup");
    }

    function findInfo(){
//        location.href = "http://192.168.0.2:8888/help"
        location.href = "http://insight.adop.cc/help"
    }

    function loginFake(action, callback) {
        var target_name = 'iframe_login';

        var form = $('<form action="'+action+'" method="get" style="display:none;" target="'+target_name+'"></form>');
        $('body').append(form);

        var iframe = $('<iframe src="javascript:false;" name="'+target_name+'" style="display:none;"></iframe>');
        $('body').append(iframe);

        iframe.load(function(){
            if(option ==2){
                window.location.href = "http://compassmgr.adop.cc/login";
//                window.location.href = "http://192.168.0.2:8090/login";
//                window.location.href = "http://localhost:8090/login";
            }
            form.remove();
            iframe.remove();
        });
        form.submit();
    }

    function getLanguage(){
        $('.plzLogin').html(langs.ko.login.pleaseLogin);
        $('.pLogin').html(langs.ko.login.publisherLogin);
        $('.aLogin').html(langs.ko.login.advertiserLogin);
        $('input[name=id], input[name=username]').attr("placeholder", langs.ko.signup.id);
        $('input[name=pwd], input[name=password]').attr("placeholder", langs.ko.login.pwPlaceholder);
        $('.beginning').html(langs.ko.login.initialScreen);
        $('.findUser').html(langs.ko.login.findUser);
        $('.signUp').html(langs.ko.signup.signUp);
        $('.footerInfo').html(langs.ko.footer.info);
    }

</script>
<!-- END LOGIN SECTION -->
<!-- BEGIN JAVASCRIPT -->
</body>
</html>
