///#source 1 1 /src/1.0.0/load.js
/*! head.load - v1.0.3 */
/*
 * HeadJS     The only script in your <HEAD>
 * Author     Tero Piirainen  (tipiirai)
 * Maintainer Robert Hoffmann (itechnology)
 * License    MIT / http://bit.ly/mit-license
 * WebSite    http://headjs.com
 */
(function (win, undefined) {
    "use strict";


    //#region variables
    var doc        = win.document,
        domWaiters = [],
        handlers   = {}, // user functions waiting for events
        assets     = {}, // loadable items in various states
        isAsync    = "async" in doc.createElement("script") || "MozAppearance" in doc.documentElement.style || win.opera,
        isDomReady,

        /*** public API ***/
        headVar = win.head_conf && win.head_conf.head || "head",
        api     = win[headVar] = (win[headVar] || function () { api.ready.apply(null, arguments); }),

    // states
        PRELOADING = 1,
        PRELOADED  = 2,
        LOADING    = 3,
        LOADED     = 4;
    //#endregion

    //#region PRIVATE functions

    //#region Helper functions
    function noop() {
        // does nothing
    }

    function each(arr, callback) {
        if (!arr) {
            return;
        }

        // arguments special type
        if (typeof arr === "object") {
            arr = [].slice.call(arr);
        }

        // do the job
        for (var i = 0, l = arr.length; i < l; i++) {
            callback.call(arr, arr[i], i);
        }
    }

    /* A must read: http://bonsaiden.github.com/JavaScript-Garden
     ************************************************************/
    function is(type, obj) {
        var clas = Object.prototype.toString.call(obj).slice(8, -1);
        return obj !== undefined && obj !== null && clas === type;
    }

    function isFunction(item) {
        return is("Function", item);
    }

    function isArray(item) {
        return is("Array", item);
    }

    function toLabel(url) {
        ///<summary>Converts a url to a file label</summary>
        var items = url.split("/"),
            name = items[items.length - 1],
            i    = name.indexOf("?");

        return i !== -1 ? name.substring(0, i) : name;
    }

    // INFO: this look like a "im triggering callbacks all over the place, but only wanna run it one time function" ..should try to make everything work without it if possible
    // INFO: Even better. Look into promises/defered's like jQuery is doing
    function one(callback) {
        ///<summary>Execute a callback only once</summary>
        callback = callback || noop;

        if (callback._done) {
            return;
        }

        callback();
        callback._done = 1;
    }
    //#endregion

    function conditional(test, success, failure, callback) {
        ///<summary>
        /// INFO: use cases:
        ///    head.test(condition, null       , "file.NOk" , callback);
        ///    head.test(condition, "fileOk.js", null       , callback);
        ///    head.test(condition, "fileOk.js", "file.NOk" , callback);
        ///    head.test(condition, "fileOk.js", ["file.NOk", "file.NOk"], callback);
        ///    head.test({
        ///               test    : condition,
        ///               success : [{ label1: "file1Ok.js"  }, { label2: "file2Ok.js" }],
        ///               failure : [{ label1: "file1NOk.js" }, { label2: "file2NOk.js" }],
        ///               callback: callback
        ///    );
        ///    head.test({
        ///               test    : condition,
        ///               success : ["file1Ok.js" , "file2Ok.js"],
        ///               failure : ["file1NOk.js", "file2NOk.js"],
        ///               callback: callback
        ///    );
        ///</summary>
        var obj = (typeof test === "object") ? test : {
            test: test,
            success: !!success ? isArray(success) ? success : [success] : false,
            failure: !!failure ? isArray(failure) ? failure : [failure] : false,
            callback: callback || noop
        };

        // Test Passed ?
        var passed = !!obj.test;

        // Do we have a success case
        if (passed && !!obj.success) {
            obj.success.push(obj.callback);
            api.load.apply(null, obj.success);
        }
        // Do we have a fail case
        else if (!passed && !!obj.failure) {
            obj.failure.push(obj.callback);
            api.load.apply(null, obj.failure);
        }
        else {
            callback();
        }

        return api;
    }

    function getAsset(item) {
        ///<summary>
        /// Assets are in the form of
        /// {
        ///     name : label,
        ///     url  : url,
        ///     state: state
        /// }
        ///</summary>
        var asset = {};

        if (typeof item === "object") {
            for (var label in item) {
                if (!!item[label]) {
                    asset = {
                        name: label,
                        url : item[label]
                    };
                }
            }
        }
        else {
            asset = {
                name: toLabel(item),
                url : item
            };
        }

        // is the item already existant
        var existing = assets[asset.name];
        if (existing && existing.url === asset.url) {
            return existing;
        }

        assets[asset.name] = asset;
        return asset;
    }

    function allLoaded(items) {
        items = items || assets;

        for (var name in items) {
            if (items.hasOwnProperty(name) && items[name].state !== LOADED) {
                return false;
            }
        }

        return true;
    }

    function onPreload(asset) {
        asset.state = PRELOADED;

        each(asset.onpreload, function (afterPreload) {
            afterPreload.call();
        });
    }

    function preLoad(asset, callback) {
        if (asset.state === undefined) {

            asset.state     = PRELOADING;
            asset.onpreload = [];

            loadAsset({ url: asset.url, type: "cache" }, function () {
                onPreload(asset);
            });
        }
    }

    function apiLoadHack() {
        /// <summary>preload with text/cache hack
        ///
        /// head.load("http://domain.com/file.js","http://domain.com/file.js", callBack)
        /// head.load(["http://domain.com/file.js","http://domain.com/file.js"], callBack)
        /// head.load({ label1: "http://domain.com/file.js" }, { label2: "http://domain.com/file.js" }, callBack)
        /// head.load([{ label1: "http://domain.com/file.js" }, { label2: "http://domain.com/file.js" }], callBack)
        /// </summary>
        var args     = arguments,
            callback = args[args.length - 1],
            rest     = [].slice.call(args, 1),
            next     = rest[0];

        if (!isFunction(callback)) {
            callback = null;
        }

        // if array, repush as args
        if (isArray(args[0])) {
            args[0].push(callback);
            api.load.apply(null, args[0]);

            return api;
        }

        // multiple arguments
        if (!!next) {
            /* Preload with text/cache hack (not good!)
             * http://blog.getify.com/on-script-loaders/
             * http://www.nczonline.net/blog/2010/12/21/thoughts-on-script-loaders/
             * If caching is not configured correctly on the server, then items could load twice !
             *************************************************************************************/
            each(rest, function (item) {
                // item is not a callback or empty string
                if (!isFunction(item) && !!item) {
                    preLoad(getAsset(item));
                }
            });

            // execute
            load(getAsset(args[0]), isFunction(next) ? next : function () {
                api.load.apply(null, rest);
            });
        }
        else {
            // single item
            load(getAsset(args[0]));
        }

        return api;
    }

    function apiLoadAsync() {
        ///<summary>
        /// simply load and let browser take care of ordering
        ///
        /// head.load("http://domain.com/file.js","http://domain.com/file.js", callBack)
        /// head.load(["http://domain.com/file.js","http://domain.com/file.js"], callBack)
        /// head.load({ label1: "http://domain.com/file.js" }, { label2: "http://domain.com/file.js" }, callBack)
        /// head.load([{ label1: "http://domain.com/file.js" }, { label2: "http://domain.com/file.js" }], callBack)
        ///</summary>
        var args     = arguments,
            callback = args[args.length - 1],
            items    = {};

        if (!isFunction(callback)) {
            callback = null;
        }

        // if array, repush as args
        if (isArray(args[0])) {
            args[0].push(callback);
            api.load.apply(null, args[0]);

            return api;
        }

        // JRH 262#issuecomment-26288601
        // First populate the items array.
        // When allLoaded is called, all items will be populated.
        // Issue when lazy loaded, the callback can execute early.
        each(args, function (item, i) {
            if (item !== callback) {
                item             = getAsset(item);
                items[item.name] = item;
            }
        });

        each(args, function (item, i) {
            if (item !== callback) {
                item = getAsset(item);

                load(item, function () {
                    if (allLoaded(items)) {
                        one(callback);
                    }
                });
            }
        });

        return api;
    }

    function load(asset, callback) {
        ///<summary>Used with normal loading logic</summary>

        callback = callback || noop;

        if (asset.state === LOADED) {
            callback();
            return;
        }

        // INFO: why would we trigger a ready event when its not really loaded yet ?
        if (asset.state === LOADING) {
            api.ready(asset.name, callback);
            return;
        }

        if (asset.state === PRELOADING) {
            asset.onpreload.push(function () {
                load(asset, callback);
            });
            return;
        }

        asset.state = LOADING;

        loadAsset(asset, function () {
            asset.state = LOADED;

            callback();

            // handlers for this asset
            each(handlers[asset.name], function (fn) {
                one(fn);
            });

            // dom is ready & no assets are queued for loading
            // INFO: shouldn't we be doing the same test above ?
            if (isDomReady && allLoaded()) {
                each(handlers.ALL, function (fn) {
                    one(fn);
                });
            }
        });
    }

    function getExtension(url) {
        url = url || "";

        var items = url.split("?")[0].split(".");
        return items[items.length-1].toLowerCase();
    }

    /* Parts inspired from: https://github.com/cujojs/curl
     ******************************************************/
    function loadAsset(asset, callback) {
        callback = callback || noop;

        function error(event) {
            event = event || win.event;

            // release event listeners
            ele.onload = ele.onreadystatechange = ele.onerror = null;

            // do callback
            callback();

            // need some more detailed error handling here
        }

        function process(event) {
            event = event || win.event;

            // IE 7/8 (2 events on 1st load)
            // 1) event.type = readystatechange, s.readyState = loading
            // 2) event.type = readystatechange, s.readyState = loaded

            // IE 7/8 (1 event on reload)
            // 1) event.type = readystatechange, s.readyState = complete

            // event.type === 'readystatechange' && /loaded|complete/.test(s.readyState)

            // IE 9 (3 events on 1st load)
            // 1) event.type = readystatechange, s.readyState = loading
            // 2) event.type = readystatechange, s.readyState = loaded
            // 3) event.type = load            , s.readyState = loaded

            // IE 9 (2 events on reload)
            // 1) event.type = readystatechange, s.readyState = complete
            // 2) event.type = load            , s.readyState = complete

            // event.type === 'load'             && /loaded|complete/.test(s.readyState)
            // event.type === 'readystatechange' && /loaded|complete/.test(s.readyState)

            // IE 10 (3 events on 1st load)
            // 1) event.type = readystatechange, s.readyState = loading
            // 2) event.type = load            , s.readyState = complete
            // 3) event.type = readystatechange, s.readyState = loaded

            // IE 10 (3 events on reload)
            // 1) event.type = readystatechange, s.readyState = loaded
            // 2) event.type = load            , s.readyState = complete
            // 3) event.type = readystatechange, s.readyState = complete

            // event.type === 'load'             && /loaded|complete/.test(s.readyState)
            // event.type === 'readystatechange' && /complete/.test(s.readyState)

            // Other Browsers (1 event on 1st load)
            // 1) event.type = load, s.readyState = undefined

            // Other Browsers (1 event on reload)
            // 1) event.type = load, s.readyState = undefined

            // event.type == 'load' && s.readyState = undefined

            // !doc.documentMode is for IE6/7, IE8+ have documentMode
            if (event.type === "load" || (/loaded|complete/.test(ele.readyState) && (!doc.documentMode || doc.documentMode < 9))) {
                // remove timeouts
                win.clearTimeout(asset.errorTimeout);
                win.clearTimeout(asset.cssTimeout);

                // release event listeners
                ele.onload = ele.onreadystatechange = ele.onerror = null;

                // do callback
                callback();
            }
        }

        function isCssLoaded() {
            // should we test again ? 20 retries = 5secs ..after that, the callback will be triggered by the error handler at 7secs
            if (asset.state !== LOADED && asset.cssRetries <= 20) {

                // loop through stylesheets
                for (var i = 0, l = doc.styleSheets.length; i < l; i++) {
                    // do we have a match ?
                    // we need to tests agains ele.href and not asset.url, because a local file will be assigned the full http path on a link element
                    if (doc.styleSheets[i].href === ele.href) {
                        process({ "type": "load" });
                        return;
                    }
                }

                // increment & try again
                asset.cssRetries++;
                asset.cssTimeout = win.setTimeout(isCssLoaded, 250);
            }
        }

        var ele;
        var ext = getExtension(asset.url);
        if (ext === "css") {
            ele      = doc.createElement("link");
            ele.type = "text/" + (asset.type || "css");
            ele.rel  = "stylesheet";
            ele.href = asset.url;

            /* onload supported for CSS on unsupported browsers
             * Safari windows 5.1.7, FF < 10
             */

            // Set counter to zero
            asset.cssRetries = 0;
            asset.cssTimeout = win.setTimeout(isCssLoaded, 500);
        }
        else {
            ele      = doc.createElement("script");
            ele.type = "text/" + (asset.type || "javascript");
            ele.src = asset.url;
        }

        ele.onload  = ele.onreadystatechange = process;
        ele.onerror = error;

        /* Good read, but doesn't give much hope !
         * http://blog.getify.com/on-script-loaders/
         * http://www.nczonline.net/blog/2010/12/21/thoughts-on-script-loaders/
         * https://hacks.mozilla.org/2009/06/defer/
         */

        // ASYNC: load in parallel and execute as soon as possible
        ele.async = false;
        // DEFER: load in parallel but maintain execution order
        ele.defer = false;

        // timout for asset loading
        asset.errorTimeout = win.setTimeout(function () {
            error({ type: "timeout" });
        }, 1000);//7e3

        // use insertBefore to keep IE from throwing Operation Aborted (thx Bryan Forbes!)
        var head = doc.head || doc.getElementsByTagName("head")[0];
        var items = doc.getElementsByTagName("script");
        var itemBloon = false;
        for( var itemCnt in items ) {
            if ( items[itemCnt].src != '' && items[itemCnt].src == asset.url ) {
                itemBloon = true;
            }
        }

        // but insert at end of head, because otherwise if it is a stylesheet, it will not override values
        if( !itemBloon)
            head.insertBefore(ele, head.lastChild);
    }

    /* Parts inspired from: https://github.com/jrburke/requirejs
     ************************************************************/
    function init() {
        var items = doc.getElementsByTagName("script");

        // look for a script with a data-head-init attribute
        for (var i = 0, l = items.length; i < l; i++) {
            var dataMain = items[i].getAttribute("data-headjs-load");
            if (!!dataMain) {
                api.load(dataMain);
                return;
            }
        }
    }

    function ready(key, callback) {
        ///<summary>
        /// INFO: use cases:
        ///    head.ready(callBack);
        ///    head.ready(document , callBack);
        ///    head.ready("file.js", callBack);
        ///    head.ready("label"  , callBack);
        ///    head.ready(["label1", "label2"], callback);
        ///</summary>

        // DOM ready check: head.ready(document, function() { });
        if (key === doc) {
            if (isDomReady) {
                one(callback);
            }
            else {
                domWaiters.push(callback);
            }

            return api;
        }

        // shift arguments
        if (isFunction(key)) {
            callback = key;
            key      = "ALL"; // holds all callbacks that where added without labels: ready(callBack)
        }

        // queue all items from key and return. The callback will be executed if all items from key are already loaded.
        if (isArray(key)) {
            var items = {};

            each(key, function (item) {
                items[item] = assets[item];

                api.ready(item, function() {
                    if (allLoaded(items)) {
                        one(callback);
                    }
                });
            });

            return api;
        }

        // make sure arguments are sane
        if (typeof key !== "string" || !isFunction(callback)) {
            return api;
        }

        // this can also be called when we trigger events based on filenames & labels
        var asset = assets[key];

        // item already loaded --> execute and return
        if (asset && asset.state === LOADED || key === "ALL" && allLoaded() && isDomReady) {
            one(callback);
            return api;
        }

        var arr = handlers[key];
        if (!arr) {
            arr = handlers[key] = [callback];
        }
        else {
            arr.push(callback);
        }

        return api;
    }

    /* Mix of stuff from jQuery & IEContentLoaded
     * http://dev.w3.org/html5/spec/the-end.html#the-end
     ***************************************************/
    function domReady() {
        // Make sure body exists, at least, in case IE gets a little overzealous (jQuery ticket #5443).
        if (!doc.body) {
            // let's not get nasty by setting a timeout too small.. (loop mania guaranteed if assets are queued)
            win.clearTimeout(api.readyTimeout);
            api.readyTimeout = win.setTimeout(domReady, 50);
            return;
        }

        if (!isDomReady) {
            isDomReady = true;

            init();
            each(domWaiters, function (fn) {
                one(fn);
            });
        }
    }

    function domContentLoaded() {
        // W3C
        if (doc.addEventListener) {
            doc.removeEventListener("DOMContentLoaded", domContentLoaded, false);
            domReady();
        }

        // IE
        else if (doc.readyState === "complete") {
            // we're here because readyState === "complete" in oldIE
            // which is good enough for us to call the dom ready!
            doc.detachEvent("onreadystatechange", domContentLoaded);
            domReady();
        }
    }

    // Catch cases where ready() is called after the browser event has already occurred.
    // we once tried to use readyState "interactive" here, but it caused issues like the one
    // discovered by ChrisS here: http://bugs.jquery.com/ticket/12282#comment:15
    if (doc.readyState === "complete") {
        domReady();
    }

    // W3C
    else if (doc.addEventListener) {
        doc.addEventListener("DOMContentLoaded", domContentLoaded, false);

        // A fallback to window.onload, that will always work
        win.addEventListener("load", domReady, false);
    }

    // IE
    else {
        // Ensure firing before onload, maybe late but safe also for iframes
        doc.attachEvent("onreadystatechange", domContentLoaded);

        // A fallback to window.onload, that will always work
        win.attachEvent("onload", domReady);

        // If IE and not a frame
        // continually check to see if the document is ready
        var top = false;

        try {
            top = !win.frameElement && doc.documentElement;
        } catch (e) { }

        if (top && top.doScroll) {
            (function doScrollCheck() {
                if (!isDomReady) {
                    try {
                        // Use the trick by Diego Perini
                        // http://javascript.nwbox.com/IEContentLoaded/
                        top.doScroll("left");
                    } catch (error) {
                        // let's not get nasty by setting a timeout too small.. (loop mania guaranteed if assets are queued)
                        win.clearTimeout(api.readyTimeout);
                        api.readyTimeout = win.setTimeout(doScrollCheck, 50);
                        return;
                    }

                    // and execute any waiting functions
                    domReady();
                }
            }());
        }
    }
    //#endregion

    //#region Public Exports
    // INFO: determine which method to use for loading
    api.load  = api.js = isAsync ? apiLoadAsync : apiLoadHack;
    api.test  = conditional;
    api.ready = ready;
    //#endregion

    //#region INIT
    // perform this when DOM is ready
    api.ready(doc, function () {
        if (allLoaded()) {
            each(handlers.ALL, function (callback) {
                one(callback);
            });
        }

        if (api.feature) {
            api.feature("domloaded", true);
        }
    });
    //#endregion
}(window));


