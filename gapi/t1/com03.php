<?php
/**
 * Created by IntelliJ IDEA.
 * User: baekdoo
 * Date: 2019. 1. 28.
 * Time: PM 1:37
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>criteo->criteo->criteo->admanager</title>
    <script>

    </script>
</head>
<body>

    <!-- BEGIN CRITEO CDB -->
    <script async="async" type="text/javascript" src="//static.criteo.net/js/ld/publishertag.js"></script>
<div id="criteo_slot_1361903"></div>
<script>
        var criteoZoneId = 1361903;
        var criteoSlotId = "criteo_slot_1361903";
        var passback = function () {
            var width = "300px",
                height = "250px";
            var div = document.getElementById(criteoSlotId);
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
                    var htmlcode = "<html><head></head><body><div id='t100'></div><scr"+"ipt src='com03_.php'></scr"+"ipt></body></html>";
                    var ifrd = ifr.contentWindow.document;
                    ifrd.open();
                    ifrd.write(htmlcode);
                    ifrd.close();
            }
        }
        passback();
</script>
</body>
</html>





