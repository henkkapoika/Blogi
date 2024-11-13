<?php
require "dbconn.php";
session_start();

if (isset($_GET['blog_id'])) {
    $blogId = htmlspecialchars($_GET['blog_id']);
    $stmt = $mysqli->prepare("SELECT * FROM blogs WHERE blog_id = ?");
    $stmt->bind_param("s", $blogId);
    $stmt->execute();
    $result = $stmt->get_result();

    $blog = $result->fetch_assoc();

    $stmt->close();
?>

    <div class="modal-content">
            <span class="close" id="close-edit-modal">&times;</span>
            <div class="modal-header">
                <h3>Edit Blog Post</h3>
            </div>
            <form id="edit-form" hx-post="data/update_entry.php" hx-target="#edit-modal" hx-swap="innerHTML" enctype="multipart/form-data">
                <input type="hidden" name="blog_id" value="<?php echo htmlspecialchars($blogId); ?>">

                <div class="form-group">
                    <label for="title">Title:</label>
                    <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($blog['title']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="content">Content:</label>
                    <textarea id="content" name="content" required><?php echo htmlspecialchars($blog['content']); ?></textarea>
                </div>

                <div class="form-group">
                    <label for="image">Update Image:</label>
                    <input type="file" id="image" name="image">
                </div>

                <button type="submit">Update Blog Post</button>
            </form>
            <div id="edit-feedback"></div>
    </div>

<?php

}

?>