<?php
require_once 'class.user.php';
$user = new USER();

if (empty($_GET['id']) && empty($_GET['code'])) {
    $user->redirect('index.php');
}

if (isset($_GET['id']) && isset($_GET['code'])) {
    $id = base64_decode($_GET['id']);
    $code = $_GET['code'];

    $stmt = $user->runQuery("SELECT * FROM tbl_users WHERE userID=:uid AND tokenCode=:token");
    $stmt->execute(array(":uid" => $id, ":token" => $code));
    $rows = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($stmt->rowCount() == 1) {
        if (isset($_POST['btn-reset-pass'])) {
            $pass = $_POST['pass'];
            $cpass = $_POST['confirm-pass'];

            if ($cpass !== $pass) {
                $msg = "<div class='alert alert-block'>
      <button class='close' data-dismiss='alert'>&times;</button>
      <strong>Sorry!</strong>  Password Doesn't match. 
      </div>";
            } else {
                $stmt = $user->runQuery("UPDATE tbl_users SET userPass=:upass WHERE userID=:uid");
                $stmt->execute(array(":upass" => sha1($cpass), ":uid" => $rows['userID']));

                $msg = "<div class='alert alert-success'>
      <button class='close' data-dismiss='alert'>&times;</button>
      Password Changed.
      </div>";
                header("refresh:5;index.php");
            }
        }
    } else {
        exit;
    }


}

?>
<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reset Password | Orono Problem Reporter</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
          integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
            integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
            crossorigin="anonymous"></script>

</head>
<body id="login">
<div class="container">
    <div class='alert alert-success'>
        <strong>Hello !</strong> <?php echo $rows['userName'] ?> Did you forget your password? Reset it here:
    </div>
    <form class="form-signin" method="post">
        <h3 class="form-signin-heading">Password Reset.</h3>
        <hr/>
        <?php
        if (isset($msg)) {
            echo $msg;
        }
        ?>
        <input type="password" class="input-block-level" placeholder="New Password" name="pass" required/>
        <input type="password" class="input-block-level" placeholder="Confirm New Password" name="confirm-pass"
               required/>
        <hr/>
        <button class="btn btn-large btn-primary" type="submit" name="btn-reset-pass">Reset Your Password</button>

    </form>

</div> <!-- /container -->

</body>
</html>