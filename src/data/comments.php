<?php
require "dbconn.php";

if (isset($_GET['blog_id'])) {
    $blogId = $_GET['blog_id'];
    $stmt = $mysqli->prepare("SELECT c.comment, c.created_at, c.edited_at, u.username, c.comment_id FROM comments c JOIN users u ON c.user_id = u.user_id WHERE c.blog_id = ? ORDER BY c.created_at DESC");
    $stmt->bind_param("s", $blogId);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($comment = $result->fetch_assoc()) {
        echo "<div id='comment-" . htmlspecialchars($comment['comment_id']) . "' class='comment'>";
        echo "<p><strong>" . htmlspecialchars($comment['username']) . "</strong></p>";
        echo "<p>Posted: " . htmlspecialchars($comment['comment']) . "</p>";
        echo "<p>Posted at: " . htmlspecialchars($comment['created_at']) . "</p>";

        if ($comment['edited_at']) {
            echo "<p>Edited at: " . htmlspecialchars($comment['edited_at']) . "</p>";
        }

        //if (isset($_SESSION['username']) && $_SESSION['username'] === $comment['username']) {
            echo "<a href='#' hx-get='data/edit_comment.php?comment_id=" . htmlspecialchars($comment['comment_id']) . "&blog_id=" . htmlspecialchars($blogId) . "' hx-target='#comment-" . htmlspecialchars($comment['comment_id']) . "' hx-swap='outerHTML'>Edit</a>";
        //}

        echo "</div>";
    }

    $stmt->close();
} else {
    echo "<p>No comments found</p>";
}
?>