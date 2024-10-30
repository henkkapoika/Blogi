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
?>
