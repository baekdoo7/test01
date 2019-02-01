<?php
/**
 * Created by IntelliJ IDEA.
 * User: baekdoo
 * Date: 2019. 1. 14.
 * Time: PM 4:21
 */

?>

<!doctype html>
<html lang="ko">
<head>
    <title>연속 노프레임 테스팅...</title>
</head>
<body>

<script>
    var passback = function (str) {
        alert(str);
    }
    var callAD = function (areaid) {
        var httpRequest;
        var callUrl = "http://compasstest.adop.cc/RD/"+areaid;
        if (window.XMLHttpRequest) {
            httpRequest = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            httpRequest = new ActiveXObject("Microsoft.XMLHTTP");
        }

        if(httpRequest){
            httpRequest.onreadystatechange = function(){
                if(httpRequest.readyState === XMLHttpRequest.DONE){
                    if (httpRequest.status === 200) {
                        passback(httpRequest.responseText);
                    }
                    else{
                        //alert('문제',httpRequest.status);
                    }
                }
                else{

                }
            }
            httpRequest.open('GET',callUrl , true);
            httpRequest.send(null);
        }

    }
callAD('b61c02ef-134b-42b4-aa77-814740ae0c7d');
</script>

<hr />
<hr />
<hr />
<hr />
<hr />
<hr />
<hr />
</body>
</html>

