var adopHB = adopHB || {};
adopHB.que = adopHB.que || {};


//랜덤 키생성(3자리 랜덤 문자열)
function makeid327()
{
    var text = "";
    var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

    for( var i=0; i < 3; i++ )
        text += possible.charAt(Math.floor(Math.random() * possible.length));

    return text;
}

//콤파스 광고 로딩 시
function adopRun005(){
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
        if($adpIns[i].className == "adsbyadop" && ($adpIns[i].getAttribute('_adop_type') == "hb" || $adpIns[i].getAttribute('_adop_type') == "HB") ){
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
        rand_code  = makeid327();
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
                }else{
                    __params_zone = $adp.getAttribute('_adop_zon');
                    over_size     = false;
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

        __url = '//compasstest.adop.cc/RE/' + encodeURIComponent(__params_zone);
        for(var k in __params){
            params += encodeURIComponent(k) + '=' + encodeURIComponent(__params[k]) + '&'
        }
        iurl = __url+((params)?'?'+params:'');

        var strIframeId   = "adopB" + Math.floor(Math.random()*10000) + 1;
        var strIframe     = "<iframe id='"+strIframeId+"' frameborder='0' marginwidth='0' marginheight='0' paddingwidth='0' paddingheight='0' scrolling='no' style='width: 100%; height: 100%;' ></iframe>";
        var strScriptLink = "<script src='"+__url+"'><\/script>";

        $adp.innerHTML = "<div style=\"width:"+__params['size_width']+"px;height:"+__params['size_height']+"px; \">"+strIframe+"</div>";
        
        //영역 정보 큐에 넣기
        var frameObj = {};
            frameObj.frameId = strIframeId;
            frameObj.scriptLink = strScriptLink;
        adopHB.que[__params_zone] = frameObj;
        
        //var passbackIframe = document.getElementById(strIframeId);
        //var passbackIframeDoc = passbackIframe.contentDocument || passbackIframe.contentWindow.document;
        //if(passbackIframeDoc != null){
        //    passbackIframeDoc.open();
        //    passbackIframeDoc.write(strScriptLink);
        //    passbackIframeDoc.close();
        //}


    }//for end
    if(typeof(pbjs) == "undefined" || typeof(pbjs.stopad02) == "undefined"){
       showAlladopAd01(false);
       }
    else{
        showAlladopAd01(pbjs.stopad02);
       }

}

var showAlladopAd01 = function(viewing){
    if(viewing){
        return ;
    }
    
    if(typeof(pbjs) == "undefined" || typeof(pbjs.que2) == "undefined"){
        for(var areaId in adopHB.que){        
            adopHBshow(areaId);
        } 
    }
    else{
        for(var areaId in adopHB.que){
//console.log(pbjs.que2);            
            if(typeof(pbjs.que2[areaId]) == "undefined"){
                adopHBshow(areaId);
            }
            else{
                adopHBshow2(areaId);
            }
            
        }         
    }
}
var adopHBshow = function(invenCode){
               
        var passbackIframe = document.getElementById(adopHB.que[invenCode].frameId);
        var passbackIframeDoc = passbackIframe.contentDocument || passbackIframe.contentWindow.document;
        if(passbackIframeDoc != null){
            passbackIframeDoc.open();
            passbackIframeDoc.write(adopHB.que[invenCode].scriptLink);
            passbackIframeDoc.close();
        }
}
var adopHBshow2 = function(invenCode2){
               
        var passbackIframe = document.getElementById(adopHB.que[invenCode2].frameId);
        var passbackIframeDoc = passbackIframe.contentDocument || passbackIframe.contentWindow.document;
        if(passbackIframeDoc != null){
            pbjs.renderAd(passbackIframeDoc,pbjs.que2[invenCode2]);
            //passbackIframeDoc.open();
            //passbackIframeDoc.write(adopHB.que[invenCode].scriptLink);
            //passbackIframeDoc.close();
        }
}

var checkLoad0977 = function() {
    document.readyState !== "complete" ? setTimeout(checkLoad0977, 11) : adopRun005();
};
checkLoad0977();
setTimeout(function(){adopRun005()},200);
setTimeout(function(){adopRun005()},700);
setTimeout(function(){adopRun005()},5000);
