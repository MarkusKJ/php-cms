<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
?>

<?php
require_once "config/database.php";
require_once "includes/header.php";
?>
<?php if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $conn->real_escape_string($_POST["username"]);
    $email = $conn->real_escape_string($_POST["email"]);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"]; // Basic validation
    if (empty($username) || empty($email) || empty($password)) {
        $error = "All fields are required";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match";
    } else {
        // Check if username or email already exists
        $check_query = "SELECT * FROM users WHERE username = '$username' OR email = '$email'";
        $result = $conn->query($check_query);
        if ($result->num_rows > 0) {
            $error = "Username or email already exists";
        } else {
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            // Insert new user
            $insert_query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";
            if ($conn->query($insert_query) === true) {
                $success = "Registration successful. You can now log in.";
            } else {
                $error = "Error: " . $conn->error;
            }
        }
    }
} ?>

<?php
if (isset($error)) {
    echo "<p style='color: red;'>$error</p>";
}
if (isset($success)) {
    echo "<p style='color: green;'>$success</p>";
}
?>
<h2>Register</h2>
<form action="register.php" method="post">
    <div>
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
    </div>
    <div>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
    </div>
    <div>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
    </div>
    <div>
        <label for="confirm_password">Confirm Password:</label>
        <input type="password" id="confirm_password" name="confirm_password" required>
    </div>
    <div>
        <input type="submit" value="Register">
    </div>
</form>

<?php require_once "includes/footer.php"; ?>
