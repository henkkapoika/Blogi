<?php
session_start();
date_default_timezone_set('Europe/Helsinki');
require 'dbconn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        exit('Unauthorized access.');
    }

    $userId = $_SESSION['user_id'];
    $title = isset($_POST['title']) ? trim($_POST['title']) : '';
    $content = isset($_POST['content']) ? trim($_POST['content']) : '';
    $created_at = date('Y-m-d H:i:s');

    if (empty($title) || empty($content)) {
        http_response_code(400);
        exit('Please fill out all fields.');
    }

    $fileName = null;

    if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
        if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['image']['tmp_name'];
            $originalFileName = basename($_FILES['image']['name']);
            $fileSize = $_FILES['image']['size'];
            $fileType = mime_content_type($fileTmpPath);
            $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];

            // Noin 4mb
            if ($fileSize > 4000000) {
                http_response_code(400);
                exit('File is too large. Please try again.');
            }

            if (!in_array($fileType, $allowedTypes)) {
                http_response_code(400);
                exit('File type not supported. Please try again.');
            }

            $fileExtension = strtolower(pathinfo($originalFileName, PATHINFO_EXTENSION));
            $newFileName = md5(time() . $originalFileName) . '.' . $fileExtension;

            $uploadDir = '../images/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $destPath = $uploadDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $destPath)) {
                $fileName = $newFileName;
            } else {
                http_response_code(500);
                exit('There was an error uploading your file. Please try again.');
            }
        } else {
            http_response_code(400);
            exit('File upload error.');
        }
    }

    $stmt = $mysqli->prepare("INSERT INTO blogs (user_id, title, content, image_url, created_at) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $userId, $title, $content, $fileName, $created_at);

    if ($stmt->execute()) {
        $newBlogId = $stmt->insert_id;
        $stmt->close();
        header("HX-Redirect: ../blog.php?blog_id={$newBlogId}");
        exit();
    } else {
        http_response_code(500);
        exit('Database insert error.');
    }
}
?>