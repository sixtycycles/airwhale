<?php
session_start();
require_once 'class.user.php';
$user_login = new USER();

if($user_login->is_logged_in()!="")
{
    $user_login->redirect('home.php');
}

if(isset($_POST['btn-login']))
{
    $email = trim($_POST['txtemail']);
    $upass = trim($_POST['txtupass']);

    if($user_login->login($email,$upass))
    {
        $user_login->redirect('home.php');
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <!-- Import common header -->
    <?php require_once "../partials/head.php"; ?>
    <title>Login | Orono Problem Reporter</title>
</head>

<body id="login">

<!-- Import navbar -->
<?php require_once "../partials/navbar.php"; ?>

<div class="container">
    <?php
    if(isset($_GET['inactive']))
    {
        ?>
        <div class='alert alert-error'>
            <button class='close' data-dismiss='alert'>&times;</button>
            <strong>Sorry!</strong> This Account is not Activated Go to your Inbox and Activate it.
        </div>
        <?php
    }
    ?>
    <form class="form-signin" method="post">
        <?php
        if(isset($_GET['error']))
        {
            ?>
            <div class='alert alert-success'>
                <button class='close' data-dismiss='alert'>&times;</button>
                <strong>Sorry that doesn't seem to be correct! Did you mistype something?</strong>
            </div>
            <?php
        }
        ?>
        <h2 class="form-signin-heading">Please log in to report problems</h2><hr />
        <input type="email" class="input-block-level" placeholder="Email address" name="txtemail" required />
        <input type="password" class="input-block-level" placeholder="Password" name="txtupass" required />
        <hr />
        <button class="btn btn-large btn-primary" type="submit" name="btn-login">Log In</button>
        <a href="signup.php" class="btn btn-large">Sign Up</a><hr />
        <a href="fpass.php">Lost your Password?</a>
    </form>

</div> <!-- /container -->

</body>
</html>