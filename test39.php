<?php
/*
    <script type="text/javascript" src="//acdn.adnxs.com/prebid/not-for-prod/prebid.js" async></script>
    <script>
    target_bd = "";    
    PREBID_TIMEOUT = 700;    
    var pbjs = pbjs || {};
    pbjs.que = pbjs.que || [];

    pbjs.que.push(function() {
        //685543434893182_951487728298750
        var adUnits = [{
            //code: 'div-gpt-ad-1509514211752-0',
            code: 'dnZTbG90LS8zNDAwOTg4MS9WT05fcXVpel9zdWJtaXRfbW9i',
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
   
            // Add other bidders here
            ]
        }];
        pbjs.addAdUnits(adUnits);
        pbjs.requestBids({
            // This callback gets triggered when all bids for this
            // ad unit come back. 
            bidsBackHandler: function(bidResponses) {
                console.log("------------------------------------------------");
                console.log(bidResponses);
                console.log("------------------------------------------------");
                var targetingParams = pbjs.getAdserverTargeting();
                var container = document.getElementById('container');
                container.innerHTML = JSON.stringify(targetingParams);
                target_bd = targetingParams;
                adop_ad();
               
                
            }
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

    <script type="text/javascript" src="//acdn.adnxs.com/prebid/not-for-prod/prebid.js" async></script>
    <script>
    target_bd = "";    
    PREBID_TIMEOUT = 2000;    
    var pbjs = pbjs || {};
    pbjs.que2 =  pbjs.que2 || {};
    pbjs.que = pbjs.que || [];
    pbjs.floor_price = 0.6;
    pbjs.que.push(function() {
        //685543434893182_951487728298750
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
   
            // Add other bidders here
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
            // This callback gets triggered when all bids for this
            // ad unit come back. 
            bidsBackHandler: function(bidResponses) {
                console.log("------------------------------------------------");
                console.log(bidResponses);
                console.log("------------------------------------------------");
                var targetingParams = pbjs.getAdserverTargeting();
                var container = document.getElementById('container');
                container.innerHTML = JSON.stringify(targetingParams);
                target_bd = targetingParams;
                for(var areaid in target_bd){
                    //console.log("test001:  "+target_bd[areaid].hb_adid);
                    if(pbjs.floor_price <= target_bd[areaid].hb_pb){
                        pbjs.que2[areaid] = target_bd[areaid].hb_adid;   
                       }
                    
                }
                sendAdserverRequest();
                
               
                
            }
        });
    });  
    
        
    //광고 스탑
    pbjs.stopad02 = true;    
    //광고시작함수    
    function sendAdserverRequest(){
         if(pbjs.adserverRequestSent) return;
         pbjs.adserverRequestSent = true;
         pbjs.stopad02 = false;
         showAlladopAd01(false);
        
        
    }     
    //타임아웃 후 기본 광고 노출    
    setTimeout(function(){sendAdserverRequest();}, PREBID_TIMEOUT);
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