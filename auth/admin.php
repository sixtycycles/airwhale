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
        
            <h1>Administer Problems</h1>

            <form method="POST" action="updateProblems.php">
                        

                <p>
                    <button type="submit" class="btn btn-primary" id="saveChanges" formaction="updateProblems.php">
                        Save
                    </button>

                    <a href="download.php">
                        <button type="button" class="btn btn-primary" id="download">
                            Download Problems
                            <span class="glyphicon glyphicon-download"></span>
                        </button>
                    </a>
                </p>

                <div id="problemList" class="">

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
                        $query = "SELECT *
                            FROM Problems
                            INNER JOIN tbl_problem_types ON (Problems.type_id=tbl_problem_types.type_id)
                            ORDER BY id ASC 
                            ;";

                        $result = mysqli_query($connection, $query);

                        if (!$result) {
                            die('Invalid query: ' . mysqli_error($connection));
                        }


                        //loops over all problems sort by type
                        while ($row = @mysqli_fetch_assoc($result)) {
                            // Make a new panel for each problem
                            echo "<div class='panel panel-default' id='" . $row['id'] . "'> " .

                                "<div class='panel-heading clearfix'>" .
                                    "<div class='pull-left'>" .
                                        // "<h3 class='panel-title pull-left'>Panel title</h3>" . "<br />" .
                                        "<strong>ID</strong>: " . $row['id'] . "<br />" .
                                        "<strong>Type</strong>: " . $row['type_name'] . "" .
                                    "</div>" .

                                    "<div class='btn-group pull-right'>" .
                                        "<button class='btn btn-sm btn-primary' onclick='beginProblem(\"" . $row['id'] . "\")'>Start</button>" .
                                        "<button class='btn btn-sm btn-success' onclick='resolveProblem(\"" . $row['id'] . "\")'>Complete</button>" .
                                        "<button class='btn btn-sm btn-danger' onclick='deleteProblem(\"" . $row['id'] . "\")'>Delete</button>" .
                                    "</div>" .
                                
                                "</div>" .

                                "<div class='panel-body'> " .
                                    //"<div class='col-sm-8 col-md-6 col-lg-4'> " .
                                    "<strong>Name:</strong> <p>" . $row['name'] . "</p>" .
                                    "<strong>Status:</strong><p> " . $row['problem_status'] . "</p>" .
                                    "<strong>Time</strong> <p>" . $row['timestamp'] . "</p>" .
                                    "<strong>Description:</strong><p> " . $row['description'] . "</p>" .

                                    // Add photo row if photo is uploaded
                                    ($row['file'] ? "<strong>Photo of Problem:</strong><br /> <img class='img-fluid img-thumbnail' src='uploads/" . $row['file'] . "'>" : "" ) .
                                
                                "</div>" .

                            "</div>";
                        };


                        ?>

                        <div class='form' style="visibility: hidden">
                            <input name="deleteList" id="deleteList" value="">
                            <input name="startList" id="startList" value="">
                            <input name="completeList" id="completeList" value="">
                        </div>

                </div> <!-- End problemList -->
            </form>

        </div>
    </div>

    <!-- Scripts -->
    <script type="text/javascript">
        var deleteList = [];
        var startList = [];
        var completeList = [];

        function deleteProblem(id) {
            deleteList.push(id);
            document.getElementById(id).innerHTML = "Problem #" + id + " Deleted!";
            document.getElementById('deleteList').setAttribute('value', deleteList);
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