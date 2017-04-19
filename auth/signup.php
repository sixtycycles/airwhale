<?php
session_start();
require_once 'class.user.php';

$reg_user = new USER();

if($reg_user->is_logged_in()!="")
{
    $reg_user->redirect('home.php');
}


if(isset($_POST['btn-signup']))
{
    $uname = trim($_POST['txtuname']);
    $email = trim($_POST['txtemail']);
    $upass = trim($_POST['txtpass']);
    $code = md5(uniqid(rand()));

    $stmt = $reg_user->runQuery("SELECT * FROM tbl_users WHERE userEmail=:email_id");
    $stmt->execute(array(":email_id"=>$email));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if($stmt->rowCount() > 0)
    {
        $msg = "
        <div class='alert alert-error'>
    <button class='close' data-dismiss='alert'>&times;</button>
     <strong>Sorry!</strong>  email allready exists , Please Try another one
     </div>
     ";
    }
    else
    {
        if($reg_user->register($uname,$email,$upass,$code))
        {
            $id = $reg_user->lasdID();
            $key = base64_encode($id);
            $id = $key;

            $message = "     
      Hello $uname,
      <br /><br />
      Welcome to the Citizen Problem Reporter!<br/>
      To complete your registration  please , just click following link<br/>
      <br /><br />
      <a href='localhost/auth/verify.php?id=$id&code=$code'>Click HERE to Activate :)</a>
      <br /><br />
      Thanks,";

            $subject = "Confirm Registration";

            $reg_user->send_mail($email,$message,$subject);
            $msg = "
     <div class='alert alert-success'>
      <button class='close' data-dismiss='alert'>&times;</button>
      <strong>Success!</strong>  We've sent an email to $email.
                    Please click on the confirmation link in the email to create your account. 
       </div>
     ";
        }
        else
        {
            echo "sorry , Query could no execute...";
        }
    }
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Sign Up | Orono Problem Reporter</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
          integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
            integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
            crossorigin="anonymous"></script>

    <script>function check(){
        if (document.getElementById('pw1').value !== document.getElementById('pw2').value){
            window.alert('passwords do not match!');
            document.getElementById('pw1').value="";
            document.getElementById('pw2').value="";
        }

        }</script>
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

    <?php if(isset($msg)) echo $msg;  ?>
<div class="container">
    <div class="row">
        <div class="col-lg-6 col-md-6">
    <div class="form-group">
            <form method="post">
        <h2>Sign Up for an Account</h2><hr />
        <h4>Only users with accounts can enter problems</h4>
        <label for="txtuname" class="control-label">Pick a user name:</label>
        <input type="text" class="form-control" placeholder="JaneDoe2017" name="txtuname" required /><br />
        <label for="txtemail">please enter your email address:</label>
        <input type="email" class="form-control" placeholder="Email address" name="txtemail" required /><br />
        <label for="txtpass">pick a password:</label>
        <input type="password" class="form-control" placeholder="Password" name="txtpass" required id='pw1'/><br />
        <label for="txtpass2">enter it again:</label>
        <input type="password" class="form-control" placeholder="re-enter password" name="txtpass2" required id='pw2' onblur="check()"/><br />

        <hr />
        <button class="btn btn-large btn-primary" type="submit" name="btn-signup">Sign Up</button>
        <a href="index.php"  class="btn btn-large">Log In</a>
    </form>
    </div>
</div></div>
        <!-- /container -->
</div>
</body>
</html>