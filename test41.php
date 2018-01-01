<?php
/*
    <script type="text/javascript" src="//acdn.adnxs.com/prebid/not-for-prod/prebid.js" async></script>
    <script>
    target_bd = "";
    PREBID_TIMEOUT = 2000;
    var pbjs = pbjs || {};
    pbjs.que2 =  pbjs.que2 || {};
    pbjs.que = pbjs.que || [];


    //광고 스탑
    pbjs.stopad02 = true;
    //광고 콜백 처리
    function floorPrice001(bidResponses) {
        var targetingParams = pbjs.getAdserverTargeting();
        target_bd = targetingParams;
        for(var areaid in target_bd){
            //console.log("test001:  "+target_bd[areaid].hb_adid);
            if(pbjs.floor_price <= target_bd[areaid].hb_pb){
                pbjs.que2[areaid] = target_bd[areaid].hb_adid;
            }
        }
        sendAdserverRequest();

    }
    //광고시작함수
    function sendAdserverRequest(){
        if(pbjs.adserverRequestSent) return;
        pbjs.adserverRequestSent = true;
        pbjs.stopad02 = false;
        showAlladopAd01(false);
    }
    //타임아웃 후 기본 광고 노출
    setTimeout(function(){sendAdserverRequest();}, PREBID_TIMEOUT);


    pbjs.floor_price = 0.5;
    pbjs.que.push(function() {
        var adUnits = [{
            //code: 'div-gpt-ad-1509514211752-0',
            code: 'ba6bed1d-7d32-4336-985c-8c789dd489e5',
            sizes: [[300, 250]],
            bids: [{
                bidder: "criteo",
                params: {
                    //zoneId: "128474"
                    zoneId: "962234",
                    slotId: "div-criteo-962234"

                }
            },
            {
                bidder: 'appnexus',
                params: { placementId: '10433394' }
            },
            {
	           bidder: "rubicon",
	           params: {
		          accountId: "4934",
		          siteId: "13945",
		          zoneId: "23948",
		          sizes: [15]
	           }
	       },{
                bidder: 'indexExchange',
                    params: {
                        id: '18',
                        siteID: '196080'
                    }
           },
            {
	           bidder: 'sovrn',
	           params: { tagId: '315045' }
	       },
            {
                bidder: 'audienceNetwork',
                params: {
                    placementId: '685543434893182_951487728298750'
                }
            }
            ]
        },
       {
         code: '152056bf-665a-4310-9b32-60da898af9b9',
            sizes: [[300, 250]],
            bids: [{
                bidder: "criteo",
                params: {
                    //zoneId: "128474"
                    zoneId: "962235",
                    slotId: "div-criteo-962234"

                }
            },
            {
                bidder: 'appnexus',
                params: { placementId: '10433394' }
            },
            {
                bidder: 'audienceNetwork',
                params: {
                    placementId: '685543434893182_951487728298751'
                }
            }]
        }];
        pbjs.addAdUnits(adUnits);
        pbjs.requestBids({
            bidsBackHandler: floorPrice001
        });
    });


</script>
 */

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>헤더비딩 테스팅</title>
<!--
<script src="http://compass.adop.cc/ST/21788cbe-df9d-44ed-8cc0-d0119b73c81b"></script>
-->

    <script type="text/javascript">
        function MD5(sMessage) {
            function RotateLeft(lValue, iShiftBits) {
                return (lValue<<iShiftBits) | (lValue>>>(32-iShiftBits));
            }
            function AddUnsigned(lX,lY) {
                var lX4,lY4,lX8,lY8,lResult;
                lX8 = (lX & 0x80000000);
                lY8 = (lY & 0x80000000);
                lX4 = (lX & 0x40000000);
                lY4 = (lY & 0x40000000);
                lResult = (lX & 0x3FFFFFFF)+(lY & 0x3FFFFFFF);
                if(lX4 & lY4)
                    return (lResult ^ 0x80000000 ^ lX8 ^ lY8);
                if (lX4 | lY4) {
                    if (lResult & 0x40000000)
                        return (lResult ^ 0xC0000000 ^ lX8 ^ lY8);
                    else
                        return (lResult ^ 0x40000000 ^ lX8 ^ lY8);
                }
                else
                    return (lResult ^ lX8 ^ lY8);
            }

            function F(x,y,z) {
                return (x & y) | ((~x) & z);
            }
            function G(x,y,z) {
                return (x & z) | (y & (~z));
            }
            function H(x,y,z) {
                return (x ^ y ^ z);
            }
            function I(x,y,z) {
                return (y ^ (x | (~z)));
            }
            function FF(a,b,c,d,x,s,ac) {
                a = AddUnsigned(a, AddUnsigned(AddUnsigned(F(b, c, d), x), ac));
                return AddUnsigned(RotateLeft(a, s), b);
            }
            function GG(a,b,c,d,x,s,ac) {
                a = AddUnsigned(a, AddUnsigned(AddUnsigned(G(b, c, d), x), ac));
                return AddUnsigned(RotateLeft(a, s), b);
            }
            function HH(a,b,c,d,x,s,ac) {
                a = AddUnsigned(a, AddUnsigned(AddUnsigned(H(b, c, d), x), ac));
                return AddUnsigned(RotateLeft(a, s), b);
            }
            function II(a,b,c,d,x,s,ac) {
                a = AddUnsigned(a, AddUnsigned(AddUnsigned(I(b, c, d), x), ac));
                return AddUnsigned(RotateLeft(a, s), b);
            }
            function ConvertToWordArray(sMessage) {
                var lWordCount;
                var lMessageLength = sMessage.length;
                var lNumberOfWords_temp1=lMessageLength + 8;
                var lNumberOfWords_temp2=(lNumberOfWords_temp1-(lNumberOfWords_temp1 % 64))/64;
                var lNumberOfWords = (lNumberOfWords_temp2+1)*16;
                var lWordArray = Array(lNumberOfWords-1);
                var lBytePosition = 0;
                var lByteCount = 0;
                while ( lByteCount < lMessageLength ) {
                    lWordCount = (lByteCount-(lByteCount % 4))/4;
                    lBytePosition = (lByteCount % 4)*8;
                    lWordArray[lWordCount] = (lWordArray[lWordCount] | (sMessage.charCodeAt(lByteCount)<<lBytePosition));
                    lByteCount++;
                }
                lWordCount = (lByteCount-(lByteCount % 4))/4;
                lBytePosition = (lByteCount % 4)*8;
                lWordArray[lWordCount] = lWordArray[lWordCount] | (0x80<<lBytePosition);
                lWordArray[lNumberOfWords-2] = lMessageLength<<3;
                lWordArray[lNumberOfWords-1] = lMessageLength>>>29;
                return lWordArray;
            }
            function WordToHex(lValue) {
                var WordToHexValue="",WordToHexValue_temp="",lByte,lCount;
                for (lCount=0; lCount<=3; lCount++) {
                    lByte = (lValue>>>(lCount*8)) & 255;
                    WordToHexValue_temp = "0" + lByte.toString(16);
                    WordToHexValue = WordToHexValue + WordToHexValue_temp.substr(WordToHexValue_temp.length-2,2);
                }
                return WordToHexValue;
            }
            var x = Array();
            var k,AA,BB,CC,DD,a,b,c,d;
            var S11=7, S12=12, S13=17, S14=22;
            var S21=5, S22=9 , S23=14, S24=20;
            var S31=4, S32=11, S33=16, S34=23;
            var S41=6, S42=10, S43=15, S44=21;
            // Steps 1 and 2.  Append padding bits and length and convert to words
            x = ConvertToWordArray(sMessage);
            // Step 3.  Initialise
            a = 0x67452301; b = 0xEFCDAB89; c = 0x98BADCFE; d = 0x10325476;
            // Step 4.  Process the message in 16-word blocks
            for (k=0;k<x.length;k+=16) {
                AA=a; BB=b; CC=c; DD=d;
                a = FF(a,b,c,d,x[k+0], S11,0xD76AA478);
                d = FF(d,a,b,c,x[k+1], S12,0xE8C7B756);
                c = FF(c,d,a,b,x[k+2], S13,0x242070DB);
                b = FF(b,c,d,a,x[k+3], S14,0xC1BDCEEE);
                a = FF(a,b,c,d,x[k+4], S11,0xF57C0FAF);
                d = FF(d,a,b,c,x[k+5], S12,0x4787C62A);
                c = FF(c,d,a,b,x[k+6], S13,0xA8304613);
                b = FF(b,c,d,a,x[k+7], S14,0xFD469501);
                a = FF(a,b,c,d,x[k+8], S11,0x698098D8);
                d = FF(d,a,b,c,x[k+9], S12,0x8B44F7AF);
                c = FF(c,d,a,b,x[k+10],S13,0xFFFF5BB1);
                b = FF(b,c,d,a,x[k+11],S14,0x895CD7BE);
                a = FF(a,b,c,d,x[k+12],S11,0x6B901122);
                d = FF(d,a,b,c,x[k+13],S12,0xFD987193);
                c = FF(c,d,a,b,x[k+14],S13,0xA679438E);
                b = FF(b,c,d,a,x[k+15],S14,0x49B40821);
                a = GG(a,b,c,d,x[k+1], S21,0xF61E2562);
                d = GG(d,a,b,c,x[k+6], S22,0xC040B340);
                c = GG(c,d,a,b,x[k+11],S23,0x265E5A51);
                b = GG(b,c,d,a,x[k+0], S24,0xE9B6C7AA);
                a = GG(a,b,c,d,x[k+5], S21,0xD62F105D);
                d = GG(d,a,b,c,x[k+10],S22,0x2441453);
                c = GG(c,d,a,b,x[k+15],S23,0xD8A1E681);
                b = GG(b,c,d,a,x[k+4], S24,0xE7D3FBC8);
                a = GG(a,b,c,d,x[k+9], S21,0x21E1CDE6);
                d = GG(d,a,b,c,x[k+14],S22,0xC33707D6);
                c = GG(c,d,a,b,x[k+3], S23,0xF4D50D87);
                b = GG(b,c,d,a,x[k+8], S24,0x455A14ED);
                a = GG(a,b,c,d,x[k+13],S21,0xA9E3E905);
                d = GG(d,a,b,c,x[k+2], S22,0xFCEFA3F8);
                c = GG(c,d,a,b,x[k+7], S23,0x676F02D9);
                b = GG(b,c,d,a,x[k+12],S24,0x8D2A4C8A);
                a = HH(a,b,c,d,x[k+5], S31,0xFFFA3942);
                d = HH(d,a,b,c,x[k+8], S32,0x8771F681);
                c = HH(c,d,a,b,x[k+11],S33,0x6D9D6122);
                b = HH(b,c,d,a,x[k+14],S34,0xFDE5380C);
                a = HH(a,b,c,d,x[k+1], S31,0xA4BEEA44);
                d = HH(d,a,b,c,x[k+4], S32,0x4BDECFA9);
                c = HH(c,d,a,b,x[k+7], S33,0xF6BB4B60);
                b = HH(b,c,d,a,x[k+10],S34,0xBEBFBC70);
                a = HH(a,b,c,d,x[k+13],S31,0x289B7EC6);
                d = HH(d,a,b,c,x[k+0], S32,0xEAA127FA);
                c = HH(c,d,a,b,x[k+3], S33,0xD4EF3085);
                b = HH(b,c,d,a,x[k+6], S34,0x4881D05);
                a = HH(a,b,c,d,x[k+9], S31,0xD9D4D039);
                d = HH(d,a,b,c,x[k+12],S32,0xE6DB99E5);
                c = HH(c,d,a,b,x[k+15],S33,0x1FA27CF8);
                b = HH(b,c,d,a,x[k+2], S34,0xC4AC5665);
                a = II(a,b,c,d,x[k+0], S41,0xF4292244);
                d = II(d,a,b,c,x[k+7], S42,0x432AFF97);
                c = II(c,d,a,b,x[k+14],S43,0xAB9423A7);
                b = II(b,c,d,a,x[k+5], S44,0xFC93A039);
                a = II(a,b,c,d,x[k+12],S41,0x655B59C3);
                d = II(d,a,b,c,x[k+3], S42,0x8F0CCC92);
                c = II(c,d,a,b,x[k+10],S43,0xFFEFF47D);
                b = II(b,c,d,a,x[k+1], S44,0x85845DD1);
                a = II(a,b,c,d,x[k+8], S41,0x6FA87E4F);
                d = II(d,a,b,c,x[k+15],S42,0xFE2CE6E0);
                c = II(c,d,a,b,x[k+6], S43,0xA3014314);
                b = II(b,c,d,a,x[k+13],S44,0x4E0811A1);
                a = II(a,b,c,d,x[k+4], S41,0xF7537E82);
                d = II(d,a,b,c,x[k+11],S42,0xBD3AF235);
                c = II(c,d,a,b,x[k+2], S43,0x2AD7D2BB);
                b = II(b,c,d,a,x[k+9], S44,0xEB86D391);
                a = AddUnsigned(a,AA); b=AddUnsigned(b,BB); c=AddUnsigned(c,CC); d=AddUnsigned(d,DD);
            }
            // Step 5.  Output the 128 bit digest
            var temp = WordToHex(a) + WordToHex(b) + WordToHex(c) + WordToHex(d);
            return temp.toLowerCase();
        }
