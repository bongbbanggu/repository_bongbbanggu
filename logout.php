<!-- logout.php -->
<?php
session_start();
session_unset();
session_destroy();
?>

<!DOCTYPE html>
<html>
<head>
    <title>로그아웃</title>
</head>
<body>
    <h1>로그아웃되었습니다.</h1>
    <a href="login.php">로그인 화면으로</a>
</body>
</html>

