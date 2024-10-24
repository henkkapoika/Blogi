<?php
function generateBlogPosts(){
require "dbconn.php";

$stmt = $mysqli->prepare("SELECT * FROM blogs");
$stmt->execute();
$result = $stmt->get_result();


    while ($post = $result->fetch_assoc()) {
        echo "<ul class='main-post'>";
        $userId = $post['user_id'];

        $stmt2 = $mysqli->prepare("SELECT username FROM users WHERE user_id = ?");
        $stmt2->bind_param("s", $userId);
        $stmt2->execute();
        $result2 = $stmt2->get_result();

        $user = $result2->fetch_assoc();

        echo "<li><img class='post-img' src='images/" . htmlspecialchars($post['image_url']) . "'></li>";
        echo "<li>" . htmlspecialchars($post['title']) . "</li>";
        echo "<li>" . htmlspecialchars($user['username']) . "</li>";
        echo "<li>" . htmlspecialchars($post['created_at']) . "</li>";

        echo "</ul>";

        $stmt2->close();
    }



$stmt->close();

}
?>






