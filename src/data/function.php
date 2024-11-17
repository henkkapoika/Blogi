<?php

function getPopularityScore($blogId){
    require "dbconn.php";
    
    $blogId = intval($blogId);

    $likes = getLikeCount($blogId);

    $comments = getCommentCount($blogId);

    $popularity = ($likes * 2) + $comments;
    return $popularity;
}

function getCommentCount($blogId){
    require "dbconn.php";
    
    $query = "SELECT COUNT(*) as comment_count FROM comments WHERE blog_id = ?";

    if (!$stmt = $mysqli->prepare($query)) {
        error_log("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error);
        return 0;
    }

    $stmt->bind_param("i", $blogId);

    if (!$stmt->execute()) {
        error_log("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
        $stmt->close();
        return 0;
    }

    $result = $stmt->get_result();
    $commentData = $result->fetch_assoc();
    $stmt->close();
    return isset($commentData['comment_count']) ? intval($commentData['comment_count']) : 0;
}


function getLikeCount($blogId){
    require "dbconn.php";

    if (!$stmt = $mysqli->prepare("SELECT COUNT(*) as like_count FROM likes WHERE blog_id = ?")) {
        error_log("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error);
        return 0;
    }

    $stmt->bind_param("i", $blogId);
    if (!$stmt->execute()) {
        error_log("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
        $stmt->close();
        return 0;
    }

    $result = $stmt->get_result();
    $like = $result->fetch_assoc();
    $stmt->close();
    return isset($like['like_count']) ? intval($like['like_count']) : 0;
}

function generateBlogPost(){
    
    require "dbconn.php";

    $stmt = $mysqli->prepare("SELECT * FROM blogs WHERE blog_id = ?");
    $stmt->bind_param("i", $_GET['blog_id']);
    $stmt->execute();
    $result = $stmt->get_result();

    $blog = $result->fetch_assoc();

    $formattedContent = "<p>" . implode("</p><p>", array_filter(explode("\n", htmlspecialchars($blog['content'])))) . "</p>";

    $likeCount = getLikeCount($blog['blog_id']);
    $commentCount = getCommentCount($blog['blog_id']);

    echo "<div id='blog-post-". intval($blog['blog_id']) . "' class='mainblog-posts'>";
    echo "<div class='blog-header' style='background-image: url(images/" . htmlspecialchars($blog['image_url']) . ");'>";
    echo "<h1 class='blog-center'>" . htmlspecialchars($blog['title']) . "</h1>";
    echo "</div>";
    echo "<div class='blog-content content-wrapper'>";
    echo "<p class=''>" . $formattedContent . "</p>";
    echo "<p>" . htmlspecialchars($blog['created_at']) . "</p>";
    if(isset($_SESSION['user_id'])){
        echo '<button class="like-button" 
                hx-post="data/like_blog.php" 
                hx-include="[name=\'blog_id\']" 
                hx-target="#like-count-' . intval($blog['blog_id']) . '" 
                hx-swap="outerHTML">Like</button>
            <span id="like-count-' . intval($blog['blog_id']) . '">' . $likeCount . ' Likes</span>';
        echo '<input type="hidden" name="blog_id" value="' . intval($blog['blog_id']) . '">';
    }
    echo "<p>Comments: " . $commentCount . "</p>";
    echo "</div>";
    echo "</div>";

    $stmt->close();
}

function generateUserBlogHtml($blog, $includeSwapOob = false) {
    $blogId = intval($blog['blog_id']);
    $title = htmlspecialchars($blog['title']);
    $createdAt = htmlspecialchars($blog['created_at']);
    $imageUrl = htmlspecialchars($blog['image_url']);
    $swapOobAttribute = $includeSwapOob ? ' hx-swap-oob="outerHTML"' : '';

    $likeCount = getLikeCount($blogId);

    return '
    <div id="blog-post-' . $blogId . '" class="user-blog"' . $swapOobAttribute . '>
        <a href="blog.php?blog_id=' . $blogId . '">
            <img class="user-blog-img" src="images/' . $imageUrl . '">
            <h3>' . $title . '</h3>
            <p>' . $createdAt . '</p>
        </a>
        <span class="like-count" id="user-like-count-' . $blogId . '">' . $likeCount . ' Likes</span>
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

    $user_id = intval($_SESSION['user_id']);

    $query = "
        SELECT b.blog_id, b.title, b.created_at, b.image_url 
        FROM blogs b 
        JOIN users u ON b.user_id = u.user_id 
        WHERE u.user_id = ?
    ";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($blog = $result->fetch_assoc()) {
        echo generateUserBlogHtml($blog);
    }

    $stmt->close();
}

?>
