<?php
require_once "includes/db.php";

// SPRAWDZENIE, CZY FORMULARZ ZOSTAŁ WYSŁANY
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: rsvp.php");
    exit;
}

$code = "RSVP";

// Pobieramy dane z formularza
$imiona = $_POST['imie'];
$nazwiska = $_POST['nazwisko'];
$obecnosci = $_POST['obecnosc'];

$d_gluten = $_POST['dieta_gluten'] ?? [];
$d_vege = $_POST['dieta_vege'] ?? [];
$d_vegan = $_POST['dieta_vegan'] ?? [];
$d_lactose = $_POST['dieta_lactose'] ?? [];
$d_other = $_POST['dieta_other_text'] ?? [];
$d_none = $_POST['dieta_choice'] ?? [];

$duplikaty = []; // lista osób, które już istnieją

for ($i = 0; $i < count($imiona); $i++) {

    $name = trim($imiona[$i] . " " . $nazwiska[$i]);
    $attending = ($obecnosci[$i] === "tak") ? 1 : 0;

    // Czy wybrano "brak diety"
    $no_diet = isset($d_none[$i]) && $d_none[$i] === "brak";

    // Dieta
    if ($attending && !$no_diet) {
        $gluten = isset($d_gluten[$i]) ? 1 : 0;
        $vege = isset($d_vege[$i]) ? 1 : 0;
        $vegan = isset($d_vegan[$i]) ? 1 : 0;
        $lactose = isset($d_lactose[$i]) ? 1 : 0;
        $other = $d_other[$i] ?? null;
    } else {
        $gluten = 0;
        $vege = 0;
        $vegan = 0;
        $lactose = 0;
        $other = null;
    }

    // SPRAWDZENIE, CZY OSOBA JUŻ ISTNIEJE
    $check = $db->prepare("SELECT id FROM guests WHERE name = ?");
    $check->execute([$name]);

    if ($check->rowCount() > 0) {
        $duplikaty[] = $name;
        continue; // przechodzimy do kolejnej osoby
    }

    // ZAPIS NOWEJ OSOBY
    $insert = $db->prepare("
        INSERT INTO guests 
        (code, name, attending, diet_gluten_free, diet_vege, diet_vegan, diet_lactose, diet_other, is_companion)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $insert->execute([
        $code,
        $name,
        $attending,
        $gluten,
        $vege,
        $vegan,
        $lactose,
        $other,
        1
    ]);
}

// JEŚLI SĄ DUPLIKATY → PRZEKIEROWANIE Z LISTĄ
if (!empty($duplikaty)) {
    $lista = urlencode(implode(", ", $duplikaty));
    header("Location: rsvp.php?duplikaty=$lista&success=1");
    exit;
}

// JEŚLI WSZYSTKO OK
header("Location: rsvp.php?success=1");
exit;
