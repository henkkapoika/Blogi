<?php
require "dbconn.php";
date_default_timezone_set('Europe/Helsinki');

if (isset($_POST['comment_id']) && isset($_POST['comment']) && isset($_POST['blog_id'])) {
    $commentId = $_POST['comment_id'];
    $newComment = $_POST['comment'];
    $blogId = $_POST['blog_id'];
    $edited_at = date('Y-m-d H:i:s');

    $stmt = $mysqli->prepare("UPDATE comments SET comment = ?, edited_at = ? WHERE comment_id = ?");
    $stmt->bind_param("ssi", $newComment, $edited_at, $commentId);
    $stmt->execute();
    $stmt->close();

    $stmt = $mysqli->prepare("SELECT c.comment, c.created_at, c.edited_at, u.username FROM comments c JOIN users u ON c.user_id = u.user_id WHERE c.comment_id = ?");
    $stmt->bind_param("i", $commentId);
    $stmt->execute();
    $result = $stmt->get_result();
    $updatedComment = $result->fetch_assoc();
    $stmt->close();


    echo "<div id='comment-" . htmlspecialchars($commentId) . "' class='comment'>";
    echo "<p><strong>" . htmlspecialchars($updatedComment['username']) . "</strong></p>";
    echo "<p>" . htmlspecialchars($updatedComment['comment']) . "</p>";
    echo "<p>Posted at: " . htmlspecialchars($updatedComment['created_at']) . "</p>";
    if ($updatedComment['edited_at']) {
        echo "<p>Edited at: " . htmlspecialchars($updatedComment['edited_at']) . "</p>";
    }
    echo "<a href='#' hx-get='data/edit_comment.php?comment_id=" . htmlspecialchars($commentId) . "&blog_id=" . htmlspecialchars($blogId) . "' hx-target='#comment-" . htmlspecialchars($commentId) . "' hx-swap='outerHTML'>Edit</a>";
    echo "</div>";


    if (!isset($_GET['blog_id']) && isset($_POST['blog_id'])) {
        $_GET['blog_id'] = $_POST['blog_id'];
    }


}
