<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: /wedding_hub/admin/admin_login.php");
    exit;
}

require_once "../includes/db.php";
include '../includes/header.php';

$total = $db->query("SELECT COUNT(*) FROM guests")->fetchColumn();
$coming = $db->query("SELECT COUNT(*) FROM guests WHERE attending = 1")->fetchColumn();
$notComing = $db->query("SELECT COUNT(*) FROM guests WHERE attending = 0")->fetchColumn();

$gluten = $db->query("SELECT COUNT(*) FROM guests WHERE diet_gluten_free = 1")->fetchColumn();
$vege = $db->query("SELECT COUNT(*) FROM guests WHERE diet_vege = 1")->fetchColumn();
$vegan = $db->query("SELECT COUNT(*) FROM guests WHERE diet_vegan = 1")->fetchColumn();
$lactose = $db->query("SELECT COUNT(*) FROM guests WHERE diet_lactose = 1")->fetchColumn();
$other = $db->query("SELECT COUNT(*) FROM guests WHERE diet_other IS NOT NULL AND diet_other != ''")->fetchColumn();

$companions = $db->query("SELECT COUNT(*) FROM guests WHERE is_companion = 1")->fetchColumn();
?>

<style>
.login-btn {
    background: var(--accent);
    color: #000;
    padding: 12px 22px;
    border-radius: 12px;
    border: none;
    cursor: pointer;
    font-weight: 600;
    transition: 0.3s;
    text-decoration: none;
    display: inline-block;
}

.login-btn:hover {
    background: #ffe2b3;
}
</style>

<div class="section-card" style="max-width:700px; margin:60px auto; text-align:center;">
    <h2>Statystyki</h2>

    <p>Łączna liczba zgłoszeń: <strong><?= $total ?></strong></p>
    <p>Potwierdzone przybycie: <strong><?= $coming ?></strong></p>
    <p>Nie przyjdą: <strong><?= $notComing ?></strong></p>

    <h3 style="margin-top:30px;">Diety</h3>
    <p>Bez glutenu: <strong><?= $gluten ?></strong></p>
    <p>Wege: <strong><?= $vege ?></strong></p>
    <p>Wegańska: <strong><?= $vegan ?></strong></p>
    <p>Bez laktozy: <strong><?= $lactose ?></strong></p>
    <p>Inne: <strong><?= $other ?></strong></p>

    <h3 style="margin-top:30px;">Osoby towarzyszące</h3>
    <p>Łącznie: <strong><?= $companions ?></strong></p>

    <a href="/wedding_hub/admin/dashboard.php" class="login-btn" style="margin-top:30px; display:inline-block;">
    Powrót
    </a>
</div>

<?php include '../includes/footer.php'; ?>
