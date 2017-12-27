<?php
/*
 DFP 라인 아이템 테스팅을 위한 페이징
*/
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>헤더비딩 테스팅</title>
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

                       
    function sendAdserverRequest() {                  
        if (pbjs.adserverRequestSent) return;
        pbjs.adserverRequestSent = true;
        googletag.cmd.push(function() {
            pbjs.que.push(function() {
                pbjs.setTargetingForGPTAsync();
                googletag.pubads().refresh();
            });
        });
    }

    //setTimeout(function(){
    //    sendAdserverRequest();
   // }, PREBID_TIMEOUT);

pbjs.bidderSettings = {
          audienceNetwork: {
            adserverTargeting: [
                  {
                      key: "fb_bidid",
                      val: function (bidResponse) {
                          // make the bidId available for targeting if required
                          return bidResponse.fbBidId;
                      } 
                  },
                  {   
                      key: "hb_bidder",
                      val: function (bidResponse) {
                          return bidResponse.bidderCode;
                      }
                  },
                  {
                    key: "hb_pb",
                    val: function(bidResponse) {
                      return bidResponse.pbMg;
                    }
                  },
              ],
          },
      };
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
                //pbjs.renderAd(document,key);
                //alert(target_bd.dnZTbG90LS8zNDAwOTg4MS9WT05fcXVpel9zdWJtaXRfbW9i.hb_adid);
                var iframe = document.getElementById('abc111');
                var iframeDoc = iframe.contentWindow.document;
    
                pbjs.renderAd(iframeDoc,target_bd.dnZTbG90LS8zNDAwOTg4MS9WT05fcXVpel9zdWJtaXRfbW9i.hb_adid);
                iframe.width = "300px";
                iframe.height = "250px";
            }
            //setTimeout(adop_ad,3000);
            
        </script>
    </div>
<br /> 
    
    <hr />
    
    <iframe id="abc111" width="300px" height="250px" ></iframe>
    
    
</body>
</html>