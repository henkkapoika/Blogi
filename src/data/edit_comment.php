<?php
require "dbconn.php";


if (isset($_GET['comment_id'])) {
    $commentId = $_GET['comment_id'];

    $stmt = $mysqli->prepare("SELECT comment, blog_id, user_id FROM comments WHERE comment_id = ?");
    $stmt->bind_param("s", $commentId);
    $stmt->execute();
    $result = $stmt->get_result();
    $comment = $result->fetch_assoc();

    if ($comment) {
        echo "<div class='comment-edit'>";
        echo "<form hx-post='data/update_comment.php' hx-target='#comment-wrapper-" . trim(htmlspecialchars($commentId)) . "' hx-swap='outerHTML'>";
        echo "<input type='hidden' name='comment_id' value='" . htmlspecialchars($commentId) . "'>";
        echo "<input type='hidden' name='user_id' value='" . htmlspecialchars($comment['user_id']) . "'>"; 
        echo "<input type='hidden' name='blog_id' value='" . htmlspecialchars($comment['blog_id']) . "'>";
        echo "<textarea name='comment'>" . htmlspecialchars($comment['comment']) . "</textarea>";
        echo "<button class='comment-edit-btn' type='submit'>Save</button>";
        echo "</form>";
        echo "</div>";
    } else {
        echo "<p>No comment found</p>";
    }

    $stmt->close();
}
