<?php
require "dbconn.php";
date_default_timezone_set('Europe/Helsinki');

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['blog_id']) && isset($_POST['title']) && isset($_POST['content'])) {
    $blogId = htmlspecialchars($_POST['blog_id']);
    $title = htmlspecialchars($_POST['title']);
    $content = htmlspecialchars($_POST['content']);
    $updated_at = date("Y-m-d H:i:s");

    $updatedQuery = "UPDATE blogs SET title = ?, content = ?, updated_at = ?";
    $params = [$title, $content, $updated_at];
    $types = "sss";

    if(isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $imgUrl = $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], "../images/" . $imgUrl);
        $updatedQuery .= ", image_url = ?";
        $types .= "s";
        $params[] = $imgUrl;
    }

    $updatedQuery .= " WHERE blog_id = ?";
    $types .= "s";
    $params[] = $blogId;

    $stmt = $mysqli->prepare($updatedQuery);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $stmt->close();

    echo "<div class='success'>Blog updated successfully! Click anywhere on the page to continue.</div>";
}


?>