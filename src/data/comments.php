<?php
require "dbconn.php";
session_start();


if (isset($_GET['blog_id'])) {
    $blogId = intval($_GET['blog_id']);
    $stmt = $mysqli->prepare("SELECT c.comment, c.created_at, c.edited_at, u.username, u.profile_picture, c.comment_id FROM comments c JOIN users u ON c.user_id = u.user_id WHERE c.blog_id = ? ORDER BY c.created_at DESC");
    $stmt->bind_param("i", $blogId);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($comment = $result->fetch_assoc()) {
        $profilePicture = $comment['profile_picture'];

        echo "<div class='comment-wrapper' id='comment-wrapper-" . trim(htmlspecialchars($comment['comment_id'])) . "'>";
        echo "<div id='comment-" . intval($comment['comment_id']) . "' class='comment'>";
        echo "<div class='comment-header'>";
        echo "<img src='" . htmlspecialchars($profilePicture) . "' alt='Profile Picture' class='comment-profile-picture'>";
        echo "<p class='username'>
            <a href='#' 
               hx-get='data/user_details.php?username=" . urlencode($comment['username']) . "' 
               hx-target='#modal-body'
               hx-trigger='click' 
               hx-swap='innerHTML'
               data-username='" . htmlspecialchars($comment['username']) . "'>
               <strong>" . htmlspecialchars($comment['username']) . "</strong>
            </a>
          </p>";
        //echo "<p class='username'><strong>" . htmlspecialchars($comment['username']) . "</strong></p>";
        echo "<p>" . htmlspecialchars($comment['created_at']) . "</p>";
        echo "</div>";
        echo "<p>" . htmlspecialchars($comment['comment']) . "</p>";


        if (!empty($comment['edited_at'])) {
            echo "<p class='edited_at'>Edited at: " . htmlspecialchars($comment['edited_at']) . "</p>";
        }

        if (isset($_SESSION['username']) && $_SESSION['username'] === $comment['username']) {
            echo "<a href='#' hx-get='data/edit_comment.php?comment_id=" . intval($comment['comment_id']) . "&blog_id=" . htmlspecialchars($blogId) . "' 
            hx-target='#comment-" . intval($comment['comment_id']) . "'  
            hx-swap='outerHTML'
            hx-ext='debug'>Edit</a>";
        }

        echo "</div>";
        echo "</div>";
    }
} else {
    echo "<p>No comments found</p>";
}

$stmt->close();
