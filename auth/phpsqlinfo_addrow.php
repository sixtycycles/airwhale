<?php
header('Location: index.php');
require_once('phpsqlinfo_dbinfo.php');

if (!empty($_POST)) {
// Gets data from URL parameters.
    $name = htmlspecialchars($_POST['name']);
    $description = htmlspecialchars($_POST['description']);
    $lat = htmlspecialchars($_POST['lat']);
    $lon = htmlspecialchars($_POST['lng']);
    $type = htmlspecialchars($_POST['type']);
//file handling
    $file = rand(1000, 100000) . "-" . $_FILES['file']['name'];
    $file = rand(1000, 100000) . "-" . $_FILES['file']['name'];
    $file_loc = $_FILES['file']['tmp_name'];
    $file_size = $_FILES['file']['size'];
    $file_type = $_FILES['file']['type'];
    $folder = "uploads/";

// Opens a connection to a MySQL server.
    $connection = mysqli_connect("localhost", $username, $password, $database, 8889);

    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }
// Inserts new row with place data, controll for if file is uploaded or not.

    if($_FILES['file']['name']!="") {
        move_uploaded_file($file_loc, $folder . $file);
        $imgQuery = "INSERT INTO tbl_uploads (file,type,size) VALUES('$file','$file_type','$file_size')";
        $result2 = mysqli_query($connection, $imgQuery);

        $query = "INSERT INTO Problems ( name, lat, lon, description, type, file ) VALUES ( '$name','$lat', '$lon','$description', '$type','$file');";
    }else{
        $query = "INSERT INTO Problems ( name, lat, lon, description, type) VALUES ( '$name','$lat', '$lon','$description', '$type');";
    }


    $result = mysqli_query($connection, $query);

    $connection->close();
}
?>

