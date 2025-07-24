<?php
// Read data from CSV
$data = [];
if (($handle = fopen("data.csv", "r")) !== FALSE) {
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
    <title>Admin Panel - Form Submissions</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
</head>
<body class="bg-light">
    <div class="container py-5">
        <h1 class="mb-4 text-center">Form Submissions</h1>

        <!-- Export Buttons -->
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
                        <th>Email</th>
                        <th>Subject</th>
                        <th>Message</th>
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
                                    <form method="POST" action="delete.php" onsubmit="return confirm('Delete this entry?')">
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
            link.download = "form_data.csv";
            link.click();
        }

        function exportExcel() {
            let table = document.getElementById("data-table");
            let wb = XLSX.utils.table_to_book(table, { sheet: "FormData" });
            XLSX.writeFile(wb, "form_data.xlsx");
        }

        async function exportPDF() {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();
            doc.text("Form Submissions", 14, 16);

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
                head: [["Timestamp", "Name", "Email", "Subject", "Message"]],
                body: data,
                startY: 20
            });

            doc.save("form_data.pdf");
        }
    </script>

    <!-- jsPDF autoTable plugin -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.29/jspdf.plugin.autotable.min.js"></script>
</body>
</html>
