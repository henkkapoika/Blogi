<?php
require 'dbconn.php';

$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$blogsPerPage = 5;
$offset = ($page - 1) * $blogsPerPage;


if ($user_id <= 0 || $page <= 0) {
    http_response_code(400);
    echo "<p class='error'>Invalid user ID!</p>";
    exit();
}

$stmt = $mysqli->prepare("SELECT blog_id, title, content, created_at FROM blogs WHERE user_id = ? ORDER BY created_at DESC LIMIT ? OFFSET ?");
if ($stmt) {
    $stmt->bind_param("iii", $user_id, $blogsPerPage, $offset);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($blog = $result->fetch_assoc()) {
            echo "<li class='user-blog-entry'>";
            echo "<h4><a href='blog.php?blog_id=" . intval($blog['blog_id']) . "'>" . htmlspecialchars($blog['title']) . "</a></h4>";
            echo "<p class='blog-date'>" . htmlspecialchars(date('F j, Y', strtotime($blog['created_at']))) . "</p>";
            echo "<p>" . nl2br(substr(htmlspecialchars($blog['content']), 0, 200)) . "...</p>";
            echo "<a href='blog.php?blog_id=" . intval($blog['blog_id']) . "'>Read More</a>";
            echo "</li>";
        }

        $nextOffset = $offset + $blogsPerPage;
        $stmtTotal = $mysqli->prepare("SELECT COUNT(*) AS total FROM blogs WHERE user_id = ?");
        if ($stmtTotal) {
            $stmtTotal->bind_param("i", $user_id);
            $stmtTotal->execute();
            $resultTotal = $stmtTotal->get_result();
            $totalBlogs = $resultTotal->fetch_assoc()['total'];
            $stmtTotal->close();

            if ($nextOffset >= $totalBlogs) {
                echo "<script>
                    // Hide the Load More button
                    var loadMoreBtn = document.getElementById('load-more-blogs');
                    if(loadMoreBtn){
                        loadMoreBtn.style.display = 'none';
                    }
                  </script>";
            }
        }
    } else {
        echo "<p>No more blog entries to load.</p>";
    }

    $stmt->close();
} else {
    http_response_code(500);
    echo "<p class='error'>Database query failed.</p>";
}
