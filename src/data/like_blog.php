<?php
session_start();
require 'dbconn.php';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['user_id'], $_POST['blog_id'])) {
        http_response_code(403);
        echo "Unauthorized action.";
        exit();
    }

    $blogId = $_POST['blog_id'];
    $userId = $_SESSION['user_id'];

    $stmt = $mysqli->prepare("SELECT like_id FROM likes WHERE blog_id = ? AND user_id = ?");
    $stmt->bind_param("ii", $blogId, $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if (!$result->fetch_assoc()) {
        $stmt = $mysqli->prepare("INSERT INTO likes (blog_id, user_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $blogId, $userId);
        $stmt->execute();
    }

    $stmt = $mysqli->prepare("SELECT COUNT(*) as like_count FROM likes WHERE blog_id = ?");
    $stmt->bind_param("i", $blogId);
    $stmt->execute();
    $result = $stmt->get_result();
    $like = $result->fetch_assoc();
    $likeCount = $like['like_count'];
    $stmt->close();

    echo '<span id="like-count-' . $blogId . '">' . $likeCount . ' Likes</span>';
}
else {
    http_response_code(400);
    echo "Invalid request.";
}


?>