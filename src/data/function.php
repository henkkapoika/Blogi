<?php
function generateBlogPost(){
    require "dbconn.php";

    $stmt = $mysqli->prepare("SELECT * FROM blogs WHERE blog_id = ?");
    $stmt->bind_param("s", $_GET['blog_id']);
    $stmt->execute();
    $result = $stmt->get_result();

    $blog = $result->fetch_assoc();

    $formattedContent = "<p>" . implode("</p><p>", array_filter(explode("\n", htmlspecialchars($blog['content'])))) . "</p>";

    echo "<div class='mainblog-posts'>";
    echo "<div class='blog-header' style='background-image: url(images/" . htmlspecialchars($blog['image_url']) . ");'>";
    //echo "<img class='blog-img content-wrapper-img' src='images/" . htmlspecialchars($blog['image_url']) . "'>";
    echo "<h1 class='blog-center'>" . htmlspecialchars($blog['title']) . "</h1>";
    echo "</div>";
    echo "<div class='blog-content content-wrapper'>";
    echo "<p class=''>" . $formattedContent . "</p>";
    echo "<p>" . htmlspecialchars($blog['created_at']) . "</p>";
    echo "</div>";
    echo "</div>";

    $stmt->close();
}

function generateUserBlogs(){
    require "dbconn.php";

    $query = "
        SELECT b.blog_id, b.title, b.created_at, b.image_url 
        FROM blogs b 
        JOIN users u ON b.user_id = u.user_id 
        WHERE u.username = ?
    ";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("s", $_SESSION['username']);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($blog = $result->fetch_assoc()) {
        echo "<div class='user-blog'>";
        echo "<a href='blog.php?blog_id=" . htmlspecialchars($blog['blog_id']) . "'>";
        echo "<img class='user-blog-img' src='images/" . htmlspecialchars($blog['image_url']) . "'>";
        echo "<h3>" . htmlspecialchars($blog['title']) . "</h3>";
        echo "<p>" . htmlspecialchars($blog['created_at']) . "</p>";
        echo "</a>";
        echo "<a href='#' hx-get='data/edit_entry.php?blog_id=" . htmlspecialchars($blog['blog_id']) . "' hx-target='#edit-modal' hx-trigger='click'>Edit</a>";
        echo "</div>";
    }

    $stmt->close();
}

?>
