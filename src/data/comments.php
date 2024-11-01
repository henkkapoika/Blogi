<?php
require "dbconn.php";

if (isset($_GET['blog_id'])) {
    $stmt = $mysqli->prepare("SELECT c.comment, c.created_at, u.username FROM comments c JOIN users u ON c.user_id = u.user_id WHERE c.blog_id = ? ORDER BY c.created_at DESC");
    $stmt->bind_param("s", $_GET['blog_id']);
    $stmt->execute();
    $result = $stmt->get_result();

while ($comment = $result->fetch_assoc()) {
    echo "<div class='comment'>";
    echo "<p><strong>" . htmlspecialchars($comment['username']) . "</strong></p>";
    echo "<p>Posted: " . htmlspecialchars($comment['comment']) . "</p>";
    echo "<p>Posted at: " . htmlspecialchars($comment['created_at']) . "</p>";
    echo "</div>";
}

$stmt->close();

} else {
    echo "<p>No comments found</p>";
}

?>