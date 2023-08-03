<?php
session_start();

// 게시글 ID 확인
if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit();
}

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
$post_id = $_GET['id'];
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
if ($row['author'] !== $_SESSION['username']) {
    header('Location: index.php');
    exit();
}

// 게시글 수정 또는 삭제 처리
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['edit'])) {
        // 수정 처리
        $newContent = $_POST['content'];
        $sql = "UPDATE posts SET content = '$newContent' WHERE post_id = '$post_id' AND password = '{$_POST['password']}'";
        mysqli_query($conn, $sql);
        
        echo "수정이 완료되었습니다.";
    } elseif (isset($_POST['delete'])) {
        // 삭제 처리
        $sql = "DELETE FROM posts WHERE post_id = '$post_id' AND password = '{$_POST['password']}'";
        mysqli_query($conn, $sql);
        
        echo "게시글이 삭제되었습니다.";
        header('Location: index.php');
        exit();
    }
}

// MySQL 연결 닫기
mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>게시글 수정/삭제</title>
</head>
<body>
    <h1>게시글 수정/삭제</h1>
    <form method="post">
        <textarea name="content" rows="5" cols="40"><?php echo

