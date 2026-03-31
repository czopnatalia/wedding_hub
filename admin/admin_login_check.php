<?php
session_start();
require_once "../includes/db.php";

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

// Pobieramy admina z bazy
$stmt = $db->prepare("SELECT * FROM admins WHERE username = ?");
$stmt->execute([$username]);
$admin = $stmt->fetch(PDO::FETCH_ASSOC);

// Sprawdzamy poprawność hasła (jawne porównanie)
if ($admin && $password === $admin['password_hash']) {

    // Ustawiamy TYLKO dane logowania administratora
    $_SESSION['admin_logged_in'] = true;
    $_SESSION['admin_username'] = $admin['username'];

    $_SESSION['access'] = true;

    header("Location: /wedding_hub/admin/dashboard.php");
    exit;
}

// Jeśli błędne dane → wracamy na logowanie
header("Location: /wedding_hub/admin/admin_login.php?error=1");
exit;
