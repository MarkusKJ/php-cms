<?php
require_once "config/database.php";
require_once "includes/csrf.php";
require_once "includes/header.php";

// Check if user is logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$csrf_token = generate_csrf_token();
$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    verify_csrf_token($_POST["csrf_token"]);

    $title = $conn->real_escape_string($_POST["title"]);
    $content = $conn->real_escape_string($_POST["content"]);
    $user_id = $_SESSION["user_id"];

    if (empty($title) || empty($content)) {
        $error = "Both title and content are required";
    } else {
        $sql = "INSERT INTO posts (user_id, title, content) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iss", $user_id, $title, $content);

        if ($stmt->execute()) {
            $success = "Post created successfully!";
        } else {
            $error = "Error: " . $conn->error;
        }
    }
}
?>

<h2>Create New Post</h2>

<?php
if (!empty($error)) {
    echo "<p style='color: red;'>$error</p>";
}
if (!empty($success)) {
    echo "<p style='color: green;'>$success</p>";
}
?>

<form action="create_post.php" method="post">
    <div>
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" required>
    </div>
    <div>
        <label for="content">Content:</label>
        <textarea id="content" name="content" rows="10" required></textarea>
    </div>
    <div>
        <input type="submit" value="Create Post">
    </div>
    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
</form>

<?php require_once "includes/footer.php"; ?>
