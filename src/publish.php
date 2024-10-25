<?php require "templates/header.php" ?>
<main>
<div>
    <form class="main-publish"
        hx-post="http://localhost:8000/data/entry.php"
        hx-target="#confirmation-message" 
        enctype="multipart/form-data"
        id="blogForm">
        <label for="">Blog entry name:</label>
        <input type="text" name="title" class="post-title" id="titleField">
        <label for="">Blog content:</label>
        <textarea rows="25" cols="60" name="content"></textarea>
        <label for="">Upload a picture:</label>
        <input type="file" name="file" accept="image/jpeg, image/png" maxlength="2000000">
        <button type="submit">Publish your blog!</button>
    </form>

    <div id="confirmation-message"></div>
</div>
<script>
    document.getElementById("blogForm").addEventListener("htmx:afterRequest", function(event){
        event.target.reset();

        document.getElementById("titleField").focus();
    });
</script>
</main>
<footer>

</footer>