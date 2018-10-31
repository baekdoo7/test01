

target_bd = "";
PREBID_TIMEOUT = 3000;
var pbjs = pbjs || {};
pbjs.addCallback = function(a,b){return};
pbjs.allBidsAvailable = function(){return false};
pbjs.que2 =  pbjs.que2 || {};
pbjs.que = pbjs.que || [];


//광고 스탑
pbjs.stopad02 = true;
//광고 콜백 처리
function floorPrice001(bidResponses) {
    if(pbjs.adopcalled) return;
    pbjs.adopcalled = true;
    var targetingParams = pbjs.getAdserverTargeting();
    target_bd = targetingParams;
    for(var areaid in target_bd){
        if(pbjs.floor_price <= target_bd[areaid].hb_pb){
            pbjs.que2[areaid] = target_bd[areaid].hb_adid;
        }
    }

    //로깅전송 체크
    if(pbjs.bidtrace) {
        setTimeout(function () {
            pbjs.sendingbidinfo(pbjs.getBidResponses(), pbjs.que2);
        }, 1000);
    }
    //광고 노출
    sendAdserverRequest();

}
//광고시작함수
function sendAdserverRequest(){
    if(pbjs.adserverRequestSent) return;
    pbjs.adserverRequestSent = true;
    pbjs.stopad02 = false;
    showAlladopAd01(false);
}

//guid
function _guid() {
    function s4() {
        return Math.floor((1 + Math.random()) * 0x10000)
            .toString(16)
            .substring(1);
    }
    return s4() + s4() + '-' + s4() + '-' + s4() + '-' +
        s4() + '-' + s4() + s4() + s4();
}

//비딩로그 전송 처리 함수
pbjs.sendingbidinfo = function (bidders,bidWinners) {

    if(bidders == null) return;
    var guid = _guid();
    for (var adunit in bidders) {
        if (bidders.hasOwnProperty(adunit)) {
            var bids = bidders[adunit].bids;
            for (var i = 0; i < bids.length; i++) {
                var b = bids[i];

                if(bidWinners[adunit] == b.adId){
                    var winned = true;
                }
                else{
                    var winned = false;
                }
                //var urltmp = "//dmp3.adop.cc/hb?ai="+adunit+"&b="+b.bidder+"&t="+b.timeToRespond+"&c="+b.cpm+"&ui="+guid+"&w="+winned;

                var urltmp = "//data.adop.cc/collect.php?log=hb&ai="+adunit+"&b="+b.bidder+"&t="+b.timeToRespond+"&c="+b.cpm+"&ui="+guid+"&w="+winned;
                //이미지 삽입
                if(urltmp != "") {
                    var head = document.getElementsByTagName("head");
                    var img  = document.createElement("img");
                    img.height = 0;
                    img.width = 0;
                    img.src = urltmp;
                    head[0].appendChild(img);
                }

            }
        }
    }
}



//타임아웃 후 기본 광고 노출
setTimeout(function(){floorPrice001(null);}, PREBID_TIMEOUT);




/*
eval(function(p,a,c,k,e,d){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--){d[e(c)]=k[c]||e(c)}k=[function(e){return d[e]}];e=function(){return'\\w+'};c=1};while(c--){if(k[c]){p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c])}}return p}('5="";o=B;4 2=2||{};2.I=3(a,c){};2.E=3(){8!1};2.7=2.7||{};2.x=2.x||[];2.j=!0;3 l(a){6(!2.v){2.v=!0;5=2.C();9(4 c p 5)2.z<=5[c].A&&(2.7[c]=5[c].P);2.J&&r(3(){2.n(2.O(),2.7)},K);m()}}3 m(){2.u||(2.u=!0,2.j=!1,M(!1))}3 q(){3 a(){8 i.N(Q*(1+i.H())).R(16).F(1)}8 a()+a()+"-"+a()+"-"+a()+"-"+a()+"-"+a()+a()+a()}2.n=3(a,c){6(s!=a){4 h=q(),d;9(d p a)6(a.G(d))9(4 g=a[d].D,f=0;f<g.L;f++){4 b=g[f];b="//18.S.17/15.1a?1b=1d&1c="+d+"&b="+b.14+"&t="+b.W+"&c="+b.V+"&U="+h+"&w="+(c[d]==b.12?!0:!1);6(""!=b){4 k=y.10("Z"),e=y.13("11");e.Y=0;e.X=0;e.T=b;k[0].19(e)}}}};r(3(){l(s)},o);',62,76,'||pbjs|function|var|target_bd|if|que2|return|for|||||||||Math|stopad02||floorPrice001|sendAdserverRequest|sendingbidinfo|PREBID_TIMEOUT|in|_guid|setTimeout|null||adserverRequestSent|adopcalled||que|document|floor_price|hb_pb|3E3|getAdserverTargeting|bids|allBidsAvailable|substring|hasOwnProperty|random|addCallback|bidtrace|1E3|length|showAlladopAd01|floor|getBidResponses|hb_adid|65536|toString|adop|src|ui|cpm|timeToRespond|width|height|head|getElementsByTagName|img|adId|createElement|bidder|collect||cc|data|appendChild|php|log|ai|hb'.split('|'),0,{}));
*/
