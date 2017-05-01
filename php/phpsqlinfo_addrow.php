<?php
header('Location: ../page/index.php');
require_once('phpsqlinfo_dbinfo.php');

if (!empty($_POST)) {
// Gets data from URL parameters.
    $name = trim(($_POST['name']));
    $description = trim(($_POST['description']));
    $lat = trim(($_POST['lat']));
    $lon = trim(($_POST['lng']));
    $type = trim(($_POST['type']));
//file handling
    $file = rand(1000, 100000) . "-" . $_FILES['file']['name'];
//    $file = rand(1000, 100000) . "-" . $_FILES['file']['name'];
    $file_loc = $_FILES['file']['tmp_name'];
    $file_size = $_FILES['file']['size'];
    $file_type = $_FILES['file']['type'];
    $folder = "../auth/uploads/";

// Opens a connection to a MySQL server.
    $connection = mysqli_connect("localhost", $username, $password, $database, 8889);

    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }
    //escape ...ALL THE THINGS: this is probably overkill, but for names and such i stops chars breaking things
    $name = mysqli_real_escape_string($connection,$name);
    $description = mysqli_real_escape_string($connection,$description);
    $lat = mysqli_real_escape_string($connection,$lat);
    $lon = mysqli_real_escape_string($connection,$lon);
    $type = mysqli_real_escape_string($connection,$type);

    // Inserts new row with place data, controll for if file is uploaded or not.


    if($_FILES['file']['name']!="") {
        move_uploaded_file($file_loc, $folder . $file);
//        $imgQuery = "INSERT INTO tbl_uploads (file,type,size) VALUES('$file','$file_type','$file_size')";
//        $result2 = mysqli_query($connection, $imgQuery);

        $query = "INSERT INTO Problems ( name, lat, lon, description, type_id, file ) VALUES ( '$name','$lat', '$lon','$description', '$type','$file');";
    }else{
        $query = "INSERT INTO Problems ( name, lat, lon, description, type_id) VALUES ( '$name','$lat', '$lon','$description', '$type');";
    }


    $result = mysqli_query($connection, $query);

    $connection->close();
}