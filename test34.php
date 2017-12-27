<?php
    $id = "";
    $var = "";
    $tmp01 = "";
    $tmp02 = "";
    $id = $_GET['Id'];
    $var = $_GET['var'];


 /*
    $method = $_SERVER['REQUEST_METHOD'];
        if ('PUT' === $method) {
            parse_str(file_get_contents('php://input'), $_PUT);
            var_dump($_PUT); //$_PUT contains put fields 
        }
        */
?>
<!DOCTYPE html>
<html>
<head>   
<meta charset="UTF-8">    
<script type="application/javascript" >   
function send()
{
    var urlvariable;

    urlvariable = "text";

    var ItemJSON;

    ItemJSON = '[  {    "Id": 1,    "ProductID": "1",    "Quantity": 1,  },  {    "Id": 1,    "ProductID": "2",    "Quantity": 2,  }]';

    //URL = "https://testrestapi.com/additems?var=" + urlvariable;  //Your URL
    URL = "http://www.test01.com/test34.php?var=" + urlvariable+"&test=1234";
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = callbackFunction(xmlhttp);
    xmlhttp.open("POST", URL, false);
    xmlhttp.setRequestHeader("Content-Type", "application/json");
    //xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xmlhttp.setRequestHeader('Authorization', 'Basic ' + window.btoa('apiusername:apiuserpassword')); //in prod, you should encrypt user name and password and provide encrypted keys here instead 
    xmlhttp.onreadystatechange = callbackFunction(xmlhttp);
    xmlhttp.send(ItemJSON);
    alert(xmlhttp.responseText);
    document.getElementById("div").innerHTML = xmlhttp.statusText + ":" + xmlhttp.status + "<BR><textarea rows='100' cols='100'>" + xmlhttp.responseText + "</textarea>";
}

function callbackFunction(xmlhttp) 
{
    //alert(xmlhttp.responseXML);
}
</script>
</head> 
<body id='bod'>

<button type="submit" onclick="javascript:send()">call</button>
<div id='div'>

</div>
<hr />
    <?
        echo "ID = $id"; 
        echo "<br />";
        echo "var = $var";
        echo "<br />";
        echo $_SERVER['REQUEST_METHOD'];
        echo "<br />";
        //echo $tmp01;
        parse_str(file_get_contents('php://input'), $_PUT);
        var_dump($_PUT); //$_PUT contains put fields 
       
    ?>
<hr />
</html>
</body>