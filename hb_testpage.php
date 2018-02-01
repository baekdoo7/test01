
<!doctype html>
<html lang="ko">
<head>
    <style>
    </style>
    <!-- BEGIN prebid.js LOADER -->
    <script type="text/javascript" src="//acdn.adnxs.com/prebid/not-for-prod/prebid.js" async></script>
    <!-- END prebid.js LOADER -->
    <script>
        var PREBID_TIMEOUT = 700;

        var adUnits = [{
            code: 'busan.com_300_250_2',
            sizes: [300,250],
            bids: [{
                bidder: 'appnexus',
                params: {
                    placementId: '10433394'
                }
            },
                {
                bidder: 'vertoz',
                params: {
                    placementId: 'VZ-HB-K767698V489B01'
                }
            }]
        }];


        var pbjs = pbjs || {};
        pbjs.que = pbjs.que || [];


        pbjs.que.push(function() {
            pbjs.addAdUnits(adUnits);
            pbjs.requestBids({
                bidsBackHandler: sendAdserverRequest
            });
        });

        function sendAdserverRequest() {
            if (pbjs.adserverRequestSent) return;
            pbjs.adserverRequestSent = true;
            var ifrTmp = document.getElementById("adtest");
            pbjs.renderAd(ifrTmp.contentDocument,pbjs.getHighestCpmBids()[0].adId);
            //console.log("test001");

        }

        setTimeout(function() {
            sendAdserverRequest();
        }, PREBID_TIMEOUT);




    </script>




</head>

<body>



<div id='busan.com_300_250_2' style='height:300px; width:250px;'>
    <iframe id="adtest" style="width: 300px;height: 250px;" frameborder="0"></iframe>
</div>






</body>
</html>