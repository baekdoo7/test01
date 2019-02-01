<?php
/**
 * Created by IntelliJ IDEA.
 * User: baekdoo
 * Date: 2019. 1. 10.
 * Time: PM 2:33
 */


require_once 'vendor/autoload.php';

$client = new Google_Client();

// service_account_file.json is the private key that you created for your service account.
$client->setAuthConfig('single-howl-228206-1e1cb1096be0.json');
$client->addScope('https://www.googleapis.com/auth/indexing');

// Get a Guzzle HTTP Client
$httpClient = $client->authorize();
$endpoint = 'https://indexing.googleapis.com/v3/urlNotifications:publish';

// Define contents here. The structure of the content is described in the next step.
$content = "{
  \"url\": \"http://amp.adop.cc/sitemap.xml\",
  \"type\": \"URL_UPDATED\"
}";

$response = $httpClient->post($endpoint, [ 'body' => $content ]);
$status_code = $response->getStatusCode();
var_dump($response);
echo $status_code;

?>



<script type="text/javascript" src="//compasscdn.adop.cc/js/prebid1_15.js" async></script>
<script type="text/javascript" src="//compasscdn.adop.cc/js/prebidadop1_15.js" ></script>
<script>
    pbjs.floor_price = 0.5;
    pbjs.bidtrace = true;
    pbjs.que.push(function() {
        var adUnits = [{
            "code": "1127a3e2-b5f5-4d1b-9083-696dd91818b5",
            "mediaTypes": {
                "banner": {
                    "sizes": [[300, 250]]
                }
            },
            "bids": [{
                "bidder": "oftmedia",
                "params": {
                    "placementId": "14684273"
                }},{
                "bidder": "audienceNetwork",
                "params": {
                    "placementId": "1858486981115843_2006747339623139"
                }}, {
                "bidder": "openx",
                "params": {
                    "unit": "540546285",
                    "delDomain": "adopkorea-d.openx.net"
                }
            }]
        },
            {
                "code": "fc2f1b1b-7799-45c5-bc15-a71d1d3cf746",
                "mediaTypes": {
                    "banner": {
                        "sizes": [[300, 250]]
                    }
                },
                "bids": [{
                    "bidder": "oftmedia",
                    "params": {
                        "placementId": "14684273"
                    }},{
                    "bidder": "audienceNetwork",
                    "params": {
                        "placementId": "1858486981115843_2001275460170327"
                    }}, {
                    "bidder": "openx",
                    "params": {
                        "unit": "540563297",
                        "delDomain": "adopkorea-d.openx.net"
                    }
                }]
            },
            {
                "code": "af5237cf-346c-41eb-937a-58edd7be6744",
                "mediaTypes": {
                    "banner": {
                        "sizes": [[300, 250]]
                    }
                },
                "bids": [{
                    "bidder": "oftmedia",
                    "params": {
                        "placementId": "14684273"
                    }},{
                    "bidder": "audienceNetwork",
                    "params": {
                        "placementId": "1858486981115843_1902559400041934"
                    }}, {
                    "bidder": "openx",
                    "params": {
                        "unit": "540563298",
                        "delDomain": "adopkorea-d.openx.net"
                    }
                }]
            }
        ];
        pbjs.addAdUnits(adUnits);
        pbjs.requestBids({
            bidsBackHandler: floorPrice001
        });
    });
</script>




<script type="text/javascript" src="//compasscdn.adop.cc/js/prebid1_15.js" async></script>
<script type="text/javascript" src="//compasscdn.adop.cc/js/prebidadop1_15.js" ></script>
<script>
    pbjs.floor_price = 0.5;
    pbjs.bidtrace = true;
    pbjs.que.push(function() {
        var adUnits = [{
            "code": "fc2f1b1b-7799-45c5-bc15-a71d1d3cf746",
            "mediaTypes": {
                "banner": {
                    "sizes": [[300, 250]]
                }
            },
            "bids": [{
                "bidder": "oftmedia",
                "params": {
                    "placementId": "14684273"
                }},{
                "bidder": "audienceNetwork",
                "params": {
                    "placementId": "1858486981115843_2001275460170327"
                }}, {
                "bidder": "openx",
                "params": {
                    "unit": "540563297",
                    "delDomain": "adopkorea-d.openx.net"
                }
            }]
        }];
        pbjs.addAdUnits(adUnits);
        pbjs.requestBids({
            bidsBackHandler: floorPrice001
        });
    });
</script>


<script type="text/javascript" src="//compasscdn.adop.cc/js/prebid1_15.js" async></script>
<script type="text/javascript" src="//compasscdn.adop.cc/js/prebidadop1_15.js" ></script>
<script>
    pbjs.floor_price = 0.5;
    pbjs.bidtrace = true;
    pbjs.que.push(function() {
        var adUnits = [{
            "code": "af5237cf-346c-41eb-937a-58edd7be6744",
            "mediaTypes": {
                "banner": {
                    "sizes": [[300, 250]]
                }
            },
            "bids": [{
                "bidder": "oftmedia",
                "params": {
                    "placementId": "14684273"
                }},{
                "bidder": "audienceNetwork",
                "params": {
                    "placementId": "1858486981115843_1902559400041934"
                }}, {
                "bidder": "openx",
                "params": {
                    "unit": "540563298",
                    "delDomain": "adopkorea-d.openx.net"
                }
            }]
        }];
        pbjs.addAdUnits(adUnits);
        pbjs.requestBids({
            bidsBackHandler: floorPrice001
        });
    });
</script>