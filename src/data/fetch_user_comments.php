<?php
require 'dbconn.php';

if(isset($_GET['user_id']) && isset($_GET['page'])){
    $userId = intval($_GET['user_id']);
    $page = intval($_GET['page']);
    $limit = 10;
    $offset = ($page - 1) * $limit;

    $stmt = $mysqli->prepare("SELECT c.comment_id, c.blog_id, c.comment, c.created_at, b.title
                FROM comments c
                JOIN blogs b ON c.blog_id = b.blog_id
                WHERE c.user_id = ?
                ORDER BY c.created_at DESC
                LIMIT ? OFFSET ?");
    $stmt->bind_param("iii", $userId, $limit, $offset);

    if($stmt){
        $stmt->bind_param("iii", $userId, $limit, $offset);

        if($stmt->execute()){
            $result = $stmt->get_result();
            if($result->num_rows > 0){
                while($comment = $result->fetch_assoc()){
                    echo "<div class='usercomment'>";

                    if(!empty($comment['title'])) {
                        echo "<p>Comment on: <a href='blog.php?blog_id=" . intval($comment['blog_id']) . "'>" . htmlspecialchars($comment['title']) . "</a></p>";
                    } else {
                        echo "<p>Comment on a blog that has been deleted.</p>";
                    }

                    echo "<p>" . nl2br(htmlspecialchars($comment['comment'])) . "</p>";

                    echo "<p class='comment-date'>On " . htmlspecialchars(date('F j, Y', strtotime($comment['created_at']))) . "</p>";
                    echo "</div>";
                }
            } else {
                echo "<div id='no-more-comments' style='display: none;'></div>";
            }
        } else {
            http_response_code(500);
            echo "<p class='error'>Database query failed.</p>";
        }

        $stmt->close();
    } else {
        http_response_code(500);
        echo "<p class='error'>Failed to prepare the database query.</p>";
    }
} else {
    http_response_code(400);
    echo "<p class='error'>Invalid user ID or page number!</p>";
    exit();
}
?>