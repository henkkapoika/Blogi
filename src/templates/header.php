<?php
require "data/dbconn.php";
require "data/post.php";


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