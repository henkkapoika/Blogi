<?php
require "dbconn.php";
date_default_timezone_set('Europe/Helsinki');

if (isset($_POST['blog_id']) && isset($_POST['username']) && isset($_POST['comment'])) {
    $blogId = $_POST['blog_id'];
    $username = $_POST['username'];
    $comment = $_POST['comment'];
    $timestamp = date('Y-m-d H:i:s');

    $stmt = $mysqli->prepare("INSERT INTO comments (blog_id, user_id, comment, created_at) VALUES (?, (SELECT user_id FROM users WHERE username = ?), ?, ?)");
    $stmt->bind_param("ssss", $blogId, $username, $comment, $timestamp);
    $stmt->execute();
    $stmt->close();

    $_GET['blog_id'] = $blogId; 
    include "comments.php";
}




//require "comments.php";
?>