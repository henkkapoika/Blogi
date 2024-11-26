<?php
require 'dbconn.php';
require 'post.php';

if(isset($_POST['search'])){
    $search = trim($_POST['search']);
    
    if($search === ''){
        http_response_code(400);
        echo "<div id='search-results'</div>";
        return;
    }

    $stmt = $mysqli->prepare("SELECT b.blog_id, b.title, b.content, b.created_at, u.username, 
    MATCH(b.title, b.content) AGAINST(?) AS relevance_title, MATCH(u.username) AGAINST(?) AS relevance_username FROM blogs b JOIN users u ON b.user_id = u.user_id 
    WHERE MATCH(b.title, b.content) AGAINST(? IN NATURAL LANGUAGE MODE) 
    OR MATCH(u.username) AGAINST(? IN NATURAL LANGUAGE MODE)
    OR u.username LIKE CONCAT('%', ?, '%') ORDER BY (relevance_title + relevance_username) DESC LIMIT 10");

    if($stmt){
        $stmt->bind_param("sssss", $search, $search, $search, $search, $search);
        $stmt->execute();
        $result = $stmt->get_result();

        $output = "<div id='search-results'>";
        if($result->num_rows > 0){
            while($blog = $result->fetch_assoc()){
                $output .= "<div class='search-result'>";
                $output .= "<h4><a href='blog.php?blog_id=" . intval($blog['blog_id']) . "'>" . htmlspecialchars($blog['title']) . "</a></h4>";
                $output .= "<p class='blog-date'>By " . htmlspecialchars($blog['username']) . " on " . htmlspecialchars(date('F j, Y', strtotime($blog['created_at']))) . "</p>";
                //$output .= "<p class='blog-date'>" . htmlspecialchars(date('F j, Y', strtotime($blog['created_at']))) . "</p>";
                $output .= "<p>" . nl2br(substr(htmlspecialchars($blog['content']), 0, 200)) . "...</p>";
                //$output .= "<a href='blog.php?blog_id=" . intval($blog['blog_id']) . "'>Read More</a>";
                $output .= "</div>";
            }
        } else {
            $output .= "<p>No results found.</p>";
        }
        $output .= "</div>";
        echo $output;

        $stmt->close();
    } else {
        http_response_code(500);
        echo "<p class='error'>Database query failed.</p>";
    }

} else {
    http_response_code(400);
    echo "<p class='error'>Invalid search query.</p>";
}



?>