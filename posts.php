<?php
require_once "config/database.php";
require_once "includes/header.php";

$sql = "SELECT posts.id, posts.title, posts.content, posts.created_at, users.username
        FROM posts
        JOIN users ON posts.user_id = users.id
        ORDER BY posts.created_at DESC";
$result = $conn->query($sql);
?>

<h2>Blog Posts</h2>

<?php if ($result->num_rows > 0): ?>
    <?php while ($row = $result->fetch_assoc()): ?>
        <article>
            <h3><?php echo htmlspecialchars($row["title"]); ?></h3>
            <p>By <?php echo htmlspecialchars(
                $row["username"]
            ); ?> on <?php echo date(
     "F j, Y",
     strtotime($row["created_at"])
 ); ?></p>
            <p><?php echo nl2br(
                htmlspecialchars(substr($row["content"], 0, 200))
            ); ?>...</p>
            <a href="view_post.php?id=<?php echo $row["id"]; ?>">Read More</a>
        </article>
        <hr>
    <?php endwhile; ?>
<?php else: ?>
    <p>No posts found.</p>
<?php endif; ?>

<?php require_once "includes/footer.php"; ?>
