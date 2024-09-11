<?php
require_once "config/database.php";
require_once "includes/header.php";

if (!isset($_GET["username"])) {
    header("Location: index.php");
    exit();
}

$username = $conn->real_escape_string($_GET["username"]);

$sql = "SELECT posts.id, posts.content, posts.created_at, users.username
        FROM posts
        JOIN users ON posts.user_id = users.id
        WHERE users.username = ?
        ORDER BY posts.created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
?>

<h2>Tweets by <?php echo htmlspecialchars($username); ?></h2>

<?php if ($result->num_rows > 0): ?>
    <?php while ($row = $result->fetch_assoc()): ?>
        <div class="tweet">
            <p><?php echo nl2br(htmlspecialchars($row["content"])); ?></p>
            <p class="tweet-meta">
                on <?php echo date(
                    "Y-m-d H:i",
                    strtotime($row["created_at"])
                ); ?>
            </p>
        </div>
    <?php endwhile; ?>
<?php else: ?>
    <p>No tweets found for this user.</p>
<?php endif; ?>

<?php require_once "includes/footer.php"; ?>
