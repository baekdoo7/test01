
<html>
<body>
    <div id="root"></div>
    <script>
        var host = 'ws://www.test01.com:12345';
        var host = 'ws://www.test01.com:12345';
        var socket = new WebSocket(host);
        socket.onmessage = function(e) {
            document.getElementById('root').innerHTML = e.data;
        };
    </script>
</body>
</html>