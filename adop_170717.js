//랜덤 키생성(3자리 랜덤 문자열)
function makeid234()
{
    var text = "";
    var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

    for( var i=0; i < 3; i++ )
        text += possible.charAt(Math.floor(Math.random() * possible.length));

    return text;
}

//콤파스 광고 로딩 시
function adopRun001(){
    //기초 변수 세팅
    var $adpIns = document.getElementsByTagName("ins");
    var $adpSet =  []; //adop ins tag Elements
    var rand_code  = '' ; //rand string 3 length
    var getPageUrl = '' ; //loc info
    var __params = {}   ; //ins attr info
    var regExp   = null ; //regular expression for referer check
    var __url = "" ;
    var params = "";
    var iurl = ""  ;
    var $iframeTmp = null;
    var $adp = null;

    //adop ins tag 수집
    for(var i=0 ;i<$adpIns.length ; i++){
        if($adpIns[i].className == "adsbyadop"){
            $adpSet.push($adpIns[i]);
        }
    }
    //adop ins tag가 없으면 종료
    if($adpSet.length <= 0){
        return;
    }

    //insert iframe to adop ins Tag
    for(var j=0;j<$adpSet.length;j++ ){
        //initialize
        rand_code  = makeid234();
        getPageUrl = "";
        __params   = {};
        regExp     = null;
        __url      = "";
        params     = "";
        iurl       = "";
        $iframeTmp = null;
        $adp       = null;

        $adp = $adpSet[j];
        getPageUrl = window.location.href;

        regExp = /compass\.adop\.cc/;
        if( getPageUrl.search( regExp ) != -1 ) {
            getPageUrl = unescape(document.referrer);
        }

        if( getPageUrl.length > 200 )   getPageUrl = '';

        __params['over-size']   = $adp.getAttribute('_over_size');
        __params['over-size-w'] = $adp.getAttribute('_over_size_w');
        __params['over-size-h'] = $adp.getAttribute('_over_size_h');
        __params['over-zone']   = $adp.getAttribute('_over_zone');
        __params['adop-zone']   = $adp.getAttribute('_adop_zon');
      
        if(__params['over-size'] != null && __params['over-size'] == "auto"){
           var obj001 = JSON.parse(__params['over-zone']);               
            for(var key01 in obj001){
                var tmpKey = key01.split("x",2);
               
                if(document.body.clientWidth >= tmpKey[0]){
                    __params_zone = obj001[key01];
                    __ori_zone    = __params['adop-zone'];
                    over_size     = false;    
                    $adp.style.width  = tmpKey[0] + "px";
                    $adp.style.height = tmpKey[1] + "px";
                    __params['size_width'] = tmpKey[0];
                    __params['size_height'] = tmpKey[1];
                }
               
            }
        }
        else if( __params['over-size'] != null && document.body.clientWidth >= __params['over-size']){
            __params_zone = __params['over-zone'];
            __ori_zone    = __params['adop-zone'];
            over_size     = true;
        }
        else{
            __params_zone = $adp.getAttribute('_adop_zon');
            over_size     = false;
        }
        __params['type'] = $adp.getAttribute('_adop_type');
        __params['loc']  = $adp.getAttribute('_page_url') ? $adp.getAttribute('_page_url') : escape(getPageUrl);

        __params['size_width'] = $adp.style.width.replace('px', '');
        __params['size_height'] = $adp.style.height.replace('px', '');

        if (over_size){
            if(__params['over-size'] == 336) {
                $adp.style.width = "336px";
                $adp.style.height = "280px";
                __params['size_width'] = 336;
                __params['size_height'] = 280;
            }else if(__params['over-size'] == 468){
                $adp.style.width = "468px" ;
                $adp.style.height = "60px" ;
                __params['size_width'] = 468;
                __params['size_height'] = 60;
            }else if(__params['over-size'] == 600){
                $adp.style.width = "600px" ;
                $adp.style.height = "90px" ;
                __params['size_width'] = 600;
                __params['size_height'] = 90;
            }else if(__params['over-size'] == 728){
                $adp.style.width = "728px" ;
                $adp.style.height = "90px" ;
                __params['size_width'] = 728;
                __params['size_height'] = 90;
            }else if(__params['over-size'] == 970){
                $adp.style.width = "970px" ;
                $adp.style.height = "90px" ;
                __params['size_width'] = 970;
                __params['size_height'] = 90;
            }

            if(__params['over-size-w']!= null){
                $adp.style.width = __params['over-size-w'];
                __params['size_width'] = __params['over-size-w'];
            }
            if(__params['over-size-h']!= null){
                $adp.style.height = __params['over-size-h'];
                __params['size_height'] = __params['over-size-h'];
            }

            $adp.setAttribute('_adop_zon',__params_zone);
        }

        $adp.className = "adsbyadop_" + __params_zone + rand_code ;

        __url = '//compass.adop.cc/RD/' + encodeURIComponent(__params_zone);
        for(var k in __params){
            params += encodeURIComponent(k) + '=' + encodeURIComponent(__params[k]) + '&'
        }
        iurl = __url+((params)?'?'+params:'');

        $iframeTmp = document.createElement("IFRAME");
        $iframeTmp.setAttribute('src',iurl);
        $iframeTmp.setAttribute('id',__params_zone);
        $iframeTmp.setAttribute('frameborder',0);
        $iframeTmp.setAttribute('marginwidth',0);
        $iframeTmp.setAttribute('marginheight',0);
        $iframeTmp.setAttribute('paddingwidth',0);
        $iframeTmp.setAttribute('paddingheight',0);
        $iframeTmp.setAttribute('width',__params['size_width']);
        $iframeTmp.setAttribute('height',__params['size_height']);
        $iframeTmp.setAttribute('scrolling','no');
        $adp.appendChild($iframeTmp);

    }//for end


}
var checkLoad0999 = function() {
    document.readyState !== "complete" ? setTimeout(checkLoad0999, 11) : adopRun001();
};

checkLoad0999();
setTimeout(function(){adopRun001()},200);
setTimeout(function(){adopRun001()},700);
