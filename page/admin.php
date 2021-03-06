<?php
session_start();
require_once '../php/class.user.php';
$user_home = new USER();
if (!$user_home->is_admin()) {
    $user_home->redirect('index.php');
}
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
    <h1>Administer Problems</h1>
    <!-- instructions area-->
    <div class="row">
        <div class="col-md-8">
            <h3>Instructions</h3>
            <p>
                To administer a problem, choose one of the three buttons next to the problem.
                You can delete, mark as started, or mark as completed. You can click the
                <strong>Save</strong> button at the top of the page to submit changes.
                To abandon changes, just refresh the page.
            </p>
            <p>
                You can also download a CSV of the problem records. all data except
                images are included as of this point.
            </p>
        </div>
    </div>

    <div class="row">

        <div class="col-md-8">

            <form method="POST">

                <p>
                    <button type="submit" class="btn btn-primary" id="saveChanges"
                            formaction="../php/updateProblems.php">
                        Save
                    </button>

                    <a href="../php/download.php">
                        <button type="button" class="btn btn-primary" id="download">
                            Download Problems
                            <span class="glyphicon glyphicon-download"></span>
                        </button>
                    </a>
                </p>

                <div class='form' style="visibility: hidden">
                    <input name="deleteList" id="deleteList" value="">
                    <input name="startList" id="startList" value="">
                    <input name="completeList" id="completeList" value="">
                </div>

            </form>

            <div id="problemList" class="">

                <?php
                $query = "SELECT *
                          FROM Problems
                          INNER JOIN tbl_problem_types ON (Problems.type_id = tbl_problem_types.type_id)
                          INNER JOIN problem_timelines ON (Problems.id = problem_timelines.id)
                          ORDER BY Problems.id ASC;";

                $stmt = $user_home->runQuery($query);
                $success = $stmt->execute();

                if(!$success){
                    die("Could not query the server!");
                }

                //loops over all problems sort by type
                foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
                    // Make a new panel for each problem
                    $image_path = "../auth/uploads/${row['file']}";
                    echo "" .
                        "<div class='panel panel-default' id='" . $row['id'] . "'> " .

                            "<div class='panel-heading clearfix'>" .
                                "<div class='pull-left'>" .
                                    // "<h3 class='panel-title pull-left'>Panel title</h3>" . "<br />" .
                                    "<strong>Type:</strong> ${row['type_name']}<br />" .
                                    "<strong>Status:</strong> " . $row['problem_status'] .
                                "</div>" .

                                "<div class='btn-group pull-right'>" .
                                    "<button class='btn btn-sm btn-primary' onclick='beginProblem(\"${row['id']}\")'>Start</button>" .
                                    "<button class='btn btn-sm btn-success' onclick='resolveProblem(\"${row['id']}\")'>Complete</button>" .
                                    "<button class='btn btn-sm btn-danger' onclick='deleteProblem(\"${row['id']}\")'>Delete</button>" .
                                "</div>" .

                            "</div>" .

                            "<div class='panel-body'>" .
                                // Add photo row if photo is uploaded
                                ($row['file'] ?
                                    "<a href='${image_path}'>
                                        <img class='img-fluid img-thumbnail' style='float: right; max-width: 40%; min-width: 25%; height: auto;' src='${image_path}'>
                                     </a>" : "") .
                                "<strong>Submitted by</strong> ${row['name']} <strong>on</strong> ${row['create_timestamp']}<br />" .
                                ($row['start_timestamp'] ? "<strong>Project Start Date: </strong> ${row['start_timestamp']}<br />" : "").
                                ($row['complete_timestamp'] ? "<strong>Project Completion Date: </strong>${row['complete_timestamp']}<br />" : "" ).
                                "<strong>ID</strong>: ${row['id']}<br />" .
                                "<strong>Description</strong><p> ${row['description']}</p>" .
                            "</div>" .
                        "</div>";
                };

                ?>

            </div> <!-- End problemList -->

        </div>
    </div>

    <!-- Scripts -->
    <script type="text/javascript">
        var deleteList = [];
        var startList = [];
        var completeList = [];

        function deleteProblem(id) {

            var conf = confirm("Would you like to delete this problem?");

            if (conf) {
                deleteList.push(id);
                document.getElementById(id).innerHTML = "Problem #" + id + " deleted!";
                document.getElementById('deleteList').setAttribute('value', deleteList);
            }

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