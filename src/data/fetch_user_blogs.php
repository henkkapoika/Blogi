<?php
require 'dbconn.php';

$userId = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$blogsPerPage = 5;
$offset = ($page - 1) * $blogsPerPage;


if ($userId <= 0) {
    echo "<p class='error'>Invalid user ID!</p>";
    exit();
}

$stmt = $mysqli->prepare("SELECT blog_id, title, content, created_at FROM blogs WHERE user_id = ? ORDER BY created_at DESC LIMIT ? OFFSET ?");
$stmt->bind_param("iii", $userId, $blogsPerPage, $offset);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "<ul class='user-blogs-list'>";
    while ($blog = $result->fetch_assoc()) {
        echo "<li class='user-blog-entry'>";
        echo "<h4><a href='blog.php?blog_id=" . intval($blog['blog_id']) . "'>" . htmlspecialchars($blog['title']) . "</a></h4>";
        echo "<p class='blog-date'>" . htmlspecialchars(date('F j, Y', strtotime($blog['created_at']))) . "</p>";
        echo "<p>" . nl2br(substr(htmlspecialchars($blog['content']), 0, 200)) . "...</p>";
        echo "<a href='blog.php?blog_id=" . intval($blog['blog_id']) . "'>Read More</a>";
        echo "</li>";
    }
    echo "</ul>";
} else {
    echo "<p>No more blog entries to display.</p>";

    echo "<script>document.getElementById('load-more-blogs').style.display = 'none';</script>";
}

$stmt->close();


?>