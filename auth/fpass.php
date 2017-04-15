<?php
session_start();
require_once 'class.user.php';
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

        $message= "
       Hello , $email
       <br /><br />
       Someone has requested a password reset for this your account. if that was you, great!<br />
       Click Following Link To Reset Your Password 
       <br /><br />
       <a href='localhost/auth/resetpass.php?id=$id&code=$code'>click here to reset your password</a>
       <br /><br />
       if that wasnt you, ignore this email. 
       thank you,
       Team Leader
       ";
        $subject = "Password Reset";

        $user->send_mail($email,$message,$subject);

        $msg = "<div class='alert alert-success'>
     <button class='close' data-dismiss='alert'>&times;</button>
     We've sent an email to $email.
                    Please follow the instructions in the email to reset your password. 
      </div>";
    }
    else
    {
        $msg = "<div class='alert alert-danger'>
     <button class='close' data-dismiss='alert'>&times;</button>
     <strong>Sorry!</strong>  we dont have an account with that email. did you want to <a href='signup.php'>create an account</a>? 
       </div>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot password?</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
          integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
            integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
            crossorigin="anonymous"></script>
</head>
<body id="login">
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
                        <li> <a href="home.php">Report Problems</a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="logout.php">logout</a></li>
                    </ul>
                </li>

            </ul>


        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>
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