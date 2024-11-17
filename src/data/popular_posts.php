<?php
require "dbconn.php";
require "function.php";

$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 5;

$stmt = $mysqli->prepare("
    SELECT blogs.*, users.username,
    (SELECT COUNT(*) FROM likes WHERE likes.blog_id = blogs.blog_id) as like_count,
    (SELECT COUNT(*) FROM comments WHERE comments.blog_id = blogs.blog_id) as comment_count
    FROM blogs
    INNER JOIN users ON blogs.user_id = users.user_id
    ORDER BY (like_count * 2 + comment_count) DESC
    LIMIT ?
");

$stmt->bind_param("i", $limit);

$stmt->execute();
$result = $stmt->get_result();

$blogs = [];
while ($blog = $result->fetch_assoc()) {
    $blogsId = intval($blog['blog_id']);
    $blog['like_count'] = intval($blog['like_count']);
    $blog['comment_count'] = intval($blog['comment_count']);
    $blog['popularity'] = ($blog['like_count'] * 2) + $blog['comment_count'];
    $blogs[] = $blog;
}
$stmt->close();


echo '<div class="carousel">';
echo '<div class="carousel-inner">';
foreach ($blogs as $blog) {
    echo generateCarouselItem($blog);
}
echo '</div>';
echo "
<button class=\"carousel-arrow left\" onclick=\"prevSlide()\">&#10094;</button>
<button class=\"carousel-arrow right\" onclick=\"nextSlide()\">&#10095;</button>
";

echo '</div>';

function generateCarouselItem($blog) {
    $blogId = intval($blog['blog_id']);
    $title = htmlspecialchars($blog['title']);
    $createdAt = htmlspecialchars($blog['created_at']);
    $username = htmlspecialchars($blog['username']);
    $imageUrl = htmlspecialchars($blog['image_url']);
    $likeCount = intval($blog['like_count']);
    $commentCount = intval($blog['comment_count']);

    return "
    <div class='carousel-item'>
        <a href='blog.php?blog_id={$blogId}'>
            <div class='image-container'>
            <img src='images/{$imageUrl}' alt='{$title}'>
            </div>
            <h3>{$title}</h3>
            <p>Posted on {$createdAt} | Likes: {$likeCount} | Comments: {$commentCount}</p>
            <p><strong>{$username}</strong></p>
        </a>
    </div>
    ";
}


?>