<?php
header('Location: index.php');
require_once('phpsqlinfo_dbinfo.php');

if (!empty($_POST)) {

    $deleteList = explode(',',htmlspecialchars($_POST['deleteList']));
    $startList = explode(',',htmlspecialchars($_POST['startList']));
    $completeList = explode(',',htmlspecialchars($_POST['completeList']));

    $connection = mysqli_connect("localhost", $username, $password, $database, 8889);

    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }

    for ($i = 0; $i <= sizeof($deleteList); $i++) {
       $query = "DELETE FROM Problems WHERE id = $deleteList[$i]";
       $result = mysqli_query($connection, $query);
    }

    for ($j = 0; $j <= sizeof($startList); $j++) {
        $query1 = "UPDATE Problems SET problem_status = 'Started' WHERE id = $startList[$j] ";
        $result = mysqli_query($connection, $query1);
    }

    for ($k = 0; $k <= sizeof($completeList); $k++) {
        $query2 = "UPDATE Problems SET problem_status = 'Completed' WHERE id = $completeList[$k] ";
        $result = mysqli_query($connection, $query2);
    }

    $connection->close();


}