<?php
require_once "config/database.php";
require_once "includes/header.php";

$sql = "SELECT posts.id, posts.content, posts.created_at, users.username
        FROM posts
        JOIN users ON posts.user_id = users.id
        ORDER BY posts.created_at DESC
        LIMIT 20";
$result = $conn->query($sql);
?>

<h2>Recent Tweets</h2>

<?php if (isset($_SESSION["user_id"])): ?>
    <p><a href="create_tweet.php">Post a New Tweet</a></p>
<?php endif; ?>

<?php if ($result->num_rows > 0): ?>
    <?php while ($row = $result->fetch_assoc()): ?>
        <div class="tweet">
            <p><?php echo nl2br(htmlspecialchars($row["content"])); ?></p>
            <p class="tweet-meta">
                By <a href="user_tweets.php?username=<?php echo urlencode(
                    $row["username"]
                ); ?>"><?php echo htmlspecialchars($row["username"]); ?></a>
                on <?php echo date(
                    "Y-m-d H:i",
                    strtotime($row["created_at"])
                ); ?>
            </p>
        </div>
    <?php endwhile; ?>
<?php else: ?>
    <p>No tweets found.</p>
<?php endif; ?>

<?php require_once "includes/footer.php"; ?>
