<?php
$now = new DateTime();
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename = OronoProblemData_' . $now->format('Y-m-d_H-i-s') . '.csv');
require_once('phpsqlinfo_dbinfo.php');

$conn = mysqli_connect("localhost", $username, $password, $database, 8889);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql2 = "SELECT Problems.id, name, lat, lon, description,problem_status, pTimes.create_timestamp,pTimes.start_timestamp,pTimes.complete_timestamp, Problems.likes, pTypes.type_name 
FROM Problems 
  INNER JOIN tbl_problem_types as pTypes
    ON (Problems.type_id = pTypes.type_id)
  INNER JOIN problem_timelines as pTimes
    ON (Problems.id = pTimes.id);";

$result = mysqli_query($conn, $sql2) or die ("Selection Error " . mysqli_error($conn));

$fp = fopen('php://output', 'w');

$headings = ['PROBLEM_ID', 'USERNAME', 'LATTITUDE', 'LONGITUDE', 'DESCRIPTION','STATUS', 'CREATE_DATETIME','START_DATETIME','COMPLETE_DATETIME', 'LIKES', 'PROBLEM_TYPE'];

fputcsv($fp, $headings);

while ($row = mysqli_fetch_assoc($result)) {
    if ($row != null) {
        fputcsv($fp, $row);
    }
}
fclose($fp);
mysqli_close($conn);
exit();