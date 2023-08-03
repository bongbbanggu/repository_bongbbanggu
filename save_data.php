<?php
// MySQL 연결 설정
$servername = "localhost";
$username = "root";
$password = "my_new_password";
$dbname = "webdata";

// MySQL 연결
$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) {
    die("MySQL 연결 실패: " . mysqli_connect_error());
}

// POST로 전송된 데이터 받기
$username = $_POST['username'];
$password = $_POST['password'];
$name = $_POST['name'];
$email = $_POST['email'];

// 데이터베이스에 데이터 저장
$sql = "INSERT INTO users (username, password, name, email) VALUES ('$username', '$password', '$name', '$email')";

if (mysqli_query($conn, $sql)) {
    echo "데이터가 성공적으로 저장되었습니다.";
} else {
    echo "MySQL 쿼리 오류: " . mysqli_error($conn);
}

mysqli_close($conn);
?>

