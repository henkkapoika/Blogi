<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

require "templates/header.php"; 
?>
<main>
<div>
    <form id="blogForm" class="main-publish"
        hx-post="data/entry.php"
        enctype="multipart/form-data"
        >
        <label for="">Blog entry name:</label>
        <input type="text" name="title" class="post-title" id="titleField" required>

        <label for="">Blog content:</label>
        <textarea rows="25" cols="60" name="content" required></textarea>

        <label for="">Upload a picture:</label>
        <input type="file" id="fileInput" name="image" accept="image/*">

        <button type="submit" id="submit-button">Publish your blog!</button>
    </form>

    <div id="confirmation-message"></div>
</div>
<script>
    document.getElementById("blogForm").addEventListener("htmx:afterRequest", function(event){
        event.target.reset();

        document.getElementById("titleField").focus();
    });
</script>
<?php
require "templates/footer.php";
?>