<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: /wedding_hub/admin/admin_login.php");
    exit;
}

require_once "../includes/db.php";
include '../includes/header.php';

// Pobieramy gości
$guests = $db->query("SELECT * FROM guests ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);

// Liczniki
$total_present = 0;
$diet_counts = [
    "Bez glutenu" => 0,
    "Wege" => 0,
    "Wegańska" => 0,
    "Bez laktozy" => 0,
    "Inne" => 0
];
?>

<style>
.admin-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    font-size: 15px;
}

.admin-table th {
    background: rgba(128, 106, 83, 0.33);
    color: #ffffff;
    padding: 12px;
    font-weight: 700;
    border-bottom: 2px solid rgba(234, 205, 161, 0.33);
    text-align: left;
    cursor: pointer;
}

.admin-table tr.present {
    background: rgba(180,255,180,0.25);
}

.admin-table tr.absent {
    background: rgba(255,180,180,0.25);
}

.admin-table td {
    padding: 10px;
    border-bottom: 1px solid rgba(0,0,0,0.15);
    color: var(--text-main);
}

.summary-box {
    margin-top: 30px;
    padding: 20px;
    background: rgba(255,255,255,0.12);;
    border-radius: 12px;
}

.search-box {
    margin-top: 20px;
    margin-bottom: 20px;
}
.search-input {
    padding: 10px;
    width: 260px;
    border-radius: 10px;
    border: 1px solid rgba(0,0,0,0.3);
}
.export-btn {
    background: var(--accent);
    padding: 10px 18px;
    border-radius: 10px;
    color: #000;
    text-decoration: none;
    margin-right: 10px;
    font-weight: 600;
}
.export-btn:hover {
    background: #ffe2b3;
}
.return-btn {
    background: var(--accent);
    color: #000;
    padding: 12px 26px;
    border-radius: 12px;
    border: none;
    cursor: pointer;
    font-weight: 600;
    text-decoration: none;
    display: inline-block;
    transition: 0.3s;
    margin-top: 30px;
}

.return-btn:hover {
    background: #ffe2b3;
}

</style>

<div class="section-card" style="max-width:1100px; margin:60px auto;">
    <h2>Lista gości</h2>

    <!-- WYSZUKIWARKA -->
    <div class="search-box">
        <input type="text" id="search" class="search-input" placeholder="Szukaj gościa...">
    </div>

    <!-- EXPORT -->
    <a href="/wedding_hub/admin/export_csv.php" class="export-btn">Eksport CSV</a>
    <a href="/wedding_hub/admin/export_pdf.php" class="export-btn">Eksport PDF</a>

    <table class="admin-table" id="guestTable">
        <thead>
            <tr>
                <th>Imię i nazwisko</th>
                <th onclick="sortPresence()" id="presenceHeader">
                    Obecność 
                    <span id="presenceArrow" style="font-size:14px; opacity:0.6;">▲</span>
                </th>
                <th>Dieta</th>
            </tr>
        </thead>

        <tbody>
        <?php foreach ($guests as $g): ?>

            <?php
            // Zliczamy obecność
            if ($g['attending']) {
                $total_present++;
            }

            // Zliczamy diety
            if ($g['diet_gluten_free']) $diet_counts["Bez glutenu"]++;
            if ($g['diet_vege']) $diet_counts["Wege"]++;
            if ($g['diet_vegan']) $diet_counts["Wegańska"]++;
            if ($g['diet_lactose']) $diet_counts["Bez laktozy"]++;
            if ($g['diet_other']) $diet_counts["Inne"]++;

            $row_class = $g['attending'] ? "present" : "absent";
            ?>

            <tr class="<?= $row_class ?>">
                <td><?= htmlspecialchars($g['name']) ?></td>
                <td><?= $g['attending'] ? "Tak" : "Nie" ?></td>

                <td>
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
            </tr>

        <?php endforeach; ?>
        </tbody>
    </table>

    <!-- PODSUMOWANIE -->
    <div class="summary-box">
        <h3>Podsumowanie</h3>

        <p><strong>Liczba gości, którzy potwierdzili obecność:</strong> <?= $total_present ?></p>

        <h4>Diety:</h4>
        <ul>
            <?php foreach ($diet_counts as $diet => $count): ?>
                <?php if ($count > 0): ?>
                    <li><?= $diet ?>: <?= $count ?></li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ul>
    </div>

    <a href="/wedding_hub/admin/dashboard.php" class="return-btn">
    Powrót
    </a>
</div>

<?php include '../includes/footer.php'; ?>

<script>
// WYSZUKIWANIE
document.getElementById("search").addEventListener("keyup", function() {
    let filter = this.value.toLowerCase();
    let rows = document.querySelectorAll("#guestTable tbody tr");

    rows.forEach(row => {
        let name = row.cells[0].innerText.toLowerCase();
        row.style.display = name.includes(filter) ? "" : "none";
    });
});

// SORTOWANIE PO OBECNOŚCI
let sortAsc = true;
function sortPresence() {
    let table = document.getElementById("guestTable");
    let rows = Array.from(table.rows).slice(1);

    rows.sort((a, b) => {
        let A = a.cells[1].innerText;
        let B = b.cells[1].innerText;
        return sortAsc ? A.localeCompare(B) : B.localeCompare(A);
    });

    sortAsc = !sortAsc;
    // Zmiana strzałki
    document.getElementById("presenceArrow").textContent = sortAsc ? "▲" : "▼";

    rows.forEach(r => table.tBodies[0].appendChild(r));
    }
</script>
