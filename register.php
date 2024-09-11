<?php
require_once "config/database.php";
require_once "includes/csrf.php";
require_once "includes/header.php";

$error = "";
$success = "";
$csrf_token = generate_csrf_token();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    verify_csrf_token($_POST["csrf_token"]);

    $username = $conn->real_escape_string($_POST["username"]);
    $email = $conn->real_escape_string($_POST["email"]);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    if (empty($username) || empty($email) || empty($password)) {
        $error = "All fields are required";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match";
    } else {
        $check_query = "SELECT * FROM users WHERE username = ? OR email = ?";
        $stmt = $conn->prepare($check_query);
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error = "Username or email already exists";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $insert_query =
                "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($insert_query);
            $stmt->bind_param("sss", $username, $email, $hashed_password);

            if ($stmt->execute()) {
                $success = "Registration successful. You can now log in.";
            } else {
                $error = "Error: " . $conn->error;
            }
        }
    }
}
?>

<h2>Register for Retro Tweet</h2>

<?php
if (!empty($error)) {
    echo "<p style='color: #ff0000;'>$error</p>";
}
if (!empty($success)) {
    echo "<p style='color: #00ff00;'>$success</p>";
}
?>

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
    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
</form>

<p>Already have an account? <a href="login.php">Login here</a>.</p>

<?php require_once "includes/footer.php"; ?>
