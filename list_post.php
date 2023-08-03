<?php
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

// 게시글 검색 처리
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $searchKeyword = $_POST['search_keyword'];

    // 게시글 검색 쿼리
    $sql = "SELECT * FROM posts WHERE title LIKE '%$searchKeyword%' OR content LIKE '%$searchKeyword%'";
} else {
    // 정렬 방식 처리
    $order = "timestamp DESC"; // 기본 정렬 방식: 최신 작성일 순
    if (isset($_GET['order'])) {
        if ($_GET['order'] === 'oldest') {
            $order = "timestamp ASC"; // 오래된 작성일 순
        } elseif ($_GET['order'] === 'title') {
            $order = "title ASC"; // 제목 가나다 순
        }
    }
    
    // 일반 게시글 목록 가져오기
    $sql = "SELECT * FROM posts ORDER BY $order";
}

$result = mysqli_query($conn, $sql);

if ($result === false) {
    die("MySQL 쿼리 오류: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>게시글 목록</title>
</head>
<body>
    <h1>게시글 목록</h1>

    <form method="post">
        <label for="search_keyword">검색어:</label>
        <input type="text" name="search_keyword" id="search_keyword">
        <input type="submit" value="검색">
    </form>

    <p>정렬 방식:
        <a href="?order=newest">최신 작성일</a> |
        <a href="?order=oldest">오래된 작성일</a> |
        <a href="?order=title">가나다</a>
    </p>

    <table>
        <tr>
            <th>제목</th>
            <th>작성자</th>
            <th>작성일</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)) : ?>
        <tr>
            <td><a href="view_post.php?id=<?php echo $row['post_id']; ?>"><?php echo $row['title']; ?></a></td>
            <td><?php echo $row['author']; ?></td>
            <td><?php echo $row['timestamp']; ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>

<?php
// MySQL 연결 닫기
mysqli_close($conn);
?>

