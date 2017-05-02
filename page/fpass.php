<?php
session_start();
require_once '../php/class.user.php';
$user = new USER();

if($user->is_logged_in()!="")
{
    $user->redirect('home.php');
}

if(isset($_POST['btn-submit']))
{
    $email = $_POST['txtemail'];

    $stmt = $user->runQuery("SELECT userID FROM tbl_users WHERE userEmail=:email LIMIT 1");
    $stmt->execute(array(":email"=>$email));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if($stmt->rowCount() == 1)
    {
        $id = base64_encode($row['userID']);
        $code = md5(uniqid(rand()));

        $stmt = $user->runQuery("UPDATE tbl_users SET tokenCode=:token WHERE userEmail=:email");
        $stmt->execute(array(":token"=>$code,"email"=>$email));

        $path = (@$_SERVER["HTTPS"] == "on") ? "https://" : "http://";
        $path .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER['SERVER_PORT'] . dirname($_SERVER["PHP_SELF"]);
        
        $message= "
            Hello, ${email}!
            <br />
            <br />
            A password reset has been requested for your account. If this was not you, please
            reset your password immediately. 
            <br />
            <a href='${path}/resetpass.php?id=$id&code=$code'>Click here to reset your password.</a>
            <br />
            <br />
            Thank you!
        ";
        $subject = "Password Reset";

        $user->send_mail($email,$message,$subject);

        $msg = "
            <div class='alert alert-success'>
            <button class='close' data-dismiss='alert'>&times;</button>
            We've sent an email to $email.
            Please follow the instructions in the email to reset your password. 
            </div>
        ";
    }
    else
    {
        $msg = "
            <div class='alert alert-danger'>
            <button class='close' data-dismiss='alert'>&times;</button>
            <strong>Sorry!</strong>  we dont have an account with that email. did you want to <a href='signup.php'>create an account</a>? 
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
    <title>Reset Password | Orono Problem Reporter</title>
</head>

<body id="login">

<!-- Import navbar -->
<?php require_once "../partials/navbar.php"; ?>

<div class="container">

    <form class="form-signin" method="post">
        <h2 class="form-signin-heading">Forgot your password? no problem!</h2><hr />

        <?php
        if(isset($msg))
        {
            echo $msg;
        }
        else
        {
            ?>
            <div class='alert alert-info'>
                Please enter your email address. You will receive a link to create a new password via email.
            </div>
            <?php
        }
        ?>

        <input type="email" class="input-block-level" placeholder="forgot@password.com" name="txtemail" required />
        <hr />
        <button class="btn btn-danger btn-primary" type="submit" name="btn-submit">Recover password</button>
    </form>

</div> <!-- /container -->

</body>
</html>