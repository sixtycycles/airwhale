<?php
header('Location: index.php');
require_once('phpsqlinfo_dbinfo.php');

if (!empty($_POST&& $_POST['deleteList'])) {
    $deleteList = explode(',',htmlspecialchars($_POST['deleteList']));

    $connection = mysqli_connect("localhost", $username, $password, $database, 8889);

    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }
    for ($i = 0; $i <= sizeof($deleteList); $i++) {
       $query = "DELETE FROM Problems WHERE id = $deleteList[$i]";
       $result = mysqli_query($connection, $query);
    }

    $connection->close();


}