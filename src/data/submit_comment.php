<?php
require "dbconn.php";

$blogId = $_POST['blog_id'];
$username = $_POST['username'];
$comment = $_POST['comment'];

$stmt = $mysqli->prepare("INSERT INTO comments (blog_id, user_id, comment) VALUES (?, (SELECT user_id FROM users WHERE username = ?), ?)");
$stmt->bind_param("sss", $blogId, $username, $comment);
$stmt->execute();
$stmt->close();


require "comments.php";
?>