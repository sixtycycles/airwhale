<?php
require_once '../php/class.user.php';
$user = new USER();

if(empty($_GET['id']) && empty($_GET['code']))
{
    $user->redirect('index.php');
}

if(isset($_GET['id']) && isset($_GET['code']))
{
    $id = $_GET['id'];//base64_decode($_GET['id']);
    $code = $_GET['code'];

    $statusY = "Y";
    $statusN = "N";

    $stmt = $user->runQuery("SELECT userID,userStatus FROM tbl_users WHERE userID=:uID AND tokenCode=:code LIMIT 1");
    $stmt->execute(array(":uID"=>$id,":code"=>$code));
    $row=$stmt->fetch(PDO::FETCH_ASSOC);
    if($stmt->rowCount() > 0)
    {
        if($row['userStatus']==$statusN)
        {
            $stmt = $user->runQuery("UPDATE tbl_users SET userStatus=:status WHERE userID=:uID");
            $stmt->bindparam(":status",$statusY);
            $stmt->bindparam(":uID",$id);
            $stmt->execute();

            $msg = "
                <div class='alert alert-success'>
                    Your account is now activated. <a href='index.php'>Log in here.</a>
                </div>
                ";
        }
        else
        {
            $msg = "
                <div class='alert alert-error'>
                    Your account is already activated. <a href='index.php'>Log in here.</a>
                </div>
                ";
        }
    }
    else
    {
        $msg = "
            <div class='alert alert-error'>
                No account found. <a href='signup.php'>Sign up here.</a>
            </div>
            ";
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <!-- Import common header -->
    <?php require_once "../partials/head.php"; ?>
    <title>Confirm Registration</title>
</head>

<body id="login">

<!-- Import navbar -->
<?php require_once "../partials/navbar.php"; ?>

<div class="container">

    <?php if(isset($msg)) { echo $msg; } ?>
</div> <!-- /container -->

</body>
</html>