<?php
// 회원가입 처리
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $name = $_POST['name'];

    // MySQL 연결 설정
    $servername = "localhost";
    $db_username = "root";
    $db_password = "my_new_password";
    $dbname = "board";

    // MySQL 연결
    $conn = mysqli_connect($servername, $db_username, $db_password, $dbname);
    if (!$conn) {
        die("MySQL 연결 실패: " . mysqli_connect_error());
    }

    // 사용자 정보 저장
    $sql = "INSERT INTO users (username, password, name, users) VALUES ('$username', '$password', '$name', 'user_value')";
    mysqli_query($conn, $sql);

    mysqli_close($conn);

    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>회원가입</title>
</head>
<body>
    <h1>회원가입</h1>
    <form method="post">
        <label for="username">아이디:</label>
        <input type="text" name="username" id="username" required>
        <br>
        <label for="password">비밀번호:</label>
        <input type="password" name="password" id="password" required>
        <br>
        <label for="confirm_password">비밀번호 확인:</label>
        <input type="password" name="confirm_password" id="confirm_password" required>
        <br>
        <label for="name">이름:</label>
        <input type="text" name="name" id="name" required>
        <br>
        <input type="submit" value="회원가입">
    </form>
</body>
</html>

