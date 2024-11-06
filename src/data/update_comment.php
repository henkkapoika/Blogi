<?php
require "dbconn.php";
date_default_timezone_set('Europe/Helsinki');

if (isset($_POST['comment_id']) && isset($_POST['comment'])) {
    $commentId = $_POST['comment_id'];
    $newComment = $_POST['comment'];
    $edited_at = date('Y-m-d H:i:s');

    $stmt = $mysqli->prepare("UPDATE comments SET comment = ?, edited_at = ? WHERE comment_id = ?");
    $stmt->bind_param("ssi", $newComment, $edited_at, $commentId);
    $stmt->execute();
    $stmt->close();

    echo "<div id='comment-" . htmlspecialchars($commentId) . "' class='comment'>";
    echo "<p><strong>" . htmlspecialchars($username) . "</strong></p>";
    echo "<p>" . htmlspecialchars($newComment) . "</p>";
    echo "<p>Posted at: " . htmlspecialchars($created_at) . "</p>";
    if ($edited_at) {
        echo "<p>Edited at: " . htmlspecialchars($edited_at) . "</p>";
    }
    echo "</div>";


    // if (!isset($_GET['blog_id']) && isset($_POST['blog_id'])) {
    //     $_GET['blog_id'] = $_POST['blog_id'];
    // }


    // include "data/comments.php";  

}
