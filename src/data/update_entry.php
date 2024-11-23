<?php
require "dbconn.php";
require "function.php";
date_default_timezone_set('Europe/Helsinki');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['blog_id']) && isset($_POST['title']) && isset($_POST['content'])) {
    $blogId = htmlspecialchars($_POST['blog_id']);
    $title = htmlspecialchars($_POST['title']);
    $content = htmlspecialchars($_POST['content']);
    $updated_at = date("Y-m-d H:i:s");

    $stmt = $mysqli->prepare("SELECT image_url FROM blogs WHERE blog_id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $blogId);
        $stmt->execute();
        $result = $stmt->get_result();
        $currentBlog = $result->fetch_assoc();
        $currentImageUrl = $currentBlog['image_url'];
        $stmt->close();
    } else {
        http_response_code(500);
        exit('Database prepare error.');
    }

    $updatedQuery = "UPDATE blogs SET title = ?, content = ?, updated_at = ?";
    $params = [$title, $content, $updated_at];
    $types = "sss";

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imgName = basename($_FILES['image']['name']);
        $imgTmpName = $_FILES['image']['tmp_name'];
        $fileSize = $_FILES['image']['size'];

        $uploadDir = '../images/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Noin 4mb
        if ($fileSize > 4000000) {
            http_response_code(400);
            exit('File is too large. Please try again.');
        }

        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
        $fileType = mime_content_type($imgTmpName);
        if (!in_array($fileType, $allowedTypes)) {
            http_response_code(400);
            exit('File type not supported. Please try again.');
        }

        $fileExtension = strtolower(pathinfo($imgName, PATHINFO_EXTENSION));
        $newFileName = md5(time() . $imgName) . '.' . $fileExtension;
        $destPath = $uploadDir . $newFileName;

        if (move_uploaded_file($imgTmpName, $destPath)) {
            $updatedQuery .= ", image_url = ?";
            $types .= "s";
            $params[] = $newFileName;

            if ($currentImageUrl) {
                $oldImagePath = $uploadDir . $currentImageUrl;
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
        } else {
            http_response_code(500);
            exit('There was an error uploading your file. Please try again.');
        }
    }

    $updatedQuery .= " WHERE blog_id = ?";
    $types .= "i"; 
    $params[] = $blogId;

    $stmt = $mysqli->prepare($updatedQuery);
    if ($stmt) {
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $stmt->close();
    } else {
        http_response_code(500);
        exit('Database prepare error.');
    }

    $stmt = $mysqli->prepare("SELECT * FROM blogs WHERE blog_id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $blogId);
        $stmt->execute();
        $result = $stmt->get_result();
        $blog = $result->fetch_assoc();
        $stmt->close();
    } else {
        http_response_code(500);
        exit('Database prepare error.');
    }

    echo generateUserBlogHtml($blog, true);

    echo '
    <script>
        document.getElementById("edit-modal").style.display = "none";
    </script>
    ';
}
?>

