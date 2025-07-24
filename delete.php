<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['index'])) {
    $indexToDelete = intval($_POST['index']);
    $data = [];

    if (($handle = fopen("data.csv", "r")) !== FALSE) {
        while (($row = fgetcsv($handle)) !== FALSE) {
            $data[] = $row;
        }
        fclose($handle);
    }

    if (isset($data[$indexToDelete])) {
        unset($data[$indexToDelete]);
        $data = array_values($data);

        $handle = fopen("data.csv", "w");
        foreach ($data as $row) {
            fputcsv($handle, $row);
        }
        fclose($handle);
    }
}
header("Location: admin.php");
exit;
?>
