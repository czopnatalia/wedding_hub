<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}
require '../includes/db.php';

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="raport_rsvp.csv"');

$output = fopen('php://output', 'w');
fputcsv($output, ['Kod', 'Imię i nazwisko', 'Potwierdza', 'Bezglutenowa', 'Wege', 'Inna dieta', 'Osoba towarzysząca', 'Piosenka']);

$res = $conn->query("SELECT * FROM guests ORDER BY code, is_companion, name");
while ($row = $res->fetch_assoc()) {
    fputcsv($output, [
        $row['code'],
        $row['name'],
        $row['attending'] ? 'Tak' : 'Nie',
        $row['diet_gluten_free'] ? 'Tak' : 'Nie',
        $row['diet_vege'] ? 'Tak' : 'Nie',
        $row['diet_other'],
        $row['is_companion'] ? 'Tak' : 'Nie',
        $row['song_request']
    ]);
}
exit;
