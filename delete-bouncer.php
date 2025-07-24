<?php require_once 'security.php'; ?>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $index = $_POST['index'] ?? null;
    if ($index !== null) {
        $file = "bouncer_data.csv";
        $rows = array_map('str_getcsv', file($file));
        if (isset($rows[$index])) {
            unset($rows[$index]);
            $fp = fopen($file, 'w');
            foreach ($rows as $row) {
                fputcsv($fp, $row);
            }
            fclose($fp);
        }
    }
    header("Location: bouncer-admin.php");
    exit;
}
?>

