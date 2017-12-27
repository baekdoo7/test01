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

    <script type="text/javascript" src="//www.test.com/prebid0_34.js" async></script>
    <script>
        eval(function(p,a,c,k,e,d){e=function(c){return c.toString(36)};if(!''.replace(/^/,String)){while(c--){d[c.toString(a)]=k[c]||c.toString(a)}k=[function(e){return d[e]}];e=function(){return'\\w+'};c=1};while(c--){if(k[c]){p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c])}}return p}('3="";7=m;8 2=2||{};2.4=2.4||{};2.9=2.9||[];2.d=!0;5 n(b){3=2.i();g(8 a e 3)2.h<=3[a].j&&(2.4[a]=3[a].k);6()}5 6(){2.c||(2.c=!0,2.d=!1,l(!1))}f(5(){6()},7);',24,24,'||pbjs|target_bd|que2|function|sendAdserverRequest|PREBID_TIMEOUT|var|que|||adserverRequestSent|stopad02|in|setTimeout|for|floor_price|getAdserverTargeting|hb_pb|hb_adid|showAlladopAd01|2E3|floorPrice001'.split('|'),0,{}));




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
    

    
</head>
<body>
    <h3> 광고영역(300x250) 헤더비딩 영역 </h3>
  <hr /> 
    ... <br />
    <div id="container"></div> 
    <div id="ad2">
        <script type="application/javascript">
            function adop_ad(){
                pbjs.renderAd(document,key);
                //alert(target_bd.dnZTbG90LS8zNDAwOTg4MS9WT05fcXVpel9zdWJtaXRfbW9i.hb_adid);
                var iframe = document.getElementById('abc111');
                var iframeDoc = iframe.contentWindow.document;
    
                //pbjs.renderAd(iframeDoc,target_bd.dnZTbG90LS8zNDAwOTg4MS9WT05fcXVpel9zdWJtaXRfbW9i.hb_adid);
                iframe.width = "300px";
                iframe.height = "250px";
                
                var iframe1 = document.getElementById('abc112');
                var iframeDoc = iframe1.contentWindow.document;
    
                pbjs.renderAd(iframeDoc1,target_bd.k1234321.hb_adid);
                iframe.width = "300px";
                iframe.height = "250px";
            }
            //setTimeout(adop_ad,3000);
            
        </script>
    </div>
<br /> 
    
    <hr />
    
    비딩 영역
    <iframe id="abc111" width="300px" height="250px" ></iframe>
    <iframe id="abc112" width="300px" height="250px" ></iframe>
    <hr />
    ADOP 광고 영역
    <!--
    <script async src='http://compass.adop.cc/assets/js/adop/adopJ.js?v=14' ></script>
    -->
    <script async src='http://www.test.com/adopHB.js?v=14' ></script>
    <ins class='adsbyadop' _adop_zon = 'ba6bed1d-7d32-4336-985c-8c789dd489e5' _adop_type = 'hb' style='display:inline-block;width:300px;height:250px;' _page_url=''></ins>
    <script async src='http://www.test.com/adopHB.js?v=15' ></script>
    <ins class='adsbyadop' _adop_zon = '152056bf-665a-4310-9b32-60da898af9b9' _adop_type = 'hb' style='display:inline-block;width:300px;height:250px;' _page_url=''></ins>

</body>
</html>