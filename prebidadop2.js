
/*
target_bd = "";
PREBID_TIMEOUT = 3000;
var pbjs = pbjs || {};
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

*/

//eval(function(p,a,c,k,e,d){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--){d[e(c)]=k[c]||e(c)}k=[function(e){return d[e]}];e=function(){return'\\w+'};c=1};while(c--){if(k[c]){p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c])}}return p}('5="";n=C;4 2=2||{};2.7=2.7||{};2.y=2.y||[];2.l=!0;3 i(a){6(!2.u){2.u=!0;5=2.A();8(4 c o 5)2.B<=5[c].z&&(2.7[c]=5[c].M);2.G&&x(3(){2.q(2.H(),2.7)},I);s()}}3 s(){2.j||(2.j=!0,2.l=!1,J(!1))}3 p(){3 a(){r 9.K(N*(1+9.F())).O(16).E(1)}r a()+a()+"-"+a()+"-"+a()+"-"+a()+"-"+a()+a()+a()}2.q=3(a,c){6(m!=a){4 h=p(),d;8(d o a)6(a.D(d))8(4 g=a[d].L,f=0;f<g.X;f++){4 b=g[f];b="//P.13.14/17?15="+d+"&b="+b.Z+"&t="+b.R+"&c="+b.Q+"&T="+h+"&w="+(c[d]==b.18?!0:!1);6(""!=b){4 k=v.10("12"),e=v.S("Y");e.V=0;e.W=0;e.U=b;k[0].11(e)}}}};x(3(){i(m)},n);',62,71,'||pbjs|function|var|target_bd|if|que2|for|Math|||||||||floorPrice001|adserverRequestSent||stopad02|null|PREBID_TIMEOUT|in|_guid|sendingbidinfo|return|sendAdserverRequest||adopcalled|document||setTimeout|que|hb_pb|getAdserverTargeting|floor_price|3E3|hasOwnProperty|substring|random|bidtrace|getBidResponses|1E3|showAlladopAd01|floor|bids|hb_adid|65536|toString|dmp3|cpm|timeToRespond|createElement|ui|src|height|width|length|img|bidder|getElementsByTagName|appendChild|head|adop|cc|ai||hb|adId'.split('|'),0,{}));


eval(function(p,a,c,k,e,d){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--){d[e(c)]=k[c]||e(c)}k=[function(e){return d[e]}];e=function(){return'\\w+'};c=1};while(c--){if(k[c]){p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c])}}return p}('5="";o=A;4 2=2||{};2.6=2.6||{};2.i=2.i||[];2.u=!0;3 r(a){7(!2.v){2.v=!0;5=2.D();8(4 c p 5)2.C<=5[c].z&&(2.6[c]=5[c].B);2.F&&x(3(){2.n(2.M(),2.6)},K);j()}}3 j(){2.m||(2.m=!0,2.u=!1,J(!1))}3 q(){3 a(){l 9.I(L*(1+9.O())).H(16).P(1)}l a()+a()+"-"+a()+"-"+a()+"-"+a()+"-"+a()+a()+a()}2.n=3(a,c){7(s!=a){4 h=q(),d;8(d p a)7(a.G(d))8(4 g=a[d].E,f=0;f<g.N;f++){4 b=g[f];b="//13.15.Q/17.1a?19=18&11="+d+"&b="+b.10+"&t="+b.S+"&c="+b.R+"&V="+h+"&w="+(c[d]==b.1b?!0:!1);7(""!=b){4 k=y.T("14"),e=y.U("W");e.X=0;e.Y=0;e.Z=b;k[0].12(e)}}}};x(3(){r(s)},o);',62,74,'||pbjs|function|var|target_bd|que2|if|for|Math|||||||||que|sendAdserverRequest||return|adserverRequestSent|sendingbidinfo|PREBID_TIMEOUT|in|_guid|floorPrice001|null||stopad02|adopcalled||setTimeout|document|hb_pb|3E3|hb_adid|floor_price|getAdserverTargeting|bids|bidtrace|hasOwnProperty|toString|floor|showAlladopAd01|1E3|65536|getBidResponses|length|random|substring|cc|cpm|timeToRespond|getElementsByTagName|createElement|ui|img|height|width|src|bidder|ai|appendChild|data|head|adop||collect|hb|log|php|adId'.split('|'),0,{}));
