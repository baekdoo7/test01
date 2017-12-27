<?php
phpinfo();
echo "test <br />\n";
$memcache = new Memcache();
 //$memcache->connect('www.test01.com',11211) or die("Could not connect memcache !!!");
 //$memcache->connect( 'cache.ads-optima.com', 11211 ) or die ( "Could not connect memcache !!!" );


$memcache->connect ( 'cache.ads-optima.com', 11211 ) or die ( "Could not connect memcache !!!" );
//$memcache->addSerer( 'cache.ads-optima.com', 11211);
$filter_list = $memcache->get ( 'filter_new_list' );
$version = $memcache->getversion ();

echo $version . "<br />\n";
echo "================================ <br />\n";
echo $filter_list;


/*
 * * cfsfilter filter_new_list
$tmp = {"date":"2017-01-11 23:35:01.932",
		"data":{"www.pcquest.com":
					[{"comIdx":"981",
					  "urlHost":"www.pcquest.com",
					  "idx":"269",
					  "urlPath":
					  "/@"}],
				"www.inven.co.kr":
					[{"comIdx":"786",
					  "urlHost":"www.inven.co.kr",
					  "param":"news",
					  "idx":"167",
					   "urlPath":"/webzine/news/"},
					 {"comIdx":"786",
					   "urlHost":"www.inven.co.kr",
					   "param":"come_idx,l",
					   "idx":"169",
					   "urlPath":"/board/powerbbs.php",
					   "keyShareOpt":"169"}],
				"bulungan.prokal.co":
					 [{"comIdx":"1224",
					   "urlHost":"bulungan.prokal.co",
					   "idx":"515",
					   "urlPath":"/@/@/@"}],
				"radarkaltim.prokal.co":
					 [{"comIdx":"1224",
					   "urlHost":"radarkaltim.prokal.co",
					   "idx":"512","urlPath":"/@/@/@"}],
			    "www.kcsnews.co.kr":
					 [{"comIdx":"382",
					   "urlHost":"www.kcsnews.co.kr",
					   "param":"idxno",
					   "idx":"12",
					   "urlPath":"/news/articleView.html"}],
				"www.81un.net":
					 [{"comIdx":"1020",
					   "urlHost":"www.81un.net",
					   "idx":"285",
					   "urlPath":"/@/@/@/@"}],
				"www.ruangpegawai.com":
					 [{"comIdx":"1250",
					   "urlHost":"www.ruangpegawai.com",
					   "idx":"455",
					   "urlPath":"/@/@"}]
				}
		}
*/
?>

