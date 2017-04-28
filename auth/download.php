<?php
$now = new DateTime();
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename = OronoProblemData_' . $now->format('Y-m-d_H-i-s') . '.csv');
require_once('phpsqlinfo_dbinfo.php');

$conn = mysqli_connect("localhost", $username, $password, $database, 8889);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql2 = "SELECT * FROM Problems";

$result = mysqli_query($conn, $sql2) or die ("Selection Error " . mysqli_error($conn));

$fp = fopen('php://output', 'w');

$headings = ['PROBLEM_ID', 'USERNAME', 'LATTITUDE', 'LONGITUDE', 'DESCRIPTION', 'TYPE', 'SUBMIT_DATETIME', 'STATUS', 'IMAGE_NAME'];

fputcsv($fp, $headings);

while ($row = mysqli_fetch_assoc($result)) {
    if ($row != null) {
        fputcsv($fp, $row);
    }
}
fclose($fp);
mysqli_close($conn);
exit();
?>