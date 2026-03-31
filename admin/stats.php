<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: /wedding_hub/admin/admin_login.php");
    exit;
}

require_once "../includes/db.php";
include '../includes/header.php';

// Statystyki główne
$total = $db->query("SELECT COUNT(*) FROM guests")->fetchColumn();
$coming = $db->query("SELECT COUNT(*) FROM guests WHERE attending = 1")->fetchColumn();
$notComing = $db->query("SELECT COUNT(*) FROM guests WHERE attending = 0")->fetchColumn();

$invited = 80; // liczba zaproszonych osób
$noResponse = $invited - ($coming + $notComing);

// Diety
$gluten = $db->query("SELECT COUNT(*) FROM guests WHERE diet_gluten_free = 1")->fetchColumn();
$vege = $db->query("SELECT COUNT(*) FROM guests WHERE diet_vege = 1")->fetchColumn();
$vegan = $db->query("SELECT COUNT(*) FROM guests WHERE diet_vegan = 1")->fetchColumn();
$lactose = $db->query("SELECT COUNT(*) FROM guests WHERE diet_lactose = 1")->fetchColumn();
$other = $db->query("SELECT COUNT(*) FROM guests WHERE diet_other IS NOT NULL AND diet_other != ''")->fetchColumn();
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

.progress-wrapper {
    margin-top: 15px;
}

.progress-bar {
    width: 100%;
    height: 32px;
    background: #e6e6e6;
    overflow: visable;
    display: flex;
    position: relative;
    box-shadow: inset 0 0 6px rgba(0,0,0,0.15);
}

.progress-bar .bar {
    height: 100%;
    position: relative;
    transition: width 1s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Kolory premium z gradientem */
.present {
    background: linear-gradient(135deg, #4CAF50, #3d8f42);
}

.absent {
    background: linear-gradient(135deg, #e74c3c, #c0392b);
}

.noresponse {
    background: linear-gradient(135deg, #bfbfbf, #a6a6a6);
}

/* Tooltip premium */
.tooltip {
    position: absolute;
    top: 40px;
    background: rgba(0,0,0,0.85);
    color: #fff;
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 13px;
    white-space: nowrap;
    opacity: 0;
    pointer-events: none;
    left: 50%;
    transform: translate(-50%, -10px);
    transition: 0.25s ease;
    box-shadow: 0 4px 10px rgba(0,0,0,0.25);
}

/* Strzałka tooltipa */
.tooltip::after {
    content: "";
    position: absolute;
    top: -6px;
    left: 50%;
    transform: translateX(-50%);
    border-width: 6px;
    border-style: solid;
    border-color: rgba(0,0,0,0.85) transparent transparent transparent;
}

/* Pokazywanie tooltipa */
.bar:hover .tooltip {
    opacity: 1;
    transform: translate(-50%, 0);
}

.progress-bar .bar {
    height: 100%;
    position: relative;
    transition: width 1s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 0; /* start od 0% */
}

</style>

<div class="section-card" style="max-width:700px; margin:60px auto; text-align:left; padding:20px 30px;">
    <h2>Statystyki:</h2>

    <p>Potwierdzili obecność: <strong><?= $coming ?></strong></p>
    <p>Nie pojawią się: <strong><?= $notComing ?></strong></p>
    <p>Brak odpowiedzi: <strong><?= $noResponse ?></strong></p>

    <h3 style="margin-top:50px;">Postęp odpowiedzi:</h3>

    <div class="progress-wrapper">
        <div class="progress-bar">
            <div class="bar present" data-width="<?= ($coming / $invited) * 100 ?>%">
                <span class="tooltip">Obecni: <?= $coming ?></span>
            </div>
            <div class="bar absent" data-width="<?= ($notComing / $invited) * 100 ?>%">
                <span class="tooltip">Nieobecni: <?= $notComing ?></span>
            </div>
            <div class="bar noresponse" data-width="<?= ($noResponse / $invited) * 100 ?>%">
                <span class="tooltip">Brak odpowiedzi: <?= $noResponse ?></span>
            </div>
        </div>
    </div>

    <h3 style="margin-top:50px;">Diety:</h3>
    <p>Bez glutenu: <strong><?= $gluten ?></strong></p>
    <p>Wege: <strong><?= $vege ?></strong></p>
    <p>Wegańska: <strong><?= $vegan ?></strong></p>
    <p>Bez laktozy: <strong><?= $lactose ?></strong></p>
    <p>Inne: <strong><?= $other ?></strong></p>

    <a href="/wedding_hub/admin/dashboard.php" class="login-btn" style="margin-top:30px; display:inline-block;">
        Powrót
    </a>
</div>

<?php include '../includes/footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.progress-bar .bar').forEach(function(bar) {
        const target = bar.getAttribute('data-width') || '0%';
        // małe opóźnienie, żeby transition zadziałało
        setTimeout(function () {
            bar.style.width = target;
        }, 200);
    });
});
</script>
