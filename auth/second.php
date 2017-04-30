<?php
//header('Location: home.php');
require_once('phpsqlinfo_dbinfo.php');

if (!empty($_GET)) {

    $id = htmlspecialchars($_GET['id']);

    $connection = mysqli_connect("localhost", $username, $password, $database, 8889);

    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }
    $selectQuery = "SELECT likes from Problems where id = $id";
    $result = mysqli_query($connection, $selectQuery);

    $row = mysqli_fetch_assoc($result);

    $likes = $row['likes'] + 1;

    //set likes to existing likes plus 1
    if(!empty($row)) {
        $updateQuery = "UPDATE Problems SET likes = $likes WHERE id = $id";
        $result2 = mysqli_query($connection, $updateQuery);
    }
    echo "$likes";

    $connection->close();

    exit();
}