</script>
    <script type="application/javascript">
        function getUserIP(onNewIP) { //  onNewIp - your listener function for new IPs
            //compatibility for firefox and chrome
            var myPeerConnection = window.RTCPeerConnection || window.mozRTCPeerConnection || window.webkitRTCPeerConnection;
            var pc = new myPeerConnection({
                    iceServers: []
                }),
                noop = function() {},
                localIPs = {},
                ipRegex = /([0-9]{1,3}(\.[0-9]{1,3}){3}|[a-f0-9]{1,4}(:[a-f0-9]{1,4}){7})/g,
                key;

            function iterateIP(ip) {
                if (!localIPs[ip]) onNewIP(ip);
                localIPs[ip] = true;
            }

            //create a bogus data channel
            pc.createDataChannel("");

            // create offer and set local description
            pc.createOffer().then(function(sdp) {
                sdp.sdp.split('\n').forEach(function(line) {
                    if (line.indexOf('candidate') < 0) return;
                    line.match(ipRegex).forEach(iterateIP);
                });

                pc.setLocalDescription(sdp, noop, noop);
            }).catch(function(reason) {
                // An error occurred, so handle the failure to connect
            });

            //listen for candidate events
            pc.onicecandidate = function(ice) {
                if (!ice || !ice.candidate || !ice.candidate.candidate || !ice.candidate.candidate.match(ipRegex)) return;
                ice.candidate.candidate.match(ipRegex).forEach(iterateIP);
            };
        }
    </script>
