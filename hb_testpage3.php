
<!doctype html>
<html lang="ko">
<head>
    <style>
    </style>
    <!-- BEGIN prebid.js LOADER -->

    <script type="text/javascript" src="prebid1_15.js" async></script>
    <script type="text/javascript" src="prebidadop1_15.js" ></script>

    <!-- END prebid.js LOADER -->
    <script>

        var PREBID_TIMEOUT = 700;

        pbjs.floor_price = 0.5;
        pbjs.bidtrace = false;

        pbjs.que.push(function() {
            var adUnits = [{
                code: 'b8f66a02-8475-446c-a818-4e347f281e71',
                mediaTypes: {
                    banner: {
                        sizes: [[300, 250]]
                    }
                },
                bids: [{
                    bidder: 'appnexus',
                    params: {
                        placementId: '10433394'
                    }
                },

                    {
                        bidder: 'audienceNetwork',
                        params: {
                            //placementId: '685543434893182_951487728298750',
                            placementId: '1781435045214196_1785482028142831',
                            format: '300x250',
                            testmode: true

                        }
                    }
                ]
            }];

            pbjs.addAdUnits(adUnits);
            pbjs.requestBids({
                bidsBackHandler: floorPrice001
            });

        });






    </script>




</head>

<body>

<script async src='http://compasscdn.adop.cc/js/adopHB.js?v=14' ></script>
<div class="banner">
    <!--헤더비딩 테스트-->
    <script async src='http://compasscdn.adop.cc/js/adopHB.js?v=14' ></script>
    <ins class='adsbyadop' _adop_zon = 'b8f66a02-8475-446c-a818-4e347f281e71' _adop_type = 'hb' style='display:inline-block;width:300px;height:250px;' _page_url=''></ins>
</div>

<div id='busan.com_300_250_2' style='height:300px; width:250px;'>
    <iframe id="adtest" style="width: 300px;height: 250px;" frameborder="0"></iframe>
</div>






</body>
</html>