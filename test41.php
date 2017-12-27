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

<script src="http://compass.adop.cc/ST/21788cbe-df9d-44ed-8cc0-d0119b73c81b"></script>



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
    <script async src='http://compasscdn.adop.cc/js/adopHB.js?v=14' ></script>
    <ins class='adsbyadop' _adop_zon = 'ba6bed1d-7d32-4336-985c-8c789dd489e5' _adop_type = 'hb' style='display:inline-block;width:300px;height:250px;' _page_url=''></ins>
    <script async src='http://compasscdn.adop.cc/js/jsadopHB.js?v=15' ></script>
    <ins class='adsbyadop' _adop_zon = '152056bf-665a-4310-9b32-60da898af9b9' _adop_type = 'hb' style='display:inline-block;width:300px;height:250px;' _page_url=''></ins>

</body>
</html>