<?php
function generate_csrf_token()
{
    if (!isset($_SESSION["csrf_token"])) {
        $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
    }
    return $_SESSION["csrf_token"];
}

function verify_csrf_token($token)
{
    if (!isset($_SESSION["csrf_token"]) || $token !== $_SESSION["csrf_token"]) {
        die("CSRF token validation failed");
    }
}
?>
