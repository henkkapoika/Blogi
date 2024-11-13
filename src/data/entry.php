<?php
session_start();
require "dbconn.php";
date_default_timezone_set('Europe/Helsinki');

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['title']) && isset($_POST['content']) && isset($_FILES['file'])) {
    $title = htmlspecialchars($_POST['title']);
    $content = htmlspecialchars($_POST['content']);
    $file = $_FILES['file'];
    $userId = $_SESSION['user_id'];
    $created_at = date("Y-m-d H:i:s");

    if($file['error'] !== 0) {
        echo "<div class='error'>There was an error uploading your file. Please try again.</div>";
        exit();
    } else if($file['size'] > 2000000) { // Noin 2MB
        echo "<div class='error'>File is too large. Please try again.</div>";
        exit();
    } else if($file['type'] !== 'image/jpeg' && $file['type'] !== 'image/png') {
        echo "<div class='error'>File type not supported. Please try again.</div>";
        exit();
    }

    if(!empty($title) && !empty($content)) {
        $stmt = $mysqli->prepare("INSERT INTO blogs (user_id, title, content, image_url, created_at) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $userId, $title, $content, $file['name'], $created_at);
        $stmt->execute();
        $stmt->close();

        move_uploaded_file($file['tmp_name'], "../images/" . $file['name']);

        echo "<div class='success'>Your blog has been published!</div>";
    } else {
        echo "<div class='error'>Please fill out all fields.</div>";
        exit();
    }
    
}


?>