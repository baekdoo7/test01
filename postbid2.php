<html>
<head>
<script>
    // Define the sizes here:
    var tagWidth = 300;
    var tagHeight = 250;
    // Add your post-bid tag IDs here:
    var bids = [
        {
            bidder: 'appnexus',
            params: {
               placementId: '10433394'
            }
        }, {
        bidder: 'indexExchange',
        params: {
                id: '18',
                siteID: '196080'
            }
        },{
        bidder: 'audienceNetwork',
        params: {
                placementId: '685543434893182_951487728298750'
            }
        }
        // Add more header bidding tags here.
    ];
    // Define how long your creative should wait for the bids.
    var bidTimeOut = 1000;
    var passbackTagHtml = "<iframe src='http://compass.adop.cc/RD/810b3bbd-01b1-4569-8c63-725244c0b57e?type=iframe&amp;loc=&amp;size_width=250&amp;size_height=250' id='810b3bbd-01b1-4569-8c63-725244c0b57e' frameBorder='0' marginWidth='0' marginHeight='0' paddingWidth='0' paddingHeight='0' scrolling='no' style='width: 250px; height: 250px;'></iframe>";
    */
    // ======= DO NOT EDIT BELOW THIS LINE =========== //
</script>

<script type="text/javascript" src="prebid_all.js" async></script>

<script>
    var pbjs = pbjs || {};
    pbjs.que = pbjs.que || [];
    var adUnitCode = 'dnZTbG90LS8zNDAwOTg4MS9WT05fcXVpel9zdWJtaXRfbW9i';
    pbjs.que.push(function() {
        var adUnits = [{
            code: adUnitCode,
            sizes: [[tagWidth, tagHeight]],
            bids: bids
        }];
        pbjs.addAdUnits(adUnits);
        pbjs.requestBids({
            timeout: bidTimeOut,
            bidsBackHandler: function() {
                var iframe = document.getElementById('postbid_if');
                var iframeDoc = iframe.contentWindow.document;
                var params = pbjs.getAdserverTargetingForAdUnitCode(adUnitCode);
                // If any bidders return any creatives
                if(params && params['hb_adid']){
                    pbjs.renderAd(iframeDoc, params['hb_adid']);
                } else {
                    // If no bidder return any creatives,
                    // Passback 3rd party tag in Javascript
                    iframe.width = tagWidth;
                    iframe.height = tagHeight;
                    iframeDoc.open("text/html", "replace");
                    iframeDoc.write(passbackTagHtml);
                    iframeDoc.close();
                }
            }
        });
    });
</script>
</head>

<body style="margin:0;padding:0">
    <iframe id='postbid_if' FRAMEBORDER="0" SCROLLING="no" MARGINHEIGHT="0" MARGINWIDTH="0" TOPMARGIN="0" LEFTMARGIN="0" ALLOWTRANSPARENCY="true" WIDTH="0" HEIGHT="0"></iframe>
</body>

</html>
