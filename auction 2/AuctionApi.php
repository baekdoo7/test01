<?php

//include_once(dirname(__FILE__) . '/AuctionCommon.php');
//include_once(dirname(__FILE__) . '/AuctionSessionApi.php');
//include_once(dirname(__FILE__) . '/AuctionRestApi.php');

class AuctionApi
{

    protected $_session;
    public $encryptedTicket;

    function __constrcut()
    {

    }


    public function auction_adver($site,$bncode='',$search_type=false)
    {

        if($search_type)
        {
            define('AUCTION_LOGO','http://cdn.ads-optima.com/atom/auction/logo_txt_kr%s.png');
            define('AUCTION_LANDING','http://www.auction.co.kr');
            define('AUCTION_SERVICE','auction');
            $_keyword['keyword'] = '13250200';
            return $this->keywordItem($_keyword,$site);
        }else {
            define('AUCTION_LOGO','http://cdn.ads-optima.com/atom/auction/logo_allkill%s.png');
            define('AUCTION_LANDING','http://corners.auction.co.kr/AllKill/AllDay.aspx');
            define('AUCTION_SERVICE','allkill');
            return $this->allkill();
        }

    }

    public function auction_adver_new($site,$bncode='', $width, $height, $search_type=false)
    {

        define('AUCTION_LOGO','http://cdn.ads-optima.com/atom/auction/logo_allkill%s.png');
        define('AUCTION_LANDING','http://corners.auction.co.kr/AllKill/AllDay.aspx');
        define('AUCTION_SERVICE','allkill');

        return $this->keywordItem($_keyword,$site, $width, $height);
    }

    /* 옥션 도메인 요청시 파악 */
    public function request_domain_auction($_site)
    {
        $_data_temp = parse_url($_site);
        return $_data_temp;
    }


    public function keywordItem($keyword,$_site,$width,$height)
    {
//        if(!empty($keyword['keyword']))
        //옥션 이미지+상품명광고 가져오기
//            return $this->getKeyWordItem($keyword['keyword'],$_site);
        //옥션 통이미지 광고 가져오기

        return $this->getImageItem($keyword['keyword'],$_site, $width, $height);

//        else
//        return '';
    }

    ### 카데고리 리스트 캐쉬
    public function getCategoryItem($site)
    {
        $CI = & get_instance();
        $_cache_path = $CI->config->item('adop_cache');
        $_category = get_cache_data($_cache_path.'category.json');

        $site_path = parse_url($site);
        
        preg_match('/^\/(.*)\//',$site_path['path'],$datas);
        
        $_make_rand = get_rand(0,(count($_category[$datas[1]])-1),1);
        
        return $_category[$_make_rand];
        
    }
    
    ### 옥션 키워드 아이템 가져오는 페이지
    public function getKeyWordItem($_keyword,$site)
    {
        $CI = & get_instance();
        $_cache_path = $CI->config->item('adop_cache');
//        $_keyword = $this->getCategoryItem($site);

//        if(strpos($site,'&test=Y'))
//            $_keyword = 'adop_items';
//        else
//            $_keyword = '86050200';
        $_keyword = 'adop_items';

        $_items = json_decode(read_file($_cache_path.$_keyword.'.json'),TRUE);

        if(count($_items) == 0)
            return false;
        
        $_make_rand = get_rand(0,(count($_items)-1),1);
        $_datas_temp =  $_items[$_make_rand[0]];
        
        $_datas[0]['ServiceText'] =  $_datas_temp['item_brand'][0];
        $_datas[0]['VisibleItemName'] =  $_datas_temp['item_name'][0];
        $_datas[0]['FixImage300Url'] =  $_datas_temp['item_image'][0];
        $_files =  pathinfo($_datas_temp['item_image'][0]);
        
        $_first = substr($_files['filename'], 0, strlen($_files['filename']) -1);
        $_last = substr($_files['filename'], 0, -1) + 1;

//        $_datas[0]['MobileBannerImageUrl'] =  $_files['dirname'].'/'.($_first.$_last).'.'.$_files['extension'];
        $_datas[0]['MobileBannerImageUrl'] =  $_datas_temp['item_image'][0];
        $_datas[0]['MobileLandingUrl'] =  $_datas_temp['item_mlink'][0];
        $_datas[0]['WebLandingUrl'] =  $_datas_temp['item_link'][0];
        $_datas[0]['DiscountedPrice'] =  $_datas_temp['item_price'][0];
        
        return $_datas;
    }

