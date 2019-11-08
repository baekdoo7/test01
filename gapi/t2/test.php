<?php
/**
 * Created by IntelliJ IDEA.
 * User: baekdoo
 * Date: 2019. 2. 18.
 * Time: AM 10:22
 */
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>iframemain</title>
    <script>
        window.addEventListener('message',function(e){
            console.log(e.origin);
            console.log(e.data);
        })
    </script>
</head>
<body>
아이프레임 메인 테스트 <br />
<iframe src="iframe01.php" width="550" height="550"></iframe> <br />
iframemain
</body>
</html>