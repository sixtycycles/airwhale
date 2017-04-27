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
    <!-- Import common header -->
    <?php require_once "../partials/head.php"; ?>
    <title>Admin Portal | Orono Problem Reporter</title>
</head>

<body>

<!-- Import navbar -->
<?php require_once "../partials/navbar.php"; ?>

<div class="container-fluid">
    <div class="">

        <div class="col-md-8 col=lg-8">
            <div id="problemList" class="panel panel-default ">
                <form method="POST" action="updateProblems.php">
                    <div class="panel-heading">
                        <h1>Administer Problems</h1>
                        
                        <input type="submit" class="btn btn-default" id="saveChanges" value="save"
                           formaction="updateProblems.php">
                        <a href="download.php"><input type="button" class="btn btn-default" id="download"
                                                    value="Download Problems"></a>
                    </div>
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


                        //loops over all problems sort by type
                        while ($row = @mysqli_fetch_assoc($result)) {
                            echo "<div class='panel panel-default' id='" . $row['id'] . "'> ";
                            echo
                                "<div class='navbar' >" .
                                " <div class='navbar-brand' >" .
                                "ID: " . $row['id'] . "<br />" .
                                " Type: " . $row['type'] . "</div></div>" .

                                "<ul class='nav nav-pills' >" .
                                "<li role=\"presentation\"><button  onclick='deleteProblem(\"" . $row['id'] . "\")'> Delete Problem </button></li>" .
                                "<li role=\"presentation\"><button onclick='beginProblem(\"" . $row['id'] . "\")'> Started Problem </button></li>" .
                                "<li role=\"presentation\"><button  onclick='resolveProblem(\"" . $row['id'] . "\")'> Completed Problem </button></li>" .
                                "</ul><hr /> " .


                                "<div class='panel-body'> " .
                                //"<div class='col-sm-8 col-md-6 col-lg-4'> " .
                                "<strong>Name:</strong> <p>" . $row['name'] . "</p>" .
                                "<strong>Status:</strong><p> " . $row['problem_status'] . "</p>" .
                                "<strong>Time</strong> <p>" . $row['timestamp'] . "</p>" .
                                //"</div>".
                                //"<div  class='col-sm-8 col-md-6 col-lg-4' >" .
                                "<strong>Photo of Problem:</strong><br /> <img class='img-fluid img-thumbnail' src='uploads/" . $row['file'] . "'>" .
                                //"</div>" .
                                "</div> ";
                            echo "</div>";
                        };


                        ?>

                    </div>
                    <div class='form' style="visibility: hidden">
                        <input name="deleteList" id="deleteList" value="">
                        <input name="startList" id="startList" value="">
                        <input name="completeList" id="completeList" value=""></div>
                </form>
            </div>
        </div>
    </div>
<!-- Scripts -->
    <script type="text/javascript">
        var dlist = [];
        var startList = [];
        var completeList = [];

        function deleteProblem(id) {
            dlist.push(id);
            document.getElementById(id).innerHTML = "Problem #" + id + " Deleted!";
            document.getElementById('deleteList').setAttribute('value', dlist);
        }

        function beginProblem(id) {
            startList.push(id);
            document.getElementById(id).innerHTML = "Problem #" + id + " started!";
            document.getElementById('startList').setAttribute('value', startList);
        }

        function resolveProblem(id) {
            completeList.push(id);
            document.getElementById(id).innerHTML = "Problem #" + id + " completed!";
            document.getElementById('completeList').setAttribute('value', completeList);
        }

    </script>
<!--END Scripts -->
</div>

</body>

</html>