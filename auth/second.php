<?php
//header('Location: home.php');
session_start();
require_once('phpsqlinfo_dbinfo.php');

if (!empty($_GET)) {

    $problems_id = htmlspecialchars($_GET['id']);
    //var_dump($_SESSION['userSession']);
    $uID =  $_SESSION['userSession'];
    //echo $uID;
    $connection = mysqli_connect("localhost", $username, $password, $database, 8889);

    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }

    $selectQuery = "SELECT likes from Problems where id = $problems_id";
    $result = mysqli_query($connection, $selectQuery);

    $row = mysqli_fetch_assoc($result);

    $likes = $row['likes'] + 1;


    //set likes to existing likes plus 1
    if (!empty($row)) {

        //check if user has liked already
        $haslikedQuery = "Select *
                              from likes
                              where user = $uID AND problem_id = $problems_id;";
        $resultLikes = mysqli_query($connection, $haslikedQuery);

        $likeRow = mysqli_fetch_assoc($resultLikes);

        echo $likeRow['user'];

        if (empty($likeRow['user'])) {
            $updateQuery = "UPDATE Problems SET likes = $likes WHERE id = $problems_id";
            $result2 = mysqli_query($connection, $updateQuery);

            $updateLikes = "INSERT INTO likes (user, problem_id ) VALUES( $uID, $problems_id )";
            $res = mysqli_query($connection, $updateLikes);
            echo $likes;
        } else {
            //echo $uID;
        }
    }

    $connection->close();
}