</head>
<body>
    <h3> 광고영역(300x250) 헤더비딩 영역 </h3>
  <hr /> 
    ... <br />

    
    <hr />
        ADOP 광고 영역
    <hr />

    <!--
    <script async src='http://compass.adop.cc/assets/js/adop/adopJ.js?v=14' ></script>
    -->
    <!--
    <script async src='http://compasscdn.adop.cc/js/adopHB.js?v=14' ></script>
    <ins class='adsbyadop' _adop_zon = 'ba6bed1d-7d32-4336-985c-8c789dd489e5' _adop_type = 'hb' style='display:inline-block;width:300px;height:250px;' _page_url=''></ins>
    <script async src='http://compasscdn.adop.cc/js/jsadopHB.js?v=15' ></script>
    <ins class='adsbyadop' _adop_zon = '152056bf-665a-4310-9b32-60da898af9b9' _adop_type = 'hb' style='display:inline-block;width:300px;height:250px;' _page_url=''></ins>
    -->

<hr />
<canvas id="drawing" style="display: block"></canvas>
    <img id="itest" src="">

    <script type="application/javascript">
        var canvas = document.getElementById("drawing");
        var ctx = canvas.getContext("2d");
        var txt = navigator.platform + " "+ window.screen.colorDepth;

        ctx.textBaseline = "top";
        ctx.font = "14px 'Arial'";
        ctx.textBaseline = "alphabetic";
        ctx.fillStyle = "#f60";
        ctx.fillRect(125,1,62,20);
        ctx.fillStyle = "#069";
        ctx.fillText(txt, 2, 15);
        ctx.fillStyle = "rgba(102, 204, 0, 0.7)";
        ctx.fillText(txt, 4, 17);

        //var myImage = document.getElementById('itest');
        //myImage.src = canvas.toDataURL();

        console.log(MD5(canvas.toDataURL("image/jpeg")));
        document.write(MD5(canvas.toDataURL("image/jpeg")));
        document.write("<br />");
        getUserIP(function (ip) {
            alert(ip);
        });
        //document.write(canvas.toDataURL());
        //console.log(canvas.toDataURL.replace("data:image/png;base64,",""));
        //console.log(canvas.toDataURL);



    </script>
</body>
</html>