//jQuery 확인후 로드

var sCnt0009 = ""; // 동적로딩 변수 10:필요시..., 1:jQuery
var nCnt0008 = 0;  // 동적로딩 타이머를위한 대기변수 

// JS 로드 메소드 문서에 head 태그 필수 
function LoadJS007(fileUrl, callback,bNo){
    var oHead = document.getElementsByTagName('HEAD').item(0);
    var oScript = document.createElement("script");
        oScript.type  = "text/javascript";
        oScript.async = true;
        oScript.src   = fileUrl;
        oHead.appendChild(oScript);
    if (document.addEventListener) {
            oScript.onload = function(){callback(bNo);};
            //oScript.onerror = callback;
    }
    else{
        oScript.onreadystatechange = function(){LoadJsReady008(oScript, callback,bNo);};
    }
}
//익스 로딩완료 처리 
function LoadJsReady008(obj, callBack,bNo) {
    if (obj.readyState == "loaded" ) {
        callBack(bNo);
    }
}
// 동적로딩 완료시 플래그 세팅 
function fncCallback099(no) {
    sCnt0009 += no;
    //console.log("loading success..." + sCnt0009);
    if(sCnt0009 == "1"){
        adopRun001();
        return;
    } 
}

// 동적 로딩 완료까지 대기 나중에 타이머시간만 늘릴것
function waitLoading0023(){
    if(nCnt0008 < 300){
        nCnt0008++;
        if(sCnt0009 == "1"){
        	//console.log("call pgm run()...")
            adopRun001();
            return;
        }
        setTimeout(waitLoading0023,10);
    }
}


//var loadUrl0001 = "//ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js";
//var loadUrl0001 = "//compass.adop.cc/assets/js/adop/jquery-1.12.0.js";
var loadUrl0001 = "//compass.adop.cc/assets/js/adop/jquery.min.js";
var loadUrl0002 = "";
if(typeof(jQuery) != "undefined"){
	nCnt009 = 1;
	adopRun001();
}else{
	LoadJS007(loadUrl0001,fncCallback099,1);
	//동적 로딩 완료 될때까지 대기.
	waitLoading0023();
}


console.log("test start point");

//=================================================================================================
//======== 광고 코드 시작 ============================================================================	

function makeid234()
{
    var text = "";
    var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

    for( var i=0; i < 3; i++ )
        text += possible.charAt(Math.floor(Math.random() * possible.length));

    return text;
}
function adopRun001(){
    
	//console.log("adopRun001 running...");
	jQuery(document).ready(function($){
		var $adpSet = $(".adsbyadop"); //adop ins tag 
		var rand_code  = '' ; //rand string 3 length
		var getPageUrl = '' ; //loc info
		var __params = {}   ; //ins attr info
		var regExp   = null ;
		var __url = "";
        var params = "";
        var iurl = "";
        var $iframeTmp = null;
        var $adp = null;
		$.each($adpSet,function(i,val){
			//initialize
			rand_code  = "";
			getPageUrl = "";
			__params   = {};
			regExp     = null;
			__url      = "";
			params     = "";
			iurl       = "";
			$iframeTmp = null;
			$adp       = null;
			
			$adp = $(val);      
			getPageUrl = $(location).attr('href');
           
			regExp = /compass\.adop\.cc/;
            if( getPageUrl.search( regExp ) != -1 ) {
            	//console.log('pageurl003 : '+getPageUrl);
                getPageUrl = unescape(document.referrer);
            }
      
            if( getPageUrl.length > 200 )   getPageUrl = '';
            
            __params['over-size'] = $adp.attr('_over_size');
            __params['over-zone'] = $adp.attr('_over_zone');
            __params['adop-zone'] = $adp.attr('_adop_zon');
            
            if(document.body.clientWidth >= __params['over-size']){
                __params_zone = __params['over-zone'];
                __ori_zone    = __params['adop-zone']
                over_size     = true;
            }
            else{
                __params_zone = $adp.attr('_adop_zon');
                over_size     = false;
            }
            __params['type'] = $adp.attr('_adop_type');
            __params['loc']  = $adp.attr('_page_url') ? $adp.attr('_page_url') : escape(getPageUrl);
            
            __params['size_width'] = $adp.css('width').replace('px', '');
            __params['size_height'] = $adp.css('height').replace('px', '');
            
            if (over_size){
                if(__params['over-size'] == 728){
                	$adp.css('width', "728px");
                	$adp.css('height', "90px");
                    __params['size_width'] = 728;
                    __params['size_height'] = 90;
                }else if(__params['over-size'] == 970){
                	$adp.css('width', "970px");
                	$adp.css('height', "90px");
                    __params['size_width'] = 970;
                    __params['size_height'] = 90;
                }else if(__params['over-size'] == 468){
                	$adp.css('width', "468px");
                	$adp.css('height', "60px");
                    __params['size_width'] = 468;
                    __params['size_height'] = 60;
                }else if(__params['over-size'] == 336){
                	$adp.css('width', "336px");
                	$adp.css('height', "280px");
                    __params['size_width'] = 336;
                    __params['size_height'] = 280;
                }

                $adp.attr('_adop_zon',__params_zone);
            }
            
            $adp.removeClass('adsbyadop').addClass("adsbyadop_" + __params_zone + rand_code);
           
            __url = '//compass.adop.cc/RD/' + encodeURIComponent(__params_zone);
            params = $.param(__params);
            iurl = __url+((params)?'?'+params:'');
            
            $iframeTmp = $('<iframe>');
            $iframeTmp.attr('src',iurl);
            $iframeTmp.attr('id',__params_zone);
            $iframeTmp.attr('frameborder',0);
            $iframeTmp.attr('marginwidth',0);
            $iframeTmp.attr('marginheight',0);
            $iframeTmp.attr('paddingwidth',0);
            $iframeTmp.attr('paddingheight',0);
            $iframeTmp.attr('width',__params['size_width']);
            $iframeTmp.attr('height',__params['size_height']);
            $iframeTmp.attr('scrolling','no');
            $iframeTmp.appendTo($adp);
                       
			//console.log($(val).attr("_adop_zon"));
		//$each end...	
		});
		
	//jQuery.ready end...	
	});
}
