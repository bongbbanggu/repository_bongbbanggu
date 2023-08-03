<?php
session_start();

// 사용자가 로그인했는지 확인
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

// comment_id가 URL에 제공되었는지 확인
if (!isset($_GET['comment_id']) || !isset($_GET['post_id'])) {
    header('Location: index.php');
    exit();
}

// URL에서 comment_id 가져오기
$comment_id = $_GET['comment_id'];
$post_id = $_GET['post_id'];

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
    header('Location: view_post.php?id=' . $post_id);
    exit();
}

$commentRow = mysqli_fetch_assoc($commentResult);

// 댓글이 로그인한 사용자의 것인지 확인
if ($commentRow['author'] !== $_SESSION['username']) {
    header('Location: view_post.php?id=' . $post_id);
    exit();
}

// 댓글 수정 폼 제출 처리
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_comment'])) {
    $newCommentContent = $_POST['new_comment_content'];

    // 데이터베이스에서 댓글 업데이트
    $sql = "UPDATE comments SET content = '$newCommentContent' WHERE comment_id = '$comment_id'";
    mysqli_query($conn, $sql);

    // 수정 후 view_post.php로 리디렉션
    header('Location: view_post.php?id=' . $post_id);
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>댓글 수정</title>
</head>
<body>
    <h1>댓글 수정</h1>
    <form method="post">
        <textarea name="new_comment_content" rows="5" cols="40"><?php echo $commentRow['content']; ?></textarea>
        <br>
        <input type="submit" name="edit_comment" value="댓글 수정 완료">
    </form>
</body>
</html>

