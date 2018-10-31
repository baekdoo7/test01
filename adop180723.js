function makeid234() {
    for (var a = "", d = 0; 3 > d; d++) a += "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789".charAt(Math.floor(62 * Math.random()));
    return a
}

function adopRun001() {
    var a = document.getElementsByTagName("ins"),
        d = [],
        b;
    for (b = 0; b < a.length; b++) "adsbyadop" == a[b].className && "re" != a[b].getAttribute("_adop_type") && "RE" != a[b].getAttribute("_adop_type") && d.push(a[b]);
    if (!(0 >= d.length))
        for (var k = 0; k < d.length; k++) {
            var c = makeid234();
            a = {};
            var f = "";
            b = d[k];
            var g = window.location.href;
            var h = /compass.adop.cc/; - 1 != g.search(h) && (g = unescape(document.referrer));
            200 < g.length && (g = "");
            a["over-size"] = b.getAttribute("_over_size");
            a["over-size-w"] = b.getAttribute("_over_size_w");
            a["over-size-h"] = b.getAttribute("_over_size_h");
            a["over-zone"] = b.getAttribute("_over_zone");
            a["adop-zone"] = b.getAttribute("_adop_zon");
            if (null != a["over-size"] && "auto" == a["over-size"]) {
                h = JSON.parse(a["over-zone"]);
                __params_zone = b.getAttribute("_adop_zon");
                over_size = !1;
                maxWidth = 0;
                for (var l in h) {
                    var e = l.split("x", 2);
                    parseInt(document.body.clientWidth) >= parseInt(e[0]) && maxWidth < parseInt(e[0]) && (__params_zone = h[l], __ori_zone = a["adop-zone"], b.style.width = e[0] + "px", b.style.height = e[1] + "px", a.size_width = e[0], maxWidth = parseInt(e[0]), a.size_height = e[1])
                }
            } else null != a["over-size"] && document.body.clientWidth >= a["over-size"] ? (__params_zone = a["over-zone"], __ori_zone = a["adop-zone"], over_size = !0) : (__params_zone = b.getAttribute("_adop_zon"), over_size = !1);
            a.type = b.getAttribute("_adop_type");
            a.loc = b.getAttribute("_page_url") ? b.getAttribute("_page_url") : escape(g);
            a.size_width = b.style.width.replace("px", "");
            a.size_height = b.style.height.replace("px", "");
            try {
                a.title = encodeURIComponent(top.document.title.replace(/'/gi, "")), a.ref = encodeURIComponent(top.document.referrer)
            } catch (n) {
                a.title = "", a.ref = ""
            }
            over_size && (336 == a["over-size"] ? (b.style.width = "336px", b.style.height = "280px", a.size_width = 336, a.size_height = 280) : 468 == a["over-size"] ? (b.style.width = "468px", b.style.height = "60px", a.size_width = 468, a.size_height = 60) : 600 == a["over-size"] ? (b.style.width = "600px", b.style.height = "90px", a.size_width = 600, a.size_height = 90) : 728 == a["over-size"] ? (b.style.width = "728px", b.style.height = "90px", a.size_width = 728, a.size_height = 90) : 970 == a["over-size"] && (b.style.width = "970px", b.style.height = "90px", a.size_width = 970, a.size_height = 90), null != a["over-size-w"] && (b.style.width = a["over-size-w"], a.size_width = a["over-size-w"]), null != a["over-size-h"] && (b.style.height = a["over-size-h"], a.size_height = a["over-size-h"]), b.setAttribute("_adop_zon", __params_zone));
            b.className = "adsbyadop_" + __params_zone + c;
            c = "//compass.adop.cc/RD/" + encodeURIComponent(__params_zone);
            for (var m in a) f += encodeURIComponent(m) + "=" + encodeURIComponent(a[m]) + "&";
            f = c + (f ? "?" + f : "");
            c = document.createElement("IFRAME");
            c.setAttribute("src", f);
            c.setAttribute("id", __params_zone);
            c.setAttribute("border", "none");
            c.setAttribute("frameBorder", 0);
            c.setAttribute("marginWidth", 0);
            c.setAttribute("marginHeight", 0);
            c.setAttribute("paddingWidth", 0);
            c.setAttribute("paddingHeight", 0);
            c.setAttribute("width", a.size_width);
            c.setAttribute("height", a.size_height);
            c.setAttribute("scrolling", "no");
            b.appendChild(c)
        }
}
var checkLoad0999 = function() {
    "complete" !== document.readyState ? setTimeout(checkLoad0999, 11) : adopRun001()
};
checkLoad0999();
setTimeout(function() {
    adopRun001()
}, 200);
setTimeout(function() {
    adopRun001()
}, 700);
setTimeout(function() {
    adopRun001()
}, 5E3);