    ### 옥션 통이미지 광고 가져오기
    public function getImageItem($_keyword, $site, $width, $height)
    {
        $CI = & get_instance();
        $_cache_path = $CI->config->item('adop_cache');
        $_keyword = 'adop_items';
        $_date = "_".date('Y-m-d');
        $_size = $width.$height;


//        로컬에서 테스트하기 위한 임시 url
//        $_items = json_decode(read_file("/Users/junho/Documents/workspace/mountain_atom/application/cache/adop_items_2016-02-16.json"),TRUE);

        //실서버
        $_items = json_decode(read_file($_cache_path.$_keyword.$_date.'.json'),TRUE);

        if(count($_items) == 0) {
            return false;
        }

        $_make_rand = get_rand(0,(count($_items)-1),1);
        $_datas_temp =  $_items[$_make_rand[0]];

        if($_datas_temp['item_name'][0] == "") {
            return 'empty_data';
        }

        $_datas[0]['ServiceText'] =  $_datas_temp['item_brand'][0];
        $_datas[0]['VisibleItemName'] =  $_datas_temp['item_name'][0];
        $_datas[0]['FixImage300Url'] =  sprintf($_datas_temp['item_image'][0], $_size);
        $_files = pathinfo($_datas_temp['item_image'][0]);
        $_item_code = parse_url($_datas_temp['item_mlink'][0]);

        $_first = substr($_files['filename'], 0, strlen($_files['filename']) -1);
        $_last = substr($_files['filename'], 0, -1) + 1;

//        $_datas[0]['MobileBannerImageUrl'] =  $_files['dirname'].'/'.($_first.$_last).'.'.$_files['extension'];
        $_datas[0]['MobileBannerImageUrl'] =  sprintf($_datas_temp['item_image'][0], $_size);
        $_datas[0]['MobileLandingUrl'] =  $_datas_temp['item_mlink'][0];
        $_datas[0]['WebLandingUrl'] =  $_datas_temp['item_link'][0];
//        $_datas[0]['MobileLandingUrl'] =  sprintf($_item_url,$_datas_temp['item_mlink'][0]);
//        $_datas[0]['WebLandingUrl'] =  sprintf($_item_url,$_datas_temp['item_link'][0]);
        $_datas[0]['ItemCode'] = $_datas_temp['item_code'][0];
        $_datas[0]['WebLandingUrl'] =  $_datas_temp['item_link'][0];
        $_datas[0]['DiscountedPrice'] =  $_datas_temp['item_price'][0];

        return $_datas;
    }
    
    //올킬 
    public function allkill()
    {
        /* 랜덤으로 뽑아올 아이템 갯수  */
        $action_rand = 1;

        $CI = & get_instance();

        $CI->load->driver('cache');

        $_allkill_url = 'http://api.auction.co.kr/allkill/items/allday';
        $_allill_data = gcurl($_allkill_url);
        var_dump($_allill_data);
        exit;

        if (! $auction_cache = $CI->cache->file->get('auction_cache')){
            $_allill_data = gcurl($_allkill_url);
            $CI->cache->file->save('auction_cache', $_allill_data, 600);

        }else {
            $_allill_data = $auction_cache;
        }

        $_allkill_temp = json_decode($_allill_data,true);

        $_make_rand = get_rand(0,(count($_allkill_temp['Data'])-1),$action_rand);

        foreach($_make_rand as $keys => $vals)
        {
            $_RAND[$keys] = $_allkill_temp['Data'][$vals];
        }

        $_datas = array_select($_RAND,'ItemNo,MobileLandingUrl,MobileBannerImageUrl,MobileImageUrl,SellPrice,DiscountedPrice,CategoryCode,VisibleItemName,ServiceText,FixImage300Url');
        
        return $_datas;
    }


    //TODO 옥션 새로운 url test -> 삭제 예정
    public function auction_adver_new_url_test($site,$bncode='', $width, $height, $search_type=false)
    {

        define('AUCTION_LOGO','http://cdn.ads-optima.com/atom/auction/logo_allkill%s.png');
        define('AUCTION_LANDING','http://corners.auction.co.kr/AllKill/AllDay.aspx');
        define('AUCTION_SERVICE','allkill');

        return $this->keywordItem_new_url_test($_keyword,$site, $width, $height);
    }

    //TODO 옥션 새로운 url test -> 삭제 예정
    public function keywordItem_new_url_test($keyword,$_site,$width,$height)
    {
        return $this->getImageItem_new_url_test($keyword['keyword'],$_site, $width, $height);
    }

