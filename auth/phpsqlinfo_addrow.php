<?php
header('Location: index.php');
require_once('phpsqlinfo_dbinfo.php');

if (!empty($_POST)) {
// Gets data from URL parameters.
    $name = htmlspecialchars($_POST['name']);
    $address = htmlspecialchars($_POST['address']);
    $lat = htmlspecialchars($_POST['lat']);
    $lon = htmlspecialchars($_POST['lng']);
    $type = htmlspecialchars($_POST['type']);

// Opens a connection to a MySQL server.
    $connection = mysqli_connect("localhost", $username, $password, $database, 8889);

    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }

// Inserts new row with place data.
    $query = "INSERT INTO Problems ( name,  lat, lon, address, type ) VALUES ( '$name','$lat', '$lon','$address', '$type');";

    $result = mysqli_query($connection, $query);

    $connection->close();
}
?>

