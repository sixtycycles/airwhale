<?php
session_start();
require_once 'class.user.php';
$user_home = new USER();
if (!$user_home->is_admin()) {
    $user_home->redirect('index.php');
}
$stmt = $user_home->runQuery("SELECT * FROM tbl_users WHERE userID=:uid");
$stmt->execute(array(":uid" => $_SESSION['userSession']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Admin Portal | Orono Problem Reporter</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
          integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
            integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
            crossorigin="anonymous"></script>
</head>

<body>
<nav class="navbar navbar-default">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                    data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">Town Of Orono Issue Tracker</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">

                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                       aria-expanded="false">Menu <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="home.php">Report Problems</a></li>
                        <?php
                        if ($_SESSION['isAdmin']) {
                            echo "<li> <a href='admin.php'>Admin Portal</a></li>";
                        }
                        ?>
                        <li role="separator" class="divider"></li>
                        <li><a href="logout.php">logout</a></li>
                    </ul>
                </li>

            </ul>

        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>

<div class="container-fluid">
    <div class="row">

        <div class="col-md-8 col=lg-8">
            <div id="problemList" class="panel panel-default ">
                <form method="POST" action="deleteProblem.php">
                <input type="submit" class="btn btn-default" id="saveChanges" value="save" formaction="deleteProblem.php" >
                    <a href="download.php"><input type="button" class="btn btn-default" id="download" value="Download Problems"></a>
                <div class="panel-heading">Administer Problems</div>
                <div class="panel-body">

                    <?php
                    require_once("phpsqlinfo_dbinfo.php");

                    $connection = mysqli_connect('localhost', $username, $password, $database, $port);
                    if (!$connection) {
                        die('Not connected : ' . mysqli_error($connection));
                    }

                    $db_selected = mysqli_select_db($connection, $database);
                    if (!$db_selected) {
                        die ('Can\'t use db : ' . mysqli_error($connection));
                    }
                    //$query = "SELECT id,type FROM Problems GROUP BY type ASC;";
                    $query = "SELECT * FROM Problems ORDER BY id ASC;";

                    $result = mysqli_query($connection, $query);

                    if (!$result) {
                        die('Invalid query: ' . mysqli_error($connection));
                    }

                    echo "<div class=''>";
                    //loops over all problems sort by type
                    while ($row = @mysqli_fetch_assoc($result)) {
                        echo "<div class='panel panel-default' id='" . $row['id'] . "'>";
                        echo
                            "<div class='panel-heading'>" .
                            "ID: " . $row['id'] . "<br />" .
                            "Type: " . $row['type'] . "<br />" .
                            "</div>" .

                            "<div class='panel-body'> " .
                            "Name: " . $row['name'] . "<br />" .
                            "Description: " . $row['description'] . "<br />" .
                            "Status: " . $row['problem_status'] . "<br />" .
                            "Time " . $row['timestamp'] . "<br />" .
                            "Photo of Problem: <img src='uploads/" . $row['file'] . "'>" .
                            "</div>";
                        echo '<button class=\'btn btn-danger\' onclick=\'deleteProblem("' . $row['id'] . '")\'> Delete Problem </button>';
                        echo "</div> <br />";

                    };
                    echo "</div>";

                    ?>

                </div>
                <div class='form' style="visibility: hidden" ><input name="deleteList" id="deleteList" value=""> </div>
                </form>
            </div>
        </div>

<!--        <div class="col-md-4 col-lg-4">-->
<!--            <div class="panel panel-default">-->
<!--                <div id="map" style="height:500px; width:100%"></div>-->
<!--            </div>-->
<!--        </div>-->


    </div>

    <script type="text/javascript">
        var dlist = [];

        function deleteProblem(id) {
           dlist.push(id);
           document.getElementById(id).innerHTML = "Problem #" + id +" Deleted!";
           document.getElementById('deleteList').setAttribute('value',dlist);
        }



    </script>

</div>

</body>

</html>