

document.write("j01 실행됨 <br />");
console.log("j01 실행됨");

var tmp = new Date();
console.log(tmp.getTime());
//document.write(' <scr'+'ipt async src="j02.js"></script>');

loadj('j02.js')

function loadj(scr){
    var head = document.getElementsByTagName('body')[0];
    var script = document.createElement('script');
    script.type = 'text/javascript';
    script.src = scr;
    head.appendChild(script);
}

