<?php
require_once "config/database.php";
require_once "includes/csrf.php";
require_once "includes/header.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$csrf_token = generate_csrf_token();
$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    verify_csrf_token($_POST["csrf_token"]);

    $content = $conn->real_escape_string($_POST["content"]);
    $user_id = $_SESSION["user_id"];

    if (empty($content) || strlen($content) > 280) {
        $error = "Tweet must be between 1 and 280 characters";
    } else {
        $sql = "INSERT INTO posts (user_id, content) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $user_id, $content);

        if ($stmt->execute()) {
            $success = "Tweet posted successfully!";
        } else {
            $error = "Error: " . $conn->error;
        }
    }
}
?>

<h2>Post a New Tweet</h2>

<?php
if (!empty($error)) {
    echo "<p style='color: #ff0000;'>$error</p>";
}
if (!empty($success)) {
    echo "<p style='color: #00ff00;'>$success</p>";
}
?>

<form action="create_tweet.php" method="post">
    <div>
        <textarea id="content" name="content" rows="3" maxlength="280" required onkeyup="updateCharCount()"></textarea>
        <p id="charCount">280 characters remaining</p>
    </div>
    <div>
        <input type="submit" value="Tweet">
    </div>
    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
</form>

<script>
function updateCharCount() {
    var remaining = 280 - document.getElementById('content').value.length;
    document.getElementById('charCount').innerHTML = remaining + ' characters remaining';
}
</script>

<?php require_once "includes/footer.php"; ?>
