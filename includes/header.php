<?php
session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Retro Tweet</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
<header>
    <h1>Retro Tweet</h1>
    <nav>
        <a href="/index.php">Home</a> |
        <?php if (isset($_SESSION["user_id"])): ?>
            <a href="/create_tweet.php">New Tweet</a> |
            <a href="/profile.php">Profile</a> |
            <a href="/logout.php">Logout</a>
        <?php else: ?>
            <a href="/login.php">Login</a> |
            <a href="/register.php">Register</a>
        <?php endif; ?>
    </nav>
</header>
    <main>
