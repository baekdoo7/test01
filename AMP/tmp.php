<?=php?>
<html>
<head>
<script>
    pbjs.floor_price = 0.5;
    pbjs.bidtrace = true;
    pbjs.que.push(function() {
        var adUnits = [{
            code: '0dbe462c-8e39-44cc-b109-4385171b228c',
            mediaTypes: {
                banner: {
                    sizes: [[300, 250]]
                }
            },
            bids: [{
                bidder: 'oftmedia',
                params: {
                    placementId: '14626098'
                }, {
                bidder: 'openx',
                params: {
                    unit: '540546285',
                    delDomain: 'adopkorea-d.openx.net',
                },
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

</body>
</html>







