

document.write("j04 실행됨 <br />");
console.log("j04 실행됨");
sleep(1000);

function sleep(milliseconds) {
  var start = new Date().getTime();
  for (var i = 0; i < 1e7; i++) {
    if ((new Date().getTime() - start) > milliseconds){
      break;
    }
  }
}