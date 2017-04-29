<?php
$now = new DateTime();
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename = OronoProblemData_' . $now->format('Y-m-d_H-i-s') . '.csv');
require_once('phpsqlinfo_dbinfo.php');

$conn = mysqli_connect("localhost", $username, $password, $database, 8889);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql2 = "SELECT id, name, lat, lon, description,timestamp,problem_status, file,tbl_problem_types.type_name 
FROM Problems 
  Inner JOIN tbl_problem_types 
    ON (Problems.type_id=tbl_problem_types.type_id);";

$result = mysqli_query($conn, $sql2) or die ("Selection Error " . mysqli_error($conn));

$fp = fopen('php://output', 'w');

$headings = ['PROBLEM_ID', 'USERNAME', 'LATTITUDE', 'LONGITUDE', 'DESCRIPTION','SUBMIT_DATETIME', 'STATUS', 'IMAGE_NAME','PROBLEM_TYPE'];

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