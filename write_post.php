<?php
session_start();

// 로그인 확인
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

// 게시글 작성 처리
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $password = $_POST['password'];

    // 파일 업로드 처리
    if (!empty($_FILES["file"]["name"])) {
        $uploadDir = "uploads/";
        $fileName = basename($_FILES["file"]["name"]);
        $targetFilePath = $uploadDir . $fileName;
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

        // 허용되는 파일 형식 (여기서는 png, jpg, jpeg, txt 파일만 허용하도록 하였습니다)
        $allowedTypes = array('png', 'jpg', 'jpeg', 'txt');

        if (!in_array($fileType, $allowedTypes)) {
            echo "허용되지 않는 파일 형식입니다.";
            exit();
        }

        if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)) {
            echo "파일이 업로드되었습니다.";
        } else {
            echo "파일 업로드에 실패했습니다.";
            exit();
        }
    } else {
        // 파일이 첨부되지 않은 경우, 빈 문자열로 처리합니다.
        $targetFilePath = "";
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

    // 게시글 저장
    $sql = "INSERT INTO posts (title, content, author, password, file_path) VALUES ('$title', '$content', '{$_SESSION['username']}', '$password', '$targetFilePath')";
    mysqli_query($conn, $sql);

    mysqli_close($conn);

    echo "게시글이 작성되었습니다.";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>게시글 작성하기</title>
</head>
<body>
    <h1>게시글 작성하기</h1>
    <form method="post" enctype="multipart/form-data">
        <label for="title">제목:</label>
        <input type="text" name="title" id="title" required>
        <br>
        <label for="content">내용:</label>
        <textarea name="content" id="content" rows="5" cols="40" required></textarea>
        <br>
        <label for="password">비밀번호:</label>
        <input type="password" name="password" id="password" required>
        <br>
        <label for="file">첨부 파일:</label>
        <input type="file" name="file" id="file">
        <br>
        <input type="submit" value="작성 완료">
    </form>
</body>
</html>

