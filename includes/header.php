<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['access']) && basename($_SERVER['PHP_SELF']) !== 'index.php') {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Natalia & Łukasz</title>
    <base href="/wedding_hub/">
    <link rel="stylesheet" href="assets/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600&family=Inter:wght@300;400;500&display=swap" rel="stylesheet">
</head>
<body>
<header class="site-header">
    <div class="site-header-inner">
        <div class="site-title-panel fade-in-up">
            <h1>Natalia i Łukasz</h1>
        </div>
        <nav class="main-nav fade-in-up" style="animation-delay:0.15s;">
            <a class="nav-tile" href="home.php">Strona główna</a>
            <a class="nav-tile" href="rsvp.php">Potwierdź obecność</a>
            <a class="nav-tile" href="galeria.php">Galeria zdjęć</a>
            <a class="nav-tile" href="admin/admin_login.php">Administrator</a>
        </nav>
    </div>
</header>
<div class="main">
