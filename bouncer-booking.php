<?php
// OPTIONAL: Uncomment to help with debugging
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name'] ?? '');
    $event = trim($_POST['event'] ?? '');
    $date = trim($_POST['date'] ?? '');
    $contact = trim($_POST['contact'] ?? '');
    $timestamp = date("Y-m-d H:i:s");

    if (!empty($name) && !empty($event) && !empty($date) && !empty($contact)) {
        // Generate timestamp
        $timestamp = date("Y-m-d H:i:s");


        $file = fopen("bouncer_data.csv", "a");
        if ($file) {
            fputcsv($file, [$timestamp, $name, $event, $date, $contact]);
            fclose($file);
        }

        // ✅ This must happen before any output
        header("Location: index.html");
        exit;
    } else {
        // Optional: Show error and stop
        echo "<h3 style='color:red; text-align:center;'>All fields are required.</h3>";
        exit;
    }
}else {
    // Invalid request method
    echo "<h3 style='color:red; text-align:center;'>Invalid request.</h3>";
    exit;
}
?>
