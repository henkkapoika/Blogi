<?php
require 'dbconn.php';

if (isset($_GET['user_id']) && isset($_GET['page'])) {
    $userId = intval($_GET['user_id']);
    $page = intval($_GET['page']);
    $limit = 10;
    $offset = ($page - 1) * $limit;

    $stmt = $mysqli->prepare("SELECT l.like_id, l.blog_id, b.title, l.created_at 
            FROM likes l 
            JOIN blogs b ON l.blog_id = b.blog_id 
            WHERE l.user_id = ? 
            ORDER BY l.created_at DESC 
            LIMIT ? 
            OFFSET ?");
    $stmt->bind_param("iii", $userId, $limit, $offset);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            while ($like = $result->fetch_assoc()) {
                echo "<div class='like'>";
                if ($like['title']) {
                    echo "<p>Liked: <a href='blog.php?blog_id=" . intval($like['blog_id']) . "'>" . htmlspecialchars($like['title']) . "</a></p>";
                } else {
                    echo "<p>Liked a blog that has been deleted.</p>";
                }
                echo "<p class='like-date'>On " . htmlspecialchars(date('F j, Y', strtotime($like['created_at']))) . "</p>";
                echo "</div>";
            }
        } else {
            echo "<p>No more likes to load.</p>";
        }
    } else {
        http_response_code(500);
        echo "<p class='error'>Database query failed.</p>";
    }
} else {
    http_response_code(400);
    echo "<p class='error'>Invalid user ID or page number!</p>";
    exit();
}
