<?php
require "dbconn.php";
require "function.php";
date_default_timezone_set('Europe/Helsinki');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['blog_id']) && isset($_POST['title']) && isset($_POST['content'])) {
    $blogId = htmlspecialchars($_POST['blog_id']);
    $title = htmlspecialchars($_POST['title']);
    $content = htmlspecialchars($_POST['content']);
    $updated_at = date("Y-m-d H:i:s");

    $updatedQuery = "UPDATE blogs SET title = ?, content = ?, updated_at = ?";
    $params = [$title, $content, $updated_at];
    $types = "sss";

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
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


    $stmt = $mysqli->prepare("SELECT * FROM blogs WHERE blog_id = ?");
    $stmt->bind_param("i", $blogId);
    $stmt->execute();
    $result = $stmt->get_result();
    $blog = $result->fetch_assoc();
    $stmt->close();

    echo generateUserBlogHtml($blog, true);

    echo '
<script>
    document.getElementById("edit-modal").style.display = "none";
</script>
';
}
