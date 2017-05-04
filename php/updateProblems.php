<?php
header('Location: ../page/admin.php');
require_once('phpsqlinfo_dbinfo.php');

if (!empty($_POST)) {

    $deleteList = explode(',', htmlspecialchars($_POST['deleteList']));
    $startList = explode(',', htmlspecialchars($_POST['startList']));
    $completeList = explode(',', htmlspecialchars($_POST['completeList']));

    $connection = mysqli_connect("localhost", $username, $password, $database, 8889);

    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }

    for ($i = 0; $i <= sizeof($deleteList); $i++) {
        $cleanQuery = "SELECT file FROM Problems where id= $deleteList[$i]";
        $res = mysqli_query($connection, $cleanQuery);
        while($fileToDelete = mysqli_fetch_assoc($res)) {
            //echo $fileToDelete['file'];
            unlink("../auth/uploads/" . $fileToDelete['file']);
        }
        $query = "DELETE FROM Problems WHERE id = $deleteList[$i]";
        $result = mysqli_query($connection, $query);

    }

    for ($j = 0; $j <= sizeof($startList); $j++) {
        $query1 = "UPDATE Problems SET problem_status = 'Started' WHERE id = $startList[$j] ";
        $start_time = "UPDATE problem_timelines SET start_timestamp = NOW() WHERE id = $startList[$j]";
        $result = mysqli_query($connection, $query1);
        if (sizeof($startList > 0)) {
            $result2 = mysqli_query($connection, $start_time);
        }
    }

    for ($k = 0; $k <= sizeof($completeList); $k++) {
        $query3 = "UPDATE Problems SET problem_status = 'Completed' WHERE id = $completeList[$k] ";
        $complete_time = "UPDATE problem_timelines SET complete_timestamp = NOW() where id = $completeList[$k]";
        $result = mysqli_query($connection, $query3);
        if (sizeof($completeList > 0)) {
            $result = mysqli_query($connection, $complete_time);
        }

    }

    $connection->close();


}