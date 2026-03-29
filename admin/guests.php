<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: /wedding_hub/admin/admin_login.php");
    exit;
}

require_once "../includes/db.php";
include '../includes/header.php';

$guests = $db->query("SELECT * FROM guests ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
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

<div class="section-card" style="max-width:1100px; margin:60px auto;">
    <h2>Lista gości</h2>

    <table style="width:100%; border-collapse:collapse; margin-top:20px; font-size:15px;">
        <tr style="background:#f5f5f5;">
            <th style="padding:10px; border-bottom:1px solid #ddd;">Kod</th>
            <th style="padding:10px; border-bottom:1px solid #ddd;">Imię i nazwisko</th>
            <th style="padding:10px; border-bottom:1px solid #ddd;">Obecność</th>
            <th style="padding:10px; border-bottom:1px solid #ddd;">Diety</th>
            <th style="padding:10px; border-bottom:1px solid #ddd;">Osoba towarzysząca</th>
            <th style="padding:10px; border-bottom:1px solid #ddd;">Piosenka</th>
            <th style="padding:10px; border-bottom:1px solid #ddd;">Data</th>
        </tr>

        <?php foreach ($guests as $g): ?>
        <tr>
            <td style="padding:10px; border-bottom:1px solid #eee;"><?= htmlspecialchars($g['code']) ?></td>
            <td style="padding:10px; border-bottom:1px solid #eee;"><?= htmlspecialchars($g['name']) ?></td>
            <td style="padding:10px; border-bottom:1px solid #eee;"><?= $g['attending'] ? "Tak" : "Nie" ?></td>

            <td style="padding:10px; border-bottom:1px solid #eee;">
                <?php
                    $diets = [];
                    if ($g['diet_gluten_free']) $diets[] = "Bez glutenu";
                    if ($g['diet_vege']) $diets[] = "Wege";
                    if ($g['diet_vegan']) $diets[] = "Wegańska";
                    if ($g['diet_lactose']) $diets[] = "Bez laktozy";
                    if ($g['diet_other']) $diets[] = htmlspecialchars($g['diet_other']);
                    echo implode(", ", $diets);
                ?>
            </td>

            <td style="padding:10px; border-bottom:1px solid #eee;"><?= $g['is_companion'] ? "Tak" : "Nie" ?></td>
            <td style="padding:10px; border-bottom:1px solid #eee;"><?= htmlspecialchars($g['song_request']) ?></td>
            <td style="padding:10px; border-bottom:1px solid #eee;"><?= $g['created_at'] ?></td>
        </tr>
        <?php endforeach; ?>
    </table>

    <a href="/wedding_hub/admin/dashboard.php" class="login-btn" style="margin-top:30px; display:inline-block;">
    Powrót
    </a>
</div>

<?php include '../includes/footer.php'; ?>
