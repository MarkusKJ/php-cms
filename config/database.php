<?php
define("DB_HOST", "localhost");
define("DB_USER", ""); // or your actual MySQL username
define("DB_PASS", ""); // your actual MySQL password, if set
define("DB_NAME", "php_cms");

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
