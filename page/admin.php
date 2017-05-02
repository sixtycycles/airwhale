<?php
session_start();
require_once '../php/class.user.php';
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
    <div class="row">

        <div class="col-md-6 col=lg-6">
        
            <h1>Administer Problems</h1>

            <form method="POST" action="../php/updateProblems.php">
                        

                <p>
                    <button type="submit" class="btn btn-primary" id="saveChanges" formaction="../php/updateProblems.php">
                        Save
                    </button>

                    <a href="../php/download.php">
                        <button type="button" class="btn btn-primary" id="download">
                            Download Problems
                            <span class="glyphicon glyphicon-download"></span>
                        </button>
                    </a>
                </p>

                <div id="problemList" class="">

                        <?php
                        require_once("../php/phpsqlinfo_dbinfo.php");

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
                            $image_path = "../auth/uploads/${row['file']}";
                            echo "<div class='panel panel-default' id='" . $row['id'] . "'> " .

                                "<div class='panel-heading clearfix'>" .
                                    "<div class='pull-left'>" .
                                        // "<h3 class='panel-title pull-left'>Panel title</h3>" . "<br />" .
                                        //"<strong>ID</strong>: " . $row['id'] . "<br />" .
                                        "<strong>Type:</strong> " . $row['type_name'] . "<br />" .
                                        "<strong>Status:</strong> " . $row['problem_status'] .
                                    "</div>" .

                                    "<div class='btn-group pull-right'>" .
                                        "<button class='btn btn-sm btn-primary' onclick='beginProblem(\"" . $row['id'] . "\")'>Start</button>" .
                                        "<button class='btn btn-sm btn-success' onclick='resolveProblem(\"" . $row['id'] . "\")'>Complete</button>" .
                                        "<button class='btn btn-sm btn-danger' onclick='deleteProblem(\"" . $row['id'] . "\")'>Delete</button>" .
                                    "</div>" .
                                
                                "</div>" .

                                "<div class='panel-body'> " .
                                    //"<div class='col-sm-8 col-md-6 col-lg-4'> " .
                                     // Add photo row if photo is uploaded
                                    ($row['file'] ? "<a href='../auth/uploads/${row['file']}'><img class='img-fluid img-thumbnail' style='float: right; max-width: 40%; min-width: 25%; height: auto;' src='../auth/uploads/${row['file']}'></a>" : "" ) .
                                    "<strong>Submitted By</strong> <p>" . $row['name'] . "</p>" .
                                    //"<strong>Status:</strong><p> " . $row['problem_status'] . "</p>" .
                                    "<strong>Time</strong> <p>" . $row['timestamp'] . "</p>" .
                                    "<strong>Description</strong><p> " . $row['description'] . "</p>" .
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
        <!-- instructions area-->
        <div class="col-lg-4">
            <h3>Instructions</h3>
            <p>To administer a problem, choose one of the three buttons next to the problem.
                You can delete, mark as started, or mark as completed. You can click the 
                <strong>Save</strong> button at the top of the page to submit changes.
                To abandon changes, just refresh the page.
            </p>
            <p>You can also download a CSV of the problem records. all data except images are included as of this point. </p>
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