<?php
header("Content-type: text/xml");

require("phpsqlinfo_dbinfo.php");

//
function parseToXML($htmlStr)
{
    $xmlStr = str_replace('<', '&lt;', $htmlStr);
    $xmlStr = str_replace('>', '&gt;', $xmlStr);
    $xmlStr = str_replace('"', '&quot;', $xmlStr);
    $xmlStr = str_replace("'", '&#39;', $xmlStr);
    $xmlStr = str_replace("&", '&amp;', $xmlStr);
    return $xmlStr;
}

// Opens a connection to a MySQL server
$connection = mysqli_connect('localhost', $username, $password);
if (!$connection) {
    die('Not connected : ' . mysqli_error($connection));
}

// Set the active MySQL database
$db_selected = mysqli_select_db($connection, $database);
if (!$db_selected) {
    die ('Can\'t use db : ' . mysqli_error($connection));
}

// Select all the rows in the markers table
$query = "SELECT *
    FROM Problems
    INNER JOIN tbl_problem_types ON (Problems.type_id=tbl_problem_types.type_id)
    ;";
$result = mysqli_query($connection, $query);
if (!$result) {
    die('Invalid query: ' . mysqli_error($connection));
}


// Start XML file, echo parent node
echo '<markers>';

// Iterate through the rows, printing XML nodes for each
while ($row = @mysqli_fetch_assoc($result)) {
    // Add to XML document node
    echo '<marker ';
    echo 'id="' . parseToXML($row['id']) . '" ';
    echo 'name="' . parseToXML($row['name']) . '" ';
    echo 'description="' . parseToXML($row['description']) . '" ';
    echo 'lat="' . $row['lat'] . '" ';
    echo 'lng="' . $row['lon'] . '" ';
    echo 'type_id="' . $row['type_id'] . '" ';
    echo 'type_name="' . $row['type_name'] . '" ';
    echo 'markerImage="' . $row['markerImage'] . '" ';
    echo 'timestamp="' . parseToXML($row['timestamp']) . '" ';
    echo 'problemStatus="' . $row['problem_status'] . '" ';
    echo 'img="' . $row['file'] . '" ';
    echo 'likes="' . $row['likes'] .' "';
    echo '/>';
}

// End XML file
echo '</markers>';

$connection->close();