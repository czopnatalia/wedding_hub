<?php
require_once "../includes/db.php";

// Pobranie danych
$stmt = $db->query("SELECT * FROM guests ORDER BY name ASC");
$guests = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Nagłówki CSV
header('Content-Type: text/csv; charset=UTF-8');
header('Content-Disposition: attachment; filename="lista_gosci.csv"');

echo "\xEF\xBB\xBF";

// Otwieramy strumień
$output = fopen('php://output', 'w');

// Nagłówki kolumn
fputcsv($output, [
    'Imię i nazwisko',
    'Obecność',
    'Dieta'
], ';'); 

// Dane
foreach ($guests as $g) {

    $diets = [];
    if ($g['diet_gluten_free']) $diets[] = "Bez glutenu";
    if ($g['diet_vege']) $diets[] = "Wege";
    if ($g['diet_vegan']) $diets[] = "Wegańska";
    if ($g['diet_lactose']) $diets[] = "Bez laktozy";
    if ($g['diet_other']) $diets[] = $g['diet_other'];

    fputcsv($output, [
        $g['name'],
        $g['attending'] ? "Obecny" : "Nieobecny",
        implode(", ", $diets)
    ], ';');
}

fclose($output);
exit;