    //TODO 옥션 새로운 url test -> 삭제 예정
    public function getImageItem_new_url_test($_keyword, $site, $width, $height)
    {
        $CI = & get_instance();
        $_cache_path = $CI->config->item('adop_cache');
        $_keyword = 'adop_items';
        $_date = "_".date('Y-m-d');
        $_size = $width.$height;

//        로컬에서 테스트하기 위한 임시 url
        $root_url = $_SERVER['DOCUMENT_ROOT'];
        $_items = json_decode(read_file($root_url."/application/cache/adop_items_2016-02-14.json"),TRUE);

        if(count($_items) == 0) {
            return false;
        }

        $_make_rand = get_rand(0,(count($_items)-1),1);
        $_datas_temp =  $_items[$_make_rand[0]];

        if($_datas_temp['item_name'][0] == "") {
            return 'empty_data';
        }

        $_datas[0]['ServiceText'] =  $_datas_temp['item_brand'][0];
        $_datas[0]['VisibleItemName'] =  $_datas_temp['item_name'][0];
        $_datas[0]['FixImage300Url'] =  sprintf($_datas_temp['item_image'][0], $_size);
        $_files = pathinfo($_datas_temp['item_image'][0]);
        $_item_code = parse_url($_datas_temp['item_mlink'][0]);

        $_first = substr($_files['filename'], 0, strlen($_files['filename']) -1);
        $_last = substr($_files['filename'], 0, -1) + 1;

        $_datas[0]['MobileBannerImageUrl'] =  sprintf($_datas_temp['item_image'][0], $_size);
        $_datas[0]['MobileLandingUrl'] =  $_datas_temp['item_mlink'][0];
        $_datas[0]['WebLandingUrl'] =  $_datas_temp['item_link'][0];
//        $_datas[0]['ItemCode'] =  str_replace('/','',$_item_code['fragment']);
        $_datas[0]['ItemCode'] = $_datas_temp['item_code'][0];
        $_datas[0]['WebLandingUrl'] =  $_datas_temp['item_link'][0];
        $_datas[0]['DiscountedPrice'] =  $_datas_temp['item_price'][0];

        return $_datas;
    }


    //현재 광고비용 가져오기
    public function get_adver_cost(){
        //실서버 반반영
        $CI = & get_instance();
        $_cache_path = $CI->config->item('adop_cache');
        $_keyword = 'status/serving_status';
        $data = json_decode(read_file($_cache_path.$_keyword.'.json'),TRUE);

//        로컬에서 테스트하기 위한 임시 url
//        $root_url = $_SERVER['DOCUMENT_ROOT'];
//        $data = json_decode(read_file($root_url . "/application/cache/serving_status.json"), TRUE);
//          테스트용
//        $fromdate = '2016-04-11';
//        $todate = '2016-04-12';

        $_url = "http://els.ads-optima.com:9200/_search";

//        실서버용
        $fromdate = $data['date'];
        $todate = date('Y-m-d',strtotime($fromdate."+1 days"));

        //올킬

        $click_data = sprintf('{"query": { "filtered": { "query": { "bool": { "must": [ { "match": { "arg_adver_type": "allkill"}}, { "match": { "uri": "click"}} ] } }, "filter": { "range": { "time" : { "gte": "%s", "lt": "%s", "time_zone": "+09:00" } } } } },"size": 0 }', $fromdate, $todate);
        $_click = pcurl($_url, $click_data);
        $_click_tmp = json_decode($_click, true);
        $alone_clk_cnt = $_click_tmp['hits']['total'];

        //나홀로 CPC 80
        if ($alone_clk_cnt >= 4850){
            $json_data['serving_status'] = "stop";
        }else{
            $json_data['serving_status'] = "go";
        }
        $this->update_serving_status($json_data);

    }

    //광고 진행상태 갱신하기
    public function update_serving_status($json_data){
        $CI = & get_instance();
        //아마존 cdn -- 시작
        $CI->load->library('AWS/s3');
        $s3 = new S3("awsAccessKey", "awsSecretKey");

        // Bucket Name
        $this->bucket = "www.ads_optima.com";

        $s3path = "atom/allkill_item/status/";
        $fileName = "serving_status.json";
        $json_data['date'] = date('Y-m-d');

        //파일 존재여부 체크
        $file_exist = $CI->s3->getBucket($this->bucket , $s3path);
        //파일경로 배열에서 제거
        unset($file_exist[$s3path]);
        //파일을 s3에 바로 작성
        if (empty($file_exist)){
            //파일 없을때 생성
            $upload_string = json_encode($json_data,JSON_UNESCAPED_UNICODE);
            $CI->s3->putObjectString($upload_string, $this->bucket , $s3path.$fileName, S3::ACL_PUBLIC_READ);
        }else{
            //파일은 있고 12만원이 넘었을떄만...
            if($json_data['serving_status'] == "stop"){
                $upload_string = json_encode($json_data,JSON_UNESCAPED_UNICODE);
                $CI->s3->putObjectString($upload_string, $this->bucket , $s3path.$fileName, S3::ACL_PUBLIC_READ);
            }
        }
    }

    public function reset_serving_status_file(){

        $CI = & get_instance();
        //아마존 cdn -- 시작
        $CI->load->library('AWS/s3');
        $s3 = new S3("awsAccessKey", "awsSecretKey");

        // Bucket Name
        $this->bucket = "www.ads_optima.com";

        $s3path = "atom/allkill_item/status/";
        $fileName = "serving_status.json";
        $json_data['serving_status']="go";
        $json_data['date'] = date('Y-m-d');

        $upload_string = json_encode($json_data,JSON_UNESCAPED_UNICODE);
        $CI->s3->putObjectString($upload_string, $this->bucket , $s3path.$fileName, S3::ACL_PUBLIC_READ);

    }

}

