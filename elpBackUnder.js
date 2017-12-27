(function(window) {
    'use strict';
    var
        debug = false,
        baseName = 'EnlipleBackAd',
        makeMethod = 'make',

        Popunder = function(url,gubun,s,userid) {
            this.__construct(url,gubun,s,userid);
        };
    Popunder.prototype = {
        __construct: function(url,gubun,s,userid) {
            this.url      = url;
            this.s        = s;
            this.gubun    = gubun;
            this.userid    = userid;
            this.register(this.url ,this.s,this.gubun,this.userid);
        },
        register: function(adUrl ,s , gubun,userid){

            if(adUrl != undefined){
                adUrl = adUrl.replace(/&amp;/g ,"&" );
            }
            if(s== '6073' || gubun == 'HB'){
                if("#news" == window.location.hash ){
                    history.replaceState(null, document.title, location.origin +location.pathname);
                }
                setTimeout(function(){
                    history.pushState({}, document.title, window.location.href+"#_enliple");
                },1000);

                window.addEventListener('hashchange', function(e){
                    if(window.location.hash.indexOf("#") != -1 ){
                    }else{
                        setTimeout(function(){
                            location.replace(adUrl);
                        },25);
                    }
                });
            }else{
                // var url1 = location.origin;
                // var url2 = location.href;
                //	history.replaceState(null, document.title, url1);
                //	history.pushState(null, document.title, adUrl);

                // window.addEventListener("popstate", function() {
                //	history.replaceState(null, document.title, url2);
                //	setTimeout(function(){
                //	    location.replace(adUrl);
                //	},0);

                //	}, false);
                if("#news" == window.location.hash ){
                    history.replaceState(null, document.title, location.origin +location.pathname);
                }
                if(!window.location.hash){
                    history.pushState({}, document.title, window.location.href+"#_enliple");
                }
                window.addEventListener('hashchange', function(e){
                    setTimeout(function(){
                        if(userid =="hnsmall01"){
                            adUrl = encodeURIComponent(adUrl);
                            location.replace("//www.dreamsearch.or.kr/ad/ico/deepLinkConfirmPage.html?adUrl="+adUrl+"&mediascript="+s+"&gubun="+gubun+"&userid="+userid+"&moveToPage="+window.location.origin);
                        }else{
                            if(window.location.hash.indexOf("#") != -1 ){
                            }else{
                                location.replace(adUrl);
                            }
                        }
                    },25);
                });

            }
        },
    };
    Popunder[makeMethod] = function(url ,gubun ,s,userid) {
        return new this(url ,gubun,s,userid);
    };
    window[baseName] = Popunder;
})(this);
