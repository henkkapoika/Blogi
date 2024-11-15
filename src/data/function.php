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

function generateUserBlogHtml($blog, $includeSwapOob = false) {
    $blogId = htmlspecialchars($blog['blog_id']);
    $title = htmlspecialchars($blog['title']);
    $createdAt = htmlspecialchars($blog['created_at']);
    $imageUrl = htmlspecialchars($blog['image_url']);
    $swapOobAttribute = $includeSwapOob ? ' hx-swap-oob="outerHTML"' : '';

    return '
    <div id="blog-post-' . $blogId . '" class="user-blog"' . $swapOobAttribute . '>
        <a href="blog.php?blog_id=' . $blogId . '">
            <img class="user-blog-img" src="images/' . $imageUrl . '">
            <h3>' . $title . '</h3>
            <p>' . $createdAt . '</p>
        </a>
        <button class="edit-button"
            hx-get="data/edit_entry.php?blog_id=' . $blogId . '"
            hx-target="#edit-modal"
            hx-swap="innerHTML">Edit</button>
        <input type="hidden" name="blog_id" value="' . $blogId . '">
        <button class="delete-button"
            hx-post="data/delete_entry.php"
            hx-include="closest div"
            hx-confirm="Are you sure you want to delete this blog post?"
            hx-target="#blog-post-' . $blogId . '"
            hx-swap="outerHTML">Delete</button>
    </div>
    ';
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
        echo generateUserBlogHtml($blog);
    }

    $stmt->close();
}

?>
