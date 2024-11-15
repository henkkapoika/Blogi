<?php
session_start();
require "dbconn.php";

if($_SERVER['REQUEST_METHOD'] === 'POST'){

    if (!isset($_POST['blog_id'])) {
        die("No blog ID provided.");
    }
    
    $blogId = htmlspecialchars($_POST['blog_id']);
    
    $stmt = $mysqli->prepare("SELECT user_id, image_url FROM blogs WHERE blog_id = ?");
    $stmt->bind_param("i", $blogId);
    $stmt->execute();
    $result = $stmt->get_result();
    $blog = $result->fetch_assoc();
    $stmt->close();

    if($blog && $blog['user_id'] === $_SESSION['user_id']){
        $stmt = $mysqli->prepare("DELETE FROM blogs WHERE blog_id = ?");
        $stmt->bind_param("i", $blogId);
        $stmt->execute();
        $stmt->close();

        // Delete image file
        $imageFile = "../images/" . $blog['image_url'];
        if($imageFile && file_exists($imageFile)){
            unlink($imageFile);
        }

        echo '';

    } else {
        http_response_code(403);
        echo "<div class='error'>You do not have permission to delete this blog post.</div>";
    }
} else {
    http_response_code(405);
    echo "<div class='error'>Method Not Allowed</div>";
}

?>