<?php
require_once "config/database.php";
require_once "includes/header.php";

if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    header("Location: posts.php");
    exit();
}

$post_id = $_GET["id"];

$sql = "SELECT posts.id, posts.title, posts.content, posts.created_at, users.username
        FROM posts
        JOIN users ON posts.user_id = users.id
        WHERE posts.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $post_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<p>Post not found.</p>";
} else {
    $post = $result->fetch_assoc(); ?>
    <article>
        <h2><?php echo htmlspecialchars($post["title"]); ?></h2>
        <p>By <?php echo htmlspecialchars(
            $post["username"]
        ); ?> on <?php echo date(
     "F j, Y",
     strtotime($post["created_at"])
 ); ?></p>
        <div>
            <?php echo nl2br(htmlspecialchars($post["content"])); ?>
        </div>
    </article>
<?php
}

require_once "includes/footer.php";
?>
