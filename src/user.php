<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

require "templates/header.php";
require "data/dbconn.php";

$stmt = $mysqli->prepare("SELECT * FROM users WHERE user_Id = ?");
$stmt->bind_param("s", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

?>
</header>
<main>
    <section>
        <div class="user-info">
            <h2>User Information:</h2>
            <p><strong>Username:</strong> <?php echo htmlspecialchars($_SESSION['username']); ?></p>
            <p><strong>Registration Date:</strong> <?php echo htmlspecialchars($user['created_at']); ?></p>
            <?php if ($user['updated_at']) : ?>
                <p><strong>Profile updated:</strong> <?php echo htmlspecialchars($user['updated_at']); ?></p>
            <?php endif; ?>
        </div>

        <div id="profile-picture">
            <a href="user_blog.php?username=<?= urlencode(htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8')) ?>">
                <img src="<?php echo htmlspecialchars($user['profile_picture']) ?? 'uploads/default.png'; ?>" alt="Profile Picture" width="150" height="150">
            </a>
        </div>

        <form id="profile-picture-form" hx-post="data/upload_profile_picture.php" hx-target="#profile-picture" hx-swap="outerHTML" enctype="multipart/form-data" hx-encoding="multipart/form-data">
            <label for="profile-picture-input">Upload a new profile picture:</label>
            <input type="file" name="profile_picture" id="profile-picture-input" accept="image/*" required>
            <button type="submit">Upload</button>
        </form>


        <div class="user-btn-div">
            <button class="user-btn" id="change-password-btn">Update your password</button>
        </div>

        <div id="password-modal" class="modal" style="display: none;">
            <div class="modal-content">
                <span class="close" id="close-password">&times;</span>
                <div class="modal-header">
                    <h3>Change Password</h3>
                </div>
                <form hx-post="data/password_change.php"
                    hx-target="#password-feedback"
                    id="password-form"
                    hx-swap="innerHTML" class="header-form"
                    hx-on="htmx:afterRequest: 
                      document.getElementById('password-form').reset(); 
                      document.getElementById('password').focus();">
                    <label for="password">Password:</label>
                    <input type="password" name="password" id="password" required>
                    <label for="newPassword">New Password:</label>
                    <input type="password" name="newPassword" id="newPassword" required>
                    <button type="submit">Change your password</button>
                </form>
                <div id="password-feedback"></div>
            </div>
        </div>
    </section>

    <section>
        <h2 class="blog-heading">Your Blogs</h2>
        <div class="user-blogs" id="user-blogs">

            <?php
            require "data/function.php";
            generateUserBlogs();
            ?>
        </div>
        <div id="edit-modal" class="modal" style="display: none;">

        </div>
    </section>
</main>
<script>
    const passwordModal = document.getElementById("password-modal");
    const closeBtn = document.getElementById("close-password");
    const editModal = document.getElementById("edit-modal");
    const closeEditBtn = document.getElementById("close-edit-modal");
    const changePasswordBtn = document.getElementById("change-password-btn");

    function openPasswordModal() {
        passwordModal.style.display = "block";
    }

    changePasswordBtn.addEventListener("click", function() {
        openPasswordModal();
    });

    closeBtn.addEventListener("click", function() {
        passwordModal.style.display = "none";
    });

    window.addEventListener("click", function(event) {
        if (event.target === passwordModal) {
            passwordModal.style.display = "none";
        }
    });

    // Edit modal
    document.addEventListener("htmx:afterOnLoad", (event) => {
        if (event.detail.target.id === "edit-modal") {
            const editModal = document.getElementById("edit-modal");
            editModal.style.display = "block";


            const closeEditBtn = document.getElementById("close-edit-modal");
            closeEditBtn.addEventListener("click", function() {
                editModal.style.display = "none";
            });
        }
    });


    window.addEventListener("click", (event) => {
        const editModal = document.getElementById("edit-modal");
        if (editModal && event.target === editModal) {
            editModal.style.display = "none";
        }
    });


    document.addEventListener("htmx:afterRequest", function(event) {
        if (event.detail.target.id === "edit-form") {
            const editModal = document.getElementById("edit-modal");
            if (editModal) editModal.style.display = "none";

            // Refresh
            htmx.trigger("#user-blogs", "htmx:get", {
                target: "#user-blogs"
            });
        }
    });

    // Delete
    document.body.addEventListener('htmx:afterRequest', function(evt) {
        if (evt.detail.xhr && evt.detail.xhr.responseURL.includes('delete_entry.php')) {
            if (evt.detail.xhr.status === 200) {
                alert('Blog post deleted successfully.');
            } else {
                alert(evt.detail.xhr.responseText);
            }
        }
    });
</script>
<?php
require "templates/footer.php";
?>