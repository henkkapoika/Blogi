<?php
require "data/dbconn.php";

$stmt = $mysqli->prepare("SELECT * FROM blogs");
$stmt->execute();
$result = $stmt->get_result();

$post = $result->fetch_assoc();

$stmt2 = $mysqli->prepare("SELECT username FROM users WHERE user_id = ?");
$stmt2->bind_param("s", $post['user_id']);
$stmt2->execute();
$result2 = $stmt2->get_result();

$user = $result2->fetch_assoc();

$stmt->close();


?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="styles/style.css">
    <script src="https://unpkg.com/htmx.org@2.0.3"></script>
</head>
<body>
    <header>
        <div class="header-field">
            <ul class="header-list">
                <li>
                    <a href="localhost:8000/">
                        <img class="logo-image" src="images/bloghouse_logo.png" alt="Logo of the website">   
                    </a>
                </li>
                <li>
                    <a class="header-options" href="http://">User Page</a>
                    <a class="header-options" href="http://">About Us</a>
                </li>
            </ul>
        </div>
    </header>
    <main>
        <section class="main-advert">
            <h1>Placeholder</h1>
        </section>
        <section class="main-posts">
            <article class="main-post">
                <img src="images/<?=$post['image_url'] ?>" alt="">
                <h2><?=$post['title'] ?></h2>
                <p>Post Content</p>
                <p><?=$user['username'] ?></p>
                <p><?=$post['created_at'] ?></p>
            </article>
            <article class="main-post">
                <h2>Post Title</h2>
                <p>Post Content</p>
                <p>Post Author</p>
                <p>Post Date</p>
            </article>
            <article class="main-post">
                <h2>Post Title</h2>
                <p>Post Content</p>
                <p>Post Author</p>
                <p>Post Date</p>
            </article>
            <article class="main-post">
                <h2>Post Title</h2>
                <p>Post Content</p>
                <p>Post Author</p>
                <p>Post Date</p>
            </article>
            <article class="main-post">
                <h2>Post Title</h2>
                <p>Post Content</p>
                <p>Post Author</p>
                <p>Post Date</p>
            </article>
            <article class="main-post">
                <h2>Post Title</h2>
                <p>Post Content</p>
                <p>Post Author</p>
                <p>Post Date</p>
            </article>
        </section>
    </main>
    <footer>

    </footer>
</body>
</html>