function makeid()
{
    var text = "";
    var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

    for( var i=0; i < 3; i++ )
        text += possible.charAt(Math.floor(Math.random() * possible.length));

    return text;
}

if (location.href.indexOf("https://") == -1) {
    var adop_pro = "http://";
}else{
    var adop_pro = "https://";
}

var set_durl = {cdn: adop_pro + 'compass.adop.cc/',advs: adop_pro + 'compass.adop.cc/'};
//var set_durl = {cdn:'http://compass.adop.cc/',advs:'http://compass.adop.cc/'};

(function(){
    head.load([set_durl.cdn+"assets/js/adop/jquery-1.12.0.js", set_durl.advs+"assets/js/adop/adop.common.js"], function() {

        var ajq = jQuery.noConflict();
        var $adp = ajq(document).find('ins.adsbyadop').eq(0);
        var rand_code = makeid();

        var getPageUrl = '';
        try{
            getPageUrl = ajq(location).attr('href');
            var regExp = /compass\.adop\.cc/;
            if( getPageUrl.search( regExp ) ) {
                getPageUrl = unescape(document.referrer);
            }
        } catch (e) {
            getPageUrl = unescape(document.referrer);
        }

        if( getPageUrl.length > 200 )   getPageUrl = '';

        var __params = {};

        __params['over-size'] = $adp.attr('_over_size');
        __params['over-zone'] = $adp.attr('_over_zone');
        __params['adop-zone'] = $adp.attr('_adop_zon');

        if(document.body.clientWidth >= __params['over-size']){
            __params_zone = __params['over-zone'];
            __ori_zone = __params['adop-zone']
            over_size = true;

        }else{
            __params_zone = $adp.attr('_adop_zon');
            over_size = false;
        }

        __params['type'] = $adp.attr('_adop_type');
        __params['loc'] = $adp.attr('_page_url') ? $adp.attr('_page_url') : escape(getPageUrl);
        // __params['ref'] = escape(document.referrer);

        try {
            __params['size_width'] = $adp.css('width').replace('px', '');
        } catch( e ) {
            ajq(document).ready(function(){
                $adp = ajq(document).find('ins.adsbyadop').eq(0);
                __params_zone = $adp.attr('_adop_zon');
                if(over_size){
                    __params_zone = __params['over-zone'];
                }
                __params['type'] = $adp.attr('_adop_type');
                __params['loc'] = $adp.attr('_page_url') ? $adp.attr('_page_url') : escape(getPageUrl);
                // __params['ref'] = escape(document.referrer);
                __params['size_width'] = $adp.css('width').replace('px', '');
                __params['size_height'] = $adp.css('height').replace('px', '');

                if (over_size){
                    if(__params['over-size'] == 728){
                        ajq(document).find('ins.adsbyadop' + '[_adop_zon="' + __ori_zone + '"]').css('width', "728px");
                        ajq(document).find('ins.adsbyadop' + '[_adop_zon="' + __ori_zone + '"]').css('height', "90px");
                        __params['size_width'] = 728;
                        __params['size_height'] = 90;
                    }else if(__params['over-size'] == 970) {
                        ajq(document).find('ins.adsbyadop' + '[_adop_zon="' + __ori_zone + '"]').css('width', "970px");
                        ajq(document).find('ins.adsbyadop' + '[_adop_zon="' + __ori_zone + '"]').css('height', "90px");
                        __params['size_width'] = 970;
                        __params['size_height'] = 90;
                    }else if(__params['over-size'] == 468){
                        ajq(document).find('ins.adsbyadop' + '[_adop_zon="' + __ori_zone + '"]').css('width', "468px");
                        ajq(document).find('ins.adsbyadop' + '[_adop_zon="' + __ori_zone + '"]').css('height', "60px");
                        __params['size_width'] = 468;
                        __params['size_height'] = 60;
                    }else if(__params['over-size'] == 336){
                        ajq(document).find('ins.adsbyadop' + '[_adop_zon="' + __ori_zone + '"]').css('width', "336px");
                        ajq(document).find('ins.adsbyadop' + '[_adop_zon="' + __ori_zone + '"]').css('height', "280px");
                        __params['size_width'] = 336;
                        __params['size_height'] = 280;
                    }
                    ajq(document).find('ins.adsbyadop' + '[_adop_zon="' + __ori_zone + '"]').attr('_adop_zon',__params_zone);
                }
                ajq(document).find('ins.adsbyadop' + '[_adop_zon="' + __params_zone + '"]').removeClass('adsbyadop').addClass("adsbyadop_" + __params_zone + rand_code);

                var __url = set_durl.advs + 'RD/' + encodeURIComponent(__params_zone);
                var params = ajq.param(__params);
                var iurl = __url+((params)?'?'+params:'');

                ajq('<iframe>', {
                    src: iurl,
                    id:  __params_zone,
                    frameborder: 0,
                    marginwidth: 0,
                    marginheight: 0,
                    paddingwidth: 0,
                    paddingheight: 0,
                    width:  __params['size_width'],
                    height:  __params['size_height'],
                    scrolling: 'no'
                }).appendTo('ins.adsbyadop_' + __params_zone + rand_code + '[_adop_zon="'+__params_zone +'"]');
            });
        }

        __params['size_height'] = $adp.css('height').replace('px', '');


        if (over_size){
            if(__params['over-size'] == 728){
                ajq(document).find('ins.adsbyadop' + '[_adop_zon="' + __ori_zone + '"]').css('width', "728px");
                ajq(document).find('ins.adsbyadop' + '[_adop_zon="' + __ori_zone + '"]').css('height', "90px");
                __params['size_width'] = 728;
                __params['size_height'] = 90;
            }else if(__params['over-size'] == 970){
                ajq(document).find('ins.adsbyadop' + '[_adop_zon="' + __ori_zone + '"]').css('width', "970px");
                ajq(document).find('ins.adsbyadop' + '[_adop_zon="' + __ori_zone + '"]').css('height', "90px");
                __params['size_width'] = 970;
                __params['size_height'] = 90;
            }else if(__params['over-size'] == 468){
                ajq(document).find('ins.adsbyadop' + '[_adop_zon="' + __ori_zone + '"]').css('width', "468px");
                ajq(document).find('ins.adsbyadop' + '[_adop_zon="' + __ori_zone + '"]').css('height', "60px");
                __params['size_width'] = 468;
                __params['size_height'] = 60;
            }else if(__params['over-size'] == 336){
                ajq(document).find('ins.adsbyadop' + '[_adop_zon="' + __ori_zone + '"]').css('width', "336px");
                ajq(document).find('ins.adsbyadop' + '[_adop_zon="' + __ori_zone + '"]').css('height', "280px");
                __params['size_width'] = 336;
                __params['size_height'] = 280;
            }

            ajq(document).find('ins.adsbyadop' + '[_adop_zon="' + __ori_zone + '"]').attr('_adop_zon',__params_zone);
        }
        ajq(document).find('ins.adsbyadop' + '[_adop_zon="' + __params_zone + '"]').removeClass('adsbyadop').addClass("adsbyadop_" + __params_zone + rand_code);
        //ajq(document).find('ins.adsbyadop' + '[_adop_zon="' + __params_zone + '"]').removeClass('adsbyadop').addClass("adsbyadop_" + __params_zone + rand_code);

        var __url = set_durl.advs + 'RD/' + encodeURIComponent(__params_zone);
        var params = ajq.param(__params);
        var iurl = __url+((params)?'?'+params:'');

        ajq('<iframe>', {
            src: iurl,
            id:  __params_zone,
            frameborder: 0,
            marginwidth: 0,
            marginheight: 0,
            paddingwidth: 0,
            paddingheight: 0,
            width:  __params['size_width'],
            height:  __params['size_height'],
            scrolling: 'no'
        }).appendTo('ins.adsbyadop_' + __params_zone + rand_code + '[_adop_zon="'+__params_zone +'"]');
    });
})();