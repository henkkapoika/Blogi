<?php
require "dbconn.php";
session_start();
date_default_timezone_set('Europe/Helsinki');

if (isset($_POST['blog_id']) && isset($_POST['username']) && isset($_POST['comment'])) {
    $blogId = intval($_POST['blog_id']);
    $username = trim($_POST['username']);
    $comment = trim($_POST['comment']);
    $timestamp = date('Y-m-d H:i:s');

    $stmt = $mysqli->prepare("INSERT INTO comments (blog_id, user_id, comment, created_at) VALUES (?, (SELECT user_id FROM users WHERE username = ?), ?, ?)");
    $stmt->bind_param("isss", $blogId, $username, $comment, $timestamp);
    $stmt->execute();
    $newCommentId = $stmt->insert_id;
    $stmt->close();

    $stmtFetch = $mysqli->prepare("
        SELECT c.comment_id, c.comment, c.created_at, c.edited_at, u.username, u.profile_picture
        FROM comments c
        JOIN users u ON c.user_id = u.user_id
        WHERE c.comment_id = ?
    ");

    $stmtFetch->bind_param("i", $newCommentId);
    $stmtFetch->execute();
    $result = $stmtFetch->get_result();
    $newComment = $result->fetch_assoc();
    $stmtFetch->close();

    $profilePicture = !empty($newComment['profile_picture']) ? $newComment['profile_picture'] : 'uploads/default.png';


    echo "<div class='comment-wrapper new-comment' id='comment-wrapper-" . trim(htmlspecialchars($newComment['comment_id'])) . "'>";
    echo "<div id='comment-" . htmlspecialchars($newComment['comment_id']) . "' class='comment'>";
    echo "<div class='comment-header'>";
    echo "<img src='" . htmlspecialchars($profilePicture) . "' alt='Profile Picture' class='comment-profile-picture'>";
    echo "<p class='username'>
            <a href='#' 
               hx-get='data/user_details.php?username=" . urlencode($newComment['username']) . "' 
               hx-target='#modal-body'
               hx-trigger='click' 
               hx-swap='innerHTML'
               data-username='" . htmlspecialchars($newComment['username']) . "'>
               <strong>" . htmlspecialchars($newComment['username']) . "</strong>
            </a>
          </p>";
    //echo "<p class='username'><strong>" . htmlspecialchars($newComment['username']) . "</strong></p>";
    echo "<p>" . htmlspecialchars($newComment['created_at']) . "</p>";
    echo "</div>";
    echo "<p>" . nl2br(htmlspecialchars($newComment['comment'])) . "</p>";

    if (!empty($newComment['edited_at'])) {
        echo "<p class='edited_at'>Edited at: " . htmlspecialchars($newComment['edited_at']) . "</p>";
    }

    if (isset($_SESSION['username']) && $_SESSION['username'] === $newComment['username']) {
        echo "<a href='#' 
                  hx-get='data/edit_comment.php?comment_id=" . htmlspecialchars($newComment['comment_id']) . "&blog_id=" . htmlspecialchars($blogId) . "' 
                  hx-target='#comment-" . htmlspecialchars($newComment['comment_id']) . "'  
                  hx-swap='outerHTML'>Edit</a>";
    }

    echo "</div>";
    echo "</div>";


    //$_GET['blog_id'] = $blogId; 
    //include "comments.php";
    
}
?>




