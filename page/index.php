<?php
session_start();
require_once '../php/class.user.php';
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
        <div class='alert alert-danger'>
            This account is not activated! Please check your inbox for an email about activating your account.
        </div>
        <?php
    };
    ?>
    <div class="row">
        <div class="col-lg-6 col-md-6">
            <form class="form-signin" method="post">
                <?php
                if(isset($_GET['error']))
                {
                    ?>
                    <div class='alert alert-danger'>
                        <strong>Sorry, that doesn't seem to be correct! Did you mistype something?</strong>
                    </div>
                    <?php
                };
                ?>
                <h2 class="form-signin-heading">Please log in to report problems.</h2>

                <hr />

                <div class="form-group">
                    <label for="txtemail">Email Address</label>
                    <input type="email" class="input-block-level form-control" placeholder="Email address" name="txtemail" required />
                </div>
                <div class="form-group">
                    <label for="txtupass">Password</label>
                    <input type="password" class="input-block-level form-control" placeholder="Password" name="txtupass" required />
                </div>

                <hr />

                <button class="btn btn-large btn-primary" type="submit" name="btn-login">Log In</button>
                <a href="signup.php" class="btn btn-large">Sign Up</a><hr />
                <a href="fpass.php">Lost your Password?</a>

            </form>
        </div>
    </div>

</div> <!-- /container -->

</body>
</html>