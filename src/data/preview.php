<?php
session_start();
require '../data/function.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = htmlspecialchars($_POST['title']);
    $content = nl2br(htmlspecialchars($_POST['content']));
    
    echo '
    <div class="blog-post-preview">
        <h2>' . $title . '</h2>
        <p>' . $content . '</p>
        <!-- Optional: Display image preview using JavaScript FileReader -->

    </div>
    ';
} else {
    http_response_code(400);
    echo "Invalid request.";
}
?>