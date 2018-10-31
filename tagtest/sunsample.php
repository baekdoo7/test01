<!DOCTYPE html>
<html>
<head lang="en">
    <script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/socket.io/1.3.6/socket.io.min.js"></script>

    <script type="text/javascript"> $(document).ready(function () {
        var socket = io.connect("http://13.125.62.224:8091/game");
        // var socket = io.connect("http://127.0.0.1:5000/game");
        socket.on('response', function (msg) {
            console.log(msg);
            if (msg.type == 'game_result'){
                $('#set_ready').attr('disabled',false);
                $('#set_ready').text('준비되면 눌러주세요');
            }
        });

        $('#game_send').on('click',function () {
            var game_id = $('#game_id').val();
            socket.emit('request',{type:'setid',uid:game_id});
            $('#game_send').attr('disabled',true);
        })

        $('#set_ready').on('click',function () {
            var game_id = $('#game_id').val();
            socket.emit('request',{type:'ready',uid:game_id});
            $('#set_ready').attr('disabled',true);
            $('#set_ready').text('준비완료');
        })

        $('#set_mjb').on('click',function () {
            var game_id = $('#game_id').val();
            socket.emit('request',{type:'mjb',uid:game_id,mjb:$('#input_mjb').val()});
            // $('#set_ready').attr('disabled',true);
            // $('#set_ready').text('준비완료');
        })
    }); </script>
    <meta charset="UTF-8">
    <title>websock test</title>
</head>
<body>
    <h1>Game</h1>
    <div id="received">

    </div>
    <div>
    아이디 :: <input id="game_id" value="sunjung"><button type="button" id="game_send">제출</button>
    </div>
    <div>
        <input id="input_mjb" value="mu"><button type="button" id="set_mjb">묵찌빠제출</button>
    </div>
    <p></p>
    <div>
        <button type="button" id="set_ready" style="width: 10%">준비되면 눌러주세요</button>
    </div>
</body>

</html>