<?php
$data = [];
if (($handle = fopen("bouncer_data.csv", "r")) !== FALSE) {
    while (($row = fgetcsv($handle)) !== FALSE) {
        $data[] = $row;
    }
    fclose($handle);
}
?>

<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header("Location: admin-login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bouncer Booking Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
</head>
<body class="bg-light">
<div class="container py-5">
    <h1 class="mb-4 text-center">Bouncer Booking Requests</h1>

    <div class="mb-3 text-center">
        <button class="btn btn-success me-2" onclick="exportExcel()">Download Excel</button>
        <button class="btn btn-primary me-2" onclick="exportCSV()">Download CSV</button>
        <button class="btn btn-danger" onclick="exportPDF()">Download PDF</button>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-striped text-center" id="data-table">
            <thead class="table-dark">
                <tr>
                    <th>Timestamp</th>
                    <th>Name</th>
                    <th>Event Type</th>
                    <th>Date</th>
                    <th>Contact</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php if (!empty($data)) : ?>
                <?php foreach ($data as $index => $row) : ?>
                    <tr>
                        <?php foreach ($row as $value) : ?>
                            <td><?= htmlspecialchars($value) ?></td>
                        <?php endforeach; ?>
                        <td>
                            <form method="POST" action="delete-bouncer.php" onsubmit="return confirm('Delete this entry?')">
                                <input type="hidden" name="index" value="<?= $index ?>">
                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr><td colspan="6">No data found.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    function exportCSV() {
        let table = document.getElementById("data-table");
        let rows = table.querySelectorAll("tr");
        let csv = [];

        rows.forEach(row => {
            let cols = row.querySelectorAll("td, th");
            let rowData = [];
            cols.forEach(col => rowData.push(col.innerText));
            csv.push(rowData.join(","));
        });

        let blob = new Blob([csv.join("\n")], { type: "text/csv" });
        let link = document.createElement("a");
        link.href = URL.createObjectURL(blob);
        link.download = "bouncer_bookings.csv";
        link.click();
    }

    function exportExcel() {
        let table = document.getElementById("data-table");
        let wb = XLSX.utils.table_to_book(table, { sheet: "Bookings" });
        XLSX.writeFile(wb, "bouncer_bookings.xlsx");
    }

    async function exportPDF() {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();
        doc.text("Bouncer Bookings", 14, 16);

        const table = document.getElementById("data-table");
        let data = [];
        for (let i = 1; i < table.rows.length; i++) {
            let row = [];
            for (let j = 0; j < 5; j++) {
                row.push(table.rows[i].cells[j].innerText);
            }
            data.push(row);
        }

        doc.autoTable({
            head: [["Timestamp", "Name", "Event", "Date", "Contact"]],
            body: data,
            startY: 20
        });

        doc.save("bouncer_bookings.pdf");
    }
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.29/jspdf.plugin.autotable.min.js"></script>
</body>
</html>
