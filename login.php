<!-- login.php -->
<?php
session_start();

// 로그인 처리
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // MySQL 연결 설정
    $servername = "localhost";
    $username = "root";
    $password = "my_new_password";
    $dbname = "board";

    // MySQL 연결
    $conn = mysqli_connect($servername, $username, $password, $dbname);
    if (!$conn) {
        die("MySQL 연결 실패: " . mysqli_connect_error());
    }

    // 사용자 정보 확인
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        $_SESSION['username'] = $username;
        header('Location: index.php');
        exit();
    } else {
        echo "로그인 실패";
    }

    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>로그인</title>
</head>
<body>
    <h1>로그인</h1>
    <form method="post">
        <label for="username">아이디:</label>
        <input type="text" name="username" id="username" required>
        <br>
        <label for="password">비밀번호:</label>
        <input type="password" name="password" id="password" required>
        <br>
        <input type="submit" value="로그인">
    </form>
    <a href="register.php">회원가입</a>
</body>
</html>

