<?php
function generateBlogPosts(){
require "dbconn.php";

$stmt = $mysqli->prepare("SELECT * FROM blogs");
$stmt->execute();
$result = $stmt->get_result();


    while ($post = $result->fetch_assoc()) {
        $userId = intval($post['user_id']);
        $blogId = intval($post['blog_id']);
        
        $stmt2 = $mysqli->prepare("SELECT username FROM users WHERE user_id = ?");
        $stmt2->bind_param("s", $userId);
        $stmt2->execute();
        $result2 = $stmt2->get_result();

        $user = $result2->fetch_assoc();

        echo "<a href='blog.php?blog_id=" . $blogId . "' class='post-link'>";
        echo "<ul class='main-post'>";
        echo "<div class='blog-image-container'>";
        echo "<li><img class='post-img' src='images/" . htmlspecialchars($post['image_url']) . "'></li>";
        echo "</div>";
        echo "<li class='title'>" . htmlspecialchars($post['title']) . "</li>";
        echo "<li><strong>" . htmlspecialchars($user['username']) . "</strong></li>";
        echo "<p> ". htmlspecialchars(date('F j, Y', strtotime($post['created_at']))) ." </p>";

        echo "</ul>";
        echo "</a>";

        $stmt2->close();
    }
$stmt->close();
}
?>






