<?php
require_once "config/database.php";
require_once "includes/csrf.php";
require_once "includes/header.php";

// Check if user is logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];
$username = $_SESSION["username"];

// Fetch user data
$sql = "SELECT email FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$email = $user["email"];

$success_message = "";
$error_message = "";

// Generate CSRF token
$csrf_token = generate_csrf_token();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    verify_csrf_token($_POST["csrf_token"]);
    $new_email = $conn->real_escape_string($_POST["email"]);
    $new_password = $_POST["new_password"];
    $confirm_password = $_POST["confirm_password"];

    if ($new_email !== $email) {
        $sql = "UPDATE users SET email = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $new_email, $user_id);
        if ($stmt->execute()) {
            $email = $new_email;
            $success_message = "Email updated successfully.";
        } else {
            $error_message = "Error updating email.";
        }
    }

    if (!empty($new_password)) {
        if ($new_password === $confirm_password) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $sql = "UPDATE users SET password = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $hashed_password, $user_id);
            if ($stmt->execute()) {
                $success_message .= " Password updated successfully.";
            } else {
                $error_message .= " Error updating password.";
            }
        } else {
            $error_message .= " Passwords do not match.";
        }
    }
}
?>

<h2>User Profile</h2>

<?php
if (!empty($success_message)) {
    echo "<p style='color: green;'>$success_message</p>";
}
if (!empty($error_message)) {
    echo "<p style='color: red;'>$error_message</p>";
}
?>

<form action="profile.php" method="post">
    <div>
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" value="<?php echo htmlspecialchars(
            $username
        ); ?>" readonly>
    </div>
    <div>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars(
            $email
        ); ?>" required>
    </div>
    <div>
        <label for="new_password">New Password (leave blank to keep current):</label>
        <input type="password" id="new_password" name="new_password">
    </div>
    <div>
        <label for="confirm_password">Confirm New Password:</label>
        <input type="password" id="confirm_password" name="confirm_password">
    </div>
    <div>
        <input type="submit" value="Update Profile">
    </div>
    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
</form>

<?php require_once "includes/footer.php"; ?>
