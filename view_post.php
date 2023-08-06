<?php
session_start();

// 게시글 ID 확인
if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit();
}

$post_id = $_GET['id'];

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

// 게시글 정보 가져오기
$sql = "SELECT * FROM posts WHERE post_id = '$post_id'";
$result = mysqli_query($conn, $sql);

if ($result === false) {
    die("MySQL 쿼리 오류: " . mysqli_error($conn));
}

if (mysqli_num_rows($result) == 0) {
    header('Location: index.php');
    exit();
}

// 게시글 정보를 가져와서 $row 배열에 저장
$row = mysqli_fetch_assoc($result);

// 게시글 작성자와 로그인한 사용자 일치 여부 확인
if (isset($_SESSION['username']) && $row['author'] !== $_SESSION['username']) {
    header('Location: index.php');
    exit();
}

// 게시글 수정 처리
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit'])) {
    $newContent = $_POST['content'];

    // 데이터베이스에서 게시글 업데이트
    $sql = "UPDATE posts SET content = '$newContent' WHERE post_id = '$post_id'";
    mysqli_query($conn, $sql);

    echo "게시글이 수정되었습니다.";
}

// 게시글 삭제 처리
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    // 게시글 삭제 쿼리 실행
    $sql = "DELETE FROM posts WHERE post_id = '$post_id'";
    mysqli_query($conn, $sql);

    echo "게시글이 삭제되었습니다.";
    header('Location: index.php');
    exit();
}
// 댓글 작성 폼 제출 처리
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment'])) {
    $commentContent = $_POST['comment_content'];
    $commentAuthor = $_SESSION['username'];

    // 댓글 데이터베이스에 저장
    $sql = "INSERT INTO comments (post_id, content, author) VALUES ('$post_id', '$commentContent', '$commentAuthor')";
    mysqli_query($conn, $sql);

    // 댓글 저장 후 현재 페이지 리로드 (새로운 댓글이 표시되도록)
    header("Location: view_post.php?id=$post_id");
    exit();
}
// 댓글 조회
$sql = "SELECT * FROM comments WHERE post_id = '$post_id'";
$commentsResult = mysqli_query($conn, $sql);

// 댓글이 로그인한 사용자의 것인지 확인 (수정 및 삭제용)
function isCommentOwner($commentAuthor)
{
    return $commentAuthor === $_SESSION['username'];
}

// MySQL 연결 닫기
mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>게시글 본문보기</title>
</head>
<body>
    <h1>게시글 본문보기</h1>
    <p>제목: <?php echo $row['title']; ?></p>
    <p>내용: <?php echo $row['content']; ?></p>
    <?php if (!empty($row['file_path'])): ?>
        <p>첨부 파일: <a href="<?php echo $row['file_path']; ?>">다운로드</a></p>
    <?php endif; ?>
    <p>작성일: <?php echo $row['timestamp']; ?></p>

    <form method="post">
        <textarea name="content" rows="5" cols="40"><?php echo $row['content']; ?></textarea>
        <br>
        <input type="submit" name="edit" value="수정하기">
    </form>

    <form method="post">
        <input type="submit" name="delete" value="삭제하기">
    </form>

    <h2>댓글 작성</h2>
    <form method="post">
        <textarea name="comment_content" rows="3" cols="40" required></textarea>
        <br>
        <input type="submit" name="comment" value="댓글 작성">
    </form>

    <?php if (mysqli_num_rows($commentsResult) > 0): ?>
        <h2>댓글 목록</h2>
        <ul>
            <?php while ($commentRow = mysqli_fetch_assoc($commentsResult)): ?>
                <li>
                    작성자: <?php echo $commentRow['author']; ?><br>
                    내용: <?php echo $commentRow['content']; ?>
                    작성일: <?php echo $commentRow['timestamp']; ?>

<!-- 각 댓글에 수정 및 삭제 버튼 추가 -->
<?php if (isCommentOwner($commentRow['author'])): ?>
    <!-- 수정 버튼을 클릭하면 edit_comment.php로 이동하도록 JavaScript 사용 -->
    <form onsubmit="return false;" style="display: inline;">
        <input type="hidden" name="comment_id" value="<?php echo $commentRow['comment_id']; ?>">
        <input type="button" value="댓글 수정" onclick="goToEditCommentPage(<?php echo $commentRow['comment_id']; ?>);">
    </form>
    <form action="delete_comment.php?comment_id=<?php echo $commentRow['comment_id']; ?>" method="post" style="display: inline;">
        <input type="submit" name="delete_comment" value="댓글 삭제">
    </form>
<?php endif; ?>
<script>
    // 댓글 수정 폼으로 이동하는 함수
    function goToEditCommentPage(commentId) {
        // 현재 게시글의 ID를 가져와서 주소를 생성하고, 댓글 수정 페이지로 리디렉션
        var postId = <?php echo $post_id; ?>;
        var editCommentUrl = 'edit_comment.php?comment_id=' + commentId + '&post_id=' + postId;
        window.location.href = editCommentUrl;
    }
</script>

               </li>
            <?php endwhile; ?>
        </ul>
    <?php else: ?>
        <p>등록된 댓글이 없습니다.</p>
    <?php endif; ?>
</body>
</html>

