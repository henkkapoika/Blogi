<?php require "templates/header.php" ?>
<main>
<div>
    <form class="main-publish"
        hx-post="http://localhost:8000/data/entry.php"
        hx-target="#confirmation-message" 
        enctype="multipart/form-data">
        <label for="">Blog entry name:</label>
        <input type="text" name="title" class="post-title">
        <label for="">Blog content:</label>
        <textarea rows="25" cols="60" name="content"></textarea>
        <label for="">Upload a picture:</label>
        <input type="file" name="file" accept="image/jpeg, image/png" maxlength="2000000">
        <button type="submit">Publish your blog!</button>
    </form>

    <div id="confirmation-message"></div>
</div>
</main>
<footer>

</footer>