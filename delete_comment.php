<?php
session_start();

// 사용자가 로그인했는지 확인
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

// comment_id가 URL에 제공되었는지 확인
if (!isset($_GET['comment_id'])) {
    header('Location: index.php');
    exit();
}

// URL에서 comment_id 가져오기
$comment_id = $_GET['comment_id'];

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

// 댓글 정보 가져오기
$sql = "SELECT * FROM comments WHERE comment_id = '$comment_id'";
$commentResult = mysqli_query($conn, $sql);

if (!$commentResult || mysqli_num_rows($commentResult) === 0) {
    header('Location: index.php');
    exit();
}

$commentRow = mysqli_fetch_assoc($commentResult);

// 댓글이 로그인한 사용자의 것인지 확인
if ($commentRow['author'] !== $_SESSION['username']) {
    header('Location: view_post.php?id=' . $commentRow['post_id']);
    exit();
}

// 댓글 삭제 처리
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_comment'])) {
    // 데이터베이스에서 댓글 삭제
    $sql = "DELETE FROM comments WHERE comment_id = '$comment_id'";
    mysqli_query($conn, $sql);

    // 삭제 후 view_post.php로 리디렉션
    header('Location: view_post.php?id=' . $commentRow['post_id']);
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>댓글 삭제</title>
</head>
<body>
    <h1>댓글 삭제</h1>
    <p>댓글 내용: <?php echo $commentRow['content']; ?></p>
    <form method="post">
        <input type="submit" name="delete_comment" value="댓글 삭제 완료">
    </form>
</body>
</html>

