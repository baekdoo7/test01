var criteoZoneId = 1361904;
var criteoSlotId = "criteo_slot_1361904";
var passback = function () {
    var width = "300px", height = "250px";
    var div = document.getElementById('t100');
    if (div) {
        var ifr = document.createElement("iframe");
            ifr.setAttribute("id", criteoSlotId+"_iframe"),
            ifr.setAttribute("frameborder","0"),
            ifr.setAttribute("allowtransparency","true"),
            ifr.setAttribute("hspace","0"),
            ifr.setAttribute("marginwidth","0"),
            ifr.setAttribute("marginheight","0"),
            ifr.setAttribute("scrolling","no"),
            ifr.setAttribute("vspace","0"),
            ifr.setAttribute("width", width),
            ifr.setAttribute("height", height);
            div.appendChild(ifr);
var htmlcode = "<html><head></head><body><div id='t200'></div><scr"+"ipt src='com03__.php'></scr"+"ipt></body></html>";
            var ifrd = ifr.contentWindow.document;
                ifrd.open();
                ifrd.write(htmlcode);
                ifrd.close();
    }
    }
    passback();
