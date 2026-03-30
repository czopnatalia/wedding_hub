<?php
require_once "../includes/db.php";
require_once "fpdf/fpdf.php";

// Zamiana polskich znaków na najbliższe odpowiedniki
function pl($text) {
    $map = [
        'ą'=>'a','ć'=>'c','ę'=>'e','ł'=>'l','ń'=>'n','ó'=>'o','ś'=>'s','ż'=>'z','ź'=>'z',
        'Ą'=>'A','Ć'=>'C','Ę'=>'E','Ł'=>'L','Ń'=>'N','Ó'=>'O','Ś'=>'S','Ż'=>'Z','Ź'=>'Z'
    ];
    return strtr($text, $map);
}

$pdf = new FPDF();
$pdf->AddPage();

// Arial – działa zawsze
$pdf->SetFont('Arial','B',16);

// Tytuł
$pdf->Cell(0,10, pl('Lista gosci'), 0, 1, 'C');
$pdf->Ln(5);

// Pobranie danych
$stmt = $db->query("SELECT * FROM guests ORDER BY name ASC");
$guests = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Liczniki
$total_present = 0;
$total_absent = 0;

$diet_counts = [
    "Bez glutenu" => 0,
    "Wege" => 0,
    "Weganska" => 0,
    "Bez laktozy" => 0,
    "Inne" => 0
];

// Zliczanie
foreach ($guests as $g) {
    if ($g['attending']) $total_present++;
    else $total_absent++;

    if ($g['diet_gluten_free']) $diet_counts["Bez glutenu"]++;
    if ($g['diet_vege']) $diet_counts["Wege"]++;
    if ($g['diet_vegan']) $diet_counts["Weganska"]++;
    if ($g['diet_lactose']) $diet_counts["Bez laktozy"]++;
    if ($g['diet_other']) $diet_counts["Inne"]++;
}

//
// PODSUMOWANIE
//
$pdf->SetFont('Arial','B',14);
$pdf->Cell(0,10, pl('Podsumowanie'), 0, 1);
$pdf->Ln(2);

$pdf->SetFont('Arial','',12);
$pdf->Cell(0,8, pl("Liczba gosci, ktorzy potwierdzili obecnosc: $total_present"),0,1);
$pdf->Cell(0,8, pl("Liczba gosci, ktorzy odmowili: $total_absent"),0,1);
$pdf->Ln(3);

$pdf->SetFont('Arial','B',12);
$pdf->Cell(0,8, pl("Diety:"),0,1);

$pdf->SetFont('Arial','',12);
foreach ($diet_counts as $diet => $count) {
    if ($count > 0) {
        $pdf->Cell(0,7, pl("$diet: $count"),0,1);
    }
}

$pdf->Ln(10);

//
// OBECNI
//
$pdf->SetFont('Arial','B',14);
$pdf->Cell(0,10, pl('Goscie obecni'),0,1);
$pdf->Ln(2);

$pdf->SetFont('Arial','B',12);
$pdf->Cell(70,10, pl('Imie i nazwisko'),1);
$pdf->Cell(90,10, pl('Dieta'),1);
$pdf->Ln();

$pdf->SetFont('Arial','',11);

foreach ($guests as $g) {
    if (!$g['attending']) continue;

    $diets = [];
    if ($g['diet_gluten_free']) $diets[] = "Bez glutenu";
    if ($g['diet_vege']) $diets[] = "Wege";
    if ($g['diet_vegan']) $diets[] = "Weganska";
    if ($g['diet_lactose']) $diets[] = "Bez laktozy";
    if ($g['diet_other']) $diets[] = $g['diet_other'];

    $pdf->Cell(70,10, pl($g['name']),1);
    $pdf->Cell(90,10, pl(implode(", ", $diets)),1);
    $pdf->Ln();
}

$pdf->Ln(10);

//
// NIEOBECNI
//
$pdf->SetFont('Arial','B',14);
$pdf->Cell(0,10, pl('Goscie nieobecni'),0,1);
$pdf->Ln(2);

$pdf->SetFont('Arial','B',12);
$pdf->Cell(70,10, pl('Imie i nazwisko'),1);
$pdf->Cell(90,10, pl('Dieta'),1);
$pdf->Ln();

$pdf->SetFont('Arial','',11);

foreach ($guests as $g) {
    if ($g['attending']) continue;

    $diets = [];
    if ($g['diet_gluten_free']) $diets[] = "Bez glutenu";
    if ($g['diet_vege']) $diets[] = "Wege";
    if ($g['diet_vegan']) $diets[] = "Weganska";
    if ($g['diet_lactose']) $diets[] = "Bez laktozy";
    if ($g['diet_other']) $diets[] = $g['diet_other'];

    $pdf->Cell(70,10, pl($g['name']),1);
    $pdf->Cell(90,10, pl(implode(", ", $diets)),1);
    $pdf->Ln();
}

$pdf->Output();
exit;
