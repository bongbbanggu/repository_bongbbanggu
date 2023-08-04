<?php
session_start();

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

// 게시글 목록 조회
$sql = "SELECT * FROM posts ORDER BY timestamp DESC";
$postsResult = mysqli_query($conn, $sql);

// MySQL 연결 닫기
mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>게시판</title>
</head>
<body>
    <h1>게시판</h1>
    <?php if (isset($_SESSION['username'])): ?>
        <p>로그인 중인 사용자: <?php echo $_SESSION['username']; ?></p>
        <p><a href="logout.php">로그아웃</a></p>
    <?php else: ?>
        <p><a href="login.php">로그인</a></p>
        <p><a href="register.php">회원가입</a></p>
    <?php endif; ?>

    <h2></h2>
        </ul>
<p><a href="list_post.php">게시글 목록보기</a></p><br>

        <p><a href="write_post.php">게시글 작성하기</a></p>
</body>
</html>

