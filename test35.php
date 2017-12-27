<?php
/*
 DFP 라인 아이템 테스팅을 위한 페이징
*/
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>DFP 테스팅</title>
    <script type="text/javascript" src="//acdn.adnxs.com/prebid/not-for-prod/prebid.js" async></script>
    <script async='async' src='https://www.googletagservices.com/tag/js/gpt.js'></script>
<script>
  var googletag = googletag || {};
  googletag.cmd = googletag.cmd || [];
</script>

<script>
  googletag.cmd.push(function() { 
    googletag.defineSlot('/43653566/L_300x250', [300, 250], 'div-gpt-ad-1509512610309-0').addService(googletag.pubads());
    googletag.defineSlot('/43653566/sallytestzone', [300, 250], 'div-gpt-ad-1509512935746-0').addService(googletag.pubads());
       googletag.defineSlot('/43653566/ad_test_300x250', [300, 250], 'div-gpt-ad-1509514211752-0').addService(googletag.pubads());
    googletag.pubads().enableSingleRequest();
    googletag.enableServices();
    googletag.pubads().disableInitialLoad();   
  });
</script>
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
            sizes: [[300, 250], [300, 600]],
            bids: [{
                bidder: "criteo",
                params: {
                    zoneId: "128474"
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

    setTimeout(function(){
        sendAdserverRequest();
    }, PREBID_TIMEOUT);

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
    <h3>광고영역(300x250) 첫번째</h3>
    <!-- /43653566/L_300x250 -->
        <div id='div-gpt-ad-1509512610309-0' style='height:250px; width:300px;'>
            <script>
                googletag.cmd.push(function() { googletag.display('div-gpt-ad-1509512610309-0'); });
            </script>
        </div>
    <h3>광고영역(300x250) 두번째</h3>
    <!-- /43653566/sallytestzone -->
        <div id='div-gpt-ad-1509512935746-0' style='height:250px; width:300px;'>
                <script>
                        googletag.cmd.push(function() { googletag.display('div-gpt-ad-1509512935746-0'); });
                </script>
        </div>
    <h3>광고영역(300x250) 세번째</h3>
    <!-- /43653566/ad_test_300x250 -->
    <div id='div-gpt-ad-1509514211752-0' style='height:250px; width:300px;'>
        <script>
            googletag.cmd.push(function() { googletag.display('div-gpt-ad-1509514211752-0'); });
        </script>
    </div>
 <br />
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
            setTimeout(adop_ad,6000);
            
        </script>
    </div>
<br /> 
    
    <hr />
    
    <iframe id="abc111" width="300px" height="250px" ></iframe>
    
</body>
</html>